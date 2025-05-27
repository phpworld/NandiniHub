<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Order Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 mb-1">Order Details</h1>
                    <p class="text-muted mb-0">Order #<?= esc($order['order_number']) ?></p>
                </div>
                <div class="text-end">
                    <span class="badge bg-<?= getStatusColor($order['status']) ?> fs-6 mb-2">
                        <?= ucfirst($order['status']) ?>
                    </span>
                    <br>
                    <small class="text-muted">
                        Placed on <?= date('M d, Y \a\t h:i A', strtotime($order['created_at'])) ?>
                    </small>
                </div>
            </div>

            <div class="row">
                <!-- Order Items -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-shopping-bag me-2"></i>Order Items
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orderItems as $item): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <?php if (!empty($item['product_image'])): ?>
                                                            <img src="<?= base_url('uploads/products/' . $item['product_image']) ?>"
                                                                alt="<?= esc($item['product_name']) ?>"
                                                                class="img-thumbnail me-3"
                                                                style="width: 60px; height: 60px; object-fit: cover;">
                                                        <?php else: ?>
                                                            <div class="bg-light d-flex align-items-center justify-content-center me-3"
                                                                style="width: 60px; height: 60px;">
                                                                <i class="fas fa-image text-muted"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div>
                                                            <h6 class="mb-1"><?= esc($item['product_name']) ?></h6>
                                                            <?php if (!empty($item['product_sku'])): ?>
                                                                <small class="text-muted">SKU: <?= esc($item['product_sku']) ?></small>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>₹<?= number_format($item['price'], 2) ?></td>
                                                <td><?= $item['quantity'] ?></td>
                                                <td>₹<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Order Status Timeline -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-clock me-2"></i>Order Timeline
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item <?= in_array($order['status'], ['pending', 'confirmed', 'processing', 'shipped', 'delivered']) ? 'active' : '' ?>">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6>Order Placed</h6>
                                        <p class="text-muted mb-0"><?= date('M d, Y \a\t h:i A', strtotime($order['created_at'])) ?></p>
                                    </div>
                                </div>

                                <div class="timeline-item <?= in_array($order['status'], ['confirmed', 'processing', 'shipped', 'delivered']) ? 'active' : '' ?>">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6>Order Confirmed</h6>
                                        <p class="text-muted mb-0">
                                            <?= $order['status'] != 'pending' ? 'Confirmed' : 'Pending confirmation' ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="timeline-item <?= in_array($order['status'], ['processing', 'shipped', 'delivered']) ? 'active' : '' ?>">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6>Processing</h6>
                                        <p class="text-muted mb-0">
                                            <?= in_array($order['status'], ['processing', 'shipped', 'delivered']) ? 'Order is being processed' : 'Waiting for processing' ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="timeline-item <?= in_array($order['status'], ['shipped', 'delivered']) ? 'active' : '' ?>">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6>Shipped</h6>
                                        <p class="text-muted mb-0">
                                            <?= in_array($order['status'], ['shipped', 'delivered']) ? 'Order has been shipped' : 'Waiting for shipment' ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="timeline-item <?= $order['status'] == 'delivered' ? 'active' : '' ?>">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6>Delivered</h6>
                                        <p class="text-muted mb-0">
                                            <?= $order['status'] == 'delivered' ? 'Order delivered successfully' : 'Pending delivery' ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <!-- Order Summary Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-receipt me-2"></i>Order Summary
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>₹<?= number_format($order['total_amount'] - $order['shipping_amount'] - $order['tax_amount'], 2) ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span>₹<?= number_format($order['shipping_amount'], 2) ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax (18%):</span>
                                <span>₹<?= number_format($order['tax_amount'], 2) ?></span>
                            </div>
                            <?php if ($order['discount_amount'] > 0): ?>
                                <div class="d-flex justify-content-between mb-2 text-success">
                                    <span>Discount:</span>
                                    <span>-₹<?= number_format($order['discount_amount'], 2) ?></span>
                                </div>
                            <?php endif; ?>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total:</span>
                                <span>₹<?= number_format($order['total_amount'], 2) ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-credit-card me-2"></i>Payment Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2">
                                <strong>Method:</strong>
                                <?= $order['payment_method'] == 'cod' ? 'Cash on Delivery' : 'Online Payment' ?>
                            </p>
                            <p class="mb-3">
                                <strong>Status:</strong>
                                <span class="badge bg-<?= $order['payment_status'] == 'paid' ? 'success' : ($order['payment_status'] == 'pending' ? 'warning' : 'danger') ?>">
                                    <?= ucfirst($order['payment_status']) ?>
                                </span>
                            </p>

                            <?php if ($order['payment_method'] == 'online' && $order['payment_status'] == 'pending'): ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Payment Required</strong><br>
                                    Your order is pending payment. Please complete the payment to confirm your order.
                                </div>
                                <div class="d-grid">
                                    <button type="button" class="btn btn-primary" onclick="initiatePayment(<?= $order['id'] ?>)">
                                        <i class="fas fa-credit-card me-2"></i>Pay Now
                                    </button>
                                </div>
                            <?php elseif ($order['payment_method'] == 'online' && $order['payment_status'] == 'paid'): ?>
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Payment completed successfully. Your order is confirmed.
                                </div>
                            <?php elseif ($order['payment_method'] == 'online' && $order['payment_status'] == 'failed'): ?>
                                <div class="alert alert-danger">
                                    <i class="fas fa-times-circle me-2"></i>
                                    Payment failed. Please try again or contact support.
                                </div>
                                <div class="d-grid">
                                    <button type="button" class="btn btn-primary" onclick="initiatePayment(<?= $order['id'] ?>)">
                                        <i class="fas fa-redo me-2"></i>Retry Payment
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-truck me-2"></i>Shipping Address
                            </h5>
                        </div>
                        <div class="card-body">
                            <address class="mb-0">
                                <?= nl2br(esc($order['shipping_address'])) ?>
                            </address>
                        </div>
                    </div>

                    <!-- Billing Address -->
                    <?php if ($order['billing_address'] != $order['shipping_address']): ?>
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-file-invoice me-2"></i>Billing Address
                                </h5>
                            </div>
                            <div class="card-body">
                                <address class="mb-0">
                                    <?= nl2br(esc($order['billing_address'])) ?>
                                </address>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Order Notes -->
                    <?php if (!empty($order['notes'])): ?>
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-sticky-note me-2"></i>Order Notes
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-0"><?= nl2br(esc($order['notes'])) ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        <?php
                        // Check if order can be cancelled (within 24 hours and pending status)
                        $canCancel = $order['status'] == 'pending';
                        if ($canCancel) {
                            $orderTime = strtotime($order['created_at']);
                            $currentTime = time();
                            $hoursSinceOrder = ($currentTime - $orderTime) / 3600;
                            $canCancel = $hoursSinceOrder <= 24;
                        }
                        ?>
                        <?php if ($canCancel): ?>
                            <button class="btn btn-danger" onclick="cancelOrder('<?= $order['order_number'] ?>')">
                                <i class="fas fa-times me-2"></i>Cancel Order
                            </button>
                        <?php elseif ($order['status'] == 'pending'): ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Cancellation Period Expired</strong><br>
                                Orders can only be cancelled within 24 hours of placement.
                            </div>
                        <?php endif; ?>

                        <a href="<?= base_url('orders') ?>" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Orders
                        </a>

                        <button class="btn btn-outline-secondary" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>Print Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-marker {
        position: absolute;
        left: -22px;
        top: 0;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #dee2e6;
        border: 3px solid #fff;
        box-shadow: 0 0 0 2px #dee2e6;
    }

    .timeline-item.active .timeline-marker {
        background: #198754;
        box-shadow: 0 0 0 2px #198754;
    }

    .timeline-content h6 {
        margin-bottom: 5px;
        font-weight: 600;
    }

    .timeline-item.active .timeline-content h6 {
        color: #198754;
    }

    @media print {

        .btn,
        .alert {
            display: none !important;
        }
    }
</style>

<script>
    function cancelOrder(orderNumber) {
        if (confirm('Are you sure you want to cancel this order?')) {
            // Show loading state
            const cancelBtn = event.target;
            const originalText = cancelBtn.innerHTML;
            cancelBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Cancelling...';
            cancelBtn.disabled = true;

            // Add AJAX call to cancel order
            fetch(`<?= base_url('orders/cancel/') ?>${orderNumber}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Show success message
                        alert(data.message || 'Order cancelled successfully');
                        location.reload();
                    } else {
                        alert(data.message || 'Failed to cancel order');
                        cancelBtn.innerHTML = originalText;
                        cancelBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while canceling the order');
                    cancelBtn.innerHTML = originalText;
                    cancelBtn.disabled = false;
                });
        }
    }

    // Payment initiation function
    function initiatePayment(orderId) {
        // Show loading state
        const payBtn = event.target;
        const originalText = payBtn.innerHTML;
        payBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Initiating Payment...';
        payBtn.disabled = true;

        // Call payment initiation API
        fetch('<?= base_url('payment/initiate') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `order_id=${orderId}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to payment processing page
                window.location.href = data.payment_url;
            } else {
                alert('Failed to initiate payment: ' + data.message);
                payBtn.innerHTML = originalText;
                payBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
            payBtn.innerHTML = originalText;
            payBtn.disabled = false;
        });
    }

    // Auto-hide flash messages after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            if (alert.classList.contains('alert-success')) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        });
    }, 5000);

    // Check for payment parameter in URL and show payment button if needed
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('payment') === 'pending') {
        // Scroll to payment section
        const paymentCard = document.querySelector('.card:has(.btn-primary)');
        if (paymentCard) {
            paymentCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            paymentCard.style.border = '2px solid #0d6efd';
            setTimeout(() => {
                paymentCard.style.border = '';
            }, 3000);
        }
    }
</script>

<?php
function getStatusColor($status)
{
    switch ($status) {
        case 'pending':
            return 'warning';
        case 'confirmed':
        case 'processing':
            return 'info';
        case 'shipped':
            return 'primary';
        case 'delivered':
            return 'success';
        case 'cancelled':
            return 'danger';
        default:
            return 'secondary';
    }
}
?>

<?= $this->endSection() ?>