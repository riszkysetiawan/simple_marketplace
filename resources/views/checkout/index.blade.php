<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }

        .checkout-container {
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
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
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

    <div class="checkout-container">
        <h1 class="mb-4"><i class="bi bi-cart-check me-2"></i> Checkout</h1>

        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-bag me-2"></i> Your Cart</h5>
                    </div>
                    <div class="card-body p-0" id="cartItems">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Checkout Form -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-credit-card me-2"></i> Order Details</h5>
                    </div>
                    <div class="card-body">
                        <form id="checkoutForm">
                            @csrf

                            <!-- Payment Method -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Payment Method</label>
                                <select class="form-select" name="payment_method" required>
                                    <option value="">Select Payment</option>
                                    <option value="transfer">Bank Transfer</option>
                                    <option value="ewallet">E-Wallet</option>
                                    <option value="credit_card">Credit Card</option>
                                    <option value="cash">Cash on Delivery</option>
                                </select>
                            </div>

                            <!-- Phone -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Phone Number</label>
                                <input type="tel" class="form-control" name="phone" placeholder="08123456789"
                                    required>
                            </div>

                            <!-- Shipping Address -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Shipping Address</label>
                                <textarea class="form-control" name="shipping_address" rows="3" placeholder="Enter your complete address"
                                    required></textarea>
                            </div>

                            <!-- Notes -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Notes (Optional)</label>
                                <textarea class="form-control" name="notes" rows="2" placeholder="Additional notes"></textarea>
                            </div>

                            <!-- Total -->
                            <div class="total-section mb-3">
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

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-checkout w-100" id="checkoutBtn">
                                <i class="bi bi-check-circle me-2"></i>
                                <span>Place Order</span>
                            </button>

                            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                                <i class="bi bi-arrow-left me-2"></i> Back to Cart
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ✅ Define base URLs
        const BASE_URL = '{{ url('/') }}';
        const STORAGE_URL = '{{ url('storage') }}';
        let cartData = null;

        // Load cart on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadCart();
            document.getElementById('checkoutForm').addEventListener('submit', handleCheckout);
        });

        // ✅ Load cart from session via API
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
                    cartData = data.data;
                    displayCart(cartData);
                } else {
                    throw new Error('Failed to load cart');
                }
            } catch (error) {
                console.error('Load cart error:', error);
                document.getElementById('cartItems').innerHTML = `
                    <div class="alert alert-danger m-3">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        Failed to load cart.<a href="{{ route('cart.index') }}">Go back to cart</a>
                    </div>
                `;
            }
        }

        // Display cart items
        function displayCart(data) {
            const cartItemsContainer = document.getElementById('cartItems');

            if (!data.items || data.items.length === 0) {
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
            data.items.forEach(item => {
                const imageUrl = item.image ? `${STORAGE_URL}/${item.image}` : 'https://via.placeholder.com/80';

                html += `
                    <div class="cart-item">
                        <img src="${imageUrl}" alt="${escapeHtml(item.name)}" 
                            onerror="this.src='https://via.placeholder.com/80'">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${escapeHtml(item.name)}</h6>
                            <p class="text-muted mb-2">Rp ${formatNumber(item.price)}</p>
                            <p class="mb-0">
                                <strong>Qty:</strong> ${item.quantity} × 
                                <strong>Rp ${formatNumber(item.price * item.quantity)}</strong>
                            </p>
                        </div>
                    </div>
                `;
            });

            cartItemsContainer.innerHTML = html;
            updateTotal(data.total);
        }

        // Update total with tax
        function updateTotal(subtotal) {
            const tax = subtotal * 0.11;
            const total = subtotal + tax;

            document.getElementById('subtotal').textContent = 'Rp ' + formatNumber(subtotal);
            document.getElementById('tax').textContent = 'Rp ' + formatNumber(tax);
            document.getElementById('totalAmount').textContent = 'Rp ' + formatNumber(total);
        }

        // Handle checkout submission
        async function handleCheckout(e) {
            e.preventDefault();

            if (!cartData || cartData.items.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Empty Cart',
                    text: 'Please add items to cart before checkout',
                    confirmButtonColor: '#4f46e5'
                });
                return;
            }

            // Validate form
            const form = e.target;
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = new FormData(form);

            // Show loading
            Swal.fire({
                title: 'Processing Order...',
                html: 'Please wait while we process your order',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Prepare request data
            const requestData = {
                items: cartData.items.map(item => ({
                    product_id: item.product_id,
                    quantity: item.quantity
                })),
                payment_method: formData.get('payment_method'),
                shipping_address: formData.get('shipping_address'),
                phone: formData.get('phone'),
                notes: formData.get('notes') || ''
            };

            try {
                const response = await fetch('{{ route('transactions.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(requestData)
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    // Success
                    await Swal.fire({
                        icon: 'success',
                        title: 'Order Placed Successfully!',
                        html: `
                            <p>Your order <strong>${result.data.order_number}</strong> has been created.</p>
                            <p class="text-muted">Total: <strong>Rp ${formatNumber(result.data.total_amount)}</strong></p>
                            <p class="text-muted small">Check your email for order details</p>
                        `,
                        confirmButtonColor: '#4f46e5',
                        confirmButtonText: 'View Orders'
                    });

                    // Clear cart and redirect
                    await fetch('{{ route('cart.clear') }}', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    window.location.href = '{{ route('transactions.index') }}';
                } else {
                    throw new Error(result.message || 'Failed to create order');
                }
            } catch (error) {
                console.error('Checkout error:', error);

                Swal.fire({
                    icon: 'error',
                    title: 'Order Failed',
                    text: error.message || 'Something went wrong.Please try again.',
                    confirmButtonColor: '#4f46e5'
                });
            }
        }

        // Format number
        function formatNumber(num) {
            return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Escape HTML
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
