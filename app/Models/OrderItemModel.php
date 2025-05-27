<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table            = 'order_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_id',
        'product_id',
        'product_name',
        'product_sku',
        'quantity',
        'price',
        'total'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'order_id' => 'required|integer',
        'product_id' => 'required|integer',
        'quantity' => 'required|integer|greater_than[0]',
        'price' => 'required|decimal',
        'total' => 'required|decimal',
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    public function getOrderItems($orderId)
    {
        return $this->select('order_items.*, products.image as product_image')
            ->join('products', 'products.id = order_items.product_id', 'left')
            ->where('order_items.order_id', $orderId)
            ->findAll();
    }

    public function getOrderItemsWithProducts($orderId)
    {
        return $this->select('order_items.*, products.image as product_image, products.name as product_name, products.sku as product_sku')
            ->join('products', 'products.id = order_items.product_id', 'left')
            ->where('order_items.order_id', $orderId)
            ->findAll();
    }

    public function createOrderItems($orderId, $cartItems)
    {
        $orderItems = [];

        foreach ($cartItems as $item) {
            $price = $item['sale_price'] ?? $item['price'];
            $total = $price * $item['quantity'];

            $orderItems[] = [
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'product_name' => $item['name'],
                'product_sku' => $item['sku'] ?? 'N/A',
                'quantity' => $item['quantity'],
                'price' => $price,
                'total' => $total
            ];
        }

        return $this->insertBatch($orderItems);
    }

    public function getTopSellingProducts($limit = 10)
    {
        return $this->select('product_id, product_name, SUM(quantity) as total_sold, SUM(total) as total_revenue')
            ->groupBy('product_id')
            ->orderBy('total_sold', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}
