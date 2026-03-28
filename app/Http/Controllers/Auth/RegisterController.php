<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Commune;
use App\Models\Newsletter;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $communes = Commune::orderBy('department_name')->orderBy('name')->get();

        return view('auth.register', compact('communes'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'commune_id' => 'required|exists:communes,id',
        ], [
            'name.required' => 'Le nom est requis.',
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'L\'adresse email n\'est pas valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'password.required' => 'Le mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'commune_id.required' => 'Veuillez choisir votre commune.',
            'commune_id.exists' => 'Commune invalide.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'operateur',
            'commune_id' => $request->commune_id,
        ]);

        // Lier un abonnement newsletter existant à ce compte
        Newsletter::where('email', $user->email)->whereNull('user_id')->update(['user_id' => $user->id]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('operator.profile.create')
            ->with('success', 'Compte créé ! Complétez maintenant votre profil.');
    }
}
