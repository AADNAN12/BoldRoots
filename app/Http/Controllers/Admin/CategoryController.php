<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class CategoryController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:manage_categories')->only('index', 'create', 'store', 'edit', 'update', 'destroy', 'show');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('parent')->get();
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('admin.categories.index', compact('categories', 'parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255',
                'parent_id' => 'nullable|exists:categories,id',
                'description' => 'nullable|string',
            ]);
            
            // Generate slug if empty
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['name']);
            } else {
                $validated['slug'] = Str::slug($validated['slug']);
            }
            
            // Check if slug exists and make it unique if needed
            $originalSlug = $validated['slug'];
            $count = 1;
            
            while (Category::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $originalSlug . '-' . $count++;
            }
            
            $category = Category::create($validated);
            
            DB::commit();
            
            return redirect()->route('admin.categories.index')
                ->with('success', 'Catégorie créée avec succès');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création de la catégorie', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);
            
            return redirect()->route('admin.categories.index')
                ->with('error', 'Erreur lors de la création de la catégorie: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::with('parent')->findOrFail($id);
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('Admin.categories.show', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            
            $category = Category::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255|unique:categories,slug,' . $id,
                'parent_id' => 'nullable|exists:categories,id',
                'description' => 'nullable|string',
            ]);
            
            // Vérifier que la catégorie n'est pas son propre parent
            if (!empty($validated['parent_id']) && $validated['parent_id'] == $id) {
                return redirect()->route('admin.categories.index')
                    ->with('error', 'Une catégorie ne peut pas être son propre parent');
            }
            
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['name']);
            }
            
            $category->update($validated);
            
            DB::commit();
            
            return redirect()->route('admin.categories.index')
                ->with('success', 'Catégorie mise à jour avec succès');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la mise à jour de la catégorie', [
                'error' => $e->getMessage(),
                'category_id' => $id,
                'data' => $request->all()
            ]);
            
            return redirect()->route('admin.categories.index')
                ->with('error', 'Erreur lors de la mise à jour de la catégorie: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            
            // Vérifier si la catégorie existe
            $category = Category::find($id);
            
            if (!$category) {
                return redirect()->route('admin.categories.index')
                    ->with('error', 'La catégorie n\'existe pas ou a déjà été supprimée.');
            }
            
            // Vérifier si la catégorie a des enfants
            if ($category->children()->count() > 0) {
                return redirect()->route('admin.categories.index')
                    ->with('error', 'Cette catégorie contient des sous-catégories. Veuillez les supprimer d\'abord.');
            }
            
            // Vérifier si la catégorie a des produits associés
            if ($category->products()->count() > 0) {
                return redirect()->route('admin.categories.index')
                    ->with('error', 'Cette catégorie contient des produits. Veuillez les supprimer ou les réaffecter d\'abord.');
            }
            
            $category->delete();
            
            DB::commit();
            
            return redirect()->route('admin.categories.index')
                ->with('success', 'Catégorie supprimée avec succès');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la suppression de la catégorie', [
                'error' => $e->getMessage(),
                'category_id' => $id
            ]);
            
            return redirect()->route('admin.categories.index')
                ->with('error', 'Erreur lors de la suppression de la catégorie: ' . $e->getMessage());
        }
    }
}
