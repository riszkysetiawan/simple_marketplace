<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display product listing with filters
     */
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)->with('category');
        if ($request->filled('featured')) {
            $query->where('is_featured', true);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }
        if ($request->filled('in_stock')) {
            $query->where('stock', '>', 0);
        }
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->latest();
        }
        $perPage = $request->get('per_page', 10);
        $products = $query->paginate($perPage)->withQueryString();

        return view('shop.index', compact('products'));
    }

    /**
     * Display single product detail
     */
    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404, 'Product not found');
        }
        $product->load('category');
        $relatedProducts = Product::where('is_active', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->limit(4)
            ->get();

        return view('shop.product-detail', compact('product', 'relatedProducts'));
    }

    /**
     * Display products by category
     */
    public function category(Category $category)
    {
        if (!$category->is_active) {
            abort(404, 'Category not found');
        }
        $products = Product::where('is_active', true)
            ->where('category_id', $category->id)
            ->latest()
            ->paginate(10);

        return view('shop.category', compact('category', 'products'));
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        if (empty($query)) {
            return redirect()->route('shop.index');
        }
        $products = Product::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                    ->orWhere('description', 'like', '%' . $query . '%')
                    ->orWhere('sku', 'like', '%' . $query . '%');
            })
            ->with('category')
            ->paginate(10)
            ->appends(['q' => $query]);
        return view('shop.search', compact('products', 'query'));
    }

    /**
     * Get featured products (for API or AJAX)
     */
    public function featured()
    {
        $products = Product::where('is_active', true)
            ->where('is_featured', true)
            ->where('stock', '>', 0)
            ->with('category')
            ->latest()
            ->limit(12)
            ->get();

        return view('shop.featured', compact('products'));
    }

    /**
     * Get products by price range (for filtering)
     */
    public function filterByPrice(Request $request)
    {
        $minPrice = $request->input('min', 0);
        $maxPrice = $request->input('max', 999999999);

        $products = Product::where('is_active', true)
            ->whereBetween('price', [$minPrice, $maxPrice])
            ->with('category')
            ->paginate(10)
            ->appends(['min' => $minPrice, 'max' => $maxPrice]);

        return view('shop.index', compact('products'));
    }
}
