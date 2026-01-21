@extends('front-office.layouts.app')

@section('title', 'Mes Commandes')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css">
    <style>
        .order-card {
            background: #fff;
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        .order-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        .order-number {
            font-size: 18px;
            font-weight: bold;
            color: #000;
            letter-spacing: 1px;
        }
        .order-status {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-processing { background: #d1ecf1; color: #0c5460; }
        .status-shipped { background: #cce5ff; color: #004085; }
        .status-delivered { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .order-total {
            font-size: 24px;
            font-weight: bold;
            color: #ca1515;
        }
        .product-mini-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #f0f0f0;
        }
        .btn-details {
            background: #000;
            color: #fff;
            padding: 10px 25px;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            letter-spacing: 1px;
            transition: all 0.3s;
        }
        .btn-details:hover {
            background: #ca1515;
            color: #fff;
            transform: scale(1.05);
        }
        .empty-orders {
            text-align: center;
            padding: 80px 20px;
        }
        .empty-orders i {
            font-size: 80px;
            color: #ddd;
            margin-bottom: 20px;
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
                        <h4>Mes Commandes</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Home</a>
                            <span>Mes Commandes</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Orders Section Begin -->
    <section class="shop spad">
        <div class="container">

            @if($orders->count() > 0)
                @foreach($orders as $order)
                    <div class="order-card">
                        <div class="row align-items-center">
                            <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                                <div>
                                    <small class="text-muted text-uppercase" style="font-size: 11px; letter-spacing: 1px;">Commande</small>
                                    <div class="order-number">{{ $order->order_number }}</div>
                                    <small class="text-muted">{{ $order->created_at->format('d/m/Y') }}</small>
                                </div>
                            </div>
                            
                            <div class="col-lg-2 col-md-6 mb-3 mb-lg-0">
                                <div>
                                    <small class="text-muted text-uppercase" style="font-size: 11px; letter-spacing: 1px;">Statut</small>
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
                                    <div>
                                        <span class="order-status {{ $statusClasses[$order->status] ?? '' }}">
                                            {{ $statusLabels[$order->status] ?? $order->status }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-2 col-md-6 mb-3 mb-lg-0">
                                <div>
                                    <small class="text-muted text-uppercase" style="font-size: 11px; letter-spacing: 1px;">Total</small>
                                    <div class="order-total">{{ number_format($order->total, 2) }} DH</div>
                                </div>
                            </div>
                            
                            <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                                <div>
                                    <small class="text-muted text-uppercase" style="font-size: 11px; letter-spacing: 1px;">Articles ({{ $order->items->count() }})</small>
                                    <div class="d-flex gap-2 mt-2">
                                        @foreach($order->items->take(3) as $item)
                                            @if($item->product && $item->product->images->first())
                                                <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                                                     alt="{{ $item->product_name }}" 
                                                     class="product-mini-img"
                                                     title="{{ $item->product_name }}">
                                            @endif
                                        @endforeach
                                        @if($order->items->count() > 3)
                                            <div class="product-mini-img d-flex align-items-center justify-content-center" style="background: #f0f0f0; font-weight: bold; font-size: 12px;">
                                                +{{ $order->items->count() - 3 }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-2 col-md-12 text-lg-end">
                                <a href="{{ route('orders.show', $order) }}" class="btn-details">
                                    <i class="fa fa-eye"></i> DÉTAILS
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-center">
                        {{ $orders->links() }}
                    </div>
                </div>
            @else
                <div class="empty-orders">
                    <i class="fa fa-shopping-bag"></i>
                    <h3 style="font-weight: bold; letter-spacing: 2px;">AUCUNE COMMANDE</h3>
                    <p class="text-muted" style="font-size: 16px; margin: 20px 0;">Vous n'avez pas encore passé de commande</p>
                    <a href="{{ route('products.index') }}" class="btn-details" style="display: inline-block; margin-top: 20px;">
                        <i class="fa fa-shopping-bag"></i> DÉCOUVRIR NOS PRODUITS
                    </a>
                </div>
            @endif
        </div>
    </section>
    <!-- Orders Section End -->
@endsection
