@extends('layouts.app')

@section('title', 'Shop')

@section('content')
    <!-- Breadcrumb -->
    <section class="bg-light py-3">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Shop</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Shop Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Sidebar Filters -->
                <div class="col-lg-3 mb-4 mb-lg-0">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-funnel me-2"></i> Filters</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('shop.index') }}" method="GET" id="filterForm">
                                <!-- Search -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Search</label>
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Search products..." value="{{ request('search') }}">
                                </div>

                                <!-- Categories -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Categories</label>
                                    <select name="category" class="form-select">
                                        <option value="">All Categories</option>
                                        @foreach (\App\Models\Category::all() as $cat)
                                            <option value="{{ $cat->id }}"
                                                {{ request('category') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Price Range -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Price Range</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="number" name="price_min" class="form-control" placeholder="Min"
                                                value="{{ request('price_min') }}">
                                        </div>
                                        <div class="col-6">
                                            <input type="number" name="price_max" class="form-control" placeholder="Max"
                                                value="{{ request('price_max') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Availability -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Availability</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="in_stock" value="1"
                                            id="inStock" {{ request('in_stock') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="inStock">In Stock Only</label>
                                    </div>
                                </div>

                                <!-- Hidden field for sort -->
                                <input type="hidden" name="sort" value="{{ request('sort') }}">

                                <!-- Buttons -->
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search me-2"></i> Apply Filters
                                    </button>
                                    <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle me-2"></i> Clear Filters
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Popular Categories -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold">Popular Categories</h6>
                        </div>
                        <div class="list-group list-group-flush">
                            @foreach (\App\Models\Category::withCount('products')->limit(5)->get() as $cat)
                                <a href="{{ route('shop.category', $cat->slug) }}"
                                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    {{ $cat->name }}
                                    <span class="badge bg-primary rounded-pill">{{ $cat->products_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="col-lg-9">
                    <!-- Toolbar -->
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                        <div>
                            <h4 class="mb-0">All Products</h4>
                            <p class="text-muted mb-0">
                                Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} of
                                {{ $products->total() }} results
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <select class="form-select" name="sort" id="sortSelect" onchange="applySort()">
                                <option value="">Sort By</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low
                                    to High</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price:
                                    High to Low</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A-Z
                                </option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z-A
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Products -->
                    <div class="row g-4 mb-4">
                        @forelse($products as $product)
                            <div class="col-lg-4 col-md-6">
                                @include('partials.product-card', ['product' => $product])
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-warning text-center py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    <h5>No products found</h5>
                                    <p class="mb-0">Try adjusting your filters or search terms</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- ✅ PAGINATION - CENTER ALIGNED -->
                    @if ($products->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $products->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function applySort() {
            const sortValue = document.getElementById('sortSelect').value;
            const form = document.getElementById('filterForm');

            // Set sort value
            form.querySelector('input[name="sort"]').value = sortValue;

            // Submit form
            form.submit();
        }
    </script>
@endpush
