<?php

namespace App\Services;

use App\Models\Promotion;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PromotionService
{
    protected $discountCalculator;

    public function __construct(DiscountCalculator $discountCalculator)
    {
        $this->discountCalculator = $discountCalculator;
    }

    /**
     * Récupérer toutes les promotions actives
     */
    public function getActivePromotions(): Collection
    {
        return Promotion::where('is_active', true)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->get();
    }

    /**
     * Récupérer les promotions applicables à un produit
     */
    public function getProductPromotions(int $productId): Collection
    {
        $activePromotions = $this->getActivePromotions();
        
        return $activePromotions->filter(function($promotion) use ($productId) {
            // Vérifier si la promotion s'applique au produit
            if ($promotion->scope === 'product') {
                return $promotion->products()->where('product_id', $productId)->exists();
            }
            
            if ($promotion->scope === 'collection') {
                $product = Product::find($productId);
                if ($product) {
                    return $promotion->categories()->where('category_id', $product->category_id)->exists();
                }
            }
            
            return false;
        });
    }

    /**
     * Récupérer les promotions de type panier
     */
    public function getCartPromotions(): Collection
    {
        return $this->getActivePromotions()->where('scope', 'cart');
    }

    /**
     * Récupérer les flash deals actifs
     */
    public function getActiveFlashDeals(): Collection
    {
        return $this->getActivePromotions()->where('type', 'flash_deal');
    }

    /**
     * Appliquer une promotion à un produit
     */
    public function applyPromotionToProduct(Promotion $promotion, Product $product, int $quantity = 1): array
    {
        // Vérifier si la promotion est valide
        if (!$this->isPromotionValid($promotion)) {
            return [
                'applied' => false,
                'reason' => 'Promotion non valide ou expirée',
            ];
        }

        // Vérifier le stock minimum
        if ($promotion->stop_when_stock_below > 0) {
            $totalStock = $product->variants->sum('quantity');
            if ($totalStock < $promotion->stop_when_stock_below) {
                return [
                    'applied' => false,
                    'reason' => 'Stock insuffisant pour cette promotion',
                ];
            }
        }

        $originalPrice = $product->price * $quantity;

        // Appliquer selon le type de promotion
        switch ($promotion->type) {
            case 'flash_deal':
            case 'regular_sale':
                $discount = $this->discountCalculator->calculateDiscount(
                    $originalPrice,
                    $promotion->discount_type,
                    $promotion->discount_value
                );
                
                return array_merge($discount, [
                    'applied' => true,
                    'promotion_id' => $promotion->id,
                    'promotion_name' => $promotion->name,
                    'promotion_type' => $promotion->type,
                ]);

            case 'buy_x_get_y':
                $buyXGetY = $this->discountCalculator->calculateBuyXGetY(
                    $quantity,
                    $promotion->buy_quantity ?? 1,
                    $promotion->get_quantity ?? 1,
                    $product->price
                );
                
                if (!$buyXGetY['eligible']) {
                    return [
                        'applied' => false,
                        'reason' => "Quantité minimale requise: {$promotion->buy_quantity}",
                    ];
                }
                
                return array_merge($buyXGetY, [
                    'applied' => true,
                    'promotion_id' => $promotion->id,
                    'promotion_name' => $promotion->name,
                    'promotion_type' => $promotion->type,
                ]);

            default:
                return [
                    'applied' => false,
                    'reason' => 'Type de promotion non supporté',
                ];
        }
    }

    /**
     * Appliquer la meilleure promotion à un produit
     */
    public function applyBestPromotionToProduct(Product $product, int $quantity = 1): array
    {
        $promotions = $this->getProductPromotions($product->id);
        
        if ($promotions->isEmpty()) {
            return [
                'applied' => false,
                'reason' => 'Aucune promotion disponible',
                'original_price' => round($product->price * $quantity, 2),
                'final_price' => round($product->price * $quantity, 2),
            ];
        }

        $results = [];
        foreach ($promotions as $promotion) {
            $result = $this->applyPromotionToProduct($promotion, $product, $quantity);
            if ($result['applied']) {
                $results[] = $result;
            }
        }

        if (empty($results)) {
            return [
                'applied' => false,
                'reason' => 'Aucune promotion applicable',
                'original_price' => round($product->price * $quantity, 2),
                'final_price' => round($product->price * $quantity, 2),
            ];
        }

        return $this->discountCalculator->getBestDiscount($results);
    }

    /**
     * Appliquer une promotion au panier
     */
    public function applyPromotionToCart(Promotion $promotion, array $cartItems): array
    {
        if (!$this->isPromotionValid($promotion)) {
            return [
                'applied' => false,
                'reason' => 'Promotion non valide ou expirée',
            ];
        }

        if ($promotion->scope !== 'cart') {
            return [
                'applied' => false,
                'reason' => 'Cette promotion ne s\'applique pas au panier',
            ];
        }

        // Calculer le total du panier
        $cartTotal = array_reduce($cartItems, function($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        // Vérifier le montant minimum
        if ($promotion->minimum_purchase_amount && $cartTotal < $promotion->minimum_purchase_amount) {
            $amountNeeded = $this->discountCalculator->calculateAmountToMinimum(
                $cartTotal,
                $promotion->minimum_purchase_amount
            );
            
            return [
                'applied' => false,
                'reason' => "Montant minimum requis: {$promotion->minimum_purchase_amount} MAD",
                'amount_needed' => $amountNeeded,
            ];
        }

        // Appliquer la réduction
        $discount = $this->discountCalculator->calculateCartDiscount(
            $cartItems,
            $promotion->discount_type,
            $promotion->discount_value
        );

        return array_merge($discount, [
            'applied' => true,
            'promotion_id' => $promotion->id,
            'promotion_name' => $promotion->name,
            'promotion_type' => $promotion->type,
        ]);
    }

    /**
     * Vérifier si une promotion est valide
     */
    public function isPromotionValid(Promotion $promotion): bool
    {
        if (!$promotion->is_active) {
            return false;
        }

        $now = Carbon::now();
        
        if ($now->lt($promotion->start_date) || $now->gt($promotion->end_date)) {
            return false;
        }

        return true;
    }

    /**
     * Vérifier si un utilisateur peut utiliser une promotion
     */
    public function canUserUsePromotion(Promotion $promotion, int $userId): bool
    {
        if (!$promotion->max_per_customer) {
            return true;
        }

        // Compter combien de fois l'utilisateur a utilisé cette promotion
        $usageCount = $promotion->usages()
            ->where('user_id', $userId)
            ->count();

        return $usageCount < $promotion->max_per_customer;
    }

    /**
     * Enregistrer l'utilisation d'une promotion
     */
    public function recordPromotionUsage(Promotion $promotion, int $userId, int $orderId, float $discountAmount): void
    {
        $promotion->usages()->create([
            'user_id' => $userId,
            'order_id' => $orderId,
            'discount_amount' => $discountAmount,
            'used_at' => Carbon::now(),
        ]);

        // Incrémenter le compteur d'utilisation
        $promotion->increment('usage_count');
    }

    /**
     * Obtenir les statistiques d'une promotion
     */
    public function getPromotionStats(Promotion $promotion): array
    {
        $usages = $promotion->usages;
        
        return [
            'total_uses' => $usages->count(),
            'total_discount_given' => $usages->sum('discount_amount'),
            'unique_users' => $usages->pluck('user_id')->unique()->count(),
            'average_discount' => $usages->count() > 0 ? $usages->avg('discount_amount') : 0,
            'remaining_uses' => $promotion->max_uses ? max(0, $promotion->max_uses - $usages->count()) : null,
        ];
    }

    /**
     * Désactiver automatiquement les promotions expirées
     */
    public function deactivateExpiredPromotions(): int
    {
        return Promotion::where('is_active', true)
            ->where('end_date', '<', Carbon::now())
            ->update(['is_active' => false]);
    }

    /**
     * Activer automatiquement les promotions qui commencent
     */
    public function activateScheduledPromotions(): int
    {
        return Promotion::where('is_active', false)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->update(['is_active' => true]);
    }
}
