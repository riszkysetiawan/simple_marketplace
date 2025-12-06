<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
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
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .email-body {
            padding: 40px 30px;
        }

        .email-body p {
            color: #374151;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .button-container {
            text-align: center;
            margin: 30px 0;
        }

        .reset-button {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
        }

        .expiry-notice {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }

        .expiry-notice p {
            margin: 0;
            color: #92400e;
            font-size: 14px;
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
            <h1>🔐 Reset Your Password</h1>
        </div>

        <div class="email-body">
            <p>Hello! </p>

            <p>You are receiving this email because we received a password reset request for your account.</p>

            <div class="button-container">
                <a href="{{ $url }}" class="reset-button">Reset Password</a>
            </div>

            <div class="expiry-notice">
                <p>⏰ This password reset link will expire in
                    {{ config('auth.passwords.' . config('auth.defaults.passwords') . '.expire') }} minutes.</p>
            </div>

            <p>If you did not request a password reset, no further action is required.</p>

            <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your
                web browser:
            </p>

            <p style="word-break: break-all; color: #6b7280; font-size: 13px;">
                {{ $url }}
            </p>
        </div>

        <div class="email-footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}.All rights reserved.</p>
            <p>
                <a href="{{ config('app.url') }}">Visit our website</a> |
                <a href="{{ config('app.url') }}/contact">Contact Support</a>
            </p>
        </div>
    </div>
</body>

</html>
