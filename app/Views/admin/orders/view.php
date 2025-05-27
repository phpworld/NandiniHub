<?= $this->extend('admin/layout/main') ?>

<?= $this->section('breadcrumb') ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('admin/orders') ?>">Orders</a></li>
        <li class="breadcrumb-item active">Order #<?= esc($order['order_number']) ?></li>
    </ol>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-receipt me-2"></i>Order #<?= esc($order['order_number']) ?></h2>
    <div class="d-flex gap-2">
        <a href="<?= base_url('admin/orders') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Orders
        </a>
        <button class="btn btn-outline-primary" onclick="window.print()">
            <i class="fas fa-print me-1"></i>Print
        </button>
    </div>
</div>

<div class="row">
    <!-- Order Information -->
    <div class="col-lg-8">
        <!-- Order Details Card -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Order Details</h5>
                <span class="badge bg-<?= getOrderStatusColor($order['status']) ?> fs-6">
                    <?= ucfirst($order['status']) ?>
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Order Information</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td><strong>Order Number:</strong></td>
                                <td><?= esc($order['order_number']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Order Date:</strong></td>
                                <td><?= date('M j, Y g:i A', strtotime($order['created_at'])) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Payment Method:</strong></td>
                                <td><?= $order['payment_method'] === 'cod' ? 'Cash on Delivery' : 'Online Payment' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Payment Status:</strong></td>
                                <td>
                                    <span class="badge bg-<?= $order['payment_status'] === 'paid' ? 'success' : 'warning' ?>">
                                        <?= ucfirst($order['payment_status'] ?? 'pending') ?>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Customer Information</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td><?= esc($order['first_name'] . ' ' . $order['last_name']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td><?= esc($order['email']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Phone:</strong></td>
                                <td><?= esc($order['phone'] ?? 'N/A') ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Order Items</h5>
            </div>
            <div class="card-body">
                <?php if (empty($orderItems)): ?>
                    <p class="text-muted text-center py-3">No items found for this order.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
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
                                                         class="me-3" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                                <?php else: ?>
                                                    <div class="me-3 bg-light d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px; border-radius: 5px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <strong><?= esc($item['product_name']) ?></strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?= esc($item['product_sku'] ?? 'N/A') ?></small>
                                        </td>
                                        <td>₹<?= number_format($item['price'], 2) ?></td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td><strong>₹<?= number_format($item['total'], 2) ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Addresses -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Shipping Address</h6>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($order['shipping_address'])): ?>
                            <?php $shippingAddress = json_decode($order['shipping_address'], true); ?>
                            <address class="mb-0">
                                <?= esc($shippingAddress['name'] ?? '') ?><br>
                                <?= esc($shippingAddress['address'] ?? '') ?><br>
                                <?= esc($shippingAddress['city'] ?? '') ?>, <?= esc($shippingAddress['state'] ?? '') ?><br>
                                <?= esc($shippingAddress['pincode'] ?? '') ?><br>
                                <?php if (!empty($shippingAddress['phone'])): ?>
                                    Phone: <?= esc($shippingAddress['phone']) ?>
                                <?php endif; ?>
                            </address>
                        <?php else: ?>
                            <p class="text-muted mb-0">No shipping address provided</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Billing Address</h6>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($order['billing_address'])): ?>
                            <?php $billingAddress = json_decode($order['billing_address'], true); ?>
                            <address class="mb-0">
                                <?= esc($billingAddress['name'] ?? '') ?><br>
                                <?= esc($billingAddress['address'] ?? '') ?><br>
                                <?= esc($billingAddress['city'] ?? '') ?>, <?= esc($billingAddress['state'] ?? '') ?><br>
                                <?= esc($billingAddress['pincode'] ?? '') ?><br>
                                <?php if (!empty($billingAddress['phone'])): ?>
                                    Phone: <?= esc($billingAddress['phone']) ?>
                                <?php endif; ?>
                            </address>
                        <?php else: ?>
                            <p class="text-muted mb-0">Same as shipping address</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Summary & Actions -->
    <div class="col-lg-4">
        <!-- Order Summary -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Order Summary</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td>Subtotal:</td>
                        <td class="text-end">₹<?= number_format(($order['total_amount'] - ($order['shipping_amount'] ?? 0) - ($order['tax_amount'] ?? 0) + ($order['discount_amount'] ?? 0)), 2) ?></td>
                    </tr>
                    <?php if (!empty($order['discount_amount']) && $order['discount_amount'] > 0): ?>
                        <tr>
                            <td>Discount:</td>
                            <td class="text-end text-success">-₹<?= number_format($order['discount_amount'], 2) ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($order['tax_amount']) && $order['tax_amount'] > 0): ?>
                        <tr>
                            <td>Tax:</td>
                            <td class="text-end">₹<?= number_format($order['tax_amount'], 2) ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($order['shipping_amount']) && $order['shipping_amount'] > 0): ?>
                        <tr>
                            <td>Shipping:</td>
                            <td class="text-end">₹<?= number_format($order['shipping_amount'], 2) ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr class="border-top">
                        <td><strong>Total:</strong></td>
                        <td class="text-end"><strong>₹<?= number_format($order['total_amount'], 2) ?></strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Order Actions -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Order Actions</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= base_url('admin/orders/' . $order['id'] . '/status') ?>">
                    <div class="mb-3">
                        <label for="status" class="form-label">Update Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                            <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                            <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                            <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-1"></i>Update Status
                    </button>
                </form>
                
                <?php if (!empty($order['notes'])): ?>
                    <div class="mt-3">
                        <h6>Order Notes</h6>
                        <p class="text-muted small"><?= esc($order['notes']) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?php
// Helper function for order status colors
function getOrderStatusColor($status) {
    switch ($status) {
        case 'pending':
            return 'warning';
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
