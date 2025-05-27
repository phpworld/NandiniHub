<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\CartModel;
use App\Models\ProductModel;
use App\Models\UserModel;

class OrderController extends BaseController
{
    protected $orderModel;
    protected $orderItemModel;
    protected $cartModel;
    protected $productModel;
    protected $userModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->cartModel = new CartModel();
        $this->productModel = new ProductModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if (!session()->get('user_id')) {
            session()->set('redirect_to', current_url());
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');

        // Get user's orders
        $orders = $this->orderModel->getUserOrders($userId);

        // Get order items for each order (for preview)
        foreach ($orders as &$order) {
            $order['items'] = $this->orderItemModel->getOrderItems($order['id']);
        }

        $data = [
            'title' => 'My Orders - Nandini Hub',
            'orders' => $orders
        ];

        return view('orders/index', $data);
    }

    public function show($orderNumber)
    {
        if (!session()->get('user_id')) {
            session()->set('redirect_to', current_url());
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');

        // Get order by order number and user ID
        $order = $this->orderModel->getOrderByNumber($orderNumber, $userId);

        if (!$order) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Order not found');
        }

        // Get order items with product details
        $orderItems = $this->orderItemModel->getOrderItemsWithProducts($order['id']);

        $data = [
            'title' => 'Order #' . $orderNumber . ' - Nandini Hub',
            'order' => $order,
            'orderItems' => $orderItems
        ];

        return view('orders/show', $data);
    }

    public function checkout()
    {
        if (!session()->get('user_id')) {
            session()->set('redirect_to', '/checkout');
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $cartItems = $this->cartModel->getCartItems($userId);

        if (empty($cartItems)) {
            session()->setFlashdata('error', 'Your cart is empty');
            return redirect()->to('/cart');
        }

        $cartTotal = $this->cartModel->getCartTotal($userId);
        $user = $this->userModel->find($userId);

        $data = [
            'title' => 'Checkout - Nandini Hub',
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal,
            'user' => $user
        ];

        return view('orders/checkout', $data);
    }

    public function processCheckout()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');

        // Debug: Log the incoming data
        log_message('info', 'Order processing attempt for user ID: ' . $userId);
        log_message('info', 'POST data: ' . json_encode($this->request->getPost()));

        $cartItems = $this->cartModel->getCartItems($userId);

        if (empty($cartItems)) {
            session()->setFlashdata('error', 'Your cart is empty');
            return redirect()->to('/cart');
        }

        // Validate form data
        $rules = [
            'shipping_address' => 'required|min_length[10]|max_length[500]',
            'payment_method' => 'required|in_list[cod,online]'
        ];

        if (!$this->validate($rules)) {
            log_message('error', 'Order validation failed: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get billing address (either from form or same as shipping)
        $billingAddress = $this->request->getPost('billing_address');
        if (empty($billingAddress)) {
            $billingAddress = $this->request->getPost('shipping_address');
        }

        // Calculate totals
        $subtotal = $this->cartModel->getCartTotal($userId);
        $shipping = $subtotal >= 500 ? 0 : 50;
        $tax = $subtotal * 0.18;
        $total = $subtotal + $shipping + $tax;

        // Create order data
        $orderData = [
            'user_id' => $userId,
            'total_amount' => $total,
            'shipping_amount' => $shipping,
            'tax_amount' => $tax,
            'discount_amount' => 0,
            'payment_method' => $this->request->getPost('payment_method'),
            'payment_status' => 'pending',
            'shipping_address' => trim($this->request->getPost('shipping_address')),
            'billing_address' => trim($billingAddress),
            'notes' => trim($this->request->getPost('notes')),
            'status' => 'pending'
        ];

        log_message('info', 'Order data prepared: ' . json_encode($orderData));

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Temporarily disable model validation since we're validating in controller
            $this->orderModel->skipValidation(true);

            // Insert order
            $orderId = $this->orderModel->insert($orderData);

            if (!$orderId) {
                $errors = $this->orderModel->errors();
                log_message('error', 'Order creation failed: ' . json_encode($errors));
                throw new \Exception('Failed to create order: ' . json_encode($errors));
            }

            log_message('info', 'Order created with ID: ' . $orderId);

            // Create order items
            if (!$this->orderItemModel->createOrderItems($orderId, $cartItems)) {
                $errors = $this->orderItemModel->errors();
                log_message('error', 'Order items creation failed: ' . json_encode($errors));
                throw new \Exception('Failed to create order items: ' . json_encode($errors));
            }

            log_message('info', 'Order items created successfully');

            // Update product stock
            foreach ($cartItems as $item) {
                $product = $this->productModel->find($item['product_id']);
                if (!$product) {
                    throw new \Exception('Product not found: ' . $item['product_id']);
                }

                if ($product['stock_quantity'] < $item['quantity']) {
                    throw new \Exception('Insufficient stock for product: ' . $product['name']);
                }

                $newStock = $product['stock_quantity'] - $item['quantity'];
                $this->productModel->update($item['product_id'], ['stock_quantity' => $newStock]);
            }

            log_message('info', 'Product stock updated successfully');

            // Clear cart
            $this->cartModel->clearCart($userId);

            log_message('info', 'Cart cleared successfully');

            $db->transComplete();

            if ($db->transStatus() === false) {
                log_message('error', 'Database transaction failed');
                throw new \Exception('Database transaction failed');
            }

            // Get order details for confirmation
            $order = $this->orderModel->find($orderId);

            if (!$order) {
                log_message('error', 'Order not found after creation: ' . $orderId);
                throw new \Exception('Order not found after creation');
            }

            log_message('info', 'Order completed successfully: ' . $order['order_number']);

            // Handle different payment methods
            if ($order['payment_method'] === 'online') {
                // For online payment, redirect to payment initiation
                session()->setFlashdata('info', 'Order created successfully! Please complete the payment to confirm your order.');
                return redirect()->to('/orders/' . $order['order_number'] . '?payment=pending');
            } else {
                // For COD, send confirmation email and redirect to order details
                $this->sendOrderConfirmationEmail($order, $cartItems);
                session()->setFlashdata('success', 'Order placed successfully! Order number: ' . $order['order_number']);
                return redirect()->to('/orders/' . $order['order_number']);
            }
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Order processing exception: ' . $e->getMessage());
            session()->setFlashdata('error', 'Failed to place order: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }



    private function sendOrderConfirmationEmail($order, $orderItems)
    {
        // Email functionality will be implemented in the email notifications section
        // For now, we'll just log the order
        log_message('info', 'Order confirmation email should be sent for order: ' . $order['order_number']);
    }

    public function cancelOrder($orderNumber)
    {
        // Check if user is logged in
        if (!session()->get('user_id')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Please login to cancel orders'
                ]);
            }
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $order = $this->orderModel->getOrderByNumber($orderNumber, $userId);

        // Check if order exists
        if (!$order) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order not found'
                ]);
            }
            session()->setFlashdata('error', 'Order not found');
            return redirect()->to('/orders');
        }

        // Check if order can be cancelled
        if (!$this->orderModel->canBeCancelled($order)) {
            $message = $this->orderModel->getCancellationReason($order);
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $message
                ]);
            }
            session()->setFlashdata('error', $message);
            return redirect()->to('/orders/' . $orderNumber);
        }

        // Attempt to cancel the order
        try {
            if ($this->orderModel->updateOrderStatus($order['id'], 'cancelled')) {
                // Restore product stock
                $orderItems = $this->orderItemModel->getOrderItems($order['id']);
                foreach ($orderItems as $item) {
                    $product = $this->productModel->find($item['product_id']);
                    if ($product) {
                        $newStock = $product['stock_quantity'] + $item['quantity'];
                        $this->productModel->update($item['product_id'], ['stock_quantity' => $newStock]);
                    }
                }

                $message = 'Order cancelled successfully';
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => $message
                    ]);
                }
                session()->setFlashdata('success', $message);
            } else {
                $message = 'Failed to cancel order. Please try again.';
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => $message
                    ]);
                }
                session()->setFlashdata('error', $message);
            }
        } catch (\Exception $e) {
            log_message('error', 'Order cancellation failed: ' . $e->getMessage());
            $message = 'An error occurred while cancelling the order. Please try again.';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $message
                ]);
            }
            session()->setFlashdata('error', $message);
        }

        return redirect()->to('/orders/' . $orderNumber);
    }
}
