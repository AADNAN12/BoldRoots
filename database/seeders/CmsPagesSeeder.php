<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CmsPage;

class CmsPagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'contact',
                'title' => 'Contact',
                'content' => '<h1>Contactez-nous</h1><p>Vous pouvez nous contacter via notre formulaire de contact.</p>',
                'is_active' => true,
                'order' => 1
            ],
            [
                'slug' => 'politique-de-confidentialite',
                'title' => 'Politique de confidentialité',
                'content' => '<h1>Politique de confidentialité</h1><p>Votre vie privée est importante pour nous.</p>',
                'is_active' => true,
                'order' => 2
            ],
            [
                'slug' => 'remboursements-et-retours',
                'title' => 'Remboursements et Retours',
                'content' => '<h1>Remboursements et Retours</h1><p>Politique de remboursement et de retour.</p>',
                'is_active' => true,
                'order' => 3
            ],
            [
                'slug' => 'politique-de-livraison',
                'title' => 'Politique de livraison',
                'content' => '<h1>Politique de livraison</h1><p>Informations sur nos délais et modes de livraison.</p>',
                'is_active' => true,
                'order' => 4
            ],
            [
                'slug' => 'termes-et-conditions',
                'title' => 'Termes & Conditions',
                'content' => '<h1>Termes & Conditions</h1><p>Conditions générales d\'utilisation.</p>',
                'is_active' => true,
                'order' => 5
            ],
            [
                'slug' => 'mentions-legales',
                'title' => 'Mentions Légales',
                'content' => '<h1>Mentions Légales</h1><p>Informations légales sur notre entreprise.</p>',
                'is_active' => true,
                'order' => 6
            ],
        ];

        foreach ($pages as $page) {
            CmsPage::updateOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }
    }
}
