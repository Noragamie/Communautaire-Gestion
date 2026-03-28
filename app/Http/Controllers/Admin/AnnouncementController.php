<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AnnouncementMail;
use App\Models\Announcement;
use App\Models\User;
use App\Notifications\AnnouncementPublished;
use App\Services\ActivityLogger;
use App\Services\CommuneContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $filterCommuneId = $request->filled('commune_id') ? (int) $request->commune_id : null;
        $ids = CommuneContext::scopedCommuneIdsForQuery($filterCommuneId);
        $announcements = Announcement::with(['author', 'commune'])
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

        return view('admin.announcements.index', compact(
            'announcements',
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

        return view('admin.announcements.create', compact('communesForCreate'));
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
                $data['image'] = $request->file('image')->store('announcements', 'public');
            }

            $announcement = Announcement::create($data);

            if ($publish) {
                $this->notifyOperatorsInCommune($announcement);
            }

            ActivityLogger::log($publish ? 'announcement_published' : 'announcement_created', 'Announcement', $announcement->id, $announcement->title);
            $message = $publish ? 'Annonce publiée et opérateurs de la commune notifiés.' : 'Annonce enregistrée comme brouillon.';

            return redirect()->route('admin.announcements.index')->with('success', $message);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la création de l\'annonce.'])->withInput();
        }
    }

    public function edit(Announcement $announcement)
    {
        $this->ensureAnnouncementCommune($announcement);

        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $this->ensureAnnouncementCommune($announcement);

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
            $wasPublished = $announcement->is_published;
            $publish = $request->input('action') === 'publish';

            $data = $request->only('title', 'content');
            $data['is_published'] = $publish;
            $data['published_at'] = $publish ? ($announcement->published_at ?? now()) : null;

            if ($request->hasFile('image')) {
                if ($announcement->image) {
                    Storage::disk('public')->delete($announcement->image);
                }
                $data['image'] = $request->file('image')->store('announcements', 'public');
            }

            $announcement->update($data);

            if ($publish && ! $wasPublished) {
                $this->notifyOperatorsInCommune($announcement);
            }

            ActivityLogger::log($publish ? 'announcement_published' : 'announcement_updated', 'Announcement', $announcement->id, $announcement->title);
            $message = $publish ? 'Annonce publiée.' : 'Brouillon enregistré.';

            return redirect()->route('admin.announcements.index')->with('success', $message);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la mise à jour.'])->withInput();
        }
    }

    public function destroy(Announcement $announcement)
    {
        $this->ensureAnnouncementCommune($announcement);
        try {
            $label = $announcement->title;
            $id = $announcement->id;
            if ($announcement->image) {
                Storage::disk('public')->delete($announcement->image);
            }
            $announcement->delete();
            ActivityLogger::log('announcement_deleted', 'Announcement', $id, $label);

            return back()->with('success', 'Annonce supprimée.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression.']);
        }
    }

    private function notifyOperatorsInCommune(Announcement $announcement): void
    {
        $communeId = $announcement->commune_id;
        if (! $communeId) {
            return;
        }

        $users = User::query()
            ->where('is_active', true)
            ->where('role', 'operateur')
            ->where('commune_id', $communeId)
            ->whereHas('profile', fn ($q) => $q->where('status', 'approved'))
            ->get();

        foreach ($users as $user) {
            Mail::to($user->email)->send(new AnnouncementMail($announcement));
        }

        Notification::send($users, new AnnouncementPublished($announcement));
    }

    private function ensureAnnouncementCommune(Announcement $announcement): void
    {
        CommuneContext::authorizeBackofficeResourceCommune($announcement->commune_id);
    }
}
