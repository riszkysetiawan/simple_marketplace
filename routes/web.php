<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\SocialLoginController;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.Make something great!
|
*/

// ========================================
// PUBLIC ROUTES
// ======================================

// Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ========================================
// SOCIAL LOGIN ROUTES (Google & Facebook OAuth)
// ========================================

Route::prefix('auth')->name('auth.')->group(function () {
    // Google OAuth
    Route::get('/google', [SocialLoginController::class, 'redirectToGoogle'])->name('google');
    Route::get('/google/callback', [SocialLoginController::class, 'handleGoogleCallback'])->name('google.callback');

    // Facebook OAuth
    Route::get('/facebook', [SocialLoginController::class, 'redirectToFacebook'])->name('facebook');
    Route::get('/facebook/callback', [SocialLoginController::class, 'handleFacebookCallback'])->name('facebook.callback');
});

// ========================================
// DASHBOARD ROUTES (Role-based Redirect)
// ========================================

// Route::get('/dashboard', function () {
//     if (auth()->check()) {
//         $user = auth()->user();

//         // Super Admin → Redirect to Admin Panel (Filament)
//         if ($user->hasRole('super_admin')) {
//             return redirect()->intended('/admin');
//         }

//         // Customer → Redirect to Customer Panel (Filament)
//         if ($user->hasRole('customer')) {
//             return redirect()->intended('/admin');
//         }

//         // Fallback for other roles
//         return redirect('/');
//     }

//     // Not logged in → Redirect to login
//     return redirect()->route('login');
// })->middleware(['auth', 'verified'])->name('dashboard');
// Route::get('/dashboard', function () {
//     if (auth()->check()) {
//         return redirect()->intended('/admin');  // ✅ Semua ke /admin
//     }
//     return redirect()->route('login');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard', function () {
    if (auth()->check()) {
        $user = auth()->user();

        // Super Admin → /admin
        if ($user->hasRole('super_admin')) {
            return redirect()->intended('/admin');
        }

        // Customer → /customer
        if ($user->hasRole('customer')) {
            return redirect()->intended('/customer');
        }

        // Default fallback
        return redirect('/');
    }

    return redirect()->route('login');
})->middleware(['auth', 'verified'])->name('dashboard');

// ❌ HAPUS route /customer (kalau ada)

// ========================================
// PROFILE ROUTES (All Authenticated Users)
// ========================================

Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/', [ProfileController::class, 'update'])->name('update');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
});

// ========================================
// PUBLIC SHOP ROUTES (Product Catalog)
// ========================================

Route::prefix('shop')->name('shop.')->group(function () {
    // Product Listing
    Route::get('/', function () {
        $products = \App\Models\Product::with('category')
            ->where('is_active', true)
            ->paginate(12);

        return view('shop.index', compact('products'));
    })->name('index');

    // Product Detail
    Route::get('/products/{product:slug}', function (\App\Models\Product $product) {
        if (! $product->is_active) {
            abort(404, 'Product not found');
        }

        return view('shop.product-detail', compact('product'));
    })->name('products.show');

    // Category Products
    Route::get('/category/{category:slug}', function (\App\Models\Category $category) {
        $products = $category->products()
            ->where('is_active', true)
            ->paginate(12);

        return view('shop.category', compact('category', 'products'));
    })->name('category');

    // Search Products
    Route::get('/search', function () {
        $query = request('q');

        $products = \App\Models\Product::query()
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->paginate(12);

        return view('shop.search', compact('products', 'query'));
    })->name('search');
});

// ========================================
// CART ROUTES (Shopping Cart)
// ========================================

Route::middleware('auth')->prefix('cart')->name('cart.')->group(function () {
    // View Cart
    Route::get('/', function () {
        return view('cart.index');
    })->name('index');

    // Add to Cart
    Route::post('/add/{product}', function (\App\Models\Product $product) {
        // Cart logic will be implemented in Phase 5
        return redirect()->back()->with('success', 'Product added to cart!');
    })->name('add');

    // Update Cart Item
    Route::patch('/update/{item}', function ($item) {
        // Update cart logic
        return redirect()->back()->with('success', 'Cart updated!');
    })->name('update');

    // Remove from Cart
    Route::delete('/remove/{item}', function ($item) {
        // Remove from cart logic
        return redirect()->back()->with('success', 'Item removed from cart!');
    })->name('remove');

    // Clear Cart
    Route::delete('/clear', function () {
        // Clear cart logic
        return redirect()->route('cart.index')->with('success', 'Cart cleared!');
    })->name('clear');
});

// ========================================
// CHECKOUT ROUTES (Order Processing)
// ========================================

Route::middleware('auth')->prefix('checkout')->name('checkout.')->group(function () {
    // Checkout Page
    Route::get('/', function () {
        return view('checkout.index');
    })->name('index');

    // Process Checkout
    Route::post('/process', function () {
        // Process checkout logic
        return redirect()->route('checkout.success')->with('success', 'Order placed successfully!');
    })->name('process');

    // Checkout Success
    Route::get('/success', function () {
        return view('checkout.success');
    })->name('success');

    // Checkout Cancel
    Route::get('/cancel', function () {
        return redirect()->route('cart.index')->with('error', 'Checkout cancelled');
    })->name('cancel');
});

// ========================================
// WISHLIST ROUTES (Future Feature)
// ========================================

Route::middleware('auth')->prefix('wishlist')->name('wishlist.')->group(function () {
    Route::get('/', function () {
        return view('wishlist.index');
    })->name('index');

    Route::post('/add/{product}', function (\App\Models\Product $product) {
        // Add to wishlist logic
        return redirect()->back()->with('success', 'Added to wishlist!');
    })->name('add');

    Route::delete('/remove/{product}', function (\App\Models\Product $product) {
        // Remove from wishlist logic
        return redirect()->back()->with('success', 'Removed from wishlist!');
    })->name('remove');
});

// ========================================
// AUTHENTICATION ROUTES (Laravel Breeze)
// ========================================

require __DIR__ . '/auth.php';

// ========================================
// FALLBACK ROUTE (404 Handler)
// ========================================

Route::fallback(function () {
    if (view()->exists('errors.404')) {
        return response()->view('errors.404', [], 404);
    }

    return response()->json([
        'success' => false,
        'message' => 'Page not found',
    ], 404);
});
