<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckType
{
    /**
     * Gère une requête entrante.
     *
     * @param  \IllAuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $type
     * @return mixed
     */
    public function handle($request, Closure $next, $type)
    {
        if (!Auth::check() || Auth::utilisateur()->type_utilisateur !== $type) {
            abort(403, 'Accès interdit.');
        }

        return $next($request);
    }
}
