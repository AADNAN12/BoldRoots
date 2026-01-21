<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Promotion;
use App\Models\Coupon;
use App\Models\PromotionUsage;
use App\Models\CouponUsage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class OrderService
{
    protected $cartService;
    protected $couponService;

    public function __construct(CartService $cartService, CouponService $couponService)
    {
        $this->cartService = $cartService;
        $this->couponService = $couponService;
    }

    /**
     * Create order from cart
     */
    public function createOrderFromCart($userId, $paymentMethod, $shippingCost = 0, $notes = null, $guestInfo = null)
    {
        DB::beginTransaction();

        try {
            // Get cart - for authenticated users by user_id, for guests by session_id
            if ($userId) {
                $cart = Cart::where('user_id', $userId)->first();
            } else {
                $cart = Cart::where('session_id', session()->getId())
                    ->whereNull('user_id')
                    ->first();
            }
            
            if (!$cart) {
                throw new Exception("Le panier n'existe pas");
            }
            
            $cartItems = $cart->items()->with(['product', 'variant'])->get();

            if ($cartItems->isEmpty()) {
                throw new Exception("Le panier est vide");
            }

            // Validate stock manually
            foreach ($cartItems as $item) {
                if ($item->variant) {
                    if ($item->variant->quantity < $item->quantity) {
                        throw new Exception("Stock insuffisant pour {$item->product->name} - {$item->variant->name}");
                    }
                } else {
                    if ($item->product->stock_quantity < $item->quantity) {
                        throw new Exception("Stock insuffisant pour {$item->product->name}");
                    }
                }
            }

            // Get applied coupon from session via CartService
            $appliedCoupon = $this->cartService->getAppliedCoupon();

            // Use CartService to calculate totals with promotions and coupons applied
            $totals = $this->cartService->calculateTotals($appliedCoupon ? $appliedCoupon->code : null);
            
            $subtotal = $totals['subtotal'];
            $discount = $totals['promotion_discount'] + $totals['coupon_discount'];
            $total = $totals['total'];
            
            // Build itemsDetails from totals for order items creation
            $itemsDetails = [];
            foreach ($totals['items'] as $itemDetail) {
                $itemsDetails[] = [
                    'item' => $itemDetail['item'],
                    'price' => $itemDetail['price'],
                    'total' => $itemDetail['total']
                ];
            }

            // Create order with guest info if provided
            $orderData = [
                'user_id' => $userId,
                'order_number' => $this->generateOrderNumber(),
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $paymentMethod,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'discount' => $discount,
                'total' => $total + $shippingCost,
                'promotion_id' => null,
                'coupon_id' => $appliedCoupon ? $appliedCoupon->id : null,
                'notes' => $notes,
                'invoice_generated' => false,
                'delivery_note_generated' => false,
            ];
            
            // Add guest information if this is a guest order
            if ($guestInfo) {
                $orderData = array_merge($orderData, $guestInfo);
            }
            
            $order = Order::create($orderData);

            // Create order items
            foreach ($itemsDetails as $itemDetail) {
                $cartItem = $itemDetail['item'];
                $product = $cartItem->product;
                $variant = $cartItem->variant;

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'variant_id' => $variant ? $variant->id : null,
                    'product_name' => $product->name,
                    'product_sku' => $variant ? $variant->sku : $product->sku,
                    'quantity' => $cartItem->quantity,
                    'price' => $itemDetail['price'],
                    'total' => $itemDetail['total'],
                    'variant_details' => $variant ? [
                        'attributes' => $variant->attribute_values,
                        'sku' => $variant->sku,
                    ] : null,
                ]);

                // Reduce stock
                if ($variant) {
                    $variant->decrement('quantity', $cartItem->quantity);
                } else {
                    // If product doesn't have variants, you might have a stock field on products table
                    // $product->decrement('quantity', $cartItem->quantity);
                }
            }

            // Record coupon usage
            if ($appliedCoupon) {
                CouponUsage::create([
                    'coupon_id' => $appliedCoupon->id,
                    'user_id' => $userId,
                    'order_id' => $order->id,
                    'discount_amount' => $discount,
                ]);

                // Update coupon usage count
                $appliedCoupon->increment('used_count');
            }

            // Delete cart completely
            $cart->items()->delete();
            $cart->delete();

            DB::commit();

            return $order;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur dans OrderService::createOrderFromCart', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Generate unique order number
     */
    public function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        
        do {
            $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $orderNumber = "{$prefix}-{$date}-{$random}";
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * Update order status
     */
    public function updateOrderStatus($orderId, $status)
    {
        $order = Order::findOrFail($orderId);

        $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        
        if (!in_array($status, $validStatuses)) {
            throw new Exception("Statut invalide");
        }

        $order->update(['status' => $status]);

        // Update timestamps based on status
        if ($status === 'shipped' && !$order->shipped_at) {
            $order->update(['shipped_at' => now()]);
        }

        if ($status === 'delivered' && !$order->delivered_at) {
            $order->update(['delivered_at' => now()]);
        }

        return $order;
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus($orderId, $paymentStatus)
    {
        $order = Order::findOrFail($orderId);

        $validStatuses = ['pending', 'paid', 'failed'];
        
        if (!in_array($paymentStatus, $validStatuses)) {
            throw new Exception("Statut de paiement invalide");
        }

        $order->update(['payment_status' => $paymentStatus]);

        // If payment is successful, update order status to processing
        if ($paymentStatus === 'paid' && $order->status === 'pending') {
            $order->update(['status' => 'processing']);
        }

        return $order;
    }

    /**
     * Cancel order
     */
    public function cancelOrder($orderId, $restoreStock = true)
    {
        DB::beginTransaction();

        try {
            $order = Order::findOrFail($orderId);

            // Check if order can be cancelled
            if (in_array($order->status, ['delivered', 'cancelled'])) {
                throw new Exception("Cette commande ne peut pas être annulée");
            }

            $order->update(['status' => 'cancelled']);

            // Restore stock if requested
            if ($restoreStock) {
                foreach ($order->items as $item) {
                    if ($item->variant_id) {
                        $variant = ProductVariant::find($item->variant_id);
                        if ($variant) {
                            $variant->increment('quantity', $item->quantity);
                        }
                    }
                }
            }

            // Restore promotion usage count
            if ($order->promotion_id) {
                $promotion = Promotion::find($order->promotion_id);
                if ($promotion) {
                    $promotion->decrement('usage_count');
                }
            }

            // Restore coupon usage count
            if ($order->coupon_id) {
                $coupon = Coupon::find($order->coupon_id);
                if ($coupon) {
                    $coupon->decrement('used_count');
                }
            }

            DB::commit();

            return $order;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Calculate order totals
     */
    public function calculateOrderTotals($subtotal, $shippingCost, $discountAmount)
    {
        $total = $subtotal + $shippingCost - $discountAmount;

        return [
            'subtotal' => round($subtotal, 2),
            'shipping_cost' => round($shippingCost, 2),
            'discount' => round($discountAmount, 2),
            'total' => round(max(0, $total), 2),
        ];
    }

    /**
     * Get order statistics
     */
    public function getOrderStats($orderId)
    {
        $order = Order::with(['items', 'user', 'promotion', 'coupon'])->findOrFail($orderId);

        return [
            'order' => $order,
            'items_count' => $order->items->count(),
            'total_quantity' => $order->items->sum('quantity'),
            'has_promotion' => $order->promotion_id !== null,
            'has_coupon' => $order->coupon_id !== null,
            'is_paid' => $order->payment_status === 'paid',
            'can_cancel' => !in_array($order->status, ['delivered', 'cancelled']),
            'can_generate_invoice' => $order->payment_status === 'paid' && !$order->invoice_generated,
            'can_generate_delivery_note' => in_array($order->status, ['processing', 'shipped']) && !$order->delivery_note_generated,
        ];
    }

    /**
     * Get global sales statistics
     */
    public function getSalesStats($startDate = null, $endDate = null)
    {
        $query = Order::query();

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        $totalOrders = $query->count();
        $totalRevenue = $query->where('payment_status', 'paid')->sum('total');
        $pendingOrders = (clone $query)->where('status', 'pending')->count();
        $processingOrders = (clone $query)->where('status', 'processing')->count();
        $shippedOrders = (clone $query)->where('status', 'shipped')->count();
        $deliveredOrders = (clone $query)->where('status', 'delivered')->count();
        $cancelledOrders = (clone $query)->where('status', 'cancelled')->count();

        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return [
            'total_orders' => $totalOrders,
            'total_revenue' => round($totalRevenue, 2),
            'average_order_value' => round($averageOrderValue, 2),
            'pending_orders' => $pendingOrders,
            'processing_orders' => $processingOrders,
            'shipped_orders' => $shippedOrders,
            'delivered_orders' => $deliveredOrders,
            'cancelled_orders' => $cancelledOrders,
        ];
    }

    /**
     * Get best selling products
     */
    public function getBestSellingProducts($limit = 10, $startDate = null, $endDate = null)
    {
        $query = OrderItem::query()
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(total) as total_revenue'))
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->limit($limit);

        if ($startDate || $endDate) {
            $query->whereHas('order', function ($q) use ($startDate, $endDate) {
                if ($startDate) {
                    $q->where('created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $q->where('created_at', '<=', $endDate);
                }
            });
        }

        return $query->get();
    }

    /**
     * Get recent orders
     */
    public function getRecentOrders($limit = 10)
    {
        return Order::with(['user', 'items'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Search orders
     */
    public function searchOrders($filters = [])
    {
        $query = Order::with(['user', 'items']);

        if (!empty($filters['order_number'])) {
            $query->where('order_number', 'like', '%' . $filters['order_number'] . '%');
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['start_date'])) {
            $query->where('created_at', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->where('created_at', '<=', $filters['end_date']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($filters['per_page'] ?? 20);
    }
}
