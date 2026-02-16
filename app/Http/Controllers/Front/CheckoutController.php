<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\ShippingMethod;
use App\Models\User;
use App\Services\CartService;
use App\Services\OrderService;
use App\Notifications\NewOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Exception;

class CheckoutController extends Controller
{
    protected $cartService;
    protected $orderService;

    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart();
        $appliedCoupon = $this->cartService->getAppliedCoupon();
        $totals = $this->cartService->calculateTotals($appliedCoupon ? $appliedCoupon->code : null);

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Votre panier est vide');
        }

        $addresses = Auth::check() ? Auth::user()->addresses : collect();
        $shippingMethods = ShippingMethod::where('is_active', true)->get();

        return view('front-office.checkout.index', compact('cart', 'totals', 'addresses', 'shippingMethods', 'appliedCoupon'));
    }

    public function validateCart()
    {
        try {
            $validation = $this->cartService->validateStock();

            return response()->json([
                'success' => $validation['valid'],
                'errors' => $validation['errors'],
            ]);

        } catch (Exception $e) {
            Log::error('Erreur lors de la validation du panier', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'errors' => [$e->getMessage()],
            ], 400);
        }
    }

    public function calculateShipping(Request $request)
    {
        try {
            $validated = $request->validate([
                'shipping_method_id' => 'required|exists:shipping_methods,id',
            ]);

            $shippingMethod = ShippingMethod::findOrFail($validated['shipping_method_id']);
            $totals = $this->cartService->calculateTotals();

            $finalTotal = $totals['total'] + $shippingMethod->cost;

            return response()->json([
                'success' => true,
                'shipping_cost' => $shippingMethod->cost,
                'final_total' => round($finalTotal, 2),
            ]);

        } catch (Exception $e) {
            Log::error('Erreur lors du calcul des frais de livraison', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function processPayment(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'address' => 'required|string|max:500',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'shipping_method_id' => 'nullable|exists:shipping_methods,id',
                'payment_method' => 'required|string|in:cash_on_delivery,stripe',
                'notes' => 'nullable|string|max:1000',
                'create_account' => 'nullable|boolean',
                'password' => 'required_if:create_account,1|nullable|string|min:8',
            ]);

            // Validate cart
            $stockValidation = $this->cartService->validateStock();
            if (!$stockValidation['valid']) {
                return redirect()->back()
                    ->with('error', implode(', ', $stockValidation['errors']));
            }

            // Handle user creation if guest wants to create account
            $userId = Auth::id();
            $user = Auth::user();
            $guestInfo = null; // Will hold guest information if not authenticated
            
            if (!$userId && $request->create_account && $request->password) {
                // Check if email already exists
                $existingUser = User::where('email', $validated['email'])->first();
                if ($existingUser) {
                    return redirect()->back()
                        ->with('error', 'Un compte avec cet email existe déjà. Veuillez vous connecter.');
                }

                // Create new user with password in separate transaction
                try {
                    DB::beginTransaction();
                    
                    $user = User::create([
                        'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                        'email' => $validated['email'],
                        'password' => Hash::make($validated['password']),
                        'is_active' => '1',
                        'phone' => $validated['phone'],
                        'address_line1' => $validated['address'],
                    ]);
                    
                    if (!$user || !$user->id) {
                        throw new Exception("Échec de la création de l'utilisateur - objet null");
                    }
                    
                    $user->assignRole('Client');
                    
                    // Transfer guest cart BEFORE login to avoid session regeneration issues
                    $guestCart = Cart::where('user_id', null)
                        ->where('session_id', session()->getId())
                        ->first();
                    
                    Log::info('Recherche panier invité', [
                        'session_id' => session()->getId(),
                        'guest_cart_found' => $guestCart ? 'oui' : 'non',
                        'guest_cart_id' => $guestCart ? $guestCart->id : null,
                        'items_count' => $guestCart ? $guestCart->items()->count() : 0
                    ]);
                    
                    if ($guestCart) {
                        $guestCart->update(['user_id' => $user->id, 'session_id' => null]);
                        Log::info('Panier transféré', ['cart_id' => $guestCart->id, 'user_id' => $user->id]);
                    }
                    
                    DB::commit(); // Commit user creation before OrderService
                    
                    Auth::login($user);
                    $userId = $user->id;
                } catch (Exception $e) {
                    DB::rollBack();
                    Log::error('Erreur création utilisateur avec compte', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    return redirect()->back()
                        ->with('error', 'Erreur lors de la création du compte: ' . $e->getMessage());
                }
            }

            // If still no user (guest checkout), prepare guest information
            if (!$userId) {
                $guestInfo = [
                    'guest_name' => $validated['first_name'] . ' ' . $validated['last_name'],
                    'guest_email' => $validated['email'],
                    'guest_phone' => $validated['phone'],
                    'guest_address_line1' => $validated['address'],
                    'guest_address_line2' => null,
                    'guest_city' => null,
                    'guest_postal_code' => null,
                ];
                
                Log::info('Commande invité préparée', $guestInfo);
            }

            // Update user address information if user is authenticated
            if ($userId && $user) {
                $user->update([
                    'address_line1' => $validated['address'],
                    'phone' => $validated['phone'],
                ]);
            }
            // dd($userId);
            // Get shipping cost
            $shippingCost = 0;
            if (!empty($validated['shipping_method_id'])) {
                $shippingMethod = ShippingMethod::findOrFail($validated['shipping_method_id']);
                $shippingCost = $shippingMethod->cost;
            }

            // Create order
            Log::info('Tentative de création de commande', [
                'user_id' => $userId,
                'payment_method' => $validated['payment_method'],
                'shipping_cost' => $shippingCost
            ]);
            
            $order = $this->orderService->createOrderFromCart(
                $userId,
                $validated['payment_method'],
                $shippingCost,
                $validated['notes'] ?? null,
                $guestInfo
            );
            
            Log::info('Commande créée avec succès', ['order_id' => $order->id]);

            // Envoyer notification à TOUS les Super Admins
            $order->load(['items.product', 'user']);
            $superAdmins = User::role('Super Admin')->get();
            if ($superAdmins->isEmpty()) {
                Notification::route('mail', config('mail.from.address'))
                    ->notify(new NewOrderNotification($order));
            } else {
                foreach ($superAdmins as $admin) {
                    $admin->notify(new NewOrderNotification($order));
                }
            }

            // Process payment based on method
            if ($validated['payment_method'] === 'cash_on_delivery') {
                return redirect()->route('checkout.success', ['order' => $order->id])
                    ->with('success', 'Commande passée avec succès');
            } elseif ($validated['payment_method'] === 'stripe') {
                // Stripe integration coming soon
                return redirect()->back()
                    ->with('error', 'Le paiement par Stripe sera bientôt disponible.');
            }

        } catch (Exception $e) {
            Log::error('Erreur lors du traitement du paiement', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors du traitement de la commande: ' . $e->getMessage());
        }
    }

    public function success($orderId)
    {
        // Allow both authenticated and guest users to view their order confirmation
        if (Auth::check()) {
            $order = Auth::user()->orders()->with(['items.product', 'user'])->findOrFail($orderId);
        } else {
            // For guest users, find order by ID (they just created it)
            $order = \App\Models\Order::with(['items.product', 'user'])->findOrFail($orderId);
        }

        return view('front-office.checkout.success', compact('order'));
    }
}
