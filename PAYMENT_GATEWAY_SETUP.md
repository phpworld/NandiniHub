# HDFC Payment Gateway Integration - Setup Guide

## Overview
This document provides instructions for setting up and testing the HDFC Bank payment gateway integration in the Nandini Hub e-commerce application.

## Features Implemented

### 1. Payment Gateway Integration
- **Gateway**: HDFC Bank Payment Gateway (CCAvenue)
- **Supported Payment Methods**: Credit Cards, Debit Cards, UPI, Net Banking, Wallets
- **Security**: 256-bit SSL encryption
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
'test_merchant_id' => 'TEST_MERCHANT_ID',
'test_access_code' => 'TEST_ACCESS_CODE',
'test_working_key' => 'TEST_WORKING_KEY',
```

### 2. Production Setup (When Going Live)
1. Set `test_mode` to `false`
2. Update production credentials:
   - `live_merchant_id`
   - `live_access_code`
   - `live_working_key`

## Testing the Payment System

### 1. Test Credentials
The system is currently configured with test credentials. For actual testing with HDFC:
- Contact HDFC Bank to get test merchant credentials
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
   - You'll be redirected to payment processing page
   - In test mode, you'll see the payment form

4. **Test Payment Response**
   - Use test card numbers provided by HDFC
   - Complete the payment process
   - Verify success/failure handling

### 3. Test URLs
- **Checkout**: `http://localhost/nandinihub/checkout`
- **Orders**: `http://localhost/nandinihub/orders`
- **Payment Success**: `http://localhost/nandinihub/payment/success/{transaction_id}`
- **Payment Failure**: `http://localhost/nandinihub/payment/failure/{transaction_id}`

## File Structure

### Controllers
- `app/Controllers/PaymentController.php` - Handles payment processing
- `app/Controllers/OrderController.php` - Updated for payment integration

### Models
- `app/Models/PaymentTransactionModel.php` - Payment transaction management
- `app/Models/OrderModel.php` - Order management

### Libraries
- `app/Libraries/HdfcPaymentGateway.php` - Payment gateway integration

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

### 1. Data Encryption
- All payment data is encrypted using AES-128-CBC
- Working key used for encryption/decryption
- Sensitive data never stored in plain text

### 2. Validation
- CSRF protection on all forms
- User authentication required
- Order ownership validation
- Payment amount validation

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
- Contact HDFC Bank for gateway-specific issues
- Refer to CCAvenue documentation for API details

## Security Notes

- Never commit real payment credentials to version control
- Use environment variables for sensitive configuration
- Regularly update and patch the system
- Monitor for suspicious payment activities
- Implement rate limiting for payment requests
