<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing users
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();



       
        // Create Super Admin
        $admin = User::create([
            'name' => 'Admin BOLDROOTS',
            'email' => 'admin@boldroots.com',
            'password' => Hash::make('BoldRoots2026'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        $admin->assignRole('Super Admin');

// Create Client User
$client = User::create([
    'name' => 'Client Test',
    'email' => 'client@boldroots.com',
    'password' => Hash::make('Client2026'),
    'email_verified_at' => now(),
    'is_active' => true,
]);

$client->assignRole('Client');

    }
}
