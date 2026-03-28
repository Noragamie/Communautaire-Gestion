<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Profile;
use App\Services\ActivityLogger;
use App\Services\CommuneContext;
use App\Services\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class AdminProfileController extends Controller
{
    public function __construct(private ProfileService $service) {}

    public function index(Request $request)
    {
        $query = Profile::with(['user.commune', 'category'])->latest();

        $filterCommuneId = $request->filled('commune_id') ? (int) $request->commune_id : null;
        $ids = CommuneContext::scopedCommuneIdsForQuery($filterCommuneId);
        if (CommuneContext::needsTerritorialScope()) {
            if ($ids === []) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereHas('user', fn ($q) => $q->whereIn('commune_id', $ids));
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('user', fn ($q) => $q->where('name', 'like', "%$s%")
                ->orWhere('email', 'like', "%$s%"));
        }

        $user = $request->user();
        $showCommuneFilter = CommuneContext::shouldShowAdminCommuneListFilter();
        $managedCommunesForFilter = $showCommuneFilter
            ? $user->managedCommunes()->orderBy('name')->get()
            : collect();

        return view('admin.profiles.index', [
            'profiles' => $query->paginate(15)->withQueryString(),
            'categories' => Category::all(),
            'showCommuneFilter' => $showCommuneFilter,
            'managedCommunesForFilter' => $managedCommunesForFilter,
        ]);
    }

    public function show(Profile $profile)
    {
        $this->ensureProfileCommune($profile);
        $profile->load(['user', 'category', 'documents']);

        return view('admin.profiles.show', compact('profile'));
    }

    public function approve(Profile $profile)
    {
        $this->ensureProfileCommune($profile);
        try {
            $this->service->approve($profile);
            ActivityLogger::log('profile_approved', 'Profile', $profile->id, $profile->user->name);

            return back()->with('success', 'Profil approuvé. Un email a été envoyé à l\'opérateur.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de l\'approbation du profil.']);
        }
    }

    public function reject(Request $request, Profile $profile)
    {
        $this->ensureProfileCommune($profile);
        $request->validate([
            'motif_rejet' => 'required|string|min:10',
        ], [
            'motif_rejet.required' => 'Le motif de rejet est requis.',
            'motif_rejet.min' => 'Le motif doit contenir au moins 10 caractères.',
        ]);

        try {
            $this->service->reject($profile, $request->motif_rejet);
            ActivityLogger::log('profile_rejected', 'Profile', $profile->id, $profile->user->name);

            return back()->with('success', 'Profil rejeté. L\'opérateur a été notifié par email.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors du rejet du profil.']);
        }
    }

    public function suspend(Profile $profile)
    {
        $this->ensureProfileCommune($profile);
        try {
            $this->service->suspend($profile);
            ActivityLogger::log('profile_suspended', 'Profile', $profile->id, $profile->user->name);

            return back()->with('success', 'Profil suspendu.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suspension du profil.']);
        }
    }

    public function destroy(Profile $profile)
    {
        $this->ensureProfileCommune($profile);
        try {
            $label = $profile->user->name;
            $id = $profile->id;
            $profile->delete();
            ActivityLogger::log('profile_deleted', 'Profile', $id, $label);

            return redirect()->route('admin.profiles.index')->with('success', 'Profil supprimé.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression du profil.']);
        }
    }

    public function exportDocuments(Profile $profile)
    {
        $this->ensureProfileCommune($profile);
        try {
            $documents = $profile->documents;

            if ($documents->isEmpty()) {
                return back()->withErrors(['error' => 'Aucun document à télécharger.']);
            }

            // Si un seul document, le télécharger directement
            if ($documents->count() === 1) {
                $doc = $documents->first();

                return Storage::disk('public')->download($doc->path, $doc->original_name);
            }

            // Créer une archive ZIP avec tous les documents
            $zip = new ZipArchive;
            $zipPath = storage_path('app/public/exports/'.uniqid().'.zip');

            // Créer le répertoire s'il n'existe pas
            if (! is_dir(dirname($zipPath))) {
                mkdir(dirname($zipPath), 0755, true);
            }

            if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
                foreach ($documents as $doc) {
                    $filePath = Storage::disk('public')->path($doc->path);
                    if (file_exists($filePath)) {
                        $zip->addFile($filePath, $doc->original_name);
                    }
                }
                $zip->close();

                return response()->download($zipPath, $profile->user->name.'_documents.zip')
                    ->deleteFileAfterSend(true);
            }

            return back()->withErrors(['error' => 'Erreur lors de la création de l\'archive.']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de l\'export des documents: '.$e->getMessage()]);
        }
    }

    private function ensureProfileCommune(Profile $profile): void
    {
        $profile->loadMissing('user');
        CommuneContext::authorizeBackofficeResourceCommune($profile->user->commune_id);
    }
}
