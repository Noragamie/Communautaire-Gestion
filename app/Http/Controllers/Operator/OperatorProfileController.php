<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\Category;
use App\Models\Document;
use App\Services\ProfileService;
use Illuminate\Support\Facades\Auth;

class OperatorProfileController extends Controller
{
    public function __construct(private ProfileService $service) {}

    public function show()
    {
        $profile = Auth::user()->profile()->with('documents','category')->first();
        
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
            return redirect()->route('operator.profile.show')
                ->with('success', 'Profil soumis ! Vous recevrez un email de confirmation.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la soumission du profil.'])->withInput();
        }
    }

    public function edit()
    {
        $profile    = Auth::user()->profile;
        if (!$profile) {
            return redirect()->route('operator.profile.create');
        }
        $categories = Category::all();
        return view('operator.profile.edit', compact('profile','categories'));
    }

    public function update(ProfileRequest $request)
    {
        try {
            $this->service->submit($request, Auth::user());
            return redirect()->route('operator.profile.show')
                ->with('success', 'Profil mis à jour et soumis pour re-validation.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la mise à jour du profil.'])->withInput();
        }
    }

    public function destroyDocument(Document $document)
    {
        abort_unless($document->profile->user_id === Auth::id(), 403);
        try {
            $this->service->deleteDocument($document);
            return back()->with('success', 'Document supprimé.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression du document.']);
        }
    }
}
