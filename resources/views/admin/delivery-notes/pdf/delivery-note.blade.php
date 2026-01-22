<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bon de Livraison {{ $deliveryNote->delivery_number }}</title>
    <style>
        @page { margin: 1cm; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        .wrapper { width: 100%; }
        .table { width: 100%; border-collapse: collapse; }
        
        /* Header réorganisé */
        .header-table { margin-bottom: 40px; }
        .delivery-info-section { width: 55%; vertical-align: top; }
        .company-info-section { width: 45%; text-align: right; vertical-align: top; }
        
        .company-name { font-size: 16px; font-weight: bold; color: #1a1a1a; margin-bottom: 5px; text-transform: uppercase; }
        .delivery-label { font-size: 26px; font-weight: bold; color: #000; margin-bottom: 8px; }

        /* Bloc Adresses */
        .address-container { margin-bottom: 40px; }
        .address-box { width: 48%; vertical-align: top; }
        .address-title { 
            text-transform: uppercase; 
            font-size: 9px; 
            font-weight: bold; 
            color: #888; 
            border-bottom: 1px solid #eee; 
            margin-bottom: 8px; 
            padding-bottom: 3px;
        }

        /* Tableau d'articles */
        .items-table { margin-top: 20px; }
        .items-table th { 
            background-color: #f9f9f9; 
            padding: 10px; 
            text-align: left; 
            border-bottom: 2px solid #333;
            text-transform: uppercase;
            font-size: 10px;
        }
        .items-table td { padding: 10px; border-bottom: 1px solid #eee; }
        
        /* Totaux */
        .totals-table { width: 250px; margin-left: auto; margin-top: 20px; }
        .totals-table td { padding: 5px 0; }
        .total-row-bold { font-size: 15px; font-weight: bold; border-top: 2px solid #000; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* Tracking info */
        .tracking-info {
            background: #f0f8ff;
            border: 1px solid #b3d9ff;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 10px;
        }

        /* Signature section */
        .signature-section { margin-top: 40px; }
        .signature-box { 
            border: 1px solid #ccc; 
            height: 80px; 
            margin-top: 10px;
            background: #fafafa;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #999;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <table class="table header-table">
        <tr>
            <td class="delivery-info-section">
                <div class="delivery-label">BON DE LIVRAISON</div>
                <div style="font-size: 12px;">
                    <strong>N°:</strong> {{ $deliveryNote->delivery_number }}<br>
                    <strong>Date:</strong> {{ $deliveryNote->delivery_date->format('d/m/Y') }}
                </div>
            </td>
            <td class="company-info-section">
                @if($company && $company->logo_path)
                    <div style="margin-bottom: 10px;">
                        <img src="{{ public_path('storage/' . $company->logo_path) }}" alt="Logo" style="max-width: 120px; max-height: 60px;">
                    </div>
                @endif
                <div class="company-name">{{ $company->company_name ?? 'VOTRE ENTREPRISE' }}</div>
                <div style="color: #555; font-size: 10px;">
                    @if($company)
                        {{ $company->address_line1 }}<br>
                        @if($company->address_line2)
                            {{ $company->address_line2 }}<br>
                        @endif
                        {{ $company->city }}, {{ $company->postal_code }}<br>
                        @if($company->tax_number)
                            ICE: {{ $company->tax_number }}<br>
                        @endif
                        @if($company->phone)
                            Tél: {{ $company->phone }}<br>
                        @endif
                        @if($company->email)
                            Email: {{ $company->email }}
                        @endif
                    @else
                        Ville, Pays<br>
                        ICE: 00000000000000<br>
                        Tél: +212 6 00 00 00 00<br>
                        Email: contact@votre-entreprise.com
                    @endif
                </div>
            </td>
        </tr>
    </table>

    @if($deliveryNote->carrier_name || $deliveryNote->tracking_number)
        <div class="tracking-info">
            <strong>Informations de Suivi:</strong>
            @if($deliveryNote->carrier_name)
                Transporteur: {{ $deliveryNote->carrier_name }}
            @endif
            @if($deliveryNote->tracking_number)
                | N° de Suivi: <strong>{{ $deliveryNote->tracking_number }}</strong>
            @endif
        </div>
    @endif

    <table class="table address-container">
        <tr>
            <td class="address-box">
                <div class="address-title">Livré à</div>
                @php $client = $order->user ?? (object)['name' => $order->guest_name, 'email' => $order->guest_email]; @endphp
                <div style="font-size: 12px; font-weight: bold;">{{ $client->name }}</div>
                {{ $order->user->address_line1 ?? $order->guest_address_line1 }}<br>
                {{ $order->user->city ?? $order->guest_city }} {{ $order->user->postal_code ?? $order->guest_postal_code }}<br>
                {{ $client->email }}<br>
                {{ $order->user->phone ?? $order->guest_phone }}
            </td>
            <td style="width: 4%;"></td>
            <td class="address-box">
                <div class="address-title">Détails de la commande</div>
                <strong>Commande:</strong> #{{ $order->order_number }}<br>
                <strong>Date:</strong> {{ $order->created_at->format('d/m/Y') }}<br>
                @if($deliveryNote->status == 'delivered' && $deliveryNote->delivered_at)
                    <strong>Livré le:</strong> {{ $deliveryNote->delivered_at->format('d/m/Y à H:i') }}
                @endif
            </td>
        </tr>
    </table>

    <table class="table items-table">
        <thead>
            <tr>
                <th>Désignation</th>
                <th style="width: 100px;">SKU</th>
                <th style="width: 80px;" class="text-center">Quantité</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>
                        <div style="font-weight: bold;">{{ $item->product_name }}</div>
                        <div style="color: #777; font-size: 9px;">Réf: {{ $item->product_sku }}</div>
                        @if($item->variant_details)
                            <div style="color: #666; font-size: 9px;">
                                @foreach($item->variant_details['attributes'] ?? [] as $key => $value)
                                    {{ $key }}: {{ $value }}{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            </div>
                        @endif
                    </td>
                    <td>{{ $item->product_sku }}</td>
                    <td class="text-center"><strong>{{ $item->quantity }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="table">
        <tr>
            <td style="width: 50%; vertical-align: top; padding-top: 20px;">
                @if($deliveryNote->notes)
                    <div class="address-title" style="border: none;">Notes</div>
                    <div style="font-size: 10px; color: #666;">{{ $deliveryNote->notes }}</div>
                @endif
                @if($deliveryNote->status == 'delivered' && $deliveryNote->delivered_at)
                    <div style="margin-top: 15px; padding: 10px; background: #d4edda; border-left: 3px solid #28a745; font-size: 10px;">
                        <strong>Livraison Confirmée</strong><br>
                        Date: {{ $deliveryNote->delivered_at->format('d/m/Y à H:i') }}<br>
                        @if($deliveryNote->recipient_name)
                            Reçu par: {{ $deliveryNote->recipient_name }}
                        @endif
                    </div>
                @endif
            </td>
            <td style="width: 50%;">
                <table class="table totals-table">
                    <tr class="total-row-bold">
                        <td class="text-right" style="padding-top: 10px;">Total Articles :</td>
                        <td class="text-right" style="padding-top: 10px;"><strong>{{ $order->items->sum('quantity') }}</strong></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="signature-section">
        <table class="table">
            <tr>
                <td style="width: 50%; padding: 10px;">
                    <strong style="font-size: 10px;">Signature du Livreur</strong>
                    <div class="signature-box"></div>
                    <div style="margin-top: 5px; font-size: 9px;">
                        Date: _______________
                    </div>
                </td>
                <td style="width: 50%; padding: 10px;">
                    <strong style="font-size: 10px;">Signature du Destinataire</strong>
                    <div class="signature-box"></div>
                    <div style="margin-top: 5px; font-size: 9px;">
                        Nom: _______________
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="footer">
    @if($company)
        {{ $company->company_name ?? $company->legal_name }}
        @if($company->registration_number)
            | RC: {{ $company->registration_number }}
        @endif
        @if($company->tax_number)
            | ICE: {{ $company->tax_number }}
        @endif
        <br>
        @if($company->address_line1)
            {{ $company->address_line1 }}, {{ $company->city }} {{ $company->postal_code }}
        @endif
    @else
        VOTRE ENTREPRISE | Capital Social: 100.000 DH | RC: 12345 | IF: 678910<br>
    @endif
    Document généré le {{ date('d/m/Y H:i') }}
</div>
</body>
</html>
