<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PromotionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        DB::table('promotion_products')->truncate();
        DB::table('promotion_categories')->truncate();
        DB::table('promotions')->truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $promotions = [
            [
                'name' => 'Flash Deal - T-Shirts Essentiels',
                'description' => 'Promotion flash sur tous les t-shirts essentiels ! Profitez de -30% pendant 48h seulement.',
                'type' => 'flash_deal',
                'discount_type' => 'percentage',
                'discount_value' => 30.00,
                'start_date' => Carbon::now()->subDays(1),
                'end_date' => Carbon::now()->addDays(1),
                'is_active' => true,
                'scope' => 'product',
                'max_per_customer' => 3,
                'stop_when_stock_below' => 5,
                'min_cart_value' => null,
                'exclude_new_products' => false,
                'usage_limit' => 100,
                'products' => ['t-shirt-essential-noir', 't-shirt-struggle-edition'],
            ],
            [
                'name' => 'Soldes Hiver - Hoodies',
                'description' => 'Soldes d\'hiver sur notre collection de hoodies ! Réduction de 150 MAD sur tous les hoodies.',
                'type' => 'regular_sale',
                'discount_type' => 'fixed_amount',
                'discount_value' => 150.00,
                'start_date' => Carbon::now()->subDays(7),
                'end_date' => Carbon::now()->addDays(23),
                'is_active' => true,
                'scope' => 'product',
                'max_per_customer' => 2,
                'stop_when_stock_below' => 3,
                'min_cart_value' => 500.00,
                'exclude_new_products' => false,
                'usage_limit' => null,
                'products' => ['hoodie-classic-black', 'hoodie-urban-roots'],
            ],
            [
                'name' => 'Promo Sweats Premium -25%',
                'description' => 'Promotion exclusive sur les sweats premium. Qualité supérieure à prix réduit !',
                'type' => 'regular_sale',
                'discount_type' => 'percentage',
                'discount_value' => 25.00,
                'start_date' => Carbon::now()->subDays(3),
                'end_date' => Carbon::now()->addDays(10),
                'is_active' => true,
                'scope' => 'product',
                'max_per_customer' => 5,
                'stop_when_stock_below' => 10,
                'min_cart_value' => null,
                'exclude_new_products' => false,
                'usage_limit' => 200,
                'products' => ['sweat-premium-crewneck'],
            ],
            [
                'name' => 'Offre Spéciale Joggers',
                'description' => 'Achetez 2 joggers, obtenez 20% de réduction sur votre commande !',
                'type' => 'regular_sale',
                'discount_type' => 'percentage',
                'discount_value' => 20.00,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(15),
                'is_active' => true,
                'scope' => 'product',
                'max_per_customer' => 1,
                'stop_when_stock_below' => 5,
                'min_cart_value' => 1000.00,
                'exclude_new_products' => false,
                'usage_limit' => 50,
                'products' => ['jogger-comfort-fit'],
            ],
            [
                'name' => 'Collection Hiver 2026 - Lancement',
                'description' => 'Lancement de la collection hiver 2026 ! -15% sur toutes les pièces de la nouvelle collection.',
                'type' => 'regular_sale',
                'discount_type' => 'percentage',
                'discount_value' => 15.00,
                'start_date' => Carbon::now()->subDays(2),
                'end_date' => Carbon::now()->addDays(28),
                'is_active' => true,
                'scope' => 'product',
                'max_per_customer' => 3,
                'stop_when_stock_below' => 5,
                'min_cart_value' => null,
                'exclude_new_products' => false,
                'usage_limit' => null,
                'products' => ['veste-hiver-limited-2026'],
            ],
            [
                'name' => 'Série Numérotée - Édition Limitée',
                'description' => 'Pièces numérotées en édition ultra-limitée. Réduction de 100 MAD pour les premiers acheteurs !',
                'type' => 'flash_deal',
                'discount_type' => 'fixed_amount',
                'discount_value' => 100.00,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addHours(72),
                'is_active' => true,
                'scope' => 'product',
                'max_per_customer' => 1,
                'stop_when_stock_below' => 2,
                'min_cart_value' => null,
                'exclude_new_products' => false,
                'usage_limit' => 30,
                'products' => ['t-shirt-numerote-001-100'],
            ],
            [
                'name' => 'Collab Street Art -20%',
                'description' => 'Collaboration exclusive avec des artistes de rue. Profitez de -20% sur cette collection unique !',
                'type' => 'regular_sale',
                'discount_type' => 'percentage',
                'discount_value' => 20.00,
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->addDays(20),
                'is_active' => true,
                'scope' => 'product',
                'max_per_customer' => 2,
                'stop_when_stock_below' => 5,
                'min_cart_value' => null,
                'exclude_new_products' => false,
                'usage_limit' => 100,
                'products' => ['t-shirt-collab-street-art', 'hoodie-designer-urban'],
            ],
        ];

        foreach ($promotions as $promoData) {
            $productSlugs = $promoData['products'];
            unset($promoData['products']);

            $promotionId = DB::table('promotions')->insertGetId([
                'name' => $promoData['name'],
                'description' => $promoData['description'],
                'type' => $promoData['type'],
                'discount_type' => $promoData['discount_type'],
                'discount_value' => $promoData['discount_value'],
                'start_date' => $promoData['start_date'],
                'end_date' => $promoData['end_date'],
                'is_active' => $promoData['is_active'],
                'scope' => $promoData['scope'],
                'max_per_customer' => $promoData['max_per_customer'],
                'stop_when_stock_below' => $promoData['stop_when_stock_below'],
                'min_cart_value' => $promoData['min_cart_value'],
                'exclude_new_products' => $promoData['exclude_new_products'],
                'total_usage_count' => 0,
                'usage_limit' => $promoData['usage_limit'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($productSlugs as $slug) {
                $productId = DB::table('products')->where('slug', $slug)->value('id');
                if ($productId) {
                    DB::table('promotion_products')->insert([
                        'promotion_id' => $promotionId,
                        'product_id' => $productId,
                    ]);
                }
            }
        }

        $this->command->info('✅ ' . count($promotions) . ' promotions créées avec succès !');
    }
}
