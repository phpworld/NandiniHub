<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentTransactionModel extends Model
{
    protected $table            = 'payment_transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_id',
        'transaction_id',
        'gateway_transaction_id',
        'payment_gateway',
        'amount',
        'currency',
        'status',
        'gateway_status',
        'gateway_response',
        'payment_method',
        'bank_ref_no',
        'failure_reason',
        'processed_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'order_id' => 'required|integer',
        'transaction_id' => 'required|is_unique[payment_transactions.transaction_id,id,{id}]',
        'amount' => 'required|decimal',
        'currency' => 'required|max_length[3]',
        'status' => 'required|in_list[pending,processing,success,failed,cancelled,refunded]'
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = false;

    /**
     * Get transaction by transaction ID
     */
    public function getByTransactionId(string $transactionId): ?array
    {
        return $this->where('transaction_id', $transactionId)->first();
    }

    /**
     * Get transaction by order ID
     */
    public function getByOrderId(int $orderId): ?array
    {
        return $this->where('order_id', $orderId)->first();
    }

    /**
     * Update transaction status
     */
    public function updateStatus(string $transactionId, string $status, array $additionalData = []): bool
    {
        $updateData = array_merge(['status' => $status], $additionalData);

        if (in_array($status, ['success', 'failed', 'cancelled'])) {
            $updateData['processed_at'] = date('Y-m-d H:i:s');
        }

        return $this->where('transaction_id', $transactionId)->set($updateData)->update();
    }

    /**
     * Get transactions for an order with details
     */
    public function getOrderTransactions(int $orderId): array
    {
        return $this->select('payment_transactions.*, orders.order_number')
            ->join('orders', 'orders.id = payment_transactions.order_id', 'left')
            ->where('payment_transactions.order_id', $orderId)
            ->orderBy('payment_transactions.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get successful transaction for order
     */
    public function getSuccessfulTransaction(int $orderId): ?array
    {
        return $this->where('order_id', $orderId)
            ->where('status', 'success')
            ->first();
    }
}
