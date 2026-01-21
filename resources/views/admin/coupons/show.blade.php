@extends('admin.layouts.master')

@section('title', 'Détails du coupon')

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

        .coupon-code-display {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            font-size: 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
            letter-spacing: 3px;
        }

        .stats-box {
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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

        .user-item {
            padding: 10px;
            border: 1px solid #e3e6f0;
            border-radius: 4px;
            margin-bottom: 10px;
            background: white;
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
                            <li class="breadcrumb-item"><a href="{{ route('admin.coupons.index') }}">Coupons</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Détails</li>
                        </ol>
                    </nav>
                </div>
                <h4 class="page-title">Détails du coupon</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Code du coupon -->
            <div class="card">
                <div class="card-body">
                    <div class="coupon-code-display">
                        {{ $coupon->code }}
                    </div>
                    @if($coupon->description)
                    <div class="alert alert-info">
                        <i class="mdi mdi-information-outline me-1"></i>
                        {{ $coupon->description }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Informations générales -->
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">
                        <i class="mdi mdi-information-outline me-1"></i> Informations générales
                    </h4>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">Type de réduction</div>
                                <div class="info-value">
                                    @if ($coupon->type === 'percentage')
                                        <span class="badge bg-primary" style="font-size: 16px;">
                                            <i class="mdi mdi-percent"></i> {{ $coupon->discount_value }}%
                                        </span>
                                    @elseif($coupon->type === 'fixed_amount')
                                        <span class="badge bg-success" style="font-size: 16px;">
                                            <i class="mdi mdi-cash"></i> {{ number_format($coupon->discount_value, 2) }} MAD
                                        </span>
                                    @else
                                        <span class="badge bg-info" style="font-size: 16px;">
                                            <i class="mdi mdi-truck"></i> Livraison gratuite
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">Statut</div>
                                <div class="info-value">
                                    @if ($coupon->is_active && (!$coupon->valid_until || now()->lte($coupon->valid_until)))
                                        <span class="badge bg-success" style="font-size: 16px;">Actif</span>
                                    @elseif($coupon->valid_until && now()->gt($coupon->valid_until))
                                        <span class="badge bg-secondary" style="font-size: 16px;">Expiré</span>
                                    @else
                                        <span class="badge bg-secondary" style="font-size: 16px;">Inactif</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">Date de début</div>
                                <div class="info-value">
                                    <i class="mdi mdi-calendar-start"></i> 
                                    {{ $coupon->valid_from ? $coupon->valid_from->format('d/m/Y H:i') : '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">Date de fin</div>
                                <div class="info-value">
                                    <i class="mdi mdi-calendar-end"></i> 
                                    @if($coupon->valid_until)
                                        {{ $coupon->valid_until->format('d/m/Y H:i') }}
                                    @else
                                        <span class="text-muted">Illimité</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($coupon->min_cart_value)
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">Montant minimum du panier</div>
                                <div class="info-value">
                                    <i class="mdi mdi-cart"></i> {{ number_format($coupon->min_cart_value, 2) }} MAD
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($coupon->usage_limit)
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">Limite d'utilisations totale</div>
                                <div class="info-value">
                                    <i class="mdi mdi-counter"></i> {{ $coupon->usage_limit }}
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($coupon->usage_per_customer)
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">Utilisations par client</div>
                                <div class="info-value">
                                    <i class="mdi mdi-account-multiple"></i> {{ $coupon->usage_per_customer }}
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">Exclure les nouveaux produits</div>
                                <div class="info-value">
                                    @if($coupon->exclude_new_products)
                                        <span class="badge bg-warning">Oui</span>
                                    @else
                                        <span class="badge bg-secondary">Non</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historique d'utilisation -->
            @if($coupon->usages && $coupon->usages->count() > 0)
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">
                        <i class="mdi mdi-history me-1"></i> Historique d'utilisation ({{ $coupon->usages->count() }})
                    </h4>

                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Utilisateur</th>
                                    <th>Commande</th>
                                    <th>Réduction</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($coupon->usages->take(10) as $usage)
                                <tr>
                                    <td>{{ $usage->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($usage->user)
                                            <i class="mdi mdi-account"></i> {{ $usage->user->name }}
                                        @else
                                            <span class="text-muted">Invité</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($usage->order)
                                            <a href="{{ route('admin.orders.show', $usage->order_id) }}">
                                                #{{ $usage->order->order_number }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-success">{{ number_format($usage->discount_amount, 2) }} MAD</strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($coupon->usages->count() > 10)
                    <div class="text-center mt-2">
                        <small class="text-muted">Affichage des 10 dernières utilisations sur {{ $coupon->usages->count() }} au total</small>
                    </div>
                    @endif
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
                        <h3>{{ $coupon->used_count ?? 0 }}</h3>
                        <p>Utilisations totales</p>
                    </div>

                    @if($coupon->usage_limit)
                    <div class="info-card">
                        <div class="info-label">Progression</div>
                        <div class="info-value">
                            {{ $coupon->used_count }} / {{ $coupon->usage_limit }}
                        </div>
                    </div>

                    <div class="progress mb-3" style="height: 25px;">
                        @php
                            $percentage = $coupon->usage_limit > 0 ? ($coupon->used_count / $coupon->usage_limit) * 100 : 0;
                        @endphp
                        <div class="progress-bar {{ $percentage >= 100 ? 'bg-danger' : 'bg-success' }}" 
                             role="progressbar" 
                             style="width: {{ min($percentage, 100) }}%;" 
                             aria-valuenow="{{ $percentage }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            {{ number_format($percentage, 1) }}%
                        </div>
                    </div>

                    @if($percentage >= 100)
                    <div class="alert alert-danger">
                        <i class="mdi mdi-alert"></i> Limite d'utilisations atteinte
                    </div>
                    @endif
                    @endif

                    @if($coupon->usages && $coupon->usages->count() > 0)
                    <div class="info-card">
                        <div class="info-label">Réduction totale accordée</div>
                        <div class="info-value text-success">
                            {{ number_format($coupon->usages->sum('discount_amount'), 2) }} MAD
                        </div>
                    </div>
                    @endif

                    <div class="info-card">
                        <div class="info-label">Créé le</div>
                        <div class="info-value">{{ $coupon->created_at->format('d/m/Y H:i') }}</div>
                    </div>

                    <div class="info-card">
                        <div class="info-label">Dernière modification</div>
                        <div class="info-value">{{ $coupon->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left me-1"></i> Retour à la liste
                        </a>
                        <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-primary">
                            <i class="mdi mdi-pencil me-1"></i> Modifier
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
