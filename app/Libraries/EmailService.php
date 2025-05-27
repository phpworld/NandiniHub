<?php

namespace App\Libraries;

use CodeIgniter\Email\Email;

class EmailService
{
    protected $email;

    public function __construct()
    {
        $this->email = \Config\Services::email();
        
        // Configure email settings
        $config = [
            'protocol'    => 'smtp',
            'SMTPHost'    => 'smtp.gmail.com', // Change to your SMTP host
            'SMTPUser'    => 'your-email@gmail.com', // Change to your email
            'SMTPPass'    => 'your-app-password', // Change to your app password
            'SMTPPort'    => 587,
            'SMTPCrypto'  => 'tls',
            'mailType'    => 'html',
            'charset'     => 'utf-8',
            'newline'     => "\r\n"
        ];
        
        $this->email->initialize($config);
    }

    public function sendOrderConfirmation($order, $orderItems, $user)
    {
        try {
            $this->email->setFrom('noreply@nandinihub.com', 'Nandini Hub');
            $this->email->setTo($user['email']);
            $this->email->setSubject('Order Confirmation - Order #' . $order['order_number']);

            $message = $this->generateOrderConfirmationEmail($order, $orderItems, $user);
            $this->email->setMessage($message);

            if ($this->email->send()) {
                log_message('info', 'Order confirmation email sent to: ' . $user['email']);
                return true;
            } else {
                log_message('error', 'Failed to send order confirmation email: ' . $this->email->printDebugger());
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'Email service error: ' . $e->getMessage());
            return false;
        }
    }

    public function sendOrderStatusUpdate($order, $user, $oldStatus, $newStatus)
    {
        try {
            $this->email->setFrom('noreply@nandinihub.com', 'Nandini Hub');
            $this->email->setTo($user['email']);
            $this->email->setSubject('Order Status Update - Order #' . $order['order_number']);

            $message = $this->generateOrderStatusUpdateEmail($order, $user, $oldStatus, $newStatus);
            $this->email->setMessage($message);

            if ($this->email->send()) {
                log_message('info', 'Order status update email sent to: ' . $user['email']);
                return true;
            } else {
                log_message('error', 'Failed to send order status update email: ' . $this->email->printDebugger());
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'Email service error: ' . $e->getMessage());
            return false;
        }
    }

    public function sendWelcomeEmail($user)
    {
        try {
            $this->email->setFrom('noreply@nandinihub.com', 'Nandini Hub');
            $this->email->setTo($user['email']);
            $this->email->setSubject('Welcome to Nandini Hub - Your Spiritual Journey Begins');

            $message = $this->generateWelcomeEmail($user);
            $this->email->setMessage($message);

            if ($this->email->send()) {
                log_message('info', 'Welcome email sent to: ' . $user['email']);
                return true;
            } else {
                log_message('error', 'Failed to send welcome email: ' . $this->email->printDebugger());
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'Email service error: ' . $e->getMessage());
            return false;
        }
    }

    private function generateOrderConfirmationEmail($order, $orderItems, $user)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Order Confirmation</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #ff6b35, #f7931e); color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .order-details { background: white; padding: 15px; margin: 15px 0; border-radius: 5px; }
                .item { border-bottom: 1px solid #eee; padding: 10px 0; }
                .total { font-weight: bold; font-size: 18px; color: #ff6b35; }
                .footer { text-align: center; padding: 20px; color: #666; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>🕉️ Nandini Hub</h1>
                    <h2>Order Confirmation</h2>
                </div>
                
                <div class="content">
                    <p>Dear ' . esc($user['first_name']) . ',</p>
                    <p>Thank you for your order! We have received your order and it is being processed.</p>
                    
                    <div class="order-details">
                        <h3>Order Details</h3>
                        <p><strong>Order Number:</strong> ' . esc($order['order_number']) . '</p>
                        <p><strong>Order Date:</strong> ' . date('F j, Y', strtotime($order['created_at'])) . '</p>
                        <p><strong>Payment Method:</strong> ' . ucfirst($order['payment_method']) . '</p>
                        
                        <h4>Items Ordered:</h4>';
        
        foreach ($orderItems as $item) {
            $html .= '
                        <div class="item">
                            <strong>' . esc($item['product_name']) . '</strong><br>
                            Quantity: ' . $item['quantity'] . ' × ₹' . number_format($item['price'], 2) . ' = ₹' . number_format($item['total'], 2) . '
                        </div>';
        }
        
        $html .= '
                        <div class="total">
                            <p>Total Amount: ₹' . number_format($order['total_amount'], 2) . '</p>
                        </div>
                    </div>
                    
                    <p>Your order will be shipped to:</p>
                    <div class="order-details">
                        ' . nl2br(esc($order['shipping_address'])) . '
                    </div>
                    
                    <p>We will send you another email when your order ships.</p>
                    <p>Thank you for choosing Nandini Hub for your spiritual needs!</p>
                </div>
                
                <div class="footer">
                    <p>Nandini Hub - Premium Puja Samagri Online</p>
                    <p>For any queries, contact us at info@nandinihub.com</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }

    private function generateOrderStatusUpdateEmail($order, $user, $oldStatus, $newStatus)
    {
        $statusMessages = [
            'pending' => 'Your order is being processed.',
            'processing' => 'Your order is being prepared for shipment.',
            'shipped' => 'Your order has been shipped and is on its way!',
            'delivered' => 'Your order has been delivered. Thank you for shopping with us!',
            'cancelled' => 'Your order has been cancelled as requested.'
        ];

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Order Status Update</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #ff6b35, #f7931e); color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .status-update { background: white; padding: 15px; margin: 15px 0; border-radius: 5px; text-align: center; }
                .status { font-size: 24px; font-weight: bold; color: #ff6b35; text-transform: uppercase; }
                .footer { text-align: center; padding: 20px; color: #666; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>🕉️ Nandini Hub</h1>
                    <h2>Order Status Update</h2>
                </div>
                
                <div class="content">
                    <p>Dear ' . esc($user['first_name']) . ',</p>
                    <p>Your order status has been updated.</p>
                    
                    <div class="status-update">
                        <p><strong>Order Number:</strong> ' . esc($order['order_number']) . '</p>
                        <div class="status">' . ucfirst($newStatus) . '</div>
                        <p>' . ($statusMessages[$newStatus] ?? 'Your order status has been updated.') . '</p>
                    </div>
                    
                    <p>You can track your order anytime by visiting your account on our website.</p>
                    <p>Thank you for choosing Nandini Hub!</p>
                </div>
                
                <div class="footer">
                    <p>Nandini Hub - Premium Puja Samagri Online</p>
                    <p>For any queries, contact us at info@nandinihub.com</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }

    private function generateWelcomeEmail($user)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Welcome to Nandini Hub</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #ff6b35, #f7931e); color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .welcome-box { background: white; padding: 15px; margin: 15px 0; border-radius: 5px; text-align: center; }
                .features { display: flex; justify-content: space-around; margin: 20px 0; }
                .feature { text-align: center; flex: 1; padding: 10px; }
                .footer { text-align: center; padding: 20px; color: #666; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>🕉️ Welcome to Nandini Hub</h1>
                    <p>Your Spiritual Journey Begins Here</p>
                </div>
                
                <div class="content">
                    <div class="welcome-box">
                        <h2>Namaste ' . esc($user['first_name']) . '!</h2>
                        <p>Thank you for joining the Nandini Hub family. We are delighted to have you with us on your spiritual journey.</p>
                    </div>
                    
                    <h3>What makes Nandini Hub special?</h3>
                    <div class="features">
                        <div class="feature">
                            <h4>🔥 Premium Quality</h4>
                            <p>Authentic puja samagri sourced from trusted suppliers</p>
                        </div>
                        <div class="feature">
                            <h4>🚚 Fast Delivery</h4>
                            <p>Quick and secure delivery to your doorstep</p>
                        </div>
                        <div class="feature">
                            <h4>🛡️ Trusted Service</h4>
                            <p>24/7 customer support and easy returns</p>
                        </div>
                    </div>
                    
                    <p>Start exploring our wide range of:</p>
                    <ul>
                        <li>Premium Agarbatti & Incense</li>
                        <li>Traditional Dhoop & Sambrani</li>
                        <li>Beautiful Puja Thali Sets</li>
                        <li>Authentic Diyas & Candles</li>
                        <li>And much more...</li>
                    </ul>
                    
                    <div class="welcome-box">
                        <p><strong>Special Offer:</strong> Get free shipping on your first order above ₹500!</p>
                        <a href="' . base_url() . '" style="background: #ff6b35; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Start Shopping</a>
                    </div>
                </div>
                
                <div class="footer">
                    <p>Nandini Hub - Premium Puja Samagri Online</p>
                    <p>For any queries, contact us at info@nandinihub.com</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }
}
