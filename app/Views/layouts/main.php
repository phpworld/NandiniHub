<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Nandini Hub - Premium Puja Samagri Online' ?></title>
    <meta name="description" content="<?= $meta_description ?? 'Premium quality puja samagri including agarbatti, dhoop, diyas, and all essential items for your spiritual needs.' ?>">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #ff6b35;
            --secondary-color: #f7931e;
            --accent-color: #ffd23f;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
        }

        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color) !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .bg-primary {
            background-color: var(--primary-color) !important;
        }

        .product-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .product-image {
            height: 200px;
            object-fit: cover;
        }

        .price-original {
            text-decoration: line-through;
            color: #6c757d;
        }

        .price-sale {
            color: var(--primary-color);
            font-weight: bold;
        }

        .cart-badge {
            background-color: var(--primary-color);
        }

        footer {
            background-color: var(--dark-color);
            color: white;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 80px 0;
        }

        .category-card {
            transition: all 0.3s ease;
            border-radius: 15px;
            overflow: hidden;
        }

        .category-card:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/') ?>">
                <i class="fas fa-om me-2"></i>Nandini Hub
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/') ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('products') ?>">All Products</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-bs-toggle="dropdown">
                            Categories
                        </a>
                        <ul class="dropdown-menu" id="categoriesMenu">
                            <!-- Categories will be loaded here -->
                        </ul>
                    </li>
                </ul>

                <!-- Search Form -->
                <form class="d-flex me-3" action="<?= base_url('products/search') ?>" method="GET">
                    <input class="form-control me-2" type="search" name="q" placeholder="Search products..." value="<?= esc($keyword ?? '') ?>">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <!-- User Menu -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="<?= base_url('cart') ?>">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill cart-badge" id="cartCount">
                                0
                            </span>
                        </a>
                    </li>

                    <?php if (session()->get('is_logged_in')): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?= esc(session()->get('user_name')) ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?= base_url('profile') ?>">My Profile</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('orders') ?>">My Orders</a></li>
                                <?php
                                $userModel = new \App\Models\UserModel();
                                $user = $userModel->find(session()->get('user_id'));
                                if ($user && $user['role'] === 'admin'):
                                ?>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="<?= base_url('admin/dashboard') ?>">
                                            <i class="fas fa-cog me-2"></i>Admin Panel
                                        </a></li>
                                <?php endif; ?>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="<?= base_url('logout') ?>">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('login') ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('register') ?>">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="mt-5 py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-om me-2"></i>Nandini Hub</h5>
                    <p>Your trusted source for premium quality puja samagri and spiritual items. Bringing divine blessings to your doorstep.</p>
                </div>
                <div class="col-md-2">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?= base_url('/') ?>" class="text-light text-decoration-none">Home</a></li>
                        <li><a href="<?= base_url('products') ?>" class="text-light text-decoration-none">Products</a></li>
                        <li><a href="<?= base_url('about') ?>" class="text-light text-decoration-none">About Us</a></li>
                        <li><a href="<?= base_url('contact') ?>" class="text-light text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Categories</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?= base_url('category/agarbatti-incense') ?>" class="text-light text-decoration-none">Agarbatti & Incense</a></li>
                        <li><a href="<?= base_url('category/dhoop-sambrani') ?>" class="text-light text-decoration-none">Dhoop & Sambrani</a></li>
                        <li><a href="<?= base_url('category/puja-thali-accessories') ?>" class="text-light text-decoration-none">Puja Thali</a></li>
                        <li><a href="<?= base_url('category/diyas-candles') ?>" class="text-light text-decoration-none">Diyas & Candles</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Contact Info</h6>
                    <p><i class="fas fa-phone me-2"></i>+91 12345 67890</p>
                    <p><i class="fas fa-envelope me-2"></i>info@nandinihub.com</p>
                    <div class="mt-3">
                        <a href="#" class="text-light me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p>&copy; 2024 Nandini Hub. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // CSRF Token for AJAX requests
        $.ajaxSetup({
            beforeSend: function(xhr, settings) {
                if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type) && !this.crossDomain) {
                    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
                }
            }
        });

        // Load cart count on page load
        $(document).ready(function() {
            loadCartCount();
            loadCategories();
        });

        function loadCartCount() {
            $.get('<?= base_url('cart/count') ?>', function(data) {
                $('#cartCount').text(data.cartCount || 0);
            });
        }

        function loadCategories() {
            // This would typically load from an API endpoint
            // For now, we'll use static categories
        }

        // Add to cart function
        function addToCart(productId, quantity = 1) {
            $.post('<?= base_url('cart/add') ?>', {
                product_id: productId,
                quantity: quantity
            }, function(response) {
                if (response.success) {
                    $('#cartCount').text(response.cartCount);
                    showAlert('success', response.message);
                } else {
                    showAlert('danger', response.message);
                }
            }).fail(function() {
                showAlert('danger', 'Failed to add item to cart');
            });
        }

        function showAlert(type, message) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            $('main').prepend(alertHtml);

            // Auto dismiss after 3 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 3000);
        }
    </script>

    <?= $this->renderSection('scripts') ?>
</body>

</html>