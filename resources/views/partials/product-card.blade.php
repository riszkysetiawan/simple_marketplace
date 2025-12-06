@php
    // ✅ Handle image URL (from seeder or storage)
    $imageUrl = null;

    if ($product->image) {
        // Check if it's a full URL (from seeder)
    if (str_starts_with($product->image, 'http://') || str_starts_with($product->image, 'https://')) {
        $imageUrl = $product->image;
    } else {
        // It's a storage path
            $imageUrl = Storage::url($product->image);
        }
    } else {
        // Fallback placeholder
        $imageUrl =
            'https://via.placeholder.com/400x400/4f46e5/ffffff?text=' . urlencode(substr($product->name, 0, 20));
    }
@endphp

<div class="card product-card h-100 border-0 shadow-sm">
    <!-- Product Image -->
    <div class="position-relative overflow-hidden">
        <a href="{{ route('shop.products.show', $product->slug) }}">
            <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $product->name }}"
                style="height: 250px; object-fit: cover;"
                onerror="this.src='https://via.placeholder.com/400x400/e2e8f0/64748b? text=No+Image'" loading="lazy">
        </a>

        <!-- ✅ Badges - Priority Order -->
        @if ($product->stock <= 0)
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                <i class="bi bi-x-circle me-1"></i> Out of Stock
            </span>
        @elseif($product->is_featured)
            <span class="badge bg-warning position-absolute top-0 end-0 m-2">
                <i class="bi bi-star-fill me-1"></i> Featured
            </span>
        @elseif($product->stock < 10)
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                <i class="bi bi-exclamation-triangle me-1"></i> Low Stock
            </span>
        @endif

        <!-- Wishlist Button -->
        {{-- 
        @auth
            <button
                class="btn btn-light btn-sm position-absolute top-0 start-0 m-2 rounded-circle wishlist-btn {{ auth()->user()->hasInWishlist($product->id) ? 'active' : '' }}"
                style="width: 40px; height: 40px;"
                title="{{ auth()->user()->hasInWishlist($product->id) ? 'Remove from Wishlist' : 'Add to Wishlist' }}"
                onclick="toggleWishlist(event, {{ $product->id }})">
                <i
                    class="bi {{ auth()->user()->hasInWishlist($product->id) ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
            </button>
        @else
            <button class="btn btn-light btn-sm position-absolute top-0 start-0 m-2 rounded-circle wishlist-btn"
                style="width: 40px; height: 40px;" title="Add to Wishlist"
                onclick="toggleWishlist(event, {{ $product->id }})">
                <i class="bi bi-heart"></i>
            </button>
        @endauth --}}

        <!-- Quick View Overlay -->
        <div class="product-overlay">
            <div class="d-flex gap-2 justify-content-center">
                <a href="{{ route('shop.products.show', $product->slug) }}" class="btn btn-light btn-sm">
                    <i class="bi bi-eye me-1"></i> View
                </a>
                @if ($product->stock > 0)
                    <button class="btn btn-primary btn-sm add-to-cart-quick" data-product-id="{{ $product->id }}"
                        data-product-name="{{ $product->name }}">
                        <i class="bi bi-cart-plus me-1"></i> Add to Cart
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Product Info -->
    <div class="card-body d-flex flex-column">
        <!-- Category -->
        <p class="text-muted small mb-2">
            <i class="bi bi-tag me-1"></i>
            @if ($product->category)
                <a href="{{ route('shop.category', $product->category->slug) }}"
                    class="text-decoration-none text-muted">
                    {{ $product->category->name }}
                </a>
            @else
                Uncategorized
            @endif
        </p>

        <!-- Product Name -->
        <h5 class="card-title mb-3">
            <a href="{{ route('shop.products.show', $product->slug) }}"
                class="text-decoration-none text-dark product-title">
                {{ Str::limit($product->name, 50) }}
            </a>
        </h5>

        <!-- Rating -->
        <div class="mb-3">
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star text-warning"></i>
            <span class="text-muted small ms-1">(4.0)</span>
        </div>

        <!-- Price & Action -->
        <div class="mt-auto">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <span class="h5 fw-bold text-primary mb-0">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                </div>
                @if ($product->stock > 0 && $product->is_active)
                    <button class="btn btn-primary btn-sm add-to-cart" data-product-id="{{ $product->id }}"
                        data-product-name="{{ $product->name }}" data-product-price="{{ $product->price }}">
                        <i class="bi bi-cart-plus"></i>
                    </button>
                @else
                    <button class="btn btn-secondary btn-sm" disabled title="Out of Stock">
                        <i class="bi bi-x-circle"></i>
                    </button>
                @endif
            </div>

            <!-- Stock Info -->
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    <i class="bi bi-box-seam me-1"></i>
                    Stock: {{ $product->stock }}
                </small>

                <!-- ✅ Featured Icon -->
                @if ($product->is_featured)
                    <small class="text-warning" title="Featured Product">
                        <i class="bi bi-star-fill"></i> Featured
                    </small>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    /* Product Card Styles */
    .product-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
    }

    .product-card .card-img-top {
        transition: transform 0.3s ease;
    }

    .product-card:hover .card-img-top {
        transform: scale(1.05);
    }

    /* Product Overlay */
    .product-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
        padding: 20px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .product-card:hover .product-overlay {
        opacity: 1;
    }

    /* Wishlist Button */
    .wishlist-btn {
        transition: all 0.3s ease;
    }

    .wishlist-btn:hover {
        background: #dc3545 !important;
        color: white !important;
        transform: scale(1.1);
    }

    .wishlist-btn:hover i {
        animation: heartBeat 0.5s;
    }

    @keyframes heartBeat {

        0%,
        100% {
            transform: scale(1);
        }

        25% {
            transform: scale(1.3);
        }

        50% {
            transform: scale(1.1);
        }

        75% {
            transform: scale(1.25);
        }
    }

    /* Product Title */
    .product-title {
        transition: color 0.3s ease;
    }

    .product-title:hover {
        color: #4f46e5 !important;
    }

    /* Image Error Handling */
    .card-img-top {
        background-color: #f8f9fa;
    }
</style>

@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // ✅ Add to Cart (Quick Add)
            document.addEventListener('DOMContentLoaded', function() {
                // Add to cart buttons
                document.querySelectorAll('.add-to-cart, .add-to-cart-quick').forEach(button => {
                        button.addEventListener('click', function(e) {
                                e.preventDefault();
                                e.stopPropagation();

                                const productId = this.dataset.productId;
                                const productName = this.dataset.productName || 'Product';

                                @guest
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Login Required',
                                    text: 'Please login to add items to cart',
                                    showCancelButton: true,
                                    confirmButtonText: 'Login Now',
                                    confirmButtonColor: '#4f46e5',
                                    cancelButtonText: 'Cancel'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = '{{ route('login') }}';
                                    }
                                });
                                return;
                            @endguest

                            // Show loading
                            Swal.fire({
                                title: 'Adding to cart...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            // Send AJAX request
                            fetch(`{{ url('/cart/add') }}/${productId}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    quantity: 1
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Added to Cart!',
                                        html: `<strong>${productName}</strong> has been added to your cart`,
                                        showCancelButton: true,
                                        confirmButtonText: '<i class="bi bi-cart3 me-2"></i> View Cart',
                                        cancelButtonText: 'Continue Shopping',
                                        confirmButtonColor: '#4f46e5',
                                        cancelButtonColor: '#6c757d'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = '{{ route('cart.index') }}';
                                        }
                                    });

                                    // Update cart count if exists
                                    const cartCount = document.querySelector('#navCartCount, .cart-count');
                                    if (cartCount && data.cart_count) {
                                        cartCount.textContent = data.cart_count;
                                    }
                                } else {
                                    throw new Error(data.message || 'Failed to add to cart');
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: error.message || 'Something went wrong! ',
                                    confirmButtonColor: '#4f46e5'
                                });
                            });
                        });
                });
            });

            // ✅ Add to Wishlist
            function addToWishlist(productId) {
                @guest
                Swal.fire({
                    icon: 'warning',
                    title: 'Login Required',
                    text: 'Please login to add items to wishlist',
                    showCancelButton: true,
                    confirmButtonText: 'Login Now',
                    confirmButtonColor: '#4f46e5'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('login') }}';
                    }
                });
                return;
            @endguest

            Swal.fire({
                icon: 'info',
                title: 'Coming Soon',
                text: 'Wishlist feature will be available soon!',
                confirmButtonColor: '#4f46e5'
            });
            }
        </script>
    @endpush
@endonce
