<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title' => 'Premium Puja Samagri Online',
                'subtitle' => 'Discover authentic and high-quality puja items including agarbatti, dhoop, diyas, and all essential spiritual accessories for your divine worship.',
                'description' => 'Experience the divine with our carefully curated collection of traditional puja items.',
                'button_text' => 'Shop Now',
                'button_link' => '/products',
                'button_text_2' => 'View Agarbatti',
                'button_link_2' => '/category/agarbatti-incense',
                'background_color' => '#ff6b35',
                'text_color' => '#ffffff',
                'is_active' => 1,
                'sort_order' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Sacred Agarbatti Collection',
                'subtitle' => 'Premium incense sticks for your daily prayers and meditation',
                'description' => 'Experience the divine with our carefully curated collection of traditional agarbatti.',
                'button_text' => 'Shop Agarbatti',
                'button_link' => '/category/agarbatti-incense',
                'button_text_2' => 'View All',
                'button_link_2' => '/products',
                'background_color' => '#ff6b35',
                'text_color' => '#ffffff',
                'is_active' => 1,
                'sort_order' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Divine Puja Thali Sets',
                'subtitle' => 'Complete brass and silver plated thali sets for worship',
                'description' => 'Elegant and traditional puja thali sets crafted with precision and devotion.',
                'button_text' => 'Shop Thali Sets',
                'button_link' => '/category/puja-thali-accessories',
                'button_text_2' => 'Learn More',
                'button_link_2' => '/products',
                'background_color' => '#f7931e',
                'text_color' => '#ffffff',
                'is_active' => 1,
                'sort_order' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Traditional Dhoop & Sambrani',
                'subtitle' => 'Authentic dhoop sticks and sambrani cups for sacred atmosphere',
                'description' => 'Create a divine ambiance with our handmade dhoop and pure sambrani.',
                'button_text' => 'Shop Dhoop',
                'button_link' => '/category/dhoop-sambrani',
                'button_text_2' => 'Explore',
                'button_link_2' => '/products',
                'background_color' => '#27ae60',
                'text_color' => '#ffffff',
                'is_active' => 1,
                'sort_order' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('banners')->insertBatch($data);
    }
}
