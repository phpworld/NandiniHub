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

    // Settings
    $routes->get('settings', 'AdminController::settings');
    $routes->post('settings', 'AdminController::updateSettings');
});
