<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart();
        $totals = $this->cartService->calculateTotals();
        $appliedCoupon = $this->cartService->getAppliedCoupon();

        return view('front-office.cart.index', compact('cart', 'totals', 'appliedCoupon'));
    }

    public function add(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'variant_id' => 'nullable|exists:product_variants,id',
                'size' => 'nullable|exists:attribute_values,id',
                'color' => 'nullable|exists:attribute_values,id',
            ]);

            $variantId = $validated['variant_id'] ?? null;
            
            // Si size ou color sont fournis, trouver la variante correspondante
            if (!$variantId && ($validated['size'] ?? null || $validated['color'] ?? null)) {
                $query = \App\Models\ProductVariant::where('product_id', $validated['product_id']);
                
                if ($validated['size'] ?? null) {
                    $query->where('size_id', $validated['size']);
                }
                if ($validated['color'] ?? null) {
                    $query->where('color_id', $validated['color']);
                }
                
                $variant = $query->first();
                if ($variant) {
                    $variantId = $variant->id;
                }
            }

            $this->cartService->addItem(
                $validated['product_id'],
                $validated['quantity'],
                $variantId
            );

            $itemCount = $this->cartService->getItemCount();

            return response()->json([
                'success' => true,
                'message' => 'Produit ajouté au panier',
                'cart_count' => $itemCount,
            ]);

        } catch (Exception $e) {
            Log::error('Erreur lors de l\'ajout au panier', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function update(Request $request, $cartItemId)
    {
        try {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:0',
            ]);

            $this->cartService->updateQuantity($cartItemId, $validated['quantity']);

            $totals = $this->cartService->calculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Quantité mise à jour',
                'totals' => $totals,
            ]);

        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour du panier', [
                'error' => $e->getMessage(),
                'cart_item_id' => $cartItemId,
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function remove($cartItemId)
    {
        try {
            $this->cartService->removeItem($cartItemId);

            $totals = $this->cartService->calculateTotals();
            $itemCount = $this->cartService->getItemCount();

            return response()->json([
                'success' => true,
                'message' => 'Produit retiré du panier',
                'totals' => $totals,
                'cart_count' => $itemCount,
            ]);

        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression du panier', [
                'error' => $e->getMessage(),
                'cart_item_id' => $cartItemId,
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function clear()
    {
        try {
            $this->cartService->clearCart();

            return response()->json([
                'success' => true,
                'message' => 'Panier vidé',
            ]);

        } catch (Exception $e) {
            Log::error('Erreur lors du vidage du panier', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function applyCoupon(Request $request)
    {
        try {
            $validated = $request->validate([
                'coupon_code' => 'required|string',
            ]);

            $coupon = $this->cartService->applyCoupon($validated['coupon_code']);
            $totals = $this->cartService->calculateTotals($coupon->code);

            return response()->json([
                'success' => true,
                'message' => 'Code promo appliqué avec succès',
                'coupon' => $coupon,
                'totals' => $totals,
            ]);

        } catch (Exception $e) {
            Log::error('Erreur lors de l\'application du coupon', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function removeCoupon()
    {
        try {
            $this->cartService->removeCoupon();
            $totals = $this->cartService->calculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Code promo retiré',
                'totals' => $totals,
            ]);

        } catch (Exception $e) {
            Log::error('Erreur lors du retrait du coupon', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function getCount()
    {
        try {
            $count = $this->cartService->getItemCount();

            return response()->json([
                'success' => true,
                'count' => $count,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'count' => 0,
            ]);
        }
    }

    public function getData()
    {
        try {
            $cart = $this->cartService->getCart();
            $totals = $this->cartService->calculateTotals();

            Log::info('Cart data retrieved', [
                'cart_id' => $cart->id,
                'items_count' => count($totals['items']),
                'total' => $totals['total'],
            ]);

            return response()->json([
                'success' => true,
                'count' => $this->cartService->getItemCount(),
                'items' => $totals['items'],
                'subtotal' => $totals['subtotal'],
                'discount' => $totals['discount'],
                'total' => $totals['total'],
            ]);

        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération des données du panier', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'count' => 0,
                'items' => [],
                'subtotal' => 0,
                'discount' => 0,
                'total' => 0,
            ]);
        }
    }
}
