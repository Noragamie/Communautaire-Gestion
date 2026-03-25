<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings', ['user' => Auth::user()]);
    }

    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ], [
            'name.required'  => 'Le nom est requis.',
            'email.required' => 'L\'email est requis.',
            'email.email'    => 'L\'email n\'est pas valide.',
            'email.unique'   => 'Cet email est déjà utilisé.',
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Informations mises à jour.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Le mot de passe actuel est requis.',
            'password.required'         => 'Le nouveau mot de passe est requis.',
            'password.confirmed'        => 'La confirmation ne correspond pas.',
            'password.min'              => 'Le mot de passe doit faire au moins 8 caractères.',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.']);
        }

        Auth::user()->update(['password' => $request->password]);

        return back()->with('success', 'Mot de passe modifié.');
    }

    public function updateNotifications(Request $request)
    {
        Auth::user()->update([
            'notify_new_profile' => $request->boolean('notify_new_profile'),
        ]);

        return back()->with('success', 'Préférences de notifications mises à jour.');
    }
}
