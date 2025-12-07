@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-lg-6 fade-in-up">
                    <h1 class="display-3 fw-bold mb-4">
                        Welcome to <br>
                        <span class="text-warning">Simple Marketplace</span>
                    </h1>
                    <p class="lead mb-4">
                        Discover amazing products at unbeatable prices.Shop with confidence and enjoy fast, secure
                        delivery.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('shop.index') }}" class="btn btn-warning btn-lg px-4">
                            <i class="bi bi-shop me-2"></i> Start Shopping
                        </a>
                        <a href="#featured" class="btn btn-outline-light btn-lg px-4">
                            <i class="bi bi-arrow-down me-2"></i> Explore
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block text-center fade-in-up">
                    <img src="https://images.unsplash.com/photo-1607082349566-187342175e2f?w=600" alt="Shopping"
                        class="img-fluid rounded-4 shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3 col-sm-6">
                    <div class="text-center p-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                            <i class="bi bi-truck fs-1 text-primary"></i>
                        </div>
                        <h5 class="fw-bold">Free Shipping</h5>
                        <p class="text-muted mb-0">On orders over $50</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="text-center p-4">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                            <i class="bi bi-shield-check fs-1 text-success"></i>
                        </div>
                        <h5 class="fw-bold">Secure Payment</h5>
                        <p class="text-muted mb-0">100% secure transactions</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="text-center p-4">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                            <i class="bi bi-arrow-repeat fs-1 text-warning"></i>
                        </div>
                        <h5 class="fw-bold">Easy Returns</h5>
                        <p class="text-muted mb-0">30-day return policy</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="text-center p-4">
                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                            <i class="bi bi-headset fs-1 text-info"></i>
                        </div>
                        <h5 class="fw-bold">24/7 Support</h5>
                        <p class="text-muted mb-0">Dedicated support team</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ✅ Featured Products Section -->
    <section class="py-5" id="featured">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">
                    <i class="bi bi-star-fill text-warning me-2"></i>
                    Featured Products
                </h2>
                <p class="text-muted">Check out our hand-picked featured items</p>
            </div>

            <div class="row g-4">
                @php
                    // ✅ Get featured products (is_featured = true)
                    $featuredProducts = \App\Models\Product::where('is_active', true)
                        ->where('is_featured', true)
                        ->where('stock', '>', 0)
                        ->latest()
                        ->limit(8)
                        ->get();

                    // If no featured products found, get latest 8 products
                    if ($featuredProducts->isEmpty()) {
                        $featuredProducts = \App\Models\Product::where('is_active', true)
                            ->where('stock', '>', 0)
                            ->latest()
                            ->limit(8)
                            ->get();
                    }
                @endphp

                @forelse($featuredProducts as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        @include('partials.product-card', ['product' => $product])
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center py-5">
                            <i class="bi bi-info-circle fs-1 d-block mb-3"></i>
                            <h5>No products available yet</h5>
                            <p class="mb-3">Check back soon for amazing products!</p>
                            <a href="{{ route('shop.index') }}" class="btn btn-primary">
                                <i class="bi bi-shop me-2"></i> Browse Shop
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('shop.index') }}" class="btn btn-primary btn-lg px-5">
                    <i class="bi bi-grid me-2"></i> View All Products
                </a>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Shop by Category</h2>
                <p class="text-muted">Browse our popular categories</p>
            </div>

            <div class="row g-4">
                @php
                    $categories = \App\Models\Category::withCount('products')
                        ->where('is_active', true)
                        ->limit(6)
                        ->get();
                @endphp

                @foreach ($categories as $category)
                    <div class="col-lg-4 col-md-6">
                        <a href="{{ route('shop.category', $category->slug) }}" class="text-decoration-none">
                            <div class="card h-100 border-0 shadow-hover">
                                <div class="card-body text-center p-4">
                                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                                        <i class="bi bi-tag fs-1 text-primary"></i>
                                    </div>
                                    <h5 class="card-title fw-bold">{{ $category->name }}</h5>
                                    <p class="text-muted mb-3">{{ Str::limit($category->description, 60) }}</p>
                                    <span class="badge bg-primary">{{ $category->products_count }} Products</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    {{-- <section class="py-5 bg-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h3 class="fw-bold mb-2">
                        <i class="bi bi-envelope-fill me-2"></i>
                        Subscribe to Our Newsletter
                    </h3>
                    <p class="mb-0">Get the latest updates on new products and upcoming sales</p>
                </div>
                <div class="col-lg-6">
                    <form class="row g-3">
                        <div class="col-md-8">
                            <input type="email" class="form-control form-control-lg" placeholder="Enter your email"
                                required>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-warning btn-lg w-100">
                                <i class="bi bi-send me-2"></i> Subscribe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section> --}}
@endsection
