<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotClient
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est connecté avec le garde web
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }
        
        // Vérifier si l'utilisateur a un rôle client ou s'il a un profil client associé
        $user = Auth::guard('web')->user();
        $userRoles = $user->roles->pluck('name')->toArray();
        
        // Vérifier si l'utilisateur a le rôle client
        $hasClientRole = in_array('client', $userRoles);
        
        // Vérifier si l'utilisateur a un profil client associé en utilisant une requête directe
        $hasClientProfile = DB::table('clients')->where('user_id', $user->id)->exists();
        
        if (!$hasClientRole && !$hasClientProfile) {
            return redirect()->route('home')->with('error', 'Vous n\'avez pas accès à l\'espace client.');
        }

        return $next($request);
    }
}
