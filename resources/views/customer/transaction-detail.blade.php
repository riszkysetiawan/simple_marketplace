@extends('layouts.app')

@section('title', 'Order ' . $transaction->order_number)

@section('content')
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="mb-2">
                    <i class="bi bi-receipt me-2"></i> {{ $transaction->order_number }}
                </h1>
                <p class="text-muted">
                    <i class="bi bi-calendar3 me-1"></i>
                    {{ $transaction->created_at->format('d M Y, H:i') }}
                </p>
            </div>
            <div class="col-md-6 text-md-end">
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

        <div class="row">
            <!-- Order Items -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-bag me-2"></i> Order Items</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transaction->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    @php
                                                        $imageUrl = $item->product->image;
                                                        if ($item->product->image) {
                                                            if (str_starts_with($item->product->image, 'http')) {
                                                                $imageUrl = $item->product->image;
                                                            } else {
                                                                $imageUrl = Storage::url($item->product->image);
                                                            }
                                                        } else {
                                                            $imageUrl = 'https://via.placeholder.com/60';
                                                        }
                                                    @endphp
                                                    <img src="{{ $imageUrl }}" alt="{{ $item->product->name }}"
                                                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;"
                                                        onerror="this.src='https://via.placeholder.com/60'">
                                                    <div>
                                                        <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                        <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td><strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Shipping Information -->
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i> Shipping Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-muted">Shipping Address</h6>
                                <p class="mb-0">{{ $transaction->shipping_address }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted">Phone Number</h6>
                                <p class="mb-0">{{ $transaction->phone }}</p>
                            </div>
                        </div>
                        @if ($transaction->notes)
                            <hr>
                            <div>
                                <h6 class="text-muted">Notes</h6>
                                <p class="mb-0">{{ $transaction->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <!-- Payment Summary -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-calculator me-2"></i> Payment Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <strong>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <strong class="text-success">FREE</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Tax (11%):</span>
                            <strong>Rp {{ number_format($transaction->tax_amount, 0, ',', '.') }}</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0">Total:</h5>
                            <h5 class="mb-0 text-primary">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                            </h5>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-credit-card me-2"></i> Payment Method</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">
                            @switch($transaction->payment_method)
                                @case('transfer')
                                    <i class="bi bi-bank me-2"></i> Bank Transfer
                                @break

                                @case('ewallet')
                                    <i class="bi bi-wallet2 me-2"></i> E-Wallet
                                @break

                                @case('credit_card')
                                    <i class="bi bi-credit-card me-2"></i> Credit Card
                                @break

                                @case('cash')
                                    <i class="bi bi-cash-coin me-2"></i> Cash on Delivery
                                @break
                            @endswitch
                        </p>
                        <small class="text-muted">
                            Status: <strong>{{ ucfirst($transaction->payment_status) }}</strong>
                        </small>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-gear me-2"></i> Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('transactions.invoice', $transaction->id) }}"
                                class="btn btn-outline-primary">
                                <i class="bi bi-download me-2"></i> Download Invoice
                            </a>
                            {{-- 
                            <a href="{{ route('transactions.print', $transaction->id) }}" target="_blank"
                                class="btn btn-outline-secondary">
                                <i class="bi bi-printer me-2"></i> Print Order
                            </a> --}}

                            @if ($transaction->isPending())
                                <button class="btn btn-success" onclick="confirmPayment({{ $transaction->id }})">
                                    <i class="bi bi-check-circle me-2"></i> Confirm Payment
                                </button>

                                <button class="btn btn-danger" onclick="cancelOrder({{ $transaction->id }})">
                                    <i class="bi bi-x-circle me-2"></i> Cancel Order
                                </button>
                            @endif

                            <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i> Back to Orders
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .status-badge {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 14px;
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
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        async function confirmPayment(transactionId) {
            const result = await Swal.fire({
                title: 'Confirm Payment',
                html: '<p>Have you completed the payment for this order?</p>',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Yes, I\'ve Paid',
                cancelButtonText: 'Cancel'
            });

            if (!result.isConfirmed) return;

            Swal.fire({
                title: 'Processing...',
                html: '<div class="spinner-border text-success" role="status"></div>',
                allowOutsideClick: false,
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
                        title: 'Payment Confirmed!',
                        confirmButtonColor: '#667eea',
                        timer: 2000
                    });
                    location.reload();
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: error.message,
                    confirmButtonColor: '#667eea'
                });
            }
        }

        async function cancelOrder(transactionId) {
            const result = await Swal.fire({
                title: 'Cancel Order?',
                html: '<p>Are you sure? This action cannot be undone.</p>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Yes, Cancel Order',
                cancelButtonText: 'Keep Order'
            });

            if (!result.isConfirmed) return;

            const {
                value: reason
            } = await Swal.fire({
                title: 'Cancellation Reason',
                html: `
                    <select id="cancelReason" class="form-select mb-3">
                        <option value="">Select a reason...</option>
                        <option value="changed_mind">Changed my mind</option>
                        <option value="found_better_price">Found better price</option>
                        <option value="ordered_by_mistake">Ordered by mistake</option>
                        <option value="delivery_too_long">Delivery takes too long</option>
                        <option value="other">Other reason</option>
                    </select>
                    <textarea id="cancelNote" class="form-control" rows="2" placeholder="Additional notes"></textarea>
                `,
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                preConfirm: () => {
                    const reason = document.getElementById('cancelReason').value;
                    if (!reason) {
                        Swal.showValidationMessage('Please select a reason');
                        return false;
                    }
                    return {
                        reason,
                        note: document.getElementById('cancelNote').value
                    };
                }
            });

            if (!reason) return;

            Swal.fire({
                title: 'Cancelling...',
                html: '<div class="spinner-border text-danger" role="status"></div>',
                allowOutsideClick: false,
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
                    body: JSON.stringify(reason)
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Order Cancelled',
                        confirmButtonColor: '#667eea',
                        timer: 2000
                    });
                    location.reload();
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: error.message,
                    confirmButtonColor: '#667eea'
                });
            }
        }
    </script>
@endsection
