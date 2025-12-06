<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Shopping Cart - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }

        .cart-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 15px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 20px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 20px;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantity-control button {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-control input {
            width: 70px;
            text-align: center;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px;
        }

        .btn-checkout {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            padding: 15px 30px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 8px;
            color: white;
            transition: all 0.3s;
        }

        .btn-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
        }

        .total-section {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
        }

        .empty-cart {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-cart i {
            font-size: 80px;
            color: #d1d5db;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    @include('partials.navbar')

    <div class="cart-container">
        <h1 class="mb-4">
            <i class="bi bi-cart3 me-2"></i> Shopping Cart
            <span class="badge bg-primary" id="cartCount">0</span>
        </h1>

        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-bag-fill me-2"></i> Cart Items
                            </h5>
                            <button class="btn btn-light btn-sm" onclick="clearCart()">
                                <i class="bi bi-trash me-1"></i> Clear Cart
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0" id="cartItems">
                        <!-- Cart items will be loaded here -->
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-calculator me-2"></i> Order Summary
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="total-section">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span class="fw-bold" id="subtotal">Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span class="fw-bold text-success">FREE</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax (11%):</span>
                                <span class="fw-bold" id="tax">Rp 0</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h5 class="mb-0">Total:</h5>
                                <h5 class="mb-0 text-primary" id="totalAmount">Rp 0</h5>
                            </div>
                        </div>

                        <button class="btn btn-checkout w-100 mt-4" id="checkoutBtn" onclick="proceedToCheckout()">
                            <i class="bi bi-credit-card me-2"></i> Proceed to Checkout
                        </button>

                        <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="bi bi-arrow-left me-2"></i> Continue Shopping
                        </a>
                    </div>
                </div>

                <!-- Promo Code -->
                <div class="card mt-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Have a promo code?</h6>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Enter code" id="promoCode">
                            <button class="btn btn-primary" onclick="applyPromo()">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ✅ Define base URLs at top
        const BASE_URL = '{{ url('/') }}';
        const SHOP_PRODUCTS_URL = '{{ url('/shop/products') }}';
        const STORAGE_URL = '{{ url('storage') }}';

        // Load cart on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadCart();
        });

        // Load cart with AJAX
        async function loadCart() {
            try {
                const response = await fetch('{{ route('cart.get') }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    displayCart(data.data);
                } else {
                    throw new Error('Failed to load cart');
                }
            } catch (error) {
                console.error('Load cart error:', error);
                document.getElementById('cartItems').innerHTML = `
                <div class="alert alert-danger m-3">
                    Failed to load cart.Please refresh the page.
                </div>
            `;
            }
        }

        // Display cart items
        function displayCart(cartData) {
            const cartItemsContainer = document.getElementById('cartItems');
            const cartCountBadge = document.getElementById('cartCount');

            cartCountBadge.textContent = cartData.count;

            if (cartData.items.length === 0) {
                cartItemsContainer.innerHTML = `
                <div class="empty-cart">
                    <i class="bi bi-cart-x"></i>
                    <h4 class="text-muted">Your cart is empty</h4>
                    <p class="text-muted">Add some products to get started</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-primary">
                        <i class="bi bi-shop me-2"></i> Start Shopping
                    </a>
                </div>
            `;
                updateTotal(0);
                return;
            }

            let html = '';
            cartData.items.forEach(item => {
                // ✅ Build URLs using variables
                const imageUrl = item.image ? `${STORAGE_URL}/${item.image}` : 'https://via.placeholder.com/100';
                const productUrl = `${SHOP_PRODUCTS_URL}/${item.slug}`;

                html += `
                <div class="cart-item">
                    <img src="${imageUrl}" alt="${escapeHtml(item.name)}" onerror="this.src='https://via.placeholder.com/100'">
                    <div class="flex-grow-1">
                        <h6 class="mb-1">
                            <a href="${productUrl}" class="text-decoration-none text-dark">
                                ${escapeHtml(item.name)}
                            </a>
                        </h6>
                        <p class="text-muted mb-2">Rp ${formatNumber(item.price)}</p>
                        <div class="quantity-control">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(${item.product_id}, ${item.quantity - 1})">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" class="form-control form-control-sm" value="${item.quantity}" readonly>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(${item.product_id}, ${item.quantity + 1})">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="text-end">
                        <p class="mb-2 fw-bold fs-5">Rp ${formatNumber(item.price * item.quantity)}</p>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(${item.product_id})">
                            <i class="bi bi-trash"></i> Remove
                        </button>
                    </div>
                </div>
            `;
            });

            cartItemsContainer.innerHTML = html;
            updateTotal(cartData.total);
        }

        // Update quantity with AJAX
        async function updateQuantity(productId, newQuantity) {
            if (newQuantity < 1) {
                removeItem(productId);
                return;
            }

            try {
                const response = await fetch(`{{ url('/cart/update') }}/${productId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        quantity: newQuantity
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    loadCart();
                } else {
                    throw new Error(data.message || 'Failed to update quantity');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message,
                    confirmButtonColor: '#4f46e5'
                });
            }
        }

        // Remove item with SweetAlert
        async function removeItem(productId) {
            const result = await Swal.fire({
                title: 'Remove Item? ',
                text: 'Are you sure you want to remove this item from cart?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, remove it! ',
                cancelButtonText: 'Cancel'
            });

            if (!result.isConfirmed) return;

            try {
                const response = await fetch(`{{ url('/cart/remove') }}/${productId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Removed! ',
                        text: 'Item removed from cart',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    loadCart();
                } else {
                    throw new Error(data.message || 'Failed to remove item');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message,
                    confirmButtonColor: '#4f46e5'
                });
            }
        }

        // Clear cart with SweetAlert
        async function clearCart() {
            const result = await Swal.fire({
                title: 'Clear Cart? ',
                text: 'This will remove all items from your cart',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, clear it!',
                cancelButtonText: 'Cancel'
            });

            if (!result.isConfirmed) return;

            try {
                const response = await fetch('{{ route('cart.clear') }}', {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Cart Cleared!',
                        text: 'All items removed from cart',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    loadCart();
                } else {
                    throw new Error(data.message || 'Failed to clear cart');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message,
                    confirmButtonColor: '#4f46e5'
                });
            }
        }

        // Update total
        function updateTotal(subtotal) {
            const tax = subtotal * 0.11;
            const total = subtotal + tax;

            document.getElementById('subtotal').textContent = 'Rp ' + formatNumber(subtotal);
            document.getElementById('tax').textContent = 'Rp ' + formatNumber(tax);
            document.getElementById('totalAmount').textContent = 'Rp ' + formatNumber(total);
        }

        // Proceed to checkout
        function proceedToCheckout() {
            const cartCount = parseInt(document.getElementById('cartCount').textContent);

            if (cartCount === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Empty Cart',
                    text: 'Please add items to cart before checkout',
                    confirmButtonColor: '#4f46e5'
                });
                return;
            }

            window.location.href = '{{ route('checkout.index') }}';
        }

        // Apply promo code
        async function applyPromo() {
            const promoCode = document.getElementById('promoCode').value.trim();

            if (!promoCode) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Enter Code',
                    text: 'Please enter a promo code',
                    confirmButtonColor: '#4f46e5'
                });
                return;
            }

            // Mock promo validation
            if (promoCode.toUpperCase() === 'DISCOUNT10') {
                Swal.fire({
                    icon: 'success',
                    title: 'Promo Applied!',
                    text: 'You got 10% discount',
                    confirmButtonColor: '#4f46e5'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Code',
                    text: 'This promo code is not valid',
                    confirmButtonColor: '#4f46e5'
                });
            }
        }

        // Format number
        function formatNumber(num) {
            return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // ✅ Escape HTML to prevent XSS
        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, m => map[m]);
        }
    </script>
</body>

</html>
