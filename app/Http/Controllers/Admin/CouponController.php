<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class CouponController extends Controller
{
    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public function index()
    {
        $coupons = Coupon::with(['usages', 'users'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $users = User::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();
        $categories = Category::where('is_active', true)->get();
        return view('admin.coupons.create', compact('users', 'products', 'categories'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'code' => 'nullable|string|max:50|unique:coupons,code',
                'description' => 'nullable|string',
                'type' => 'required|in:percentage,fixed_amount,free_shipping',
                'discount_value' => 'nullable|numeric|min:0',
                'valid_from' => 'required|date',
                'valid_until' => 'nullable|date|after:valid_from',
                'is_active' => 'boolean',
                'usage_limit' => 'nullable|integer|min:1',
                'usage_per_customer' => 'nullable|integer|min:1',
                'min_cart_value' => 'nullable|numeric|min:0',
                'exclude_new_products' => 'boolean',
                'user_ids' => 'nullable|array',
                'user_ids.*' => 'exists:users,id',
                'product_ids' => 'nullable|array',
                'product_ids.*' => 'exists:products,id',
                'category_ids' => 'nullable|array',
                'category_ids.*' => 'exists:categories,id',
                'auto_generate' => 'boolean',
                'code_prefix' => 'nullable|string|max:10',
                'code_length' => 'nullable|integer|min:4|max:20',
            ]);

            // Générer le code si nécessaire
            if ($request->boolean('auto_generate') || empty($validated['code'])) {
                $validated['code'] = $this->couponService->generateUniqueCouponCode(
                    $validated['code_prefix'] ?? '',
                    $validated['code_length'] ?? 8
                );
            } else {
                $validated['code'] = strtoupper($validated['code']);
            }

            // Validation du pourcentage
            if ($validated['type'] === 'percentage' && $validated['discount_value'] > 100) {
                return back()->withInput()
                    ->with('error', 'Le pourcentage ne peut pas dépasser 100%');
            }

            $coupon = Coupon::create([
                'code' => $validated['code'],
                'description' => $validated['description'] ?? null,
                'type' => $validated['type'],
                'discount_value' => $validated['discount_value'] ?? null,
                'valid_from' => $validated['valid_from'],
                'valid_until' => $validated['valid_until'] ?? null,
                'is_active' => $request->boolean('is_active'),
                'usage_limit' => $validated['usage_limit'] ?? null,
                'usage_per_customer' => $validated['usage_per_customer'] ?? 1,
                'used_count' => 0,
                'min_cart_value' => $validated['min_cart_value'] ?? null,
                'exclude_new_products' => $request->boolean('exclude_new_products', false),
            ]);

            // Associer les utilisateurs si spécifique
            if ($request->boolean('user_specific') && !empty($validated['user_ids'])) {
                $coupon->users()->attach($validated['user_ids']);
            }

            // Associer les produits
            if (!empty($validated['product_ids'])) {
                $coupon->products()->sync($validated['product_ids']);
            }

            // Associer les catégories
            if (!empty($validated['category_ids'])) {
                $coupon->categories()->sync($validated['category_ids']);
            }

            DB::commit();

            return redirect()->route('admin.coupons.index')
                ->with('success', "Coupon créé avec succès. Code: {$coupon->code}");

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création du coupon', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return back()->withInput()
                ->with('error', 'Erreur lors de la création du coupon: ' . $e->getMessage());
        }
    }

    public function show(Coupon $coupon)
    {
        $coupon->load(['usages.user', 'users']);
        $stats = $this->couponService->getCouponStats($coupon);

        return view('admin.coupons.show', compact('coupon', 'stats'));
    }

    public function edit(Coupon $coupon)
    {
        $coupon->load(['users', 'products', 'categories']);
        $users = User::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();
        $categories = Category::where('is_active', true)->get();

        return view('admin.coupons.edit', compact('coupon', 'users', 'products', 'categories'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
                'description' => 'nullable|string',
                'type' => 'required|in:percentage,fixed_amount,free_shipping',
                'discount_value' => 'nullable|numeric|min:0',
                'valid_from' => 'required|date',
                'valid_until' => 'nullable|date|after:valid_from',
                'is_active' => 'boolean',
                'usage_limit' => 'nullable|integer|min:1',
                'usage_per_customer' => 'nullable|integer|min:1',
                'min_cart_value' => 'nullable|numeric|min:0',
                'exclude_new_products' => 'boolean',
                'user_ids' => 'nullable|array',
                'user_ids.*' => 'exists:users,id',
                'product_ids' => 'nullable|array',
                'product_ids.*' => 'exists:products,id',
                'category_ids' => 'nullable|array',
                'category_ids.*' => 'exists:categories,id',
            ]);

            // Validation du pourcentage
            if ($validated['type'] === 'percentage' && $validated['discount_value'] > 100) {
                return back()->withInput()
                    ->with('error', 'Le pourcentage ne peut pas dépasser 100%');
            }

            $coupon->update([
                'code' => strtoupper($validated['code']),
                'description' => $validated['description'] ?? null,
                'type' => $validated['type'],
                'discount_value' => $validated['discount_value'] ?? null,
                'valid_from' => $validated['valid_from'],
                'valid_until' => $validated['valid_until'] ?? null,
                'is_active' => $request->boolean('is_active'),
                'usage_limit' => $validated['usage_limit'] ?? null,
                'usage_per_customer' => $validated['usage_per_customer'] ?? 1,
                'min_cart_value' => $validated['min_cart_value'] ?? null,
                'exclude_new_products' => $request->boolean('exclude_new_products', false),
            ]);

            // Mettre à jour les utilisateurs (via coupon_usage table)
            // Note: users relationship is through coupon_usage, not a direct many-to-many

            // Mettre à jour les produits
            $coupon->products()->sync($validated['product_ids'] ?? []);

            // Mettre à jour les catégories
            $coupon->categories()->sync($validated['category_ids'] ?? []);

            DB::commit();

            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon mis à jour avec succès');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la mise à jour du coupon', [
                'error' => $e->getMessage(),
                'coupon_id' => $coupon->id,
                'data' => $request->all()
            ]);

            return back()->withInput()
                ->with('error', 'Erreur lors de la mise à jour du coupon: ' . $e->getMessage());
        }
    }

    public function destroy(Coupon $coupon)
    {
        try {
            DB::beginTransaction();

            // Vérifier si le coupon a été utilisé
            if ($coupon->usages()->count() > 0) {
                return back()->with('error', 'Impossible de supprimer un coupon qui a été utilisé. Vous pouvez le désactiver à la place.');
            }

            $coupon->users()->detach();
            $coupon->delete();

            DB::commit();

            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon supprimé avec succès');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la suppression du coupon', [
                'error' => $e->getMessage(),
                'coupon_id' => $coupon->id
            ]);

            return back()->with('error', 'Erreur lors de la suppression du coupon: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Coupon $coupon)
    {
        try {
            $coupon->update([
                'is_active' => !$coupon->is_active
            ]);

            $status = $coupon->is_active ? 'activé' : 'désactivé';

            return response()->json([
                'success' => true,
                'message' => "Coupon {$status} avec succès",
                'is_active' => $coupon->is_active
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du changement de statut'
            ], 500);
        }
    }

    public function duplicate(Coupon $coupon)
    {
        try {
            $newCoupon = $this->couponService->duplicateCoupon($coupon);

            return redirect()->route('admin.coupons.edit', $newCoupon)
                ->with('success', "Coupon dupliqué avec succès. Nouveau code: {$newCoupon->code}");

        } catch (Exception $e) {
            Log::error('Erreur lors de la duplication du coupon', [
                'error' => $e->getMessage(),
                'coupon_id' => $coupon->id
            ]);

            return back()->with('error', 'Erreur lors de la duplication du coupon');
        }
    }

    public function bulkCreate()
    {
        return view('admin.coupons.bulk-create');
    }

    public function bulkStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:1|max:1000',
                'prefix' => 'nullable|string|max:10',
                'code_length' => 'required|integer|min:4|max:20',
                'discount_type' => 'required|in:percentage,fixed_amount',
                'discount_value' => 'required|numeric|min:0',
                'max_discount_amount' => 'nullable|numeric|min:0',
                'minimum_purchase_amount' => 'nullable|numeric|min:0',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after:start_date',
                'max_uses' => 'nullable|integer|min:1',
                'max_uses_per_user' => 'nullable|integer|min:1',
            ]);

            $coupons = $this->couponService->createBulkCoupons([
                'prefix' => $validated['prefix'] ?? '',
                'code_length' => $validated['code_length'],
                'discount_type' => $validated['discount_type'],
                'discount_value' => $validated['discount_value'],
                'max_discount_amount' => $validated['max_discount_amount'] ?? null,
                'minimum_purchase_amount' => $validated['minimum_purchase_amount'] ?? null,
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'] ?? null,
                'is_active' => true,
                'max_uses' => $validated['max_uses'] ?? null,
                'max_uses_per_user' => $validated['max_uses_per_user'] ?? null,
                'user_specific' => false,
                'usage_count' => 0,
            ], $validated['quantity']);

            return redirect()->route('admin.coupons.index')
                ->with('success', "{$validated['quantity']} coupons créés avec succès");

        } catch (Exception $e) {
            Log::error('Erreur lors de la création en masse de coupons', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return back()->withInput()
                ->with('error', 'Erreur lors de la création en masse: ' . $e->getMessage());
        }
    }

    public function validateCouponCode(Request $request)
    {
        $code = $request->input('code');
        $userId = $request->input('user_id');
        $cartTotal = $request->input('cart_total');

        $validation = $this->couponService->validateCoupon($code, $userId, $cartTotal);

        return response()->json($validation);
    }

    public function stats()
    {
        $totalCoupons = Coupon::count();
        $activeCoupons = Coupon::where('is_active', true)->count();
        $totalUsages = DB::table('coupon_usage')->count();
        $totalDiscountGiven = DB::table('coupon_usage')->sum('discount_amount');

        $topCoupons = Coupon::withCount('usages')
            ->orderBy('usages_count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.coupons.stats', compact(
            'totalCoupons',
            'activeCoupons',
            'totalUsages',
            'totalDiscountGiven',
            'topCoupons'
        ));
    }

    public function export()
    {
        $coupons = Coupon::all();
        
        $csv = "Code,Description,Type,Valeur,Utilisations,Statut\n";
        
        foreach ($coupons as $coupon) {
            $csv .= "\"{$coupon->code}\",";
            $csv .= "\"{$coupon->description}\",";
            $csv .= "\"{$coupon->discount_type}\",";
            $csv .= "\"{$coupon->discount_value}\",";
            $csv .= "\"{$coupon->usage_count}/{$coupon->max_uses}\",";
            $csv .= "\"" . ($coupon->is_active ? 'Actif' : 'Inactif') . "\"\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="coupons_' . date('Y-m-d') . '.csv"');
    }
}
