<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| All routes are prefixed with /api automatically.
|
*/

// ========================================
// API DOCUMENTATION
// ========================================
Route::get('/docs', function () {
    return response()->json([
        'message' => 'Simple Marketplace API v1',
        'version' => '1.0.0',
        'documentation' => url('/api/documentation'),
        'base_url' => url('/api/v1'),
        'endpoints' => [
            'auth' => [
                'POST /api/v1/auth/register' => 'Register new user',
                'POST /api/v1/auth/login' => 'Login user',
                'POST /api/v1/auth/google/login' => 'Login with Google',
                'POST /api/v1/auth/facebook/login' => 'Login with Facebook',
                'POST /api/v1/auth/logout' => 'Logout user (requires auth)',
                'GET /api/v1/auth/me' => 'Get authenticated user (requires auth)',
                'GET /api/v1/auth/sso' => 'Generate SSO token (requires auth)',
                'POST /api/v1/auth/sso/callback' => 'SSO callback',
            ],
            'products' => [
                'GET /api/v1/products' => 'Get all products',
                'GET /api/v1/products/{id}' => 'Get product by ID',
                'POST /api/v1/products' => 'Create product (admin only)',
                'PUT /api/v1/products/{id}' => 'Update product (admin only)',
                'DELETE /api/v1/products/{id}' => 'Delete product (admin only)',
            ],
            'transactions' => [
                'GET /api/v1/transactions' => 'Get all transactions (requires auth)',
                'GET /api/v1/transactions/{id}' => 'Get transaction by ID (requires auth)',
                'POST /api/v1/transactions' => 'Create transaction (requires auth)',
                'PUT /api/v1/transactions/{id}' => 'Update transaction (admin only)',
                'POST /api/v1/transactions/{id}/cancel' => 'Cancel transaction (requires auth)',
                'GET /api/v1/transactions/statistics' => 'Get statistics (admin only)',
            ],
        ],
        'authentication' => [
            'type' => 'Bearer Token',
            'header' => 'Authorization: Bearer {token}',
            'note' => 'Include the token in the Authorization header for protected routes'
        ]
    ]);
});

// ========================================
// API VERSION 1 - PUBLIC ROUTES
// ========================================
Route::prefix('v1')->group(function () {

    // ========================================
    // AUTHENTICATION - PUBLIC
    // ========================================
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])
            ->name('api.auth.register');

        Route::post('/login', [AuthController::class, 'login'])
            ->name('api.auth.login');

        Route::post('/google/login', [AuthController::class, 'googleLogin'])
            ->name('api.auth.google');

        Route::post('/facebook/login', [AuthController::class, 'facebookLogin'])
            ->name('api.auth.facebook');

        Route::post('/sso/callback', [AuthController::class, 'ssoCallback'])
            ->name('api.auth.sso.callback');
    });

    // ========================================
    // PRODUCTS - PUBLIC (READ ONLY)
    // ========================================
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])
            ->name('api.products.index');

        Route::get('/{id}', [ProductController::class, 'show'])
            ->name('api.products.show');
    });
});

// ========================================
// API VERSION 1 - PROTECTED ROUTES (REQUIRES AUTHENTICATION)
// ========================================
Route::prefix('v1')->middleware('auth:api')->group(function () {

    // ========================================
    // AUTHENTICATION - PROTECTED
    // ========================================
    Route::prefix('auth')->group(function () {
        Route::get('/me', [AuthController::class, 'me'])
            ->name('api.auth.me');

        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('api.auth.logout');

        Route::get('/sso', [AuthController::class, 'ssoLogin'])
            ->name('api.auth.sso.login');
    });

    // ========================================
    // TRANSACTIONS - AUTHENTICATED USERS
    // ========================================
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])
            ->name('api.transactions.index');

        Route::get('/{id}', [TransactionController::class, 'show'])
            ->name('api.transactions.show');

        Route::post('/', [TransactionController::class, 'store'])
            ->name('api.transactions.store');

        Route::post('/{id}/cancel', [TransactionController::class, 'cancel'])
            ->name('api.transactions.cancel');
    });

    // ========================================
    // ADMIN ONLY ROUTES
    // ========================================
    Route::middleware(['role:super_admin'])->group(function () {

        // PRODUCT MANAGEMENT
        Route::prefix('products')->group(function () {
            Route::post('/', [ProductController::class, 'store'])
                ->name('api.products.store');

            Route::put('/{id}', [ProductController::class, 'update'])
                ->name('api.products.update');

            Route::delete('/{id}', [ProductController::class, 'destroy'])
                ->name('api.products.destroy');
        });

        // TRANSACTION MANAGEMENT
        Route::prefix('transactions')->group(function () {
            Route::put('/{id}', [TransactionController::class, 'update'])
                ->name('api.transactions.update');

            Route::get('/statistics/all', [TransactionController::class, 'statistics'])
                ->name('api.transactions.statistics');
        });
    });
});

// ========================================
// FALLBACK ROUTE - 404 NOT FOUND
// ========================================
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'API endpoint not found',
        'available_endpoints' => url('/api/docs')
    ], 404);
});
