<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(
            ['email' => 'required|email'],
            ['email.required' => 'L\'adresse email est requise.', 'email.email' => 'L\'adresse email n\'est pas valide.']
        );

        Password::sendResetLink($request->only('email'));

        // Toujours afficher le même message (sécurité : ne pas révéler si l'email existe)
        return back()->with('success', 'Si cette adresse est associée à un compte, vous recevrez un lien de réinitialisation dans quelques minutes.');
    }
}
