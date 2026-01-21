<?php

namespace App\Http\Controllers\FrontOffice;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * Affichage de la liste des produits
     */
    public function index(Request $request)
    {
        $query = Product::with(['images', 'category', 'promotions', 'variants'])
            ->where('is_active', true);

        // Filtrage par catégorie
        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Recherche
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('sku', 'like', "%{$searchTerm}%");
            });
        }

        // Tri
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $products = $query->paginate(12);

        // Charger les catégories avec le nombre de produits
        $categories = Category::withCount(['products' => function($q) {
            $q->where('is_active', true);
        }])->where('is_active', true)->get();

        // Promotions actives
        $activePromotions = Promotion::where('is_active', true)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->get();

        return view('front-office.products.index', compact('products', 'categories', 'activePromotions'));
    }

    /**
     * Affichage des détails d'un produit
     */
    public function show($id)
    {
        $product = Product::with([
            'images', 
            'category', 
            'variants.color',
            'variants.size',
            'promotions' => function($q) {
                $q->where('is_active', true)
                  ->where('start_date', '<=', Carbon::now())
                  ->where('end_date', '>=', Carbon::now());
            }
        ])->where('is_active', true)->findOrFail($id);

        // Produits similaires
        $relatedProducts = Product::with([
            'images', 
            'promotions' => function($q) {
                $q->where('is_active', true)
                  ->where('start_date', '<=', Carbon::now())
                  ->where('end_date', '>=', Carbon::now());
            }
        ])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('front-office.products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Affichage des produits par catégorie
     */
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $products = Product::with(['images', 'promotions', 'variants'])
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->paginate(12);

        $categories = Category::withCount(['products' => function($q) {
            $q->where('is_active', true);
        }])->where('is_active', true)->get();

        return view('front-office.products.index', compact('products', 'categories', 'category'));
    }

    /**
     * Recherche de produits
     */
    public function search(Request $request)
    {
        $searchTerm = $request->input('q');
        
        $products = Product::with(['images', 'category', 'promotions', 'variants'])
            ->where('is_active', true)
            ->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('sku', 'like', "%{$searchTerm}%");
            })
            ->paginate(12);

        $categories = Category::withCount(['products' => function($q) {
            $q->where('is_active', true);
        }])->where('is_active', true)->get();

        return view('front-office.products.index', compact('products', 'categories', 'searchTerm'));
    }
}
