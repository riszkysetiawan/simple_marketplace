<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ========================================
// PUBLIC API ROUTES
// ========================================

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::get('/products', function () {
        return response()->json(['success' => true, 'message' => 'Products (coming soon)']);
    })->name('products.index');

    Route::get('/categories', function () {
        return response()->json(['success' => true, 'message' => 'Categories (coming soon)']);
    })->name('categories.index');
});

// ========================================
// PROTECTED API ROUTES (Auth Required)
// ========================================

Route::middleware('auth:api')->prefix('v1')->name('api.v1.')->group(function () {
    Route::get('/user', [AuthController::class, 'user'])->name('user');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::patch('/profile', function () {
        return response()->json(['success' => true, 'message' => 'Profile update (coming soon)']);
    })->name('profile.update');

    Route::get('/orders', function () {
        return response()->json(['success' => true, 'message' => 'Orders (coming soon)']);
    })->name('orders.index');
});

// ========================================
// ADMIN API ROUTES (Super Admin Only)
// ========================================

Route::middleware(['auth:api', 'role:super_admin'])
    ->prefix('v1/admin')
    ->name('api.v1.admin.')
    ->group(function () {

        Route::get('/stats', function () {
            return response()->json([
                'success' => true,
                'data' => [
                    'total_products' => \App\Models\Product::count(),
                    'total_orders' => \App\Models\Transaction::count(),
                    'total_users' => \App\Models\User::count(),
                    'total_revenue' => \App\Models\Transaction::sum('total_amount'),
                ],
            ]);
        })->name('stats');

        Route::get('/products', function () {
            return response()->json(['success' => true, 'message' => 'Admin products (coming soon)']);
        })->name('products.index');

        Route::get('/categories', function () {
            return response()->json(['success' => true, 'message' => 'Admin categories (coming soon)']);
        })->name('categories.index');
    });

// ========================================
// HEALTH CHECK
// ========================================

Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is running',
        'timestamp' => now()->toISOString(),
    ]);
})->name('api.health');

// ========================================
// FALLBACK (404)
// ========================================

Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'API endpoint not found',
    ], 404);
});
