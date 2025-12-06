<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Notifications\NewOrderCreated;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['items.product', 'user']);

        // Admin can see all, customer only their own
        if (! $request->user()->hasRole('super_admin')) {
            $query->where('user_id', $request->user()->id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $perPage = $request->get('per_page', 15);
        $transactions = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Transactions retrieved successfully',
            'data' => $transactions
        ]);
    }

    /**
     * Store a newly created transaction
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'nullable|in:cash,transfer,ewallet,credit_card',
            'shipping_address' => 'required|string',
            'phone' => 'required|string|max:20',
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
            $totalAmount = 0;
            $itemsData = [];

            // Validate and calculate
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock for {$product->name}"
                    ], 400);
                }

                if (! $product->is_active) {
                    return response()->json([
                        'success' => false,
                        'message' => "{$product->name} is not available"
                    ], 400);
                }

                $subtotal = $product->price * $item['quantity'];
                $totalAmount += $subtotal;

                $itemsData[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ];
            }

            // Create transaction
            $transaction = Transaction::create([
                'user_id' => $request->user()->id,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_method' => $request->payment_method ?? 'transfer',
                'shipping_address' => $request->shipping_address,
                'phone' => $request->phone,
                'notes' => $request->notes,
            ]);

            // Create items
            foreach ($itemsData as $itemData) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $itemData['product']->id,
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price'],
                    'subtotal' => $itemData['subtotal'],
                ]);

                $itemData['product']->decrement('stock', $itemData['quantity']);
            }

            $transaction->load(['items.product', 'user']);

            // ✅ Send email notification
            try {
                $transaction->user->notify(new NewOrderCreated($transaction));

                $adminUsers = \App\Models\User::whereHas('roles', function ($q) {
                    $q->where('name', 'super_admin');
                })->get();

                if ($adminUsers->count() > 0) {
                    Notification::send($adminUsers, new NewOrderCreated($transaction));
                }
            } catch (\Exception $e) {
                Log::error('Email failed: ' . $e->getMessage());
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully. Email sent! ',
                'data' => $transaction
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified transaction
     */
    public function show(Request $request, $id)
    {
        $transaction = Transaction::with(['items.product', 'user'])->find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ], 404);
        }

        // Check ownership
        if ($transaction->user_id !== $request->user()->id && !$request->user()->hasRole('super_admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaction retrieved successfully',
            'data' => $transaction
        ]);
    }

    /**
     * Update transaction status (Admin only)
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
            'status' => 'required|in:pending,paid,processing,shipped,completed,cancelled',
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
            $updateData = ['status' => $request->status];

            if ($request->status === 'paid') $updateData['paid_at'] = now();
            if ($request->status === 'shipped') $updateData['shipped_at'] = now();
            if ($request->status === 'completed') $updateData['completed_at'] = now();

            $transaction->update($updateData);

            // Restore stock if cancelled
            if ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
                foreach ($transaction->items as $item) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            $transaction->load(['items.product', 'user']);

            // ✅ Send status update email
            try {
                $transaction->user->notify(new OrderStatusUpdated($transaction, $oldStatus));
            } catch (\Exception $e) {
                Log::error('Email failed: ' . $e->getMessage());
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaction updated successfully. Email sent!',
                'data' => $transaction
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
     * Cancel transaction
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

        if ($transaction->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($transaction->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending transactions can be cancelled'
            ], 400);
        }

        DB::beginTransaction();

        try {
            $oldStatus = $transaction->status;
            $transaction->update(['status' => 'cancelled']);

            foreach ($transaction->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            $transaction->load(['items.product', 'user']);

            // ✅ Send cancellation email
            try {
                $transaction->user->notify(new OrderStatusUpdated($transaction, $oldStatus));
            } catch (\Exception $e) {
                Log::error('Email failed: ' . $e->getMessage());
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaction cancelled successfully.Email sent!',
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
}
