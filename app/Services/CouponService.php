<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CouponService
{
    protected $discountCalculator;

    public function __construct(DiscountCalculator $discountCalculator)
    {
        $this->discountCalculator = $discountCalculator;
    }

    /**
     * Valider un code coupon
     */
    public function validateCoupon(string $code, ?int $userId = null, ?float $cartTotal = null): array
    {
        $coupon = Coupon::where('code', strtoupper($code))->first();

       
        if (!$coupon) {
            return [
                'valid' => false,
                'message' => 'Code coupon invalide',
            ];
        }

        // Vérifier si le coupon est actif
        if (!$coupon->is_active) {
            return [
                'valid' => false,
                'message' => 'Ce coupon n\'est plus actif',
            ];
        }

        // Vérifier les dates
        $now = Carbon::now();
        if ($coupon->valid_from && $now->lt($coupon->valid_from)) {
            return [
                'valid' => false,
                'message' => 'Ce coupon n\'est pas encore valide',
                'start_date' => $coupon->valid_from->format('d/m/Y H:i'),
            ];
        }

        if ($coupon->valid_until && $now->gt($coupon->valid_until)) {
            return [
                'valid' => false,
                'message' => 'Ce coupon a expiré',
            ];
        }

        // Vérifier le nombre d'utilisations maximum
        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return [
                'valid' => false,
                'message' => 'Ce coupon a atteint sa limite d\'utilisation',
            ];
        }

        // Vérifier l'utilisation par utilisateur
        if ($userId && $coupon->usage_per_customer) {
            $userUsageCount = $coupon->usages()->where('user_id', $userId)->count();
            if ($userUsageCount >= $coupon->usage_per_customer) {
                return [
                    'valid' => false,
                    'message' => 'Vous avez déjà utilisé ce coupon le nombre maximum de fois',
                ];
            }
        }

        // Vérifier le montant minimum d'achat
        if ($cartTotal !== null && $coupon->min_cart_value) {
            if ($cartTotal < $coupon->min_cart_value) {
                $amountNeeded = $this->discountCalculator->calculateAmountToMinimum(
                    $cartTotal,
                    $coupon->min_cart_value
                );
                
                return [
                    'valid' => false,
                    'message' => "Montant minimum requis: {$coupon->min_cart_value} MAD",
                    'amount_needed' => $amountNeeded,
                ];
            }
        }

        // Note: user_specific field doesn't exist in coupons table
        // Users relationship is through coupon_usage for tracking who used the coupon

        return [
            'valid' => true,
            'coupon' => $coupon,
            'message' => 'Coupon valide',
        ];
    }

    /**
     * Appliquer un coupon au panier
     */
    public function applyCouponToCart(Coupon $coupon, array $cartItems, ?int $userId = null): array
    {
        // Calculer le total du panier
        $cartTotal = array_reduce($cartItems, function($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        // Valider le coupon
        $validation = $this->validateCoupon($coupon->code, $userId, $cartTotal);
        
        if (!$validation['valid']) {
            return [
                'applied' => false,
                'message' => $validation['message'],
            ];
        }

        // Calculer la réduction
        if ($coupon->type === 'free_shipping') {
            // Free shipping coupon - no cart discount calculation needed
            $discount = [
                'subtotal' => $cartTotal,
                'discount_amount' => 0,
                'final_total' => $cartTotal,
                'discount_percentage' => 0,
                'free_shipping' => true,
            ];
        } else {
            $discount = $this->discountCalculator->calculateCartDiscount(
                $cartItems,
                $coupon->type,
                $coupon->discount_value ?? 0
            );
        }

        return array_merge($discount, [
            'applied' => true,
            'coupon_id' => $coupon->id,
            'coupon_code' => $coupon->code,
            'coupon_description' => $coupon->description,
            'message' => 'Coupon appliqué avec succès',
        ]);
    }

    /**
     * Enregistrer l'utilisation d'un coupon
     */
    public function recordCouponUsage(Coupon $coupon, int $userId, int $orderId, float $discountAmount): void
    {
        $coupon->usages()->create([
            'user_id' => $userId,
            'order_id' => $orderId,
            'discount_amount' => $discountAmount,
            'used_at' => Carbon::now(),
        ]);

        // Incrémenter le compteur d'utilisation
        $coupon->increment('used_count');

        // Désactiver le coupon si la limite est atteinte
        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            $coupon->update(['is_active' => false]);
        }
    }

    /**
     * Générer un code coupon unique
     */
    public function generateUniqueCouponCode(string $prefix = '', int $length = 8): string
    {
        do {
            $code = $prefix . strtoupper(Str::random($length));
        } while (Coupon::where('code', $code)->exists());

        return $code;
    }

    /**
     * Créer des coupons en masse
     */
    public function createBulkCoupons(array $data, int $quantity): array
    {
        $coupons = [];
        
        for ($i = 0; $i < $quantity; $i++) {
            $code = $this->generateUniqueCouponCode($data['prefix'] ?? '', $data['code_length'] ?? 8);
            
            $coupon = Coupon::create(array_merge($data, [
                'code' => $code,
            ]));
            
            $coupons[] = $coupon;
        }

        return $coupons;
    }

    /**
     * Assigner un coupon à des utilisateurs spécifiques
     */
    public function assignCouponToUsers(Coupon $coupon, array $userIds): void
    {
        $coupon->users()->sync($userIds);
        $coupon->update(['user_specific' => true]);
    }

    /**
     * Envoyer un coupon par email à un utilisateur
     */
    public function sendCouponToUser(Coupon $coupon, User $user): void
    {
        // TODO: Implémenter l'envoi d'email
        // Mail::to($user->email)->send(new CouponMail($coupon));
    }

    /**
     * Obtenir les statistiques d'un coupon
     */
    public function getCouponStats(Coupon $coupon): array
    {
        $usages = $coupon->usages;
        
        return [
            'total_uses' => $usages->count(),
            'total_discount_given' => round($usages->sum('discount_amount'), 2),
            'unique_users' => $usages->pluck('user_id')->unique()->count(),
            'average_discount' => $usages->count() > 0 ? round($usages->avg('discount_amount'), 2) : 0,
            'remaining_uses' => $coupon->usage_limit ? max(0, $coupon->usage_limit - $usages->count()) : null,
            'conversion_rate' => $this->calculateConversionRate($coupon),
        ];
    }

    /**
     * Calculer le taux de conversion d'un coupon
     */
    protected function calculateConversionRate(Coupon $coupon): float
    {
        // TODO: Implémenter le calcul du taux de conversion
        // Nécessite de tracker les vues/tentatives d'utilisation
        return 0;
    }

    /**
     * Désactiver automatiquement les coupons expirés
     */
    public function deactivateExpiredCoupons(): int
    {
        return Coupon::where('is_active', true)
            ->whereNotNull('valid_until')
            ->where('valid_until', '<', Carbon::now())
            ->update(['is_active' => false]);
    }

    /**
     * Obtenir les coupons disponibles pour un utilisateur
     */
    public function getAvailableCouponsForUser(?int $userId = null): array
    {
        $query = Coupon::where('is_active', true)
            ->where(function($q) {
                $q->whereNull('valid_from')
                  ->orWhere('valid_from', '<=', Carbon::now());
            })
            ->where(function($q) {
                $q->whereNull('valid_until')
                  ->orWhere('valid_until', '>=', Carbon::now());
            })
            ->where(function($q) {
                $q->whereNull('usage_limit')
                  ->orWhereRaw('used_count < usage_limit');
            });

        // Note: No user_specific field in coupons table
        // All active coupons are available to all users

        return $query->get()->toArray();
    }

    /**
     * Vérifier si un utilisateur peut combiner plusieurs coupons
     */
    public function canCombineCoupons(array $coupons): bool
    {
        // Par défaut, on ne permet pas la combinaison de coupons
        // Cette logique peut être personnalisée selon les besoins
        return count($coupons) <= 1;
    }

    /**
     * Dupliquer un coupon
     */
    public function duplicateCoupon(Coupon $coupon): Coupon
    {
        $newCoupon = $coupon->replicate();
        $newCoupon->code = $this->generateUniqueCouponCode();
        $newCoupon->used_count = 0;
        $newCoupon->save();

        return $newCoupon;
    }
}
