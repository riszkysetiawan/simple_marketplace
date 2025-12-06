<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- ✅ SweetAlert2 -->
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
            width: 60px;
            text-align: center;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
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
    </style>
</head>

<body>
    <div class="checkout-container">
        <h1 class="mb-4"><i class="bi bi-cart-check me-2"></i> Checkout</h1>

        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-bag me-2"></i> Your Cart</h5>
                    </div>
                    <div class="card-body" id="cartItems">
                        <!-- Cart items will be loaded here -->
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
                                    <span class="fw-bold">FREE</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <h5>Total:</h5>
                                    <h5 class="text-primary" id="totalAmount">Rp 0</h5>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-checkout w-100" id="checkoutBtn">
                                <i class="bi bi-check-circle me-2"></i>
                                <span>Place Order</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ✅ Cart Data (temporary - should come from session/localStorage)
        let cart = [{
                product_id: 1,
                name: 'iPhone 15 Pro',
                price: 15000000,
                quantity: 1,
                image: 'https://via.placeholder.com/80'
            },
            {
                product_id: 3,
                name: 'MacBook Pro M3',
                price: 35000000,
                quantity: 1,
                image: 'https://via.placeholder.com/80'
            }
        ];

        // Load cart on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadCart();
            updateTotal();

            // Handle checkout form submission
            document.getElementById('checkoutForm').addEventListener('submit', handleCheckout);
        });

        // Load cart items
        function loadCart() {
            const cartItemsContainer = document.getElementById('cartItems');

            if (cart.length === 0) {
                cartItemsContainer.innerHTML = `
                    <div class="text-center py-5">
                        <i class="bi bi-cart-x fs-1 text-muted"></i>
                        <p class="text-muted mt-3">Your cart is empty</p>
                        <a href="/shop" class="btn btn-primary">Continue Shopping</a>
                    </div>
                `;
                return;
            }

            let html = '';
            cart.forEach((item, index) => {
                html += `
                    <div class="cart-item">
                        <img src="${item.image}" alt="${item.name}">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${item.name}</h6>
                            <p class="text-muted mb-2">Rp ${formatNumber(item.price)}</p>
                            <div class="quantity-control">
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(${index}, -1)">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" class="form-control form-control-sm" value="${item.quantity}" readonly>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(${index}, 1)">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="text-end">
                            <p class="mb-2 fw-bold">Rp ${formatNumber(item.price * item.quantity)}</p>
                            <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(${index})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            });

            cartItemsContainer.innerHTML = html;
        }

        // Update quantity
        function updateQuantity(index, change) {
            cart[index].quantity += change;
            if (cart[index].quantity < 1) cart[index].quantity = 1;
            loadCart();
            updateTotal();
        }

        // Remove item
        function removeItem(index) {
            Swal.fire({
                title: 'Remove Item? ',
                text: 'Are you sure you want to remove this item from cart?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, remove it! ',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    cart.splice(index, 1);
                    loadCart();
                    updateTotal();

                    Swal.fire({
                        icon: 'success',
                        title: 'Removed!',
                        text: 'Item removed from cart',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        }

        // Update total
        function updateTotal() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            document.getElementById('subtotal').textContent = 'Rp ' + formatNumber(subtotal);
            document.getElementById('totalAmount').textContent = 'Rp ' + formatNumber(subtotal);
        }

        async function handleCheckout(e) {
            e.preventDefault();

            if (cart.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Empty Cart',
                    text: 'Please add items to cart before checkout'
                });
                return;
            }

            const form = e.target;
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
                items: cart.map(item => ({
                    product_id: item.product_id,
                    quantity: item.quantity
                })),
                payment_method: formData.get('payment_method'),
                shipping_address: formData.get('shipping_address'),
                phone: formData.get('phone'),
                notes: formData.get('notes')
            };

            try {
                // ✅ Use web route instead of API route
                const response = await fetch('{{ route('transactions.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        // ❌ REMOVE Authorization header
                    },
                    body: JSON.stringify(requestData)
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Success
                    await Swal.fire({
                        icon: 'success',
                        title: 'Order Placed Successfully!',
                        html: `
                        <p>Your order <strong>${data.data.order_number}</strong> has been created.</p>
                        <p class="text-muted">Total: <strong>Rp ${formatNumber(data.data.total_amount)}</strong></p>
                        <p class="text-muted small">Check your email for order details</p>
                    `,
                        confirmButtonColor: '#4f46e5',
                        confirmButtonText: 'View Order'
                    });

                    // Clear cart
                    cart = [];

                    // Redirect to customer dashboard
                    window.location.href = '{{ url('/') }}';
                } else {
                    // Error
                    throw new Error(data.message || 'Failed to create order');
                }
            } catch (error) {
                console.error('Checkout error:', error);

                Swal.fire({
                    icon: 'error',
                    title: 'Order Failed',
                    text: error.message || 'Something went wrong.  Please try again.',
                    confirmButtonColor: '#4f46e5'
                });
            }
        }

        // Format number with thousand separator
        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    </script>
</body>

</html>
