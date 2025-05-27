<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'        => 'Agarbatti & Incense',
                'slug'        => 'agarbatti-incense',
                'description' => 'Premium quality agarbatti and incense sticks for daily puja and special occasions',
                'is_active'   => 1,
                'sort_order'  => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Dhoop & Sambrani',
                'slug'        => 'dhoop-sambrani',
                'description' => 'Traditional dhoop sticks and sambrani for creating sacred atmosphere',
                'is_active'   => 1,
                'sort_order'  => 2,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Puja Thali & Accessories',
                'slug'        => 'puja-thali-accessories',
                'description' => 'Complete puja thali sets and essential accessories for worship',
                'is_active'   => 1,
                'sort_order'  => 3,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Diyas & Candles',
                'slug'        => 'diyas-candles',
                'description' => 'Traditional diyas, candles and oil lamps for lighting during puja',
                'is_active'   => 1,
                'sort_order'  => 4,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Flowers & Garlands',
                'slug'        => 'flowers-garlands',
                'description' => 'Fresh flowers, artificial garlands and flower decorations',
                'is_active'   => 1,
                'sort_order'  => 5,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Puja Oils & Ghee',
                'slug'        => 'puja-oils-ghee',
                'description' => 'Pure oils, ghee and other liquid offerings for puja',
                'is_active'   => 1,
                'sort_order'  => 6,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Idols & Statues',
                'slug'        => 'idols-statues',
                'description' => 'Beautiful idols and statues of various deities',
                'is_active'   => 1,
                'sort_order'  => 7,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Puja Books & Mantras',
                'slug'        => 'puja-books-mantras',
                'description' => 'Religious books, mantra collections and spiritual literature',
                'is_active'   => 1,
                'sort_order'  => 8,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('categories')->insertBatch($data);
    }
}
