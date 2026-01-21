<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Coupon;

class PriceService
{
    protected $promotionService;
    protected $couponService;
    protected $discountCalculator;

    public function __construct(
        PromotionService $promotionService,
        CouponService $couponService,
        DiscountCalculator $discountCalculator
    ) {
        $this->promotionService = $promotionService;
        $this->couponService = $couponService;
        $this->discountCalculator = $discountCalculator;
    }

    /**
     * Calculer le prix final d'un produit avec toutes les promotions
     */
    public function calculateProductPrice(Product $product, int $quantity = 1, ?int $userId = null): array
    {
        $originalPrice = $product->price * $quantity;
        
        // Appliquer la meilleure promotion disponible
        $promotionResult = $this->promotionService->applyBestPromotionToProduct($product, $quantity);
        
        return [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => $quantity,
            'unit_price' => round($product->price, 2),
            'original_price' => round($originalPrice, 2),
            'promotion' => $promotionResult['applied'] ? [
                'id' => $promotionResult['promotion_id'] ?? null,
                'name' => $promotionResult['promotion_name'] ?? null,
                'type' => $promotionResult['promotion_type'] ?? null,
                'discount_amount' => $promotionResult['discount_amount'] ?? 0,
            ] : null,
            'final_price' => $promotionResult['final_price'] ?? $originalPrice,
            'savings' => $promotionResult['applied'] ? 
                round($originalPrice - ($promotionResult['final_price'] ?? $originalPrice), 2) : 0,
        ];
    }

    /**
     * Calculer le prix total du panier avec promotions et coupons
     */
    public function calculateCartTotal(array $cartItems, ?string $couponCode = null, ?int $userId = null): array
    {
        $itemsWithPrices = [];
        $subtotal = 0;
        $totalPromotionDiscount = 0;

        // Calculer le prix de chaque article avec ses promotions
        foreach ($cartItems as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) {
                continue;
            }

            $priceDetails = $this->calculateProductPrice($product, $item['quantity'], $userId);
            $itemsWithPrices[] = $priceDetails;
            
            $subtotal += $priceDetails['original_price'];
            $totalPromotionDiscount += $priceDetails['savings'];
        }

        $subtotalAfterPromotions = $subtotal - $totalPromotionDiscount;

        // Appliquer les promotions de type panier
        $cartPromotions = $this->promotionService->getCartPromotions();
        $bestCartPromotion = null;
        $cartPromotionDiscount = 0;

        foreach ($cartPromotions as $promotion) {
            $result = $this->promotionService->applyPromotionToCart($promotion, $cartItems);
            if ($result['applied'] && $result['discount_amount'] > $cartPromotionDiscount) {
                $bestCartPromotion = $result;
                $cartPromotionDiscount = $result['discount_amount'];
            }
        }

        $subtotalAfterCartPromotion = $subtotalAfterPromotions - $cartPromotionDiscount;

        // Appliquer le coupon si fourni
        $couponDiscount = 0;
        $couponDetails = null;

        if ($couponCode) {
            $validation = $this->couponService->validateCoupon($couponCode, $userId, $subtotalAfterCartPromotion);
            
            if ($validation['valid']) {
                $coupon = $validation['coupon'];
                $couponResult = $this->couponService->applyCouponToCart($coupon, $cartItems, $userId);
                
                if ($couponResult['applied']) {
                    // Calculer la réduction du coupon sur le montant après promotions
                    $couponCalculation = $this->discountCalculator->calculateDiscount(
                        $subtotalAfterCartPromotion,
                        $coupon->discount_type,
                        $coupon->discount_value
                    );
                    
                    $couponDiscount = $couponCalculation['discount_amount'];
                    
                    // Appliquer le plafond si défini
                    if ($coupon->max_discount_amount && $couponDiscount > $coupon->max_discount_amount) {
                        $couponDiscount = $coupon->max_discount_amount;
                    }
                    
                    $couponDetails = [
                        'code' => $coupon->code,
                        'description' => $coupon->description,
                        'discount_amount' => round($couponDiscount, 2),
                    ];
                }
            }
        }

        $finalTotal = max(0, $subtotalAfterCartPromotion - $couponDiscount);
        $totalSavings = $subtotal - $finalTotal;

        return [
            'items' => $itemsWithPrices,
            'subtotal' => round($subtotal, 2),
            'promotion_discount' => round($totalPromotionDiscount, 2),
            'cart_promotion' => $bestCartPromotion ? [
                'name' => $bestCartPromotion['promotion_name'],
                'discount_amount' => round($cartPromotionDiscount, 2),
            ] : null,
            'coupon' => $couponDetails,
            'total_discount' => round($totalPromotionDiscount + $cartPromotionDiscount + $couponDiscount, 2),
            'final_total' => round($finalTotal, 2),
            'total_savings' => round($totalSavings, 2),
            'savings_percentage' => $subtotal > 0 ? round(($totalSavings / $subtotal) * 100, 2) : 0,
        ];
    }

    /**
     * Obtenir le résumé des économies pour un utilisateur
     */
    public function getUserSavingsSummary(int $userId): array
    {
        // TODO: Implémenter le calcul des économies totales de l'utilisateur
        // Nécessite de récupérer l'historique des commandes
        
        return [
            'total_orders' => 0,
            'total_spent' => 0,
            'total_savings' => 0,
            'average_savings_per_order' => 0,
        ];
    }

    /**
     * Vérifier si un produit a une promotion active
     */
    public function hasActivePromotion(int $productId): bool
    {
        $promotions = $this->promotionService->getProductPromotions($productId);
        return $promotions->isNotEmpty();
    }

    /**
     * Obtenir le badge de promotion pour un produit
     */
    public function getPromotionBadge(int $productId): ?array
    {
        $promotions = $this->promotionService->getProductPromotions($productId);
        
        if ($promotions->isEmpty()) {
            return null;
        }

        $promotion = $promotions->first();
        
        $badgeType = 'sale';
        $badgeText = 'PROMO';
        
        if ($promotion->type === 'flash_deal') {
            $badgeType = 'flash';
            $badgeText = 'FLASH';
        } elseif ($promotion->discount_type === 'percentage') {
            $badgeText = "-{$promotion->discount_value}%";
        }

        return [
            'type' => $badgeType,
            'text' => $badgeText,
            'color' => $promotion->type === 'flash_deal' ? 'danger' : 'warning',
        ];
    }

    /**
     * Comparer les prix avec et sans promotions
     */
    public function comparePrices(Product $product, int $quantity = 1): array
    {
        $withPromotion = $this->calculateProductPrice($product, $quantity);
        $withoutPromotion = [
            'final_price' => $product->price * $quantity,
            'savings' => 0,
        ];

        return [
            'with_promotion' => $withPromotion,
            'without_promotion' => $withoutPromotion,
            'has_discount' => $withPromotion['savings'] > 0,
            'discount_percentage' => $product->price > 0 ? 
                round(($withPromotion['savings'] / ($product->price * $quantity)) * 100, 2) : 0,
        ];
    }

    /**
     * Calculer le prix minimum pour bénéficier d'une promotion panier
     */
    public function getMinimumForCartPromotion(): ?array
    {
        $cartPromotions = $this->promotionService->getCartPromotions();
        
        if ($cartPromotions->isEmpty()) {
            return null;
        }

        $bestPromotion = $cartPromotions->sortBy('minimum_purchase_amount')->first();
        
        return [
            'promotion_name' => $bestPromotion->name,
            'minimum_amount' => $bestPromotion->minimum_purchase_amount,
            'discount_type' => $bestPromotion->discount_type,
            'discount_value' => $bestPromotion->discount_value,
        ];
    }

    /**
     * Calculer combien il manque pour atteindre la livraison gratuite ou une promotion
     */
    public function calculateAmountToThreshold(float $currentTotal, float $threshold): array
    {
        $amountNeeded = $this->discountCalculator->calculateAmountToMinimum($currentTotal, $threshold);
        
        return [
            'current_total' => round($currentTotal, 2),
            'threshold' => round($threshold, 2),
            'amount_needed' => $amountNeeded,
            'percentage_reached' => $threshold > 0 ? round(($currentTotal / $threshold) * 100, 2) : 100,
            'threshold_reached' => $amountNeeded <= 0,
        ];
    }

    /**
     * Obtenir les promotions actives pour l'affichage
     */
    public function getActivePromotionsForDisplay(): array
    {
        $promotions = $this->promotionService->getActivePromotions();
        
        return $promotions->map(function($promotion) {
            return [
                'id' => $promotion->id,
                'name' => $promotion->name,
                'description' => $promotion->description,
                'type' => $promotion->type,
                'discount_type' => $promotion->discount_type,
                'discount_value' => $promotion->discount_value,
                'end_date' => $promotion->end_date,
                'badge' => $this->getPromotionDisplayBadge($promotion),
            ];
        })->toArray();
    }

    /**
     * Obtenir le badge d'affichage pour une promotion
     */
    protected function getPromotionDisplayBadge($promotion): array
    {
        $badge = [
            'text' => 'PROMO',
            'color' => 'warning',
        ];

        if ($promotion->type === 'flash_deal') {
            $badge['text'] = 'FLASH DEAL';
            $badge['color'] = 'danger';
        } elseif ($promotion->discount_type === 'percentage') {
            $badge['text'] = "-{$promotion->discount_value}%";
        } elseif ($promotion->discount_type === 'fixed_amount') {
            $badge['text'] = "-{$promotion->discount_value} MAD";
        }

        return $badge;
    }
}
