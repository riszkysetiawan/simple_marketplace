<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Google Sign-In -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>

    <!-- Facebook SDK -->
    <script async defer crossorigin="anonymous"
        src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v18.0&appId={{ env('FACEBOOK_CLIENT_ID') }}">
    </script>

    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #4338ca;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --danger-color: #ef4444;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .register-container {
            max-width: 500px;
            margin: 2rem auto;
        }

        .register-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .register-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .register-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .register-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }

        .register-body {
            padding: 2rem;
        }

        .form-label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
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

        .form-control.is-invalid {
            border-color: var(--danger-color);
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 0.2rem rgba(239, 68, 68, 0.15);
        }

        .invalid-feedback {
            display: block;
            color: var(--danger-color);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .password-strength {
            margin-top: 0.5rem;
            height: 4px;
            background-color: #e2e8f0;
            border-radius: 2px;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.3s;
            border-radius: 2px;
        }

        .password-strength-bar.weak {
            width: 33%;
            background-color: var(--danger-color);
        }

        .password-strength-bar.fair {
            width: 66%;
            background-color: #f59e0b;
        }

        .password-strength-bar.strong {
            width: 100%;
            background-color: var(--success-color);
        }

        .password-strength-text {
            font-size: 0.75rem;
            margin-top: 0.25rem;
            font-weight: 600;
        }

        .password-strength-text.weak {
            color: var(--danger-color);
        }

        .password-strength-text.fair {
            color: #f59e0b;
        }

        .password-strength-text.strong {
            color: var(--success-color);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(79, 70, 229, 0.3);
            color: white;
        }

        .btn-primary:disabled {
            opacity: 0.7;
            cursor: not-allowed;
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
            cursor: pointer;
            background: white;
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

        .form-check-input {
            border: 2px solid #e2e8f0;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-check-label {
            cursor: pointer;
            font-size: 0.95rem;
            color: #475569;
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

        .btn-loading {
            position: relative;
            pointer-events: none;
        }

        .btn-loading::after {
            content: "";
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spinner 0.6s linear infinite;
        }

        .password-toggle {
            cursor: pointer;
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        .password-toggle:hover {
            color: var(--primary-dark);
        }

        .input-group .form-control {
            border-right: none;
        }

        .input-group .btn-outline-secondary {
            border: 2px solid #e2e8f0;
            border-left: none;
            color: var(--primary-color);
        }

        .input-group .btn-outline-secondary:hover {
            background-color: transparent;
            color: var(--primary-dark);
        }

        @keyframes spinner {
            to {
                transform: rotate(360deg);
            }
        }

        .alert-info {
            background-color: #dbeafe;
            border-color: #93c5fd;
            color: #1e40af;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
        }

        @media (max-width: 576px) {
            .register-container {
                margin: 1rem;
            }

            .register-body {
                padding: 1.5rem;
            }

            .register-header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="register-container">
            <div class="register-card">
                <!-- Header -->
                <div class="register-header">
                    <h1>
                        <i class="bi bi-person-plus"></i> Create Account
                    </h1>
                    <p>Join us and start shopping today!</p>
                </div>

                <!-- Body -->
                <div class="register-body">
                    <!-- Info Alert -->
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Welcome!</strong> You'll be registered as a customer by default.
                    </div>

                    <!-- Register Form -->
                    <form id="registerForm" method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Full Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="bi bi-person"></i> Full Name
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" placeholder="Enter your full name"
                                value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope"></i> Email Address
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}"
                                required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock"></i> Password
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Enter your password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="password-strength">
                                <div class="password-strength-bar" id="strengthBar"></div>
                            </div>
                            <div class="password-strength-text" id="strengthText"></div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">
                                <i class="bi bi-lock-check"></i> Confirm Password
                            </label>
                            <div class="input-group">
                                <input type="password"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    id="password_confirmation" name="password_confirmation"
                                    placeholder="Confirm your password" required>
                                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div id="passwordMatch" style="font-size: 0.875rem; margin-top: 0.25rem;"></div>
                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Terms & Conditions -->
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" class="link-primary">Terms & Conditions</a> and
                                <a href="#" class="link-primary">Privacy Policy</a>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100 mb-3" id="registerBtn">
                            <i class="bi bi-check-circle me-2"></i>
                            <span id="btnText">Create Account</span>
                        </button>

                        <!-- Login Link -->
                        <div class="text-center">
                            <span class="text-muted">Already have an account? </span>
                            <a href="{{ route('login') }}" class="link-primary ms-1">
                                Sign In
                            </a>
                        </div>
                    </form>

                    <div class="divider">
                        <span>OR SIGN UP WITH</span>
                    </div>

                    <div class="row g-2">
                        <!-- Google Register -->
                        <div class="col-6">
                            <button type="button" class="btn btn-social btn-google w-100" id="googleRegisterBtn">
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
                            </button>
                        </div>

                        <!-- Facebook Register -->
                        {{-- <div class="col-6">
                            <button type="button" class="btn btn-social btn-facebook w-100"
                                id="facebookRegisterBtn">
                                <svg width="20" height="20" fill="#1877F2" viewBox="0 0 24 24">
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                                <span>Facebook</span>
                            </button>
                        </div> --}}
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-3">
                <p class="text-white mb-0">
                    <small>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</small>
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ========== GET CSRF TOKEN ==========
        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }

        // ========== SHOW WELCOME ALERT ==========
        function showWelcomeAlert() {
            Swal.fire({
                title: 'Welcome! 👋',
                html: `
                    <div style="text-align: left;">
                        <p style="font-size: 0.95rem; color: #475569; margin-bottom: 1rem;">
                            <strong>Join our marketplace community!</strong>
                        </p>
                        <ul style="text-align: left; color: #64748b; font-size: 0.9rem; line-height: 1.8;">
                            <li>✅ Browse thousands of products</li>
                            <li>✅ Secure checkout process</li>
                            <li>✅ Fast and reliable delivery</li>
                            <li>✅ 24/7 customer support</li>
                        </ul>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Let\'s Get Started!',
                confirmButtonColor: '#4f46e5',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: (modal) => {
                    // Animate the modal
                    modal.classList.add('animate__animated', 'animate__fadeIn');
                }
            });
        }

        // ========== PASSWORD STRENGTH CHECKER ==========
        function checkPasswordStrength(password) {
            let strength = 0;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');

            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            strengthBar.className = 'password-strength-bar';
            strengthText.className = 'password-strength-text';

            if (strength < 2) {
                strengthBar.classList.add('weak');
                strengthText.classList.add('weak');
                strengthText.textContent = 'Weak password';
            } else if (strength < 3) {
                strengthBar.classList.add('fair');
                strengthText.classList.add('fair');
                strengthText.textContent = 'Fair password';
            } else {
                strengthBar.classList.add('strong');
                strengthText.classList.add('strong');
                strengthText.textContent = 'Strong password';
            }
        }

        // ========== PASSWORD MATCH CHECKER ==========
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const matchText = document.getElementById('passwordMatch');

            if (confirmPassword === '') {
                matchText.textContent = '';
                matchText.className = '';
                return;
            }

            if (password === confirmPassword) {
                matchText.textContent = '✓ Passwords match';
                matchText.style.color = 'var(--success-color)';
                matchText.style.fontWeight = '600';
            } else {
                matchText.textContent = '✗ Passwords do not match';
                matchText.style.color = 'var(--danger-color)';
                matchText.style.fontWeight = '600';
            }
        }

        // ========== PASSWORD TOGGLE ==========
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const icon = this.querySelector('i');

            if (confirmPasswordInput.type === 'password') {
                confirmPasswordInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                confirmPasswordInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });

        // ========== GOOGLE REGISTER ==========
        function onGoogleRegister(response) {
            const registerBtn = document.getElementById('googleRegisterBtn');
            registerBtn.disabled = true;
            registerBtn.classList.add('btn-loading');

            fetch('/api/google/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    body: JSON.stringify({
                        id_token: response.credential
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Welcome! 🎉',
                            text: 'Registration successful! Your account has been created.',
                            icon: 'success',
                            confirmButtonText: 'Continue',
                            confirmButtonColor: '#4f46e5',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: (modal) => {
                                modal.classList.add('animate__animated', 'animate__zoomIn');
                            }
                        }).then(() => {
                            window.location.href = data.data.redirect_url;
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops! 😞',
                            text: data.message || 'Registration failed',
                            confirmButtonColor: '#4f46e5',
                            confirmButtonText: 'Try Again'
                        });
                        registerBtn.disabled = false;
                        registerBtn.classList.remove('btn-loading');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error! ⚠️',
                        text: 'Google registration failed: ' + error.message,
                        confirmButtonColor: '#4f46e5',
                        confirmButtonText: 'Try Again'
                    });
                    registerBtn.disabled = false;
                    registerBtn.classList.remove('btn-loading');
                });
        }

        // ========== FACEBOOK REGISTER ==========
        function handleFacebookRegister() {
            FB.login(function(response) {
                if (response.authResponse) {
                    const registerBtn = document.getElementById('facebookRegisterBtn');
                    registerBtn.disabled = true;
                    registerBtn.classList.add('btn-loading');

                    fetch('/api/facebook/login', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': getCsrfToken()
                            },
                            body: JSON.stringify({
                                access_token: response.authResponse.accessToken
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Welcome! 🎉',
                                    text: 'Registration successful! Your account has been created.',
                                    icon: 'success',
                                    confirmButtonText: 'Continue',
                                    confirmButtonColor: '#4f46e5',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    didOpen: (modal) => {
                                        modal.classList.add('animate__animated', 'animate__zoomIn');
                                    }
                                }).then(() => {
                                    window.location.href = data.data.redirect_url;
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops! 😞',
                                    text: data.message || 'Registration failed',
                                    confirmButtonColor: '#4f46e5',
                                    confirmButtonText: 'Try Again'
                                });
                                registerBtn.disabled = false;
                                registerBtn.classList.remove('btn-loading');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error! ⚠️',
                                text: 'Facebook registration failed: ' + error.message,
                                confirmButtonColor: '#4f46e5',
                                confirmButtonText: 'Try Again'
                            });
                            registerBtn.disabled = false;
                            registerBtn.classList.remove('btn-loading');
                        });
                }
            }, {
                scope: 'public_profile,email'
            });
        }

        // ========== INITIALIZE & EVENT LISTENERS ==========
        document.addEventListener('DOMContentLoaded', function() {
            // ✅ Show welcome alert on page load
            showWelcomeAlert();

            // ✅ Initialize Google
            if (typeof google !== 'undefined' && google.accounts) {
                google.accounts.id.initialize({
                    client_id: '{{ env('GOOGLE_CLIENT_ID') }}',
                    callback: onGoogleRegister
                });
            }

            // ✅ Event listeners
            document.getElementById('password').addEventListener('input', function() {
                checkPasswordStrength(this.value);
                checkPasswordMatch();
            });

            document.getElementById('password_confirmation').addEventListener('input', function() {
                checkPasswordMatch();
            });

            document.getElementById('googleRegisterBtn').addEventListener('click', function() {
                if (typeof google !== 'undefined' && google.accounts) {
                    google.accounts.id.prompt();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Google Sign-In is not available',
                        confirmButtonColor: '#4f46e5'
                    });
                }
            });

            document.getElementById('facebookRegisterBtn').addEventListener('click', function() {
                if (typeof FB !== 'undefined') {
                    handleFacebookRegister();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Facebook SDK is not available',
                        confirmButtonColor: '#4f46e5'
                    });
                }
            });

            document.getElementById('terms').addEventListener('change', function() {
                document.getElementById('registerBtn').disabled = !this.checked;
            });

            // ✅ Disable submit button by default
            document.getElementById('registerBtn').disabled = true;
        });

        // ========== FACEBOOK SDK INIT ==========
        window.fbAsyncInit = function() {
            FB.init({
                appId: '{{ env('FACEBOOK_CLIENT_ID') }}',
                xfbml: true,
                version: 'v18.0'
            });
        };
    </script>
</body>

</html>
