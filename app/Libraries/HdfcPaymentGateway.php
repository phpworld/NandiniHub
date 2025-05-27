<?php

namespace App\Libraries;

use App\Config\Payment;

class HdfcPaymentGateway
{
    private array $config;
    private Payment $paymentConfig;

    public function __construct()
    {
        $this->paymentConfig = new Payment();
        $this->config = $this->paymentConfig->getHdfcConfig();
    }

    /**
     * Generate Basic Auth header for API authentication
     */
    private function generateAuthHeader(): string
    {
        // HDFC SmartGateway uses Basic Auth with Base64 encoded API key
        // Format: Basic <base64(api_key:)>
        $credentials = $this->config['api_key'] . ':';
        return 'Basic ' . base64_encode($credentials);
    }

    /**
     * Make API request to HDFC SmartGateway
     */
    private function makeApiRequest(string $endpoint, array $data, string $method = 'POST'): array
    {
        $url = $this->config['api_endpoint'] . $endpoint;
        $authHeader = $this->generateAuthHeader();

        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: ' . $authHeader,
            'x-merchantid: ' . $this->config['merchant_id']
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->config['request_timeout'],
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2
        ]);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new \Exception('CURL Error: ' . $error);
        }

        $decodedResponse = json_decode($response, true);

        if ($httpCode !== 200) {
            throw new \Exception('API Error: HTTP ' . $httpCode . ' - ' . ($decodedResponse['message'] ?? 'Unknown error'));
        }

        return $decodedResponse;
    }

    /**
     * Create order for HDFC SmartGateway
     */
    public function createOrder(array $orderData): array
    {
        $orderPayload = [
            'order_id' => $orderData['transaction_id'],
            'amount' => number_format($orderData['amount'], 2, '.', ''),
            'currency' => $this->config['currency'],
            'customer_id' => (string) $orderData['user_id'],
            'customer_email' => $orderData['billing_email'],
            'customer_phone' => $orderData['billing_tel'],
            'product_info' => 'Order #' . $orderData['order_id'],
            'return_url' => $this->config['return_url'],
            'notify_url' => $this->config['notify_url'],
            'billing_name' => $orderData['billing_name'],
            'billing_address' => $orderData['billing_address'],
            'billing_city' => $orderData['billing_city'],
            'billing_state' => $orderData['billing_state'],
            'billing_zip' => $orderData['billing_zip'],
            'billing_country' => $this->config['country'],
            'billing_tel' => $orderData['billing_tel'],
            'billing_email' => $orderData['billing_email'],
            'delivery_name' => $orderData['delivery_name'] ?? $orderData['billing_name'],
            'delivery_address' => $orderData['delivery_address'] ?? $orderData['billing_address'],
            'delivery_city' => $orderData['delivery_city'] ?? $orderData['billing_city'],
            'delivery_state' => $orderData['delivery_state'] ?? $orderData['billing_state'],
            'delivery_zip' => $orderData['delivery_zip'] ?? $orderData['billing_zip'],
            'delivery_country' => $this->config['country'],
            'delivery_tel' => $orderData['delivery_tel'] ?? $orderData['billing_tel'],
            'udf1' => $orderData['order_id'], // Internal order ID
            'udf2' => $orderData['user_id'],  // User ID
            'udf3' => '',
            'udf4' => '',
            'udf5' => ''
        ];

        try {
            // Check if we're using test credentials (mock mode)
            if ($this->config['test_mode'] && $this->config['api_key'] === 'test_api_key_here') {
                // Return mock response for testing
                return [
                    'success' => true,
                    'order_id' => $orderData['transaction_id'],
                    'payment_page_url' => $this->config['gateway_url'] . '/payment?order_id=' . $orderData['transaction_id'],
                    'payment_links' => [
                        'card' => $this->config['gateway_url'] . '/payment/card?order_id=' . $orderData['transaction_id'],
                        'upi' => $this->config['gateway_url'] . '/payment/upi?order_id=' . $orderData['transaction_id'],
                        'netbanking' => $this->config['gateway_url'] . '/payment/nb?order_id=' . $orderData['transaction_id']
                    ],
                    'gateway_url' => $this->config['gateway_url'] . '/payment?order_id=' . $orderData['transaction_id'],
                    'order_data' => $orderPayload,
                    'raw_response' => [
                        'status' => 'success',
                        'message' => 'Mock order created for testing',
                        'order_id' => $orderData['transaction_id'],
                        'test_mode' => true
                    ]
                ];
            }

            // Make actual API call for real credentials
            $response = $this->makeApiRequest('/orders', $orderPayload);

            return [
                'success' => true,
                'order_id' => $response['order_id'] ?? $orderData['transaction_id'],
                'payment_page_url' => $response['payment_page_url'] ?? '',
                'payment_links' => $response['payment_links'] ?? [],
                'gateway_url' => $response['payment_page_url'] ?? $this->config['gateway_url'],
                'order_data' => $orderPayload,
                'raw_response' => $response
            ];

        } catch (\Exception $e) {
            log_message('error', 'HDFC Order creation failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Prepare payment request (for backward compatibility)
     */
    public function preparePaymentRequest(array $orderData): array
    {
        return $this->createOrder($orderData);
    }

    /**
     * Get order status from HDFC SmartGateway
     */
    public function getOrderStatus(string $orderId): array
    {
        try {
            $response = $this->makeApiRequest('/orders/' . $orderId, [], 'GET');

            return [
                'success' => true,
                'order_id' => $response['order_id'],
                'status' => $response['status'],
                'amount' => $response['amount'],
                'currency' => $response['currency'],
                'payment_method' => $response['payment_method'] ?? '',
                'gateway_transaction_id' => $response['txn_id'] ?? '',
                'bank_ref_no' => $response['bank_ref_no'] ?? '',
                'raw_response' => json_encode($response)
            ];

        } catch (\Exception $e) {
            log_message('error', 'Order status check failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Process payment response/callback
     */
    public function processPaymentResponse(array $responseData): array
    {
        try {
            // Map the response to our format
            $processedResponse = [
                'transaction_id' => $responseData['order_id'] ?? '',
                'gateway_transaction_id' => $responseData['txn_id'] ?? '',
                'amount' => $responseData['amount'] ?? 0,
                'order_status' => $responseData['status'] ?? '',
                'payment_mode' => $responseData['payment_method'] ?? '',
                'currency' => $responseData['currency'] ?? '',
                'bank_ref_no' => $responseData['bank_ref_no'] ?? '',
                'failure_message' => $responseData['error_message'] ?? '',
                'metadata' => $responseData['metadata'] ?? [],
                'raw_response' => json_encode($responseData),
                'success' => $this->isPaymentSuccessful($responseData['status'] ?? '')
            ];

            return $processedResponse;

        } catch (\Exception $e) {
            log_message('error', 'Payment response processing failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Failed to process payment response',
                'raw_response' => json_encode($responseData)
            ];
        }
    }

    /**
     * Check if payment is successful
     */
    private function isPaymentSuccessful(string $orderStatus): bool
    {
        return strtoupper($orderStatus) === 'CHARGED';
    }

    /**
     * Get payment status mapping
     */
    public function getPaymentStatus(string $gatewayStatus): string
    {
        $statusMapping = $this->paymentConfig->statusMapping;
        return $statusMapping[$gatewayStatus] ?? 'failed';
    }

    /**
     * Validate payment amount
     */
    public function validateAmount(float $amount): bool
    {
        return $amount >= $this->config['min_amount'] && $amount <= $this->config['max_amount'];
    }

    /**
     * Get configuration for frontend
     */
    public function getPublicConfig(): array
    {
        return [
            'currency' => $this->config['currency'],
            'min_amount' => $this->config['min_amount'],
            'max_amount' => $this->config['max_amount'],
            'payment_options' => $this->config['payment_options'],
            'test_mode' => $this->config['test_mode'],
            'gateway_url' => $this->config['gateway_url']
        ];
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        $expectedSignature = hash_hmac('sha256', $payload, $this->config['api_key']);
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Process webhook notification
     */
    public function processWebhook(array $webhookData): array
    {
        try {
            return [
                'success' => true,
                'order_id' => $webhookData['order_id'],
                'status' => $webhookData['status'],
                'amount' => $webhookData['amount'],
                'currency' => $webhookData['currency'],
                'gateway_transaction_id' => $webhookData['txn_id'] ?? '',
                'payment_method' => $webhookData['payment_method'] ?? '',
                'bank_ref_no' => $webhookData['bank_ref_no'] ?? '',
                'metadata' => $webhookData['metadata'] ?? [],
                'raw_data' => json_encode($webhookData)
            ];
        } catch (\Exception $e) {
            log_message('error', 'Webhook processing failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
