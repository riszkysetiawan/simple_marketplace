<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Notifications\NewOrderCreated;
use App\Notifications\OrderStatusUpdated;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    /**
     * Store new transaction (checkout)
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'nullable|in:cash,transfer,ewallet,credit_card',
            'shipping_address' => 'required|string',
            'phone' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $totalAmount = 0;
            $itemsData = [];

            // Validate stock and calculate total
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock for {$product->name}.Available: {$product->stock}"
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
                'user_id' => auth()->id(),
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_method' => $request->payment_method ??  'transfer',
                'shipping_address' => $request->shipping_address,
                'phone' => $request->phone,
                'notes' => $request->notes,
            ]);

            // Create transaction items
            foreach ($itemsData as $itemData) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $itemData['product']->id,
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price'],
                    'subtotal' => $itemData['subtotal'],
                ]);

                // Update stock
                $itemData['product']->decrement('stock', $itemData['quantity']);
            }

            // Load relationships for email
            $transaction->load(['items.product', 'user']);

            // ✅✅✅ SEND EMAIL NOTIFICATION ✅✅✅
            try {
                Log::info('Attempting to send order email', [
                    'order_number' => $transaction->order_number,
                    'customer_email' => $transaction->user->email
                ]);

                // Send to customer
                $transaction->user->notify(new NewOrderCreated($transaction));
                Log::info('✅ Customer email sent successfully');

                // Send to admins
                $adminUsers = \App\Models\User::whereHas('roles', function ($query) {
                    $query->where('name', 'super_admin');
                })->get();

                if ($adminUsers->count() > 0) {
                    Notification::send($adminUsers, new NewOrderCreated($transaction));
                    Log::info('✅ Admin emails sent successfully', [
                        'admin_count' => $adminUsers->count()
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('❌ Failed to send order email', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                // Don't fail transaction if email fails
            }

            DB::commit();

            // Clear cart session
            session()->forget('cart');

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully!  Check your email for details.',
                'data' => $transaction
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction store error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create order',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }
    /**
     * Get user's transactions
     */
    /**
     * Get user's transactions
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['items.product'])
            ->where('user_id', auth()->id())
            ->latest();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                    ->orWhereHas('items.product', function ($q2) use ($request) {
                        $q2->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        $transactions = $query->paginate(10);

        // If AJAX request
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $transactions
            ]);
        }

        // Web request
        return view('customer.transactions', compact('transactions'));
    }

    /**
     * Show single transaction
     */
    public function show(Transaction $transaction)
    {
        // Check ownership or admin
        $user = auth()->user();
        $isOwner = $transaction->user_id === $user->id;
        $isAdmin = $user->hasRole('super_admin');

        if (!$isOwner && ! $isAdmin) {
            abort(403, 'Unauthorized');
        }

        $transaction->load(['items.product', 'user']);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $transaction
            ]);
        }

        return view('customer.transaction-detail', compact('transaction'));
    }
    /**
     * Show invoice (HTML)
     */
    public function showInvoice(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        return view('customer.transactions.invoice', compact('transaction'));
    }

    /**
     * Download invoice (PDF)
     */
    /**
     * Download invoice (PDF)
     */
    public function downloadInvoice(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $pdf = PDF::loadView('invoices.transaction', compact('transaction'));

        // Set paper size and margins
        $pdf->setPaper('A4');
        $pdf->setOption('margin-top', 10);
        $pdf->setOption('margin-bottom', 10);
        $pdf->setOption('margin-left', 10);
        $pdf->setOption('margin-right', 10);

        // Set DPI for better quality
        $pdf->setOption('dpi', 300);

        // Enable images
        $pdf->setOption('enable_local_file_access', true);

        return $pdf->download("Invoice-{$transaction->order_number}.pdf");
    }


    /**
     * Print order
     */
    public function printOrder(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        return view('customer.transactions.print', compact('transaction'));
    }

    /**
     * Confirm payment
     */
    public function confirmPayment(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($transaction->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending transactions can be confirmed'
            ], 400);
        }

        try {
            $oldStatus = $transaction->status;

            $transaction->update([
                'status' => 'paid',
                'paid_at' => now()
            ]);

            $transaction->load(['items.product', 'user']);

            try {
                $transaction->user->notify(new OrderStatusUpdated($transaction, $oldStatus));
            } catch (\Exception $e) {
                Log::error('Failed to send payment email: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment confirmed successfully',
                'data' => $transaction
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to confirm payment',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }

    /**
     * Cancel transaction
     */
    public function cancel(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
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

            // Restore stock
            foreach ($transaction->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            $transaction->load(['items.product', 'user']);

            try {
                $transaction->user->notify(new OrderStatusUpdated($transaction, $oldStatus));
            } catch (\Exception $e) {
                Log::error('Failed to send cancel email: ' . $e->getMessage());
            }

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
                'error' => config('app.debug') ?  $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }

    /**
     * Update transaction (Admin only)
     */
    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'sometimes|in:pending,paid,processing,shipped,completed,cancelled',
            'payment_method' => 'sometimes|in:cash,transfer,ewallet,credit_card',
            'shipping_address' => 'sometimes|string',
            'phone' => 'sometimes|string|max:20',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $oldStatus = $transaction->status;
            $updateData = $request->only(['status', 'payment_method', 'shipping_address', 'phone', 'notes']);

            if (isset($request->status)) {
                if ($request->status === 'paid' && !$transaction->paid_at) {
                    $updateData['paid_at'] = now();
                }
                if ($request->status === 'shipped' && !$transaction->shipped_at) {
                    $updateData['shipped_at'] = now();
                }
                if ($request->status === 'completed' && !$transaction->completed_at) {
                    $updateData['completed_at'] = now();
                }
            }

            $transaction->update($updateData);

            // If cancelled, restore stock
            if (isset($request->status) && $request->status === 'cancelled' && $oldStatus !== 'cancelled') {
                foreach ($transaction->items as $item) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            $transaction->load(['items.product', 'user']);

            // Send email if status changed
            if (isset($request->status) && $oldStatus !== $request->status) {
                try {
                    $transaction->user->notify(new OrderStatusUpdated($transaction, $oldStatus));
                } catch (\Exception $e) {
                    Log::error('Failed to send update email: ' . $e->getMessage());
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaction updated successfully',
                'data' => $transaction
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update transaction',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }

    /**
     * Statistics (Admin only)
     */
    public function statistics()
    {
        $stats = [
            'total_transactions' => Transaction::count(),
            'pending' => Transaction::where('status', 'pending')->count(),
            'paid' => Transaction::where('status', 'paid')->count(),
            'processing' => Transaction::where('status', 'processing')->count(),
            'shipped' => Transaction::where('status', 'shipped')->count(),
            'completed' => Transaction::where('status', 'completed')->count(),
            'cancelled' => Transaction::where('status', 'cancelled')->count(),
            'total_revenue' => Transaction::whereIn('status', ['completed', 'shipped'])->sum('total_amount'),
            'pending_revenue' => Transaction::where('status', 'pending')->sum('total_amount'),
            'today_orders' => Transaction::whereDate('created_at', today())->count(),
            'today_revenue' => Transaction::whereDate('created_at', today())
                ->whereIn('status', ['completed', 'shipped'])
                ->sum('total_amount'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
