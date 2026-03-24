<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class AdminProfileController extends Controller
{
    public function __construct(private ProfileService $service) {}

    public function index(Request $request)
    {
        $query = Profile::with(['user','category'])->latest();

        if ($request->filled('status'))   { $query->where('status', $request->status); }
        if ($request->filled('category')) { $query->where('category_id', $request->category); }
        if ($request->filled('search'))   {
            $s = $request->search;
            $query->whereHas('user', fn($q) => $q->where('name','like',"%$s%")
                                                  ->orWhere('email','like',"%$s%"));
        }

        return view('admin.profiles.index', [
            'profiles'   => $query->paginate(15),
            'categories' => \App\Models\Category::all(),
        ]);
    }

    public function show(Profile $profile)
    {
        $profile->load(['user','category','documents']);
        return view('admin.profiles.show', compact('profile'));
    }

    public function approve(Profile $profile)
    {
        try {
            $this->service->approve($profile);
            return back()->with('success', 'Profil approuvé. Un email a été envoyé à l\'opérateur.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de l\'approbation du profil.']);
        }
    }

    public function reject(Request $request, Profile $profile)
    {
        $request->validate([
            'motif_rejet' => 'required|string|min:10'
        ], [
            'motif_rejet.required' => 'Le motif de rejet est requis.',
            'motif_rejet.min' => 'Le motif doit contenir au moins 10 caractères.',
        ]);

        try {
            $this->service->reject($profile, $request->motif_rejet);
            return back()->with('success', 'Profil rejeté. L\'opérateur a été notifié par email.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors du rejet du profil.']);
        }
    }

    public function suspend(Profile $profile)
    {
        try {
            $this->service->suspend($profile);
            return back()->with('success', 'Profil suspendu.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suspension du profil.']);
        }
    }

    public function destroy(Profile $profile)
    {
        try {
            $profile->delete();
            return redirect()->route('admin.profiles.index')->with('success', 'Profil supprimé.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression du profil.']);
        }
    }
}
