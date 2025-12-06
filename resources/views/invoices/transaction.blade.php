<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $transaction->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
            background: white;
        }

        .invoice-wrapper {
            max-width: 900px;
            margin: 0 auto;
            background: white;
        }

        /* Header Section */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 3px solid #667eea;
        }

        .company-section {
            flex: 1;
        }

        .company-logo {
            font-size: 32px;
            font-weight: 800;
            color: #667eea;
            margin-bottom: 15px;
            letter-spacing: -1px;
        }

        .company-details {
            font-size: 12px;
            color: #666;
            line-height: 1.8;
        }

        .company-details p {
            margin-bottom: 4px;
        }

        .invoice-title-section {
            text-align: right;
            flex: 1;
        }

        .invoice-title-section h1 {
            font-size: 42px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 10px;
            letter-spacing: -1px;
        }

        .invoice-number {
            font-size: 14px;
            color: #667eea;
            font-weight: 700;
            letter-spacing: 1px;
        }

        /* Details Grid */
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .detail-box {
            padding: 20px;
            background: #f9fafb;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }

        .detail-box h3 {
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .detail-box p {
            font-size: 13px;
            color: #1f2937;
            margin-bottom: 6px;
            line-height: 1.8;
        }

        .detail-box strong {
            color: #1f2937;
            font-weight: 600;
        }

        /* Items Table */
        .items-section {
            margin-bottom: 40px;
        }

        .items-section h2 {
            font-size: 14px;
            color: #1f2937;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        table th {
            padding: 16px 12px;
            text-align: left;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table th.text-right {
            text-align: right;
        }

        table th.text-center {
            text-align: center;
        }

        table td {
            padding: 16px 12px;
            font-size: 13px;
            color: #1f2937;
            border-bottom: 1px solid #e5e7eb;
        }

        table td.text-right {
            text-align: right;
        }

        table td.text-center {
            text-align: center;
        }

        table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        table tbody tr:last-child td {
            border-bottom: 2px solid #667eea;
        }

        .product-name {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .product-sku {
            font-size: 11px;
            color: #9ca3af;
        }

        /* Summary Section */
        .summary-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 40px;
        }

        .summary-box {
            width: 350px;
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 28px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 14px;
            font-size: 13px;
        }

        .summary-row span:first-child {
            color: #6b7280;
            font-weight: 500;
        }

        .summary-row span:last-child {
            color: #1f2937;
            font-weight: 600;
        }

        .summary-row.total {
            border-top: 2px solid #e5e7eb;
            padding-top: 14px;
            margin-top: 14px;
            font-size: 15px;
        }

        .summary-row.total span:first-child {
            color: #1f2937;
            font-weight: 700;
        }

        .summary-row.total span:last-child {
            color: #667eea;
            font-weight: 800;
            font-size: 18px;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
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

        /* Notes Section */
        .notes-section {
            background: #eff6ff;
            border-left: 4px solid #667eea;
            padding: 16px;
            margin-bottom: 40px;
            border-radius: 4px;
        }

        .notes-section h4 {
            font-size: 12px;
            color: #1e40af;
            font-weight: 700;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .notes-section p {
            font-size: 12px;
            color: #1e40af;
            line-height: 1.6;
        }

        /* Footer */
        .invoice-footer {
            border-top: 2px solid #e5e7eb;
            padding-top: 30px;
            margin-top: 30px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
            text-align: center;
        }

        .footer-item h4 {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .footer-item p {
            font-size: 12px;
            color: #1f2937;
            font-weight: 600;
        }

        .footer-item small {
            font-size: 10px;
            color: #9ca3af;
            display: block;
            margin-top: 4px;
        }

        .footer-divider {
            height: 1px;
            background: #e5e7eb;
            margin: 30px 0;
        }

        .footer-text {
            font-size: 10px;
            color: #6b7280;
            line-height: 1.8;
            text-align: center;
        }

        .footer-text p {
            margin-bottom: 8px;
        }

        .footer-text strong {
            color: #1f2937;
        }

        /* Utilities */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-success {
            color: #10b981;
        }

        .text-muted {
            color: #6b7280;
        }

        /* Print Styles */
        @media print {
            body {
                margin: 0;
                padding: 0;
                background: white;
            }

            .invoice-wrapper {
                max-width: 100%;
                margin: 0;
                padding: 0;
            }

            page {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-wrapper">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-section">
                <div class="company-logo">🛍️ ShopHub</div>
                <div class="company-details">
                    <p><strong>PT. ShopHub Indonesia</strong></p>
                    <p>Jl. Merdeka No. 123</p>
                    <p>Jakarta Pusat, DKI Jakarta 12345</p>
                    <p>Indonesia</p>
                    <p style="margin-top: 8px;">
                        <strong>Phone:</strong> +62 21 1234 5678<br>
                        <strong>Email:</strong> support@shophub.com<br>
                        <strong>Website:</strong> www.shophub.com
                    </p>
                </div>
            </div>
            <div class="invoice-title-section">
                <h1>INVOICE</h1>
                <div class="invoice-number">{{ $transaction->order_number }}</div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="details-grid">
            <!-- Bill To -->
            <div class="detail-box">
                <h3>📍 Bill To</h3>
                <p><strong>{{ auth()->user()->name }}</strong></p>
                <p>{{ $transaction->shipping_address }}</p>
                <p style="margin-top: 12px;">
                    <strong>Phone:</strong> {{ $transaction->phone }}<br>
                    <strong>Email:</strong> {{ auth()->user()->email }}
                </p>
            </div>

            <!-- Invoice Details -->
            <div class="detail-box">
                <h3>📋 Invoice Details</h3>
                <p>
                    <strong>Invoice Date:</strong><br>
                    {{ $transaction->created_at->format('d F Y') }}
                </p>
                <p style="margin-top: 12px;">
                    <strong>Payment Method:</strong><br>
                    @switch($transaction->payment_method)
                        @case('transfer')
                            🏦 Bank Transfer
                        @break

                        @case('ewallet')
                            💳 E-Wallet
                        @break

                        @case('credit_card')
                            💳 Credit Card
                        @break

                        @case('cash')
                            💵 Cash on Delivery
                        @break
                    @endswitch
                </p>
                <p style="margin-top: 12px;">
                    <strong>Order Status:</strong><br>
                    <span class="status-badge status-{{ $transaction->status }}">
                        {{ ucfirst($transaction->status) }}
                    </span>
                </p>
            </div>
        </div>

        <!-- Items Table -->
        <div class="items-section">
            <h2>Order Items</h2>
            <table>
                <thead>
                    <tr>
                        <th style="width: 50%;">Product</th>
                        <th style="width: 15%;" class="text-right">Price</th>
                        <th style="width: 15%;" class="text-center">Qty</th>
                        <th style="width: 20%;" class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction->items as $item)
                        <tr>
                            <td>
                                <div class="product-name">{{ $item->product->name }}</div>
                                <div class="product-sku">SKU: {{ $item->product->sku }}</div>
                            </td>
                            <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right"><strong>Rp
                                    {{ number_format($item->subtotal, 0, ',', '.') }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="summary-wrapper">
            <div class="summary-box">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row">
                    <span>Shipping Cost:</span>
                    <span class="text-success">FREE</span>
                </div>
                <div class="summary-row">
                    <span>Tax (11%):</span>
                    <span>Rp {{ number_format($transaction->tax_amount, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row total">
                    <span>Total Amount:</span>
                    <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if ($transaction->notes)
            <div class="notes-section">
                <h4>📝 Order Notes</h4>
                <p>{{ $transaction->notes }}</p>
            </div>
        @endif

        <!-- Footer -->
        <div class="invoice-footer">
            <div class="footer-grid">
                <div class="footer-item">
                    <h4>Payment Status</h4>
                    <p>{{ ucfirst($transaction->payment_status) }}</p>
                    @if ($transaction->paid_at)
                        <small>{{ $transaction->paid_at->format('d M Y H:i') }}</small>
                    @endif
                </div>
                <div class="footer-item">
                    <h4>Order Date</h4>
                    <p>{{ $transaction->created_at->format('d F Y') }}</p>
                    <small>{{ $transaction->created_at->format('H:i') }} WIB</small>
                </div>
                <div class="footer-item">
                    <h4>Invoice Number</h4>
                    <p>{{ $transaction->order_number }}</p>
                </div>
            </div>

            <div class="footer-divider"></div>

            <div class="footer-text">
                <p>
                    <strong>Thank you for your purchase!</strong> This invoice is valid for 30 days from the order date.
                </p>
                <p>
                    For inquiries or support, please contact us at <strong>support@shophub.com</strong> or call
                    <strong>+62 21 1234 5678</strong>
                </p>
                <p style="margin-top: 15px; font-size: 9px; color: #9ca3af;">
                    <strong>Terms & Conditions:</strong> All sales are final. Returns accepted within 7 days of delivery
                    with original packaging and receipt.
                    This invoice is automatically generated and does not require a signature.
                </p>
            </div>
        </div>
    </div>
</body>

</html>
