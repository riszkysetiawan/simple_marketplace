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
                {{-- <li class="nav-item">
                    <a class="nav-link" href="#about">
                        <i class="bi bi-info-circle me-1"></i> About
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">
                        <i class="bi bi-envelope me-1"></i> Contact
                    </a>
                </li> --}}
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
                <a href="{{ route('cart.index') }}" class="btn btn-outline-primary position-relative">
                    <i class="bi bi-cart3 fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                        id="navCartCount">
                        {{ count(session()->get('cart', [])) }}
                    </span>
                </a>

                <!-- User Menu -->
                @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2"
                            type="button" data-bs-toggle="dropdown">
                            @if (auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="rounded-circle"
                                    width="30" height="30">
                            @else
                                <i class="bi bi-person-circle fs-5"></i>
                            @endif
                            <span class="d-none d-lg-inline">{{ auth()->user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if (auth()->user()->hasRole('super_admin'))
                                <li>
                                    <a class="dropdown-item" href="{{ url('/admin') }}">
                                        <i class="bi bi-speedometer2 me-2"></i> Admin Dashboard
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            @elseif(auth()->user()->hasRole('customer'))
                                <li>
                                    <a class="dropdown-item" href="{{ url('/customer') }}">
                                        <i class="bi bi-speedometer2 me-2"></i> My Dashboard
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person me-2"></i> Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-box-seam me-2"></i> My Orders
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('wishlist.index') }}">
                                    <i class="bi bi-heart me-2"></i> Wishlist
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
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
@push('scripts')
    <script>
        function updateNavCartCount() {
            fetch('{{ route('cart.get') }}')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('navCartCount').textContent = data.data.count;
                    }
                });
        }

        document.addEventListener('DOMContentLoaded', updateNavCartCount);
    </script>
@endpush
