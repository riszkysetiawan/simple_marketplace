<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Updated</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 650px;
            margin: 40px auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .email-header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 700;
        }

        .email-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 16px;
        }

        .email-body {
            padding: 40px 30px;
        }

        .order-summary {
            background: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .order-item-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .order-item-row:last-child {
            border-bottom: none;
        }

        .order-label {
            color: #6b7280;
            font-weight: 500;
        }

        .order-value {
            color: #111827;
            font-weight: 600;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .items-table th {
            background: #f3f4f6;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
        }

        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            color: #6b7280;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        .total-row {
            background: #f9fafb;
            font-weight: 700;
            color: #111827;
        }

        .total-amount {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
            font-size: 24px;
            font-weight: 700;
        }

        .button-container {
            text-align: center;
            margin: 30px 0;
        }

        .button {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-paid {
            background: #d1fae5;
            color: #065f46;
        }

        .status-processing {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-shipped {
            background: #e0e7ff;
            color: #3730a3;
        }

        .status-completed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .info-box {
            background: #f0f9ff;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #3b82f6;
        }

        .email-footer {
            background: #f9fafb;
            padding: 30px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }

        .email-footer a {
            color: #4f46e5;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <h1>📦 Order Status Updated</h1>
            <p>{{ $transaction->order_number }}</p>
        </div>

        <div class="email-body">
            <p style="font-size: 16px; color: #374151; line-height: 1.6;">
                Hello <strong>{{ $transaction->user->name }}</strong>,
            </p>

            @php
                $statusMessages = [
                    'paid' => 'Your payment has been confirmed!  We are preparing your order.',
                    'processing' => 'Your order is now being processed and packed.',
                    'shipped' => 'Great news! Your order has been shipped and is on its way to you.',
                    'completed' => 'Your order has been delivered!  We hope you enjoy your purchase.',
                    'cancelled' => 'Your order has been cancelled.If you did not request this, please contact us.',
                ];
                $message = $statusMessages[$transaction->status] ?? 'Your order status has been updated.';
            @endphp

            <p style="font-size: 18px; color: #111827; font-weight: 600; line-height: 1.6;">
                {{ $message }}
            </p>

            <div class="status-change-box">
                <p style="margin: 0 0 15px 0; color: #6b7280; font-weight: 600;">Status Update:</p>
                <div>
                    <span class="status-badge status-{{ $oldStatus }}">{{ ucfirst($oldStatus) }}</span>
                    <span class="arrow">→</span>
                    <span
                        class="status-badge status-{{ $transaction->status }}">{{ ucfirst($transaction->status) }}</span>
                </div>
            </div>

            @if ($transaction->status === 'shipped' && $transaction->shipped_at)
                <div style="background: #e0e7ff; padding: 15px; border-radius: 8px; margin: 20px 0;">
                    <p style="margin: 0; color: #3730a3; text-align: center;">
                        🚚 Shipped on: <strong>{{ $transaction->shipped_at->format('d M Y, H:i') }}</strong>
                    </p>
                </div>
            @endif

            @if ($transaction->status === 'completed' && $transaction->completed_at)
                <div style="background: #d1fae5; padding: 15px; border-radius: 8px; margin: 20px 0;">
                    <p style="margin: 0; color: #065f46; text-align: center;">
                        ✅ Completed on: <strong>{{ $transaction->completed_at->format('d M Y, H:i') }}</strong>
                    </p>
                </div>
            @endif

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/customer') }}" class="button">
                    View Order Details
                </a>
            </div>

            <p
                style="font-size: 14px; color: #6b7280; line-height: 1.6; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                Thank you for your business!
            </p>
        </div>

        <div class="email-footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}.All rights reserved.</p>
        </div>
    </div>
</body>

</html>
