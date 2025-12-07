<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- ✅ Google Sign-In Library -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>

    <!-- Facebook SDK -->
    <script async defer crossorigin="anonymous"
        src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v18.0&appId={{ config('services.facebook.client_id') }}">
    </script>

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
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(79, 70, 229, 0.3);
            color: white;
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

        .btn-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
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

        @keyframes spinner {
            to {
                transform: rotate(360deg);
            }
        }

        /* Hide Google One Tap if needed */
        #g_id_onload {
            display: none !important;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <h1>
                        <i class="bi bi-shop"></i> Welcome Back!
                    </h1>
                    <p>Sign in to your account to continue</p>
                </div>

                <div class="login-body">
                    <!-- Login Form -->
                    <form id="loginForm">
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope"></i> Email Address
                            </label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Enter your email" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock"></i> Password
                            </label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Enter your password" required>
                        </div>

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

                        <button type="submit" class="btn btn-primary w-100 mb-3" id="loginBtn">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            <span id="btnText">Sign In</span>
                        </button>

                        <div class="text-center">
                            <span class="text-muted">Don't have an account? </span>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="link-primary ms-1">Sign Up</a>
                            @endif
                        </div>
                    </form>

                    <div class="divider">
                        <span>OR CONTINUE WITH</span>
                    </div>

                    <div class="row g-2">
                        <!-- Google Login Button -->
                        <div class="col-6">
                            <div id="g_id_signin" style="display: none;"></div>
                            <button type="button" class="btn btn-social btn-google w-100" id="googleLoginBtn">
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

                        <!-- Facebook Login Button -->
                        {{-- <div class="col-6">
                            <button type="button" class="btn btn-social btn-facebook w-100" id="facebookLoginBtn">
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

            <div class="text-center mt-3">
                <p class="text-white mb-0">
                    <small>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</small>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const GOOGLE_CLIENT_ID = '{{ config('services.google.client_id') }}';
        const FACEBOOK_APP_ID = '{{ config('services.facebook.client_id') }}';

        // ========== CSRF TOKEN ==========
        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }

        // ========== REGULAR LOGIN ==========
        async function loginUser(email, password) {
            const loginBtn = document.getElementById('loginBtn');
            const btnText = document.getElementById('btnText');

            loginBtn.disabled = true;
            loginBtn.classList.add('btn-loading');
            btnText.textContent = 'Signing in...';

            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    body: JSON.stringify({
                        email,
                        password
                    })
                });

                const data = await response.json();

                if (data.success) {
                    localStorage.setItem('token', data.data.access_token);
                    localStorage.setItem('user', JSON.stringify(data.data.user));

                    await Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        confirmButtonColor: '#4f46e5',
                        timer: 2000,
                        timerProgressBar: true
                    });

                    window.location.href = data.data.redirect_url;
                } else {
                    throw new Error(data.message || 'Login failed');
                }
            } catch (error) {
                console.error('Login Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Login Failed',
                    text: error.message || 'An error occurred',
                    confirmButtonColor: '#4f46e5'
                });

                loginBtn.disabled = false;
                loginBtn.classList.remove('btn-loading');
                btnText.textContent = 'Sign In';
            }
        }

        // ========== GOOGLE LOGIN CALLBACK ==========
        function handleGoogleCredentialResponse(response) {
            const loginBtn = document.getElementById('googleLoginBtn');
            loginBtn.disabled = true;
            loginBtn.classList.add('btn-loading');

            console.log('Google credential received');

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
                .then(async data => {
                    console.log('Google login response:', data);

                    if (data.success) {
                        localStorage.setItem('token', data.data.access_token);
                        localStorage.setItem('user', JSON.stringify(data.data.user));

                        await Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            confirmButtonColor: '#4f46e5',
                            timer: 2000,
                            timerProgressBar: true
                        });

                        window.location.href = data.data.redirect_url;
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Google login error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: error.message || 'Google login failed',
                        confirmButtonColor: '#4f46e5'
                    });

                    loginBtn.disabled = false;
                    loginBtn.classList.remove('btn-loading');
                });
        }

        // ========== FACEBOOK LOGIN ==========
        function handleFacebookLogin() {
            if (typeof FB === 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Facebook SDK not loaded',
                    confirmButtonColor: '#4f46e5'
                });
                return;
            }

            FB.login(function(response) {
                if (response.authResponse) {
                    const loginBtn = document.getElementById('facebookLoginBtn');
                    loginBtn.disabled = true;
                    loginBtn.classList.add('btn-loading');

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
                        .then(async data => {
                            if (data.success) {
                                localStorage.setItem('token', data.data.access_token);
                                localStorage.setItem('user', JSON.stringify(data.data.user));

                                await Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: data.message,
                                    confirmButtonColor: '#4f46e5',
                                    timer: 2000,
                                    timerProgressBar: true
                                });

                                window.location.href = data.data.redirect_url;
                            } else {
                                throw new Error(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Facebook login error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Login Failed',
                                text: error.message || 'Facebook login failed',
                                confirmButtonColor: '#4f46e5'
                            });

                            loginBtn.disabled = false;
                            loginBtn.classList.remove('btn-loading');
                        });
                } else {
                    console.log('Facebook login cancelled');
                }
            }, {
                scope: 'public_profile,email'
            });
        }

        // ========== INITIALIZE ==========
        window.addEventListener('load', function() {
            // Initialize Google Sign-In
            if (typeof google !== 'undefined' && google.accounts) {
                google.accounts.id.initialize({
                    client_id: GOOGLE_CLIENT_ID,
                    callback: handleGoogleCredentialResponse,
                    auto_select: false,
                    cancel_on_tap_outside: true
                });

                console.log('Google Sign-In initialized');
            } else {
                console.error('Google Sign-In library not loaded');
            }

            // Event Listeners
            document.getElementById('loginForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                loginUser(email, password);
            });

            document.getElementById('googleLoginBtn').addEventListener('click', function() {
                if (typeof google !== 'undefined' && google.accounts) {
                    // Trigger Google One Tap
                    google.accounts.id.prompt((notification) => {
                        if (notification.isNotDisplayed() || notification.isSkippedMoment()) {
                            // Fallback to redirect method
                            console.log('One Tap not available, using redirect method');
                            window.location.href = '/auth/google';
                        }
                    });
                } else {
                    // Fallback to redirect method
                    window.location.href = '/auth/google';
                }
            });

            document.getElementById('facebookLoginBtn').addEventListener('click', handleFacebookLogin);
        });

        // ========== FACEBOOK SDK INIT ==========
        window.fbAsyncInit = function() {
            FB.init({
                appId: FACEBOOK_APP_ID,
                cookie: true,
                xfbml: true,
                version: 'v18.0'
            });

            console.log('Facebook SDK initialized');
        };
    </script>
</body>

</html>
