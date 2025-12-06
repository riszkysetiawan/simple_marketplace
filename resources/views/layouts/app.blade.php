<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ✅ CSRF Token (WAJIB ADA!) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ✅ Auth Check (untuk JavaScript) -->
    @auth
        <meta name="user-authenticated" content="true">
        <meta name="user-id" content="{{ auth()->id() }}">
    @endauth

    <meta name="description" content="@yield('meta_description', 'Simple Marketplace - Your trusted online shopping destination')">
    <meta name="keywords" content="@yield('meta_keywords', 'marketplace, online shop, products')">
    <title>@yield('title', 'Simple Marketplace') - Simple Marketplace</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @stack('styles')

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Back to Top Button */
        #back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            display: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        #back-to-top:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
        }

        #back-to-top.show {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Pulse Animation */
        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.3);
            }
        }

        .pulse-animation {
            animation: pulse 0.5s ease-in-out;
        }

        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loading-overlay.show {
            display: flex;
        }
    </style>
</head>

<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Navigation -->
    @include('partials.navbar')

    <!-- Main Content -->
    <main class="min-vh-100">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Back to Top Button -->
    <button id="back-to-top" class="btn btn-primary btn-floating" title="Back to top">
        <i class="bi bi-arrow-up"></i>
    </button>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery (optional) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom JS -->
    <script src="{{ asset('js/script.js') }}"></script>

    <!-- ✅ GLOBAL FUNCTIONS -->
    <script>
        // ========================================
        // GET CSRF TOKEN
        // ========================================
        function getCsrfToken() {
            const meta = document.querySelector('meta[name="csrf-token"]');
            if (!meta) {
                console.error('❌ CSRF token meta tag not found! ');
                return null;
            }
            return meta.content;
        }

        // ========================================
        // CHECK AUTHENTICATION
        // ========================================
        function isAuthenticated() {
            return document.querySelector('meta[name="user-authenticated"]') !== null;
        }

        // ========================================
        // GLOBAL WISHLIST TOGGLE
        // ========================================


        // ========================================
        // UPDATE CART COUNT
        // ========================================
        function updateCartCount(count) {
            const cartBadge = document.getElementById('navCartCount');
            if (cartBadge) {
                cartBadge.textContent = count;
                cartBadge.classList.add('pulse-animation');
                setTimeout(() => {
                    cartBadge.classList.remove('pulse-animation');
                }, 500);
            }
        }

        // ========================================
        // SHOW TOAST NOTIFICATION
        // ========================================
        function showToast(icon, message) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            Toast.fire({
                icon: icon,
                title: message
            });
        }

        // ========================================
        // SHOW LOADING OVERLAY
        // ========================================
        function showLoading() {
            document.getElementById('loadingOverlay')?.classList.add('show');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay')?.classList.remove('show');
        }

        // ========================================
        // BACK TO TOP BUTTON
        // ========================================
        window.addEventListener('scroll', function() {
            const backToTop = document.getElementById('back-to-top');
            if (window.scrollY > 300) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });

        document.getElementById('back-to-top')?.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // ========================================
        // GLOBAL SESSION MESSAGES
        // ========================================
        @if (session('success'))
            showToast('success', '{{ session('success') }}');
        @endif

        @if (session('error'))
            showToast('error', '{{ session('error') }}');
        @endif

        @if (session('logout_success'))
            showToast('success', 'You have been logged out successfully');
        @endif

        @if (session('info'))
            showToast('info', '{{ session('info') }}');
        @endif

        @if (session('warning'))
            showToast('warning', '{{ session('warning') }}');
        @endif

        // ========================================
        // DEBUG INFO
        // ========================================
        console.log('✅ App loaded successfully!');
        console.log('🔐 CSRF Token:', getCsrfToken() ? 'Present' : 'Missing');
        console.log('👤 Authenticated:', isAuthenticated());
        console.log('📍 Wishlist Routes:');
        console.log('  - Add: /wishlist/add/{id}');
        console.log('  - Remove: /wishlist/remove/{id}');
    </script>

    @stack('scripts')
</body>

</html>
