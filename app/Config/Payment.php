<?php

namespace App\Config;

use CodeIgniter\Config\BaseConfig;

class Payment extends BaseConfig
{
    /**
     * HDFC Payment Gateway Configuration
     */
    public array $hdfc = [
        // Test Environment Settings
        'test_mode' => true,
        'test_merchant_id' => 'TEST_MERCHANT_ID',
        'test_access_code' => 'TEST_ACCESS_CODE',
        'test_working_key' => 'TEST_WORKING_KEY',
        'test_gateway_url' => 'https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction',
        'test_redirect_url' => 'https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction',
        
        // Production Environment Settings (Use when going live)
        'live_merchant_id' => 'LIVE_MERCHANT_ID',
        'live_access_code' => 'LIVE_ACCESS_CODE', 
        'live_working_key' => 'LIVE_WORKING_KEY',
        'live_gateway_url' => 'https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction',
        'live_redirect_url' => 'https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction',
        
        // Common Settings
        'currency' => 'INR',
        'language' => 'EN',
        
        // Callback URLs (will be dynamically set based on base_url)
        'redirect_url' => '',
        'cancel_url' => '',
        
        // Payment Methods
        'payment_options' => [
            'OPTCRDC' => 'Credit Card',
            'OPTDBCRD' => 'Debit Card', 
            'OPTNBK' => 'Net Banking',
            'OPTUPINB' => 'UPI',
            'OPTWLT' => 'Wallet'
        ],
        
        // Transaction Settings
        'timeout' => 300, // 5 minutes
        'max_amount' => 100000, // Rs. 1,00,000
        'min_amount' => 1, // Rs. 1
    ];
    
    /**
     * Get current environment configuration
     */
    public function getHdfcConfig(): array
    {
        $config = $this->hdfc;
        $baseUrl = base_url();
        
        if ($config['test_mode']) {
            $config['merchant_id'] = $config['test_merchant_id'];
            $config['access_code'] = $config['test_access_code'];
            $config['working_key'] = $config['test_working_key'];
            $config['gateway_url'] = $config['test_gateway_url'];
        } else {
            $config['merchant_id'] = $config['live_merchant_id'];
            $config['access_code'] = $config['live_access_code'];
            $config['working_key'] = $config['live_working_key'];
            $config['gateway_url'] = $config['live_gateway_url'];
        }
        
        // Set callback URLs
        $config['redirect_url'] = $baseUrl . 'payment/callback';
        $config['cancel_url'] = $baseUrl . 'payment/failure/cancelled';
        
        return $config;
    }
    
    /**
     * Payment status mappings
     */
    public array $statusMapping = [
        'Success' => 'paid',
        'Failure' => 'failed', 
        'Aborted' => 'failed',
        'Invalid' => 'failed',
        'Timeout' => 'failed',
        'Cancelled' => 'failed'
    ];
}
