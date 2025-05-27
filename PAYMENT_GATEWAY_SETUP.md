# HDFC SmartGateway Integration - Setup Guide

## Overview
This document provides instructions for setting up and testing the HDFC Bank SmartGateway integration in the Nandini Hub e-commerce application.

## Features Implemented

### 1. Payment Gateway Integration
- **Gateway**: HDFC Bank SmartGateway (Powered by Juspay)
- **API Type**: REST API with JWT authentication
- **Supported Payment Methods**: Credit Cards, Debit Cards, UPI, Net Banking, Wallets, EMI
- **Security**: JWT token authentication, webhook signature verification
- **Test Mode**: Enabled for development and testing

### 2. Payment Flow
1. Customer selects "Online Payment" during checkout
2. Order is created with "pending" payment status
3. Customer is redirected to payment gateway
4. Payment is processed securely
5. Customer is redirected back with payment status
6. Order status is updated based on payment result

### 3. Database Structure
- **payment_transactions** table stores all payment transaction details
- **orders** table updated with payment status
- Foreign key relationships maintain data integrity

## Configuration

### 1. Payment Configuration File
Location: `app/Config/Payment.php`

```php
// Test Environment Settings (Currently Active)
'test_mode' => true,
'test_merchant_id' => 'test_merchant_hdfc',
'test_api_key' => 'test_api_key_here',
'test_client_id' => 'test_client_id_here',
'test_gateway_url' => 'https://smartgatewayuat.hdfcbank.com',
'test_api_endpoint' => 'https://smartgatewayuat.hdfcbank.com/api/v1',
```

### 2. Production Setup (When Going Live)
1. Set `test_mode` to `false`
2. Update production credentials:
   - `live_merchant_id`
   - `live_api_key`
   - `live_client_id`
   - Gateway URLs will automatically switch to production endpoints

## Testing the Payment System

### 1. Test Credentials
The system is currently configured with test credentials. For actual testing with HDFC SmartGateway:
- Contact HDFC Bank to get test merchant credentials
- Obtain API key and client ID from HDFC SmartGateway portal
- Update the configuration in `app/Config/Payment.php`

### 2. Test Flow
1. **Add products to cart**
   - Navigate to the website
   - Add any products to cart
   - Proceed to checkout

2. **Select Online Payment**
   - Choose "Online Payment" option
   - Fill in shipping address
   - Click "Proceed to Payment"

3. **Payment Processing**
   - Order will be created with pending status
   - System creates order via HDFC SmartGateway API
   - You'll be redirected to payment processing page
   - Choose from available payment methods

4. **Test Payment Response**
   - Use test card numbers provided by HDFC SmartGateway
   - Complete the payment process
   - System receives callback and webhook notifications
   - Verify success/failure handling

### 3. Test URLs
- **Checkout**: `http://localhost/nandinihub/checkout`
- **Orders**: `http://localhost/nandinihub/orders`
- **Payment Success**: `http://localhost/nandinihub/payment/success/{transaction_id}`
- **Payment Failure**: `http://localhost/nandinihub/payment/failure/{transaction_id}`
- **Payment Webhook**: `http://localhost/nandinihub/payment/webhook`

## File Structure

### Controllers
- `app/Controllers/PaymentController.php` - Handles payment processing
- `app/Controllers/OrderController.php` - Updated for payment integration

### Models
- `app/Models/PaymentTransactionModel.php` - Payment transaction management
- `app/Models/OrderModel.php` - Order management

### Libraries
- `app/Libraries/HdfcPaymentGateway.php` - HDFC SmartGateway API integration

### Views
- `app/Views/payment/process.php` - Payment processing page
- `app/Views/payment/success.php` - Payment success page
- `app/Views/payment/failure.php` - Payment failure page
- `app/Views/orders/checkout.php` - Enhanced checkout form
- `app/Views/orders/show.php` - Order details with payment options

### Configuration
- `app/Config/Payment.php` - Payment gateway configuration
- `app/Config/Routes.php` - Payment routes

### Database
- `app/Database/Migrations/2024-01-01-000009_CreatePaymentTransactionsTable.php`

## Security Features

### 1. API Security
- JWT token authentication for all API requests
- Webhook signature verification using HMAC-SHA256
- API key securely stored and never exposed to client
- HTTPS required for all communications

### 2. Validation
- CSRF protection on all forms
- User authentication required
- Order ownership validation
- Payment amount validation
- Request signature validation

### 3. Error Handling
- Comprehensive error logging
- Graceful failure handling
- User-friendly error messages
- Automatic retry mechanisms

## Troubleshooting

### Common Issues
1. **Migration Error**: Run `php spark migrate` to create payment_transactions table
2. **Permission Issues**: Ensure proper file permissions
3. **Configuration**: Verify payment configuration settings
4. **SSL**: Ensure SSL is properly configured for production

### Logs
- Check `writable/logs/` for error logs
- Payment transactions are logged for debugging
- Gateway responses are stored for analysis

## Production Checklist

Before going live:
- [ ] Update to production payment credentials
- [ ] Set `test_mode` to `false`
- [ ] Configure SSL certificate
- [ ] Test with real payment methods
- [ ] Set up monitoring and alerts
- [ ] Configure backup and recovery
- [ ] Update callback URLs to production domain

## Support

For technical support:
- Check the logs in `writable/logs/`
- Review payment transaction records in database
- Contact HDFC Bank for SmartGateway-specific issues
- Refer to HDFC SmartGateway documentation for API details
- Check Juspay documentation for technical implementation

## Security Notes

- Never commit real payment credentials to version control
- Use environment variables for sensitive configuration
- Regularly update and patch the system
- Monitor for suspicious payment activities
- Implement rate limiting for payment requests
