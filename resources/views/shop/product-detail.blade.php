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
                    {{-- @if ($product->stock > 0 && $product->is_active)
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
                    @endif --}}

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
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                                <i class="bi bi-cart-plus me-2"></i> Add to Cart
                                            </button>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-success btn-lg w-100"
                                                onclick="buyNow()">
                                                <i class="bi bi-lightning-charge me-2"></i> Buy Now
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
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
        // ========================================
        // GLOBAL VARIABLES
        // ========================================
        let isInWishlist = {{ auth()->check() && auth()->user()->hasInWishlist($product->id) ? 'true' : 'false' }};
        const productId = {{ $product->id }};

        // ========================================
        // QUANTITY CONTROLS
        // ========================================
        document.getElementById('increaseQty')?.addEventListener('click', function() {
            let qty = document.getElementById('quantity');
            let max = parseInt(qty.getAttribute('max'));

            if (parseInt(qty.value) < max) {
                qty.value = parseInt(qty.value) + 1;
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Maximum Stock Reached',
                    html: `<p>Only <strong>${max} units</strong> available in stock</p>`,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            }
        });

        document.getElementById('decreaseQty')?.addEventListener('click', function() {
            let qty = document.getElementById('quantity');
            if (parseInt(qty.value) > 1) {
                qty.value = parseInt(qty.value) - 1;
            }
        });

        // Keyboard support for quantity
        document.getElementById('quantity')?.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowUp') {
                e.preventDefault();
                document.getElementById('increaseQty').click();
            } else if (e.key === 'ArrowDown') {
                e.preventDefault();
                document.getElementById('decreaseQty').click();
            }
        });

        // ========================================
        // IMAGE GALLERY
        // ========================================
        document.querySelectorAll('.thumbnail').forEach(function(thumb) {
            thumb.addEventListener('click', function() {
                // Update main image
                document.getElementById('mainImage').src = this.src;

                // Update active border
                document.querySelectorAll('.thumbnail').forEach(t => {
                    t.classList.remove('border-primary', 'border-3');
                });
                this.classList.add('border-primary', 'border-3');
            });
        });

        // Image zoom on hover
        const mainImage = document.getElementById('mainImage');
        if (mainImage) {
            mainImage.addEventListener('mousemove', function(e) {
                const rect = this.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width) * 100;
                const y = ((e.clientY - rect.top) / rect.height) * 100;
                this.style.transformOrigin = `${x}% ${y}%`;
            });

            mainImage.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.5)';
                this.style.cursor = 'zoom-in';
            });

            mainImage.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
                this.style.cursor = 'default';
            });
        }

        // ========================================
        // ADD TO CART
        // ========================================
        @auth
        document.getElementById('addToCartForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const quantity = formData.get('quantity');
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;

            // Disable button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Adding...';

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Update cart count
                    updateCartCount(data.cart_count || (parseInt(document.getElementById('navCartCount')
                        ?.textContent || 0) + parseInt(quantity)));

                    // Show success message
                    const result = await Swal.fire({
                        icon: 'success',
                        title: 'Added to Cart!',
                        html: `
                            <p><strong>${quantity} item(s)</strong> added to your cart</p>
                            <p class="text-muted small">Product: {{ Str::limit($product->name, 40) }}</p>
                        `,
                        showCancelButton: true,
                        confirmButtonText: '<i class="bi bi-cart3 me-2"></i> Go to Cart',
                        cancelButtonText: '<i class="bi bi-shop me-2"></i> Continue Shopping',
                        confirmButtonColor: '#667eea',
                        cancelButtonColor: '#6c757d',
                        reverseButtons: true
                    });

                    if (result.isConfirmed) {
                        window.location.href = '{{ route('cart.index') }}';
                    }
                } else {
                    throw new Error(data.message || 'Failed to add to cart');
                }
            } catch (error) {
                console.error('Add to cart error:', error);

                Swal.fire({
                    icon: 'error',
                    title: 'Failed to Add',
                    text: error.message || 'Something went wrong',
                    confirmButtonColor: '#667eea'
                });
            } finally {
                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        });
        @else
            document.getElementById('addToCartForm')?.addEventListener('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    icon: 'warning',
                    title: 'Login Required',
                    text: 'Please login to add items to cart',
                    showCancelButton: true,
                    confirmButtonText: '<i class="bi bi-box-arrow-in-right me-2"></i> Login',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#667eea',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('login') }}';
                    }
                });
            });
        @endauth

        // ========================================
        // WISHLIST FUNCTIONS
        // ========================================
        async function addToWishlist(productId) {
            @guest
            Swal.fire({
                icon: 'warning',
                title: 'Login Required',
                text: 'Please login to add items to wishlist',
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-box-arrow-in-right me-2"></i> Login',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#667eea',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('login') }}';
                }
            });
            return;
        @endguest

        @auth
        // Toggle wishlist
        if (isInWishlist) {
            await removeFromWishlist(productId);
        } else {
            await addProductToWishlist(productId);
        }
        @endauth
        }

        @auth
        async function addProductToWishlist(productId) {
            try {
                const response = await fetch(`/wishlist/add/${productId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    isInWishlist = true;
                    updateWishlistButton();
                    updateWishlistCount(data.wishlist_count);

                    // Show success toast
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });

                    Toast.fire({
                        icon: 'success',
                        title: '❤️ Added to Wishlist',
                        text: data.message
                    });
                } else {
                    throw new Error(data.message || 'Failed to add to wishlist');
                }
            } catch (error) {
                console.error('Wishlist error:', error);

                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: error.message,
                    confirmButtonColor: '#667eea'
                });
            }
        }

        async function removeFromWishlist(productId) {
            const result = await Swal.fire({
                title: 'Remove from Wishlist?',
                text: 'This item will be removed from your wishlist',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Remove',
                cancelButtonText: 'Cancel'
            });

            if (!result.isConfirmed) return;

            try {
                const response = await fetch(`/wishlist/remove/${productId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    isInWishlist = false;
                    updateWishlistButton();
                    updateWishlistCount(data.wishlist_count);

                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });

                    Toast.fire({
                        icon: 'info',
                        title: 'Removed from Wishlist',
                        text: data.message
                    });
                } else {
                    throw new Error(data.message || 'Failed to remove');
                }
            } catch (error) {
                console.error('Remove error:', error);

                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: error.message,
                    confirmButtonColor: '#667eea'
                });
            }
        }

        function updateWishlistButton() {
            const wishlistBtn = document.querySelector('[onclick*="addToWishlist"]');
            if (wishlistBtn) {
                if (isInWishlist) {
                    wishlistBtn.classList.remove('btn-outline-danger');
                    wishlistBtn.classList.add('btn-danger');
                    wishlistBtn.innerHTML = '<i class="bi bi-heart-fill me-2"></i> Remove from Wishlist';
                } else {
                    wishlistBtn.classList.remove('btn-danger');
                    wishlistBtn.classList.add('btn-outline-danger');
                    wishlistBtn.innerHTML = '<i class="bi bi-heart me-2"></i> Add to Wishlist';
                }
            }
        }

        // Update button on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateWishlistButton();
        });
        @endauth

        // ========================================
        // SHARE PRODUCT
        // ========================================
        async function shareProduct() {
            const shareData = {
                title: '{{ $product->name }}',
                text: '{{ Str::limit($product->description, 100) }}',
                url: window.location.href
            };

            try {
                if (navigator.share) {
                    // Use native share API
                    await navigator.share(shareData);

                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });

                    Toast.fire({
                        icon: 'success',
                        title: 'Shared Successfully!'
                    });
                } else {
                    // Fallback: Show share options
                    const result = await Swal.fire({
                        title: 'Share Product',
                        html: `
                        <div class="d-flex flex-column gap-3">
                            <button class="btn btn-primary" onclick="copyToClipboard()">
                                <i class="bi bi-clipboard me-2"></i> Copy Link
                            </button>
                            <a href="https://wa.me/?text=${encodeURIComponent(shareData.title + ' - ' + shareData.url)}" 
                               target="_blank" 
                               class="btn btn-success">
                                <i class="bi bi-whatsapp me-2"></i> Share via WhatsApp
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareData.url)}" 
                               target="_blank" 
                               class="btn btn-primary">
                                <i class="bi bi-facebook me-2"></i> Share on Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet? text=${encodeURIComponent(shareData.title)}&url=${encodeURIComponent(shareData.url)}" 
                               target="_blank" 
                               class="btn btn-info">
                                <i class="bi bi-twitter me-2"></i> Share on Twitter
                            </a>
                        </div>
                    `,
                        showConfirmButton: false,
                        showCloseButton: true
                    });
                }
            } catch (error) {
                console.error('Share error:', error);
            }
        }

        async function copyToClipboard() {
            try {
                await navigator.clipboard.writeText(window.location.href);

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });

                Toast.fire({
                    icon: 'success',
                    title: 'Link Copied! ',
                    text: 'Product link copied to clipboard'
                });

                Swal.close();
            } catch (error) {
                console.error('Copy error:', error);
            }
        }

        // ========================================
        // UTILITY FUNCTIONS
        // ========================================
        function updateCartCount(count) {
            const cartBadge = document.getElementById('navCartCount');
            if (cartBadge) {
                cartBadge.textContent = count;

                // Add animation
                cartBadge.style.animation = 'none';
                setTimeout(() => {
                    cartBadge.style.animation = 'pulse 0.5s';
                }, 10);
            }
        }

        function updateWishlistCount(count) {
            const wishlistBadge = document.getElementById('navWishlistCount');
            if (wishlistBadge) {
                wishlistBadge.textContent = count;

                if (count > 0) {
                    wishlistBadge.style.display = 'inline-block';
                } else {
                    wishlistBadge.style.display = 'none';
                }

                // Add animation
                wishlistBadge.style.animation = 'none';
                setTimeout(() => {
                    wishlistBadge.style.animation = 'pulse 0.5s';
                }, 10);
            }
        }

        // ========================================
        // BUY NOW (Quick Checkout)
        // ========================================
        function buyNow() {
            @auth
            const quantity = document.getElementById('quantity').value;

            Swal.fire({
                title: 'Quick Checkout',
                html: `
                    <p>Proceed to checkout with <strong>${quantity} item(s)</strong>?</p>
                    <p class="text-muted small">This will take you directly to the checkout page</p>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-lightning-charge me-2"></i> Buy Now',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#667eea',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Add to cart first, then redirect to checkout
                    document.getElementById('addToCartForm').submit();
                    setTimeout(() => {
                        window.location.href = '{{ route('checkout.index') }}';
                    }, 500);
                }
            });
        @else
            Swal.fire({
                icon: 'warning',
                title: 'Login Required',
                text: 'Please login to proceed with checkout',
                showCancelButton: true,
                confirmButtonText: 'Login',
                confirmButtonColor: '#667eea'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('login') }}';
                }
            });
        @endauth
        }

        // ========================================
        // SESSION MESSAGES
        // ========================================
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#667eea',
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#667eea'
            });
        @endif

        // ========================================
        // ANIMATIONS
        // ========================================
        const style = document.createElement('style');
        style.textContent = `
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }

        #mainImage {
            transition: transform 0.3s ease;
        }
    `;
        document.head.appendChild(style);

        console.log('✅ Product detail page loaded successfully!');
    </script>
@endpush
