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
                            @php
                                // ✅ Check if image is URL or storage path
                                $imageUrl = $product->image;

                                if ($product->image) {
                                    // If it's a full URL (starts with http)
    if (str_starts_with($product->image, 'http')) {
        $imageUrl = $product->image;
    } else {
        // If it's a storage path
                                        $imageUrl = Storage::url($product->image);
                                    }
                                } else {
                                    // Fallback placeholder
                                    $imageUrl =
                                        'https://via.placeholder.com/800x800/4f46e5/ffffff? text=' .
                                        urlencode($product->name);
                                }
                            @endphp

                            <img src="{{ $imageUrl }}" class="img-fluid rounded" alt="{{ $product->name }}"
                                id="mainImage"
                                onerror="this.src='https://via.placeholder.com/800x800/4f46e5/ffffff?text=No+Image'"
                                loading="lazy">

                            <!-- Thumbnail Gallery -->
                            <div class="row g-2 mt-3">
                                <div class="col-3">
                                    <img src="{{ $imageUrl }}"
                                        class="img-fluid rounded border cursor-pointer thumbnail hover-zoom"
                                        alt="Thumbnail 1" style="cursor: pointer; transition: transform 0.2s;"
                                        onmouseover="this.style.transform='scale(1.05)'"
                                        onmouseout="this.style.transform='scale(1)'">
                                </div>
                                <!-- You can add more thumbnails here -->
                                <div class="col-3">
                                    <img src="{{ $imageUrl }}" class="img-fluid rounded border cursor-pointer thumbnail"
                                        alt="Thumbnail 2" style="cursor: pointer;">
                                </div>
                                <div class="col-3">
                                    <img src="{{ $imageUrl }}" class="img-fluid rounded border cursor-pointer thumbnail"
                                        alt="Thumbnail 3" style="cursor: pointer;">
                                </div>
                                <div class="col-3">
                                    <img src="{{ $imageUrl }}"
                                        class="img-fluid rounded border cursor-pointer thumbnail" alt="Thumbnail 4"
                                        style="cursor: pointer;">
                                </div>
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
                            <span class="badge bg-danger ms-2">
                                <i class="bi bi-x-circle me-1"></i> Out of Stock
                            </span>
                        @elseif($product->stock < 10)
                            <span class="badge bg-warning ms-2">
                                <i class="bi bi-exclamation-triangle me-1"></i> Only {{ $product->stock }} left!
                            </span>
                        @else
                            <span class="badge bg-success ms-2">
                                <i class="bi bi-check-circle me-1"></i> In Stock ({{ $product->stock }} available)
                            </span>
                        @endif

                        <!-- Featured Badge -->
                        @if ($product->is_featured)
                            <span class="badge bg-warning ms-2">
                                <i class="bi bi-star-fill me-1"></i> Featured
                            </span>
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
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </h2>
                        <small class="text-muted">Tax included.Shipping calculated at checkout.</small>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-info-circle me-2"></i> Description
                        </h5>
                        <p class="text-muted lh-lg">{{ $product->description }}</p>
                    </div>

                    <!-- Product Details -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-list-check me-2"></i> Product Details
                        </h5>
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
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <strong>Status:</strong>
                                @if ($product->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </li>
                        </ul>
                    </div>

                    <!-- Add to Cart Form -->
                    @if ($product->stock > 0 && $product->is_active)
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-4" id="addToCartForm">
                            @csrf
                            <div class="row g-3 align-items-end">
                                <div class="col-auto">
                                    <label class="form-label fw-bold">Quantity</label>
                                    <div class="input-group" style="width: 150px;">
                                        <button class="btn btn-outline-secondary" type="button" id="decreaseQty">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number" name="quantity" class="form-control text-center"
                                            value="1" min="1" max="{{ $product->stock }}" id="quantity"
                                            required>
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
                            @if (!$product->is_active)
                                This product is currently unavailable.
                            @else
                                This product is currently out of stock.
                            @endif
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 mb-4">
                        <button class="btn btn-outline-danger flex-fill" onclick="addToWishlist({{ $product->id }})">
                            <i class="bi bi-heart me-2"></i> Add to Wishlist
                        </button>
                        <button class="btn btn-outline-secondary" onclick="shareProduct()">
                            <i class="bi bi-share"></i> Share
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

            <!-- Product Tabs -->
            <section class="mt-5">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#description"
                            type="button">
                            Description
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#specifications" type="button">
                            Specifications
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#reviews" type="button">
                            Reviews (128)
                        </button>
                    </li>
                </ul>
                <div class="tab-content p-4 bg-light rounded-bottom">
                    <div class="tab-pane fade show active" id="description">
                        <p>{{ $product->description }}</p>
                    </div>
                    <div class="tab-pane fade" id="specifications">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th width="200">SKU</th>
                                    <td>{{ $product->sku }}</td>
                                </tr>
                                <tr>
                                    <th>Category</th>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Stock</th>
                                    <td>{{ $product->stock }} units</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>{{ $product->is_active ? 'Active' : 'Inactive' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="reviews">
                        <p class="text-muted">Reviews feature coming soon...</p>
                    </div>
                </div>
            </section>

            <!-- Related Products -->
            @if ($relatedProducts->count() > 0)
                <section class="mt-5">
                    <h3 class="fw-bold mb-4">
                        <i class="bi bi-grid me-2"></i> Related Products
                    </h3>
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

@push('styles')
    <style>
        .cursor-pointer {
            cursor: pointer;
        }

        .thumbnail:hover {
            opacity: 0.8;
            transform: scale(1.05);
            transition: all 0.3s ease;
        }

        .thumbnail {
            transition: all 0.3s ease;
        }

        #mainImage {
            max-height: 600px;
            object-fit: contain;
            width: 100%;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Quantity controls
        document.getElementById('increaseQty').addEventListener('click', function() {
            let qty = document.getElementById('quantity');
            let max = parseInt(qty.getAttribute('max'));
            if (parseInt(qty.value) < max) {
                qty.value = parseInt(qty.value) + 1;
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Maximum Stock Reached',
                    text: `Only ${max} units available`,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        });

        document.getElementById('decreaseQty').addEventListener('click', function() {
            let qty = document.getElementById('quantity');
            if (parseInt(qty.value) > 1) {
                qty.value = parseInt(qty.value) - 1;
            }
        });

        // Thumbnail click to change main image
        document.querySelectorAll('.thumbnail').forEach(function(thumb) {
            thumb.addEventListener('click', function() {
                document.getElementById('mainImage').src = this.src;

                // Add active border to clicked thumbnail
                document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('border-primary',
                    'border-3'));
                this.classList.add('border-primary', 'border-3');
            });
        });

        // Add to cart with SweetAlert
        @if (auth()->check())
            document.getElementById('addToCartForm')?.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const quantity = formData.get('quantity');

                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Added to Cart!',
                                text: `${quantity} item(s) added to your cart`,
                                showCancelButton: true,
                                confirmButtonText: 'Go to Cart',
                                cancelButtonText: 'Continue Shopping',
                                confirmButtonColor: '#0d6efd'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '{{ route('cart.index') }}';
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Failed to add to cart'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong'
                        });
                    });
            });
        @endif

        // Add to wishlist
        function addToWishlist(productId) {
            @if (auth()->check())
                Swal.fire({
                    icon: 'info',
                    title: 'Coming Soon',
                    text: 'Wishlist feature will be available soon!',
                    confirmButtonColor: '#0d6efd'
                });
            @else
                Swal.fire({
                    icon: 'warning',
                    title: 'Login Required',
                    text: 'Please login to add items to wishlist',
                    showCancelButton: true,
                    confirmButtonText: 'Login',
                    confirmButtonColor: '#0d6efd'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('login') }}';
                    }
                });
            @endif
        }

        // Share product
        function shareProduct() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $product->name }}',
                    text: '{{ Str::limit($product->description, 100) }}',
                    url: window.location.href
                }).then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Shared! ',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }).catch(console.error);
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(window.location.href).then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Link Copied!',
                        text: 'Product link copied to clipboard',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
            }
        }
    </script>
@endpush
