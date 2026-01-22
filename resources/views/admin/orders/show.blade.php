@extends('admin.layouts.master')

@section('title', 'Commande #' . $order->order_number)

@section('head')
    <style>
        .status-badge-large {
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e3e6f0;
        }
        .timeline-item {
            position: relative;
            padding-bottom: 20px;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -26px;
            top: 5px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #fff;
            border: 3px solid #4e73df;
        }
        .timeline-item.completed::before {
            background: #1cc88a;
            border-color: #1cc88a;
        }
        .info-card {
            border-left: 4px solid #4e73df;
        }
        .product-img-preview {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    
                    <h4 class="page-title mb-0">Commande #{{ $order->order_number }}</h4>
                </div>
                <div>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary mb-2">
                        <i class="mdi mdi-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12   ">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0"><i class="mdi mdi-information-outline me-2"></i>Informations Générales</h5>
                        @php
                            $statusColors = [
                                'pending' => 'warning',
                                'processing' => 'info',
                                'shipped' => 'primary',
                                'delivered' => 'success',
                                'cancelled' => 'danger'
                            ];
                            $statusLabels = [
                                'pending' => 'En attente',
                                'processing' => 'En traitement',
                                'shipped' => 'Expédiée',
                                'delivered' => 'Livrée',
                                'cancelled' => 'Annulée'
                            ];
                            $paymentColors = [
                                'pending' => 'warning',
                                'paid' => 'success',
                                'failed' => 'danger'
                            ];
                            $paymentLabels = [
                                'pending' => 'En attente',
                                'paid' => 'Payé',
                                'failed' => 'Échoué'
                            ];
                        @endphp
                        <div>
                            <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} status-badge-large me-2">
                                {{ $statusLabels[$order->status] ?? $order->status }}
                            </span>
                            <span class="badge bg-{{ $paymentColors[$order->payment_status] ?? 'secondary' }} status-badge-large">
                                <i class="mdi mdi-cash"></i> {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted mb-1"><i class="mdi mdi-calendar"></i> Date de commande</label>
                                <p class="mb-0 fw-bold">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted mb-1"><i class="mdi mdi-credit-card"></i> Méthode de paiement</label>
                                @php
                                    $paymentMethods = [
                                        'cash_on_delivery' => 'Paiement à la livraison',
                                        'credit_card' => 'Carte bancaire',
                                        'bank_transfer' => 'Virement bancaire'
                                    ];
                                @endphp
                                <p class="mb-0 fw-bold">{{ $paymentMethods[$order->payment_method] ?? $order->payment_method }}</p>
                            </div>
                        </div>
                        @if($order->shipped_at)
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted mb-1"><i class="mdi mdi-truck"></i> Date d'expédition</label>
                                    <p class="mb-0 fw-bold">{{ $order->shipped_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endif
                        @if($order->delivered_at)
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted mb-1"><i class="mdi mdi-check-circle"></i> Date de livraison</label>
                                    <p class="mb-0 fw-bold">{{ $order->delivered_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="mdi mdi-cart me-2"></i>Articles Commandés</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table">
                                <tr>
                                    <th>Produit</th>
                                    <th>SKU</th>
                                    <th class="text-end">Prix Unit.</th>
                                    <th class="text-center">Qté</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product && $item->product->images->first())
                                                    <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                                                         alt="{{ $item->product_name }}" 
                                                         class="product-img-preview me-3">
                                                @else
                                                    <div class="product-img-preview bg-light d-flex align-items-center justify-content-center me-3">
                                                        <i class="mdi mdi-image-off text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $item->product_name }}</strong>
                                                    @if($item->variant_details)
                                                        <br>
                                                        <small class="text-muted">
                                                            @foreach($item->variant_details['attributes'] ?? [] as $key => $value)
                                                                <span class="badge bg-light text-dark">{{ $key }}: {{ $value }}</span>
                                                            @endforeach
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td><code>{{ $item->product_sku }}</code></td>
                                        <td class="text-end">{{ number_format($item->price, 2) }} DH</td>
                                        <td class="text-center"><span class="badge bg-primary">{{ $item->quantity }}</span></td>
                                        <td class="text-end"><strong>{{ number_format($item->total, 2) }} DH</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Sous-total:</strong></td>
                                    <td class="text-end"><strong>{{ number_format($order->subtotal, 2) }} DH</strong></td>
                                </tr>
                                @if($order->discount > 0)
                                    <tr>
                                        <td colspan="4" class="text-end">
                                            <strong>Réduction:</strong>
                                            @if($order->promotion)
                                                <br><small class="text-muted"><i class="mdi mdi-tag"></i> {{ $order->promotion->name }}</small>
                                            @endif
                                            @if($order->coupon)
                                                <br><small class="text-muted"><i class="mdi mdi-ticket-percent"></i> {{ $order->coupon->code }}</small>
                                            @endif
                                        </td>
                                        <td class="text-end"><strong class="text-danger">-{{ number_format($order->discount, 2) }} DH</strong></td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Frais de livraison:</strong></td>
                                    <td class="text-end"><strong>{{ number_format($order->shipping_cost, 2) }} DH</strong></td>
                                </tr>
                                <tr class="table-primary">
                                    <td colspan="4" class="text-end"><strong class="fs-5">TOTAL:</strong></td>
                                    <td class="text-end"><strong class="fs-5 text-primary">{{ number_format($order->total, 2) }} DH</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="mdi mdi-map-marker me-2"></i>Informations Client</h5>
                </div>
                <div class="card-body">
                    @if($order->user_id && $order->user)
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <div class="avatar-lg bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="mdi mdi-account mdi-24px text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-1">{{ $order->user->name }}</h5>
                                <p class="text-muted mb-2"><i class="mdi mdi-email"></i> {{ $order->user->email }}</p>
                                <p class="text-muted mb-2"><i class="mdi mdi-phone"></i> {{ $order->user->phone }}</p>
                                <div class="mt-3">
                                    <h6 class="mb-2">Adresse de livraison:</h6>
                                    <address class="mb-0">
                                        {{ $order->user->address_line1 }}<br>
                                        @if($order->user->address_line2)
                                            {{ $order->user->address_line2 }}<br>
                                        @endif
                                        @if($order->user->postal_code && $order->user->city)
                                            {{ $order->user->postal_code }} {{ $order->user->city }}
                                        @endif
                                    </address>
                                </div>
                            </div>
                        </div>
                    @elseif($order->guest_name)
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <div class="avatar-lg bg-info-subtle rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="mdi mdi-account-question mdi-24px text-info"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-1">{{ $order->guest_name }} <span class="badge bg-info">Invité</span></h5>
                                <p class="text-muted mb-2"><i class="mdi mdi-email"></i> {{ $order->guest_email }}</p>
                                <p class="text-muted mb-2"><i class="mdi mdi-phone"></i> {{ $order->guest_phone }}</p>
                                <div class="mt-3">
                                    <h6 class="mb-2">Adresse de livraison:</h6>
                                    <address class="mb-0">
                                        {{ $order->guest_address_line1 }}<br>
                                        @if($order->guest_address_line2)
                                            {{ $order->guest_address_line2 }}<br>
                                        @endif
                                        @if($order->guest_postal_code && $order->guest_city)
                                            {{ $order->guest_postal_code }} {{ $order->guest_city }}
                                        @endif
                                    </address>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-muted mb-0">Aucune information client disponible</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deliveryNoteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.orders.generate-delivery-note', $order) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Générer Bon de Livraison</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Transporteur</label>
                        <input type="text" name="carrier_name" class="form-control" placeholder="Ex: DHL, Aramex..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Numéro de suivi</label>
                        <input type="text" name="tracking_number" class="form-control" placeholder="Ex: 1234567890">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Générer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="cancelOrderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.orders.cancel', $order) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title text-white">Annuler la Commande</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir annuler cette commande ?</p>
                    <p class="text-muted mb-0"><small>Cette action est irréversible.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non, garder</button>
                    <button type="submit" class="btn btn-danger">Oui, annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function updateOrderStatus() {
    const status = document.getElementById('orderStatus').value;
    
    fetch('{{ route("admin.orders.status", $order) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => {
        alert('Erreur lors de la mise à jour');
    });
}

function updatePaymentStatus() {
    const paymentStatus = document.getElementById('paymentStatus').value;
    
    fetch('{{ route("admin.orders.update-payment-status", $order) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ payment_status: paymentStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => {
        alert('Erreur lors de la mise à jour');
    });
}
</script>
@endsection
