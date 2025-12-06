<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\Auth\CustomLogoutController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ========================================
// PUBLIC ROUTES
// ========================================

// Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ========================================
// SOCIAL LOGIN ROUTES
// ========================================

Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/google', [SocialLoginController::class, 'redirectToGoogle'])->name('google');
    Route::get('/google/callback', [SocialLoginController::class, 'handleGoogleCallback'])->name('google.callback');
    Route::get('/facebook', [SocialLoginController::class, 'redirectToFacebook'])->name('facebook');
    Route::get('/facebook/callback', [SocialLoginController::class, 'handleFacebookCallback'])->name('facebook.callback');
});

// ========================================
// DASHBOARD ROUTES
// ========================================

Route::get('/dashboard', function () {
    if (auth()->check()) {
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            return redirect()->intended('/admin');
        }

        if ($user->hasRole('customer')) {
            return redirect()->intended('/customer');
        }

        return redirect('/');
    }

    return redirect()->route('login');
})->middleware(['auth', 'verified'])->name('dashboard');

// ========================================
// CUSTOM LOGOUT ROUTE
// ========================================

Route::post('/logout', CustomLogoutController::class)
    ->middleware('auth')
    ->name('logout');

// ========================================
// PROFILE ROUTES
// ========================================

Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/', [ProfileController::class, 'update'])->name('update');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
});

// ========================================
// ✅ SHOP ROUTES (PUBLIC)
// ========================================

Route::prefix('shop')->name('shop.')->group(function () {
    // Main shop page with filters
    Route::get('/', [ShopController::class, 'index'])->name('index');

    // Product detail
    Route::get('/products/{product:slug}', [ShopController::class, 'show'])->name('products.show');

    // Category page
    Route::get('/category/{category:slug}', [ShopController::class, 'category'])->name('category');

    // Search
    Route::get('/search', [ShopController::class, 'search'])->name('search');

    // Featured products
    Route::get('/featured', [ShopController::class, 'featured'])->name('featured');
});

// ========================================
// CART ROUTES
// ========================================

Route::middleware('auth')->prefix('cart')->name('cart.')->group(function () {
    Route::get('/', function () {
        return view('cart.index');
    })->name('index');

    Route::post('/add/{product}', function (\App\Models\Product $product) {
        return redirect()->back()->with('success', 'Product added to cart! ');
    })->name('add');

    Route::patch('/update/{item}', function ($item) {
        return redirect()->back()->with('success', 'Cart updated!');
    })->name('update');

    Route::delete('/remove/{item}', function ($item) {
        return redirect()->back()->with('success', 'Item removed from cart!');
    })->name('remove');

    Route::delete('/clear', function () {
        return redirect()->route('cart.index')->with('success', 'Cart cleared!');
    })->name('clear');
});

// ========================================
// CHECKOUT ROUTES
// ========================================

Route::middleware('auth')->prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', function () {
        return view('checkout.index');
    })->name('index');

    Route::post('/process', function () {
        return redirect()->route('checkout.success')->with('success', 'Order placed successfully!');
    })->name('process');

    Route::get('/success', function () {
        return view('checkout.success');
    })->name('success');

    Route::get('/cancel', function () {
        return redirect()->route('cart.index')->with('error', 'Checkout cancelled');
    })->name('cancel');
});

// ========================================
// WISHLIST ROUTES
// ========================================

Route::middleware('auth')->prefix('wishlist')->name('wishlist.')->group(function () {
    Route::get('/', function () {
        return view('wishlist.index');
    })->name('index');

    Route::post('/add/{product}', function (\App\Models\Product $product) {
        return redirect()->back()->with('success', 'Added to wishlist!');
    })->name('add');

    Route::delete('/remove/{product}', function (\App\Models\Product $product) {
        return redirect()->back()->with('success', 'Removed from wishlist!');
    })->name('remove');
});

// ========================================
// AUTHENTICATION ROUTES (Laravel Breeze)
// ========================================

require __DIR__ . '/auth.php';

// ========================================
// FALLBACK ROUTE
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
