<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        \Illuminate\Support\Facades\Log::info('AdminAuth middleware - Vérification de l\'authentification admin');
        \Illuminate\Support\Facades\Log::info('Guard admin check: ' . (Auth::guard('admin')->check() ? 'true' : 'false'));
        
        if (!Auth::guard('admin')->check()) {
            \Illuminate\Support\Facades\Log::warning('AdminAuth middleware - Utilisateur non connecté avec guard admin');
            return redirect()->route('login')->with('error', 'Veuillez vous connecter en tant qu\'administrateur.');
        }

        // Vérifier si l'utilisateur a le rôle admin
        $user = Auth::guard('admin')->user();
        \Illuminate\Support\Facades\Log::info('AdminAuth middleware - Utilisateur: ' . $user->email);
        \Illuminate\Support\Facades\Log::info('AdminAuth middleware - Rôles: ' . $user->roles->pluck('name')->implode(', '));
        
        if (!$user->hasRole(['Super Admin', 'Admin'])) {
            \Illuminate\Support\Facades\Log::warning('AdminAuth middleware - Utilisateur sans rôle admin');
            Auth::guard('admin')->logout();
            return redirect()->route('login')->with('error', 'Accès refusé. Vous n\'avez pas les permissions administrateur.');
        }

        \Illuminate\Support\Facades\Log::info('AdminAuth middleware - Accès autorisé');
        return $next($request);
    }
}
