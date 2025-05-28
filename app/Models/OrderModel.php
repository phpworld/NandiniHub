<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'order_number',
        'status',
        'total_amount',
        'subtotal_amount',
        'shipping_amount',
        'tax_amount',
        'discount_amount',
        'coupon_id',
        'coupon_code',
        'payment_method',
        'payment_status',
        'shipping_address',
        'billing_address',
        'notes'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'user_id' => 'required|integer',
        'order_number' => 'required|is_unique[orders.order_number,id,{id}]',
        'total_amount' => 'required|decimal',
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generateOrderNumber'];

    protected function generateOrderNumber(array $data)
    {
        if (empty($data['data']['order_number'])) {
            $data['data']['order_number'] = 'ORD' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        }
        return $data;
    }

    public function getOrdersWithItems($userId = null, $limit = null)
    {
        $builder = $this->select('orders.*, users.first_name, users.last_name, users.email')
            ->join('users', 'users.id = orders.user_id')
            ->orderBy('orders.created_at', 'DESC');

        if ($userId) {
            $builder->where('orders.user_id', $userId);
        }

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    public function getOrderWithDetails($orderId, $userId = null)
    {
        $builder = $this->select('orders.*, users.first_name, users.last_name, users.email, users.phone')
            ->join('users', 'users.id = orders.user_id')
            ->where('orders.id', $orderId);

        if ($userId) {
            $builder->where('orders.user_id', $userId);
        }

        return $builder->first();
    }

    public function getOrderByNumber($orderNumber, $userId = null)
    {
        $builder = $this->where('order_number', $orderNumber);

        if ($userId) {
            $builder->where('user_id', $userId);
        }

        return $builder->first();
    }

    public function updateOrderStatus($orderId, $status)
    {
        return $this->update($orderId, ['status' => $status]);
    }

    public function updatePaymentStatus($orderId, $paymentStatus)
    {
        return $this->update($orderId, ['payment_status' => $paymentStatus]);
    }

    public function canBeCancelled($order)
    {
        // Only pending orders can be cancelled
        if ($order['status'] !== 'pending') {
            return false;
        }

        // Check if order is too old to cancel (e.g., older than 24 hours)
        $orderTime = strtotime($order['created_at']);
        $currentTime = time();
        $hoursSinceOrder = ($currentTime - $orderTime) / 3600;

        // Allow cancellation within 24 hours for pending orders
        if ($hoursSinceOrder > 24) {
            return false;
        }

        return true;
    }

    public function getCancellationReason($order)
    {
        if ($order['status'] !== 'pending') {
            return 'Order cannot be cancelled. Only pending orders can be cancelled.';
        }

        $orderTime = strtotime($order['created_at']);
        $currentTime = time();
        $hoursSinceOrder = ($currentTime - $orderTime) / 3600;

        if ($hoursSinceOrder > 24) {
            return 'Order cannot be cancelled. Cancellation is only allowed within 24 hours of placing the order.';
        }

        return '';
    }

    public function getOrderStats($userId = null)
    {
        $builder = $this->selectSum('total_amount', 'total_spent')
            ->selectCount('id', 'total_orders');

        if ($userId) {
            $builder->where('user_id', $userId);
        }

        return $builder->first();
    }

    public function getUserOrders($userId, $limit = null)
    {
        $builder = $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    public function getRecentOrders($limit = 10)
    {
        return $this->select('orders.*, users.first_name, users.last_name')
            ->join('users', 'users.id = orders.user_id')
            ->orderBy('orders.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}
