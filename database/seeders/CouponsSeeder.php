<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CouponsSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        DB::table('coupon_products')->truncate();
        DB::table('coupon_categories')->truncate();
        DB::table('coupons')->truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $coupons = [
            [
                'code' => 'WELCOME2026',
                'description' => 'Code de bienvenue pour les nouveaux clients. 15% de réduction sur votre première commande !',
                'type' => 'percentage',
                'discount_value' => 15.00,
                'valid_from' => Carbon::now()->subDays(30),
                'valid_until' => Carbon::now()->addDays(60),
                'is_active' => true,
                'usage_limit' => 500,
                'usage_per_customer' => 1,
                'used_count' => 0,
                'min_cart_value' => 300.00,
                'exclude_new_products' => false,
                'products' => [],
            ],
            [
                'code' => 'WINTER50',
                'description' => 'Coupon hiver ! Réduction de 50 MAD sur tous les hoodies et sweats.',
                'type' => 'fixed_amount',
                'discount_value' => 50.00,
                'valid_from' => Carbon::now()->subDays(10),
                'valid_until' => Carbon::now()->addDays(20),
                'is_active' => true,
                'usage_limit' => 200,
                'usage_per_customer' => 2,
                'used_count' => 0,
                'min_cart_value' => 500.00,
                'exclude_new_products' => false,
                'products' => ['hoodie-classic-black', 'hoodie-urban-roots', 'sweat-premium-crewneck'],
            ],
            [
                'code' => 'FREESHIP',
                'description' => 'Livraison gratuite sur toutes les commandes sans minimum d\'achat !',
                'type' => 'free_shipping',
                'discount_value' => 0.00,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addDays(30),
                'is_active' => true,
                'usage_limit' => 1000,
                'usage_per_customer' => 1,
                'used_count' => 0,
                'min_cart_value' => null,
                'exclude_new_products' => false,
                'products' => [],
            ],
            [
                'code' => 'TSHIRT20',
                'description' => 'Spécial t-shirts ! 20% de réduction sur tous les t-shirts de la collection.',
                'type' => 'percentage',
                'discount_value' => 20.00,
                'valid_from' => Carbon::now()->subDays(5),
                'valid_until' => Carbon::now()->addDays(15),
                'is_active' => true,
                'usage_limit' => 150,
                'usage_per_customer' => 3,
                'used_count' => 0,
                'min_cart_value' => 250.00,
                'exclude_new_products' => false,
                'products' => ['t-shirt-essential-noir', 't-shirt-struggle-edition', 't-shirt-collab-street-art', 't-shirt-numerote-001-100'],
            ],
            [
                'code' => 'MEGA100',
                'description' => 'Méga promotion ! 100 MAD de réduction sur les commandes de plus de 1000 MAD.',
                'type' => 'fixed_amount',
                'discount_value' => 100.00,
                'valid_from' => Carbon::now()->subDays(3),
                'valid_until' => Carbon::now()->addDays(25),
                'is_active' => true,
                'usage_limit' => 100,
                'usage_per_customer' => 1,
                'used_count' => 0,
                'min_cart_value' => 1000.00,
                'exclude_new_products' => false,
                'products' => [],
            ],
            [
                'code' => 'LIMITED30',
                'description' => 'Édition limitée ! 30% de réduction sur les collections limitées et collaborations.',
                'type' => 'percentage',
                'discount_value' => 30.00,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addDays(10),
                'is_active' => true,
                'usage_limit' => 50,
                'usage_per_customer' => 1,
                'used_count' => 0,
                'min_cart_value' => 400.00,
                'exclude_new_products' => false,
                'products' => ['veste-hiver-limited-2026', 't-shirt-numerote-001-100', 'hoodie-designer-urban'],
            ],
            [
                'code' => 'JOGGER25',
                'description' => 'Promotion joggers ! 25% de réduction sur tous les pantalons confort.',
                'type' => 'percentage',
                'discount_value' => 25.00,
                'valid_from' => Carbon::now()->subDays(7),
                'valid_until' => Carbon::now()->addDays(14),
                'is_active' => true,
                'usage_limit' => 80,
                'usage_per_customer' => 2,
                'used_count' => 0,
                'min_cart_value' => 300.00,
                'exclude_new_products' => false,
                'products' => ['jogger-comfort-fit'],
            ],
            [
                'code' => 'ARTIST15',
                'description' => 'Soutenez les artistes ! 15% de réduction sur toutes les collaborations artistiques.',
                'type' => 'percentage',
                'discount_value' => 15.00,
                'valid_from' => Carbon::now()->subDays(2),
                'valid_until' => Carbon::now()->addDays(30),
                'is_active' => true,
                'usage_limit' => 120,
                'usage_per_customer' => 2,
                'used_count' => 0,
                'min_cart_value' => 350.00,
                'exclude_new_products' => false,
                'products' => ['t-shirt-collab-street-art', 'hoodie-designer-urban'],
            ],
            [
                'code' => 'VIP200',
                'description' => 'Code VIP exclusif ! 200 MAD de réduction sur les commandes de plus de 1500 MAD.',
                'type' => 'fixed_amount',
                'discount_value' => 200.00,
                'valid_from' => Carbon::now()->subDays(1),
                'valid_until' => Carbon::now()->addDays(45),
                'is_active' => true,
                'usage_limit' => 30,
                'usage_per_customer' => 1,
                'used_count' => 0,
                'min_cart_value' => 1500.00,
                'exclude_new_products' => true,
                'products' => [],
            ],
            [
                'code' => 'FLASH40',
                'description' => 'Flash sale ! 40% de réduction sur une sélection de produits pendant 24h !',
                'type' => 'percentage',
                'discount_value' => 40.00,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addHours(24),
                'is_active' => true,
                'usage_limit' => 50,
                'usage_per_customer' => 1,
                'used_count' => 0,
                'min_cart_value' => 400.00,
                'exclude_new_products' => false,
                'products' => ['t-shirt-essential-noir', 'hoodie-classic-black', 'sweat-premium-crewneck'],
            ],
        ];

        foreach ($coupons as $couponData) {
            $productSlugs = $couponData['products'];
            unset($couponData['products']);

            $couponId = DB::table('coupons')->insertGetId([
                'code' => $couponData['code'],
                'description' => $couponData['description'],
                'type' => $couponData['type'],
                'discount_value' => $couponData['discount_value'],
                'valid_from' => $couponData['valid_from'],
                'valid_until' => $couponData['valid_until'],
                'is_active' => $couponData['is_active'],
                'usage_limit' => $couponData['usage_limit'],
                'usage_per_customer' => $couponData['usage_per_customer'],
                'used_count' => $couponData['used_count'],
                'min_cart_value' => $couponData['min_cart_value'],
                'exclude_new_products' => $couponData['exclude_new_products'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (!empty($productSlugs)) {
                foreach ($productSlugs as $slug) {
                    $productId = DB::table('products')->where('slug', $slug)->value('id');
                    if ($productId) {
                        DB::table('coupon_products')->insert([
                            'coupon_id' => $couponId,
                            'product_id' => $productId,
                        ]);
                    }
                }
            }
        }

        $this->command->info('✅ ' . count($coupons) . ' coupons créés avec succès !');
    }
}
