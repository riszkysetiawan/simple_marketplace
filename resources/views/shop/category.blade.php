@extends('layouts.app')

@section('title', $category->name)

@section('content')
    <!-- Breadcrumb -->
    <section class="bg-light py-3">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Shop</a></li>
                    <li class="breadcrumb-item active">{{ $category->name }}</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Category Products -->
    <section class="py-5">
        <div class="container">
            <!-- Category Header -->
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold mb-3">{{ $category->name }}</h1>
                @if ($category->description)
                    <p class="text-muted lead">{{ $category->description }}</p>
                @endif
                <p class="text-muted">
                    <i class="bi bi-box-seam me-2"></i>
                    {{ $products->total() }} products found
                </p>
            </div>

            <!-- Products Grid -->
            <div class="row g-4 mb-4">
                @forelse($products as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        @include('partials.product-card', ['product' => $product])
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            <h5>No products in this category</h5>
                            <p class="mb-3">Check back later for new products</p>
                            <a href="{{ route('shop.index') }}" class="btn btn-primary">
                                <i class="bi bi-shop me-2"></i> Browse All Products
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- ✅ PAGINATION - CENTER -->
            @if ($products->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </section>
@endsection
