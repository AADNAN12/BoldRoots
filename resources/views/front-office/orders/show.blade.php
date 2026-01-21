@extends('front-office.layouts.app')

@section('title', 'Commande #' . $order->order_number)

@section('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css">
    <style>
        .order-header {
            background: #000;
            color: #fff;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .order-header h2 {
            font-weight: bold;
            color:white;
            letter-spacing: 2px;
            margin: 0;
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
        }
        .product-item {
            display: flex;
            padding: 20px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .product-item:last-child {
            border-bottom: none;
        }
        .product-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #f0f0f0;
        }
        .status-badge {
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-block;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-processing { background: #d1ecf1; color: #0c5460; }
        .status-shipped { background: #cce5ff; color: #004085; }
        .status-delivered { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
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
        }
        .btn-action:hover {
            background: #ca1515;
            color: #fff;
            transform: scale(1.05);
        }
        .btn-back {
            background: transparent;
            color: #000 !important;
            border: 2px solid #000;
        }
        .btn-back:hover {
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
            font-size: 16px;
        }
        .total-row.final {
            border-top: 2px solid #000;
            padding-top: 15px;
            margin-top: 10px;
            font-size: 24px;
            font-weight: bold;
            color: #ca1515;
        }
        .info-label {
            font-weight: 600;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 16px;
            color: #000;
            font-weight: 500;
        }
    </style>
@endsection

@section('content')
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option" style="margin-top: 130px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Commande #{{ $order->order_number }}</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Home</a>
                            <a href="{{ route('orders.index') }}">Mes Commandes</a>
                            <span>Détails</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Order Details Section Begin -->
    <section class="shop spad">
        <div class="container">
            <!-- Order Header -->
            <div class="order-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h2>COMMANDE #{{ $order->order_number }}</h2>
                        <p style="margin: 10px 0 0 0; opacity: 0.8;">Passée le {{ $order->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="{{ route('orders.index') }}" class="btn-action me-2">
                            <i class="fa fa-arrow-left"></i> RETOUR
                        </a>
                        @if($order->invoice && $order->invoice->pdf_path)
                            <a href="{{ route('orders.download-invoice', $order) }}" class="btn-action">
                                <i class="fa fa-download"></i> FACTURE
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Articles Commandés -->
                    <div class="info-box">
                        <h5><i class="fa fa-shopping-bag"></i> ARTICLES COMMANDÉS</h5>
                        @foreach($order->items as $item)
                            <div class="product-item">
                                @if($item->product && $item->product->images->first())
                                    <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                                         alt="{{ $item->product_name }}" 
                                         class="product-img me-3">
                                @else
                                    <div class="product-img me-3 d-flex align-items-center justify-content-center" style="background: #f0f0f0;">
                                        <i class="fa fa-image fa-2x text-muted"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <h6 style="font-weight: bold; margin-bottom: 5px;">{{ $item->product_name }}</h6>
                                    @if($item->variant_details)
                                        <p class="text-muted mb-1" style="font-size: 13px;">
                                            @foreach($item->variant_details['attributes'] ?? [] as $key => $value)
                                                {{ $key }}: {{ $value }}{{ !$loop->last ? ' | ' : '' }}
                                            @endforeach
                                        </p>
                                    @endif
                                    <p class="text-muted mb-0" style="font-size: 12px;">SKU: {{ $item->product_sku }}</p>
                                </div>
                                <div class="text-center" style="min-width: 100px;">
                                    <div style="font-size: 14px; color: #666;">{{ number_format($item->price, 2) }} DH</div>
                                    <div style="font-size: 12px; color: #999;">x {{ $item->quantity }}</div>
                                </div>
                                <div class="text-end" style="min-width: 120px;">
                                    <div style="font-size: 18px; font-weight: bold; color: #000;">{{ number_format($item->total, 2) }} DH</div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Total Section -->
                        <div class="total-section">
                            <div class="total-row">
                                <span>Sous-total:</span>
                                <strong>{{ number_format($order->subtotal, 2) }} DH</strong>
                            </div>
                            @if($order->discount > 0)
                                <div class="total-row" style="color: #28a745;">
                                    <span>
                                        Réduction:
                                        @if($order->promotion)
                                            <br><small style="color: #666;">Promotion: {{ $order->promotion->name }}</small>
                                        @endif
                                        @if($order->coupon)
                                            <br><small style="color: #666;">Coupon: {{ $order->coupon->code }}</small>
                                        @endif
                                    </span>
                                    <strong>-{{ number_format($order->discount, 2) }} DH</strong>
                                </div>
                            @endif
                            <div class="total-row">
                                <span>Livraison:</span>
                                <strong>{{ number_format($order->shipping_cost, 2) }} DH</strong>
                            </div>
                            <div class="total-row final">
                                <span>TOTAL:</span>
                                <span>{{ number_format($order->total, 2) }} DH</span>
                            </div>
                        </div>
                    </div>

                    <!-- Adresse de Livraison -->
                    <div class="info-box">
                        <h5><i class="fa fa-map-marker"></i> ADRESSE DE LIVRAISON</h5>
                        @if($order->user_id && $order->user)
                            <div>
                                <strong style="font-size: 16px;">{{ $order->user->name }}</strong><br>
                                <p style="margin: 10px 0 0 0; line-height: 1.8; color: #666;">
                                    {{ $order->user->address_line1 }}<br>
                                    @if($order->user->address_line2)
                                        {{ $order->user->address_line2 }}<br>
                                    @endif
                                    @if($order->user->postal_code && $order->user->city)
                                        {{ $order->user->postal_code }} {{ $order->user->city }}<br>
                                    @endif
                                    <strong>Tél:</strong> {{ $order->user->phone }}<br>
                                    <strong>Email:</strong> {{ $order->user->email }}
                                </p>
                            </div>
                        @elseif($order->guest_name)
                            <div>
                                <strong style="font-size: 16px;">{{ $order->guest_name }}</strong><br>
                                <p style="margin: 10px 0 0 0; line-height: 1.8; color: #666;">
                                    {{ $order->guest_address_line1 }}<br>
                                    @if($order->guest_address_line2)
                                        {{ $order->guest_address_line2 }}<br>
                                    @endif
                                    @if($order->guest_postal_code && $order->guest_city)
                                        {{ $order->guest_postal_code }} {{ $order->guest_city }}<br>
                                    @endif
                                    <strong>Tél:</strong> {{ $order->guest_phone }}<br>
                                    <strong>Email:</strong> {{ $order->guest_email }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Informations de Commande -->
                    <div class="info-box">
                        <h5><i class="fa fa-info-circle"></i> INFORMATIONS</h5>
                        
                        <div class="mb-3">
                            <div class="info-label">Statut</div>
                            @php
                                $statusClasses = [
                                    'pending' => 'status-pending',
                                    'processing' => 'status-processing',
                                    'shipped' => 'status-shipped',
                                    'delivered' => 'status-delivered',
                                    'cancelled' => 'status-cancelled'
                                ];
                                $statusLabels = [
                                    'pending' => 'En attente',
                                    'processing' => 'En traitement',
                                    'shipped' => 'Expédiée',
                                    'delivered' => 'Livrée',
                                    'cancelled' => 'Annulée'
                                ];
                            @endphp
                            <span class="status-badge {{ $statusClasses[$order->status] ?? '' }}">
                                {{ $statusLabels[$order->status] ?? $order->status }}
                            </span>
                        </div>

                        <div class="mb-3">
                            <div class="info-label">Paiement</div>
                            @php
                                $paymentMethods = [
                                    'cash_on_delivery' => 'À la livraison',
                                    'credit_card' => 'Carte bancaire',
                                    'bank_transfer' => 'Virement'
                                ];
                                $paymentStatusClasses = [
                                    'pending' => 'status-pending',
                                    'paid' => 'status-delivered',
                                    'failed' => 'status-cancelled'
                                ];
                                $paymentLabels = [
                                    'pending' => 'En attente',
                                    'paid' => 'Payé',
                                    'failed' => 'Échoué'
                                ];
                            @endphp
                            <div class="info-value">{{ $paymentMethods[$order->payment_method] ?? $order->payment_method }}</div>
                            <span class="status-badge {{ $paymentStatusClasses[$order->payment_status] ?? '' }}" style="margin-top: 5px;">
                                {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                            </span>
                        </div>

                        @if($order->shipped_at)
                            <div class="mb-3">
                                <div class="info-label">Expédiée le</div>
                                <div class="info-value">{{ $order->shipped_at->format('d/m/Y H:i') }}</div>
                            </div>
                        @endif

                        @if($order->delivered_at)
                            <div class="mb-3">
                                <div class="info-label">Livrée le</div>
                                <div class="info-value">{{ $order->delivered_at->format('d/m/Y H:i') }}</div>
                            </div>
                        @endif
                    </div>

                    @if($order->deliveryNote && $order->deliveryNote->tracking_number)
                        <div class="info-box" style="background: #e7f3ff; border-color: #0066cc;">
                            <h5 style="color: #0066cc;"><i class="fa fa-truck"></i> SUIVI DE LIVRAISON</h5>
                            <div class="mb-2">
                                <div class="info-label">Transporteur</div>
                                <div class="info-value">{{ $order->deliveryNote->carrier_name ?? '-' }}</div>
                            </div>
                            <div class="mb-3">
                                <div class="info-label">N° de suivi</div>
                                <div class="info-value" style="font-family: monospace; background: #fff; padding: 8px; border-radius: 4px;">
                                    {{ $order->deliveryNote->tracking_number }}
                                </div>
                            </div>
                            <a href="{{ route('orders.track', $order) }}" class="btn-action" style="width: 100%; text-align: center;">
                                <i class="fa fa-map-marker"></i> SUIVRE MA COMMANDE
                            </a>
                        </div>
                    @endif

                    @if(in_array($order->status, ['pending', 'processing']))
                        <div class="info-box" style="background: #fff5f5; border-color: #dc3545;">
                            <h5 style="color: #dc3545;"><i class="fa fa-exclamation-triangle"></i> ANNULER LA COMMANDE</h5>
                            <p style="color: #666; font-size: 14px; margin-bottom: 15px;">
                                Vous pouvez annuler votre commande tant qu'elle n'a pas été expédiée.
                            </p>
                            <button type="button" class="btn-action" style="width: 100%; background: #dc3545;" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                <i class="fa fa-times"></i> ANNULER LA COMMANDE
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- Order Details Section End -->

    <!-- Modal Annulation -->
    <div class="modal fade" id="cancelModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('orders.cancel', $order) }}" method="POST">
                    @csrf
                    <div class="modal-header" style="background: #000; color: #fff;">
                        <h5 class="modal-title" style="font-weight: bold; letter-spacing: 1px;">ANNULER LA COMMANDE</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p style="font-size: 16px; margin-bottom: 20px;">Êtes-vous sûr de vouloir annuler cette commande ?</p>
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600;">Raison (optionnel)</label>
                            <textarea name="reason" class="form-control" rows="3" placeholder="Indiquez la raison de l'annulation..." style="border-radius: 8px;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-action btn-back" data-bs-dismiss="modal">FERMER</button>
                        <button type="submit" class="btn-action" style="background: #dc3545;">CONFIRMER</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
