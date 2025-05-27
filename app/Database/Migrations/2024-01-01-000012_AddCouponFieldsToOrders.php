<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCouponFieldsToOrders extends Migration
{
    public function up()
    {
        $fields = [
            'coupon_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'total_amount',
            ],
            'coupon_code' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'coupon_id',
            ],
            'subtotal_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
                'after' => 'discount_amount',
            ],
        ];

        $this->forge->addColumn('orders', $fields);

        // Add foreign key for coupon_id
        $this->forge->addForeignKey('coupon_id', 'coupons', 'id', 'SET NULL', 'CASCADE', 'orders');
    }

    public function down()
    {
        $this->forge->dropForeignKey('orders', 'orders_coupon_id_foreign');
        $this->forge->dropColumn('orders', ['coupon_id', 'coupon_code', 'subtotal_amount']);
    }
}
