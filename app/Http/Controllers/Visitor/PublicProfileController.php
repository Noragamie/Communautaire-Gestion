<?php

namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Controller;
use App\Models\Actuality;
use App\Models\Category;
use App\Models\Newsletter;
use App\Models\Profile;
use Illuminate\Http\Request;

class PublicProfileController extends Controller
{
    public function index(Request $request)
    {
        $query = Profile::approved()->with(['user','category','documents']);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->whereHas('user', fn($qu)=>$qu->where('name','like',"%$s%"))
                  ->orWhere('bio','like',"%$s%")
                  ->orWhere('secteur_activite','like',"%$s%");
            });
        }

        return view('visitor.index', [
            'profiles'   => $query->paginate(12),
            'categories' => Category::all(),
        ]);
    }

    public function show(Profile $profile)
    {
        if (!$profile->isApproved()) {
            abort(404, 'Profil non disponible');
        }
        return view('visitor.show', compact('profile'));
    }

    public function annuaire(Request $request)
    {
        $categories = Category::withCount(['profiles'=>fn($q)=>$q->approved()])->get();
        return view('visitor.annuaire', compact('categories'));
    }

    public function byCategory(Category $category, Request $request)
    {
        $profiles = $category->profiles()->approved()->with('user','documents')->paginate(12);
        return view('visitor.category', compact('category','profiles'));
    }

    public function actualities()
    {
        $actualities = Actuality::published()->paginate(10);
        return view('visitor.actualities', compact('actualities'));
    }

    public function subscribeNewsletter(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ], [
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'L\'adresse email n\'est pas valide.',
        ]);

        try {
            Newsletter::firstOrCreate(['email' => $request->email], ['subscribed' => true]);
            return back()->with('success', 'Vous êtes abonné à la newsletter.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de l\'abonnement.']);
        }
    }

    public function unsubscribeNewsletter(string $token)
    {
        $sub = Newsletter::where('token', $token)->first();
        if (!$sub) {
            return redirect('/')->withErrors(['error' => 'Lien invalide.']);
        }
        $sub->update(['subscribed' => false]);
        return redirect('/')->with('success', 'Vous êtes désabonné.');
    }
}
