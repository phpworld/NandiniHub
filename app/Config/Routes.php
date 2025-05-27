<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Home routes
$routes->get('/', 'Home::index');

// Product routes
$routes->get('/products', 'ProductController::index');
$routes->get('/products/search', 'ProductController::search');
$routes->get('/category/(:segment)', 'ProductController::category/$1');
$routes->get('/product/(:segment)', 'ProductController::show/$1');

// Cart routes
$routes->get('/cart', 'CartController::index');
$routes->post('/cart/add', 'CartController::add');
$routes->post('/cart/update', 'CartController::update');
$routes->post('/cart/remove', 'CartController::remove');
$routes->post('/cart/clear', 'CartController::clear');
$routes->get('/cart/count', 'CartController::getCartCount');

// Authentication routes
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::processLogin');
$routes->get('/register', 'AuthController::register');
$routes->post('/register', 'AuthController::processRegister');
$routes->get('/logout', 'AuthController::logout');
$routes->get('/profile', 'AuthController::profile');
$routes->post('/profile', 'AuthController::updateProfile');
$routes->get('/change-password', 'AuthController::changePassword');
$routes->post('/change-password', 'AuthController::updatePassword');

// Order routes
$routes->get('/orders', 'OrderController::index');
$routes->get('/orders/(:segment)', 'OrderController::show/$1');
$routes->get('/checkout', 'OrderController::checkout');
$routes->post('/checkout', 'OrderController::processCheckout');
$routes->post('/orders/cancel/(:segment)', 'OrderController::cancelOrder/$1');

// Payment routes
$routes->group('payment', function ($routes) {
    $routes->post('initiate', 'PaymentController::initiate');
    $routes->get('process/(:segment)', 'PaymentController::process/$1');
    $routes->post('callback', 'PaymentController::callback');
    $routes->get('callback', 'PaymentController::callback'); // GET callback for HDFC SmartGateway
    $routes->post('webhook', 'PaymentController::webhook'); // Webhook for HDFC SmartGateway
    $routes->get('success/(:segment)', 'PaymentController::success/$1');
    $routes->get('failure/(:segment)', 'PaymentController::failure/$1');
    $routes->post('verify/(:segment)', 'PaymentController::verify/$1');
    $routes->get('test', 'PaymentController::test'); // Debug route
});

// Coupon routes
$routes->group('coupon', function ($routes) {
    $routes->post('apply', 'CouponController::apply');
    $routes->post('remove', 'CouponController::remove');
    $routes->post('validate', 'CouponController::validateCoupon');
    $routes->get('available', 'CouponController::getAvailable');
    $routes->get('check/(:segment)', 'CouponController::check/$1');
});

// Test routes
$routes->get('test/coupons', function() {
    return view('test/coupon_test');
});

$routes->get('test/coupon-debug', function() {
    $couponModel = new \App\Models\CouponModel();
    $coupons = $couponModel->findAll();

    $output = '<h2>Coupon Debug Information</h2>';
    $output .= '<h3>Database Coupons:</h3>';
    $output .= '<pre>' . print_r($coupons, true) . '</pre>';

    $output .= '<h3>Test Coupon Validation:</h3>';
    if (!empty($coupons)) {
        $testCoupon = $coupons[0];
        $validation = $couponModel->validateCoupon($testCoupon['code'], 250.00, 1);
        $output .= '<pre>' . print_r($validation, true) . '</pre>';

        if ($validation['valid']) {
            $discount = $couponModel->calculateDiscount($validation['coupon'], 250.00);
            $output .= '<h3>Calculated Discount:</h3>';
            $output .= '<pre>Discount Amount: ₹' . $discount . '</pre>';
        }
    }

    return $output;
});

$routes->get('test/coupon-apply/(:segment)', function($code) {
    $couponService = new \App\Libraries\CouponService();
    $cartData = [
        'items' => [
            [
                'id' => 1,
                'name' => 'Test Product',
                'price' => 250.00,
                'quantity' => 1
            ]
        ]
    ];

    $result = $couponService->applyCoupon($code, $cartData, 1);

    $output = '<h2>Coupon Apply Test: ' . $code . '</h2>';
    $output .= '<pre>' . print_r($result, true) . '</pre>';

    return $output;
});

$routes->get('test/cart-data', function() {
    $userId = session()->get('user_id');
    $sessionId = session()->session_id;
    $cartModel = new \App\Models\CartModel();

    $cartItems = $cartModel->getCartItems($userId, $sessionId);
    $cartTotal = $cartModel->getCartTotal($userId, $sessionId);

    $output = '<h2>Cart Data Debug</h2>';
    $output .= '<h3>User ID:</h3><pre>' . ($userId ?? 'Not logged in') . '</pre>';
    $output .= '<h3>Session ID:</h3><pre>' . $sessionId . '</pre>';
    $output .= '<h3>Cart Items:</h3><pre>' . print_r($cartItems, true) . '</pre>';
    $output .= '<h3>Cart Total:</h3><pre>₹' . number_format($cartTotal, 2) . '</pre>';

    // Test coupon controller cart data
    $couponController = new \App\Controllers\CouponController();
    $reflection = new ReflectionClass($couponController);
    $method = $reflection->getMethod('getCartData');
    $method->setAccessible(true);
    $cartData = $method->invoke($couponController);

    $output .= '<h3>Coupon Controller Cart Data:</h3><pre>' . print_r($cartData, true) . '</pre>';

    return $output;
});

$routes->get('test/add-to-cart', function() {
    $sessionId = session()->session_id;
    $cartModel = new \App\Models\CartModel();
    $productModel = new \App\Models\ProductModel();

    // Get first product
    $product = $productModel->first();

    if ($product) {
        $cartData = [
            'product_id' => $product['id'],
            'quantity' => 2,
            'price' => $product['price'],
            'session_id' => $sessionId
        ];

        $result = $cartModel->addToCart($cartData);

        $output = '<h2>Add to Cart Test</h2>';
        $output .= '<h3>Product Added:</h3><pre>' . print_r($product, true) . '</pre>';
        $output .= '<h3>Cart Data:</h3><pre>' . print_r($cartData, true) . '</pre>';
        $output .= '<h3>Result:</h3><pre>' . ($result ? 'Success' : 'Failed') . '</pre>';

        // Check cart after adding
        $cartItems = $cartModel->getCartItems(null, $sessionId);
        $cartTotal = $cartModel->getCartTotal(null, $sessionId);

        $output .= '<h3>Cart Items After Adding:</h3><pre>' . print_r($cartItems, true) . '</pre>';
        $output .= '<h3>Cart Total:</h3><pre>₹' . number_format($cartTotal, 2) . '</pre>';

        $output .= '<p><a href="' . base_url('test/cart-data') . '">Check Cart Data</a></p>';
        $output .= '<p><a href="' . base_url('test/coupon-api-test') . '">Test Coupon API</a></p>';
        $output .= '<p><a href="' . base_url('checkout') . '">Go to Checkout</a></p>';

        return $output;
    } else {
        return '<h2>No products found in database</h2>';
    }
});

$routes->get('test/coupon-api-test', function() {
    $output = '<h2>Coupon API Test</h2>';
    $output .= '<div id="test-results"></div>';
    $output .= '<script>
        // Test coupon API directly
        fetch("' . base_url('coupon/apply') . '", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
                "X-Requested-With": "XMLHttpRequest"
            },
            body: "code=WELCOME10"
        })
        .then(response => {
            console.log("Response status:", response.status);
            return response.text();
        })
        .then(text => {
            console.log("Response text:", text);
            document.getElementById("test-results").innerHTML = "<h3>API Response:</h3><pre>" + text + "</pre>";

            // Try to parse as JSON
            try {
                const data = JSON.parse(text);
                document.getElementById("test-results").innerHTML += "<h3>Parsed JSON:</h3><pre>" + JSON.stringify(data, null, 2) + "</pre>";
            } catch (e) {
                document.getElementById("test-results").innerHTML += "<h3>JSON Parse Error:</h3><pre>" + e.message + "</pre>";
            }
        })
        .catch(error => {
            console.error("Error:", error);
            document.getElementById("test-results").innerHTML = "<h3>Error:</h3><pre>" + error.message + "</pre>";
        });
    </script>';

    return $output;
});

$routes->get('test/coupon-direct', function() {
    // Create a mock request
    $request = \Config\Services::request();
    $request->setMethod('post');

    // Create coupon controller
    $couponController = new \App\Controllers\CouponController();

    // Manually set POST data
    $_POST['code'] = 'WELCOME10';

    try {
        $response = $couponController->apply();

        $output = '<h2>Direct Coupon Controller Test</h2>';
        $output .= '<h3>Response Type:</h3><pre>' . get_class($response) . '</pre>';

        if (method_exists($response, 'getBody')) {
            $output .= '<h3>Response Body:</h3><pre>' . $response->getBody() . '</pre>';
        } else {
            $output .= '<h3>Response:</h3><pre>' . print_r($response, true) . '</pre>';
        }

        return $output;
    } catch (Exception $e) {
        return '<h2>Error:</h2><pre>' . $e->getMessage() . '</pre>';
    }
});

// Review routes
$routes->get('/product/(:segment)/review', 'ReviewController::create/$1');
$routes->post('/reviews', 'ReviewController::store');
$routes->post('/reviews/(:num)/helpful', 'ReviewController::helpful/$1');
$routes->get('/api/products/(:num)/reviews', 'ReviewController::getProductReviews/$1');

// Admin routes
$routes->group('admin', function ($routes) {
    $routes->get('/', 'AdminController::index');
    $routes->get('dashboard', 'AdminController::index');

    // Product management
    $routes->get('products', 'AdminController::products');
    $routes->get('products/create', 'AdminController::createProduct');
    $routes->post('products', 'AdminController::storeProduct');
    $routes->get('products/(:num)/edit', 'AdminController::editProduct/$1');
    $routes->post('products/(:num)', 'AdminController::updateProduct/$1');
    $routes->delete('products/(:num)', 'AdminController::deleteProduct/$1');

    // AJAX Product endpoints
    $routes->post('products/(:num)/toggle-status', 'AdminController::toggleProductStatus/$1');
    $routes->post('products/(:num)/toggle-featured', 'AdminController::toggleProductFeatured/$1');
    $routes->post('products/bulk-action', 'AdminController::bulkProductAction');
    $routes->get('products/export', 'AdminController::exportProducts');

    // Category management
    $routes->get('categories', 'AdminController::categories');
    $routes->get('categories/create', 'AdminController::createCategory');
    $routes->post('categories', 'AdminController::storeCategory');
    $routes->get('categories/(:num)/edit', 'AdminController::editCategory/$1');
    $routes->post('categories/(:num)', 'AdminController::updateCategory/$1');
    $routes->delete('categories/(:num)', 'AdminController::deleteCategory/$1');

    // AJAX Category endpoints
    $routes->post('categories/(:num)/toggle-status', 'AdminController::toggleCategoryStatus/$1');
    $routes->get('categories/(:num)/product-count', 'AdminController::getCategoryProductCount/$1');

    // User management
    $routes->get('users', 'AdminController::users');
    $routes->get('users/(:num)', 'AdminController::viewUser/$1');
    $routes->post('users/(:num)/toggle-status', 'AdminController::toggleUserStatus/$1');

    // Order management
    $routes->get('orders', 'AdminController::orders');
    $routes->get('orders/(:num)', 'AdminController::viewOrder/$1');
    $routes->post('orders/(:num)/status', 'AdminController::updateOrderStatus/$1');

    // Review management
    $routes->get('reviews', 'AdminController::reviews');
    $routes->post('reviews/(:num)/approve', 'AdminController::approveReview/$1');
    $routes->post('reviews/(:num)/reject', 'AdminController::rejectReview/$1');

    // Banner management
    $routes->get('banners', 'AdminController::banners');
    $routes->get('banners/create', 'AdminController::createBanner');
    $routes->post('banners', 'AdminController::storeBanner');
    $routes->get('banners/(:num)/edit', 'AdminController::editBanner/$1');
    $routes->post('banners/(:num)', 'AdminController::updateBanner/$1');
    $routes->delete('banners/(:num)', 'AdminController::deleteBanner/$1');
    $routes->post('banners/(:num)/toggle-status', 'AdminController::toggleBannerStatus/$1');

    // Coupon management
    $routes->get('coupons', 'Admin\CouponController::index');
    $routes->get('coupons/create', 'Admin\CouponController::create');
    $routes->post('coupons/store', 'Admin\CouponController::store');
    $routes->get('coupons/(:num)/edit', 'Admin\CouponController::edit/$1');
    $routes->post('coupons/(:num)/update', 'Admin\CouponController::update/$1');
    $routes->post('coupons/(:num)/delete', 'Admin\CouponController::delete/$1');
    $routes->post('coupons/(:num)/toggle', 'Admin\CouponController::toggle/$1');
    $routes->get('coupons/(:num)/stats', 'Admin\CouponController::stats/$1');

    // Settings
    $routes->get('settings', 'AdminController::settings');
    $routes->post('settings', 'AdminController::updateSettings');
});
