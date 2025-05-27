<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-credit-card"></i> Processing Payment</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h5 class="mt-3">Redirecting to Payment Gateway...</h5>
                        <p class="text-muted">Please wait while we redirect you to the secure payment page.</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Order Details</h6>
                                    <p class="mb-1"><strong>Order Number:</strong> <?= esc($order['order_number']) ?></p>
                                    <p class="mb-1"><strong>Amount:</strong> ₹<?= number_format($transaction['amount'], 2) ?></p>
                                    <p class="mb-0"><strong>Transaction ID:</strong> <?= esc($transaction['transaction_id']) ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Payment Information</h6>
                                    <p class="mb-1"><strong>Gateway:</strong> HDFC Bank</p>
                                    <p class="mb-1"><strong>Currency:</strong> INR</p>
                                    <p class="mb-0"><strong>Status:</strong> <span class="badge bg-warning">Processing</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Important:</strong> Do not close this window or press the back button during payment processing.
                        </div>
                    </div>

                    <!-- Auto-submit form to payment gateway -->
                    <form id="paymentForm" action="<?= esc($paymentRequest['gateway_url']) ?>" method="POST" style="display: none;">
                        <input type="hidden" name="encRequest" value="<?= esc($paymentRequest['encRequest']) ?>">
                        <input type="hidden" name="access_code" value="<?= esc($paymentRequest['access_code']) ?>">
                    </form>

                    <div class="mt-3">
                        <button type="button" class="btn btn-primary" onclick="submitPayment()">
                            <i class="fas fa-arrow-right"></i> Proceed to Payment
                        </button>
                        <a href="<?= base_url('orders/' . $order['order_number']) ?>" class="btn btn-secondary ms-2">
                            <i class="fas fa-arrow-left"></i> Back to Order
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function submitPayment() {
    // Show loading state
    document.querySelector('.btn-primary').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Redirecting...';
    document.querySelector('.btn-primary').disabled = true;
    
    // Submit form after a short delay
    setTimeout(function() {
        document.getElementById('paymentForm').submit();
    }, 1000);
}

// Auto-submit after 3 seconds
setTimeout(function() {
    submitPayment();
}, 3000);
</script>

<?= $this->endSection() ?>
