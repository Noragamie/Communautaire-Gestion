<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureBackoffice
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (! $user || ! $user->isBackoffice()) {
            abort(403, 'Accès réservé à l\'administration.');
        }

        return $next($request);
    }
}
