<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-3">
            <!-- Profile Sidebar -->
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-user-circle fa-4x text-primary mb-3"></i>
                    <h5><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></h5>
                    <p class="text-muted"><?= esc($user['email']) ?></p>
                </div>
            </div>

            <div class="list-group mt-3">
                <a href="<?= base_url('profile') ?>" class="list-group-item list-group-item-action active">
                    <i class="fas fa-user me-2"></i>Profile Information
                </a>
                <a href="<?= base_url('orders') ?>" class="list-group-item list-group-item-action">
                    <i class="fas fa-shopping-bag me-2"></i>My Orders
                </a>
                <a href="<?= base_url('addresses') ?>" class="list-group-item list-group-item-action">
                    <i class="fas fa-map-marker-alt me-2"></i>Addresses
                </a>
                <a href="<?= base_url('wishlist') ?>" class="list-group-item list-group-item-action">
                    <i class="fas fa-heart me-2"></i>Wishlist
                </a>
            </div>
        </div>

        <div class="col-md-9">
            <!-- Profile Form -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Profile Information</h5>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
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

                    <form action="<?= base_url('profile') ?>" method="POST" id="profileForm">
                        <?= csrf_field() ?>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?= isset($errors['first_name']) ? 'is-invalid' : '' ?>"
                                    id="first_name" name="first_name"
                                    value="<?= old('first_name', $user['first_name']) ?>" required>
                                <?php if (isset($errors['first_name'])): ?>
                                    <div class="invalid-feedback"><?= $errors['first_name'] ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?= isset($errors['last_name']) ? 'is-invalid' : '' ?>"
                                    id="last_name" name="last_name"
                                    value="<?= old('last_name', $user['last_name']) ?>" required>
                                <?php if (isset($errors['last_name'])): ?>
                                    <div class="invalid-feedback"><?= $errors['last_name'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                                id="email" name="email"
                                value="<?= old('email', $user['email']) ?>" required>
                            <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback"><?= $errors['email'] ?></div>
                            <?php endif; ?>
                            <div class="form-text">We'll never share your email with anyone else.</div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text">+91</span>
                                <input type="tel" class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>"
                                    id="phone" name="phone"
                                    value="<?= old('phone', $user['phone']) ?>"
                                    placeholder="1234567890" maxlength="10">
                            </div>
                            <?php if (isset($errors['phone'])): ?>
                                <div class="invalid-feedback"><?= $errors['phone'] ?></div>
                            <?php endif; ?>
                            <div class="form-text">Enter 10-digit mobile number without country code.</div>
                        </div>

                        <hr class="my-4">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-map-marker-alt me-2"></i>Address Information
                        </h6>

                        <div class="mb-3">
                            <label for="address" class="form-label">Street Address</label>
                            <textarea class="form-control <?= isset($errors['address']) ? 'is-invalid' : '' ?>"
                                id="address" name="address" rows="3"
                                placeholder="Enter your complete address..."><?= old('address', $user['address']) ?></textarea>
                            <?php if (isset($errors['address'])): ?>
                                <div class="invalid-feedback"><?= $errors['address'] ?></div>
                            <?php endif; ?>
                            <div class="form-text">Include house/flat number, street name, and locality.</div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control <?= isset($errors['city']) ? 'is-invalid' : '' ?>"
                                    id="city" name="city"
                                    value="<?= old('city', $user['city']) ?>"
                                    placeholder="Enter city name">
                                <?php if (isset($errors['city'])): ?>
                                    <div class="invalid-feedback"><?= $errors['city'] ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control <?= isset($errors['state']) ? 'is-invalid' : '' ?>"
                                    id="state" name="state"
                                    value="<?= old('state', $user['state']) ?>"
                                    placeholder="Enter state name">
                                <?php if (isset($errors['state'])): ?>
                                    <div class="invalid-feedback"><?= $errors['state'] ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="pincode" class="form-label">Pincode</label>
                                <input type="text" class="form-control <?= isset($errors['pincode']) ? 'is-invalid' : '' ?>"
                                    id="pincode" name="pincode"
                                    value="<?= old('pincode', $user['pincode']) ?>"
                                    placeholder="123456" maxlength="6" pattern="[0-9]{6}">
                                <?php if (isset($errors['pincode'])): ?>
                                    <div class="invalid-feedback"><?= $errors['pincode'] ?></div>
                                <?php endif; ?>
                                <div class="form-text">6-digit postal code</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary" id="updateBtn">
                                <i class="fas fa-save me-2"></i>Update Profile
                            </button>
                            <a href="<?= base_url('change-password') ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-key me-2"></i>Change Password
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Account Stats -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-shopping-bag fa-2x text-primary mb-2"></i>
                            <h5>0</h5>
                            <small class="text-muted">Total Orders</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-rupee-sign fa-2x text-success mb-2"></i>
                            <h5>₹0.00</h5>
                            <small class="text-muted">Total Spent</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-heart fa-2x text-danger mb-2"></i>
                            <h5>0</h5>
                            <small class="text-muted">Wishlist Items</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Form validation and enhancement
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('profileForm');
        const phoneInput = document.getElementById('phone');
        const pincodeInput = document.getElementById('pincode');

        // Phone number validation
        phoneInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, ''); // Remove non-digits
            if (value.length > 10) {
                value = value.substring(0, 10);
            }
            this.value = value;

            // Validation feedback
            if (value.length === 10) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else if (value.length > 0) {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-valid', 'is-invalid');
            }
        });

        // Pincode validation
        pincodeInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, ''); // Remove non-digits
            if (value.length > 6) {
                value = value.substring(0, 6);
            }
            this.value = value;

            // Validation feedback
            if (value.length === 6) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else if (value.length > 0) {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-valid', 'is-invalid');
            }
        });

        // Name validation (only letters and spaces)
        ['first_name', 'last_name', 'city'].forEach(function(fieldId) {
            const field = document.getElementById(fieldId);
            field.addEventListener('input', function() {
                // Remove numbers and special characters except spaces
                this.value = this.value.replace(/[^a-zA-Z\s]/g, '');

                // Capitalize first letter of each word
                this.value = this.value.replace(/\b\w/g, function(char) {
                    return char.toUpperCase();
                });
            });
        });

        // Form submission
        form.addEventListener('submit', function(e) {
            const firstName = document.getElementById('first_name').value.trim();
            const lastName = document.getElementById('last_name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const pincode = document.getElementById('pincode').value.trim();

            let isValid = true;
            let errors = [];

            // Validate required fields
            if (firstName.length < 2) {
                errors.push('First name must be at least 2 characters long');
                isValid = false;
            }

            if (lastName.length < 2) {
                errors.push('Last name must be at least 2 characters long');
                isValid = false;
            }

            if (!email || !isValidEmail(email)) {
                errors.push('Please enter a valid email address');
                isValid = false;
            }

            // Validate optional fields if provided
            if (phone && phone.length !== 10) {
                errors.push('Phone number must be exactly 10 digits');
                isValid = false;
            }

            if (pincode && pincode.length !== 6) {
                errors.push('Pincode must be exactly 6 digits');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                alert('Please fix the following errors:\n\n' + errors.join('\n'));
                return false;
            }

            // Show loading state
            const submitBtn = document.getElementById('updateBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
            submitBtn.disabled = true;

            // Re-enable button after 3 seconds (in case of error)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
        });

        // Email validation function
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
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
    });
</script>

<?= $this->endSection() ?>