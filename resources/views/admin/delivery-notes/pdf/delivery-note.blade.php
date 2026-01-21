<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bon de Livraison {{ $deliveryNote->delivery_number }}</title>
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
            border-bottom: 2px solid #17a2b8;
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
            color: #17a2b8;
            margin-bottom: 5px;
        }
        .delivery-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .delivery-number {
            font-size: 16px;
            color: #666;
            margin-bottom: 5px;
        }
        .info-section {
            margin-bottom: 30px;
        }
        .info-row {
            display: table;
            width: 100%;
        }
        .info-col {
            display: table-cell;
            vertical-align: top;
            width: 50%;
            padding: 15px;
            background: #f8f9fa;
        }
        .info-col h3 {
            font-size: 14px;
            margin-bottom: 10px;
            color: #17a2b8;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table thead {
            background: #17a2b8;
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
        .total-row {
            background: #e9ecef;
            font-weight: bold;
        }
        .signature-section {
            margin-top: 50px;
        }
        .signature-row {
            display: table;
            width: 100%;
        }
        .signature-col {
            display: table-cell;
            width: 50%;
            padding: 10px;
        }
        .signature-box {
            border: 1px solid #dee2e6;
            height: 100px;
            margin-top: 10px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
            background: #d4edda;
            color: #155724;
        }
        .tracking-info {
            background: #d1ecf1;
            border-left: 4px solid #17a2b8;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-row">
                <div class="header-col">
                    <div class="delivery-title">BON DE LIVRAISON</div>
                    <div class="delivery-number">N° {{ $deliveryNote->delivery_number }}</div>
                    <div>Date: {{ $deliveryNote->delivery_date->format('d/m/Y') }}</div>
                </div>
                <div class="header-col right">
                    @if($company)
                        <div class="company-name">{{ $company->name ?? 'Votre Entreprise' }}</div>
                        <div>{{ $company->address ?? 'Adresse de l\'entreprise' }}</div>
                        <div>{{ $company->postal_code ?? '' }} {{ $company->city ?? '' }}</div>
                        <div>Tél: {{ $company->phone ?? '+212 XXX XXX XXX' }}</div>
                        <div>Email: {{ $company->email ?? 'contact@entreprise.com' }}</div>
                        @if($company->tax_number)
                            <div>ICE: {{ $company->tax_number }}</div>
                        @endif
                    @else
                        <div class="company-name">Votre Entreprise</div>
                        <div>Adresse de l'entreprise</div>
                        <div>Ville, Code Postal</div>
                        <div>Tél: +212 XXX XXX XXX</div>
                    @endif
                </div>
            </div>
        </div>

        @if($deliveryNote->carrier_name || $deliveryNote->tracking_number)
            <div class="tracking-info">
                <strong>Informations de Suivi</strong><br>
                @if($deliveryNote->carrier_name)
                    Transporteur: {{ $deliveryNote->carrier_name }}<br>
                @endif
                @if($deliveryNote->tracking_number)
                    N° de Suivi: <strong>{{ $deliveryNote->tracking_number }}</strong>
                @endif
            </div>
        @endif

        <div class="info-section">
            <div class="info-row">
                <div class="info-col">
                    <h3>Livraison à</h3>
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
                <div class="info-col">
                    <h3>Informations Commande</h3>
                    <strong>Commande: {{ $order->order_number }}</strong><br>
                    Date commande: {{ $order->created_at->format('d/m/Y') }}<br>
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
                    <th>SKU</th>
                    <th class="text-center">Quantité</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product_name }}</strong>
                            @if($item->variant_details)
                                <br>
                                <small style="color: #666;">
                                    @foreach($item->variant_details['attributes'] ?? [] as $key => $value)
                                        {{ $key }}: {{ $value }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                </small>
                            @endif
                        </td>
                        <td>{{ $item->product_sku }}</td>
                        <td class="text-center"><strong>{{ $item->quantity }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="2" class="text-right">Total Articles:</td>
                    <td class="text-center"><strong>{{ $order->items->sum('quantity') }}</strong></td>
                </tr>
            </tfoot>
        </table>

        @if($deliveryNote->notes)
            <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin-bottom: 20px;">
                <strong>Notes:</strong><br>
                {{ $deliveryNote->notes }}
            </div>
        @endif

        @if($deliveryNote->status == 'delivered' && $deliveryNote->delivered_at)
            <div class="alert">
                <strong>Livraison Confirmée</strong><br>
                Date: {{ $deliveryNote->delivered_at->format('d/m/Y à H:i') }}<br>
                @if($deliveryNote->recipient_name)
                    Reçu par: {{ $deliveryNote->recipient_name }}
                @endif
            </div>
        @endif

        <div class="signature-section">
            <div class="signature-row">
                <div class="signature-col">
                    <strong>Signature du Livreur</strong>
                    <div class="signature-box"></div>
                    <div style="margin-top: 5px;">
                        Date: _______________
                    </div>
                </div>
                <div class="signature-col">
                    <strong>Signature du Destinataire</strong>
                    <div class="signature-box"></div>
                    <div style="margin-top: 5px;">
                        Nom: _______________
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>Merci pour votre confiance !</p>
            @if($company && $company->tax_number)
                <p>ICE: {{ $company->tax_number }}</p>
            @endif
            <p>Ce bon de livraison est généré électroniquement.</p>
        </div>
    </div>
</body>
</html>
