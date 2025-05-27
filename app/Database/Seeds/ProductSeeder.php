<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Agarbatti & Incense
            [
                'category_id'       => 1,
                'name'              => 'Nag Champa Agarbatti',
                'slug'              => 'nag-champa-agarbatti',
                'description'       => 'Premium quality Nag Champa incense sticks made from natural ingredients. Perfect for daily puja and meditation.',
                'short_description' => 'Premium Nag Champa incense sticks for daily worship',
                'price'             => 45.00,
                'sale_price'        => 40.00,
                'sku'               => 'AGR001',
                'stock_quantity'    => 100,
                'weight'            => 0.15,
                'is_featured'       => 1,
                'is_active'         => 1,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'category_id'       => 1,
                'name'              => 'Sandalwood Agarbatti',
                'slug'              => 'sandalwood-agarbatti',
                'description'       => 'Pure sandalwood incense sticks with authentic fragrance. Ideal for creating peaceful atmosphere during prayers.',
                'short_description' => 'Pure sandalwood incense sticks with authentic fragrance',
                'price'             => 65.00,
                'sale_price'        => null,
                'sku'               => 'AGR002',
                'stock_quantity'    => 75,
                'weight'            => 0.15,
                'is_featured'       => 1,
                'is_active'         => 1,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'category_id'       => 1,
                'name'              => 'Mogra Agarbatti',
                'slug'              => 'mogra-agarbatti',
                'description'       => 'Delicate mogra (jasmine) fragrance incense sticks. Perfect for evening prayers and special occasions.',
                'short_description' => 'Delicate mogra fragrance incense sticks',
                'price'             => 35.00,
                'sale_price'        => 30.00,
                'sku'               => 'AGR003',
                'stock_quantity'    => 120,
                'weight'            => 0.15,
                'is_featured'       => 0,
                'is_active'         => 1,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],

            // Dhoop & Sambrani
            [
                'category_id'       => 2,
                'name'              => 'Traditional Dhoop Sticks',
                'slug'              => 'traditional-dhoop-sticks',
                'description'       => 'Handmade traditional dhoop sticks with natural herbs and resins. Creates thick aromatic smoke perfect for puja.',
                'short_description' => 'Handmade traditional dhoop sticks with natural herbs',
                'price'             => 55.00,
                'sale_price'        => null,
                'sku'               => 'DHP001',
                'stock_quantity'    => 80,
                'weight'            => 0.20,
                'is_featured'       => 1,
                'is_active'         => 1,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'category_id'       => 2,
                'name'              => 'Sambrani Cups',
                'slug'              => 'sambrani-cups',
                'description'       => 'Ready-to-use sambrani cups made from pure benzoin resin. Just light and enjoy the divine fragrance.',
                'short_description' => 'Ready-to-use sambrani cups made from pure benzoin resin',
                'price'             => 25.00,
                'sale_price'        => 22.00,
                'sku'               => 'SMB001',
                'stock_quantity'    => 150,
                'weight'            => 0.10,
                'is_featured'       => 0,
                'is_active'         => 1,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],

            // Puja Thali & Accessories
            [
                'category_id'       => 3,
                'name'              => 'Brass Puja Thali Set',
                'slug'              => 'brass-puja-thali-set',
                'description'       => 'Complete brass puja thali set with diya, incense holder, small bowls and decorative elements. Perfect for daily worship.',
                'short_description' => 'Complete brass puja thali set with all accessories',
                'price'             => 450.00,
                'sale_price'        => 399.00,
                'sku'               => 'PTH001',
                'stock_quantity'    => 25,
                'weight'            => 0.80,
                'is_featured'       => 1,
                'is_active'         => 1,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'category_id'       => 3,
                'name'              => 'Silver Plated Puja Thali',
                'slug'              => 'silver-plated-puja-thali',
                'description'       => 'Elegant silver plated puja thali with intricate designs. Ideal for special occasions and festivals.',
                'short_description' => 'Elegant silver plated puja thali with intricate designs',
                'price'             => 850.00,
                'sale_price'        => null,
                'sku'               => 'PTH002',
                'stock_quantity'    => 15,
                'weight'            => 1.20,
                'is_featured'       => 1,
                'is_active'         => 1,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],

            // Diyas & Candles
            [
                'category_id'       => 4,
                'name'              => 'Clay Diyas (Pack of 12)',
                'slug'              => 'clay-diyas-pack-12',
                'description'       => 'Traditional handmade clay diyas perfect for Diwali and daily puja. Pack contains 12 pieces.',
                'short_description' => 'Traditional handmade clay diyas - pack of 12',
                'price'             => 60.00,
                'sale_price'        => 50.00,
                'sku'               => 'DYA001',
                'stock_quantity'    => 200,
                'weight'            => 0.50,
                'is_featured'       => 1,
                'is_active'         => 1,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('products')->insertBatch($data);
    }
}
