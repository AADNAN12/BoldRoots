<?php

namespace App\Services;

class DiscountCalculator
{
    /**
     * Calculer le montant de la réduction en pourcentage
     */
    public function calculatePercentageDiscount(float $price, float $percentage): float
    {
        if ($percentage < 0 || $percentage > 100) {
            throw new \InvalidArgumentException('Le pourcentage doit être entre 0 et 100');
        }
        
        return round($price * ($percentage / 100), 2);
    }

    /**
     * Calculer le montant de la réduction fixe
     */
    public function calculateFixedDiscount(float $price, float $fixedAmount): float
    {
        if ($fixedAmount < 0) {
            throw new \InvalidArgumentException('Le montant fixe doit être positif');
        }
        
        // La réduction ne peut pas dépasser le prix
        return min($fixedAmount, $price);
    }

    /**
     * Calculer le prix final après réduction
     */
    public function calculateFinalPrice(float $originalPrice, float $discountAmount): float
    {
        $finalPrice = $originalPrice - $discountAmount;
        
        // Le prix final ne peut pas être négatif
        return max(0, round($finalPrice, 2));
    }

    /**
     * Calculer la réduction selon le type
     */
    public function calculateDiscount(float $price, string $discountType, float $discountValue): array
    {
        $discountAmount = 0;
        
        switch ($discountType) {
            case 'percentage':
                $discountAmount = $this->calculatePercentageDiscount($price, $discountValue);
                break;
                
            case 'fixed_amount':
                $discountAmount = $this->calculateFixedDiscount($price, $discountValue);
                break;
                
            default:
                throw new \InvalidArgumentException("Type de réduction invalide: {$discountType}");
        }
        
        $finalPrice = $this->calculateFinalPrice($price, $discountAmount);
        $discountPercentage = $price > 0 ? round(($discountAmount / $price) * 100, 2) : 0;
        
        return [
            'original_price' => round($price, 2),
            'discount_amount' => round($discountAmount, 2),
            'final_price' => $finalPrice,
            'discount_percentage' => $discountPercentage,
            'savings' => round($discountAmount, 2),
        ];
    }

    /**
     * Calculer la réduction pour plusieurs articles (panier)
     */
    public function calculateCartDiscount(array $items, string $discountType, float $discountValue): array
    {
        $totalOriginalPrice = 0;
        $itemsWithDiscount = [];
        
        foreach ($items as $item) {
            $price = $item['price'] ?? 0;
            $quantity = $item['quantity'] ?? 1;
            $itemTotal = $price * $quantity;
            
            $totalOriginalPrice += $itemTotal;
            
            $itemsWithDiscount[] = array_merge($item, [
                'subtotal' => round($itemTotal, 2),
            ]);
        }
        
        // Appliquer la réduction sur le total
        $discountResult = $this->calculateDiscount($totalOriginalPrice, $discountType, $discountValue);
        
        return [
            'items' => $itemsWithDiscount,
            'subtotal' => round($totalOriginalPrice, 2),
            'discount_amount' => $discountResult['discount_amount'],
            'final_total' => $discountResult['final_price'],
            'discount_percentage' => $discountResult['discount_percentage'],
        ];
    }

    /**
     * Calculer la promotion "Achetez X, obtenez Y"
     */
    public function calculateBuyXGetY(int $quantity, int $buyQuantity, int $getQuantity, float $unitPrice): array
    {
        if ($quantity < $buyQuantity) {
            return [
                'eligible' => false,
                'free_items' => 0,
                'discount_amount' => 0,
                'final_price' => round($quantity * $unitPrice, 2),
            ];
        }
        
        // Calculer combien de fois la promotion s'applique
        $promoSets = floor($quantity / ($buyQuantity + $getQuantity));
        $remainingItems = $quantity % ($buyQuantity + $getQuantity);
        
        // Si les articles restants sont suffisants pour un achat sans bonus
        if ($remainingItems >= $buyQuantity) {
            $promoSets++;
            $remainingItems -= $buyQuantity;
        }
        
        $freeItems = $promoSets * $getQuantity;
        $paidItems = $quantity - $freeItems;
        $discountAmount = $freeItems * $unitPrice;
        $finalPrice = $paidItems * $unitPrice;
        
        return [
            'eligible' => true,
            'free_items' => $freeItems,
            'paid_items' => $paidItems,
            'discount_amount' => round($discountAmount, 2),
            'final_price' => round($finalPrice, 2),
            'original_price' => round($quantity * $unitPrice, 2),
        ];
    }

    /**
     * Comparer plusieurs promotions et retourner la meilleure
     */
    public function getBestDiscount(array $discounts): ?array
    {
        if (empty($discounts)) {
            return null;
        }
        
        // Trier par montant de réduction décroissant
        usort($discounts, function($a, $b) {
            return $b['discount_amount'] <=> $a['discount_amount'];
        });
        
        return $discounts[0];
    }

    /**
     * Vérifier si le prix minimum est atteint pour appliquer une promotion
     */
    public function meetsMinimumPrice(float $currentPrice, ?float $minimumPrice): bool
    {
        if ($minimumPrice === null) {
            return true;
        }
        
        return $currentPrice >= $minimumPrice;
    }

    /**
     * Calculer le montant manquant pour atteindre le minimum
     */
    public function calculateAmountToMinimum(float $currentPrice, float $minimumPrice): float
    {
        $difference = $minimumPrice - $currentPrice;
        return max(0, round($difference, 2));
    }
}
