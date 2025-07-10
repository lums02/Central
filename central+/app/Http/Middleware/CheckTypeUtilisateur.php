<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckTypeUtilisateur
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $type  Le type d'utilisateur attendu (ex: hopital, pharmacie, patient)
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $type)
    {
        if (!Auth::check() || Auth::user()->type_utilisateur !== $type) {
            abort(403, 'Accès refusé.');
        }

        return $next($request);
    }
}
