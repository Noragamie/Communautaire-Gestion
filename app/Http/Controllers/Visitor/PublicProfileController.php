<?php

namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeNewsletterMail;
use App\Models\Actuality;
use App\Models\Category;
use App\Models\Commune;
use App\Models\Newsletter;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
            'heroProfessionnelsCount' => Profile::approved()->count(),
            'heroSecteursCount' => Category::count(),
        ]);
    }

    public function show(Profile $profile)
    {
        if (! $profile->isApproved() || $profile->user->is_suspended) {
            abort(404, 'Profil non disponible');
        }

        $profile->loadMissing(['user', 'category']);

        return view('visitor.show', compact('profile'));
    }

    public function annuaire(Request $request)
    {
        $categories = Category::query()
            ->withCount(['profiles' => fn ($q) => $q->approved()])
            ->orderBy('name')
            ->get();

        $query = Profile::approved()->with(['user', 'category']);

        $categoryIds = array_values(array_filter(array_map('intval', (array) $request->input('categories', []))));
        if ($categoryIds === [] && $request->filled('category')) {
            $categoryIds = [(int) $request->input('category')];
        }
        if ($categoryIds !== []) {
            $query->whereIn('category_id', $categoryIds);
        }

        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where(function ($q) use ($s) {
                $q->whereHas('user', fn ($qu) => $qu->where('name', 'like', "%{$s}%"))
                    ->orWhere('bio', 'like', "%{$s}%")
                    ->orWhere('secteur_activite', 'like', "%{$s}%")
                    ->orWhere('localisation', 'like', "%{$s}%")
                    ->orWhere('competences', 'like', "%{$s}%");
            });
        }

        if ($request->filled('location')) {
            $loc = $request->input('location');
            $query->where('localisation', 'like', "%{$loc}%");
        }

        $niveauOptions = [
            'bac' => 'Baccalauréat',
            'licence' => 'Licence',
            'master' => 'Master',
            'doctorat' => 'Doctorat',
            'autre' => 'Autre',
        ];
        $selectedNiveaux = array_values(array_intersect(array_keys($niveauOptions), (array) $request->input('niveaux', [])));
        if ($selectedNiveaux !== []) {
            $query->whereIn('niveau_etude', $selectedNiveaux);
        }

        $communeId = (int) $request->input('commune_id', 0);
        if ($communeId > 0 && Commune::query()->whereKey($communeId)->exists()) {
            $query->whereHas('user', fn ($q) => $q->where('commune_id', $communeId));
        }

        $communes = Commune::query()->orderBy('name')->get();

        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'name_asc' => $query->orderBy(
                User::query()
                    ->select('name')
                    ->whereColumn('users.id', 'profiles.user_id')
                    ->limit(1)
            ),
            'name_desc' => $query->orderByDesc(
                User::query()
                    ->select('name')
                    ->whereColumn('users.id', 'profiles.user_id')
                    ->limit(1)
            ),
            'category' => $query
                ->join('categories', 'categories.id', '=', 'profiles.category_id')
                ->orderBy('categories.name')
                ->select('profiles.*'),
            default => $query->latest('profiles.updated_at'),
        };

        $profiles = $query->paginate(12)->withQueryString();

        return view('visitor.annuaire', compact(
            'categories',
            'profiles',
            'communes',
            'niveauOptions',
            'selectedNiveaux',
        ));
    }

    public function byCategory(Category $category, Request $request)
    {
        $profiles = $category->profiles()->approved()->with('user','documents')->paginate(12);
        return view('visitor.category', compact('category','profiles'));
    }

    public function actualities(Request $request)
    {
        $query = Actuality::published();
        
        // Filter by month if provided
        if ($request->filled('month')) {
            // SQLite uses strftime instead of DATE_FORMAT
            $query->whereRaw("strftime('%Y-%m', published_at) = ?", [$request->month]);
        }

        $communeId = (int) $request->input('commune_id', 0);
        if ($communeId > 0 && Commune::query()->whereKey($communeId)->exists()) {
            $query->where('commune_id', $communeId);
        }

        $actualities = $query->paginate(10)->withQueryString();
        
        // Generate dynamic months from actualities - SQLite compatible
        $months = Actuality::published()
            ->selectRaw("strftime('%Y-%m', published_at) as month")
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->pluck('month')
            ->filter()
            ->map(function($month) {
                $date = \Carbon\Carbon::createFromFormat('Y-m', $month);
                return [
                    'value' => $month,
                    'label' => $date->translatedFormat('F Y')
                ];
            });
        
        $communes = Commune::query()->orderBy('name')->get();

        return view('visitor.actualities', compact('actualities', 'months', 'communes'));
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
            $subscriber = Newsletter::firstOrCreate(
                ['email' => $request->email],
                ['subscribed' => true]
            );

            $justSubscribed = $subscriber->wasRecentlyCreated;

            if (!$subscriber->wasRecentlyCreated && !$subscriber->subscribed) {
                $subscriber->update(['subscribed' => true]);
                $justSubscribed = true;
            }

            if ($justSubscribed) {
                Mail::to($subscriber->email)->send(new WelcomeNewsletterMail($subscriber->token));
            }

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
