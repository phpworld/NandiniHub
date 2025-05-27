<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white text-center">
                    <h3 class="mb-0">
                        <i class="fas fa-times-circle fa-2x mb-2"></i><br>
                        Payment Failed
                    </h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h5 class="text-danger">We're sorry, your payment could not be processed.</h5>
                        <p class="text-muted"><?= esc($errorMessage) ?></p>
                    </div>

                    <?php if ($order && $transaction): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-primary">
                                        <i class="fas fa-shopping-bag"></i> Order Information
                                    </h6>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td><strong>Order Number:</strong></td>
                                            <td><?= esc($order['order_number']) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Order Date:</strong></td>
                                            <td><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Amount:</strong></td>
                                            <td><strong>₹<?= number_format($order['total_amount'], 2) ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Payment Status:</strong></td>
                                            <td><span class="badge bg-danger">Failed</span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-danger">
                                        <i class="fas fa-exclamation-triangle"></i> Failure Details
                                    </h6>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td><strong>Transaction ID:</strong></td>
                                            <td><?= esc($transaction['transaction_id']) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Gateway Ref:</strong></td>
                                            <td><?= esc($transaction['gateway_transaction_id'] ?? 'N/A') ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Failure Time:</strong></td>
                                            <td><?= $transaction['processed_at'] ? date('d M Y, h:i A', strtotime($transaction['processed_at'])) : 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Reason:</strong></td>
                                            <td><?= esc($transaction['failure_reason'] ?? 'Payment declined') ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="alert alert-warning mt-4">
                        <h6><i class="fas fa-lightbulb"></i> What can you do?</h6>
                        <ul class="mb-0">
                            <li><strong>Try again:</strong> You can retry the payment with the same or different payment method</li>
                            <li><strong>Check details:</strong> Ensure your card details, bank account balance, and limits are correct</li>
                            <li><strong>Contact bank:</strong> If the issue persists, contact your bank or card issuer</li>
                            <li><strong>Alternative payment:</strong> Try using a different payment method or card</li>
                            <li><strong>Cash on Delivery:</strong> You can change to COD if available</li>
                        </ul>
                    </div>

                    <div class="text-center mt-4">
                        <?php if ($order): ?>
                        <button type="button" class="btn btn-primary" onclick="retryPayment()">
                            <i class="fas fa-redo"></i> Retry Payment
                        </button>
                        <a href="<?= base_url('orders/' . $order['order_number']) ?>" class="btn btn-outline-primary ms-2">
                            <i class="fas fa-eye"></i> View Order
                        </a>
                        <?php endif; ?>
                        <a href="<?= base_url('orders') ?>" class="btn btn-outline-secondary ms-2">
                            <i class="fas fa-list"></i> My Orders
                        </a>
                        <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary ms-2">
                            <i class="fas fa-home"></i> Continue Shopping
                        </a>
                    </div>

                    <div class="mt-4 text-center">
                        <small class="text-muted">
                            Need help? Contact our customer support at 
                            <a href="mailto:support@nandinihub.com">support@nandinihub.com</a> 
                            or call <a href="tel:+919876543210">+91 98765 43210</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function retryPayment() {
    <?php if ($order): ?>
    // Show loading state
    const btn = event.target;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Initiating Payment...';
    btn.disabled = true;
    
    // Call payment initiation API
    fetch('<?= base_url('payment/initiate') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'order_id=<?= $order['id'] ?>&<?= csrf_token() ?>=<?= csrf_hash() ?>'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.payment_url;
        } else {
            alert('Failed to initiate payment: ' + data.message);
            btn.innerHTML = '<i class="fas fa-redo"></i> Retry Payment';
            btn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
        btn.innerHTML = '<i class="fas fa-redo"></i> Retry Payment';
        btn.disabled = false;
    });
    <?php endif; ?>
}
</script>

<?= $this->endSection() ?>
