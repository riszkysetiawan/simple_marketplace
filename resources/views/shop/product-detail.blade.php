@extends('layouts.app')

@section('title', $product->name)
@section('meta_description', Str::limit($product->description, 160))

@section('content')
    <!-- Breadcrumb -->
    <section class="bg-light py-3">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Shop</a></li>
                    @if ($product->category)
                        <li class="breadcrumb-item">
                            <a href="{{ route('shop.category', $product->category->slug) }}">
                                {{ $product->category->name }}
                            </a>
                        </li>
                    @endif
                    <li class="breadcrumb-item active">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Product Detail -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Product Images -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                        <div class="card-body p-4">
                            @if ($product->image)
                                <img src="{{ Storage::url($product->image) }}" class="img-fluid rounded"
                                    alt="{{ $product->name }}" id="mainImage">
                            @else
                                <img src="https://via.placeholder.com/600x600? text={{ urlencode($product->name) }}"
                                    class="img-fluid rounded" alt="{{ $product->name }}" id="mainImage">
                            @endif

                            <!-- Thumbnail Gallery (if you have multiple images) -->
                            <div class="row g-2 mt-3">
                                <div class="col-3">
                                    <img src="{{ $product->image ? Storage::url($product->image) : 'https://via.placeholder.com/150' }}"
                                        class="img-fluid rounded border cursor-pointer thumbnail" alt="Thumbnail 1">
                                </div>
                                <!-- Add more thumbnails here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="col-lg-6">
                    <div class="mb-4">
                        <!-- Category Badge -->
                        @if ($product->category)
                            <a href="{{ route('shop.category', $product->category->slug) }}"
                                class="badge bg-primary text-decoration-none">
                                <i class="bi bi-tag me-1"></i> {{ $product->category->name }}
                            </a>
                        @endif

                        <!-- Stock Badge -->
                        @if ($product->stock <= 0)
                            <span class="badge bg-danger ms-2">Out of Stock</span>
                        @elseif($product->stock < 10)
                            <span class="badge bg-warning ms-2">Only {{ $product->stock }} left! </span>
                        @else
                            <span class="badge bg-success ms-2">In Stock</span>
                        @endif
                    </div>

                    <!-- Product Name -->
                    <h1 class="display-5 fw-bold mb-3">{{ $product->name }}</h1>

                    <!-- Rating -->
                    <div class="d-flex align-items-center mb-4">
                        <div class="text-warning me-2">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-half"></i>
                        </div>
                        <span class="text-muted">(4.5 / 5) - 128 reviews</span>
                    </div>

                    <!-- Price -->
                    <div class="mb-4">
                        <h2 class="text-primary fw-bold mb-0">
                            ${{ number_format($product->price, 2) }}
                        </h2>
                        <small class="text-muted">Tax included.Shipping calculated at checkout.</small>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Description</h5>
                        <p class="text-muted">{{ $product->description }}</p>
                    </div>

                    <!-- Product Details -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Product Details</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <strong>SKU:</strong> {{ $product->sku ?? 'N/A' }}
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <strong>Stock:</strong> {{ $product->stock }} units
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <strong>Category:</strong> {{ $product->category->name ?? 'Uncategorized' }}
                            </li>
                        </ul>
                    </div>

                    <!-- Add to Cart Form -->
                    @if ($product->stock > 0)
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-4">
                            @csrf
                            <div class="row g-3 align-items-end">
                                <div class="col-auto">
                                    <label class="form-label fw-bold">Quantity</label>
                                    <div class="input-group" style="width: 150px;">
                                        <button class="btn btn-outline-secondary" type="button" id="decreaseQty">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number" name="quantity" class="form-control text-center"
                                            value="1" min="1" max="{{ $product->stock }}" id="quantity">
                                        <button class="btn btn-outline-secondary" type="button" id="increaseQty">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col">
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="bi bi-cart-plus me-2"></i> Add to Cart
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            This product is currently out of stock.
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 mb-4">
                        <button class="btn btn-outline-danger flex-fill">
                            <i class="bi bi-heart me-2"></i> Add to Wishlist
                        </button>
                        <button class="btn btn-outline-secondary">
                            <i class="bi bi-share"></i>
                        </button>
                    </div>

                    <!-- Features -->
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <div class="row text-center g-3">
                                <div class="col-4">
                                    <i class="bi bi-truck fs-3 text-primary d-block mb-2"></i>
                                    <small class="fw-bold">Free Shipping</small>
                                </div>
                                <div class="col-4">
                                    <i class="bi bi-shield-check fs-3 text-success d-block mb-2"></i>
                                    <small class="fw-bold">Secure Payment</small>
                                </div>
                                <div class="col-4">
                                    <i class="bi bi-arrow-repeat fs-3 text-warning d-block mb-2"></i>
                                    <small class="fw-bold">Easy Returns</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            @if ($relatedProducts->count() > 0)
                <section class="mt-5">
                    <h3 class="fw-bold mb-4">Related Products</h3>
                    <div class="row g-4">
                        @foreach ($relatedProducts as $relatedProduct)
                            <div class="col-lg-3 col-md-6">
                                @include('partials.product-card', ['product' => $relatedProduct])
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Quantity controls
        document.getElementById('increaseQty').addEventListener('click', function() {
            let qty = document.getElementById('quantity');
            let max = parseInt(qty.getAttribute('max'));
            if (parseInt(qty.value) < max) {
                qty.value = parseInt(qty.value) + 1;
            }
        });

        document.getElementById('decreaseQty').addEventListener('click', function() {
            let qty = document.getElementById('quantity');
            if (parseInt(qty.value) > 1) {
                qty.value = parseInt(qty.value) - 1;
            }
        });

        // Thumbnail click
        document.querySelectorAll('.thumbnail').forEach(function(thumb) {
            thumb.addEventListener('click', function() {
                document.getElementById('mainImage').src = this.src;
            });
        });
    </script>
@endpush
