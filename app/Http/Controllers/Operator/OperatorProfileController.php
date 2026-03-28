<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\Category;
use App\Models\Document;
use App\Services\ActivityLogger;
use App\Services\ProfileService;
use Illuminate\Support\Facades\Auth;

class OperatorProfileController extends Controller
{
    public function __construct(private ProfileService $service) {}

    public function show()
    {
        $profile = Auth::user()->profile()->with(['documents', 'category', 'user', 'modificationRequest'])->first();
        
        if (!$profile) {
            return redirect()->route('operator.profile.create')
                ->with('info', 'Veuillez créer votre profil pour accéder à votre espace.');
        }
        
        return view('operator.profile.show', compact('profile'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('operator.profile.create', compact('categories'));
    }

    public function store(ProfileRequest $request)
    {
        try {
            $this->service->submit($request, Auth::user());
            ActivityLogger::log('profile_created', 'Profile', null, Auth::user()->name);
            return redirect()->route('operator.profile.show')
                ->with('success', 'Profil soumis ! Vous recevrez un email de confirmation.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la soumission du profil.'])->withInput();
        }
    }

    public function edit()
    {
        $profile = Auth::user()->profile()->with('documents')->first();
        if (! $profile) {
            return redirect()->route('operator.profile.create');
        }
        $categories = Category::all();

        return view('operator.profile.edit', compact('profile', 'categories'));
    }

    public function update(ProfileRequest $request)
    {
        $profile = Auth::user()->profile;

        try {
            // Profil validé → demande de modification (profil reste visible)
            if ($profile && $profile->isApproved()) {
                if ($profile->modificationRequest()->where('status', 'pending')->exists()) {
                    return back()->withErrors(['error' => 'Une demande de modification est déjà en cours d\'examen. Attendez la décision de l\'administrateur.']);
                }
                $this->service->submitModificationRequest($request, $profile);
                ActivityLogger::log('modification_requested', 'Profile', $profile->id, Auth::user()->name);
                return redirect()->route('operator.profile.show')
                    ->with('success', 'Demande de modification envoyée. Votre profil reste visible pendant l\'examen.');
            }

            // Profil non validé → mise à jour directe (comportement existant)
            $this->service->submit($request, Auth::user());
            ActivityLogger::log('profile_updated', 'Profile', null, Auth::user()->name);
            return redirect()->route('operator.profile.show')
                ->with('success', 'Profil mis à jour et soumis pour re-validation.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la mise à jour du profil.'])->withInput();
        }
    }

    public function toggleContactVisible()
    {
        $profile = Auth::user()->profile;
        if (!$profile) return back();
        $profile->update(['contact_visible' => !$profile->contact_visible]);
        return back()->with('success', $profile->contact_visible ? 'Coordonnées visibles publiquement.' : 'Coordonnées masquées.');
    }

    public function destroyDocument(Document $document)
    {
        abort_unless($document->profile->user_id === Auth::id(), 403);
        try {
            $label = $document->original_name ?? $document->path;
            $id    = $document->id;
            $this->service->deleteDocument($document);
            ActivityLogger::log('document_deleted', 'Document', $id, $label);
            return back()->with('success', 'Document supprimé.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression du document.']);
        }
    }
}
