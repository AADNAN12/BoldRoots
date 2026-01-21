<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        DB::table('product_images')->truncate();
        DB::table('product_variants')->truncate();
        DB::table('promotion_products')->truncate();
        DB::table('coupon_products')->truncate();
        DB::table('products')->truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = DB::table('categories')->pluck('id', 'slug')->toArray();
        $colors = DB::table('attribute_values')
            ->where('attribute_id', DB::table('attributes')->where('type', 'color')->value('id'))
            ->pluck('id', 'value')
            ->toArray();
        $sizes = DB::table('attribute_values')
            ->where('attribute_id', DB::table('attributes')->where('type', 'select')->value('id'))
            ->pluck('id', 'value')
            ->toArray();

        $products = [
            [
                'category' => 'tshirts-essentiels',
                'name' => 'T-Shirt Essential Noir',
                'slug' => 't-shirt-essential-noir',
                'sku' => 'TSH-ESS-001',
                'description' => 'T-shirt essentiel en coton premium. Coupe classique, confortable pour un usage quotidien. Logo BOOLD ROOTS brodé sur la poitrine.',
                'price' => 299.00,
                'compare_price' => 399.00,
                'is_new' => true,
                'is_featured' => true,
                'colors' => ['Noir', 'Blanc', 'Gris'],
                'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
                'stock_per_variant' => 50,
            ],
            [
                'category' => 'tshirts-essentiels',
                'name' => 'T-Shirt Struggle Edition',
                'slug' => 't-shirt-struggle-edition',
                'sku' => 'TSH-STR-002',
                'description' => 'T-shirt avec imprimé "STRUGGLE | ENDURE | WIN". Design exclusif, tissu respirant et durable.',
                'price' => 349.00,
                'compare_price' => 449.00,
                'is_new' => true,
                'is_featured' => true,
                'colors' => ['Noir', 'Blanc', 'Gris Foncé'],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'stock_per_variant' => 40,
            ],
            [
                'category' => 'hoodies-classiques',
                'name' => 'Hoodie Classic Black',
                'slug' => 'hoodie-classic-black',
                'sku' => 'HOD-CLS-003',
                'description' => 'Sweat à capuche classique en molleton épais. Poche kangourou, cordon de serrage. Parfait pour les journées fraîches.',
                'price' => 699.00,
                'compare_price' => 899.00,
                'is_new' => false,
                'is_featured' => true,
                'colors' => ['Noir', 'Gris', 'Bleu Marine'],
                'sizes' => ['M', 'L', 'XL', 'XXL'],
                'stock_per_variant' => 30,
            ],
            [
                'category' => 'hoodies-classiques',
                'name' => 'Hoodie Urban Roots',
                'slug' => 'hoodie-urban-roots',
                'sku' => 'HOD-URB-004',
                'description' => 'Hoodie premium avec logo BOOLD ROOTS brodé. Coupe oversize, confort maximum.',
                'price' => 799.00,
                'compare_price' => 999.00,
                'is_new' => true,
                'is_featured' => false,
                'colors' => ['Noir', 'Gris Clair', 'Beige'],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'stock_per_variant' => 35,
            ],
            [
                'category' => 'sweats-premium',
                'name' => 'Sweat Premium Crewneck',
                'slug' => 'sweat-premium-crewneck',
                'sku' => 'SWT-PRE-005',
                'description' => 'Sweat col rond premium en coton biologique. Finitions soignées, coupe ajustée.',
                'price' => 599.00,
                'compare_price' => 749.00,
                'is_new' => false,
                'is_featured' => true,
                'colors' => ['Noir', 'Blanc', 'Gris', 'Beige'],
                'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
                'stock_per_variant' => 45,
            ],
            [
                'category' => 'pantalons-confort',
                'name' => 'Jogger Comfort Fit',
                'slug' => 'jogger-comfort-fit',
                'sku' => 'JOG-COM-006',
                'description' => 'Pantalon jogger en coton stretch. Taille élastique, poches zippées. Idéal pour le sport et le casual.',
                'price' => 549.00,
                'compare_price' => 699.00,
                'is_new' => true,
                'is_featured' => false,
                'colors' => ['Noir', 'Gris Foncé', 'Bleu Marine'],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'stock_per_variant' => 40,
            ],
            [
                'category' => 'collection-hiver-2026',
                'name' => 'Veste Hiver Limited 2026',
                'slug' => 'veste-hiver-limited-2026',
                'sku' => 'VES-HIV-007',
                'description' => 'Veste d\'hiver édition limitée. Isolation thermique, imperméable, design exclusif collection 2026.',
                'price' => 1299.00,
                'compare_price' => 1599.00,
                'is_new' => true,
                'is_featured' => true,
                'colors' => ['Noir', 'Kaki'],
                'sizes' => ['M', 'L', 'XL'],
                'stock_per_variant' => 20,
            ],
            [
                'category' => 'series-numerotees',
                'name' => 'T-Shirt Numéroté #001-100',
                'slug' => 't-shirt-numerote-001-100',
                'sku' => 'TSH-NUM-008',
                'description' => 'T-shirt en série limitée numérotée de 1 à 100. Chaque pièce est unique avec son numéro brodé.',
                'price' => 499.00,
                'compare_price' => 649.00,
                'is_new' => true,
                'is_featured' => true,
                'colors' => ['Noir', 'Blanc'],
                'sizes' => ['M', 'L', 'XL'],
                'stock_per_variant' => 15,
            ],
            [
                'category' => 'street-artists',
                'name' => 'T-Shirt Collab Street Art',
                'slug' => 't-shirt-collab-street-art',
                'sku' => 'TSH-ART-009',
                'description' => 'Collaboration exclusive avec un artiste de rue local. Design unique, impression haute qualité.',
                'price' => 449.00,
                'compare_price' => 599.00,
                'is_new' => true,
                'is_featured' => true,
                'colors' => ['Noir', 'Blanc', 'Gris'],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'stock_per_variant' => 25,
            ],
            [
                'category' => 'designers-urbains',
                'name' => 'Hoodie Designer Urban',
                'slug' => 'hoodie-designer-urban',
                'sku' => 'HOD-DES-010',
                'description' => 'Hoodie créé en collaboration avec un designer urbain renommé. Pièce collector, édition limitée.',
                'price' => 899.00,
                'compare_price' => 1199.00,
                'is_new' => true,
                'is_featured' => true,
                'colors' => ['Noir', 'Gris Foncé'],
                'sizes' => ['M', 'L', 'XL', 'XXL'],
                'stock_per_variant' => 20,
            ],
        ];

        foreach ($products as $productData) {
            $categoryId = $categories[$productData['category']] ?? null;
            
            $productId = DB::table('products')->insertGetId([
                'category_id' => $categoryId,
                'name' => $productData['name'],
                'slug' => $productData['slug'],
                'sku' => $productData['sku'],
                'description' => $productData['description'],
                'price' => $productData['price'],
                'compare_price' => $productData['compare_price'],
                'is_new' => $productData['is_new'],
                'is_featured' => $productData['is_featured'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($productData['colors'] as $colorName) {
                $colorId = $colors[$colorName] ?? null;
                if (!$colorId) continue;

                DB::table('product_images')->insert([
                    'product_id' => $productId,
                    'color_id' => $colorId,
                    'image_path' => 'products/placeholder-' . strtolower(str_replace(' ', '-', $colorName)) . '.jpg',
                    'is_primary' => true,
                    'sort_order' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ($productData['sizes'] as $sizeName) {
                    $sizeId = $sizes[$sizeName] ?? null;
                    if (!$sizeId) continue;

                    DB::table('product_variants')->insert([
                        'product_id' => $productId,
                        'color_id' => $colorId,
                        'size_id' => $sizeId,
                        'quantity' => $productData['stock_per_variant'],
                        'low_stock_threshold' => 5,
                        'sku' => $productData['sku'] . '-' . strtoupper(substr($colorName, 0, 3)) . '-' . $sizeName,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        $this->command->info('✅ ' . count($products) . ' produits créés avec succès avec leurs variantes et images !');
    }
}
