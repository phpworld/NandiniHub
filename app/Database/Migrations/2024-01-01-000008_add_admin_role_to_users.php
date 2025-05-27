<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdminRoleToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['customer', 'admin'],
                'default'    => 'customer',
                'after'      => 'is_active'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'role');
    }
}
