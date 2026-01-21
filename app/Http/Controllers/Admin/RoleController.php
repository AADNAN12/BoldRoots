<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage_roles')->only('index', 'store', 'edit', 'update', 'destroy', 'show');
    }
    public function index()
    {
        $roles = Role::with('permissions')
            ->get();

        $permissions = Permission::all();

        return view('Admin.Roles.index', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['required', 'string', 'exists:permissions,name'],
        ]);

        $role = Role::create([
            'name' => $request->name,
            'company_id' => auth()->user()->company_id,
        ]);

        $role->syncPermissions($request->permissions);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rôle créé avec succès.');
    }

    public function update(Request $request, Role $role)
    {
        // Vérifier que le rôle appartient à la même entreprise
        if ($role->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['required', 'string', 'exists:permissions,name'],
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rôle mis à jour avec succès.');
    }

    public function destroy(Role $role)
    {
        // Vérifier que le rôle appartient à la même entreprise
        if ($role->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        // Empêcher la suppression du rôle Super Admin
        if ($role->name === 'Super Admin') {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Le rôle Super Admin ne peut pas être supprimé.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rôle supprimé avec succès.');
    }
}
