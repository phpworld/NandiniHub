<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'code' => 'WELCOME10',
                'name' => 'Welcome Discount',
                'description' => 'Get 10% off on your first order',
                'type' => 'percentage',
                'value' => 10.00,
                'minimum_order_amount' => 100.00,
                'maximum_discount_amount' => 500.00,
                'usage_limit' => 100,
                'usage_limit_per_customer' => 1,
                'used_count' => 0,
                'valid_from' => date('Y-m-d H:i:s'),
                'valid_until' => date('Y-m-d H:i:s', strtotime('+30 days')),
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'code' => 'SAVE50',
                'name' => 'Flat ₹50 Off',
                'description' => 'Get flat ₹50 off on orders above ₹200',
                'type' => 'fixed_amount',
                'value' => 50.00,
                'minimum_order_amount' => 200.00,
                'maximum_discount_amount' => null,
                'usage_limit' => 50,
                'usage_limit_per_customer' => 2,
                'used_count' => 0,
                'valid_from' => date('Y-m-d H:i:s'),
                'valid_until' => date('Y-m-d H:i:s', strtotime('+15 days')),
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'code' => 'FREESHIP',
                'name' => 'Free Shipping',
                'description' => 'Get free shipping on any order',
                'type' => 'free_shipping',
                'value' => 50.00, // Shipping amount
                'minimum_order_amount' => 0.00,
                'maximum_discount_amount' => null,
                'usage_limit' => null, // Unlimited
                'usage_limit_per_customer' => 5,
                'used_count' => 0,
                'valid_from' => date('Y-m-d H:i:s'),
                'valid_until' => date('Y-m-d H:i:s', strtotime('+60 days')),
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'code' => 'MEGA20',
                'name' => 'Mega Sale 20%',
                'description' => 'Get 20% off on orders above ₹500 (max ₹200 discount)',
                'type' => 'percentage',
                'value' => 20.00,
                'minimum_order_amount' => 500.00,
                'maximum_discount_amount' => 200.00,
                'usage_limit' => 25,
                'usage_limit_per_customer' => 1,
                'used_count' => 0,
                'valid_from' => date('Y-m-d H:i:s'),
                'valid_until' => date('Y-m-d H:i:s', strtotime('+7 days')),
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'code' => 'EXPIRED',
                'name' => 'Expired Coupon',
                'description' => 'This coupon has expired (for testing)',
                'type' => 'percentage',
                'value' => 15.00,
                'minimum_order_amount' => 100.00,
                'maximum_discount_amount' => null,
                'usage_limit' => 10,
                'usage_limit_per_customer' => 1,
                'used_count' => 0,
                'valid_from' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'valid_until' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('coupons')->insertBatch($data);
        
        echo "Sample coupons created successfully!\n";
        echo "Available coupons:\n";
        echo "- WELCOME10: 10% off (first order, min ₹100)\n";
        echo "- SAVE50: ₹50 off (min ₹200)\n";
        echo "- FREESHIP: Free shipping\n";
        echo "- MEGA20: 20% off (min ₹500, max ₹200 discount)\n";
        echo "- EXPIRED: Expired coupon (for testing)\n";
    }
}
