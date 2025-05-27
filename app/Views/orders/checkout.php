<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('cart') ?>">Cart</a></li>
                    <li class="breadcrumb-item active">Checkout</li>
                </ol>
            </nav>

            <h1 class="h2 mb-4">Checkout</h1>

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

            <!-- Validation Errors -->
            <?php if (isset($errors) && !empty($errors)): ?>
                <div class="alert alert-danger">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</h6>
                    <ul class="mb-0">
                        <?php foreach ($errors as $field => $error): ?>
                            <li><strong><?= ucfirst(str_replace('_', ' ', $field)) ?>:</strong> <?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <form action="<?= base_url('checkout') ?>" method="POST" id="checkoutForm">
        <?= csrf_field() ?>

        <div class="row">
            <!-- Checkout Form -->
            <div class="col-lg-8">
                <!-- Shipping Address -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Shipping Address</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" value="<?= esc($user['first_name']) ?>" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" value="<?= esc($user['last_name']) ?>" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Complete Address *</label>
                            <textarea class="form-control <?= isset($errors['shipping_address']) ? 'is-invalid' : '' ?>"
                                id="shipping_address" name="shipping_address" rows="3" required
                                placeholder="Enter your complete shipping address..."><?= old('shipping_address', $user['address']) ?></textarea>
                            <?php if (isset($errors['shipping_address'])): ?>
                                <div class="invalid-feedback"><?= $errors['shipping_address'] ?></div>
                            <?php endif; ?>
                            <div class="form-text">Include house/flat number, street name, locality, and landmark if any.</div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" id="city" value="<?= esc($user['city']) ?>" readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control" id="state" value="<?= esc($user['state']) ?>" readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="pincode" class="form-label">Pincode</label>
                                <input type="text" class="form-control" id="pincode" value="<?= esc($user['pincode']) ?>" readonly>
                            </div>
                        </div>

                        <small class="text-muted">
                            <a href="<?= base_url('profile') ?>">Update your profile</a> to change personal details.
                        </small>
                    </div>
                </div>

                <!-- Billing Address -->
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="same_as_shipping" checked>
                            <label class="form-check-label" for="same_as_shipping">
                                <h5 class="mb-0">Billing address same as shipping</h5>
                            </label>
                        </div>
                    </div>
                    <div class="card-body" id="billing_address_section" style="display: none;">
                        <div class="mb-3">
                            <label for="billing_address_text" class="form-label">Billing Address</label>
                            <textarea class="form-control" id="billing_address_text" rows="3"><?= old('billing_address') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Payment Method</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                            <label class="form-check-label" for="cod">
                                <strong>Cash on Delivery (COD)</strong>
                                <small class="d-block text-muted">Pay when you receive your order</small>
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="online" value="online">
                            <label class="form-check-label" for="online">
                                <strong>Online Payment</strong>
                                <small class="d-block text-muted">Pay securely using UPI, Cards, or Net Banking</small>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Order Notes -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Order Notes (Optional)</h5>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control" name="notes" rows="3" placeholder="Any special instructions for your order..."><?= old('notes') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <!-- Cart Items -->
                        <div class="mb-3">
                            <?php foreach ($cartItems as $item): ?>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0"><?= esc($item['name']) ?></h6>
                                        <small class="text-muted">Qty: <?= $item['quantity'] ?></small>
                                    </div>
                                    <div>
                                        <?php $price = $item['sale_price'] ?? $item['price']; ?>
                                        <span>₹<?= number_format($price * $item['quantity'], 2) ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <hr>

                        <!-- Totals -->
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>₹<?= number_format($cartTotal, 2) ?></span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span class="text-success">
                                <?php if ($cartTotal >= 500): ?>
                                    Free
                                <?php else: ?>
                                    ₹50.00
                                <?php endif; ?>
                            </span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (18% GST):</span>
                            <span>₹<?= number_format($cartTotal * 0.18, 2) ?></span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong class="text-primary">
                                ₹<?= number_format($cartTotal + ($cartTotal >= 500 ? 0 : 50) + ($cartTotal * 0.18), 2) ?>
                            </strong>
                        </div>

                        <!-- Hidden field for billing address -->
                        <input type="hidden" name="billing_address" id="billing_address" value="">

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-credit-card"></i> Place Order
                            </button>
                            <a href="<?= base_url('cart') ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Cart
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Security Features -->
                <div class="card mt-3">
                    <div class="card-body">
                        <h6 class="card-title">Secure Checkout</h6>
                        <div class="row text-center">
                            <div class="col-4">
                                <i class="fas fa-shield-alt text-success mb-2"></i>
                                <small class="d-block">SSL Secure</small>
                            </div>
                            <div class="col-4">
                                <i class="fas fa-lock text-success mb-2"></i>
                                <small class="d-block">Safe Payment</small>
                            </div>
                            <div class="col-4">
                                <i class="fas fa-truck text-success mb-2"></i>
                                <small class="d-block">Fast Delivery</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Handle billing address toggle
    document.getElementById('same_as_shipping').addEventListener('change', function() {
        const billingSection = document.getElementById('billing_address_section');
        const billingAddressField = document.getElementById('billing_address');
        const shippingAddress = document.getElementById('shipping_address').value;

        if (this.checked) {
            billingSection.style.display = 'none';
            billingAddressField.value = shippingAddress;
        } else {
            billingSection.style.display = 'block';
            billingAddressField.value = '';
        }
    });

    // Update billing address when shipping address changes
    document.getElementById('shipping_address').addEventListener('input', function() {
        const sameAsShipping = document.getElementById('same_as_shipping');
        const billingAddressField = document.getElementById('billing_address');

        if (sameAsShipping.checked) {
            billingAddressField.value = this.value;
        }
    });

    // Set initial billing address
    document.addEventListener('DOMContentLoaded', function() {
        const shippingAddress = document.getElementById('shipping_address').value;
        const billingAddressField = document.getElementById('billing_address');
        billingAddressField.value = shippingAddress;
    });

    // Handle billing address textarea
    document.getElementById('billing_address_text').addEventListener('input', function() {
        const billingAddressField = document.getElementById('billing_address');
        billingAddressField.value = this.value;
    });

    // Form validation before submission
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        const shippingAddress = document.getElementById('shipping_address').value.trim();
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');

        if (shippingAddress.length < 10) {
            e.preventDefault();
            alert('Please enter a complete shipping address (at least 10 characters)');
            document.getElementById('shipping_address').focus();
            return false;
        }

        if (!paymentMethod) {
            e.preventDefault();
            alert('Please select a payment method');
            return false;
        }

        // Show loading state
        const submitBtn = document.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing Order...';
        submitBtn.disabled = true;

        // Re-enable button after 10 seconds (in case of error)
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 10000);
    });
</script>
<?= $this->endSection() ?>