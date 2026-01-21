<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Exception;

class CartService
{
    protected $discountCalculator;
    protected $couponService;

    public function __construct(DiscountCalculator $discountCalculator, CouponService $couponService)
    {
        $this->discountCalculator = $discountCalculator;
        $this->couponService = $couponService;
    }

    /**
     * Get or create cart for current user/session
     */
    public function getCart()
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(
                ['user_id' => Auth::id()],
                ['session_id' => null]
            );
        } else {
            $sessionId = Session::getId();
            $cart = Cart::firstOrCreate(
                ['session_id' => $sessionId],
                ['user_id' => null]
            );
        }

        $cart->load(['items.product.images', 'items.variant']);
        return $cart;
    }

    /**
     * Add item to cart
     */
    public function addItem($productId, $quantity = 1, $variantId = null)
    {
        $cart = $this->getCart();
        
        // Validate product
        $product = Product::where('is_active', true)->findOrFail($productId);
        
        // Validate variant if provided
        if ($variantId) {
            $variant = ProductVariant::where('product_id', $productId)
                ->findOrFail($variantId);
            
            // Check stock
            if ($variant->quantity < $quantity) {
                throw new Exception("Stock insuffisant. Disponible: {$variant->quantity}");
            }
        } else {
            // Check if product requires variant
            if ($product->variants()->exists()) {
                throw new Exception("Ce produit nécessite la sélection d'une variante");
            }
        }

        // Check if item already exists in cart
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->first();

        if ($cartItem) {
            // Update quantity
            $newQuantity = $cartItem->quantity + $quantity;
            
            // Validate stock for new quantity
            if ($variantId) {
                $variant = ProductVariant::find($variantId);
                if ($variant && $variant->quantity < $newQuantity) {
                    throw new Exception("Stock insuffisant. Disponible: {$variant->quantity}");
                }
            }
            
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            // Create new cart item
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity,
            ]);
        }

        return $cartItem;
    }

    /**
     * Update item quantity
     */
    public function updateQuantity($cartItemId, $quantity)
    {
        $cart = $this->getCart();
        $cartItem = CartItem::where('cart_id', $cart->id)->findOrFail($cartItemId);

        if ($quantity <= 0) {
            return $this->removeItem($cartItemId);
        }

        // Validate stock
        if ($cartItem->variant_id) {
            $variant = ProductVariant::find($cartItem->variant_id);
            if ($variant && $variant->quantity < $quantity) {
                throw new Exception("Stock insuffisant. Disponible: {$variant->quantity}");
            }
        }

        $cartItem->update(['quantity' => $quantity]);
        return $cartItem;
    }

    /**
     * Remove item from cart
     */
    public function removeItem($cartItemId)
    {
        $cart = $this->getCart();
        $cartItem = CartItem::where('cart_id', $cart->id)->findOrFail($cartItemId);
        $cartItem->delete();
        
        return true;
    }

    /**
     * Clear entire cart
     */
    public function clearCart()
    {
        $cart = $this->getCart();
        $cart->items()->delete();
        
        return true;
    }

    /**
     * Get cart item count
     */
    public function getItemCount()
    {
        $cart = $this->getCart();
        return $cart->items()->sum('quantity');
    }

    /**
     * Calculate cart totals with promotions and coupons
     */
    public function calculateTotals($couponCode = null)
    {
        $cart = $this->getCart();
        $items = $cart->items()->with(['product.images', 'variant.color', 'variant.size'])->get();

        if ($items->isEmpty()) {
            return [
                'subtotal' => 0,
                'discount' => 0,
                'shipping' => 0,
                'total' => 0,
                'items' => [],
                'coupon' => null,
                'promotions' => [],
            ];
        }

        $subtotal = 0;
        $totalDiscount = 0;
        $itemsDetails = [];
        $appliedPromotions = [];

        // Calculate each item
        foreach ($items as $item) {
            $product = $item->product;
            $variant = $item->variant;
            
            // Get base price (variants don't have their own price, use product price)
            $basePrice = $product->price;
            
            // Check for product promotions
            $productPromotions = $product->promotions()
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->where('scope', 'product')
                ->get();

            $itemPrice = $basePrice;
            $itemDiscount = 0;
            $bestPromotion = null;

            foreach ($productPromotions as $promotion) {
                $discountResult = $this->discountCalculator->calculateDiscount(
                    $basePrice,
                    $promotion->discount_type,
                    $promotion->discount_value
                );

                if ($discountResult['discount_amount'] > $itemDiscount) {
                    $itemDiscount = $discountResult['discount_amount'];
                    $bestPromotion = $promotion;
                }
            }

            if ($bestPromotion) {
                $itemPrice = $basePrice - $itemDiscount;
                $appliedPromotions[] = [
                    'promotion' => $bestPromotion,
                    'product_id' => $product->id,
                    'discount' => $itemDiscount * $item->quantity,
                ];
            }

            $itemTotal = $itemPrice * $item->quantity;
            $subtotal += $basePrice * $item->quantity;
            $totalDiscount += $itemDiscount * $item->quantity;

            $itemsDetails[] = [
                'item' => $item,
                'base_price' => $basePrice,
                'price' => $itemPrice,
                'discount' => $itemDiscount,
                'total' => $itemTotal,
                'promotion' => $bestPromotion,
            ];
        }

        // Apply cart-wide promotions
        $cartPromotions = \App\Models\Promotion::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('scope', 'cart')
            ->get();

        $cartDiscount = 0;
        $bestCartPromotion = null;

        foreach ($cartPromotions as $promotion) {
            // Check minimum purchase amount
            if ($promotion->minimum_purchase_amount && $subtotal < $promotion->minimum_purchase_amount) {
                continue;
            }

            $discountResult = $this->discountCalculator->calculateDiscount(
                $subtotal - $totalDiscount,
                $promotion->discount_type,
                $promotion->discount_value
            );

            if ($discountResult['discount_amount'] > $cartDiscount) {
                $cartDiscount = $discountResult['discount_amount'];
                $bestCartPromotion = $promotion;
            }
        }

        if ($bestCartPromotion) {
            $totalDiscount += $cartDiscount;
            $appliedPromotions[] = [
                'promotion' => $bestCartPromotion,
                'product_id' => null,
                'discount' => $cartDiscount,
            ];
        }

        // Apply coupon if provided
        $coupon = null;
        $couponDiscount = 0;

        if ($couponCode) {
            try {
                $coupon = Coupon::where('code', strtoupper($couponCode))->first();
                
                if ($coupon) {
                    // Validate coupon
                    $validation = $this->couponService->validateCoupon($coupon->code, Auth::id(), $subtotal - $totalDiscount);
                    
                    if ($validation['valid']) {
                        if ($coupon->type === 'free_shipping') {
                            // Free shipping coupon - no discount on cart total
                            $couponDiscount = 0;
                        } else {
                            $couponDiscountResult = $this->discountCalculator->calculateDiscount(
                                $subtotal - $totalDiscount,
                                $coupon->type,
                                $coupon->discount_value
                            );

                            $couponDiscount = $couponDiscountResult['discount_amount'];
                        }

                        $totalDiscount += $couponDiscount;
                    }
                }
            } catch (Exception $e) {
                // Coupon validation failed, ignore
            }
        }

        $total = $subtotal - $totalDiscount;

        return [
            'subtotal' => round($subtotal, 2),
            'discount' => round($totalDiscount, 2),
            'promotion_discount' => round($totalDiscount - $couponDiscount, 2),
            'coupon_discount' => round($couponDiscount, 2),
            'shipping' => 0, // Will be calculated at checkout
            'total' => round(max(0, $total), 2),
            'items' => $itemsDetails,
            'coupon' => $coupon,
            'promotions' => $appliedPromotions,
        ];
    }

    /**
     * Validate cart stock availability
     */
    public function validateStock()
    {
        $cart = $this->getCart();
        $items = $cart->items()->with(['product', 'variant'])->get();
        $errors = [];

        foreach ($items as $item) {
            $product = $item->product;
            
            if (!$product->is_active) {
                $errors[] = "Le produit '{$product->name}' n'est plus disponible";
                continue;
            }

            if ($item->variant_id) {
                $variant = $item->variant;
                if (!$variant) {
                    $errors[] = "La variante sélectionnée pour '{$product->name}' n'est plus disponible";
                    continue;
                }

                if ($variant->quantity < $item->quantity) {
                    $errors[] = "Stock insuffisant pour '{$product->name}'. Disponible: {$variant->quantity}";
                }
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Merge guest cart with user cart after login
     */
    public function mergeGuestCart($sessionId, $userId)
    {
        $guestCart = Cart::where('session_id', $sessionId)->first();
        
        if (!$guestCart) {
            return;
        }

        $userCart = Cart::firstOrCreate(
            ['user_id' => $userId],
            ['session_id' => null]
        );

        // Merge items
        foreach ($guestCart->items as $guestItem) {
            $existingItem = CartItem::where('cart_id', $userCart->id)
                ->where('product_id', $guestItem->product_id)
                ->where('variant_id', $guestItem->variant_id)
                ->first();

            if ($existingItem) {
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $guestItem->quantity
                ]);
            } else {
                $guestItem->update(['cart_id' => $userCart->id]);
            }
        }

        // Delete guest cart
        $guestCart->delete();
    }

    /**
     * Apply coupon to cart
     */
    public function applyCoupon($couponCode)
    {
        $coupon = Coupon::where('code', strtoupper($couponCode))->first();
        
        if (!$coupon) {
            throw new Exception("Code promo invalide");
        }

        $totals = $this->calculateTotals();
        $validation = $this->couponService->validateCoupon($coupon->code, Auth::id(), $totals['subtotal']);

        if (!$validation['valid']) {
            throw new Exception($validation['message']);
        }

        // Store coupon in session
        Session::put('applied_coupon', $couponCode);

        return $coupon;
    }

    /**
     * Remove applied coupon
     */
    public function removeCoupon()
    {
        Session::forget('applied_coupon');
        return true;
    }

    /**
     * Get applied coupon from session
     */
    public function getAppliedCoupon()
    {
        $couponCode = Session::get('applied_coupon');
        
        if (!$couponCode) {
            return null;
        }

        return Coupon::where('code', strtoupper($couponCode))->first();
    }
}
