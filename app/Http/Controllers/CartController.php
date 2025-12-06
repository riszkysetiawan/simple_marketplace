<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display cart page
     */
    public function index()
    {
        return view('cart.index');
    }
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock
        ]);
        if (! $product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Product is not available'
            ], 400);
        }
        if ($product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock. Available: ' . $product->stock
            ], 400);
        }
        $cart = session()->get('cart', []);
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
        } else {
            $cart[$product->id] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'quantity' => $request->quantity,
                'image' => $product->image,
            ];
        }
        session()->put('cart', $cart);
        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully',
            'cart_count' => count($cart),
            'data' => $cart[$product->id]
        ]);
    }
    public function update(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);

        if (! isset($cart[$productId])) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in cart'
            ], 404);
        }
        $product = Product::find($productId);
        if ($product && $product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock.Available: ' . $product->stock
            ], 400);
        }
        $cart[$productId]['quantity'] = $request->quantity;
        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully',
            'data' => $cart[$productId]
        ]);
    }
    public function remove($productId)
    {
        $cart = session()->get('cart', []);

        if (! isset($cart[$productId])) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in cart'
            ], 404);
        }
        unset($cart[$productId]);
        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart',
            'cart_count' => count($cart)
        ]);
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);
    }

    /**
     * Get cart data (AJAX)
     */
    public function getCart()
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'items' => array_values($cart),
                'count' => count($cart),
                'total' => $total
            ]
        ]);
    }
}
