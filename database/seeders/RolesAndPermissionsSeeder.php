<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Clear existing roles and permissions
        Schema::disableForeignKeyConstraints();
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        Schema::enableForeignKeyConstraints();

        // Create permissions with groups and translations
        $permissions = [
            // Gestion des utilisateurs
            [
                'name' => 'manage_users',
                'name_fr' => 'Gérer les utilisateurs',
                'name_en' => 'Manage Users',
                'group' => 'Gestion des Utilisateurs',
            ],
            [
                'name' => 'view_users',
                'name_fr' => 'Voir les utilisateurs',
                'name_en' => 'View Users',
                'group' => 'Gestion des Utilisateurs',
            ],
            [
                'name' => 'create_users',
                'name_fr' => 'Créer des utilisateurs',
                'name_en' => 'Create Users',
                'group' => 'Gestion des Utilisateurs',
            ],
            [
                'name' => 'edit_users',
                'name_fr' => 'Modifier les utilisateurs',
                'name_en' => 'Edit Users',
                'group' => 'Gestion des Utilisateurs',
            ],
            [
                'name' => 'delete_users',
                'name_fr' => 'Supprimer les utilisateurs',
                'name_en' => 'Delete Users',
                'group' => 'Gestion des Utilisateurs',
            ],
            [
                'name' => 'show_user',
                'name_fr' => 'Voir le profil',
                'name_en' => 'Show Profile',
                'group' => 'Gestion des Utilisateurs',
            ],
            [
                'name' => 'manage_user_roles',
                'name_fr' => 'Gérer les rôles utilisateurs',
                'name_en' => 'Manage User Roles',
                'group' => 'Gestion des Utilisateurs',
            ],

            // Gestion des rôles
            [
                'name' => 'manage_roles',
                'name_fr' => 'Gérer les rôles',
                'name_en' => 'Manage Roles',
                'group' => 'Gestion des Rôles',
            ],
        ];
        
        foreach ($permissions as $permissionData) {
            Permission::create($permissionData);
        }

        // Create roles and assign permissions

        // Super Admin - Accès complet
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Client - Accès limité (espace client uniquement)
        $client = Role::create(['name' => 'Client']);
        // Les clients n'ont pas de permissions admin

    }
}
