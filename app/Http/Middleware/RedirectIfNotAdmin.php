<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }
        
        // Vérifier si l'utilisateur a un rôle admin
        $user = Auth::guard('web')->user();
        $userRoles = $user->roles->pluck('name')->toArray();
        
        if (!in_array('admin', $userRoles) && !in_array('super-admin', $userRoles)) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')->with('error', 'Vous n\'avez pas les permissions nécessaires pour accéder à l\'administration.');
        }

        return $next($request);
    }
}
