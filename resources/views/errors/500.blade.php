<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error | {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #ea580c;
            --primary-dark: #c2410c;
        }

        body {
            background: linear-gradient(135deg, #fb923c 0%, #ea580c 100%);
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
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(234, 88, 12, 0.3);
            color: white;
        }

        .error-icon {
            font-size: 5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        @media (max-width: 576px) {
            .error-code {
                font-size: 5rem;
            }

            .error-title {
                font-size: 1.5rem;
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
                <i class="bi bi-bug error-icon"></i>
                <div class="error-code">500</div>
                <h1 class="error-title">Server Error</h1>
                <p class="error-message">
                    @if (config('app.debug') && isset($exception))
                        {{ $exception->getMessage() }}
                    @else
                        Oops! Something went wrong on our server. Please try again later.
                    @endif
                </p>
                <a href="{{ url('/') }}" class="btn-home">
                    <i class="bi bi-house-door"></i>
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</body>

</html>
