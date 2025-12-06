@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="page-title">
                        <i class="bi bi-bag-check me-2"></i> My Orders
                    </h1>
                    <p class="text-muted mb-0">Track and manage your orders</p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('shop.index') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i> Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-3 mb-4">
                <div class="filter-card">
                    <h5 class="filter-title">
                        <i class="bi bi-funnel me-2"></i> Filter Orders
                    </h5>

                    <!-- Status Filter -->
                    <div class="filter-group">
                        <label class="filter-label">Order Status</label>
                        <div class="filter-options">
                            <a href="{{ route('transactions.index') }}"
                                class="filter-option {{ !request('status') ? 'active' : '' }}">
                                <i class="bi bi-circle-fill"></i>
                                <span>All Orders</span>
                                <span class="badge">{{ $transactions->total() }}</span>
                            </a>
                            <a href="{{ route('transactions.index', ['status' => 'pending']) }}"
                                class="filter-option {{ request('status') == 'pending' ? 'active' : '' }}">
                                <i class="bi bi-clock-history text-warning"></i>
                                <span>Pending</span>
                            </a>
                            <a href="{{ route('transactions.index', ['status' => 'paid']) }}"
                                class="filter-option {{ request('status') == 'paid' ? 'active' : '' }}">
                                <i class="bi bi-check-circle text-info"></i>
                                <span>Paid</span>
                            </a>
                            <a href="{{ route('transactions.index', ['status' => 'processing']) }}"
                                class="filter-option {{ request('status') == 'processing' ? 'active' : '' }}">
                                <i class="bi bi-arrow-repeat text-primary"></i>
                                <span>Processing</span>
                            </a>
                            <a href="{{ route('transactions.index', ['status' => 'shipped']) }}"
                                class="filter-option {{ request('status') == 'shipped' ? 'active' : '' }}">
                                <i class="bi bi-truck text-info"></i>
                                <span>Shipped</span>
                            </a>
                            <a href="{{ route('transactions.index', ['status' => 'completed']) }}"
                                class="filter-option {{ request('status') == 'completed' ? 'active' : '' }}">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span>Completed</span>
                            </a>
                            <a href="{{ route('transactions.index', ['status' => 'cancelled']) }}"
                                class="filter-option {{ request('status') == 'cancelled' ? 'active' : '' }}">
                                <i class="bi bi-x-circle text-danger"></i>
                                <span>Cancelled</span>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="filter-group">
                        <label class="filter-label">Quick Stats</label>
                        <div class="stats-list">
                            <div class="stat-item-mini">
                                <i class="bi bi-bag-check text-primary"></i>
                                <div>
                                    <h6>{{ auth()->user()->transactions()->count() }}</h6>
                                    <p>Total Orders</p>
                                </div>
                            </div>
                            <div class="stat-item-mini">
                                <i class="bi bi-wallet2 text-success"></i>
                                <div>
                                    <h6>Rp
                                        {{ number_format(auth()->user()->transactions()->whereIn('status', ['completed', 'shipped'])->sum('total_amount'),0,',','.') }}
                                    </h6>
                                    <p>Total Spent</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders List -->
            <div class="col-lg-9">
                @if ($transactions->count() > 0)
                    <!-- Orders -->
                    <div class="orders-list">
                        @foreach ($transactions as $transaction)
                            <div class="order-card">
                                <!-- Order Header -->
                                <div class="order-header">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <h5 class="order-number">
                                                <i class="bi bi-receipt me-2"></i>
                                                {{ $transaction->order_number }}
                                            </h5>
                                            <p class="order-date">
                                                <i class="bi bi-calendar3 me-1"></i>
                                                {{ $transaction->created_at->format('d M Y, H:i') }}
                                            </p>
                                        </div>
                                        <div class="col-md-6 text-md-end mt-2 mt-md-0">
                                            <span class="status-badge status-{{ $transaction->status }}">
                                                @switch($transaction->status)
                                                    @case('pending')
                                                        <i class="bi bi-clock-history me-1"></i> Pending Payment
                                                    @break

                                                    @case('paid')
                                                        <i class="bi bi-check-circle me-1"></i> Paid
                                                    @break

                                                    @case('processing')
                                                        <i class="bi bi-arrow-repeat me-1"></i> Processing
                                                    @break

                                                    @case('shipped')
                                                        <i class="bi bi-truck me-1"></i> Shipped
                                                    @break

                                                    @case('completed')
                                                        <i class="bi bi-check-circle-fill me-1"></i> Completed
                                                    @break

                                                    @case('cancelled')
                                                        <i class="bi bi-x-circle me-1"></i> Cancelled
                                                    @break
                                                @endswitch
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Order Items -->
                                <div class="order-items">
                                    @foreach ($transaction->items->take(3) as $item)
                                        <div class="order-item">
                                            @php
                                                $imageUrl = $item->product->image;
                                                if ($item->product->image) {
                                                    if (str_starts_with($item->product->image, 'http')) {
                                                        $imageUrl = $item->product->image;
                                                    } else {
                                                        $imageUrl = Storage::url($item->product->image);
                                                    }
                                                } else {
                                                    $imageUrl = 'https://via.placeholder.com/100';
                                                }
                                            @endphp

                                            <img src="{{ $imageUrl }}" alt="{{ $item->product->name }}"
                                                onerror="this.src='https://via.placeholder.com/100'">

                                            <div class="item-info">
                                                <h6>{{ Str::limit($item->product->name, 40) }}</h6>
                                                <p>Qty: {{ $item->quantity }} × Rp
                                                    {{ number_format($item->price, 0, ',', '.') }}</p>
                                            </div>

                                            <div class="item-price">
                                                <h6>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</h6>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if ($transaction->items->count() > 3)
                                        <div class="more-items">
                                            <i class="bi bi-three-dots"></i>
                                            +{{ $transaction->items->count() - 3 }} more items
                                        </div>
                                    @endif
                                </div>

                                <!-- Order Footer -->
                                <div class="order-footer">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="order-total">
                                                <span>Total Amount:</span>
                                                <h5>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                            <div class="order-actions">
                                                <a href="{{ route('transactions.show', $transaction->id) }}"
                                                    class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-eye me-1"></i> View Details
                                                </a>

                                                @if ($transaction->status == 'pending')
                                                    <button class="btn btn-success btn-sm"
                                                        onclick="confirmPayment({{ $transaction->id }})">
                                                        <i class="bi bi-check-circle me-1"></i> Confirm Payment
                                                    </button>
                                                    <button class="btn btn-danger btn-sm"
                                                        onclick="cancelOrder({{ $transaction->id }})">
                                                        <i class="bi bi-x-circle me-1"></i> Cancel
                                                    </button>
                                                @endif

                                                @if ($transaction->status == 'completed')
                                                    <button class="btn btn-primary btn-sm">
                                                        <i class="bi bi-star me-1"></i> Review
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if ($transactions->hasPages())
                        <div class="mt-4">
                            {{ $transactions->links() }}
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bi bi-inbox"></i>
                        </div>
                        <h3>No Orders Found</h3>
                        <p class="text-muted">
                            @if (request('status'))
                                No orders with status "{{ request('status') }}"
                            @else
                                You haven't placed any orders yet
                            @endif
                        </p>
                        <a href="{{ route('shop.index') }}" class="btn btn-primary btn-lg mt-3">
                            <i class="bi bi-shop me-2"></i> Start Shopping
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 0;
            color: white;
            margin-bottom: 0;
        }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            margin: 0;
        }

        /* Filter Card */
        .filter-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 100px;
        }

        .filter-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #1f2937;
        }

        .filter-group {
            margin-bottom: 24px;
        }

        .filter-label {
            font-size: 13px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            display: block;
        }

        .filter-options {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .filter-option {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 10px;
            color: #4b5563;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .filter-option i {
            font-size: 16px;
        }

        .filter-option span:first-of-type {
            flex: 1;
        }

        .filter-option .badge {
            background: #e5e7eb;
            color: #4b5563;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
        }

        .filter-option:hover {
            background: #f3f4f6;
            padding-left: 20px;
        }

        .filter-option.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .filter-option.active .badge {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }

        /* Stats List */
        .stats-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .stat-item-mini {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #f9fafb;
            border-radius: 10px;
        }

        .stat-item-mini i {
            font-size: 24px;
        }

        .stat-item-mini h6 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
        }

        .stat-item-mini p {
            margin: 0;
            font-size: 12px;
            color: #6b7280;
        }

        /* Order Card */
        .orders-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .order-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .order-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .order-header {
            padding: 20px 24px;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }

        .order-number {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin: 0 0 8px;
        }

        .order-date {
            margin: 0;
            font-size: 14px;
            color: #6b7280;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-paid {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-processing {
            background: #e0e7ff;
            color: #3730a3;
        }

        .status-shipped {
            background: #dbeafe;
            color: #1e3a8a;
        }

        .status-completed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Order Items */
        .order-items {
            padding: 20px 24px;
        }

        .order-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-item img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .item-info {
            flex: 1;
        }

        .item-info h6 {
            margin: 0 0 4px;
            font-size: 15px;
            font-weight: 600;
            color: #1f2937;
        }

        .item-info p {
            margin: 0;
            font-size: 13px;
            color: #6b7280;
        }

        .item-price h6 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #667eea;
        }

        .more-items {
            text-align: center;
            padding: 12px;
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
        }

        /* Order Footer */
        .order-footer {
            padding: 20px 24px;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
        }

        .order-total {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .order-total span {
            font-size: 14px;
            color: #6b7280;
        }

        .order-total h5 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            color: #667eea;
        }

        .order-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        /* Empty State */
        .empty-state {
            background: white;
            border-radius: 16px;
            padding: 60px 40px;
            text-align: center;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }

        .empty-icon {
            font-size: 80px;
            color: #d1d5db;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 12px;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .filter-card {
                position: relative;
                top: 0;
            }

            .order-actions {
                justify-content: flex-start;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ========================================
        // CONFIRM PAYMENT
        // ========================================
        async function confirmPayment(transactionId) {
            const result = await Swal.fire({
                title: 'Confirm Payment',
                html: `
                <div class="text-start">
                    <p class="mb-3">Have you completed the payment for this order?</p>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Important:</strong> Only confirm if you have made the payment.
                    </div>
                    <p class="text-muted small">Your order will be processed once payment is verified.</p>
                </div>
            `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-check-circle me-2"></i> Yes, I\'ve Paid',
                cancelButtonText: '<i class="bi bi-x-circle me-2"></i> Not Yet',
                reverseButtons: true
            });

            if (!result.isConfirmed) return;

            // Show loading
            Swal.fire({
                title: 'Processing...',
                html: '<div class="spinner-border text-success" role="status"></div><p class="mt-3 text-muted">Please wait while we confirm your payment</p>',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false
            });

            try {
                const response = await fetch(`/transactions/${transactionId}/confirm-payment`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Payment Confirmed! ',
                        html: `
                        <p>Your payment has been confirmed successfully.</p>
                        <p class="text-muted small">We will process your order shortly.</p>
                    `,
                        confirmButtonColor: '#667eea',
                        timer: 3000,
                        timerProgressBar: true
                    });

                    // Reload page
                    location.reload();
                } else {
                    throw new Error(data.message || 'Failed to confirm payment');
                }
            } catch (error) {
                console.error('Confirm payment error:', error);

                Swal.fire({
                    icon: 'error',
                    title: 'Confirmation Failed',
                    html: `<p>${error.message}</p>`,
                    confirmButtonColor: '#667eea'
                });
            }
        }

        // ========================================
        // CANCEL ORDER
        // ========================================
        async function cancelOrder(transactionId) {
            // Step 1: Initial warning
            const warningResult = await Swal.fire({
                title: 'Cancel Order? ',
                html: `
                <div class="text-start">
                    <p class="mb-3">Are you sure you want to cancel this order?</p>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Note:</strong> This action cannot be undone.
                    </div>
                    <p class="text-muted small">The items will be returned to stock.</p>
                </div>
            `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-trash me-2"></i> Yes, Cancel Order',
                cancelButtonText: '<i class="bi bi-x-circle me-2"></i> Keep Order',
                reverseButtons: true
            });

            if (!warningResult.isConfirmed) return;

            // Step 2: Ask for reason
            const {
                value: reason
            } = await Swal.fire({
                title: 'Cancellation Reason',
                html: `
                <div class="text-start">
                    <p class="mb-3">Please tell us why you're cancelling this order:</p>
                    <select id="cancelReason" class="form-select mb-3">
                        <option value="">Select a reason...</option>
                        <option value="changed_mind">Changed my mind</option>
                        <option value="found_better_price">Found better price elsewhere</option>
                        <option value="ordered_by_mistake">Ordered by mistake</option>
                        <option value="delivery_too_long">Delivery takes too long</option>
                        <option value="other">Other reason</option>
                    </select>
                    <textarea id="cancelNote" class="form-control" rows="3" placeholder="Additional notes (optional)"></textarea>
                </div>
            `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Submit & Cancel Order',
                cancelButtonText: 'Back',
                preConfirm: () => {
                    const reason = document.getElementById('cancelReason').value;
                    const note = document.getElementById('cancelNote').value;

                    if (!reason) {
                        Swal.showValidationMessage('Please select a reason');
                        return false;
                    }

                    return {
                        reason,
                        note
                    };
                }
            });

            if (!reason) return;

            // Show loading
            Swal.fire({
                title: 'Cancelling Order...',
                html: '<div class="spinner-border text-danger" role="status"></div><p class="mt-3 text-muted">Please wait</p>',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false
            });

            try {
                const response = await fetch(`/transactions/${transactionId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        reason: reason.reason,
                        note: reason.note
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Order Cancelled',
                        html: `
                        <p>Your order has been cancelled successfully.</p>
                        <p class="text-muted small">Items have been returned to stock.</p>
                    `,
                        confirmButtonColor: '#667eea',
                        timer: 3000,
                        timerProgressBar: true
                    });

                    // Reload page
                    location.reload();
                } else {
                    throw new Error(data.message || 'Failed to cancel order');
                }
            } catch (error) {
                console.error('Cancel order error:', error);

                Swal.fire({
                    icon: 'error',
                    title: 'Cancellation Failed',
                    html: `<p>${error.message}</p>`,
                    confirmButtonColor: '#667eea'
                });
            }
        }

        // ========================================
        // VIEW ORDER DETAILS (Modal)
        // ========================================
        async function viewOrderDetails(transactionId) {
            // Show loading
            Swal.fire({
                title: 'Loading Order Details...',
                html: '<div class="spinner-border text-primary" role="status"></div>',
                allowOutsideClick: false,
                showConfirmButton: false
            });

            try {
                const response = await fetch(`/transactions/${transactionId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    const transaction = data.data;

                    let itemsHtml = '';
                    transaction.items.forEach(item => {
                        itemsHtml += `
                        <div class="d-flex align-items-center gap-3 mb-3 p-2 bg-light rounded">
                            <img src="${item.product.image || 'https://via.placeholder.com/60'}" 
                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                            <div class="flex-fill">
                                <h6 class="mb-1">${item.product.name}</h6>
                                <small class="text-muted">Qty: ${item.quantity} × Rp ${formatNumber(item.price)}</small>
                            </div>
                            <strong class="text-primary">Rp ${formatNumber(item.subtotal)}</strong>
                        </div>
                    `;
                    });

                    Swal.fire({
                        title: `Order ${transaction.order_number}`,
                        html: `
                        <div class="text-start">
                            <div class="mb-3">
                                <span class="badge bg-primary">${transaction.status}</span>
                                <small class="text-muted ms-2">${new Date(transaction.created_at).toLocaleString()}</small>
                            </div>
                            
                            <h6 class="border-bottom pb-2">Order Items</h6>
                            ${itemsHtml}
                            
                            <h6 class="border-bottom pb-2 mt-4">Shipping Information</h6>
                            <p class="mb-2"><strong>Address:</strong> ${transaction.shipping_address}</p>
                            <p class="mb-2"><strong>Phone:</strong> ${transaction.phone}</p>
                            ${transaction.notes ? `<p class="mb-2"><strong>Notes:</strong> ${transaction.notes}</p>` : ''}
                            
                            <h6 class="border-bottom pb-2 mt-4">Payment Summary</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <strong>Rp ${formatNumber(transaction.total_amount)}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <strong class="text-success">FREE</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h5>Total:</h5>
                                <h5 class="text-primary">Rp ${formatNumber(transaction.total_amount)}</h5>
                            </div>
                        </div>
                    `,
                        width: 700,
                        confirmButtonColor: '#667eea',
                        confirmButtonText: 'Close'
                    });
                } else {
                    throw new Error(data.message || 'Failed to load order details');
                }
            } catch (error) {
                console.error('View order error:', error);

                Swal.fire({
                    icon: 'error',
                    title: 'Failed to Load',
                    html: `<p>${error.message}</p>`,
                    confirmButtonColor: '#667eea'
                });
            }
        }

        // ========================================
        // TRACK ORDER
        // ========================================
        async function trackOrder(transactionId) {
            Swal.fire({
                title: 'Order Tracking',
                html: `
                <div class="text-start">
                    <div class="timeline">
                        <div class="timeline-item active">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Order Placed</h6>
                                <small class="text-muted">Your order has been received</small>
                            </div>
                        </div>
                        <div class="timeline-item active">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Payment Confirmed</h6>
                                <small class="text-muted">Payment has been verified</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Processing</h6>
                                <small class="text-muted">Your order is being prepared</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Shipped</h6>
                                <small class="text-muted">Your order is on the way</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Delivered</h6>
                                <small class="text-muted">Order has been delivered</small>
                            </div>
                        </div>
                    </div>
                </div>
            `,
                width: 600,
                confirmButtonColor: '#667eea',
                confirmButtonText: 'Close'
            });
        }

        // ========================================
        // DOWNLOAD INVOICE
        // ========================================
        async function downloadInvoice(transactionId) {
            Swal.fire({
                title: 'Generating Invoice...',
                html: '<div class="spinner-border text-primary" role="status"></div>',
                allowOutsideClick: false,
                showConfirmButton: false
            });

            try {
                // Simulate download
                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Invoice Ready!',
                        html: '<p>Your invoice is ready to download</p>',
                        confirmButtonColor: '#667eea',
                        timer: 2000
                    });

                    // Actual download would be:
                    // window.location.href = `/transactions/${transactionId}/invoice`;
                }, 1500);
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Download Failed',
                    html: `<p>${error.message}</p>`,
                    confirmButtonColor: '#667eea'
                });
            }
        }

        // ========================================
        // UTILITY FUNCTIONS
        // ========================================
        function formatNumber(num) {
            return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // ========================================
        // SESSION MESSAGES
        // ========================================
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#667eea',
                timer: 3000,
                timerProgressBar: true,
                toast: true,
                position: 'top-end',
                showConfirmButton: false
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error! ',
                text: '{{ session('error') }}',
                confirmButtonColor: '#667eea'
            });
        @endif

        // ========================================
        // TIMELINE STYLES
        // ========================================
        const timelineStyles = `
        <style>
            .timeline {
                position: relative;
                padding: 20px 0;
            }
            
            .timeline-item {
                position: relative;
                padding-left: 45px;
                padding-bottom: 30px;
            }
            
            .timeline-item:last-child {
                padding-bottom: 0;
            }
            
            .timeline-item::before {
                content: '';
                position: absolute;
                left: 11px;
                top: 24px;
                bottom: -6px;
                width: 2px;
                background: #e5e7eb;
            }
            
            .timeline-item:last-child::before {
                display: none;
            }
            
            .timeline-item.active .timeline-marker {
                background: #667eea;
                border-color: #667eea;
            }
            
            .timeline-item.active::before {
                background: #667eea;
            }
            
            .timeline-marker {
                position: absolute;
                left: 0;
                top: 0;
                width: 24px;
                height: 24px;
                border-radius: 50%;
                background: white;
                border: 3px solid #e5e7eb;
                z-index: 1;
            }
            
            .timeline-content h6 {
                margin: 0 0 4px;
                font-weight: 600;
                color: #1f2937;
            }
            
            .timeline-content small {
                display: block;
                font-size: 13px;
            }
        </style>
    `;

        // Add timeline styles to document
        document.head.insertAdjacentHTML('beforeend', timelineStyles);

        // ========================================
        // SEARCH ORDERS
        // ========================================
        let searchTimeout;
        const searchInput = document.getElementById('searchOrders');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);

                searchTimeout = setTimeout(() => {
                    const query = this.value.toLowerCase();
                    const orders = document.querySelectorAll('.order-card');

                    orders.forEach(order => {
                        const orderNumber = order.querySelector('.order-number').textContent
                            .toLowerCase();
                        const orderItems = order.querySelector('.order-items').textContent
                            .toLowerCase();

                        if (orderNumber.includes(query) || orderItems.includes(query)) {
                            order.style.display = 'block';
                        } else {
                            order.style.display = 'none';
                        }
                    });
                }, 300);
            });
        }

        // ========================================
        // PRINT ORDER
        // ========================================
        function printOrder(transactionId) {
            window.open(`/transactions/${transactionId}/print`, '_blank');
        }

        // ========================================
        // REORDER
        // ========================================
        async function reorder(transactionId) {
            const result = await Swal.fire({
                title: 'Reorder Items? ',
                html: '<p>Do you want to add these items to your cart again?</p>',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#667eea',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Add to Cart',
                cancelButtonText: 'Cancel'
            });

            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Added to Cart!',
                    text: 'Items have been added to your cart',
                    confirmButtonColor: '#667eea',
                    timer: 2000
                }).then(() => {
                    window.location.href = '{{ route('cart.index') }}';
                });
            }
        }

        console.log('✅ Transactions page loaded successfully!');
    </script>
@endpush
