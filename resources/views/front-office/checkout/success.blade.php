@extends('front-office.layouts.app')

@section('title', 'Commande Confirmée')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css">
    <style>
        .success-header {
            background: linear-gradient(135deg, #155724 0%, #28a745 100%);
            color: #fff;
            padding: 40px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
        }
        .success-header h2 {
            font-weight: bold;
            letter-spacing: 2px;
            color:white !important; 
            margin: 20px 0 10px 0;
            font-size: 32px;
        }
        .success-icon {
            font-size: 80px;
            color: #fff;
            margin-bottom: 20px;
        }
        .order-number {
            background: rgba(255, 255, 255, 0.2);
            padding: 15px 30px;
            border-radius: 25px;
            display: inline-block;
            margin-top: 15px;
            font-size: 18px;
            letter-spacing: 1px;
        }
        .info-box {
            background: #fff;
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 20px;
        }
        .info-box h5 {
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #000;
            font-size: 16px;
        }
        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #666;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            min-width: 150px;
        }
        .info-value {
            font-size: 14px;
            color: #000;
            font-weight: 500;
            flex: 1;
        }
        .product-item {
            display: flex;
            padding: 20px 0;
            border-bottom: 1px solid #f0f0f0;
            gap: 20px;
        }
        .product-item:last-child {
            border-bottom: none;
        }
        .product-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #f0f0f0;
        }
        .product-details {
            flex: 1;
        }
        .product-name {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .product-variant {
            font-size: 12px;
            color: #666;
        }
        .product-price {
            font-weight: 600;
            font-size: 14px;
            text-align: right;
        }
        .btn-action {
            background: #000;
            color: #fff;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            letter-spacing: 1px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
        }
        .btn-action:hover {
            background: #ca1515;
            color: #fff;
            transform: scale(1.05);
        }
        .btn-secondary-action {
            background: transparent;
            color: #000;
            border: 2px solid #000;
        }
        .btn-secondary-action:hover {
            background: #000;
            color: #fff;
        }
        .total-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 14px;
        }
        .total-row.final {
            border-top: 2px solid #000;
            margin-top: 10px;
            padding-top: 15px;
            font-weight: bold;
            font-size: 18px;
        }
        .payment-info {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .payment-info.bank {
            background: #d1ecf1;
            border-color: #17a2b8;
        }
        .email-notice {
            background: #e7f3ff;
            border: 1px solid #0066cc;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }
    </style>
@endsection

@section('content')
    <!-- Breadcrumb Section -->
    <section class="breadcrumb-option" style="margin-top: 130px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Commande Confirmée</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Accueil</a>
                            <a href="{{ route('products.index') }}">Boutique</a>
                            <span>Confirmation</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="shopping-cart spad">
        <div class="container">
            <!-- Success Header -->
            <div class="success-header">
                <i class="fas fa-check-circle success-icon"></i>
                <h2>COMMANDE CONFIRMÉE !</h2>
                <p style="margin: 10px 0;color:white; font-size: 16px; opacity: 0.9;">Merci pour votre commande. Nous avons bien reçu votre demande.</p>
                <div class="order-number">
                    <strong>N° DE COMMANDE :</strong> {{ $order->order_number }}
                </div>
            </div>

            <div class="row">
                <!-- Left Column - Order Details -->
                <div class="col-lg-8">
                    <!-- Shipping Information -->
                    <div class="info-box">
                        <h5><i class="fa fa-map-marker"></i> INFORMATIONS DE LIVRAISON</h5>
                        @if($order->user_id && $order->user)
                            <div class="info-row">
                                <span class="info-label">Nom</span>
                                <span class="info-value">{{ $order->user->name }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Adresse</span>
                                <span class="info-value">
                                    {{ $order->user->address_line1 }}
                                    @if($order->user->address_line2)
                                        <br>{{ $order->user->address_line2 }}
                                    @endif
                                </span>
                            </div>
                            @if($order->user->postal_code && $order->user->city)
                                <div class="info-row">
                                    <span class="info-label">Ville</span>
                                    <span class="info-value">{{ $order->user->postal_code }} {{ $order->user->city }}</span>
                                </div>
                            @endif
                            <div class="info-row">
                                <span class="info-label">Téléphone</span>
                                <span class="info-value">{{ $order->user->phone }}</span>
                            </div>
                        @elseif($order->guest_name)
                            <div class="info-row">
                                <span class="info-label">Nom</span>
                                <span class="info-value">{{ $order->guest_name }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Adresse</span>
                                <span class="info-value">
                                    {{ $order->guest_address_line1 }}
                                    @if($order->guest_address_line2)
                                        <br>{{ $order->guest_address_line2 }}
                                    @endif
                                </span>
                            </div>
                            @if($order->guest_postal_code && $order->guest_city)
                                <div class="info-row">
                                    <span class="info-label">Ville</span>
                                    <span class="info-value">{{ $order->guest_postal_code }} {{ $order->guest_city }}</span>
                                </div>
                            @endif
                            <div class="info-row">
                                <span class="info-label">Téléphone</span>
                                <span class="info-value">{{ $order->guest_phone }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Ordered Items -->
                    <div class="info-box">
                        <h5><i class="fa fa-shopping-bag"></i> ARTICLES COMMANDÉS</h5>
                        @foreach($order->items as $item)
                            <div class="product-item">
                                @php
                                    $image = $item->product && $item->product->images->first() 
                                        ? asset('storage/' . $item->product->images->first()->image_path)
                                        : asset('images/No-Product-Image-Available.webp');
                                @endphp
                                <img src="{{ $image }}" alt="{{ $item->product_name }}" class="product-img">
                                <div class="product-details">
                                    <div class="product-name">{{ $item->product_name }}</div>
                                    @if($item->variant_details)
                                        <div class="product-variant">
                                            @foreach($item->variant_details['attributes'] ?? [] as $key => $value)
                                                {{ $key }}: {{ $value }}
                                                @if(!$loop->last) | @endif
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="product-variant">Quantité: {{ $item->quantity }}</div>
                                </div>
                                <div class="product-price">
                                    {{ number_format($item->total, 2) }} DH
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Payment Information -->
                    @if($order->payment_method == 'cash_on_delivery')
                        <div class="payment-info">
                            <h5 style="margin-bottom: 10px; font-weight: bold;">
                                <i class="fas fa-money-bill-wave"></i> PAIEMENT À LA LIVRAISON
                            </h5>
                            <p style="margin: 0; font-size: 14px;">
                                Vous pourrez régler votre commande en espèces lors de la livraison.
                            </p>
                        </div>
                    @elseif($order->payment_method == 'bank_transfer')
                        <div class="payment-info bank">
                            <h5 style="margin-bottom: 10px; font-weight: bold;">
                                <i class="fas fa-university"></i> VIREMENT BANCAIRE
                            </h5>
                            <p style="margin: 0; font-size: 14px;">
                                Vous recevrez les coordonnées bancaires par email pour effectuer le virement.
                            </p>
                        </div>
                    @endif

                    <!-- Email Notice -->
                    <div class="email-notice">
                        <i class="fas fa-envelope" style="font-size: 20px; margin-right: 10px;"></i>
                        <strong>Un email de confirmation a été envoyé à :</strong><br>
                        <span style="font-size: 16px; color: #0066cc;">{{ $order->user->email ?? $order->guest_email ?? 'N/A' }}</span>
                    </div>
                </div>

                <!-- Right Column - Order Summary -->
                <div class="col-lg-4">
                    <div class="info-box">
                        <h5><i class="fa fa-file-text"></i> RÉCAPITULATIF</h5>
                        <div class="info-row">
                            <span class="info-label">Articles</span>
                            <span class="info-value">{{ $order->items->count() }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Sous-total</span>
                            <span class="info-value">{{ number_format($order->subtotal, 2) }} DH</span>
                        </div>
                        @if($order->discount > 0)
                            <div class="info-row">
                                <span class="info-label">Réduction</span>
                                <span class="info-value" style="color: #28a745;">-{{ number_format($order->discount, 2) }} DH</span>
                            </div>
                        @endif
                        <div class="info-row">
                            <span class="info-label">Livraison</span>
                            <span class="info-value">{{ number_format($order->shipping_cost, 2) }} DH</span>
                        </div>
                        <div class="total-section">
                            <div class="total-row final">
                                <span>TOTAL</span>
                                <span>{{ number_format($order->total, 2) }} DH</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="info-box">
                        <h5><i class="fa fa-bolt"></i> ACTIONS</h5>
                        <a href="{{ route('orders.show', $order) }}" class="btn-action w-100 mb-3 text-center">
                            <i class="fas fa-eye"></i> VOIR MA COMMANDE
                        </a>
                        <a href="{{ route('products.index') }}" class="btn-action btn-secondary-action w-100 text-center">
                            <i class="fas fa-shopping-bag"></i> CONTINUER MES ACHATS
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
