<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Order Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #000000 0%, #cc0000 100%);
            color: #fff;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 2px;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border: 1px solid #e3e6f0;
            border-top: none;
            border-radius: 0 0 8px 8px;
        }
        .order-info {
            background: #fff;
            padding: 20px;
            border: 1px solid #e3e6f0;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .order-number {
            font-size: 20px;
            font-weight: bold;
            color: #cc0000;
            margin-bottom: 10px;
        }
        .field {
            margin-bottom: 15px;
        }
        .field-label {
            font-weight: bold;
            color: #000;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }
        .field-value {
            background: #fff;
            padding: 10px 15px;
            border: 1px solid #e3e6f0;
            border-radius: 5px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items-table th, .items-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e3e6f0;
        }
        .items-table th {
            background: #000;
            color: #fff;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }
        .total-row {
            font-weight: bold;
            background: #f5f5f5;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BOLDROOTS - NEW ORDER</h1>
    </div>
    
    <div class="content">
        <div class="order-info">
            <div class="order-number">Order #{{ $order->order_number }}</div>
            <p>A new order has been placed on {{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>
        
        <div class="field">
            <div class="field-label">Customer</div>
            <div class="field-value">
                @if($order->user)
                    {{ $order->user->name }}<br>
                    {{ $order->user->email }}<br>
                    {{ $order->user->phone ?? 'N/A' }}
                @else
                    {{ $order->guest_name }}<br>
                    {{ $order->guest_email }}<br>
                    {{ $order->guest_phone ?? 'N/A' }}
                @endif
            </div>
        </div>
        
        <div class="field">
            <div class="field-label">Shipping Address</div>
            <div class="field-value">
                @if($order->user)
                    {{ $order->user->address_line1 ?? 'N/A' }}
                @else
                    {{ $order->guest_address_line1 ?? 'N/A' }}
                    @if($order->guest_city), {{ $order->guest_city }}@endif
                    @if($order->guest_postal_code) {{ $order->guest_postal_code }}@endif
                @endif
            </div>
        </div>
        
        <div class="field">
            <div class="field-label">Payment Method</div>
            <div class="field-value">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</div>
        </div>
        
        <table class="items-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        {{ $item->product_name ?? ($item->product->name ?? 'Product') }}
                        @if($item->variant_details)
                            <br><small>
                                @if(isset($item->variant_details['color']))
                                    Color: {{ $item->variant_details['color'] }}
                                @endif
                                @if(isset($item->variant_details['size']))
                                    @if(isset($item->variant_details['color'])) | @endif
                                    Size: {{ $item->variant_details['size'] }}
                                @endif
                            </small>
                        @elseif($item->variant)
                            <br><small>
                                @if($item->variant->color)
                                    Color: {{ $item->variant->color->value }}
                                @endif
                                @if($item->variant->size)
                                    @if($item->variant->color) | @endif
                                    Size: {{ $item->variant->size->value }}
                                @endif
                            </small>
                        @endif
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 2) }} €</td>
                    <td>{{ number_format($item->quantity * $item->price, 2) }} €</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="3" style="text-align: right;">Subtotal</td>
                    <td>{{ number_format($order->subtotal, 2) }} €</td>
                </tr>
                @if($order->shipping_cost > 0)
                <tr>
                    <td colspan="3" style="text-align: right;">Shipping</td>
                    <td>{{ number_format($order->shipping_cost, 2) }} €</td>
                </tr>
                @endif
                @if($order->discount > 0)
                <tr>
                    <td colspan="3" style="text-align: right;">Discount</td>
                    <td>-{{ number_format($order->discount, 2) }} €</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">TOTAL</td>
                    <td>{{ number_format($order->total, 2) }} €</td>
                </tr>
            </tbody>
        </table>
        
        @if($order->notes)
        <div class="field">
            <div class="field-label">Notes</div>
            <div class="field-value">{{ $order->notes }}</div>
        </div>
        @endif
    </div>
    
    <div class="footer">
        <p>This email was sent from the BOLDROOTS order system.</p>
    </div>
</body>
</html>
