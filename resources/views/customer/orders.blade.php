<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Orders - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }

        .orders-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 15px;
        }

        .order-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .order-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .order-header {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            color: white;
            padding: 20px;
            border-radius: 12px 12px 0 0;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-paid {
            background: #d1fae5;
            color: #065f46;
        }

        .status-processing {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-shipped {
            background: #e0e7ff;
            color: #3730a3;
        }

        .status-completed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>

<body>
    <div class="orders-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-bag-check me-2"></i> My Orders</h1>
            <a href="/shop" class="btn btn-primary">
                <i class="bi bi-shop me-2"></i> Continue Shopping
            </a>
        </div>

        <!-- Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <select class="form-select" id="statusFilter" onchange="loadOrders()">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-secondary" onclick="loadOrders()">
                            <i class="bi bi-arrow-clockwise me-2"></i> Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders List -->
        <div id="ordersList">
            <!-- Orders will be loaded here via AJAX -->
        </div>

        <!-- Pagination -->
        <div id="pagination" class="d-flex justify-content-center mt-4"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let currentPage = 1;
        const apiToken = '{{ auth()->user()->createToken('orders')->plainTextToken }}';

        // Load orders on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadOrders();
        });

        // ✅ Load orders with AJAX
        async function loadOrders(page = 1) {
            const status = document.getElementById('statusFilter').value;
            const ordersList = document.getElementById('ordersList');

            // Show loading
            ordersList.innerHTML =
                '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>';

            try {
                let url = `/api/v1/transactions?page=${page}&per_page=10`;
                if (status) url += `&status=${status}`;

                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + apiToken
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    displayOrders(data.data.data);
                    displayPagination(data.data);
                } else {
                    throw new Error(data.message || 'Failed to load orders');
                }
            } catch (error) {
                console.error('Load orders error:', error);
                ordersList.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Failed to load orders: ${error.message}
                    </div>
                `;
            }
        }

        // Display orders
        function displayOrders(orders) {
            const ordersList = document.getElementById('ordersList');

            if (orders.length === 0) {
                ordersList.innerHTML = `
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mt-3">No orders found</p>
                        <a href="/shop" class="btn btn-primary">Start Shopping</a>
                    </div>
                `;
                return;
            }

            let html = '';
            orders.forEach(order => {
                html += `
                    <div class="order-card">
                        <div class="order-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1">${order.order_number}</h5>
                                    <small>${new Date(order.created_at).toLocaleDateString('id-ID', { 
                                        year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' 
                                    })}</small>
                                </div>
                                <span class="status-badge status-${order.status}">${order.status}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="mb-3">Order Items:</h6>
                                    ${order.items.map(item => `
                                                                                        <div class="d-flex justify-content-between mb-2">
                                                                                            <span>${item.product.name} x${item.quantity}</span>
                                                                                            <span class="text-muted">Rp ${formatNumber(item.subtotal)}</span>
                                                                                        </div>
                                                                                    `).join('')}
                                </div>
                                <div class="col-md-4 text-end">
                                    <p class="mb-1 text-muted">Payment Method</p>
                                    <p class="fw-bold mb-3">${order.payment_method ?  order.payment_method.toUpperCase() : 'Not Set'}</p>
                                    
                                    <p class="mb-1 text-muted">Total Amount</p>
                                    <h4 class="text-primary mb-3">Rp ${formatNumber(order.total_amount)}</h4>
                                    
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewOrderDetail(${order.id})">
                                            <i class="bi bi-eye"></i> View
                                        </button>
                                        ${order.status === 'pending' ? `
                                                                                            <button class="btn btn-sm btn-success" onclick="confirmPayment(${order.id})">
                                                                                                <i class="bi bi-check-circle"></i> Confirm Payment
                                                                                            </button>
                                                                                            <button class="btn btn-sm btn-danger" onclick="cancelOrder(${order.id})">
                                                                                                <i class="bi bi-x-circle"></i> Cancel
                                                                                            </button>
                                                                                        ` : ''}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            ordersList.innerHTML = html;
        }

        // Display pagination
        function displayPagination(paginationData) {
            const pagination = document.getElementById('pagination');

            if (paginationData.last_page <= 1) {
                pagination.innerHTML = '';
                return;
            }

            let html = '<ul class="pagination">';

            // Previous
            html += `<li class="page-item ${paginationData.current_page === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="loadOrders(${paginationData.current_page - 1}); return false;">Previous</a>
            </li>`;

            // Pages
            for (let i = 1; i <= paginationData.last_page; i++) {
                html += `<li class="page-item ${i === paginationData.current_page ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="loadOrders(${i}); return false;">${i}</a>
                </li>`;
            }

            // Next
            html += `<li class="page-item ${paginationData.current_page === paginationData.last_page ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="loadOrders(${paginationData.current_page + 1}); return false;">Next</a>
            </li>`;

            html += '</ul>';
            pagination.innerHTML = html;
        }

        // ✅ Confirm payment with AJAX
        async function confirmPayment(orderId) {
            const result = await Swal.fire({
                title: 'Confirm Payment? ',
                text: 'Have you completed the payment for this order?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, I have paid',
                cancelButtonText: 'Not yet'
            });

            if (!result.isConfirmed) return;

            Swal.fire({
                title: 'Processing...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            try {
                const response = await fetch(`/api/v1/transactions/${orderId}/confirm-payment`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Authorization': 'Bearer ' + apiToken
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Payment Confirmed!',
                        text: 'Your payment has been confirmed. We will process your order soon.',
                        confirmButtonColor: '#4f46e5'
                    });
                    loadOrders(currentPage);
                } else {
                    throw new Error(data.message || 'Failed to confirm payment');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: error.message,
                    confirmButtonColor: '#4f46e5'
                });
            }
        }

        // ✅ Cancel order with AJAX
        async function cancelOrder(orderId) {
            const result = await Swal.fire({
                title: 'Cancel Order?',
                text: 'Are you sure you want to cancel this order?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, cancel it',
                cancelButtonText: 'No, keep it'
            });

            if (!result.isConfirmed) return;

            Swal.fire({
                title: 'Cancelling...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            try {
                const response = await fetch(`/api/v1/transactions/${orderId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Authorization': 'Bearer ' + apiToken
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Order Cancelled',
                        text: 'Your order has been cancelled successfully.',
                        confirmButtonColor: '#4f46e5'
                    });
                    loadOrders(currentPage);
                } else {
                    throw new Error(data.message || 'Failed to cancel order');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: error.message,
                    confirmButtonColor: '#4f46e5'
                });
            }
        }

        // View order detail
        function viewOrderDetail(orderId) {
            window.location.href = `/customer/orders/${orderId}`;
        }

        // Format number
        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    </script>
</body>

</html>
