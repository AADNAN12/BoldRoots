<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage_users,admin')->only('index', 'store', 'edit', 'update', 'destroy', 'show');
        $this->middleware('permission:view_users,admin')->only('index');
        $this->middleware('permission:create_users,admin')->only('store');
        $this->middleware('permission:edit_users,admin')->only('edit', 'update');
        $this->middleware('permission:delete_users,admin')->only('destroy');
        $this->middleware('permission:show_user,admin')->only('show');
    }
    public function index()
    {
        $users = User::with('roles')
            ->get();

        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }
    public function show(User $user)
    {
        // Les admins peuvent voir tous les profils
        $user = User::with('roles')
            ->findOrFail($user->id);

        return view('admin.users.show', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['required', 'string', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'is_active' => 1,
        ]);

        $user->syncRoles($request->roles);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function update(Request $request, User $user)
    {
        // Vérification de sécurité : seuls les admins peuvent modifier tous les profils
        // Les autres utilisateurs ne peuvent modifier que leur propre profil
        if (!Auth::user()->hasAnyRole(['Super Admin', 'Admin']) && Auth::user()->id !== $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous n\'avez pas l\'autorisation de modifier ce profil.');
        }
        
        // Empêcher la modification du Super Admin par les non Super Admin
        if ($user->hasRole('Super Admin') && !Auth::user()->hasRole('Super Admin')) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous n\'avez pas les droits pour modifier un Super Admin.');
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['required', 'string', 'exists:roles,name'],
            'is_active' => ['required', 'in:active,inactive'],
        ];

        // Ajouter la validation du mot de passe uniquement s'il est fourni
        if ($request->filled('password')) {
            $rules['password'] = ['string', 'min:8', 'confirmed'];
        }

        $request->validate($rules);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_active' => $request->is_active === 'active',
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);
        $user->syncRoles($request->roles);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
       

        // Empêcher la suppression de son propre compte
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        

        // Empêcher la suppression d'un Super Admin par un non Super Admin
        if ($user->hasRole('Super Admin') && !Auth::user()->hasRole('Super Admin')) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous n\'avez pas les droits pour supprimer un Super Admin.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function toggleStatus(Request $request)
    {
        try {
            $user = User::findOrFail($request->user_id);

            // Vérification de sécurité : seuls les admins peuvent modifier le statut de tous les utilisateurs
            // Les autres utilisateurs ne peuvent modifier que leur propre statut
            if (!Auth::guard('admin')->user()->hasAnyRole(['Super Admin', 'Admin']) && Auth::user()->id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'avez pas l\'autorisation de modifier ce statut.'
                ], 403);
            }

            // Empêcher la modification du Super Admin par les non Super Admin
            if ($user->hasRole('Super Admin') && !Auth::user()->hasRole('Super Admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'avez pas les droits pour modifier un Super Admin.'
                ], 403);
            }

            $user->is_active = $request->active;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => $user->is_active ? 'Utilisateur activé avec succès' : 'Utilisateur désactivé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Changer le mot de passe d'un utilisateur
     */
    public function changePassword(Request $request, User $user)
    {
        try {
            // Vérification de sécurité : seuls les utilisateurs peuvent changer leur propre mot de passe
            if (Auth::user()->id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous ne pouvez changer que votre propre mot de passe.'
                ], 403);
            }

            // Validation des données
            $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|confirmed',
                'new_password_confirmation' => 'required|string'
            ], [
                'current_password.required' => 'Le mot de passe actuel est requis.',
                'new_password.required' => 'Le nouveau mot de passe est requis.',
                'new_password.min' => 'Le nouveau mot de passe doit contenir au moins 8 caractères.',
                'new_password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
                'new_password_confirmation.required' => 'La confirmation du mot de passe est requise.'
            ]);

            // Vérifier le mot de passe actuel
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'current_password' => ['Le mot de passe actuel est incorrect.']
                    ]
                ], 422);
            }

            // Mettre à jour le mot de passe
            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Mot de passe changé avec succès.'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors du changement de mot de passe.'
            ], 500);
        }
    }
}
