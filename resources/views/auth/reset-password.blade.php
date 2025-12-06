<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password - {{ config('app.name') }}</title>
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .reset-container {
            max-width: 450px;
            margin: 2rem auto;
        }

        .reset-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .reset-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .reset-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .reset-header p {
            margin: 0;
            opacity: 0.9;
        }

        .reset-body {
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

        .alert {
            border-radius: 8px;
            border: none;
        }

        .password-strength {
            margin-top: 0.5rem;
            height: 4px;
            background: #e2e8f0;
            border-radius: 2px;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            transition: all 0.3s;
        }

        .password-requirements {
            font-size: 0.85rem;
            color: #64748b;
            margin-top: 0.5rem;
        }

        .password-requirements li {
            margin-bottom: 0.25rem;
        }

        .password-requirements .valid {
            color: #10b981;
        }

        @media (max-width: 576px) {
            .reset-container {
                margin: 1rem;
            }

            .reset-body {
                padding: 1.5rem;
            }

            .reset-header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="reset-container">
            <div class="reset-card">
                <!-- Header -->
                <div class="reset-header">
                    <h1>
                        <i class="bi bi-shield-lock"></i> Reset Password
                    </h1>
                    <p>Enter your new password below</p>
                </div>

                <!-- Body -->
                <div class="reset-body">
                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Reset Password Form -->
                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope"></i> Email Address
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email', $request->email) }}"
                                placeholder="Enter your email" required autofocus readonly>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock"></i> New Password
                            </label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Enter new password" required>

                            <!-- Password Strength -->
                            <div class="password-strength">
                                <div class="password-strength-bar" id="strengthBar"></div>
                            </div>

                            <!-- Password Requirements -->
                            <ul class="password-requirements">
                                <li id="req-length">At least 8 characters</li>
                                <li id="req-uppercase">One uppercase letter</li>
                                <li id="req-lowercase">One lowercase letter</li>
                                <li id="req-number">One number</li>
                            </ul>

                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                <i class="bi bi-lock-fill"></i> Confirm Password
                            </label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" placeholder="Confirm new password" required>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle me-2"></i>
                            Reset Password
                        </button>
                    </form>
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

    <!-- Password Strength Checker -->
    <script>
        const passwordInput = document.getElementById('password');
        const strengthBar = document.getElementById('strengthBar');
        const reqLength = document.getElementById('req-length');
        const reqUppercase = document.getElementById('req-uppercase');
        const reqLowercase = document.getElementById('req-lowercase');
        const reqNumber = document.getElementById('req-number');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;

            // Check length
            if (password.length >= 8) {
                strength += 25;
                reqLength.classList.add('valid');
            } else {
                reqLength.classList.remove('valid');
            }

            // Check uppercase
            if (/[A-Z]/.test(password)) {
                strength += 25;
                reqUppercase.classList.add('valid');
            } else {
                reqUppercase.classList.remove('valid');
            }

            // Check lowercase
            if (/[a-z]/.test(password)) {
                strength += 25;
                reqLowercase.classList.add('valid');
            } else {
                reqLowercase.classList.remove('valid');
            }

            // Check number
            if (/[0-9]/.test(password)) {
                strength += 25;
                reqNumber.classList.add('valid');
            } else {
                reqNumber.classList.remove('valid');
            }

            // Update strength bar
            strengthBar.style.width = strength + '%';

            if (strength < 50) {
                strengthBar.style.backgroundColor = '#ef4444';
            } else if (strength < 75) {
                strengthBar.style.backgroundColor = '#f59e0b';
            } else {
                strengthBar.style.backgroundColor = '#10b981';
            }
        });
    </script>
</body>

</html>
