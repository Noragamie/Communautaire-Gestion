<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuthLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'L\'adresse email n\'est pas valide.',
            'password.required' => 'Le mot de passe est requis.',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if (! $user->hasVerifiedEmail()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors(['email' => 'Veuillez confirmer votre adresse email avant de vous connecter. Vérifiez votre boîte mail.'])
                    ->withInput($request->only('email'));
            }

            if ($user->is_suspended) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                AuthLog::create([
                    'user_id' => $user->id,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'action' => 'failed',
                ]);

                return back()->withErrors([
                    'email' => 'Votre compte a été suspendu. Contactez l\'administrateur.',
                ])->withInput($request->only('email'));
            }

            $request->session()->regenerate();

            AuthLog::create([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'action' => 'login',
            ]);

            return match ($user->role) {
                'admin', 'agent_municipal' => redirect()->route('admin.dashboard'),
                'operateur' => redirect()->route('operator.profile.show'),
                default => redirect()->route('home'),
            };
        }

        AuthLog::create([
            'user_id' => null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'action' => 'failed',
        ]);

        return back()->withErrors([
            'email' => 'Ces identifiants ne correspondent à aucun compte. Vérifiez votre email et mot de passe.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        AuthLog::create([
            'user_id' => Auth::id(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'action' => 'logout',
        ]);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
