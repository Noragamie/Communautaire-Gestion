<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModificationRequest;
use App\Services\ActivityLogger;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class ModificationRequestController extends Controller
{
    public function __construct(private ProfileService $service) {}

    public function index()
    {
        $requests = ModificationRequest::with(['profile.user', 'profile.category', 'documents'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('admin.modifications.index', compact('requests'));
    }

    public function show(ModificationRequest $modificationRequest)
    {
        $modificationRequest->load(['profile.user', 'profile.category', 'profile.documents', 'documents']);
        return view('admin.modifications.show', compact('modificationRequest'));
    }

    public function approve(ModificationRequest $modificationRequest)
    {
        if (!$modificationRequest->isPending()) {
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
        if (!$modificationRequest->isPending()) {
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
}
