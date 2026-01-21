<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        // Rate limiting
        $throttleKey = Str::transliterate(Str::lower($request->string('email')).'|'.$request->ip());
        
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }
        

        // Valider les credentials
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Essayer d'abord avec le guard 'admin' pour les administrateurs
        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::guard('admin')->user();
            
            // Log pour débogage
            \Illuminate\Support\Facades\Log::info('Tentative de connexion admin pour: ' . $user->email);
            \Illuminate\Support\Facades\Log::info('Rôles de l\'utilisateur: ' . $user->roles->pluck('name')->implode(', '));
            
            // Vérifier si l'utilisateur a un rôle admin
            if ($user->hasRole(['Super Admin', 'Admin'])) {
                \Illuminate\Support\Facades\Log::info('Utilisateur identifié comme admin, redirection vers la page welcome');
                
                // Clear rate limiter
                RateLimiter::clear($throttleKey);
                
                // Mettre à jour la date de dernière connexion
                $user->last_login = now();
                $user->save();
                
                // Rediriger vers le dashboard admin
                return redirect()->route('admin.welcome');
            }
            
            \Illuminate\Support\Facades\Log::warning('Utilisateur sans rôle admin, déconnexion du guard admin');
            // Si pas admin, déconnecter du guard admin
            Auth::guard('admin')->logout();
        }

        // Essayer avec le guard 'web' pour les clients
        if (Auth::guard('web')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::guard('web')->user();
            
            // Clear rate limiter
            RateLimiter::clear($throttleKey);
            
            // Mettre à jour la date de dernière connexion
            $user->last_login = now();
            $user->save();
            
            // Rediriger vers la page d'accueil
            return redirect()->intended(route('home', absolute: false));
        }

        // Si aucune authentification ne fonctionne
        RateLimiter::hit($throttleKey);
        
        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->withInput($request->only('email'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Désactiver temporairement la vérification CSRF pour cette requête
        $request->headers->set('X-CSRF-TOKEN', $request->session()->token());
        
        // Log de déconnexion
        if ($user = Auth::user()) {
            \Illuminate\Support\Facades\Log::info('Déconnexion de l\'utilisateur: ' . $user->email);
        }
        
        // Déconnexion
        Auth::guard('web')->logout();

        // Invalidation de la session
        $request->session()->invalidate();

        // Régénération du jeton CSRF
        $request->session()->regenerateToken();

        // Redirection vers la page d'accueil avec un message flash
        return redirect('/')->with('status', 'Vous avez été déconnecté avec succès.');
    }
}
