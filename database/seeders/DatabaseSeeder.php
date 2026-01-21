<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\CategoriesAndAttributesSeeder;
use Database\Seeders\CompanyInfoSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            AdminUserSeeder::class,
            CategoriesAndAttributesSeeder::class,
            CompanyInfoSeeder::class,
            ProductsSeeder::class,
            PromotionsSeeder::class,
            CouponsSeeder::class,
            SiteSettingsSeeder::class,
            CmsPagesSeeder::class,
        ]);
    }
}
