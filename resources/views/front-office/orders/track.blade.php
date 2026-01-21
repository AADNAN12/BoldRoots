@extends('front-office.layouts.app')

@section('title', 'Suivi Commande #' . $order->order_number)

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-secondary mb-2">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <h1 class="h3 mb-0">Suivi de Commande #{{ $order->order_number }}</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Statut de la Livraison</h5>
                </div>
                <div class="card-body">
                    <div class="tracking-timeline">
                        @foreach($timeline as $step)
                            <div class="tracking-step {{ $step['completed'] ? 'completed' : 'pending' }}">
                                <div class="tracking-icon">
                                    @if($step['completed'])
                                        <i class="fas fa-check-circle text-success"></i>
                                    @else
                                        <i class="far fa-circle text-muted"></i>
                                    @endif
                                </div>
                                <div class="tracking-content">
                                    <h6 class="{{ $step['completed'] ? 'text-success' : 'text-muted' }}">
                                        {{ $step['label'] }}
                                    </h6>
                                    @if($step['date'])
                                        <p class="mb-0 text-muted">
                                            <small>{{ $step['date']->format('d/m/Y H:i') }}</small>
                                        </p>
                                    @endif
                                    @if(isset($step['tracking']) && $step['tracking'])
                                        <p class="mb-0">
                                            <small><strong>N° de suivi:</strong> <code>{{ $step['tracking'] }}</code></small>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @if($order->deliveryNote)
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Informations de Livraison</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Transporteur</h6>
                                <p>{{ $order->deliveryNote->carrier_name ?? 'Non renseigné' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Numéro de Suivi</h6>
                                @if($order->deliveryNote->tracking_number)
                                    <p><code>{{ $order->deliveryNote->tracking_number }}</code></p>
                                @else
                                    <p class="text-muted">Non disponible</p>
                                @endif
                            </div>
                        </div>

                        @if($order->deliveryNote->status == 'delivered' && $order->deliveryNote->delivered_at)
                            <div class="alert alert-success mt-3">
                                <i class="fas fa-check-circle"></i>
                                <strong>Livraison confirmée</strong><br>
                                Votre colis a été livré le {{ $order->deliveryNote->delivered_at->format('d/m/Y à H:i') }}
                                @if($order->deliveryNote->recipient_name)
                                    <br>Reçu par: {{ $order->deliveryNote->recipient_name }}
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Adresse de Livraison</h5>
                </div>
                <div class="card-body">
                    @if($order->user_id && $order->user)
                        <address>
                            <strong>{{ $order->user->name }}</strong><br>
                            {{ $order->user->address_line1 }}<br>
                            @if($order->user->address_line2)
                                {{ $order->user->address_line2 }}<br>
                            @endif
                            @if($order->user->postal_code && $order->user->city)
                                {{ $order->user->postal_code }} {{ $order->user->city }}<br>
                            @endif
                            Tél: {{ $order->user->phone }}
                        </address>
                    @elseif($order->guest_name)
                        <address>
                            <strong>{{ $order->guest_name }}</strong><br>
                            {{ $order->guest_address_line1 }}<br>
                            @if($order->guest_address_line2)
                                {{ $order->guest_address_line2 }}<br>
                            @endif
                            @if($order->guest_postal_code && $order->guest_city)
                                {{ $order->guest_postal_code }} {{ $order->guest_city }}<br>
                            @endif
                            Tél: {{ $order->guest_phone }}
                        </address>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Résumé Commande</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-6">Articles:</dt>
                        <dd class="col-sm-6">{{ $order->items->count() }}</dd>

                        <dt class="col-sm-6">Total:</dt>
                        <dd class="col-sm-6"><strong>{{ number_format($order->total, 2) }} DH</strong></dd>

                        <dt class="col-sm-6">Paiement:</dt>
                        <dd class="col-sm-6">
                            @php
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
                            <span class="badge bg-{{ $paymentColors[$order->payment_status] ?? 'secondary' }}">
                                {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                            </span>
                        </dd>
                    </dl>

                    <hr>

                    <a href="{{ route('orders.show', $order) }}" class="btn btn-primary w-100">
                        <i class="fas fa-eye"></i> Voir Détails
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.tracking-timeline {
    position: relative;
    padding-left: 40px;
}

.tracking-step {
    position: relative;
    padding-bottom: 30px;
}

.tracking-step:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -29px;
    top: 30px;
    width: 2px;
    height: calc(100% - 10px);
    background: #dee2e6;
}

.tracking-step.completed:not(:last-child)::before {
    background: #28a745;
}

.tracking-icon {
    position: absolute;
    left: -40px;
    top: 0;
    font-size: 24px;
}

.tracking-content {
    min-height: 50px;
}
</style>
@endpush
@endsection
