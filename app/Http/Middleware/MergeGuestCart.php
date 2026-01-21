<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class MergeGuestCart
{
    /**
     * Handle an incoming request.
     *
     * Fusionne le panier invité avec le panier utilisateur lors de la connexion
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si l'utilisateur vient de se connecter, fusionner les paniers
        if (Auth::check()) {
            Cart::mergeGuestCart(Auth::id());
        }

        return $next($request);
    }
}
