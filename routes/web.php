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
|
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
|
*/

// ========================================
// PUBLIC ROUTES
// ========================================

// Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::get('/home', function () {
    return redirect('/');
})->name('home.redirect');
// ========================================
// API ROUTES (untuk frontend yang menggunakan API via web.php)
// ========================================
Route::prefix('api')->group(function () {
    // Public auth routes
    Route::post('/register', [AuthController::class, 'register'])
        ->name('api.web.register');
    Route::post('/login', [AuthController::class, 'login'])
        ->name('api.web.login');
    Route::post('/google/login', [AuthController::class, 'googleLogin'])
        ->name('api.web.google.login');
    Route::post('/facebook/login', [AuthController::class, 'facebookLogin'])
        ->name('api.web.facebook.login');
    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('api.web.logout');
        Route::get('/me', [AuthController::class, 'me'])
            ->name('api.web.me');
        Route::get('/sso/login', [AuthController::class, 'ssoLogin'])
            ->name('api.web.sso.login');
    });
    Route::post('/sso/callback', [AuthController::class, 'ssoCallback'])
        ->name('api.web.sso.callback');
});

// ========================================
// SOCIAL LOGIN ROUTES (Redirect-based)
// ========================================
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/google', [SocialLoginController::class, 'redirectToGoogle'])
        ->name('google.redirect');
    Route::get('/google/callback', [SocialLoginController::class, 'handleGoogleCallback'])
        ->name('google.callback');
    Route::get('/facebook', [SocialLoginController::class, 'redirectToFacebook'])
        ->name('facebook.redirect');
    Route::get('/facebook/callback', [SocialLoginController::class, 'handleFacebookCallback'])
        ->name('facebook.callback');
});

// ========================================
// AUTHENTICATION ROUTES (Laravel Breeze)
// ========================================
require __DIR__ . '/auth.php';
// ========================================
// DASHBOARD ROUTES
// ========================================
Route::get('/dashboard', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return redirect()->intended('/admin');
        }
        return redirect()->route('home');
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
// PROFILE ROUTES (Protected)
// ========================================
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])
        ->name('index');
    Route::post('/update', [ProfileController::class, 'updateProfile'])
        ->name('update');
    Route::post('/avatar', [ProfileController::class, 'updateAvatar'])
        ->name('updateAvatar');
    Route::post('/password', [ProfileController::class, 'updatePassword'])
        ->name('updatePassword');
    Route::delete('/delete', [ProfileController::class, 'deleteAccount'])
        ->name('delete');
});

// ========================================
// SHOP ROUTES (Public)
// ========================================
Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('/', [ShopController::class, 'index'])
        ->name('index');
    Route::get('/products/{product:slug}', [ShopController::class, 'show'])
        ->name('products.show');
    Route::get('/category/{category:slug}', [ShopController::class, 'category'])
        ->name('category');
    Route::get('/search', [ShopController::class, 'search'])
        ->name('search');
    Route::get('/featured', [ShopController::class, 'featured'])
        ->name('featured');
});

// ========================================
// CART ROUTES (Protected)
// ========================================
Route::middleware('auth')->prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])
        ->name('index');
    Route::get('/get', [CartController::class, 'getCart'])
        ->name('get');
    Route::post('/add/{product}', [CartController::class, 'add'])
        ->name('add');
    Route::patch('/update/{product}', [CartController::class, 'update'])
        ->name('update');
    Route::delete('/remove/{product}', [CartController::class, 'remove'])
        ->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])
        ->name('clear');
});

// ========================================
// TRANSACTION ROUTES (Protected)
// ========================================
Route::middleware('auth')->prefix('transactions')->name('transactions.')->group(function () {
    Route::get('/', [TransactionController::class, 'index'])
        ->name('index');
    Route::post('/', [TransactionController::class, 'store'])
        ->name('store');
    Route::get('/{transaction}', [TransactionController::class, 'show'])
        ->name('show');
    Route::post('/{transaction}/confirm-payment', [TransactionController::class, 'confirmPayment'])
        ->name('confirm-payment');
    Route::post('/{transaction}/cancel', [TransactionController::class, 'cancel'])
        ->name('cancel');
    Route::get('/{transaction}/invoice-html', [TransactionController::class, 'showInvoice'])
        ->name('invoice-html');
    Route::get('/{transaction}/invoice', [TransactionController::class, 'downloadInvoice'])
        ->name('invoice');
    Route::get('/{transaction}/print', [TransactionController::class, 'printOrder'])
        ->name('print');
});

// ========================================
// CHECKOUT ROUTES (Protected)
// ========================================
Route::middleware('auth')->prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', function () {
        return view('checkout.index');
    })->name('index');
    Route::post('/process', function () {
        return redirect()->route('checkout.success')
            ->with('success', 'Order placed successfully!');
    })->name('process');
    Route::get('/success', function () {
        return view('checkout.success');
    })->name('success');
    Route::get('/cancel', function () {
        return redirect()->route('cart.index')
            ->with('error', 'Checkout cancelled');
    })->name('cancel');
});

// ========================================
// WISHLIST ROUTES (Protected)
// ========================================
Route::middleware('auth')->prefix('wishlist')->name('wishlist.')->group(function () {
    Route::get('/', function () {
        return view('wishlist.index');
    })->name('index');
    Route::post('/add/{product}', function (\App\Models\Product $product) {
        return redirect()->back()
            ->with('success', 'Added to wishlist!');
    })->name('add');
    Route::delete('/remove/{product}', function (\App\Models\Product $product) {
        return redirect()->back()
            ->with('success', 'Removed from wishlist!');
    })->name('remove');
});

// ========================================
// FALLBACK ROUTE (404 Handler)
// ========================================
Route::fallback(function () {
    if (view()->exists('errors.404')) {
        return response()->view('errors.404', [], 404);
    }
    if (request()->expectsJson()) {
        return response()->json([
            'success' => false,
            'message' => 'Page not found',
            'error' => 'The requested resource could not be found'
        ], 404);
    }
    return response()->view('errors.404', [], 404);
});
