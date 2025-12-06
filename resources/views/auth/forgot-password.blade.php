<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- ✅ SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        .forgot-container {
            max-width: 450px;
            margin: 2rem auto;
        }

        .forgot-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .forgot-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .forgot-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .forgot-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }

        .forgot-body {
            padding: 2rem;
        }

        .info-box {
            background: #f1f5f9;
            border-left: 4px solid var(--primary-color);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .info-box p {
            margin: 0;
            color: #475569;
            font-size: 0.9rem;
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

        .btn-secondary {
            background: #e2e8f0;
            color: #475569;
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-secondary:hover {
            background: #cbd5e1;
            transform: translateY(-2px);
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

        @keyframes spinner {
            to {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 576px) {
            .forgot-container {
                margin: 1rem;
            }

            .forgot-body {
                padding: 1.5rem;
            }

            .forgot-header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="forgot-container">
            <div class="forgot-card">
                <!-- Header -->
                <div class="forgot-header">
                    <h1>
                        <i class="bi bi-key"></i> Forgot Password?
                    </h1>
                    <p>No worries, we'll send you reset instructions</p>
                </div>

                <!-- Body -->
                <div class="forgot-body">
                    <!-- Info Box -->
                    <div class="info-box">
                        <p>
                            <i class="bi bi-info-circle me-2"></i>
                            Enter your email and we'll send you a link to reset your password.
                        </p>
                    </div>

                    <!-- ✅ Forgot Password Form with AJAX -->
                    <form id="forgotPasswordForm">
                        @csrf

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope"></i> Email Address
                            </label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Enter your email" required autofocus>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100 mb-3" id="submitBtn">
                            <i class="bi bi-send me-2"></i>
                            <span>Email Password Reset Link</span>
                        </button>

                        <!-- Back to Login -->
                        <a href="{{ route('login') }}" class="btn btn-secondary w-100">
                            <i class="bi bi-arrow-left me-2"></i>
                            Back to Login
                        </a>
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

    <!-- ✅ AJAX Forgot Password Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('forgotPasswordForm');
            const submitBtn = document.getElementById('submitBtn');
            const emailInput = document.getElementById('email');

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Clear previous errors
                emailInput.classList.remove('is-invalid');

                // Show loading
                submitBtn.disabled = true;
                submitBtn.classList.add('btn-loading');
                submitBtn.querySelector('span').style.opacity = '0';

                const formData = new FormData(form);

                try {
                    const response = await fetch('{{ route('password.email') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                            'Accept': 'application/json',
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok) {
                        // Success
                        await Swal.fire({
                            icon: 'success',
                            title: 'Email Sent! ',
                            html: `
                                <p>We have emailed your password reset link! </p>
                                <p class="text-muted small">Please check your inbox and spam folder.</p>
                            `,
                            confirmButtonColor: '#4f46e5',
                            confirmButtonText: 'Got it!'
                        });

                        // Clear form
                        form.reset();
                    } else {
                        // Error
                        if (data.errors && data.errors.email) {
                            emailInput.classList.add('is-invalid');
                            emailInput.nextElementSibling.textContent = data.errors.email[0];

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.errors.email[0],
                                confirmButtonColor: '#4f46e5'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message ||
                                    'Failed to send reset link.Please try again.',
                                confirmButtonColor: '#4f46e5'
                            });
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong. Please try again.',
                        confirmButtonColor: '#4f46e5'
                    });
                } finally {
                    // Hide loading
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('btn-loading');
                    submitBtn.querySelector('span').style.opacity = '1';
                }
            });
        });
    </script>
</body>

</html>
