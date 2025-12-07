<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Updated</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .content {
            background: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            margin: 10px 5px;
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
            background: #d1fae5;
            color: #065f46;
        }

        .status-completed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .order-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .product-item {
            border-bottom: 1px solid #e5e7eb;
            padding: 15px 0;
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .total {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            text-align: right;
            margin-top: 20px;
        }

        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>📦 Order Status Updated</h1>
        <p>Your order has been updated</p>
    </div>

    <div class="content">
        <p>Hi <strong>{{ $transaction->user->name }}</strong>,</p>

        <p>Your order <strong>#{{ $transaction->order_number }}</strong> status has been updated:</p>

        <div style="text-align: center; margin: 20px 0;">
            <span class="status-badge status-{{ $oldStatus }}">{{ ucfirst($oldStatus) }}</span>
            <span style="font-size: 24px;">→</span>
            <span class="status-badge status-{{ $transaction->status }}">{{ ucfirst($transaction->status) }}</span>
        </div>

        <div class="order-details">
            <h3>Order Details</h3>

            <table style="width: 100%; margin-bottom: 20px;">
                <tr>
                    <td><strong>Order Number:</strong></td>
                    <td>{{ $transaction->order_number }}</td>
                </tr>
                <tr>
                    <td><strong>Order Date:</strong></td>
                    <td>{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                </tr>
                <tr>
                    <td><strong>Payment Method:</strong></td>
                    <td>{{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}</td>
                </tr>
            </table>

            <h4>Items:</h4>
            @foreach ($transaction->items as $item)
                <div class="product-item">
                    <strong>{{ $item->product->name }}</strong><br>
                    <small>{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</small>
                    <div style="text-align: right; font-weight: bold;">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </div>
                </div>
            @endforeach

            <div class="total">
                Total: Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
            </div>
        </div>

        @if ($transaction->shipping_address)
            <div class="order-details">
                <h4>Shipping Address:</h4>
                <p>{{ $transaction->shipping_address }}</p>
                @if ($transaction->phone)
                    <p><strong>Phone:</strong> {{ $transaction->phone }}</p>
                @endif
            </div>
        @endif

        <div style="text-align: center;">
            <a href="{{ url('/admin/transactions/' . $transaction->id) }}" class="button">
                View Order Details
            </a>
        </div>

        @if ($transaction->status === 'completed')
            <p style="color: #059669; font-weight: bold;">
                ✅ Thank you for your purchase! We hope you enjoy your products.
            </p>
        @elseif($transaction->status === 'shipped')
            <p style="color: #0284c7; font-weight: bold;">
                🚚 Your order is on the way! You should receive it soon.
            </p>
        @elseif($transaction->status === 'cancelled')
            <p style="color: #dc2626; font-weight: bold;">
                ❌ Your order has been cancelled. If you have any questions, please contact us.
            </p>
        @endif
    </div>

    <div class="footer">
        <p>This is an automated email. Please do not reply.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>

</html>
