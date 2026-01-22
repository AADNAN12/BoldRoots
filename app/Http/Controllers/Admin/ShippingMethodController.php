<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class ShippingMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_shipping_methods,admin')->only(['index', 'show']);
        $this->middleware('permission:create_shipping_methods,admin')->only(['create', 'store']);
        $this->middleware('permission:edit_shipping_methods,admin')->only(['edit', 'update', 'toggleStatus']);
        $this->middleware('permission:delete_shipping_methods,admin')->only(['destroy']);
    }

    public function index()
    {
        $shippingMethods = ShippingMethod::orderBy('name')->get();
        return view('admin.shipping-methods.index', compact('shippingMethods'));
    }

    public function create()
    {
        return view('admin.shipping-methods.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'cost' => 'required|numeric|min:0',
                'estimated_days' => 'nullable|string|max:50',
                'is_active' => 'boolean',
            ]);

            $shippingMethod = ShippingMethod::create([
                'name' => $validated['name'],
                'cost' => $validated['cost'],
                'estimated_days' => $validated['estimated_days'] ?? null,
                'is_active' => $request->boolean('is_active'),
            ]);

            return redirect()->route('admin.shipping-methods.index')
                ->with('success', 'Méthode de livraison créée avec succès');

        } catch (Exception $e) {
            Log::error('Erreur lors de la création de la méthode de livraison', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return back()->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function show(ShippingMethod $shippingMethod)
    {
        return view('admin.shipping-methods.show', compact('shippingMethod'));
    }

    public function edit(ShippingMethod $shippingMethod)
    {
        return view('admin.shipping-methods.edit', compact('shippingMethod'));
    }

    public function update(Request $request, ShippingMethod $shippingMethod)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'cost' => 'required|numeric|min:0',
                'estimated_days' => 'nullable|string|max:50',
                'is_active' => 'boolean',
            ]);

            $shippingMethod->update([
                'name' => $validated['name'],
                'cost' => $validated['cost'],
                'estimated_days' => $validated['estimated_days'] ?? null,
                'is_active' => $request->boolean('is_active'),
            ]);

            return redirect()->route('admin.shipping-methods.index')
                ->with('success', 'Méthode de livraison mise à jour avec succès');

        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour de la méthode de livraison', [
                'error' => $e->getMessage(),
                'shipping_method_id' => $shippingMethod->id,
            ]);

            return back()->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function destroy(ShippingMethod $shippingMethod)
    {
        try {
            $shippingMethod->delete();

            return redirect()->route('admin.shipping-methods.index')
                ->with('success', 'Méthode de livraison supprimée avec succès');

        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression de la méthode de livraison', [
                'error' => $e->getMessage(),
                'shipping_method_id' => $shippingMethod->id,
            ]);

            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function toggleStatus(ShippingMethod $shippingMethod)
    {
        try {
            $shippingMethod->update(['is_active' => !$shippingMethod->is_active]);

            return response()->json([
                'success' => true,
                'is_active' => $shippingMethod->is_active,
                'message' => 'Statut mis à jour avec succès',
            ]);

        } catch (Exception $e) {
            Log::error('Erreur lors du changement de statut', [
                'error' => $e->getMessage(),
                'shipping_method_id' => $shippingMethod->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
            ], 500);
        }
    }
}
