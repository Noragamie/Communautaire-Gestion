<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NewsletterMail;
use App\Models\Actuality;
use App\Models\Newsletter;
use App\Models\User;
use App\Notifications\ActualityPublished;
use App\Services\ActivityLogger;
use App\Services\CommuneContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class ActualityController extends Controller
{
    public function index(Request $request)
    {
        $filterCommuneId = $request->filled('commune_id') ? (int) $request->commune_id : null;
        $ids = CommuneContext::scopedCommuneIdsForQuery($filterCommuneId);
        $actualities = Actuality::with(['author', 'commune'])
            ->when(CommuneContext::needsTerritorialScope() && $ids !== [], fn ($q) => $q->whereIn('commune_id', $ids))
            ->when(CommuneContext::needsTerritorialScope() && $ids === [], fn ($q) => $q->whereRaw('1 = 0'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $user = $request->user();
        $showCommuneFilter = CommuneContext::shouldShowAdminCommuneListFilter();
        $managedCommunesForFilter = $showCommuneFilter
            ? $user->managedCommunes()->orderBy('name')->get()
            : collect();

        return view('admin.actualities.index', compact(
            'actualities',
            'showCommuneFilter',
            'managedCommunesForFilter'
        ));
    }

    public function create()
    {
        $communesForCreate = collect();
        $user = Auth::user();
        if ($user && $user->isAdmin() && CommuneContext::isAdminViewingAllManagedCommunes()) {
            $communesForCreate = $user->managedCommunes()->orderBy('name')->get();
        }

        return view('admin.actualities.create', compact('communesForCreate'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->isAdmin() && CommuneContext::isAdminViewingAllManagedCommunes()) {
            $request->validate([
                'commune_id' => 'required|integer|exists:communes,id',
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'image' => 'nullable|image|max:2048',
            ], [
                'commune_id.required' => 'Choisissez la commune concernée.',
                'title.required' => 'Le titre est requis.',
                'content.required' => 'Le contenu est requis.',
                'image.image' => 'Le fichier doit être une image.',
                'image.max' => 'L\'image ne doit pas dépasser 2 Mo.',
            ]);
            $cid = (int) $request->input('commune_id');
            if (! $user->canManageCommune($cid)) {
                return back()->withErrors(['commune_id' => 'Vous ne gérez pas cette commune.'])->withInput();
            }
        } else {
            $cid = CommuneContext::activeId();
            if ($cid === null) {
                return back()->withErrors(['error' => 'Aucune commune active.'])->withInput();
            }
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'image' => 'nullable|image|max:2048',
            ], [
                'title.required' => 'Le titre est requis.',
                'content.required' => 'Le contenu est requis.',
                'image.image' => 'Le fichier doit être une image.',
                'image.max' => 'L\'image ne doit pas dépasser 2 Mo.',
            ]);
        }

        try {
            $publish = $request->input('action') === 'publish';

            $data = $request->only('title', 'content');
            $data['user_id'] = Auth::id();
            $data['commune_id'] = $cid;
            $data['is_published'] = $publish;
            $data['published_at'] = $publish ? now() : null;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                // Stocker en base64 dans la DB (pas de fichier)
                $imageData = base64_encode(file_get_contents($file->getRealPath()));
                $mimeType = $file->getMimeType();
                $data['image_data'] = "data:{$mimeType};base64,{$imageData}";
                $data['image'] = $file->getClientOriginalName(); // Garder le nom pour référence
            }

            $actuality = Actuality::create($data);

            if ($publish) {
                $this->broadcastActuality($actuality);
            }

            ActivityLogger::log($publish ? 'actuality_published' : 'actuality_created', 'Actuality', $actuality->id, $actuality->title);
            $message = $publish ? 'Actualité publiée et newsletter envoyée.' : 'Actualité enregistrée comme brouillon.';

            return redirect()->route('admin.actualities.index')->with('success', $message);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la création de l\'actualité.'])->withInput();
        }
    }

    public function edit(Actuality $actuality)
    {
        $this->ensureActualityCommune($actuality);

        return view('admin.actualities.edit', compact('actuality'));
    }

    public function update(Request $request, Actuality $actuality)
    {
        $this->ensureActualityCommune($actuality);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ], [
            'title.required' => 'Le titre est requis.',
            'content.required' => 'Le contenu est requis.',
            'image.image' => 'Le fichier doit être une image.',
            'image.max' => 'L\'image ne doit pas dépasser 2 Mo.',
        ]);

        try {
            $wasPublished = $actuality->is_published;
            $publish = $request->input('action') === 'publish';

            $data = $request->only('title', 'content');
            $data['is_published'] = $publish;
            $data['published_at'] = $publish ? ($actuality->published_at ?? now()) : null;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                // Stocker en base64 dans la DB
                $imageData = base64_encode(file_get_contents($file->getRealPath()));
                $mimeType = $file->getMimeType();
                $data['image_data'] = "data:{$mimeType};base64,{$imageData}";
                $data['image'] = $file->getClientOriginalName();
            }

            $actuality->update($data);

            if ($publish && ! $wasPublished) {
                $this->broadcastActuality($actuality);
            }

            ActivityLogger::log($publish ? 'actuality_published' : 'actuality_updated', 'Actuality', $actuality->id, $actuality->title);
            $message = $publish ? 'Actualité publiée.' : 'Brouillon enregistré.';

            return redirect()->route('admin.actualities.index')->with('success', $message);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la mise à jour.'])->withInput();
        }
    }

    public function publish(Actuality $actuality)
    {
        $this->ensureActualityCommune($actuality);
        try {
            $actuality->update(['is_published' => true, 'published_at' => now()]);

            $this->broadcastActuality($actuality);

            ActivityLogger::log('actuality_published', 'Actuality', $actuality->id, $actuality->title);

            return back()->with('success', 'Actualité publiée et newsletter envoyée aux abonnés.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la publication.']);
        }
    }

    public function destroy(Actuality $actuality)
    {
        $this->ensureActualityCommune($actuality);
        try {
            $label = $actuality->title;
            $id = $actuality->id;
            $actuality->delete();
            ActivityLogger::log('actuality_deleted', 'Actuality', $id, $label);

            return back()->with('success', 'Actualité supprimée.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression.']);
        }
    }

    private function broadcastActuality(Actuality $actuality): void
    {
        Newsletter::where('subscribed', true)->each(function ($subscriber) use ($actuality) {
            Mail::to($subscriber->email)->send(new NewsletterMail($actuality, $subscriber->token));
        });
        $users = User::where('is_active', true)->whereNotIn('role', ['admin', 'agent_municipal'])->get();
        Notification::send($users, new ActualityPublished($actuality));
    }

    private function ensureActualityCommune(Actuality $actuality): void
    {
        CommuneContext::authorizeBackofficeResourceCommune($actuality->commune_id);
    }
}
