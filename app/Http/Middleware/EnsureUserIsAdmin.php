<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // On récupère l'utilisateur connecté
        // et on dit à PHP que c'est bien un objet "User"
        /** @var User $user */
        $user = Auth::user();

        // Si personne n'est connecté
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Si l'utilisateur n'est pas admin
        if (!$user->isAdmin()) {
            return redirect()->route('dashboard')
                             ->with('error', 'Accès réservé aux administrateurs.');
        }

        return $next($request);
    }
}