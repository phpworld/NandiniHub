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
     * Encrypt data for HDFC gateway
     */
    private function encrypt(string $plainText): string
    {
        $key = $this->config['working_key'];
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        $encryptedText = bin2hex($openMode);
        return $encryptedText;
    }

    /**
     * Decrypt data from HDFC gateway
     */
    private function decrypt(string $encryptedText): string
    {
        $key = $this->config['working_key'];
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $encryptedText = hex2bin($encryptedText);
        $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        return $decryptedText;
    }

    /**
     * Prepare payment request
     */
    public function preparePaymentRequest(array $orderData): array
    {
        $merchantData = [
            'merchant_id' => $this->config['merchant_id'],
            'order_id' => $orderData['transaction_id'],
            'amount' => $orderData['amount'],
            'currency' => $this->config['currency'],
            'redirect_url' => $this->config['redirect_url'],
            'cancel_url' => $this->config['cancel_url'],
            'language' => $this->config['language'],
            'billing_name' => $orderData['billing_name'],
            'billing_address' => $orderData['billing_address'],
            'billing_city' => $orderData['billing_city'],
            'billing_state' => $orderData['billing_state'],
            'billing_zip' => $orderData['billing_zip'],
            'billing_country' => 'India',
            'billing_tel' => $orderData['billing_tel'],
            'billing_email' => $orderData['billing_email'],
            'delivery_name' => $orderData['delivery_name'] ?? $orderData['billing_name'],
            'delivery_address' => $orderData['delivery_address'] ?? $orderData['billing_address'],
            'delivery_city' => $orderData['delivery_city'] ?? $orderData['billing_city'],
            'delivery_state' => $orderData['delivery_state'] ?? $orderData['billing_state'],
            'delivery_zip' => $orderData['delivery_zip'] ?? $orderData['billing_zip'],
            'delivery_country' => 'India',
            'delivery_tel' => $orderData['delivery_tel'] ?? $orderData['billing_tel'],
            'merchant_param1' => $orderData['order_id'], // Store our internal order ID
            'merchant_param2' => $orderData['user_id'],
            'promo_code' => '',
            'customer_identifier' => $orderData['user_id']
        ];

        // Convert array to query string
        $merchantDataString = '';
        foreach ($merchantData as $key => $value) {
            $merchantDataString .= $key . '=' . $value . '&';
        }
        $merchantDataString = rtrim($merchantDataString, '&');

        // Encrypt the data
        $encryptedData = $this->encrypt($merchantDataString);

        return [
            'encRequest' => $encryptedData,
            'access_code' => $this->config['access_code'],
            'gateway_url' => $this->config['gateway_url'],
            'merchant_data' => $merchantData
        ];
    }

    /**
     * Process payment response
     */
    public function processPaymentResponse(string $encResponse): array
    {
        try {
            // Decrypt the response
            $decryptedResponse = $this->decrypt($encResponse);
            
            // Parse the response
            $responseData = [];
            parse_str($decryptedResponse, $responseData);
            
            // Map the response to our format
            $processedResponse = [
                'transaction_id' => $responseData['order_id'] ?? '',
                'gateway_transaction_id' => $responseData['tracking_id'] ?? '',
                'amount' => $responseData['amount'] ?? 0,
                'order_status' => $responseData['order_status'] ?? '',
                'payment_mode' => $responseData['payment_mode'] ?? '',
                'card_name' => $responseData['card_name'] ?? '',
                'status_code' => $responseData['status_code'] ?? '',
                'status_message' => $responseData['status_message'] ?? '',
                'currency' => $responseData['currency'] ?? '',
                'response_code' => $responseData['response_code'] ?? '',
                'bank_ref_no' => $responseData['bank_ref_no'] ?? '',
                'failure_message' => $responseData['failure_message'] ?? '',
                'merchant_param1' => $responseData['merchant_param1'] ?? '', // Our order ID
                'merchant_param2' => $responseData['merchant_param2'] ?? '', // User ID
                'raw_response' => $decryptedResponse,
                'success' => $this->isPaymentSuccessful($responseData['order_status'] ?? '')
            ];
            
            return $processedResponse;
            
        } catch (\Exception $e) {
            log_message('error', 'Payment response processing failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Failed to process payment response',
                'raw_response' => $encResponse
            ];
        }
    }

    /**
     * Check if payment is successful
     */
    private function isPaymentSuccessful(string $orderStatus): bool
    {
        return strtolower($orderStatus) === 'success';
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
            'test_mode' => $this->config['test_mode']
        ];
    }
}
