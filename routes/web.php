<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\Auth\CustomLogoutController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishlistController;
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
Route::prefix('api')->middleware('api')->group(function () {
    // Public auth routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/google/login', [AuthController::class, 'googleLogin']);
    Route::post('/facebook/login', [AuthController::class, 'facebookLogin']);

    // Protected routes (require token)
    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/sso/login', [AuthController::class, 'ssoLogin']);
    });

    // SSO callback (public)
    Route::post('/sso/callback', [AuthController::class, 'ssoCallback']);
});


// Web Routes - Social Login

// Social Login Routes
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
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::post('/update', [ProfileController::class, 'updateProfile'])->name('update');
    Route::post('/avatar', [ProfileController::class, 'updateAvatar'])->name('updateAvatar');
    Route::post('/password', [ProfileController::class, 'updatePassword'])->name('updatePassword');
    Route::post('/delete', [ProfileController::class, 'deleteAccount'])->name('delete');
});

// ========================================
// ✅ SHOP ROUTES (PUBLIC)
// ========================================

Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('/', [ShopController::class, 'index'])->name('index');
    Route::get('/products/{product:slug}', [ShopController::class, 'show'])->name('products.show');
    Route::get('/category/{category:slug}', [ShopController::class, 'category'])->name('category');
    Route::get('/search', [ShopController::class, 'search'])->name('search');
    Route::get('/featured', [ShopController::class, 'featured'])->name('featured');
});

// ========================================
// CART ROUTES
// ========================================
Route::middleware('auth')->prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::get('/get', [CartController::class, 'getCart'])->name('get');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
    Route::patch('/update/{product}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{product}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
});
Route::middleware('auth')->prefix('transactions')->name('transactions.')->group(function () {
    Route::post('/', [TransactionController::class, 'store'])->name('store');
    Route::get('/', [TransactionController::class, 'index'])->name('index');
    Route::get('/{transaction}', [TransactionController::class, 'show'])->name('show');
    Route::post('/{transaction}/confirm-payment', [TransactionController::class, 'confirmPayment'])->name('confirm-payment');
    Route::post('/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('cancel');
    Route::get('/transactions/{transaction}/invoice-html', [TransactionController::class, 'showInvoice'])->name('invoice-html');
    Route::get('/{transaction}/invoice', [TransactionController::class, 'downloadInvoice'])->name('invoice');
    Route::get('/{transaction}/print', [TransactionController::class, 'printOrder'])->name('print');
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
