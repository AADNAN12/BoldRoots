<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Si c'est un admin connecté
                if ($guard === 'admin' || Auth::guard('admin')->check()) {
                    $user = Auth::guard('admin')->user();
                    if ($user && $user->hasRole(['Super Admin', 'Admin'])) {
                        return redirect()->route('admin.welcome');
                    }
                }
                
                // Si c'est un client connecté
                if ($guard === 'web' || Auth::guard('web')->check()) {
                    return redirect()->route('home');
                }
                
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
