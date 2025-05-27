<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\PaymentTransactionModel;
use App\Models\UserModel;
use App\Libraries\HdfcPaymentGateway;

class PaymentController extends BaseController
{
    protected $orderModel;
    protected $paymentTransactionModel;
    protected $userModel;
    protected $hdfcGateway;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->paymentTransactionModel = new PaymentTransactionModel();
        $this->userModel = new UserModel();
        $this->hdfcGateway = new HdfcPaymentGateway();
    }

    /**
     * Initiate payment process
     */
    public function initiate()
    {
        // Allow both AJAX and regular POST requests for debugging
        if (!$this->request->isAJAX() && !$this->request->isPost()) {
            return redirect()->back();
        }

        $orderId = $this->request->getPost('order_id');

        log_message('info', 'Payment initiation request received. Order ID: ' . $orderId);
        log_message('info', 'Request method: ' . $this->request->getMethod());
        log_message('info', 'Is AJAX: ' . ($this->request->isAJAX() ? 'Yes' : 'No'));
        log_message('info', 'POST data: ' . json_encode($this->request->getPost()));

        if (!$orderId) {
            log_message('error', 'Order ID missing in payment initiation request');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order ID is required'
            ]);
        }

        // Get order details
        $order = $this->orderModel->find($orderId);
        if (!$order) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order not found'
            ]);
        }

        // Check if user owns this order
        if ($order['user_id'] != session()->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ]);
        }

        // Check if order is eligible for payment
        if ($order['payment_method'] !== 'online' || $order['payment_status'] !== 'pending') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order is not eligible for online payment'
            ]);
        }

        // Get user details
        $user = $this->userModel->find($order['user_id']);

        try {
            // Generate unique transaction ID
            $transactionIdString = 'TXN' . date('YmdHis') . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);

            log_message('info', 'Creating payment transaction for order: ' . $orderId . ', transaction: ' . $transactionIdString);

            // Create payment transaction record
            $transactionData = [
                'order_id' => $orderId,
                'transaction_id' => $transactionIdString,
                'amount' => $order['total_amount'],
                'currency' => 'INR',
                'status' => 'pending',
                'payment_gateway' => 'hdfc'
            ];

            log_message('info', 'Transaction data: ' . json_encode($transactionData));

            $transactionId = $this->paymentTransactionModel->insert($transactionData);
            if (!$transactionId) {
                $errors = $this->paymentTransactionModel->errors();
                log_message('error', 'Payment transaction insert failed: ' . json_encode($errors));
                throw new \Exception('Failed to create payment transaction: ' . json_encode($errors));
            }

            log_message('info', 'Payment transaction created with ID: ' . $transactionId);

            $transaction = $this->paymentTransactionModel->find($transactionId);
            if (!$transaction) {
                throw new \Exception('Failed to retrieve payment transaction');
            }

            log_message('info', 'Retrieved transaction: ' . json_encode($transaction));

            // Prepare order data for payment gateway
            $orderData = [
                'transaction_id' => $transaction['transaction_id'],
                'order_id' => $orderId,
                'user_id' => $user['id'],
                'amount' => $order['total_amount'],
                'billing_name' => $user['first_name'] . ' ' . $user['last_name'],
                'billing_address' => $order['billing_address'],
                'billing_city' => $user['city'] ?? 'Unknown',
                'billing_state' => $user['state'] ?? 'Unknown',
                'billing_zip' => $user['pincode'] ?? '000000',
                'billing_tel' => $user['phone'] ?? '',
                'billing_email' => $user['email'],
                'delivery_address' => $order['shipping_address']
            ];

            // Prepare payment request
            log_message('info', 'Preparing payment request with order data: ' . json_encode($orderData));
            $paymentRequest = $this->hdfcGateway->preparePaymentRequest($orderData);
            log_message('info', 'Payment request prepared successfully');

            return $this->response->setJSON([
                'success' => true,
                'transaction_id' => $transaction['transaction_id'],
                'payment_url' => base_url('payment/process/' . $transaction['transaction_id']),
                'message' => 'Payment initiated successfully'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Payment initiation failed: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to initiate payment. Please try again.',
                'debug' => ENVIRONMENT === 'development' ? $e->getMessage() : null
            ]);
        }
    }

    /**
     * Process payment - redirect to gateway
     */
    public function process($transactionId)
    {
        $transaction = $this->paymentTransactionModel->getByTransactionId($transactionId);

        if (!$transaction) {
            session()->setFlashdata('error', 'Transaction not found');
            return redirect()->to('/orders');
        }

        // Get order and user details
        $order = $this->orderModel->find($transaction['order_id']);
        $user = $this->userModel->find($order['user_id']);

        // Prepare order data for payment gateway
        $orderData = [
            'transaction_id' => $transaction['transaction_id'],
            'order_id' => $order['id'],
            'user_id' => $user['id'],
            'amount' => $transaction['amount'],
            'billing_name' => $user['first_name'] . ' ' . $user['last_name'],
            'billing_address' => $order['billing_address'],
            'billing_city' => $user['city'] ?? 'Unknown',
            'billing_state' => $user['state'] ?? 'Unknown',
            'billing_zip' => $user['pincode'] ?? '000000',
            'billing_tel' => $user['phone'] ?? '',
            'billing_email' => $user['email'],
            'delivery_address' => $order['shipping_address']
        ];

        // Get payment request data
        $paymentRequest = $this->hdfcGateway->preparePaymentRequest($orderData);

        // Update transaction status to processing
        $this->paymentTransactionModel->updateStatus($transactionId, 'processing');

        $data = [
            'title' => 'Processing Payment - Nandini Hub',
            'paymentRequest' => $paymentRequest,
            'order' => $order,
            'transaction' => $transaction
        ];

        return view('payment/process', $data);
    }

    /**
     * Handle payment gateway callback
     */
    public function callback()
    {
        $encResponse = $this->request->getPost('encResp');

        if (!$encResponse) {
            log_message('error', 'Payment callback received without encrypted response');
            return redirect()->to('/payment/failure/invalid');
        }

        try {
            // Process the payment response
            $response = $this->hdfcGateway->processPaymentResponse($encResponse);

            if (!$response || !isset($response['transaction_id'])) {
                log_message('error', 'Invalid payment response structure');
                return redirect()->to('/payment/failure/invalid');
            }

            $transactionId = $response['transaction_id'];
            $transaction = $this->paymentTransactionModel->getByTransactionId($transactionId);

            if (!$transaction) {
                log_message('error', 'Transaction not found for ID: ' . $transactionId);
                return redirect()->to('/payment/failure/notfound');
            }

            // Update transaction with gateway response
            $updateData = [
                'gateway_transaction_id' => $response['gateway_transaction_id'] ?? null,
                'gateway_status' => $response['order_status'] ?? '',
                'gateway_response' => $response['raw_response'] ?? '',
                'payment_method' => $response['payment_mode'] ?? '',
                'bank_ref_no' => $response['bank_ref_no'] ?? '',
                'failure_reason' => $response['failure_message'] ?? null
            ];

            if ($response['success']) {
                // Payment successful
                $this->paymentTransactionModel->updateStatus($transactionId, 'success', $updateData);

                // Update order payment status
                $this->orderModel->update($transaction['order_id'], [
                    'payment_status' => 'paid',
                    'status' => 'processing'
                ]);

                return redirect()->to('/payment/success/' . $transactionId);
            } else {
                // Payment failed
                $this->paymentTransactionModel->updateStatus($transactionId, 'failed', $updateData);

                return redirect()->to('/payment/failure/' . $transactionId);
            }

        } catch (\Exception $e) {
            log_message('error', 'Payment callback processing failed: ' . $e->getMessage());
            return redirect()->to('/payment/failure/error');
        }
    }

    /**
     * Payment success page
     */
    public function success($transactionId)
    {
        $transaction = $this->paymentTransactionModel->getByTransactionId($transactionId);

        if (!$transaction || $transaction['status'] !== 'success') {
            session()->setFlashdata('error', 'Transaction not found or not successful');
            return redirect()->to('/orders');
        }

        $order = $this->orderModel->find($transaction['order_id']);

        // Check if user owns this order
        if ($order['user_id'] != session()->get('user_id')) {
            session()->setFlashdata('error', 'Unauthorized access');
            return redirect()->to('/orders');
        }

        $data = [
            'title' => 'Payment Successful - Nandini Hub',
            'transaction' => $transaction,
            'order' => $order
        ];

        return view('payment/success', $data);
    }

    /**
     * Payment failure page
     */
    public function failure($transactionId)
    {
        $transaction = null;
        $order = null;
        $errorMessage = 'Payment failed. Please try again.';

        if ($transactionId !== 'invalid' && $transactionId !== 'notfound' && $transactionId !== 'error' && $transactionId !== 'cancelled') {
            $transaction = $this->paymentTransactionModel->getByTransactionId($transactionId);

            if ($transaction) {
                $order = $this->orderModel->find($transaction['order_id']);

                // Check if user owns this order
                if ($order && $order['user_id'] != session()->get('user_id')) {
                    session()->setFlashdata('error', 'Unauthorized access');
                    return redirect()->to('/orders');
                }

                $errorMessage = $transaction['failure_reason'] ?: 'Payment failed. Please try again.';
            }
        } else {
            // Handle specific error types
            switch ($transactionId) {
                case 'invalid':
                    $errorMessage = 'Invalid payment response received.';
                    break;
                case 'notfound':
                    $errorMessage = 'Transaction not found.';
                    break;
                case 'cancelled':
                    $errorMessage = 'Payment was cancelled by user.';
                    break;
                case 'error':
                    $errorMessage = 'An error occurred while processing payment.';
                    break;
            }
        }

        $data = [
            'title' => 'Payment Failed - Nandini Hub',
            'transaction' => $transaction,
            'order' => $order,
            'errorMessage' => $errorMessage
        ];

        return view('payment/failure', $data);
    }

    /**
     * Verify payment status (AJAX)
     */
    public function verify($transactionId)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $transaction = $this->paymentTransactionModel->getByTransactionId($transactionId);

        if (!$transaction) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Transaction not found'
            ]);
        }

        $order = $this->orderModel->find($transaction['order_id']);

        // Check if user owns this order
        if ($order['user_id'] != session()->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'status' => $transaction['status'],
            'payment_status' => $order['payment_status'],
            'order_status' => $order['status'],
            'transaction' => $transaction
        ]);
    }

    /**
     * Test method for debugging
     */
    public function test()
    {
        try {
            // Test basic functionality
            $result = [
                'payment_config' => 'OK',
                'gateway_library' => 'OK',
                'models' => 'OK',
                'database' => 'OK'
            ];

            // Test payment config
            $paymentConfig = new \App\Config\Payment();
            $config = $paymentConfig->getHdfcConfig();
            $result['config_details'] = [
                'test_mode' => $config['test_mode'],
                'currency' => $config['currency'],
                'merchant_id' => substr($config['merchant_id'], 0, 5) . '...'
            ];

            // Test gateway library
            $gateway = new \App\Libraries\HdfcPaymentGateway();
            $publicConfig = $gateway->getPublicConfig();
            $result['gateway_details'] = $publicConfig;

            // Test database connection
            $db = \Config\Database::connect();
            $result['database_connection'] = $db->tableExists('payment_transactions') ? 'OK' : 'FAILED';

            // Test models
            $paymentModel = new \App\Models\PaymentTransactionModel();
            $orderModel = new \App\Models\OrderModel();
            $result['models_loaded'] = 'OK';

            // Test creating a sample transaction
            $sampleData = [
                'order_id' => 999,
                'transaction_id' => 'TEST_' . time(),
                'amount' => 100.00,
                'currency' => 'INR',
                'status' => 'pending',
                'payment_gateway' => 'hdfc'
            ];

            $transactionId = $paymentModel->insert($sampleData);
            if ($transactionId) {
                $result['sample_transaction'] = 'Created with ID: ' . $transactionId;
                // Clean up
                $paymentModel->delete($transactionId);
                $result['cleanup'] = 'OK';
            } else {
                $result['sample_transaction'] = 'FAILED: ' . json_encode($paymentModel->errors());
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Payment system test completed',
                'results' => $result
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Test failed: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
