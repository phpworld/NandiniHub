<?php

namespace App\Libraries;

use App\Models\CouponModel;
use App\Models\CouponUsageModel;

class CouponService
{
    private CouponModel $couponModel;
    private CouponUsageModel $usageModel;

    public function __construct()
    {
        $this->couponModel = new CouponModel();
        $this->usageModel = new CouponUsageModel();
    }

    /**
     * Apply coupon to cart/order
     */
    public function applyCoupon(string $code, array $cartData, int $userId = null): array
    {
        // Calculate cart total
        $cartTotal = $this->calculateCartTotal($cartData);

        // Validate coupon
        $validation = $this->couponModel->validateCoupon($code, $cartTotal, $userId);
        
        if (!$validation['valid']) {
            return [
                'success' => false,
                'message' => $validation['message']
            ];
        }

        $coupon = $validation['coupon'];
        $discountAmount = $this->couponModel->calculateDiscount($coupon, $cartTotal);

        // Calculate final amounts
        $finalTotal = $cartTotal - $discountAmount;
        
        return [
            'success' => true,
            'coupon' => $coupon,
            'discount_amount' => $discountAmount,
            'cart_total' => $cartTotal,
            'final_total' => $finalTotal,
            'savings' => $discountAmount,
            'message' => $this->getSuccessMessage($coupon, $discountAmount)
        ];
    }

    /**
     * Remove coupon from cart
     */
    public function removeCoupon(array $cartData): array
    {
        $cartTotal = $this->calculateCartTotal($cartData);
        
        return [
            'success' => true,
            'cart_total' => $cartTotal,
            'final_total' => $cartTotal,
            'discount_amount' => 0,
            'coupon' => null,
            'message' => 'Coupon removed successfully'
        ];
    }

    /**
     * Process coupon usage after order completion
     */
    public function processCouponUsage(int $couponId, int $userId, int $orderId, float $discountAmount, float $orderAmount): bool
    {
        try {
            // Record usage
            $usageRecorded = $this->usageModel->recordUsage($couponId, $userId, $orderId, $discountAmount, $orderAmount);
            
            if ($usageRecorded) {
                // Increment coupon usage count
                $this->couponModel->incrementUsage($couponId);
                return true;
            }
            
            return false;
        } catch (\Exception $e) {
            log_message('error', 'Failed to process coupon usage: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get available coupons for user
     */
    public function getAvailableCoupons(int $userId = null, float $orderAmount = 0): array
    {
        $coupons = $this->couponModel->getActiveCoupons();
        $availableCoupons = [];

        foreach ($coupons as $coupon) {
            $validation = $this->couponModel->validateCoupon($coupon['code'], $orderAmount, $userId);
            
            if ($validation['valid']) {
                $coupon['potential_discount'] = $this->couponModel->calculateDiscount($coupon, $orderAmount);
                $availableCoupons[] = $coupon;
            }
        }

        return $availableCoupons;
    }

    /**
     * Calculate cart total from cart data
     */
    private function calculateCartTotal(array $cartData): float
    {
        $total = 0;
        
        if (isset($cartData['items'])) {
            foreach ($cartData['items'] as $item) {
                $total += $item['price'] * $item['quantity'];
            }
        } elseif (isset($cartData['total'])) {
            $total = $cartData['total'];
        }

        return (float) $total;
    }

    /**
     * Get success message for applied coupon
     */
    private function getSuccessMessage(array $coupon, float $discountAmount): string
    {
        switch ($coupon['type']) {
            case 'percentage':
                return "Coupon applied! You saved ₹" . number_format($discountAmount, 2) . " ({$coupon['value']}% off)";
            case 'fixed_amount':
                return "Coupon applied! You saved ₹" . number_format($discountAmount, 2);
            case 'free_shipping':
                return "Coupon applied! You get free shipping";
            default:
                return "Coupon applied successfully!";
        }
    }

    /**
     * Validate coupon code format
     */
    public function validateCouponCode(string $code): bool
    {
        return preg_match('/^[A-Z0-9]{3,50}$/', strtoupper($code));
    }

    /**
     * Generate coupon code suggestions
     */
    public function generateCodeSuggestions(string $baseName = ''): array
    {
        $suggestions = [];
        $baseName = strtoupper(preg_replace('/[^A-Z0-9]/', '', $baseName));
        
        if (strlen($baseName) > 0) {
            $suggestions[] = $baseName . date('Y');
            $suggestions[] = $baseName . date('m');
            $suggestions[] = $baseName . rand(10, 99);
        }
        
        $suggestions[] = 'SAVE' . rand(10, 99);
        $suggestions[] = 'DEAL' . date('md');
        $suggestions[] = 'OFFER' . rand(100, 999);
        
        return array_slice($suggestions, 0, 5);
    }

    /**
     * Get coupon statistics
     */
    public function getCouponStatistics(int $couponId): array
    {
        $coupon = $this->couponModel->find($couponId);
        if (!$coupon) {
            return [];
        }

        $stats = $this->usageModel->getCouponStats($couponId);
        
        return [
            'coupon' => $coupon,
            'usage_stats' => $stats,
            'usage_percentage' => $coupon['usage_limit'] ? 
                round(($coupon['used_count'] / $coupon['usage_limit']) * 100, 2) : 0,
            'remaining_uses' => $coupon['usage_limit'] ? 
                max(0, $coupon['usage_limit'] - $coupon['used_count']) : 'Unlimited'
        ];
    }

    /**
     * Check if coupon is about to expire
     */
    public function isExpiringSoon(array $coupon, int $days = 7): bool
    {
        if (!$coupon['valid_until']) {
            return false;
        }

        $expiryDate = strtotime($coupon['valid_until']);
        $warningDate = strtotime("+{$days} days");
        
        return $expiryDate <= $warningDate;
    }
}
