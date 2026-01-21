<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        .header-row {
            display: table;
            width: 100%;
        }
        .header-col {
            display: table-cell;
            vertical-align: top;
            width: 50%;
        }
        .header-col.right {
            text-align: right;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .invoice-number {
            font-size: 16px;
            color: #666;
            margin-bottom: 5px;
        }
        .addresses {
            margin-bottom: 30px;
        }
        .address-row {
            display: table;
            width: 100%;
        }
        .address-col {
            display: table-cell;
            vertical-align: top;
            width: 50%;
            padding: 15px;
            background: #f8f9fa;
        }
        .address-col h3 {
            font-size: 14px;
            margin-bottom: 10px;
            color: #007bff;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table thead {
            background: #007bff;
            color: white;
        }
        .items-table th,
        .items-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .items-table th {
            font-weight: bold;
        }
        .items-table tbody tr:hover {
            background: #f8f9fa;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals {
            margin-top: 20px;
            float: right;
            width: 300px;
        }
        .totals-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }
        .totals-label {
            display: table-cell;
            text-align: right;
            padding-right: 20px;
        }
        .totals-value {
            display: table-cell;
            text-align: right;
            font-weight: bold;
        }
        .total-final {
            border-top: 2px solid #007bff;
            padding-top: 10px;
            margin-top: 10px;
            font-size: 18px;
            color: #007bff;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #666;
            font-size: 10px;
            clear: both;
        }
        .notes {
            margin-top: 30px;
            padding: 15px;
            background: #fff3cd;
            border-left: 4px solid #ffc107;
        }
        .notes h4 {
            margin-bottom: 5px;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-row">
                <div class="header-col">
                    <div class="invoice-title">FACTURE</div>
                    <div class="invoice-number">N° {{ $invoice->invoice_number }}</div>
                    <div>Date: {{ $invoice->invoice_date->format('d/m/Y') }}</div>
                    <div>Échéance: {{ $invoice->due_date->format('d/m/Y') }}</div>
                </div>
                <div class="header-col right">
                    <div style="font-size: 14px; color: #666;">
                        <strong>Statut:</strong> 
                        @if($invoice->status === 'paid')
                            <span style="color: #28a745;">PAYÉE</span>
                        @elseif($invoice->status === 'sent')
                            <span style="color: #ffc107;">ENVOYÉE</span>
                        @elseif($invoice->status === 'cancelled')
                            <span style="color: #dc3545;">ANNULÉE</span>
                        @else
                            <span style="color: #6c757d;">BROUILLON</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="addresses">
            <div class="address-row">
                <div class="address-col">
                    <h3>Facturé à</h3>
                    @if($order->user_id && $order->user)
                        <strong>{{ $order->user->name }}</strong><br>
                        {{ $order->user->address_line1 }}<br>
                        @if($order->user->address_line2)
                            {{ $order->user->address_line2 }}<br>
                        @endif
                        @if($order->user->postal_code && $order->user->city)
                            {{ $order->user->postal_code }} {{ $order->user->city }}<br>
                        @endif
                        Tél: {{ $order->user->phone }}<br>
                        Email: {{ $order->user->email }}
                    @elseif($order->guest_name)
                        <strong>{{ $order->guest_name }}</strong><br>
                        {{ $order->guest_address_line1 }}<br>
                        @if($order->guest_address_line2)
                            {{ $order->guest_address_line2 }}<br>
                        @endif
                        @if($order->guest_postal_code && $order->guest_city)
                            {{ $order->guest_postal_code }} {{ $order->guest_city }}<br>
                        @endif
                        Tél: {{ $order->guest_phone }}<br>
                        Email: {{ $order->guest_email }}
                    @endif
                </div>
                <div class="address-col">
                    <h3>Commande</h3>
                    <strong>{{ $order->order_number }}</strong><br>
                    Date: {{ $order->created_at->format('d/m/Y') }}<br>
                    @if($order->user)
                        Client: {{ $order->user->name }}<br>
                        Email: {{ $order->user->email }}
                    @endif
                </div>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-center">Quantité</th>
                    <th class="text-right">Prix Unitaire</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product_name }}</strong><br>
                            <small style="color: #666;">SKU: {{ $item->product_sku }}</small>
                            @if($item->variant_details)
                                <br>
                                <small style="color: #666;">
                                    @foreach($item->variant_details['attributes'] ?? [] as $key => $value)
                                        {{ $key }}: {{ $value }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                </small>
                            @endif
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($item->price, 2) }} DH</td>
                        <td class="text-right">{{ number_format($item->total, 2) }} DH</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div class="totals-row">
                <div class="totals-label">Sous-total:</div>
                <div class="totals-value">{{ number_format($invoice->subtotal, 2) }} DH</div>
            </div>
            @if($invoice->discount > 0)
                <div class="totals-row" style="color: #28a745;">
                    <div class="totals-label">
                        Réduction:
                        @if($order->promotion)
                            <br><small style="font-weight: normal;">{{ $order->promotion->name }}</small>
                        @endif
                        @if($order->coupon)
                            <br><small style="font-weight: normal;">Coupon: {{ $order->coupon->code }}</small>
                        @endif
                    </div>
                    <div class="totals-value">-{{ number_format($invoice->discount, 2) }} DH</div>
                </div>
            @endif
            <div class="totals-row">
                <div class="totals-label">Frais de livraison:</div>
                <div class="totals-value">{{ number_format($invoice->shipping_cost, 2) }} DH</div>
            </div>
            <div class="totals-row total-final">
                <div class="totals-label">TOTAL TTC:</div>
                <div class="totals-value">{{ number_format($invoice->total, 2) }} DH</div>
            </div>
        </div>

        <div style="clear: both;"></div>

        @if($invoice->notes)
            <div class="notes">
                <h4>Notes:</h4>
                <p>{{ $invoice->notes }}</p>
            </div>
        @endif
    </div>
</body>
</html>
