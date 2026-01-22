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

        // Clear existing roles and permissions (but keep user assignments)
        Schema::disableForeignKeyConstraints();
        DB::table('role_has_permissions')->truncate();
        // DB::table('model_has_roles')->truncate(); // Commenté pour préserver les rôles des utilisateurs
        // DB::table('model_has_permissions')->truncate(); // Commenté pour préserver les permissions des utilisateurs
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        Schema::enableForeignKeyConstraints();

        // Create permissions with groups and translations
        $permissions = [
            // Gestion des utilisateurs
            ['name' => 'view_users', 'name_fr' => 'Voir les utilisateurs', 'name_en' => 'View Users', 'group' => 'Utilisateurs'],
            ['name' => 'create_users', 'name_fr' => 'Créer des utilisateurs', 'name_en' => 'Create Users', 'group' => 'Utilisateurs'],
            ['name' => 'edit_users', 'name_fr' => 'Modifier les utilisateurs', 'name_en' => 'Edit Users', 'group' => 'Utilisateurs'],
            ['name' => 'delete_users', 'name_fr' => 'Supprimer les utilisateurs', 'name_en' => 'Delete Users', 'group' => 'Utilisateurs'],
            ['name' => 'show_user', 'name_fr' => 'Voir le profil utilisateur', 'name_en' => 'Show User Profile', 'group' => 'Utilisateurs'],
            ['name' => 'manage_users', 'name_fr' => 'Gérer les utilisateurs', 'name_en' => 'Manage Users', 'group' => 'Utilisateurs'],

            // Gestion des rôles et permissions
            ['name' => 'view_roles', 'name_fr' => 'Voir les rôles', 'name_en' => 'View Roles', 'group' => 'Rôles & Permissions'],
            ['name' => 'create_roles', 'name_fr' => 'Créer des rôles', 'name_en' => 'Create Roles', 'group' => 'Rôles & Permissions'],
            ['name' => 'edit_roles', 'name_fr' => 'Modifier les rôles', 'name_en' => 'Edit Roles', 'group' => 'Rôles & Permissions'],
            ['name' => 'delete_roles', 'name_fr' => 'Supprimer les rôles', 'name_en' => 'Delete Roles', 'group' => 'Rôles & Permissions'],
            ['name' => 'manage_roles', 'name_fr' => 'Gérer les rôles', 'name_en' => 'Manage Roles', 'group' => 'Rôles & Permissions'],

            // Gestion des catégories
            ['name' => 'view_categories', 'name_fr' => 'Voir les catégories', 'name_en' => 'View Categories', 'group' => 'Catégories'],
            ['name' => 'create_categories', 'name_fr' => 'Créer des catégories', 'name_en' => 'Create Categories', 'group' => 'Catégories'],
            ['name' => 'edit_categories', 'name_fr' => 'Modifier les catégories', 'name_en' => 'Edit Categories', 'group' => 'Catégories'],
            ['name' => 'delete_categories', 'name_fr' => 'Supprimer les catégories', 'name_en' => 'Delete Categories', 'group' => 'Catégories'],
            ['name' => 'manage_categories', 'name_fr' => 'Gérer les catégories', 'name_en' => 'Manage Categories', 'group' => 'Catégories'],

            // Gestion des produits
            ['name' => 'view_products', 'name_fr' => 'Voir les produits', 'name_en' => 'View Products', 'group' => 'Produits'],
            ['name' => 'create_products', 'name_fr' => 'Créer des produits', 'name_en' => 'Create Products', 'group' => 'Produits'],
            ['name' => 'edit_products', 'name_fr' => 'Modifier les produits', 'name_en' => 'Edit Products', 'group' => 'Produits'],
            ['name' => 'delete_products', 'name_fr' => 'Supprimer les produits', 'name_en' => 'Delete Products', 'group' => 'Produits'],
            ['name' => 'manage_products', 'name_fr' => 'Gérer les produits', 'name_en' => 'Manage Products', 'group' => 'Produits'],

            // Gestion des attributs
            ['name' => 'view_attributes', 'name_fr' => 'Voir les attributs', 'name_en' => 'View Attributes', 'group' => 'Attributs'],
            ['name' => 'create_attributes', 'name_fr' => 'Créer des attributs', 'name_en' => 'Create Attributes', 'group' => 'Attributs'],
            ['name' => 'edit_attributes', 'name_fr' => 'Modifier les attributs', 'name_en' => 'Edit Attributes', 'group' => 'Attributs'],
            ['name' => 'delete_attributes', 'name_fr' => 'Supprimer les attributs', 'name_en' => 'Delete Attributes', 'group' => 'Attributs'],
            ['name' => 'manage_attributes', 'name_fr' => 'Gérer les attributs', 'name_en' => 'Manage Attributes', 'group' => 'Attributs'],

            // Gestion des commandes
            ['name' => 'view_orders', 'name_fr' => 'Voir les commandes', 'name_en' => 'View Orders', 'group' => 'Commandes'],
            ['name' => 'show_orders', 'name_fr' => 'Voir détails commande', 'name_en' => 'Show Order Details', 'group' => 'Commandes'],
            ['name' => 'edit_orders', 'name_fr' => 'Modifier les commandes', 'name_en' => 'Edit Orders', 'group' => 'Commandes'],
            ['name' => 'update_order_status', 'name_fr' => 'Changer statut commande', 'name_en' => 'Update Order Status', 'group' => 'Commandes'],
            ['name' => 'update_payment_status', 'name_fr' => 'Changer statut paiement', 'name_en' => 'Update Payment Status', 'group' => 'Commandes'],
            ['name' => 'cancel_orders', 'name_fr' => 'Annuler les commandes', 'name_en' => 'Cancel Orders', 'group' => 'Commandes'],
            ['name' => 'export_orders', 'name_fr' => 'Exporter les commandes', 'name_en' => 'Export Orders', 'group' => 'Commandes'],
            ['name' => 'manage_orders', 'name_fr' => 'Gérer les commandes', 'name_en' => 'Manage Orders', 'group' => 'Commandes'],

            // Gestion des factures
            ['name' => 'view_invoices', 'name_fr' => 'Voir les factures', 'name_en' => 'View Invoices', 'group' => 'Factures'],
            ['name' => 'show_invoices', 'name_fr' => 'Voir détails facture', 'name_en' => 'Show Invoice Details', 'group' => 'Factures'],
            ['name' => 'generate_invoices', 'name_fr' => 'Générer des factures', 'name_en' => 'Generate Invoices', 'group' => 'Factures'],
            ['name' => 'edit_invoices', 'name_fr' => 'Modifier les factures', 'name_en' => 'Edit Invoices', 'group' => 'Factures'],
            ['name' => 'update_invoice_status', 'name_fr' => 'Changer statut facture', 'name_en' => 'Update Invoice Status', 'group' => 'Factures'],
            ['name' => 'cancel_invoices', 'name_fr' => 'Annuler les factures', 'name_en' => 'Cancel Invoices', 'group' => 'Factures'],
            ['name' => 'download_invoices', 'name_fr' => 'Télécharger les factures', 'name_en' => 'Download Invoices', 'group' => 'Factures'],
            ['name' => 'manage_invoices', 'name_fr' => 'Gérer les factures', 'name_en' => 'Manage Invoices', 'group' => 'Factures'],

            // Gestion des bons de livraison
            ['name' => 'view_delivery_notes', 'name_fr' => 'Voir les bons de livraison', 'name_en' => 'View Delivery Notes', 'group' => 'Bons de Livraison'],
            ['name' => 'generate_delivery_notes', 'name_fr' => 'Générer des bons de livraison', 'name_en' => 'Generate Delivery Notes', 'group' => 'Bons de Livraison'],
            ['name' => 'download_delivery_notes', 'name_fr' => 'Télécharger les bons de livraison', 'name_en' => 'Download Delivery Notes', 'group' => 'Bons de Livraison'],
            ['name' => 'manage_delivery_notes', 'name_fr' => 'Gérer les bons de livraison', 'name_en' => 'Manage Delivery Notes', 'group' => 'Bons de Livraison'],

            // Gestion des coupons
            ['name' => 'view_coupons', 'name_fr' => 'Voir les coupons', 'name_en' => 'View Coupons', 'group' => 'Coupons'],
            ['name' => 'create_coupons', 'name_fr' => 'Créer des coupons', 'name_en' => 'Create Coupons', 'group' => 'Coupons'],
            ['name' => 'edit_coupons', 'name_fr' => 'Modifier les coupons', 'name_en' => 'Edit Coupons', 'group' => 'Coupons'],
            ['name' => 'delete_coupons', 'name_fr' => 'Supprimer les coupons', 'name_en' => 'Delete Coupons', 'group' => 'Coupons'],
            ['name' => 'toggle_coupon_status', 'name_fr' => 'Activer/Désactiver coupons', 'name_en' => 'Toggle Coupon Status', 'group' => 'Coupons'],
            ['name' => 'duplicate_coupons', 'name_fr' => 'Dupliquer les coupons', 'name_en' => 'Duplicate Coupons', 'group' => 'Coupons'],
            ['name' => 'export_coupons', 'name_fr' => 'Exporter les coupons', 'name_en' => 'Export Coupons', 'group' => 'Coupons'],
            ['name' => 'manage_coupons', 'name_fr' => 'Gérer les coupons', 'name_en' => 'Manage Coupons', 'group' => 'Coupons'],

            // Gestion des promotions
            ['name' => 'view_promotions', 'name_fr' => 'Voir les promotions', 'name_en' => 'View Promotions', 'group' => 'Promotions'],
            ['name' => 'create_promotions', 'name_fr' => 'Créer des promotions', 'name_en' => 'Create Promotions', 'group' => 'Promotions'],
            ['name' => 'edit_promotions', 'name_fr' => 'Modifier les promotions', 'name_en' => 'Edit Promotions', 'group' => 'Promotions'],
            ['name' => 'delete_promotions', 'name_fr' => 'Supprimer les promotions', 'name_en' => 'Delete Promotions', 'group' => 'Promotions'],
            ['name' => 'toggle_promotion_status', 'name_fr' => 'Activer/Désactiver promotions', 'name_en' => 'Toggle Promotion Status', 'group' => 'Promotions'],
            ['name' => 'duplicate_promotions', 'name_fr' => 'Dupliquer les promotions', 'name_en' => 'Duplicate Promotions', 'group' => 'Promotions'],
            ['name' => 'manage_promotions', 'name_fr' => 'Gérer les promotions', 'name_en' => 'Manage Promotions', 'group' => 'Promotions'],

            // Gestion des méthodes de livraison
            ['name' => 'view_shipping_methods', 'name_fr' => 'Voir les méthodes de livraison', 'name_en' => 'View Shipping Methods', 'group' => 'Livraison'],
            ['name' => 'create_shipping_methods', 'name_fr' => 'Créer des méthodes de livraison', 'name_en' => 'Create Shipping Methods', 'group' => 'Livraison'],
            ['name' => 'edit_shipping_methods', 'name_fr' => 'Modifier les méthodes de livraison', 'name_en' => 'Edit Shipping Methods', 'group' => 'Livraison'],
            ['name' => 'delete_shipping_methods', 'name_fr' => 'Supprimer les méthodes de livraison', 'name_en' => 'Delete Shipping Methods', 'group' => 'Livraison'],
            ['name' => 'manage_shipping_methods', 'name_fr' => 'Gérer les méthodes de livraison', 'name_en' => 'Manage Shipping Methods', 'group' => 'Livraison'],

            // Gestion des newsletters
            ['name' => 'view_newsletters', 'name_fr' => 'Voir les newsletters', 'name_en' => 'View Newsletters', 'group' => 'Newsletter'],
            ['name' => 'delete_newsletters', 'name_fr' => 'Supprimer les newsletters', 'name_en' => 'Delete Newsletters', 'group' => 'Newsletter'],
            ['name' => 'export_newsletters', 'name_fr' => 'Exporter les newsletters', 'name_en' => 'Export Newsletters', 'group' => 'Newsletter'],
            ['name' => 'manage_newsletters', 'name_fr' => 'Gérer les newsletters', 'name_en' => 'Manage Newsletters', 'group' => 'Newsletter'],

            // Gestion des pages CMS
            ['name' => 'view_cms_pages', 'name_fr' => 'Voir les pages CMS', 'name_en' => 'View CMS Pages', 'group' => 'Pages CMS'],
            ['name' => 'create_cms_pages', 'name_fr' => 'Créer des pages CMS', 'name_en' => 'Create CMS Pages', 'group' => 'Pages CMS'],
            ['name' => 'edit_cms_pages', 'name_fr' => 'Modifier les pages CMS', 'name_en' => 'Edit CMS Pages', 'group' => 'Pages CMS'],
            ['name' => 'delete_cms_pages', 'name_fr' => 'Supprimer les pages CMS', 'name_en' => 'Delete CMS Pages', 'group' => 'Pages CMS'],
            ['name' => 'manage_cms_pages', 'name_fr' => 'Gérer les pages CMS', 'name_en' => 'Manage CMS Pages', 'group' => 'Pages CMS'],

            // Gestion des paramètres
            ['name' => 'view_settings', 'name_fr' => 'Voir les paramètres', 'name_en' => 'View Settings', 'group' => 'Paramètres'],
            ['name' => 'edit_settings', 'name_fr' => 'Modifier les paramètres', 'name_en' => 'Edit Settings', 'group' => 'Paramètres'],
            ['name' => 'manage_settings', 'name_fr' => 'Gérer les paramètres', 'name_en' => 'Manage Settings', 'group' => 'Paramètres'],

            // Gestion des informations entreprise
            ['name' => 'view_company_info', 'name_fr' => 'Voir les infos entreprise', 'name_en' => 'View Company Info', 'group' => 'Entreprise'],
            ['name' => 'edit_company_info', 'name_fr' => 'Modifier les infos entreprise', 'name_en' => 'Edit Company Info', 'group' => 'Entreprise'],
            ['name' => 'manage_company_info', 'name_fr' => 'Gérer les infos entreprise', 'name_en' => 'Manage Company Info', 'group' => 'Entreprise'],

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
