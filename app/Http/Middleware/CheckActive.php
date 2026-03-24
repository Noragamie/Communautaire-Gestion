<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckActive
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (Auth::check() && !Auth::user()->is_active) {
            Auth::logout();
            return redirect('/connexion')
                ->withErrors(['email' => 'Votre compte a été désactivé. Contactez l\'administrateur.']);
        }
        return $next($request);
    }
}
