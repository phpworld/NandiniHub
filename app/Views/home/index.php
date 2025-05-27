<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Premium Puja Samagri Online</h1>
                <p class="lead mb-4">Discover authentic and high-quality puja items including agarbatti, dhoop, diyas, and all essential spiritual accessories for your divine worship.</p>
                <div class="d-flex gap-3">
                    <a href="<?= base_url('products') ?>" class="btn btn-light btn-lg">Shop Now</a>
                    <a href="<?= base_url('category/agarbatti-incense') ?>" class="btn btn-outline-light btn-lg">View Agarbatti</a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://via.placeholder.com/500x400/ff6b35/ffffff?text=Puja+Samagri" alt="Puja Samagri" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Shop by Categories</h2>
            <p class="text-muted">Explore our wide range of spiritual and puja items</p>
        </div>

        <div class="row g-4">
            <?php foreach ($categories as $category): ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card category-card h-100 text-center">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <?php
                                $icons = [
                                    'agarbatti-incense' => 'fas fa-fire',
                                    'dhoop-sambrani' => 'fas fa-smoke',
                                    'puja-thali-accessories' => 'fas fa-circle',
                                    'diyas-candles' => 'fas fa-candle-holder',
                                    'flowers-garlands' => 'fas fa-seedling',
                                    'puja-oils-ghee' => 'fas fa-oil-can',
                                    'idols-statues' => 'fas fa-praying-hands',
                                    'puja-books-mantras' => 'fas fa-book'
                                ];
                                $icon = $icons[$category['slug']] ?? 'fas fa-star';
                                ?>
                                <i class="<?= $icon ?> fa-3x text-primary"></i>
                            </div>
                            <h5 class="card-title"><?= esc($category['name']) ?></h5>
                            <p class="card-text text-muted"><?= esc($category['description']) ?></p>
                            <a href="<?= base_url('category/' . esc($category['slug'])) ?>" class="btn btn-primary">Explore</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Featured Products</h2>
            <p class="text-muted">Handpicked premium items for your spiritual needs</p>
        </div>

        <div class="row g-4">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card product-card h-100">
                        <div class="position-relative">
                            <img src="<?= $product['image'] ? esc($product['image']) : 'https://via.placeholder.com/300x200/f8f9fa/6c757d?text=' . urlencode($product['name']) ?>"
                                 class="card-img-top product-image" alt="<?= esc($product['name']) ?>">

                            <?php if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                                <span class="position-absolute top-0 start-0 badge bg-danger m-2">
                                    <?= round((($product['price'] - $product['sale_price']) / $product['price']) * 100) ?>% OFF
                                </span>
                            <?php endif; ?>

                            <?php if ($product['is_featured']): ?>
                                <span class="position-absolute top-0 end-0 badge bg-warning m-2">
                                    <i class="fas fa-star"></i> Featured
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title"><?= esc($product['name']) ?></h6>
                            <p class="card-text text-muted small flex-grow-1"><?= esc($product['short_description']) ?></p>

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <?php if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                                            <span class="price-original small">₹<?= number_format($product['price'], 2) ?></span>
                                            <span class="price-sale">₹<?= number_format($product['sale_price'], 2) ?></span>
                                        <?php else: ?>
                                            <span class="price-sale">₹<?= number_format($product['price'], 2) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <small class="text-muted">Stock: <?= $product['stock_quantity'] ?></small>
                                </div>

                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary btn-sm" onclick="addToCart(<?= $product['id'] ?>)">
                                        <i class="fas fa-cart-plus"></i> Add to Cart
                                    </button>
                                    <a href="<?= base_url('product/' . esc($product['slug'])) ?>" class="btn btn-outline-primary btn-sm">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-5">
            <a href="<?= base_url('products') ?>" class="btn btn-primary btn-lg">View All Products</a>
        </div>
    </div>
</section>

<!-- Latest Products Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Latest Arrivals</h2>
            <p class="text-muted">Newest additions to our spiritual collection</p>
        </div>

        <div class="row g-4">
            <?php foreach (array_slice($latestProducts, 0, 8) as $product): ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card product-card h-100">
                        <div class="position-relative">
                            <img src="<?= $product['image'] ? esc($product['image']) : 'https://via.placeholder.com/300x200/f8f9fa/6c757d?text=' . urlencode($product['name']) ?>"
                                 class="card-img-top product-image" alt="<?= esc($product['name']) ?>">

                            <span class="position-absolute top-0 start-0 badge bg-success m-2">New</span>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title"><?= esc($product['name']) ?></h6>
                            <p class="card-text text-muted small flex-grow-1"><?= esc($product['short_description']) ?></p>

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <?php if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                                            <span class="price-original small">₹<?= number_format($product['price'], 2) ?></span>
                                            <span class="price-sale">₹<?= number_format($product['sale_price'], 2) ?></span>
                                        <?php else: ?>
                                            <span class="price-sale">₹<?= number_format($product['price'], 2) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <small class="text-muted">Stock: <?= $product['stock_quantity'] ?></small>
                                </div>

                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary btn-sm" onclick="addToCart(<?= $product['id'] ?>)">
                                        <i class="fas fa-cart-plus"></i> Add to Cart
                                    </button>
                                    <a href="<?= base_url('product/' . esc($product['slug'])) ?>" class="btn btn-outline-primary btn-sm">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3">
                <i class="fas fa-shipping-fast fa-3x mb-3"></i>
                <h5>Free Shipping</h5>
                <p>Free delivery on orders above ₹500</p>
            </div>
            <div class="col-md-3">
                <i class="fas fa-shield-alt fa-3x mb-3"></i>
                <h5>Authentic Products</h5>
                <p>100% genuine and blessed items</p>
            </div>
            <div class="col-md-3">
                <i class="fas fa-headset fa-3x mb-3"></i>
                <h5>24/7 Support</h5>
                <p>Round the clock customer service</p>
            </div>
            <div class="col-md-3">
                <i class="fas fa-undo fa-3x mb-3"></i>
                <h5>Easy Returns</h5>
                <p>Hassle-free return policy</p>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
