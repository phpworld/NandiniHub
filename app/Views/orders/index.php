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

            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 mb-1">My Orders</h1>
                    <p class="text-muted mb-0">Track and manage your orders</p>
                </div>
                <a href="<?= base_url('/') ?>" class="btn btn-primary">
                    <i class="fas fa-shopping-cart me-2"></i>Continue Shopping
                </a>
            </div>

            <?php if (empty($orders)): ?>
                <!-- Empty State -->
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-shopping-bag text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="text-muted mb-3">No Orders Yet</h3>
                    <p class="text-muted mb-4">You haven't placed any orders yet. Start shopping to see your orders here.</p>
                    <a href="<?= base_url('/') ?>" class="btn btn-primary">
                        <i class="fas fa-shopping-cart me-2"></i>Start Shopping
                    </a>
                </div>
            <?php else: ?>
                <!-- Orders List -->
                <div class="row">
                    <?php foreach ($orders as $order): ?>
                        <div class="col-12 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <h6 class="mb-1">Order #<?= esc($order['order_number']) ?></h6>
                                            <small class="text-muted">
                                                <?= date('M d, Y', strtotime($order['created_at'])) ?>
                                            </small>
                                        </div>
                                        <div class="col-md-2">
                                            <span class="badge bg-<?= getStatusColor($order['status']) ?> fs-6">
                                                <?= ucfirst($order['status']) ?>
                                            </span>
                                        </div>
                                        <div class="col-md-2">
                                            <strong>₹<?= number_format($order['total_amount'], 2) ?></strong>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted">
                                                <?= $order['payment_method'] == 'cod' ? 'Cash on Delivery' : 'Online Payment' ?>
                                            </small>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <a href="<?= base_url('orders/' . $order['order_number']) ?>"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i>View
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <?php if (!empty($order['items'])): ?>
                                    <div class="card-body">
                                        <div class="row">
                                            <?php
                                            $displayItems = array_slice($order['items'], 0, 3); // Show first 3 items
                                            $remainingCount = count($order['items']) - 3;
                                            ?>

                                            <?php foreach ($displayItems as $item): ?>
                                                <div class="col-md-4 mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <?php if (!empty($item['product_image'])): ?>
                                                            <img src="<?= base_url('uploads/products/' . $item['product_image']) ?>"
                                                                alt="<?= esc($item['product_name']) ?>"
                                                                class="img-thumbnail me-3"
                                                                style="width: 50px; height: 50px; object-fit: cover;">
                                                        <?php else: ?>
                                                            <div class="bg-light d-flex align-items-center justify-content-center me-3"
                                                                style="width: 50px; height: 50px;">
                                                                <i class="fas fa-image text-muted"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div>
                                                            <h6 class="mb-1 small"><?= esc($item['product_name']) ?></h6>
                                                            <small class="text-muted">Qty: <?= $item['quantity'] ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>

                                            <?php if ($remainingCount > 0): ?>
                                                <div class="col-md-4 mb-3">
                                                    <div class="d-flex align-items-center justify-content-center h-100">
                                                        <div class="text-center">
                                                            <i class="fas fa-plus-circle text-muted mb-2"></i>
                                                            <p class="mb-0 small text-muted">
                                                                +<?= $remainingCount ?> more item<?= $remainingCount > 1 ? 's' : '' ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Order Actions -->
                                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                            <div>
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
                                                    <button class="btn btn-outline-danger btn-sm me-2"
                                                        onclick="cancelOrder('<?= $order['order_number'] ?>')">
                                                        <i class="fas fa-times me-1"></i>Cancel
                                                    </button>
                                                <?php elseif ($order['status'] == 'pending'): ?>
                                                    <small class="text-muted">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Cancellation period expired (24 hours)
                                                    </small>
                                                <?php endif; ?>

                                                <?php if (in_array($order['status'], ['delivered'])): ?>
                                                    <button class="btn btn-outline-warning btn-sm me-2">
                                                        <i class="fas fa-undo me-1"></i>Return
                                                    </button>
                                                <?php endif; ?>
                                            </div>

                                            <div>
                                                <a href="<?= base_url('orders/' . $order['order_number']) ?>"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="fas fa-eye me-1"></i>View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if (isset($pager)): ?>
                    <div class="d-flex justify-content-center mt-4">
                        <?= $pager->links() ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.5em 0.75em;
    }

    .img-thumbnail {
        border: 1px solid #dee2e6;
    }

    @media (max-width: 768px) {
        .card-header .row>div {
            margin-bottom: 0.5rem;
        }

        .card-header .row>div:last-child {
            margin-bottom: 0;
        }
    }
</style>

<script>
    function cancelOrder(orderNumber) {
        if (confirm('Are you sure you want to cancel this order?')) {
            // Show loading state
            const cancelBtn = event.target;
            const originalText = cancelBtn.innerHTML;
            cancelBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Cancelling...';
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