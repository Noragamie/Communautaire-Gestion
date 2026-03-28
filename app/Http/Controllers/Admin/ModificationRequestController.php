<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModificationRequest;
use App\Services\ActivityLogger;
use App\Services\CommuneContext;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class ModificationRequestController extends Controller
{
    public function __construct(private ProfileService $service) {}

    public function index(Request $request)
    {
        $filterCommuneId = $request->filled('commune_id') ? (int) $request->commune_id : null;
        $ids = CommuneContext::scopedCommuneIdsForQuery($filterCommuneId);
        $requests = ModificationRequest::with(['profile.user.commune', 'profile.category', 'documents'])
            ->where('status', 'pending')
            ->when(CommuneContext::needsTerritorialScope() && $ids !== [], fn ($q) => $q->whereHas('profile.user', fn ($u) => $u->whereIn('commune_id', $ids)))
            ->when(CommuneContext::needsTerritorialScope() && $ids === [], fn ($q) => $q->whereRaw('1 = 0'))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $user = $request->user();
        $showCommuneFilter = CommuneContext::shouldShowAdminCommuneListFilter();
        $managedCommunesForFilter = $showCommuneFilter
            ? $user->managedCommunes()->orderBy('name')->get()
            : collect();

        return view('admin.modifications.index', compact(
            'requests',
            'showCommuneFilter',
            'managedCommunesForFilter'
        ));
    }

    public function show(ModificationRequest $modificationRequest)
    {
        $this->ensureModificationCommune($modificationRequest);
        $modificationRequest->load(['profile.user', 'profile.category', 'profile.documents', 'documents']);

        return view('admin.modifications.show', compact('modificationRequest'));
    }

    public function approve(ModificationRequest $modificationRequest)
    {
        $this->ensureModificationCommune($modificationRequest);
        if (! $modificationRequest->isPending()) {
            return back()->withErrors(['error' => 'Cette demande a déjà été traitée.']);
        }
        try {
            $this->service->approveModification($modificationRequest);
            ActivityLogger::log('modification_approved', 'ModificationRequest', $modificationRequest->id, $modificationRequest->profile->user->name);

            return redirect()->route('admin.modifications.index')->with('success', 'Modification approuvée et appliquée.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de l\'approbation.']);
        }
    }

    public function reject(Request $request, ModificationRequest $modificationRequest)
    {
        $this->ensureModificationCommune($modificationRequest);
        if (! $modificationRequest->isPending()) {
            return back()->withErrors(['error' => 'Cette demande a déjà été traitée.']);
        }
        $request->validate(['motif' => 'required|string|max:500'], ['motif.required' => 'Le motif de refus est obligatoire.']);
        try {
            $this->service->rejectModification($modificationRequest, $request->motif);
            ActivityLogger::log('modification_rejected', 'ModificationRequest', $modificationRequest->id, $modificationRequest->profile->user->name);

            return redirect()->route('admin.modifications.index')->with('success', 'Modification refusée.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors du refus.']);
        }
    }

    private function ensureModificationCommune(ModificationRequest $modificationRequest): void
    {
        $modificationRequest->loadMissing('profile.user');
        $profile = $modificationRequest->profile;
        CommuneContext::authorizeBackofficeResourceCommune($profile?->user->commune_id);
    }
}
