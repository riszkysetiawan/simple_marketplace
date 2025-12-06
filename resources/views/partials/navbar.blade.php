<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand fw-bold text-primary d-flex align-items-center" href="{{ route('home') }}">
            <i class="bi bi-shop fs-3 me-2"></i>
            <span class="fs-4">Simple Marketplace</span>
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <!-- Center Menu -->
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="bi bi-house-door me-1"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('shop.*') ? 'active' : '' }}"
                        href="{{ route('shop.index') }}">
                        <i class="bi bi-grid me-1"></i> Shop
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-tags me-1"></i> Categories
                    </a>
                    <ul class="dropdown-menu">
                        @php
                            $categories = \App\Models\Category::withCount('products')->limit(10)->get();
                        @endphp
                        @forelse($categories as $category)
                            <li>
                                <a class="dropdown-item" href="{{ route('shop.category', $category->slug) }}">
                                    {{ $category->name }}
                                    <span class="badge bg-secondary ms-2">{{ $category->products_count }}</span>
                                </a>
                            </li>
                        @empty
                            <li><span class="dropdown-item text-muted">No categories</span></li>
                        @endforelse
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item fw-bold" href="{{ route('shop.index') }}">View All Categories</a>
                        </li>
                    </ul>
                </li>
            </ul>

            <!-- Right Menu -->
            <div class="d-flex align-items-center gap-3">
                <!-- Search -->
                <form action="{{ route('shop.search') }}" method="GET" class="d-none d-lg-flex">
                    <div class="input-group">
                        <input type="search" name="q" class="form-control" placeholder="Search products..."
                            value="{{ request('q') }}" style="min-width: 200px;">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                <!-- Cart -->
                @auth
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-primary position-relative">
                        <i class="bi bi-cart3 fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            id="navCartCount">
                            {{ count(session()->get('cart', [])) }}
                        </span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">
                        <i class="bi bi-cart3 fs-5"></i>
                    </a>
                @endauth

                <!-- User Menu -->
                @auth
                    <div class="dropdown">
                        <a class="btn btn-link text-decoration-none dropdown-toggle d-flex align-items-center"
                            href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @if (auth()->user()->avatar)
                                @if (str_starts_with(auth()->user()->avatar, 'http'))
                                    <img src="{{ auth()->user()->avatar }}" class="rounded-circle me-2" width="35"
                                        height="35" style="object-fit: cover; border: 2px solid #4f46e5;" alt="Avatar">
                                @else
                                    <img src="{{ Storage::url(auth()->user()->avatar) }}" class="rounded-circle me-2"
                                        width="35" height="35" style="object-fit: cover; border: 2px solid #4f46e5;"
                                        alt="Avatar">
                                @endif
                            @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                    style="width: 35px; height: 35px; font-size: 16px; font-weight: bold;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <span class="d-none d-md-inline fw-bold text-dark">
                                {{ Str::limit(auth()->user()->name, 15) }}
                            </span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="min-width: 250px;">
                            <!-- User Info Header -->
                            <li class="px-3 py-2 border-bottom">
                                <div class="d-flex align-items-center">
                                    @if (auth()->user()->avatar)
                                        @if (str_starts_with(auth()->user()->avatar, 'http'))
                                            <img src="{{ auth()->user()->avatar }}" class="rounded-circle me-2"
                                                width="40" height="40" style="object-fit: cover;" alt="Avatar">
                                        @else
                                            <img src="{{ Storage::url(auth()->user()->avatar) }}"
                                                class="rounded-circle me-2" width="40" height="40"
                                                style="object-fit: cover;" alt="Avatar">
                                        @endif
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                            style="width: 40px; height: 40px; font-size: 18px; font-weight: bold;">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ auth()->user()->name }}</div>
                                        <small class="text-muted">{{ auth()->user()->email }}</small>
                                    </div>
                                </div>
                            </li>

                            <!-- Menu Items -->
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('profile.index') }}">
                                    <i class="bi bi-person-circle me-2 text-primary"></i> My Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('transactions.index') }}">
                                    <i class="bi bi-bag-check me-2 text-success"></i> My Orders
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('cart.index') }}">
                                    <i class="bi bi-cart3 me-2 text-info"></i> Shopping Cart
                                </a>
                            </li>

                            @if (auth()->user()->hasRole('super_admin'))
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="/admin">
                                        <i class="bi bi-speedometer2 me-2 text-warning"></i> Admin Dashboard
                                    </a>
                                </li>
                            @endif

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <!-- ✅ Logout with SweetAlert -->
                            <li>
                                <a class="dropdown-item py-2 text-danger" href="#" onclick="confirmLogout(event)">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Hidden Logout Form -->
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @else
                    <!-- Guest Menu -->
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary">
                        <i class="bi bi-person-plus me-1"></i> Register
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Search Bar -->
<div class="d-lg-none bg-light py-2">
    <div class="container">
        <form action="{{ route('shop.search') }}" method="GET">
            <div class="input-group">
                <input type="search" name="q" class="form-control" placeholder="Search products..."
                    value="{{ request('q') }}">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // ✅ Update Cart Count
            function updateNavCartCount() {
                @auth
                fetch('{{ route('cart.get') }}')
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const cartCount = document.getElementById('navCartCount');
                            if (cartCount) {
                                cartCount.textContent = data.data.count;

                                // Add pulse animation if count > 0
                                if (data.data.count > 0) {
                                    cartCount.classList.add('animate__animated', 'animate__pulse');
                                    setTimeout(() => {
                                        cartCount.classList.remove('animate__animated', 'animate__pulse');
                                    }, 1000);
                                }
                            }
                        }
                    })
                    .catch(error => console.error('Cart count error:', error));
            @endauth
            }

            // Update on page load
            document.addEventListener('DOMContentLoaded', updateNavCartCount);

            // ✅ Confirm Logout with SweetAlert
            function confirmLogout(event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You will be logged out from your account",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="bi bi-box-arrow-right me-2"></i> Yes, Logout',
                    cancelButtonText: '<i class="bi bi-x-circle me-2"></i> Cancel',
                    reverseButtons: true,
                    focusCancel: true,
                    customClass: {
                        confirmButton: 'btn btn-danger px-4',
                        cancelButton: 'btn btn-secondary px-4'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Logging out...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Submit logout form
                        setTimeout(() => {
                            document.getElementById('logout-form').submit();
                        }, 500);
                    }
                });
            }

            // ✅ Show success message after logout redirect
            @if (session('logout_success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Logged Out Successfully!',
                    text: 'You have been logged out from your account',
                    confirmButtonColor: '#4f46e5',
                    timer: 3000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false
                });
            @endif

            // ✅ Show error message if any
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#4f46e5'
                });
            @endif

            // ✅ Show success message if any
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#4f46e5',
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif
        </script>
    @endpush
@endonce

<style>
    /* Dropdown hover effect */
    .dropdown-menu {
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .dropdown-item {
        transition: all 0.3s ease;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
        padding-left: 1.5rem;
    }

    /* Cart count pulse animation */
    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.2);
        }
    }

    .animate__pulse {
        animation: pulse 0.5s;
    }

    /* Active nav link */
    .nav-link.active {
        color: #4f46e5 !important;
        font-weight: 600;
    }
</style>
