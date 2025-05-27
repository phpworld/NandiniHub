<?php

namespace App\Controllers;

use App\Libraries\CouponService;
use App\Models\CouponModel;

class CouponController extends BaseController
{
    private CouponService $couponService;
    private CouponModel $couponModel;

    public function __construct()
    {
        $this->couponService = new CouponService();
        $this->couponModel = new CouponModel();
    }

    /**
     * Apply coupon (AJAX)
     */
    public function apply()
    {
        // Debug logging
        log_message('debug', 'CouponController::apply called');
        log_message('debug', 'Request method: ' . $this->request->getMethod());
        log_message('debug', 'Is AJAX: ' . ($this->request->isAJAX() ? 'yes' : 'no'));

        $code = $this->request->getPost('code');
        $userId = session()->get('user_id');

        log_message('debug', 'Coupon code: ' . ($code ?? 'null'));
        log_message('debug', 'User ID: ' . ($userId ?? 'null'));

        if (empty($code)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please enter a coupon code'
            ]);
        }

        // Get cart data from session
        $cartData = $this->getCartData();
        log_message('debug', 'Cart data retrieved: ' . json_encode($cartData));

        // Check if cart has items or total
        if (empty($cartData['items']) && !isset($cartData['total'])) {
            log_message('debug', 'Cart is empty - no items or total');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Your cart is empty. Please add items to cart first.'
            ]);
        }

        // Apply coupon
        $result = $this->couponService->applyCoupon($code, $cartData, $userId);
        log_message('debug', 'Coupon service result: ' . json_encode($result));

        if ($result['success']) {
            // Store coupon in session
            session()->set('applied_coupon', [
                'code' => $code,
                'coupon_id' => $result['coupon']['id'],
                'discount_amount' => $result['discount_amount'],
                'coupon_data' => $result['coupon']
            ]);
            log_message('debug', 'Coupon stored in session');
        }

        log_message('debug', 'Returning JSON response');
        return $this->response->setJSON($result);
    }

    /**
     * Remove coupon (AJAX)
     */
    public function remove()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        // Remove coupon from session
        session()->remove('applied_coupon');

        $cartData = $this->getCartData();
        $result = $this->couponService->removeCoupon($cartData);

        return $this->response->setJSON($result);
    }

    /**
     * Validate coupon (AJAX)
     */
    public function validateCoupon()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $code = $this->request->getPost('code');
        $userId = session()->get('user_id');

        if (empty($code)) {
            return $this->response->setJSON([
                'valid' => false,
                'message' => 'Please enter a coupon code'
            ]);
        }

        $cartData = $this->getCartData();
        $cartTotal = 0;

        if (!empty($cartData['items'])) {
            foreach ($cartData['items'] as $item) {
                $cartTotal += $item['price'] * $item['quantity'];
            }
        }

        $validation = $this->couponModel->validateCoupon($code, $cartTotal, $userId);

        if ($validation['valid']) {
            $discountAmount = $this->couponModel->calculateDiscount($validation['coupon'], $cartTotal);

            return $this->response->setJSON([
                'valid' => true,
                'coupon' => $validation['coupon'],
                'discount_amount' => $discountAmount,
                'message' => 'Coupon is valid'
            ]);
        }

        return $this->response->setJSON($validation);
    }

    /**
     * Get available coupons for user (AJAX)
     */
    public function getAvailable()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $userId = session()->get('user_id');
        $cartData = $this->getCartData();
        $cartTotal = 0;

        if (!empty($cartData['items'])) {
            foreach ($cartData['items'] as $item) {
                $cartTotal += $item['price'] * $item['quantity'];
            }
        }

        $coupons = $this->couponService->getAvailableCoupons($userId, $cartTotal);

        return $this->response->setJSON([
            'success' => true,
            'coupons' => $coupons,
            'cart_total' => $cartTotal
        ]);
    }

    /**
     * Get cart data from database
     */
    private function getCartData(): array
    {
        $userId = session()->get('user_id');
        $sessionId = session()->session_id;
        $cartModel = new \App\Models\CartModel();

        // Get cart items from database
        $cartItems = $cartModel->getCartItems($userId, $sessionId);
        $cartData = ['items' => []];

        if (!empty($cartItems)) {
            foreach ($cartItems as $item) {
                $price = $item['sale_price'] ?? $item['price'];
                $cartData['items'][] = [
                    'id' => $item['product_id'],
                    'name' => $item['name'],
                    'price' => $price,
                    'quantity' => $item['quantity']
                ];
            }
        } else {
            // If no items, get total directly
            $cartTotal = $cartModel->getCartTotal($userId, $sessionId);
            if ($cartTotal > 0) {
                $cartData['total'] = $cartTotal;
            }
        }

        return $cartData;
    }

    /**
     * Check coupon by code (for quick lookup)
     */
    public function check($code = null)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        if (!$code) {
            $code = $this->request->getGet('code');
        }

        if (empty($code)) {
            return $this->response->setJSON([
                'found' => false,
                'message' => 'No coupon code provided'
            ]);
        }

        $coupon = $this->couponModel->getByCode($code);

        if (!$coupon) {
            return $this->response->setJSON([
                'found' => false,
                'message' => 'Coupon not found'
            ]);
        }

        // Return basic coupon info (without sensitive data)
        return $this->response->setJSON([
            'found' => true,
            'coupon' => [
                'code' => $coupon['code'],
                'name' => $coupon['name'],
                'description' => $coupon['description'],
                'type' => $coupon['type'],
                'value' => $coupon['value'],
                'minimum_order_amount' => $coupon['minimum_order_amount'],
                'is_active' => $coupon['is_active']
            ]
        ]);
    }
}
