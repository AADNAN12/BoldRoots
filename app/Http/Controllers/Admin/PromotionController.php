<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Product;
use App\Models\Category;
use App\Services\PromotionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class PromotionController extends Controller
{
    protected $promotionService;

    public function __construct(PromotionService $promotionService)
    {
        $this->promotionService = $promotionService;
    }

    public function index()
    {
        $promotions = Promotion::with(['products', 'categories', 'usages'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->get();
        $categories = Category::where('is_active', true)->get();

        return view('admin.promotions.create', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'required|in:flash_deal,regular_sale,buy_x_get_y',
                'discount_type' => 'required|in:percentage,fixed_amount',
                'discount_value' => 'required|numeric|min:0',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'is_active' => 'boolean',
                'scope' => 'required|in:product,collection,cart',
                'max_per_customer' => 'nullable|integer|min:1',
                'stop_when_stock_below' => 'nullable|integer|min:0',
                'min_cart_value' => 'nullable|numeric|min:0',
                'max_uses' => 'nullable|integer|min:1',
                'buy_quantity' => 'nullable|integer|min:1',
                'get_quantity' => 'nullable|integer|min:1',
                'product_ids' => 'nullable|array',
                'product_ids.*' => 'exists:products,id',
                'category_ids' => 'nullable|array',
                'category_ids.*' => 'exists:categories,id',
            ]);

            // Validation supplémentaire pour buy_x_get_y
            if ($validated['type'] === 'buy_x_get_y') {
                if (!isset($validated['buy_quantity']) || !isset($validated['get_quantity'])) {
                    return back()->withInput()
                        ->with('error', 'Les quantités "Acheter" et "Obtenir" sont requises pour ce type de promotion');
                }
            }

            // Validation du pourcentage
            if ($validated['discount_type'] === 'percentage' && $validated['discount_value'] > 100) {
                return back()->withInput()
                    ->with('error', 'Le pourcentage ne peut pas dépasser 100%');
            }

            $promotion = Promotion::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'type' => $validated['type'],
                'discount_type' => $validated['discount_type'],
                'discount_value' => $validated['discount_value'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'is_active' => $request->boolean('is_active'),
                'scope' => $validated['scope'],
                'max_per_customer' => $validated['max_per_customer'] ?? null,
                'stop_when_stock_below' => $validated['stop_when_stock_below'] ?? 0,
                'min_cart_value' => $validated['min_cart_value'] ?? null,
                'max_uses' => $validated['max_uses'] ?? null,
                'buy_quantity' => $validated['buy_quantity'] ?? null,
                'get_quantity' => $validated['get_quantity'] ?? null,
                'usage_count' => 0,
            ]);

            // Associer les produits
            if ($validated['scope'] === 'product' && !empty($validated['product_ids'])) {
                $promotion->products()->attach($validated['product_ids']);
            }

            // Associer les catégories
            if ($validated['scope'] === 'collection' && !empty($validated['category_ids'])) {
                $promotion->categories()->attach($validated['category_ids']);
            }

            DB::commit();

            return redirect()->route('admin.promotions.index')
                ->with('success', 'Promotion créée avec succès');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création de la promotion', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return back()->withInput()
                ->with('error', 'Erreur lors de la création de la promotion: ' . $e->getMessage());
        }
    }

    public function show(Promotion $promotion)
    {
        $promotion->load(['products', 'categories', 'usages.user']);
        $stats = $this->promotionService->getPromotionStats($promotion);

        return view('admin.promotions.show', compact('promotion', 'stats'));
    }

    public function edit(Promotion $promotion)
    {
        $promotion->load(['products', 'categories']);
        $products = Product::where('is_active', true)->get();
        $categories = Category::where('is_active', true)->get();

        return view('admin.promotions.edit', compact('promotion', 'products', 'categories'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'required|in:flash_deal,regular_sale,buy_x_get_y',
                'discount_type' => 'required|in:percentage,fixed_amount',
                'discount_value' => 'required|numeric|min:0',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'is_active' => 'boolean',
                'scope' => 'required|in:product,collection,cart',
                'max_per_customer' => 'nullable|integer|min:1',
                'stop_when_stock_below' => 'nullable|integer|min:0',
                'min_cart_value' => 'nullable|numeric|min:0',
                'max_uses' => 'nullable|integer|min:1',
                'buy_quantity' => 'nullable|integer|min:1',
                'get_quantity' => 'nullable|integer|min:1',
                'product_ids' => 'nullable|array',
                'product_ids.*' => 'exists:products,id',
                'category_ids' => 'nullable|array',
                'category_ids.*' => 'exists:categories,id',
            ]);

            // Validation du pourcentage
            if ($validated['discount_type'] === 'percentage' && $validated['discount_value'] > 100) {
                return back()->withInput()
                    ->with('error', 'Le pourcentage ne peut pas dépasser 100%');
            }

            $promotion->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'type' => $validated['type'],
                'discount_type' => $validated['discount_type'],
                'discount_value' => $validated['discount_value'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'is_active' => $request->boolean('is_active'),
                'scope' => $validated['scope'],
                'max_per_customer' => $validated['max_per_customer'] ?? null,
                'stop_when_stock_below' => $validated['stop_when_stock_below'] ?? 0,
                'min_cart_value' => $validated['min_cart_value'] ?? null,
                'max_uses' => $validated['max_uses'] ?? null,
                'buy_quantity' => $validated['buy_quantity'] ?? null,
                'get_quantity' => $validated['get_quantity'] ?? null,
            ]);

            // Mettre à jour les produits
            if ($validated['scope'] === 'product') {
                $promotion->products()->sync($validated['product_ids'] ?? []);
            } else {
                $promotion->products()->detach();
            }

            // Mettre à jour les catégories
            if ($validated['scope'] === 'collection') {
                $promotion->categories()->sync($validated['category_ids'] ?? []);
            } else {
                $promotion->categories()->detach();
            }

            DB::commit();

            return redirect()->route('admin.promotions.index')
                ->with('success', 'Promotion mise à jour avec succès');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la mise à jour de la promotion', [
                'error' => $e->getMessage(),
                'promotion_id' => $promotion->id,
                'data' => $request->all()
            ]);

            return back()->withInput()
                ->with('error', 'Erreur lors de la mise à jour de la promotion: ' . $e->getMessage());
        }
    }

    public function destroy(Promotion $promotion)
    {
        try {
            DB::beginTransaction();

            // Vérifier si la promotion a été utilisée
            if ($promotion->usages()->count() > 0) {
                return back()->with('error', 'Impossible de supprimer une promotion qui a été utilisée. Vous pouvez la désactiver à la place.');
            }

            $promotion->products()->detach();
            $promotion->categories()->detach();
            $promotion->delete();

            DB::commit();

            return redirect()->route('admin.promotions.index')
                ->with('success', 'Promotion supprimée avec succès');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la suppression de la promotion', [
                'error' => $e->getMessage(),
                'promotion_id' => $promotion->id
            ]);

            return back()->with('error', 'Erreur lors de la suppression de la promotion: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Promotion $promotion)
    {
        try {
            $promotion->update([
                'is_active' => !$promotion->is_active
            ]);

            $status = $promotion->is_active ? 'activée' : 'désactivée';

            return response()->json([
                'success' => true,
                'message' => "Promotion {$status} avec succès",
                'is_active' => $promotion->is_active
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du changement de statut'
            ], 500);
        }
    }

    public function duplicate(Promotion $promotion)
    {
        try {
            DB::beginTransaction();

            $newPromotion = $promotion->replicate();
            $newPromotion->name = $promotion->name . ' (Copie)';
            $newPromotion->is_active = false;
            $newPromotion->usage_count = 0;
            $newPromotion->save();

            // Copier les relations
            if ($promotion->products()->count() > 0) {
                $productIds = $promotion->products()->pluck('product_id')->toArray();
                $newPromotion->products()->attach($productIds);
            }

            if ($promotion->categories()->count() > 0) {
                $categoryIds = $promotion->categories()->pluck('category_id')->toArray();
                $newPromotion->categories()->attach($categoryIds);
            }

            DB::commit();

            return redirect()->route('admin.promotions.edit', $newPromotion)
                ->with('success', 'Promotion dupliquée avec succès');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la duplication de la promotion', [
                'error' => $e->getMessage(),
                'promotion_id' => $promotion->id
            ]);

            return back()->with('error', 'Erreur lors de la duplication de la promotion');
        }
    }

    public function stats()
    {
        $activePromotions = $this->promotionService->getActivePromotions();
        $totalPromotions = Promotion::count();
        $totalUsages = DB::table('promotion_usages')->count();
        $totalDiscountGiven = DB::table('promotion_usages')->sum('discount_amount');

        $promotionsByType = Promotion::select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get();

        return view('admin.promotions.stats', compact(
            'activePromotions',
            'totalPromotions',
            'totalUsages',
            'totalDiscountGiven',
            'promotionsByType'
        ));
    }
}
