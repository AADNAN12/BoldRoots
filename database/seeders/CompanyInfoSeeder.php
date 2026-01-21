<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanyInfo;

class CompanyInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CompanyInfo::create([
            'company_name' => 'Blood Roots',
            'legal_name' => 'Blood Roots SARL',
            'address_line1' => '123 Avenue Mohammed V',
            'address_line2' => 'Quartier des Affaires',
            'city' => 'Casablanca',
            'postal_code' => '20000',
            'country' => 'Maroc',
            'phone' => '+212 522 123 456',
            'email' => 'contact@bloodroots.ma',
            'website' => 'https://www.bloodroots.ma',
            'tax_number' => '12345678901234',
            'registration_number' => 'RC 123456',
            'logo_path' => null, // À mettre à jour manuellement avec le chemin du logo
            'bank_name' => 'Attijariwafa Bank',
            'bank_account' => '007 123 456789012345 67',
            'iban' => 'MA64 007 123 456789012345 67',
        ]);
    }
}
