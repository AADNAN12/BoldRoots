@extends('admin.layouts.master')

@section('title', 'Détails de la promotion')

@section('head')
    <style>
        .info-card {
            border-left: 3px solid #727cf5;
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .info-label {
            font-weight: 600;
            color: #6c757d;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            color: #313a46;
        }

        .product-item {
            padding: 10px;
            border: 1px solid #e3e6f0;
            border-radius: 4px;
            margin-bottom: 10px;
            background: white;
        }

        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }

        .stats-box {
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .stats-box h3 {
            font-size: 32px;
            margin: 0;
            font-weight: 700;
        }

        .stats-box p {
            margin: 5px 0 0 0;
            opacity: 0.9;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-light-lighten p-2">
                            <li class="breadcrumb-item"><a href=""><i class="uil-home-alt"></i> Accueil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.promotions.index') }}">Promotions</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Détails</li>
                        </ol>
                    </nav>
                </div>
                <h4 class="page-title">Détails de la promotion</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Informations générales -->
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">
                        <i class="mdi mdi-information-outline me-1"></i> Informations générales
                    </h4>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">Nom de la promotion</div>
                                <div class="info-value">{{ $promotion->name }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">Type</div>
                                <div class="info-value">
                                    @if ($promotion->type === 'flash_deal')
                                        <span class="badge bg-danger">
                                            <i class="mdi mdi-flash"></i> Flash Deal
                                        </span>
                                    @elseif($promotion->type === 'regular_sale')
                                        <span class="badge bg-warning">
                                            <i class="mdi mdi-sale"></i> Promotion Régulière
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="mdi mdi-gift"></i> Achetez X Obtenez Y
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="info-card">
                                <div class="info-label">Description</div>
                                <div class="info-value">{{ $promotion->description ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">Type de réduction</div>
                                <div class="info-value">
                                    @if ($promotion->discount_type === 'percentage')
                                        <strong class="text-primary">{{ $promotion->discount_value }}%</strong>
                                    @else
                                        <strong class="text-primary">{{ number_format($promotion->discount_value, 2) }} MAD</strong>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">Portée</div>
                                <div class="info-value">
                                    @if ($promotion->scope === 'product')
                                        <span class="badge bg-info">
                                            <i class="mdi mdi-tag"></i> Produits spécifiques
                                        </span>
                                    @elseif($promotion->scope === 'collection')
                                        <span class="badge bg-purple">
                                            <i class="mdi mdi-folder"></i> Catégories
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="mdi mdi-cart"></i> Tout le panier
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($promotion->type === 'buy_x_get_y')
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">Quantité à acheter</div>
                                <div class="info-value">{{ $promotion->buy_quantity }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">Quantité offerte</div>
                                <div class="info-value">{{ $promotion->get_quantity }}</div>
                            </div>
                        </div>
                        @endif

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">Date de début</div>
                                <div class="info-value">
                                    <i class="mdi mdi-calendar"></i> {{ $promotion->start_date ? $promotion->start_date->format('d/m/Y H:i') : '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">Date de fin</div>
                                <div class="info-value">
                                    <i class="mdi mdi-calendar"></i> {{ $promotion->end_date ? $promotion->end_date->format('d/m/Y H:i') : '-' }}
                                </div>
                            </div>
                        </div>

                        @if($promotion->min_cart_value)
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">Montant minimum du panier</div>
                                <div class="info-value">{{ number_format($promotion->min_cart_value, 2) }} MAD</div>
                            </div>
                        </div>
                        @endif

                        @if($promotion->max_uses)
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">Nombre maximum d'utilisations</div>
                                <div class="info-value">{{ $promotion->max_uses }}</div>
                            </div>
                        </div>
                        @endif

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">Statut</div>
                                <div class="info-value">
                                    @if ($promotion->is_active && now()->between($promotion->start_date, $promotion->end_date))
                                        <span class="badge bg-success">Actif</span>
                                    @elseif($promotion->is_active && now()->lt($promotion->start_date))
                                        <span class="badge bg-info">Programmé</span>
                                    @elseif(now()->gt($promotion->end_date))
                                        <span class="badge bg-secondary">Expiré</span>
                                    @else
                                        <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produits/Catégories concernés -->
            @if($promotion->scope === 'product' && $promotion->products->count() > 0)
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">
                        <i class="mdi mdi-tag-multiple me-1"></i> Produits concernés ({{ $promotion->products->count() }})
                    </h4>

                    <div class="row">
                        @foreach($promotion->products as $product)
                        <div class="col-md-6">
                            <div class="product-item d-flex align-items-center">
                                @php
                                    $primaryImage = $product->images->where('is_primary', true)->first();
                                @endphp
                                @if($primaryImage)
                                    <img src="{{ asset('storage/' . $primaryImage->image_path) }}" 
                                         alt="{{ $product->name }}" 
                                         class="product-image me-3">
                                @else
                                    <div class="product-image bg-light d-flex align-items-center justify-content-center me-3">
                                        <i class="mdi mdi-image-off text-muted"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-bold">{{ $product->name }}</div>
                                    <small class="text-muted">SKU: {{ $product->sku }}</small><br>
                                    <small class="text-primary">{{ number_format($product->price, 2) }} MAD</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if($promotion->scope === 'collection' && $promotion->categories->count() > 0)
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">
                        <i class="mdi mdi-folder-multiple me-1"></i> Catégories concernées ({{ $promotion->categories->count() }})
                    </h4>

                    <div class="row">
                        @foreach($promotion->categories as $category)
                        <div class="col-md-4">
                            <div class="info-card">
                                <i class="mdi mdi-folder"></i> {{ $category->name }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Statistiques -->
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">
                        <i class="mdi mdi-chart-line me-1"></i> Statistiques
                    </h4>

                    <div class="stats-box">
                        <h3>{{ $promotion->usage_count ?? 0 }}</h3>
                        <p>Utilisations totales</p>
                    </div>

                    @if($promotion->max_uses)
                    <div class="info-card">
                        <div class="info-label">Limite d'utilisations</div>
                        <div class="info-value">{{ $promotion->max_uses }}</div>
                    </div>

                    <div class="progress mb-3" style="height: 25px;">
                        @php
                            $percentage = $promotion->max_uses > 0 ? ($promotion->usage_count / $promotion->max_uses) * 100 : 0;
                        @endphp
                        <div class="progress-bar" role="progressbar" 
                             style="width: {{ $percentage }}%;" 
                             aria-valuenow="{{ $percentage }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            {{ number_format($percentage, 1) }}%
                        </div>
                    </div>
                    @endif

                    <div class="info-card">
                        <div class="info-label">Créée le</div>
                        <div class="info-value">{{ $promotion->created_at->format('d/m/Y H:i') }}</div>
                    </div>

                    <div class="info-card">
                        <div class="info-label">Dernière modification</div>
                        <div class="info-value">{{ $promotion->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left me-1"></i> Retour à la liste
                        </a>
                        <a href="{{ route('admin.promotions.edit', $promotion->id) }}" class="btn btn-primary">
                            <i class="mdi mdi-pencil me-1"></i> Modifier
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
