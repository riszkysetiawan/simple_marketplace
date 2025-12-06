@extends('layouts.app')

@section('title', 'Invoice ' . $transaction->order_number)

@section('content')
    <div class="container py-5">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-8">
                <h1 class="mb-2">
                    <i class="bi bi-receipt me-2"></i> Invoice
                </h1>
                <p class="text-muted">{{ $transaction->order_number }}</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('transactions.invoice', $transaction->id) }}" class="btn btn-primary me-2" target="_blank">
                    <i class="bi bi-download me-2"></i> Download PDF
                </a>
                <button class="btn btn-outline-secondary" onclick="window.print()">
                    <i class="bi bi-printer me-2"></i> Print
                </button>
            </div>
        </div>

        <!-- Invoice Content -->
        <div class="card shadow-sm">
            <div class="card-body p-5">
                <!-- Header -->
                <div class="row mb-5 pb-4 border-bottom">
                    <div class="col-md-6">
                        <h3 class="text-primary mb-3">
                            <i class="bi bi-shop me-2"></i> ShopHub
                        </h3>
                        <p class="text-muted mb-1"><strong>PT. ShopHub Indonesia</strong></p>
                        <p class="text-muted mb-1">Jl. Merdeka No. 123, Jakarta Pusat</p>
                        <p class="text-muted mb-1">DKI Jakarta 12345, Indonesia</p>
                        <p class="text-muted mb-1">Phone: +62 21 1234 5678</p>
                        <p class="text-muted">Email: support@shophub.com</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h1 class="text-muted mb-2" style="font-size: 48px; font-weight: 700;">INVOICE</h1>
                        <p class="mb-1">
                            <strong>Invoice Number:</strong><br>
                            <span class="text-primary"
                                style="font-size: 16px; font-weight: 700;">{{ $transaction->order_number }}</span>
                        </p>
                        <p class="mb-0">
                            <strong>Invoice Date:</strong><br>
                            {{ $transaction->created_at->format('d F Y') }}
                        </p>
                    </div>
                </div>

                <!-- Bill To & Invoice Details -->
                <div class="row mb-5">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded" style="border-left: 4px solid #667eea;">
                            <h6 class="text-muted text-uppercase mb-3" style="font-size: 12px; letter-spacing: 1px;">
                                <i class="bi bi-geo-alt me-2"></i> Shipping Address
                            </h6>
                            <p class="mb-1"><strong>{{ auth()->user()->name }}</strong></p>
                            <p class="mb-1 text-muted">{{ $transaction->shipping_address }}</p>
                            <p class="mb-1 text-muted"><strong>Phone:</strong> {{ $transaction->phone }}</p>
                            <p class="text-muted"><strong>Email:</strong> {{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-6">
                                <div class="p-3 bg-light rounded mb-3" style="border-left: 4px solid #667eea;">
                                    <h6 class="text-muted text-uppercase mb-3"
                                        style="font-size: 12px; letter-spacing: 1px;">
                                        <i class="bi bi-credit-card me-2"></i> Payment Method
                                    </h6>
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
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded mb-3" style="border-left: 4px solid #667eea;">
                                    <h6 class="text-muted text-uppercase mb-3"
                                        style="font-size: 12px; letter-spacing: 1px;">
                                        <i class="bi bi-info-circle me-2"></i> Order Status
                                    </h6>
                                    <p class="mb-0">
                                        <span
                                            class="badge bg-{{ $transaction->status == 'completed' ? 'success' : ($transaction->status == 'cancelled' ? 'danger' : 'primary') }}"
                                            style="font-size: 12px; padding: 8px 12px;">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="table-responsive mb-5">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                <th style="color: white;">Product</th>
                                <th class="text-end" style="color: white;">Price</th>
                                <th class="text-center" style="color: white;">Qty</th>
                                <th class="text-end" style="color: white;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaction->items as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->product->name }}</strong><br>
                                        <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                    </td>
                                    <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end"><strong>Rp
                                            {{ number_format($item->subtotal, 0, ',', '.') }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Summary -->
                <div class="row mb-5">
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <div class="p-4 rounded"
                            style="background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); border: 2px solid #e5e7eb;">
                            <div class="d-flex justify-content-between mb-3">
                                <span style="color: #6b7280; font-weight: 500;">Subtotal:</span>
                                <strong>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span style="color: #6b7280; font-weight: 500;">Shipping:</span>
                                <strong class="text-success">FREE</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span style="color: #6b7280; font-weight: 500;">Tax (11%):</span>
                                <strong>Rp {{ number_format($transaction->tax_amount, 0, ',', '.') }}</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h5 class="mb-0" style="font-weight: 700;">Total Amount:</h5>
                                <h5 class="mb-0 text-primary" style="font-weight: 800; font-size: 18px;">Rp
                                    {{ number_format($transaction->total_amount, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                @if ($transaction->notes)
                    <div class="alert alert-info mb-5" style="background: #eff6ff; border-left: 4px solid #667eea;">
                        <h6 class="alert-heading mb-2" style="color: #1e40af;">
                            <i class="bi bi-info-circle me-2"></i> Order Notes
                        </h6>
                        <p class="mb-0" style="color: #1e40af;">{{ $transaction->notes }}</p>
                    </div>
                @endif

                <!-- Footer -->
                <div class="border-top pt-4">
                    <div class="row text-center mb-4">
                        <div class="col-md-4">
                            <h6 class="text-muted text-uppercase mb-2" style="font-size: 12px; letter-spacing: 1px;">
                                Payment Status</h6>
                            <p class="mb-0">
                                <strong>{{ ucfirst($transaction->payment_status) }}</strong>
                                @if ($transaction->paid_at)
                                    <br><small class="text-muted">{{ $transaction->paid_at->format('d M Y H:i') }}</small>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted text-uppercase mb-2" style="font-size: 12px; letter-spacing: 1px;">Order
                                Date</h6>
                            <p class="mb-0">{{ $transaction->created_at->format('d F Y H:i') }} WIB</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted text-uppercase mb-2" style="font-size: 12px; letter-spacing: 1px;">
                                Invoice Number</h6>
                            <p class="mb-0">{{ $transaction->order_number }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="text-center text-muted small">
                        <p class="mb-2">
                            <strong>Thank you for your purchase!</strong> This invoice is valid for 30 days from the order
                            date.
                        </p>
                        <p class="mb-2">
                            For inquiries, please contact us at <strong>support@shophub.com</strong> or call <strong>+62 21
                                1234 5678</strong>
                        </p>
                        <p class="mb-0" style="font-size: 11px; color: #9ca3af;">
                            <strong>Terms & Conditions:</strong> All sales are final. Returns accepted within 7 days of
                            delivery with original packaging and receipt.
                            This invoice is automatically generated and does not require a signature.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-4">
            <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i> Back to Order Details
            </a>
        </div>
    </div>

    <style>
        @media print {

            .btn,
            .no-print {
                display: none !important;
            }

            body {
                background: white;
            }

            .container {
                max-width: 100%;
            }

            .card {
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>
@endsection
