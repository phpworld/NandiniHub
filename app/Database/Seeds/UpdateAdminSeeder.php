<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateAdminSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('users')
                 ->where('email', 'admin@nandinihub.com')
                 ->update(['role' => 'admin']);
    }
}
