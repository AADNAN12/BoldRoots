@extends('admin.layouts.master')

@section('title', 'Détails Client')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Détails du Client</h2>
                <div>
                    <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-warning">
                        <i class="mdi mdi-pencil me-1"></i> Modifier
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
                        <i class="mdi mdi-arrow-left me-1"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle" style="width: 80px; height: 80px; font-size: 2rem; line-height: 80px;">
                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                        </div>
                    </div>
                    <h4 class="mb-1">{{ $customer->name }}</h4>
                    <p class="text-muted mb-3">{{ $customer->email }}</p>
                    @if($customer->is_active)
                        <span class="badge bg-success">Actif</span>
                    @else
                        <span class="badge bg-danger">Inactif</span>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Informations</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="mdi mdi-phone text-muted me-2"></i>
                            <strong>Téléphone:</strong> {{ $customer->phone ?? 'Non renseigné' }}
                        </li>
                        <li class="mb-2">
                            <i class="mdi mdi-calendar text-muted me-2"></i>
                            <strong>Inscrit le:</strong> {{ $customer->created_at->format('d/m/Y') }}
                        </li>
                        <li class="mb-2">
                            <i class="mdi mdi-clock text-muted me-2"></i>
                            <strong>Dernière connexion:</strong> {{ $customer->last_login ? $customer->last_login->format('d/m/Y H:i') : 'Jamais' }}
                        </li>
                        <li class="mb-2">
                            <i class="mdi mdi-email-check text-muted me-2"></i>
                            <strong>Email vérifié:</strong> 
                            @if($customer->email_verified_at)
                                <span class="badge bg-success">Oui</span>
                            @else
                                <span class="badge bg-warning">Non</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Commandes</h5>
                    @if($customer->orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>N° Commande</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer->orders->take(10) as $order)
                                        <tr>
                                            <td><strong>{{ $order->order_number }}</strong></td>
                                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                            <td>{{ number_format($order->total, 2) }} DH</td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'warning',
                                                        'processing' => 'info',
                                                        'shipped' => 'primary',
                                                        'delivered' => 'success',
                                                        'cancelled' => 'danger',
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">Aucune commande</p>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Adresses</h5>
                    @if($customer->addresses->count() > 0)
                        <div class="row">
                            @foreach($customer->addresses as $address)
                                <div class="col-md-6 mb-3">
                                    <div class="border rounded p-3">
                                        <h6 class="mb-2">
                                            {{ $address->first_name }} {{ $address->last_name }}
                                            @if($address->is_default)
                                                <span class="badge bg-primary">Par défaut</span>
                                            @endif
                                        </h6>
                                        <p class="mb-1 small">{{ $address->address_line1 }}</p>
                                        @if($address->address_line2)
                                            <p class="mb-1 small">{{ $address->address_line2 }}</p>
                                        @endif
                                        <p class="mb-1 small">{{ $address->postal_code }} {{ $address->city }}</p>
                                        <p class="mb-0 small"><i class="mdi mdi-phone"></i> {{ $address->phone }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-4">Aucune adresse enregistrée</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
