<! DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Login - {{ config('app.name') }}</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <style>
            :root {
                --primary-color: #4f46e5;
                --primary-dark: #4338ca;
                --secondary-color: #64748b;
            }

            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

            .login-container {
                max-width: 450px;
                margin: 2rem auto;
            }

            .login-card {
                background: white;
                border-radius: 16px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }

            .login-header {
                background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
                color: white;
                padding: 2rem;
                text-align: center;
            }

            .login-header h1 {
                font-size: 1.75rem;
                font-weight: 700;
                margin-bottom: 0.5rem;
            }

            .login-header p {
                margin: 0;
                opacity: 0.9;
            }

            .login-body {
                padding: 2rem;
            }

            .form-label {
                font-weight: 600;
                color: #334155;
                margin-bottom: 0.5rem;
            }

            .form-control {
                border: 2px solid #e2e8f0;
                border-radius: 8px;
                padding: 0.75rem 1rem;
                font-size: 0.95rem;
                transition: all 0.3s;
            }

            .form-control:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.15);
            }

            .btn-primary {
                background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
                border: none;
                padding: 0.75rem 1.5rem;
                font-weight: 600;
                border-radius: 8px;
                transition: all 0.3s;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(79, 70, 229, 0.3);
            }

            .btn-social {
                border: 2px solid #e2e8f0;
                border-radius: 8px;
                padding: 0.75rem;
                font-weight: 600;
                transition: all 0.3s;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                text-decoration: none;
                color: #475569;
            }

            .btn-social:hover {
                border-color: var(--primary-color);
                color: var(--primary-color);
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            }

            .btn-google:hover {
                border-color: #4285F4;
                color: #4285F4;
            }

            .btn-facebook:hover {
                border-color: #1877F2;
                color: #1877F2;
            }

            .divider {
                display: flex;
                align-items: center;
                text-align: center;
                margin: 1.5rem 0;
            }

            .divider::before,
            .divider::after {
                content: '';
                flex: 1;
                border-bottom: 1px solid #e2e8f0;
            }

            .divider span {
                padding: 0 1rem;
                color: #94a3b8;
                font-size: 0.875rem;
            }

            .alert {
                border-radius: 8px;
                border: none;
            }

            .form-check-input:checked {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
            }

            .link-primary {
                color: var(--primary-color);
                text-decoration: none;
                font-weight: 500;
            }

            .link-primary:hover {
                color: var(--primary-dark);
                text-decoration: underline;
            }

            @media (max-width: 576px) {
                .login-container {
                    margin: 1rem;
                }

                .login-body {
                    padding: 1.5rem;
                }

                .login-header h1 {
                    font-size: 1.5rem;
                }
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="login-container">
                <div class="login-card">
                    <!-- Header -->
                    <div class="login-header">
                        <h1>
                            <i class="bi bi-shop"></i> Welcome Back!
                        </h1>
                        <p>Sign in to your account to continue</p>
                    </div>

                    <!-- Body -->
                    <div class="login-body">
                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                {{ session('status') }}
                            </div>
                        @endif

                        <!-- Error Messages -->
                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                {{ session('error') }}
                            </div>
                        @endif

                        <!-- Login Form -->
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope"></i> Email Address
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}"
                                    placeholder="Enter your email" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock"></i> Password
                                </label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Enter your password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                                    <label class="form-check-label" for="remember_me">
                                        Remember me
                                    </label>
                                </div>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="link-primary">
                                        Forgot Password?
                                    </a>
                                @endif
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Sign In
                            </button>

                            <!-- Register Link -->
                            <div class="text-center">
                                <span class="text-muted">Don't have an account? </span>
                                <a href="{{ route('register') }}" class="link-primary ms-1">
                                    Sign Up
                                </a>
                            </div>
                        </form>

                        <!-- Divider -->
                        <div class="divider">
                            <span>OR CONTINUE WITH</span>
                        </div>

                        <!-- Social Login Buttons -->
                        <div class="row g-2">
                            <!-- Google -->
                            <div class="col-6">
                                <a href="{{ route('auth.google') }}" class="btn btn-social btn-google w-100">
                                    <svg width="20" height="20" viewBox="0 0 24 24">
                                        <path fill="#4285F4"
                                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                        <path fill="#34A853"
                                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                        <path fill="#FBBC05"
                                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                        <path fill="#EA4335"
                                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                                    </svg>
                                    <span>Google</span>
                                </a>
                            </div>

                            <!-- Facebook -->
                            <div class="col-6">
                                <a href="{{ route('auth.facebook') }}" class="btn btn-social btn-facebook w-100">
                                    <svg width="20" height="20" fill="#1877F2" viewBox="0 0 24 24">
                                        <path
                                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                    </svg>
                                    <span>Facebook</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center mt-3">
                    <p class="text-white mb-0">
                        <small>&copy; {{ date('Y') }} {{ config('app.name') }}.All rights reserved.</small>
                    </p>
                </div>
            </div>
        </div>

        <!-- Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>

    </html>
