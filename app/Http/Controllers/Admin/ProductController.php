<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;

class ProductController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:manage_products,admin')->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    // }

    public function index()
    {
        $products = Product::with(['category', 'variants', 'images'])
            ->latest()
            ->paginate(20);
        
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->where('is_active', true)
            ->get();
        
        $colors = AttributeValue::whereHas('attribute', function($query) {
            $query->where('type', 'color');
        })->with('attribute')->get();
        
        $sizes = AttributeValue::whereHas('attribute', function($query) {
            $query->where('type', 'select');
        })->with('attribute')->get();
        // dd($sizes); 
        
        return view('admin.products.create', compact('categories', 'colors', 'sizes'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'sku' => 'nullable|string|max:100|unique:products,sku',
                'description' => 'required|string|min:50',
                'category_id' => 'required|exists:categories,id',
                'price' => 'required|numeric|min:0',
                'compare_price' => 'nullable|numeric|min:0',
                'is_new' => 'boolean',
                'is_featured' => 'boolean',
                'is_active' => 'boolean',
                'variants' => 'required|array|min:1',
                'variants.*.color_id' => 'required|exists:attribute_values,id',
                'variants.*.size_id' => 'required|exists:attribute_values,id',
                'variants.*.quantity' => 'required|integer|min:0',
                'variants.*.low_stock_threshold' => 'nullable|integer|min:0',
                'variants.*.sku' => 'nullable|string|max:100',
            ]);
            
            $slug = Str::slug($validated['name']);
            $originalSlug = $slug;
            $count = 1;
            
            while (Product::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
            
            if (empty($validated['sku'])) {
                $validated['sku'] = $this->generateProductSku($validated['name']);
            }
            
            $product = Product::create([
                'name' => $validated['name'],
                'slug' => $slug,
                'sku' => $validated['sku'],
                'description' => $validated['description'],
                'category_id' => $validated['category_id'],
                'price' => $validated['price'],
                'compare_price' => $validated['compare_price'] ?? null,
                'is_new' => $request->boolean('is_new', true),
                'is_featured' => $request->boolean('is_featured', false),
                'is_active' => $request->boolean('is_active', true),
            ]);
            
            foreach ($validated['variants'] as $variantData) {
                $variantSku = $variantData['sku'] ?? $this->generateVariantSku(
                    $product->sku,
                    $variantData['color_id'],
                    $variantData['size_id']
                );
                
                ProductVariant::create([
                    'product_id' => $product->id,
                    'color_id' => $variantData['color_id'],
                    'size_id' => $variantData['size_id'],
                    'quantity' => $variantData['quantity'],
                    'low_stock_threshold' => $variantData['low_stock_threshold'] ?? 5,
                    'sku' => $variantSku,
                ]);
            }
            
            // Récupérer l'information de l'image homepage
            $homepageImageInfo = null;
            if ($request->has('homepage_image_info') && $request->input('homepage_image_info')) {
                $homepageImageInfo = json_decode($request->input('homepage_image_info'), true);
            }
            
            // Gestion des images
            if ($request->has('images')) {
                foreach ($request->file('images') as $colorId => $colorImages) {
                    if (is_array($colorImages)) {
                        foreach ($colorImages as $index => $imageFile) {
                            // Générer un nom unique pour l'image
                            $filename = time() . '_' . $colorId . '_' . $index . '.' . $imageFile->getClientOriginalExtension();
                            
                            // Stocker l'image dans storage/app/public/products
                            $imagePath = Storage::disk('public')->putFileAs('products', $imageFile, $filename);
                            
                            // Vérifier si c'est l'image principale
                            $isPrimary = $request->input("images_primary.{$colorId}") == $index;
                            
                            // Vérifier si c'est l'image homepage
                            $isHomepage = false;
                            if ($homepageImageInfo && 
                                $homepageImageInfo['color_id'] == $colorId && 
                                $homepageImageInfo['index'] == $index) {
                                $isHomepage = true;
                            }
                            
                            ProductImage::create([
                                'product_id' => $product->id,
                                'color_id' => $colorId,
                                'image_path' => $imagePath,
                                'is_primary' => $isPrimary,
                                'is_homepage_image' => $isHomepage,
                                'sort_order' => $index,
                            ]);
                        }
                    }
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Produit créé avec succès');
                
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création du produit', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->except('images')
            ]);
            
            return back()->withInput()
                ->with('error', 'Erreur lors de la création du produit: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $product = Product::with(['category', 'variants.color', 'variants.size', 'images.color'])->findOrFail($id);
        
        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->where('is_active', true)
            ->get();
        
        $colors = AttributeValue::whereHas('attribute', function($query) {
            $query->where('type', 'color');
        })->with('attribute')->get();
        
        $sizes = AttributeValue::whereHas('attribute', function($query) {
            $query->where('type', 'select');
        })->with('attribute')->get();      
        return view('admin.products.edit', compact('product', 'categories', 'colors', 'sizes'));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $product = Product::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'sku' => 'nullable|string|max:100|unique:products,sku,' . $id,
                'description' => 'required|string|min:50',
                'category_id' => 'required|exists:categories,id',
                'price' => 'required|numeric|min:0',
                'compare_price' => 'nullable|numeric|min:0',
                'is_new' => 'boolean',
                'is_featured' => 'boolean',
                'is_active' => 'boolean',
                'variants' => 'required|array|min:1',
                'variants.*.color_id' => 'required|exists:attribute_values,id',
                'variants.*.size_id' => 'required|exists:attribute_values,id',
                'variants.*.quantity' => 'required|integer|min:0',
                'variants.*.low_stock_threshold' => 'nullable|integer|min:0',
                'variants.*.sku' => 'nullable|string|max:100',
            ]);
            
            $slug = Str::slug($validated['name']);
            if ($slug !== $product->slug) {
                $originalSlug = $slug;
                $count = 1;
                
                while (Product::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                    $slug = $originalSlug . '-' . $count++;
                }
            }
            
            $product->update([
                'name' => $validated['name'],
                'slug' => $slug,
                'sku' => $validated['sku'] ?? $product->sku,
                'description' => $validated['description'],
                'category_id' => $validated['category_id'],
                'price' => $validated['price'],
                'compare_price' => $validated['compare_price'] ?? null,
                'is_new' => $request->boolean('is_new'),
                'is_featured' => $request->boolean('is_featured'),
                'is_active' => $request->boolean('is_active'),
            ]);
            
            // Récupérer les IDs des variantes à conserver
            $variantIds = [];
            
            // Mettre à jour ou créer les variantes
            foreach ($validated['variants'] as $variantData) {
                $variantSku = $variantData['sku'] ?? $this->generateVariantSku(
                    $product->sku,
                    $variantData['color_id'],
                    $variantData['size_id']
                );
                
                // Utiliser updateOrCreate pour éviter les doublons
                $variant = ProductVariant::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'color_id' => $variantData['color_id'],
                        'size_id' => $variantData['size_id'],
                    ],
                    [
                        'quantity' => $variantData['quantity'],
                        'low_stock_threshold' => $variantData['low_stock_threshold'] ?? 5,
                        'sku' => $variantSku,
                    ]
                );
                
                $variantIds[] = $variant->id;
            }
            
            // Supprimer les variantes qui ne sont plus sélectionnées
            ProductVariant::where('product_id', $product->id)
                ->whereNotIn('id', $variantIds)
                ->delete();
            
            // Récupérer l'information de l'image homepage
            $homepageImageInfo = null;
            if ($request->has('homepage_image_info') && $request->input('homepage_image_info')) {
                $homepageImageInfo = json_decode($request->input('homepage_image_info'), true);
            }
            
            // Gestion des nouvelles images
            $newImageIds = [];
            if ($request->has('images')) {
                foreach ($request->file('images') as $colorId => $colorImages) {
                    if (is_array($colorImages)) {
                        foreach ($colorImages as $index => $imageFile) {
                            // Générer un nom unique pour l'image
                            $filename = time() . '_' . $colorId . '_' . $index . '.' . $imageFile->getClientOriginalExtension();
                            
                            // Stocker l'image dans storage/app/public/products
                            $imagePath = Storage::disk('public')->putFileAs('products', $imageFile, $filename);
                            
                            // Vérifier si c'est l'image principale
                            $isPrimary = $request->input("images_primary.{$colorId}") == $index;
                            
                            // Vérifier si c'est l'image homepage
                            $isHomepage = false;
                            if ($homepageImageInfo && 
                                !$homepageImageInfo['existing'] && 
                                $homepageImageInfo['color_id'] == $colorId && 
                                $homepageImageInfo['index'] == $index) {
                                $isHomepage = true;
                            }
                            
                            $newImage = ProductImage::create([
                                'product_id' => $product->id,
                                'color_id' => $colorId,
                                'image_path' => $imagePath,
                                'is_primary' => $isPrimary,
                                'is_homepage_image' => $isHomepage,
                                'sort_order' => $index,
                            ]);
                            
                            // Stocker l'ID pour référence
                            $newImageIds["{$colorId}_{$index}"] = $newImage->id;
                        }
                    }
                }
            }
            
            // Si l'image homepage est une image existante, la mettre à jour
            if ($homepageImageInfo && $homepageImageInfo['existing'] && $homepageImageInfo['image_id']) {
                // Désactiver toutes les images homepage
                ProductImage::where('product_id', $product->id)
                    ->update(['is_homepage_image' => false]);
                
                // Activer l'image sélectionnée
                ProductImage::where('id', $homepageImageInfo['image_id'])
                    ->where('product_id', $product->id)
                    ->update(['is_homepage_image' => true]);
            }
            
            DB::commit();
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Produit mis à jour avec succès');
                
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la mise à jour du produit', [
                'error' => $e->getMessage(),
                'product_id' => $id,
                'data' => $request->except('images')
            ]);
            
            return back()->withInput()
                ->with('error', 'Erreur lors de la mise à jour du produit: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $product = Product::findOrFail($id);
            
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }
            
            $product->delete();
            
            DB::commit();
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Produit supprimé avec succès');
                
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la suppression du produit', [
                'error' => $e->getMessage(),
                'product_id' => $id
            ]);
            
            return back()->with('error', 'Erreur lors de la suppression du produit: ' . $e->getMessage());
        }
    }

    public function deleteImage(Product $product, ProductImage $image)
    {
        try {
            if ($image->product_id !== $product->id) {
                return response()->json(['error' => 'Image non trouvée pour ce produit'], 404);
            }

            DB::beginTransaction();

            // Supprimer le fichier physique
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }

            // Supprimer l'enregistrement de la base de données
            $imageId = $image->id;
            $deleted = $image->delete();

            if (!$deleted) {
                DB::rollBack();
                return response()->json(['error' => 'Impossible de supprimer l\'image de la base de données'], 500);
            }

            DB::commit();

            Log::info('Image supprimée avec succès', [
                'image_id' => $imageId,
                'product_id' => $product->id
            ]);

            return response()->json(['success' => true, 'message' => 'Image supprimée avec succès']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la suppression de l\'image', [
                'error' => $e->getMessage(),
                'image_id' => $image->id ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Erreur lors de la suppression de l\'image: ' . $e->getMessage()], 500);
        }
    }

    public function setHomepageImage(Request $request, Product $product)
    {
        try {
            $request->validate([
                'image_id' => 'nullable|exists:product_images,id'
            ]);

            $imageId = $request->image_id;

            DB::beginTransaction();

            // Désactiver toutes les images homepage pour ce produit
            ProductImage::where('product_id', $product->id)
                ->update(['is_homepage_image' => false]);

            // Si image_id est fourni, activer cette image
            if ($imageId) {
                // Vérifier que l'image appartient bien au produit
                $image = ProductImage::where('id', $imageId)
                    ->where('product_id', $product->id)
                    ->first();
                
                if (!$image) {
                    DB::rollBack();
                    return response()->json(['error' => 'Image non trouvée pour ce produit'], 404);
                }

                // Activer l'image sélectionnée
                $image->is_homepage_image = true;
                $image->save();

                $message = 'Image homepage définie avec succès';
            } else {
                $message = 'Image homepage désactivée avec succès';
            }

            DB::commit();

            Log::info('Image homepage modifiée', [
                'product_id' => $product->id,
                'image_id' => $imageId,
                'action' => $imageId ? 'activated' : 'deactivated'
            ]);

            return response()->json([
                'success' => true, 
                'message' => $message
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la modification de l\'image homepage', [
                'error' => $e->getMessage(),
                'product_id' => $product->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Erreur lors de la modification de l\'image homepage: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateProductSku($name)
    {
        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $name), 0, 3));
        $random = strtoupper(Str::random(6));
        
        return $prefix . '-' . $random;
    }

    private function generateVariantSku($productSku, $colorId, $sizeId)
    {
        $color = AttributeValue::find($colorId);
        $size = AttributeValue::find($sizeId);
        
        $colorCode = $color ? strtoupper(substr($color->value, 0, 2)) : 'XX';
        $sizeCode = $size ? strtoupper(substr($size->value, 0, 2)) : 'XX';
        
        return $productSku . '-' . $colorCode . '-' . $sizeCode;
    }
}
