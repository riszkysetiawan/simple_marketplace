<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>404 - Page Not Found | {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #4338ca;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .error-container {
            text-align: center;
            padding: 2rem;
        }

        .error-card {
            background: white;
            border-radius: 20px;
            padding: 3rem 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            margin: 0 auto;
        }

        .error-code {
            font-size: 8rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 1rem;
        }

        .error-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
        }

        .error-message {
            font-size: 1.1rem;
            color: #64748b;
            margin-bottom: 2rem;
        }

        .btn-home {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
            border: none;
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
            color: white;
        }

        .error-icon {
            font-size: 5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @media (max-width: 576px) {
            .error-code {
                font-size: 5rem;
            }

            .error-title {
                font-size: 1.5rem;
            }

            .error-message {
                font-size: 1rem;
            }

            .error-card {
                padding: 2rem 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="error-container">
            <div class="error-card">
                <i class="bi bi-exclamation-triangle error-icon"></i>
                <div class="error-code">404</div>
                <h1 class="error-title">Page Not Found</h1>
                <p class="error-message">
                    @if (isset($exception) && $exception->getMessage())
                        {{ $exception->getMessage() }}
                    @else
                        Oops! The page you are looking for doesn't exist or has been moved.
                    @endif
                </p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ url('/') }}" class="btn-home">
                        <i class="bi bi-house-door"></i>
                        Back to Home
                    </a>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-home"
                            style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                            <i class="bi bi-speedometer2"></i>
                            Dashboard
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</body>

</html>
