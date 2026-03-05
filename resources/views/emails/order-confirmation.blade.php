<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .email-wrapper {
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #000000 0%, #cc0000 100%);
            color: #fff;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .thank-you {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }
        .thank-you h2 {
            color: #cc0000;
            margin: 0 0 10px 0;
        }
        .order-info {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .order-number {
            font-size: 18px;
            font-weight: bold;
            color: #cc0000;
            margin-bottom: 10px;
        }
        .order-date {
            color: #666;
            font-size: 14px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #000;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #cc0000;
        }
        .info-box {
            background: #fff;
            padding: 15px;
            border: 1px solid #e3e6f0;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .info-row:last-child {
            margin-bottom: 0;
        }
        .info-label {
            font-weight: 600;
            color: #555;
        }
        .info-value {
            color: #333;
            text-align: right;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 14px;
        }
        .items-table th {
            background: #000;
            color: #fff;
            padding: 12px;
            text-align: left;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        .items-table tr:last-child td {
            border-bottom: none;
        }
        .product-name {
            font-weight: 600;
            color: #333;
        }
        .product-variant {
            font-size: 12px;
            color: #666;
            margin-top: 4px;
        }
        .text-right {
            text-align: right;
        }
        .total-section {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .total-row.grand-total {
            font-size: 18px;
            font-weight: bold;
            color: #cc0000;
            padding-top: 10px;
            border-top: 2px solid #cc0000;
            margin-top: 10px;
        }
        .footer {
            text-align: center;
            padding: 30px;
            background: #f9f9f9;
            border-top: 1px solid #eee;
        }
        .footer p {
            margin: 0 0 10px 0;
            color: #666;
            font-size: 13px;
        }
        .social-links {
            margin-top: 15px;
        }
        .social-links a {
            display: inline-block;
            margin: 0 8px;
            color: #cc0000;
            text-decoration: none;
            font-weight: 600;
        }
        .invoice-badge {
            display: inline-block;
            background: #cc0000;
            color: #fff;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }
        @media print {
            body {
                background: white;
            }
            .email-wrapper {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="header">
            <h1>{{ env("APP_NAME") }}</h1>
            <p>Order Confirmation</p>
        </div>
        
        <div class="content">
            <div class="thank-you">
                <h2>Thank You for Your Order!</h2>
                <p>We have received your order and are currently processing it. Thank you for your patience.</p>
            </div>
            
            <div class="order-info">
                <div class="order-number">Order #{{ $order->order_number }}</div>
                <div class="order-date">Ordered on {{ $order->created_at->format('F d, Y at h:i A') }}</div>
            </div>
            
            <div class="info-box" style="text-align: center; padding: 30px;">
                <p style="font-size: 16px; margin-bottom: 15px;">Your invoice is attached to this email.</p>
                <p style="color: #666;">Please find your detailed invoice in the attachment below.</p>
            </div>
        </div>
        
        <div class="footer">
            <p>If you have any questions about your order, please contact us at<br>
            <strong>{{ \App\Models\SiteSetting::get('contact_email', 'contact@boldroots.com') }}</strong></p>
            
            <p style="margin-top: 20px; font-size: 12px;">
                © {{ date('Y') }} {{ env("APP_NAME") }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
