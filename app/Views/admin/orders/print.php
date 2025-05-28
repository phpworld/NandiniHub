<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Invoice' ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            background: white;
            padding: 20px;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
        }

        .company-info {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #ff6b35;
            margin-bottom: 5px;
        }

        .invoice-header {
            margin-bottom: 30px;
        }

        .invoice-header .row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .invoice-header .col {
            flex: 1;
        }

        .invoice-header .col:last-child {
            text-align: right;
        }

        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .address-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .address-section>div {
            flex: 1;
            margin-right: 20px;
        }

        .address-section>div:last-child {
            margin-right: 0;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .invoice-table th,
        .invoice-table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        .invoice-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }

        .invoice-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .invoice-total {
            border-top: 2px solid #333;
            font-weight: bold;
            font-size: 14px;
        }

        .invoice-footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }

        .print-button:hover {
            background: #0056b3;
        }

        @media print {
            .print-button {
                display: none;
            }

            body {
                padding: 0;
            }

            @page {
                margin: 0.5in;
                size: A4;
            }
        }

        h5 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        address {
            font-style: normal;
            line-height: 1.5;
        }

        p {
            margin-bottom: 5px;
        }

        strong {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <button class="print-button" onclick="window.print()">
        <i class="fas fa-print"></i> Print Invoice
    </button>

    <div class="invoice-container">
        <!-- Company Header -->
        <div class="company-info">
            <?php
            $settingModel = new \App\Models\SettingModel();
            $siteName = $settingModel->getSetting('site_name', 'NANDINI HUB');
            $siteTagline = $settingModel->getSetting('site_tagline', 'Your Trusted Shopping Destination');
            $contactEmail = $settingModel->getSetting('contact_email', 'info@nandinihub.com');
            $contactPhone = $settingModel->getSetting('contact_phone', '+91 9876543210');
            ?>
            <div class="company-name"><?= strtoupper(esc($siteName)) ?></div>
            <div><?= esc($siteTagline) ?></div>
            <div>Email: <?= esc($contactEmail) ?> | Phone: <?= esc($contactPhone) ?></div>
            <div>Website: www.nandinihub.com</div>
        </div>

        <!-- Invoice Header -->
        <div class="invoice-header">
            <div class="row">
                <div class="col">
                    <h2 class="invoice-title">INVOICE</h2>
                    <p><strong>Invoice #:</strong> <?= esc($order['order_number']) ?></p>
                    <p><strong>Date:</strong> <?= date('F j, Y', strtotime($order['created_at'])) ?></p>
                    <p><strong>Status:</strong> <?= ucfirst($order['status']) ?></p>
                </div>
                <div class="col">
                    <h4>Bill To:</h4>
                    <p>
                        <strong><?= esc($order['first_name'] . ' ' . $order['last_name']) ?></strong><br>
                        <?= esc($order['email']) ?><br>
                        <?php if (!empty($order['phone'])): ?>
                            <?= esc($order['phone']) ?><br>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Addresses -->
        <?php if (!empty($order['shipping_address'])): ?>
            <?php $shippingAddress = json_decode($order['shipping_address'], true); ?>
            <div class="address-section">
                <div>
                    <h5>Shipping Address:</h5>
                    <address>
                        <?= esc($shippingAddress['name'] ?? '') ?><br>
                        <?= esc($shippingAddress['address'] ?? '') ?><br>
                        <?= esc($shippingAddress['city'] ?? '') ?>, <?= esc($shippingAddress['state'] ?? '') ?><br>
                        <?= esc($shippingAddress['pincode'] ?? '') ?><br>
                        <?php if (!empty($shippingAddress['phone'])): ?>
                            Phone: <?= esc($shippingAddress['phone']) ?>
                        <?php endif; ?>
                    </address>
                </div>
                <div>
                    <h5>Payment Information:</h5>
                    <p>
                        <strong>Method:</strong> <?= $order['payment_method'] === 'cod' ? 'Cash on Delivery' : 'Online Payment' ?><br>
                        <strong>Status:</strong>
                        <?php
                        $paymentStatus = $order['payment_status'] ?? 'pending';
                        $statusText = match ($paymentStatus) {
                            'paid' => 'Paid',
                            'confirmed' => 'Confirmed',
                            'pending' => 'Pending',
                            default => ucfirst($paymentStatus)
                        };
                        echo $statusText;
                        ?>
                        <?php if ($order['payment_method'] === 'cod'): ?>
                            <br><small style="color: #666;">
                                <?php if ($paymentStatus === 'pending'): ?>
                                    Cash payment pending
                                <?php elseif ($paymentStatus === 'confirmed'): ?>
                                    Cash payment received
                                <?php elseif ($paymentStatus === 'paid'): ?>
                                    Cash payment completed
                                <?php endif; ?>
                            </small>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Invoice Items Table -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th style="width: 50%;">Product Description</th>
                    <th style="width: 15%;">SKU</th>
                    <th style="width: 10%;" class="text-right">Price</th>
                    <th style="width: 10%;" class="text-right">Qty</th>
                    <th style="width: 15%;" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orderItems)): ?>
                    <?php foreach ($orderItems as $item): ?>
                        <tr>
                            <td>
                                <strong><?= esc($item['product_name']) ?></strong>
                            </td>
                            <td><?= esc($item['product_sku'] ?? 'N/A') ?></td>
                            <td class="text-right">₹<?= number_format($item['price'], 2) ?></td>
                            <td class="text-right"><?= $item['quantity'] ?></td>
                            <td class="text-right">₹<?= number_format($item['total'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right"><strong>Subtotal:</strong></td>
                    <td class="text-right">₹<?= number_format(($order['total_amount'] - ($order['shipping_amount'] ?? 0) - ($order['tax_amount'] ?? 0) + ($order['discount_amount'] ?? 0)), 2) ?></td>
                </tr>
                <?php if (!empty($order['discount_amount']) && $order['discount_amount'] > 0): ?>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Discount:</strong></td>
                        <td class="text-right">-₹<?= number_format($order['discount_amount'], 2) ?></td>
                    </tr>
                <?php endif; ?>
                <?php if (!empty($order['tax_amount']) && $order['tax_amount'] > 0): ?>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Tax:</strong></td>
                        <td class="text-right">₹<?= number_format($order['tax_amount'], 2) ?></td>
                    </tr>
                <?php endif; ?>
                <?php if (!empty($order['shipping_amount']) && $order['shipping_amount'] > 0): ?>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Shipping:</strong></td>
                        <td class="text-right">₹<?= number_format($order['shipping_amount'], 2) ?></td>
                    </tr>
                <?php endif; ?>
                <tr class="invoice-total">
                    <td colspan="4" class="text-right"><strong>TOTAL AMOUNT:</strong></td>
                    <td class="text-right"><strong>₹<?= number_format($order['total_amount'], 2) ?></strong></td>
                </tr>
            </tfoot>
        </table>

        <!-- Order Notes -->
        <?php if (!empty($order['notes'])): ?>
            <div style="margin-top: 20px;">
                <h5>Order Notes:</h5>
                <p><?= esc($order['notes']) ?></p>
            </div>
        <?php endif; ?>

        <!-- Invoice Footer -->
        <div class="invoice-footer">
            <p><strong>Thank you for your business!</strong></p>
            <p>This is a computer-generated invoice. No signature required.</p>
            <p>For any queries, please contact us at <?= esc($contactEmail) ?> or <?= esc($contactPhone) ?></p>
            <hr style="margin: 10px 0;">
            <p><?= esc($siteName) ?> - <?= esc($siteTagline) ?></p>
        </div>
    </div>
</body>

</html>