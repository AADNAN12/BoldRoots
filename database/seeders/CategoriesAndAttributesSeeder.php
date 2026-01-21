<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriesAndAttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Désactiver les contraintes de clés étrangères temporairement
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Vider les tables
        DB::table('attribute_values')->truncate();
        DB::table('attributes')->truncate();
        DB::table('categories')->truncate();

        // Réactiver les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // =====================================
        // 1. CRÉER LES CATÉGORIES
        // =====================================
        
        $this->createCategories();
        
        // =====================================
        // 2. CRÉER LES ATTRIBUTS
        // =====================================
        
        $this->createAttributes();

        $this->command->info('✅ Catégories et attributs créés avec succès !');
    }

    /**
     * Créer les catégories de vêtements
     */
    private function createCategories(): void
    {
        $categories = [
            // BASICS ROOTS
            [
                'name' => 'BASICS ROOTS',
                'slug' => 'basics-roots',
                'description' => 'Collection essentielle BOOLD ROOTS - Les basiques intemporels',
                'parent_id' => null,
                'is_active' => true,
                'children' => [
                    [
                        'name' => 'T-Shirts Essentiels',
                        'slug' => 'tshirts-essentiels',
                        'description' => 'T-shirts basiques et essentiels pour tous les jours',
                    ],
                    [
                        'name' => 'Hoodies Classiques',
                        'slug' => 'hoodies-classiques',
                        'description' => 'Sweats à capuche classiques et confortables',
                    ],
                    [
                        'name' => 'Sweats Premium',
                        'slug' => 'sweats-premium',
                        'description' => 'Sweats de qualité supérieure',
                    ],
                    [
                        'name' => 'Pantalons Confort',
                        'slug' => 'pantalons-confort',
                        'description' => 'Pantalons confortables pour un style décontracté',
                    ],
                ]
            ],

            // LIMITED EDITION
            [
                'name' => 'LIMITED EDITION',
                'slug' => 'limited-edition',
                'description' => 'Collections limitées et exclusives - Pièces uniques en quantité limitée',
                'parent_id' => null,
                'is_active' => true,
                'children' => [
                    [
                        'name' => 'Collection Hiver 2026',
                        'slug' => 'collection-hiver-2026',
                        'description' => 'Collection exclusive hiver 2026',
                    ],
                    [
                        'name' => 'Séries Numérotées',
                        'slug' => 'series-numerotees',
                        'description' => 'Pièces numérotées en édition limitée',
                    ],
                    [
                        'name' => 'Pièces Exclusives',
                        'slug' => 'pieces-exclusives',
                        'description' => 'Articles exclusifs disponibles en quantité limitée',
                    ],
                    [
                        'name' => 'Drop du Mois',
                        'slug' => 'drop-du-mois',
                        'description' => 'Nouveautés mensuelles en édition limitée',
                    ],
                ]
            ],

            // ARTISTS COLLABS
            [
                'name' => 'ARTISTS COLLABS',
                'slug' => 'artists-collabs',
                'description' => 'Collaborations artistiques exclusives avec des créateurs urbains',
                'parent_id' => null,
                'is_active' => true,
                'children' => [
                    [
                        'name' => 'Street Artists',
                        'slug' => 'street-artists',
                        'description' => 'Collaborations avec des artistes de rue',
                    ],
                    [
                        'name' => 'Designers Urbains',
                        'slug' => 'designers-urbains',
                        'description' => 'Créations de designers urbains contemporains',
                    ],
                    [
                        'name' => 'Illustrateurs',
                        'slug' => 'illustrateurs',
                        'description' => 'Collaborations avec des illustrateurs talentueux',
                    ],
                    [
                        'name' => 'Collaborations Spéciales',
                        'slug' => 'collaborations-speciales',
                        'description' => 'Projets spéciaux et collaborations uniques',
                    ],
                ]
            ],

            // ABOUT US
            [
                'name' => 'ABOUT US',
                'slug' => 'about-us',
                'description' => 'Découvrez l\'univers BOOLD ROOTS - Notre histoire, nos valeurs',
                'parent_id' => null,
                'is_active' => true,
            ],
        ];

        $this->insertCategories($categories);
    }

    /**
     * Insertion récursive des catégories
     */
    private function insertCategories(array $categories, $parentId = null): void
    {
        foreach ($categories as $category) {
            $children = $category['children'] ?? [];
            unset($category['children']);

            $categoryId = DB::table('categories')->insertGetId([
                'name' => $category['name'],
                'slug' => $category['slug'],
                'description' => $category['description'] ?? null,
                'parent_id' => $parentId,
                'is_active' => $category['is_active'] ?? true,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (!empty($children)) {
                $this->insertCategories($children, $categoryId);
            }
        }
    }

    /**
     * Créer les attributs (Couleurs et Tailles)
     */
    private function createAttributes(): void
    {
        // =====================================
        // ATTRIBUT : COULEUR
        // =====================================
        
        $colorAttributeId = DB::table('attributes')->insertGetId([
            'name' => 'Couleur',
            'slug' => 'couleur',
            'type' => 'color',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $colors = [
            ['value' => 'Noir', 'color_code' => '#000000'],
            ['value' => 'Blanc', 'color_code' => '#FFFFFF'],
            ['value' => 'Gris', 'color_code' => '#808080'],
            ['value' => 'Gris Clair', 'color_code' => '#D3D3D3'],
            ['value' => 'Gris Foncé', 'color_code' => '#404040'],
            ['value' => 'Beige', 'color_code' => '#F5F5DC'],
            ['value' => 'Marron', 'color_code' => '#8B4513'],
            ['value' => 'Bleu Marine', 'color_code' => '#1E3A5F'],
            ['value' => 'Bleu Ciel', 'color_code' => '#87CEEB'],
            ['value' => 'Bleu Royal', 'color_code' => '#4169E1'],
            ['value' => 'Bleu Électrique', 'color_code' => '#0000FF'],
            ['value' => 'Rouge', 'color_code' => '#FF0000'],
            ['value' => 'Bordeaux', 'color_code' => '#800020'],
            ['value' => 'Rose', 'color_code' => '#FFC0CB'],
            ['value' => 'Rose Fuchsia', 'color_code' => '#FF00FF'],
            ['value' => 'Vert', 'color_code' => '#008000'],
            ['value' => 'Vert Olive', 'color_code' => '#808000'],
            ['value' => 'Vert Forêt', 'color_code' => '#228B22'],
            ['value' => 'Vert Menthe', 'color_code' => '#98FF98'],
            ['value' => 'Jaune', 'color_code' => '#FFFF00'],
            ['value' => 'Jaune Moutarde', 'color_code' => '#FFDB58'],
            ['value' => 'Orange', 'color_code' => '#FFA500'],
            ['value' => 'Orange Brûlé', 'color_code' => '#CC5500'],
            ['value' => 'Violet', 'color_code' => '#800080'],
            ['value' => 'Lavande', 'color_code' => '#E6E6FA'],
            ['value' => 'Camel', 'color_code' => '#C19A6B'],
            ['value' => 'Kaki', 'color_code' => '#C3B091'],
            ['value' => 'Marine', 'color_code' => '#000080'],
        ];

        foreach ($colors as $color) {
            DB::table('attribute_values')->insert([
                'attribute_id' => $colorAttributeId,
                'value' => $color['value'],
                'color_code' => $color['color_code'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // =====================================
        // ATTRIBUT : TAILLE
        // =====================================
        
        $sizeAttributeId = DB::table('attributes')->insertGetId([
            'name' => 'Taille',
            'slug' => 'taille',
            'type' => 'select',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $sizes = [
            // Tailles standards
            'XXS',
            'XS',
            'S',
            'M',
            'L',
            'XL',
            'XXL',
            'XXXL',
            
            // Tailles numériques (Pantalons)
            '28',
            '30',
            '32',
            '34',
            '36',
            '38',
            '40',
            '42',
            '44',
            '46',
            '48',
            
            // Tailles chaussures (EU)
            '36',
            '37',
            '38',
            '39',
            '40',
            '41',
            '42',
            '43',
            '44',
            '45',
            '46',
            
            // Tailles enfant
            '2 ans',
            '3 ans',
            '4 ans',
            '5 ans',
            '6 ans',
            '8 ans',
            '10 ans',
            '12 ans',
            '14 ans',
            '16 ans',
            
            // Taille unique
            'Taille Unique',
        ];

        foreach ($sizes as $size) {
            DB::table('attribute_values')->insert([
                'attribute_id' => $sizeAttributeId,
                'value' => $size,
                'color_code' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ ' . count($colors) . ' couleurs créées');
        $this->command->info('✅ ' . count($sizes) . ' tailles créées');
    }
}