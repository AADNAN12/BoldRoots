<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture {{ $invoice->invoice_number }}</title>
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
        .invoice-info-section { width: 55%; vertical-align: top; }
        .company-info-section { width: 45%; text-align: right; vertical-align: top; }
        
        .company-name { font-size: 16px; font-weight: bold; color: #1a1a1a; margin-bottom: 5px; text-transform: uppercase; }
        .invoice-label { font-size: 26px; font-weight: bold; color: #000; margin-bottom: 8px; }

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

        /* Badge Statut */
        .status-badge {
            padding: 4px 8px;
            border: 1px solid #ccc;
            font-weight: bold;
            font-size: 10px;
            display: inline-block;
            margin-top: 10px;
            text-transform: uppercase;
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
            <td class="invoice-info-section">
                <div class="invoice-label">FACTURE</div>
                <div style="font-size: 12px;">
                    <strong>N°:</strong> {{ $invoice->invoice_number }}<br>
                    <strong>Date:</strong> {{ $invoice->invoice_date->format('d/m/Y') }}<br>
                    <strong>Échéance:</strong> {{ $invoice->due_date->format('d/m/Y') }}
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

    <table class="table address-container">
        <tr>
            <td class="address-box">
                <div class="address-title">Facturé à</div>
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
                <strong>Paiement:</strong> {{ $order->payment_method ?? 'Virement/Carte' }}
            </td>
        </tr>
    </table>

    <table class="table items-table">
        <thead>
            <tr>
                <th>Désignation</th>
                <th style="width: 50px;" class="text-right">Qté</th>
                <th style="width: 100px;" class="text-right">Prix Unitaire</th>
                <th style="width: 100px;" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>
                        <div style="font-weight: bold;">{{ $item->product_name }}</div>
                        <div style="color: #777; font-size: 9px;">Réf: {{ $item->product_sku }}</div>
                    </td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->price, 2, ',', ' ') }} DH</td>
                    <td class="text-right">{{ number_format($item->total, 2, ',', ' ') }} DH</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="table">
        <tr>
            <td style="width: 50%; vertical-align: top; padding-top: 20px;">
                @if($invoice->notes)
                    <div class="address-title" style="border: none;">Notes</div>
                    <div style="font-size: 10px; color: #666;">{{ $invoice->notes }}</div>
                @endif
            </td>
            <td style="width: 50%;">
                <table class="table totals-table">
                    <tr>
                        <td class="text-right">Sous-total :</td>
                        <td class="text-right">{{ number_format($invoice->subtotal, 2, ',', ' ') }} DH</td>
                    </tr>
                    @if($invoice->discount > 0)
                    <tr>
                        <td class="text-right">Remise :</td>
                        <td class="text-right">-{{ number_format($invoice->discount, 2, ',', ' ') }} DH</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="text-right">Frais de port :</td>
                        <td class="text-right">{{ number_format($invoice->shipping_cost, 2, ',', ' ') }} DH</td>
                    </tr>
                    <tr class="total-row-bold">
                        <td class="text-right" style="padding-top: 10px;">TOTAL TTC :</td>
                        <td class="text-right" style="padding-top: 10px;">{{ number_format($invoice->total, 2, ',', ' ') }} DH</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
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