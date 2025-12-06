<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'product']);

        // Filter by user (customers can only see their own transactions)
        if ($request->user()->hasRole('customer')) {
            $query->where('user_id', $request->user()->id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $transactions = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Transactions retrieved successfully',
            'data' => $transactions
        ]);
    }

    /**
     * Display the specified transaction
     */
    public function show(Request $request, $id)
    {
        $transaction = Transaction::with(['user', 'product'])->find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ], 404);
        }

        // Customers can only view their own transactions
        if ($request->user()->hasRole('customer') && $transaction->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaction retrieved successfully',
            'data' => $transaction
        ]);
    }

    /**
     * Store a newly created transaction
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'nullable|string|in:credit_card,debit_card,paypal,bank_transfer',
            'shipping_address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $product = Product::findOrFail($request->product_id);

            // Check stock availability
            if ($product->stock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock. Available: ' . $product->stock
                ], 400);
            }

            // Check if product is active
            if (!$product->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product is not available'
                ], 400);
            }

            // Calculate total
            $total_price = $product->price * $request->quantity;

            // Create transaction
            $transaction = Transaction::create([
                'user_id' => $request->user()->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'total_price' => $total_price,
                'status' => 'pending',
                'payment_method' => $request->payment_method ??  'credit_card',
                'shipping_address' => $request->shipping_address ??  $request->user()->address,
                'notes' => $request->notes,
            ]);

            // Update product stock
            $product->decrement('stock', $request->quantity);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaction created successfully',
                'data' => $transaction->load(['user', 'product'])
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified transaction (Admin only)
     */
    public function update(Request $request, $id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|required|string|in:pending,processing,shipped,completed,cancelled',
            'payment_method' => 'sometimes|string|in:credit_card,debit_card,paypal,bank_transfer',
            'shipping_address' => 'sometimes|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $oldStatus = $transaction->status;

            $transaction->update($request->only([
                'status',
                'payment_method',
                'shipping_address',
                'notes'
            ]));

            // If transaction is cancelled, restore product stock
            if ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
                $transaction->product->increment('stock', $transaction->quantity);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaction updated successfully',
                'data' => $transaction->load(['user', 'product'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel transaction (Customer can cancel their own pending transactions)
     */
    public function cancel(Request $request, $id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ], 404);
        }

        // Check ownership for customers
        if ($request->user()->hasRole('customer') && $transaction->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        // Only pending transactions can be cancelled
        if ($transaction->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending transactions can be cancelled'
            ], 400);
        }

        DB::beginTransaction();

        try {
            $transaction->update(['status' => 'cancelled']);

            // Restore product stock
            $transaction->product->increment('stock', $transaction->quantity);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaction cancelled successfully',
                'data' => $transaction
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get transaction statistics (Admin only)
     */
    public function statistics(Request $request)
    {
        $stats = [
            'total_transactions' => Transaction::count(),
            'pending_transactions' => Transaction::where('status', 'pending')->count(),
            'completed_transactions' => Transaction::where('status', 'completed')->count(),
            'cancelled_transactions' => Transaction::where('status', 'cancelled')->count(),
            'total_revenue' => Transaction::where('status', 'completed')->sum('total_price'),
            'average_order_value' => Transaction::where('status', 'completed')->avg('total_price'),
            'recent_transactions' => Transaction::with(['user', 'product'])
                ->latest()
                ->limit(10)
                ->get(),
        ];

        return response()->json([
            'success' => true,
            'message' => 'Statistics retrieved successfully',
            'data' => $stats
        ]);
    }
}
