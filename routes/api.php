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
*/

// Public routes
Route::prefix('v1')->group(function () {

    // Authentication
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });

    // Public product routes
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
    });
});

// Protected routes (requires authentication)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    // Auth user info
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Transactions (accessible by all authenticated users)
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']);
        Route::get('/{id}', [TransactionController::class, 'show']);
        Route::post('/', [TransactionController::class, 'store']);
        Route::post('/{id}/cancel', [TransactionController::class, 'cancel']);
    });

    // Admin-only routes
    Route::middleware(['role:super_admin'])->group(function () {

        // Product management
        Route::prefix('products')->group(function () {
            Route::post('/', [ProductController::class, 'store']);
            Route::put('/{id}', [ProductController::class, 'update']);
            Route::delete('/{id}', [ProductController::class, 'destroy']);
        });

        // Transaction management
        Route::prefix('transactions')->group(function () {
            Route::put('/{id}', [TransactionController::class, 'update']);
            Route::get('/statistics/all', [TransactionController::class, 'statistics']);
        });
    });
});

// API Documentation route
Route::get('/v1/docs', function () {
    return response()->json([
        'message' => 'Simple Marketplace API v1',
        'documentation' => url('/api/documentation'),
        'endpoints' => [
            'auth' => [
                'POST /api/v1/auth/register' => 'Register new user',
                'POST /api/v1/auth/login' => 'Login user',
                'POST /api/v1/auth/logout' => 'Logout user (requires auth)',
                'GET /api/v1/auth/me' => 'Get authenticated user (requires auth)',
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
                'GET /api/v1/transactions/statistics/all' => 'Get statistics (admin only)',
            ],
        ],
    ]);
});
