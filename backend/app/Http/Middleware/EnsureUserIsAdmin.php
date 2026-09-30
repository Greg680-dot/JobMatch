<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Gère la vérification des permissions administrateur.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('warning', 'Veuillez vous connecter pour accéder à cette page.');
        }

        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé : cette section est strictement réservée à l\'administrateur.');
        }

        return $next($request);
    }
}
