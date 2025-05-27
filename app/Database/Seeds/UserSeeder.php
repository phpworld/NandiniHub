<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'first_name' => 'Admin',
                'last_name'  => 'User',
                'email'      => 'admin@nandinihub.com',
                'password'   => password_hash('admin123', PASSWORD_DEFAULT),
                'phone'      => '+91 98765 43210',
                'address'    => '123 Admin Street, Business District',
                'city'       => 'Mumbai',
                'state'      => 'Maharashtra',
                'pincode'    => '400001',
                'is_active'  => 1,
                'role'       => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Test',
                'last_name'  => 'Customer',
                'email'      => 'customer@test.com',
                'password'   => password_hash('customer123', PASSWORD_DEFAULT),
                'phone'      => '+91 87654 32109',
                'address'    => '456 Customer Lane, Residential Area',
                'city'       => 'Delhi',
                'state'      => 'Delhi',
                'pincode'    => '110001',
                'is_active'  => 1,
                'role'       => 'customer',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
