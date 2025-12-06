<div class="card product-card h-100 border-0 shadow-sm">
    <!-- Product Image -->
    <div class="position-relative overflow-hidden">
        <a href="{{ route('shop.products.show', $product->slug) }}">
            @if ($product->image)
                <img src="{{ Storage::url($product->image) }}" class="card-img-top" alt="{{ $product->name }}">
            @else
                <img src="https://via.placeholder.com/300x250? text={{ urlencode($product->name) }}" class="card-img-top"
                    alt="{{ $product->name }}">
            @endif
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
        <button class="btn btn-light btn-sm position-absolute top-0 start-0 m-2 rounded-circle"
            style="width: 40px; height: 40px;" title="Add to Wishlist">
            <i class="bi bi-heart"></i>
        </button>

        <!-- Quick View Overlay -->
        <div class="product-overlay text-white">
            <div class="d-flex gap-2 justify-content-center">
                <a href="{{ route('shop.products.show', $product->slug) }}" class="btn btn-light btn-sm">
                    <i class="bi bi-eye me-1"></i> View
                </a>
                @if ($product->stock > 0)
                    <button class="btn btn-primary btn-sm add-to-cart" data-product-id="{{ $product->id }}">
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
            {{ $product->category->name ?? 'Uncategorized' }}
        </p>

        <!-- Product Name -->
        <h5 class="card-title mb-3">
            <a href="{{ route('shop.products.show', $product->slug) }}" class="text-decoration-none text-dark">
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
                @if ($product->stock > 0)
                    <button class="btn btn-primary btn-sm add-to-cart" data-product-id="{{ $product->id }}">
                        <i class="bi bi-cart-plus"></i>
                    </button>
                @else
                    <button class="btn btn-secondary btn-sm" disabled>
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
                    <small class="text-warning">
                        <i class="bi bi-star-fill"></i>
                    </small>
                @endif
            </div>
        </div>
    </div>
</div>
