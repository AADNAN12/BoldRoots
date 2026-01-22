@extends('admin.layouts.master')

@section('title', 'Gestion des Commandes')

@section('head')
    <link href="{{ asset('assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/vendor/responsive.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="container-fluid">
        

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="page-title align-items-center">Gestion des Commandes</h4>
                    <div class="page-title-right">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb p-2">
                                <li class="breadcrumb-item"><a href="#"><i class="uil-home-alt"></i> Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Commandes</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Commandes</h5>
                                <h2>{{ $stats['total_orders'] }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">Revenu Total</h5>
                                <h2>{{ number_format($stats['total_revenue'], 2) }} DH</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5 class="card-title">Panier Moyen</h5>
                                <h2>{{ number_format($stats['average_order_value'], 2) }} DH</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h5 class="card-title">En Attente</h5>
                                <h2>{{ $stats['pending_orders'] }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="row mb-3">
                    <div class="col-md-6">
                        <h4 class="mb-0"><i class="mdi mdi-cart me-2"></i>Liste des Commandes</h4>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('admin.orders.stats') }}" class="btn btn-info btn-sm">
                            <i class="mdi mdi-chart-line"></i> Statistiques
                        </a>
                        <a href="{{ route('admin.orders.export') }}" class="btn btn-success btn-sm">
                            <i class="mdi mdi-file-export"></i> Exporter
                        </a>
                    </div>
                </div> --}}

                <ul class="nav nav-tabs mb-4 justify-content-center">
                    <li class="nav-item">
                        <a href="#tabAll" data-bs-toggle="tab" class="nav-link active">
                            <i class="mdi mdi-clipboard-list-outline d-md-none"></i>
                            <span class="d-none d-md-inline">Toutes</span>
                            <span class="badge bg-secondary ms-1">{{ $orders->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tabPending" data-bs-toggle="tab" class="nav-link">
                            <i class="mdi mdi-progress-clock d-md-none text-warning"></i>
                            <span class="d-none d-md-inline text-warning">En Attente</span>
                            <span class="badge bg-warning ms-1">{{ $ordersPending->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tabProcessing" data-bs-toggle="tab" class="nav-link">
                            <i class="mdi mdi-cog d-md-none text-info"></i>
                            <span class="d-none d-md-inline text-info">En Traitement</span>
                            <span class="badge bg-info ms-1">{{ $ordersProcessing->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tabShipped" data-bs-toggle="tab" class="nav-link">
                            <i class="mdi mdi-truck-fast-outline d-md-none text-primary"></i>
                            <span class="d-none d-md-inline text-primary">Expédiées</span>
                            <span class="badge bg-primary ms-1">{{ $ordersShipped->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tabDelivered" data-bs-toggle="tab" class="nav-link">
                            <i class="mdi mdi-package-variant-closed d-md-none text-success"></i>
                            <span class="d-none d-md-inline text-success">Livrées</span>
                            <span class="badge bg-success ms-1">{{ $ordersDelivered->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tabCancelled" data-bs-toggle="tab" class="nav-link">
                            <i class="mdi mdi-cancel d-md-none text-danger"></i>
                            <span class="d-none d-md-inline text-danger">Annulées</span>
                            <span class="badge bg-danger ms-1">{{ $ordersCancelled->count() }}</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tabAll">
                        <div class="table-responsive">
                            <table class="table table-hover w-100" id="table_commande_tout">
                                <thead>
                                    <tr>
                                        <th>N° Commande / Statut</th>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Paiement</th>
                                        <th class="text-center" style="width: 200px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $order)
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'processing' => 'info',
                                                'shipped' => 'primary',
                                                'delivered' => 'success',
                                                'cancelled' => 'danger',
                                            ];
                                            $statusLabels = [
                                                'pending' => 'En attente',
                                                'processing' => 'En traitement',
                                                'shipped' => 'Expédiée',
                                                'delivered' => 'Livrée',
                                                'cancelled' => 'Annulée',
                                            ];
                                            $paymentColors = [
                                                'pending' => 'warning',
                                                'paid' => 'success',
                                                'failed' => 'danger',
                                            ];
                                            $paymentLabels = [
                                                'pending' => 'En attente',
                                                'paid' => 'Payé',
                                                'failed' => 'Échoué',
                                            ];
                                        @endphp
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order) }}"
                                                    class="text-decoration-none">
                                                    <strong>{{ $order->order_number }}</strong>
                                                </a>
                                                <br>
                                                <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                                    {{ $statusLabels[$order->status] ?? $order->status }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $order->user ? $order->user->name : 'Invité' }}<br>
                                                <small
                                                    class="text-muted">{{ $order->user ? $order->user->email : '' }}</small>
                                            </td>
                                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                            <td><strong>{{ number_format($order->total, 2) }} DH</strong></td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $paymentColors[$order->payment_status] ?? 'secondary' }}">
                                                    {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if(Auth::guard('admin')->user()->can('show_orders'))
                                                <a href="{{ route('admin.orders.show', $order) }}" class="action-icon"
                                                    title="Voir">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                                @endif
                                                @if ($order->status !== 'cancelled')
                                                    @if(Auth::guard('admin')->user()->can('update_payment_status'))
                                                    @if ($order->payment_status !== 'paid')
                                                        <a href="#" class="action-icon text-success mark-paid-btn"
                                                            data-bs-toggle="modal" data-bs-target="#markPaidModal"
                                                            data-order-id="{{ $order->id }}"
                                                            title="Marquer comme payé">
                                                            <i class="mdi mdi-cash-check"></i>
                                                        </a>
                                                    @endif
                                                    @endif
                                                    @if(Auth::guard('admin')->user()->can('update_order_status'))
                                                    <a href="#" class="action-icon change-status-btn"
                                                        data-bs-toggle="modal" data-bs-target="#changeStatusModal"
                                                        data-order-id="{{ $order->id }}"
                                                        data-current-status="{{ $order->status }}"
                                                        title="Changer statut">
                                                        <i class="mdi mdi-swap-horizontal"></i>
                                                    </a>
                                                    @endif
                                                    @if(Auth::guard('admin')->user()->can('generate_invoices'))
                                                    @if (!$order->invoice_generated)
                                                        <a href="{{ route('admin.orders.generate-invoice', $order) }}"
                                                            class="action-icon" title="Générer facture">
                                                            <i class="mdi mdi-file-document"></i>
                                                        </a>
                                                    @endif
                                                    @endif
                                                    @if(Auth::guard('admin')->user()->can('generate_delivery_notes'))
                                                    @if (!$order->delivery_note_generated)
                                                        <a href="{{ route('admin.orders.generate-delivery-note', $order) }}"
                                                            class="action-icon" title="Générer BL">
                                                            <i class="mdi mdi-truck-delivery"></i>
                                                        </a>
                                                    @endif
                                                    @endif
                                                    @if(Auth::guard('admin')->user()->can('cancel_orders'))
                                                    <a href="#" class="action-icon cancel-order-btn"
                                                        data-bs-toggle="modal" data-bs-target="#cancelOrderModal"
                                                        data-order-id="{{ $order->id }}" title="Annuler">
                                                        <i class="mdi mdi-cancel"></i>
                                                    </a>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                       
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tabPending">
                        <div class="table-responsive">
                            <table class="table table-hover w-100" id="table_commande_attente">
                                <thead>
                                    <tr>
                                        <th>N° Commande</th>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Paiement</th>
                                        <th class="text-center" style="width: 200px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ordersPending as $order)
                                        @php
                                            $paymentColors = [
                                                'pending' => 'warning',
                                                'paid' => 'success',
                                                'failed' => 'danger',
                                            ];
                                            $paymentLabels = [
                                                'pending' => 'En attente',
                                                'paid' => 'Payé',
                                                'failed' => 'Échoué',
                                            ];
                                        @endphp
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order) }}"
                                                    class="text-decoration-none">
                                                    <strong>{{ $order->order_number }}</strong>
                                                </a>
                                            </td>
                                            <td>
                                                {{ $order->user ? $order->user->name : 'Invité' }}<br>
                                                <small
                                                    class="text-muted">{{ $order->user ? $order->user->email : '' }}</small>
                                            </td>
                                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                            <td><strong>{{ number_format($order->total, 2) }} DH</strong></td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $paymentColors[$order->payment_status] ?? 'secondary' }}">
                                                    {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.orders.show', $order) }}" class="action-icon"
                                                    title="Voir">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                                <a href="#" class="action-icon change-status-btn"
                                                    data-bs-toggle="modal" data-bs-target="#changeStatusModal"
                                                    data-order-id="{{ $order->id }}"
                                                    data-current-status="{{ $order->status }}" title="Changer statut">
                                                    <i class="mdi mdi-swap-horizontal"></i>
                                                </a>
                                                @if (!$order->invoice_generated)
                                                    <a href="{{ route('admin.orders.generate-invoice', $order) }}"
                                                        class="action-icon" title="Générer facture">
                                                        <i class="mdi mdi-file-document"></i>
                                                    </a>
                                                @endif
                                                @if (!$order->delivery_note_generated)
                                                    <a href="{{ route('admin.orders.generate-delivery-note', $order) }}"
                                                        class="action-icon" title="Générer BL">
                                                        <i class="mdi mdi-truck-delivery"></i>
                                                    </a>
                                                @endif
                                                <a href="#" class="action-icon cancel-order-btn"
                                                    data-bs-toggle="modal" data-bs-target="#cancelOrderModal"
                                                    data-order-id="{{ $order->id }}" title="Annuler">
                                                    <i class="mdi mdi-cancel"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tabProcessing">
                        <div class="table-responsive">
                            <table class="table table-hover w-100" id="table_commande_traitement">
                                <thead>
                                    <tr>
                                        <th>N° Commande</th>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Paiement</th>
                                        <th class="text-center" style="width: 200px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ordersProcessing as $order)
                                        @php
                                            $paymentColors = [
                                                'pending' => 'warning',
                                                'paid' => 'success',
                                                'failed' => 'danger',
                                            ];
                                            $paymentLabels = [
                                                'pending' => 'En attente',
                                                'paid' => 'Payé',
                                                'failed' => 'Échoué',
                                            ];
                                        @endphp
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order) }}"
                                                    class="text-decoration-none">
                                                    <strong>{{ $order->order_number }}</strong>
                                                </a>
                                            </td>
                                            <td>
                                                {{ $order->user ? $order->user->name : 'Invité' }}<br>
                                                <small
                                                    class="text-muted">{{ $order->user ? $order->user->email : '' }}</small>
                                            </td>
                                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                            <td><strong>{{ number_format($order->total, 2) }} DH</strong></td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $paymentColors[$order->payment_status] ?? 'secondary' }}">
                                                    {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.orders.show', $order) }}" class="action-icon"
                                                    title="Voir">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                                <a href="#" class="action-icon change-status-btn"
                                                    data-bs-toggle="modal" data-bs-target="#changeStatusModal"
                                                    data-order-id="{{ $order->id }}"
                                                    data-current-status="{{ $order->status }}" title="Changer statut">
                                                    <i class="mdi mdi-swap-horizontal"></i>
                                                </a>
                                                @if (!$order->invoice_generated)
                                                    <a href="{{ route('admin.orders.generate-invoice', $order) }}"
                                                        class="action-icon" title="Générer facture">
                                                        <i class="mdi mdi-file-document"></i>
                                                    </a>
                                                @endif
                                                @if (!$order->delivery_note_generated)
                                                    <a href="{{ route('admin.orders.generate-delivery-note', $order) }}"
                                                        class="action-icon" title="Générer BL">
                                                        <i class="mdi mdi-truck-delivery"></i>
                                                    </a>
                                                @endif
                                                <a href="#" class="action-icon cancel-order-btn"
                                                    data-bs-toggle="modal" data-bs-target="#cancelOrderModal"
                                                    data-order-id="{{ $order->id }}" title="Annuler">
                                                    <i class="mdi mdi-cancel"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tabShipped">
                        <div class="table-responsive">
                            <table class="table table-hover w-100" id="table_commande_expedie">
                                <thead>
                                    <tr>
                                        <th>N° Commande</th>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Paiement</th>
                                        <th class="text-center" style="width: 200px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ordersShipped as $order)
                                        @php
                                            $paymentColors = [
                                                'pending' => 'warning',
                                                'paid' => 'success',
                                                'failed' => 'danger',
                                            ];
                                            $paymentLabels = [
                                                'pending' => 'En attente',
                                                'paid' => 'Payé',
                                                'failed' => 'Échoué',
                                            ];
                                        @endphp
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order) }}"
                                                    class="text-decoration-none">
                                                    <strong>{{ $order->order_number }}</strong>
                                                </a>
                                            </td>
                                            <td>
                                                {{ $order->user ? $order->user->name : 'Invité' }}<br>
                                                <small
                                                    class="text-muted">{{ $order->user ? $order->user->email : '' }}</small>
                                            </td>
                                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                            <td><strong>{{ number_format($order->total, 2) }} DH</strong></td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $paymentColors[$order->payment_status] ?? 'secondary' }}">
                                                    {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.orders.show', $order) }}" class="action-icon"
                                                    title="Voir">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                                <a href="#" class="action-icon change-status-btn"
                                                    data-bs-toggle="modal" data-bs-target="#changeStatusModal"
                                                    data-order-id="{{ $order->id }}"
                                                    data-current-status="{{ $order->status }}" title="Changer statut">
                                                    <i class="mdi mdi-swap-horizontal"></i>
                                                </a>
                                                <a href="#" class="action-icon cancel-order-btn"
                                                    data-bs-toggle="modal" data-bs-target="#cancelOrderModal"
                                                    data-order-id="{{ $order->id }}" title="Annuler">
                                                    <i class="mdi mdi-cancel"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tabDelivered">
                        <div class="table-responsive">
                            <table class="table table-hover w-100" id="table_commande_livre">
                                <thead>
                                    <tr>
                                        <th>N° Commande</th>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Paiement</th>
                                        <th class="text-center" style="width: 150px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ordersDelivered as $order)
                                        @php
                                            $paymentColors = [
                                                'pending' => 'warning',
                                                'paid' => 'success',
                                                'failed' => 'danger',
                                            ];
                                            $paymentLabels = [
                                                'pending' => 'En attente',
                                                'paid' => 'Payé',
                                                'failed' => 'Échoué',
                                            ];
                                        @endphp
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order) }}"
                                                    class="text-decoration-none">
                                                    <strong>{{ $order->order_number }}</strong>
                                                </a>
                                            </td>
                                            <td>
                                                {{ $order->user ? $order->user->name : 'Invité' }}<br>
                                                <small
                                                    class="text-muted">{{ $order->user ? $order->user->email : '' }}</small>
                                            </td>
                                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                            <td><strong>{{ number_format($order->total, 2) }} DH</strong></td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $paymentColors[$order->payment_status] ?? 'secondary' }}">
                                                    {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.orders.show', $order) }}" class="action-icon"
                                                    title="Voir">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tabCancelled">
                        <div class="table-responsive">
                            <table class="table table-hover w-100" id="table_commande_annule">
                                <thead>
                                    <tr>
                                        <th>N° Commande</th>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Paiement</th>
                                        <th class="text-center" style="width: 150px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ordersCancelled as $order)
                                        @php
                                            $paymentColors = [
                                                'pending' => 'warning',
                                                'paid' => 'success',
                                                'failed' => 'danger',
                                            ];
                                            $paymentLabels = [
                                                'pending' => 'En attente',
                                                'paid' => 'Payé',
                                                'failed' => 'Échoué',
                                            ];
                                        @endphp
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order) }}"
                                                    class="text-decoration-none">
                                                    <strong>{{ $order->order_number }}</strong>
                                                </a>
                                            </td>
                                            <td>
                                                {{ $order->user ? $order->user->name : 'Invité' }}<br>
                                                <small
                                                    class="text-muted">{{ $order->user ? $order->user->email : '' }}</small>
                                            </td>
                                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                            <td><strong>{{ number_format($order->total, 2) }} DH</strong></td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $paymentColors[$order->payment_status] ?? 'secondary' }}">
                                                    {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.orders.show', $order) }}" class="action-icon"
                                                    title="Voir">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="changeStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Changer le statut</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="changeStatusForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nouveau statut</label>
                            <select name="status" class="form-select" required>
                                <option value="pending">En attente</option>
                                <option value="processing">En traitement</option>
                                <option value="shipped">Expédiée</option>
                                <option value="delivered">Livrée</option>
                                <option value="cancelled">Annulée</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Confirmer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cancelOrderModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Annuler la commande</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="cancelOrderForm" method="POST">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="status" value="cancelled">
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir annuler cette commande ?</p>
                        <p class="text-danger"><strong>Cette action est irréversible.</strong></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                        <button type="submit" class="btn btn-danger">Oui, annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="markPaidModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Marquer comme payé</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="markPaidForm" method="POST">
                    @csrf
                    <input type="hidden" name="payment_status" value="paid">
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir marquer cette commande comme payée ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">Oui, marquer comme payé</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/responsive.bootstrap5.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const routeTemplate = "{{ route('admin.orders.status', ['order' => ':orderId']) }}";
            const paymentRouteTemplate =
                "{{ route('admin.orders.update-payment-status', ['order' => ':orderId']) }}";

            // Initialize DataTables
            const dataTableConfig = {
                order: [],
                scrollX: true,
                pageLength: 10,
                lengthMenu: [
                    [5, 10, 20, -1],
                    [5, 10, 25, "Tous"],
                ],
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json",
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                        next: "<i class='mdi mdi-chevron-right'>",
                    },
                },
                drawCallback: function() {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                },
            };

            $("#table_commande_tout").DataTable(dataTableConfig);
            $("#table_commande_attente").DataTable(dataTableConfig);
            $("#table_commande_traitement").DataTable(dataTableConfig);
            $("#table_commande_expedie").DataTable(dataTableConfig);
            $("#table_commande_livre").DataTable(dataTableConfig);
            $("#table_commande_annule").DataTable(dataTableConfig);

            // Adjust DataTables on tab change
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                $.fn.dataTable.tables({
                    visible: true,
                    api: true
                }).columns.adjust();
            });

            document.querySelectorAll('.change-status-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var orderId = this.dataset.orderId;
                    var currentStatus = this.dataset.currentStatus;
                    var form = document.getElementById('changeStatusForm');
                    form.action = routeTemplate.replace(':orderId', orderId);
                    form.querySelector('select[name="status"]').value = currentStatus;
                });
            });

            document.querySelectorAll('.cancel-order-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var orderId = this.dataset.orderId;
                    var form = document.getElementById('cancelOrderForm');
                    form.action = routeTemplate.replace(':orderId', orderId);
                });
            });

            document.querySelectorAll('.mark-paid-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    alert('test');
                    var orderId = this.dataset.orderId;
                    var form = document.getElementById('markPaidForm');
                    form.action = paymentRouteTemplate.replace(':orderId', orderId);
                });
            });
        });
    </script>
@endsection
