<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use App\Models\User;
use App\Helpers\LogActivity;
use App\Models\Client;
use Illuminate\Auth\Events\Registered;

class AdminAuthController extends Controller
{
    /**
     * Affiche le formulaire de connexion admin
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Traite la tentative de connexion admin
     */
    public function login(Request $request)
    {

        // Désactiver temporairement la vérification CSRF pour cette requête
        // pour résoudre le problème de "Page Expired"
        $request->headers->set('X-CSRF-TOKEN', $request->session()->token());

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        // Log pour débogage
        Log::info('Tentative de connexion pour: ' . $credentials['email']);

        // Tentative de connexion avec le garde admin
        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            // Régénérer la session pour éviter les attaques de fixation de session
            $request->session()->regenerate();

            // Récupérer l'utilisateur connecté avec le même garde
            $user = Auth::guard('admin')->user();
            Log::info('Utilisateur authentifié: ' . $user->email);

            // Vérifier les rôles via la relation roles
            $userRoles = $user->roles->pluck('name')->toArray();
            Log::info('Rôles de l\'utilisateur: ' . implode(', ', $userRoles));

            // Vérifier si l'utilisateur a un rôle admin
            if (in_array('Admin', $userRoles) || in_array('Super Admin', $userRoles)) {
                // Mettre à jour la date de dernière connexion
                try {
                    \Illuminate\Support\Facades\DB::table('users')
                        ->where('id', $user->id)
                        ->update(['last_login' => now()]);
                } catch (\Exception $e) {
                    Log::warning('Impossible de mettre à jour last_login: ' . $e->getMessage());
                }

                // Rediriger vers le dashboard admin
                Log::info('Redirection vers le dashboard admin');
                return redirect()->intended(route('admin.welcome'));
            }

            // Si l'utilisateur n'a pas de rôle admin, le déconnecter
            Log::warning('Utilisateur sans rôle admin: ' . $user->email);
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors([
                'email' => 'Accès refusé. Vous n\'avez pas les permissions administrateur.',
            ]);
        }

        Log::warning('Échec d\'authentification pour: ' . $credentials['email']);
        // Si l'authentification échoue, rediriger avec message d'erreur
        return redirect()->route('login')
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
            ]);
    }

    /**
     * Déconnexion de l'admin
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Traite l'inscription d'un nouveau client
     */
    public function register(Request $request)
    {
        // Validation des données
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'telephone' => ['required', 'string', 'max:20'],
            'ville' => ['required', 'string', 'max:100'],
            'adresse' => ['required', 'string', 'max:500'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required' => 'Full name is required.',
            'name.max' => 'Name cannot exceed 255 characters.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Email address must be valid.',
            'email.unique' => 'This email address is already in use.',
            'telephone.required' => 'Phone number is required.',
            'ville.required' => 'City is required.',
            'adresse.required' => 'Address is required.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        DB::beginTransaction();

        try {
            // Créer l'utilisateur
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->telephone,
                'city' => $request->ville,
                'address_line1' => $request->adresse,
                'is_active' => 1,
                'password' => Hash::make($request->password),
            ]);

            // Attribuer le rôle Client à l'utilisateur
            $user->assignRole('Client');

            DB::commit();

            // Déclencher l'événement d'inscription
            event(new Registered($user));

            // Connecter automatiquement l'utilisateur avec le guard 'web' (client)
            Auth::guard('web')->login($user);

            // Authentification réussie
            $userName = Auth::guard('web')->user()->name;
            $action = 'Inscription';
            $desc = 'Le client' . ' ' . $userName . ' ' . 's\'est inscrit au réseau';
            LogActivity::addToLog($action, $desc, $request);
            // Rediriger vers la page d'accueil avec un message de succès
            return redirect()->route('home')->with('success', 'Your account has been created successfully! Welcome to BOLDROOTS.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors de l\'inscription', [
                'error' => $e->getMessage(),
                'request_data' => $request->except(['password', 'password_confirmation'])
            ]);

            return redirect()->back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->with('error', 'An error occurred while creating your account. Please try again.');
        }
    }
}
