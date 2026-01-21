<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientAuthController extends Controller
{
    /**
     * Affiche le formulaire de connexion client
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Traite la tentative de connexion client
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Tentative de connexion avec le garde web (client)
        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            
            // Vérifier si l'utilisateur a le rôle client
            $user = Auth::guard('web')->user();
            // Vérifier les rôles via la relation roles
            $userRoles = $user->roles->pluck('name')->toArray();
            if ($user && in_array('client', $userRoles)) {
                return redirect()->intended(route('home'));
            }
            
            // Si l'utilisateur est un admin qui essaie de se connecter comme client
            if ($user && (in_array('admin', $userRoles) || in_array('super-admin', $userRoles))) {
                Auth::guard('web')->logout();
                return back()->withErrors([
                    'email' => 'Veuillez utiliser l\'interface d\'administration pour vous connecter.',
                ]);
            }
            
            // Si l'utilisateur n'a pas de rôle défini
            Auth::guard('web')->logout();
            return back()->withErrors([
                'email' => 'Votre compte n\'est pas activé. Veuillez contacter l\'administrateur.',
            ]);
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ]);
    }

    /**
     * Déconnexion du client
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}
