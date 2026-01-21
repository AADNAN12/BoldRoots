@extends('admin.layouts.master')

@section('title', 'Gestion des Factures')

@section('head')
    <link href="{{ asset('assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/vendor/responsive.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="container-fluid">
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

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="page-title align-items-center">Gestion des Factures</h4>
                    <div class="page-title-right">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb p-2">
                                <li class="breadcrumb-item"><a href="#"><i class="uil-home-alt"></i> Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Factures</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Factures</h5>
                                <h2>{{ $stats['total_invoices'] }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">Factures Payées</h5>
                                <h2>{{ $stats['paid_invoices'] }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h5 class="card-title">En Attente</h5>
                                <h2>{{ $stats['sent_invoices'] }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5 class="card-title">Montant Total</h5>
                                <h2>{{ number_format($stats['total_amount'], 2) }} DH</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <ul class="nav nav-tabs mb-4 justify-content-center">
                    <li class="nav-item">
                        <a href="#tabAll" data-bs-toggle="tab" class="nav-link active">
                            <i class="mdi mdi-clipboard-list-outline d-md-none"></i>
                            <span class="d-none d-md-inline">Toutes</span>
                            <span class="badge bg-secondary ms-1">{{ $invoices->total() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tabDraft" data-bs-toggle="tab" class="nav-link">
                            <i class="mdi mdi-file-edit-outline d-md-none text-secondary"></i>
                            <span class="d-none d-md-inline text-secondary">Brouillon</span>
                            <span class="badge bg-secondary ms-1">{{ $invoicesDraft->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tabSent" data-bs-toggle="tab" class="nav-link">
                            <i class="mdi mdi-send d-md-none text-warning"></i>
                            <span class="d-none d-md-inline text-warning">Envoyées</span>
                            <span class="badge bg-warning ms-1">{{ $invoicesSent->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tabPaid" data-bs-toggle="tab" class="nav-link">
                            <i class="mdi mdi-check-circle d-md-none text-success"></i>
                            <span class="d-none d-md-inline text-success">Payées</span>
                            <span class="badge bg-success ms-1">{{ $invoicesPaid->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tabCancelled" data-bs-toggle="tab" class="nav-link">
                            <i class="mdi mdi-cancel d-md-none text-danger"></i>
                            <span class="d-none d-md-inline text-danger">Annulées</span>
                            <span class="badge bg-danger ms-1">{{ $invoicesCancelled->count() }}</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    {{-- Tab Toutes --}}
                    <div class="tab-pane fade show active" id="tabAll">
                        <div class="table-responsive">
                            <table id="table_facture_tout" class="table table-hover w-100">
                                <thead>
                                    <tr>
                                        <th>N° Facture / Statut</th>
                                        <th>N° Commande</th>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th class="text-center" style="width: 200px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $invoice)
                                        @php
                                            $statusColors = [
                                                'draft' => 'secondary',
                                                'sent' => 'warning',
                                                'paid' => 'success',
                                                'cancelled' => 'danger',
                                            ];
                                            $statusLabels = [
                                                'draft' => 'Brouillon',
                                                'sent' => 'Envoyée',
                                                'paid' => 'Payée',
                                                'cancelled' => 'Annulée',
                                            ];
                                        @endphp
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.invoices.show', $invoice) }}"
                                                    class="text-decoration-none">
                                                    <strong>{{ $invoice->invoice_number }}</strong>
                                                </a>
                                                <br>
                                                <span
                                                    class="badge bg-{{ $statusColors[$invoice->status] ?? 'secondary' }}">
                                                    {{ $statusLabels[$invoice->status] ?? $invoice->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $invoice->order) }}"
                                                    class="text-decoration-none">
                                                    {{ $invoice->order->order_number }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ $invoice->order->user_id && $invoice->order->user ? $invoice->order->user->name : $invoice->order->guest_name ?? 'Invité' }}<br>
                                                <small
                                                    class="text-muted">{{ $invoice->order->user_id && $invoice->order->user ? $invoice->order->user->email : $invoice->order->guest_email ?? '' }}</small>
                                            </td>
                                            <td>{{ $invoice->invoice_date->format('d/m/Y') }}</td>
                                            <td><strong>{{ number_format($invoice->total, 2) }} DH</strong></td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.invoices.show', $invoice) }}" class="action-icon"
                                                    title="Voir">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                                @if ($invoice->status !== 'cancelled')
                                                    @if (!$invoice->pdf_path)
                                                        <a href="{{ route('admin.invoices.generate-pdf', $invoice) }}"
                                                            class="action-icon text-primary" title="Générer PDF">
                                                            <i class="mdi mdi-file-pdf-box"></i>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('admin.invoices.download-pdf', $invoice) }}"
                                                            class="action-icon text-success" title="Télécharger PDF">
                                                            <i class="mdi mdi-download"></i>
                                                        </a>
                                                    @endif
                                                    @if ($invoice->status === 'sent')
                                                        <a href="#"
                                                            class="action-icon text-success mark-invoice-paid-btn"
                                                            data-bs-toggle="modal" data-bs-target="#markInvoicePaidModal"
                                                            data-invoice-id="{{ $invoice->id }}"
                                                            title="Marquer comme payé">
                                                            <i class="mdi mdi-cash-check"></i>
                                                        </a>
                                                    @endif
                                                    <a href="#" class="action-icon change-invoice-status-btn"
                                                        data-bs-toggle="modal" data-bs-target="#changeInvoiceStatusModal"
                                                        data-invoice-id="{{ $invoice->id }}"
                                                        data-current-status="{{ $invoice->status }}"
                                                        title="Changer statut">
                                                        <i class="mdi mdi-swap-horizontal"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tab Brouillon --}}
                    <div class="tab-pane fade" id="tabDraft">
                        <div class="table-responsive">
                            <table id="table_facture_brouillon" class="table table-hover w-100">
                                <thead>
                                    <tr>
                                        <th>N° Facture</th>
                                        <th>N° Commande</th>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoicesDraft as $invoice)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.invoices.show', $invoice) }}"
                                                    class="text-decoration-none">
                                                    <strong>{{ $invoice->invoice_number }}</strong>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $invoice->order) }}"
                                                    class="text-decoration-none">
                                                    {{ $invoice->order->order_number }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ $invoice->order->user_id && $invoice->order->user ? $invoice->order->user->name : $invoice->order->guest_name ?? 'Invité' }}<br>
                                                <small
                                                    class="text-muted">{{ $invoice->order->user_id && $invoice->order->user ? $invoice->order->user->email : $invoice->order->guest_email ?? '' }}</small>
                                            </td>
                                            <td>{{ $invoice->invoice_date->format('d/m/Y') }}</td>
                                            <td><strong>{{ number_format($invoice->total, 2) }} DH</strong></td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.invoices.show', $invoice) }}"
                                                    class="action-icon" title="Voir">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                                @if (!$invoice->pdf_path)
                                                    <a href="{{ route('admin.invoices.generate-pdf', $invoice) }}"
                                                        class="action-icon text-primary" title="Générer PDF">
                                                        <i class="mdi mdi-file-pdf-box"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('admin.invoices.download-pdf', $invoice) }}"
                                                        class="action-icon text-success" title="Télécharger PDF">
                                                        <i class="mdi mdi-download"></i>
                                                    </a>
                                                @endif
                                                <a href="#" class="action-icon change-invoice-status-btn"
                                                    data-bs-toggle="modal" data-bs-target="#changeInvoiceStatusModal"
                                                    data-invoice-id="{{ $invoice->id }}"
                                                    data-current-status="{{ $invoice->status }}" title="Changer statut">
                                                    <i class="mdi mdi-swap-horizontal"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tab Envoyées --}}
                    <div class="tab-pane fade" id="tabSent">
                        <div class="table-responsive">
                            <table id="table_facture_envoyee" class="table table-hover w-100">
                                <thead>
                                    <tr>
                                        <th>N° Facture</th>
                                        <th>N° Commande</th>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoicesSent as $invoice)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.invoices.show', $invoice) }}"
                                                    class="text-decoration-none">
                                                    <strong>{{ $invoice->invoice_number }}</strong>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $invoice->order) }}"
                                                    class="text-decoration-none">
                                                    {{ $invoice->order->order_number }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ $invoice->order->user_id && $invoice->order->user ? $invoice->order->user->name : $invoice->order->guest_name ?? 'Invité' }}<br>
                                                <small
                                                    class="text-muted">{{ $invoice->order->user_id && $invoice->order->user ? $invoice->order->user->email : $invoice->order->guest_email ?? '' }}</small>
                                            </td>
                                            <td>{{ $invoice->invoice_date->format('d/m/Y') }}</td>
                                            <td><strong>{{ number_format($invoice->total, 2) }} DH</strong></td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.invoices.show', $invoice) }}"
                                                    class="action-icon" title="Voir">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                                @if ($invoice->pdf_path)
                                                    <a href="{{ route('admin.invoices.download-pdf', $invoice) }}"
                                                        class="action-icon text-success" title="Télécharger PDF">
                                                        <i class="mdi mdi-download"></i>
                                                    </a>
                                                @endif
                                                <a href="#" class="action-icon text-success mark-invoice-paid-btn"
                                                    data-bs-toggle="modal" data-bs-target="#markInvoicePaidModal"
                                                    data-invoice-id="{{ $invoice->id }}" title="Marquer comme payé">
                                                    <i class="mdi mdi-cash-check"></i>
                                                </a>
                                                <a href="#" class="action-icon change-invoice-status-btn"
                                                    data-bs-toggle="modal" data-bs-target="#changeInvoiceStatusModal"
                                                    data-invoice-id="{{ $invoice->id }}"
                                                    data-current-status="{{ $invoice->status }}" title="Changer statut">
                                                    <i class="mdi mdi-swap-horizontal"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tab Payées --}}
                    <div class="tab-pane fade" id="tabPaid">
                        <div class="table-responsive">
                            <table id="table_facture_payee" class="table table-hover w-100">
                                <thead>
                                    <tr>
                                        <th>N° Facture</th>
                                        <th>N° Commande</th>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoicesPaid as $invoice)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.invoices.show', $invoice) }}"
                                                    class="text-decoration-none">
                                                    <strong>{{ $invoice->invoice_number }}</strong>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $invoice->order) }}"
                                                    class="text-decoration-none">
                                                    {{ $invoice->order->order_number }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ $invoice->order->user_id && $invoice->order->user ? $invoice->order->user->name : $invoice->order->guest_name ?? 'Invité' }}<br>
                                                <small
                                                    class="text-muted">{{ $invoice->order->user_id && $invoice->order->user ? $invoice->order->user->email : $invoice->order->guest_email ?? '' }}</small>
                                            </td>
                                            <td>{{ $invoice->invoice_date->format('d/m/Y') }}</td>
                                            <td><strong>{{ number_format($invoice->total, 2) }} DH</strong></td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.invoices.show', $invoice) }}"
                                                    class="action-icon" title="Voir">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                                @if ($invoice->pdf_path)
                                                    <a href="{{ route('admin.invoices.download-pdf', $invoice) }}"
                                                        class="action-icon text-success" title="Télécharger PDF">
                                                        <i class="mdi mdi-download"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tab Annulées --}}
                    <div class="tab-pane fade" id="tabCancelled">
                        <div class="table-responsive">
                            <table id="table_facture_annulee" class="table table-hover w-100">
                                <thead>
                                    <tr>
                                        <th>N° Facture</th>
                                        <th>N° Commande</th>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoicesCancelled as $invoice)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.invoices.show', $invoice) }}"
                                                    class="text-decoration-none">
                                                    <strong>{{ $invoice->invoice_number }}</strong>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $invoice->order) }}"
                                                    class="text-decoration-none">
                                                    {{ $invoice->order->order_number }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ $invoice->order->user_id && $invoice->order->user ? $invoice->order->user->name : $invoice->order->guest_name ?? 'Invité' }}<br>
                                                <small
                                                    class="text-muted">{{ $invoice->order->user_id && $invoice->order->user ? $invoice->order->user->email : $invoice->order->guest_email ?? '' }}</small>
                                            </td>
                                            <td>{{ $invoice->invoice_date->format('d/m/Y') }}</td>
                                            <td><strong>{{ number_format($invoice->total, 2) }} DH</strong></td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.invoices.show', $invoice) }}"
                                                    class="action-icon" title="Voir">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                                @if ($invoice->pdf_path)
                                                    <a href="{{ route('admin.invoices.download-pdf', $invoice) }}"
                                                        class="action-icon text-success" title="Télécharger PDF">
                                                        <i class="mdi mdi-download"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Changer Statut Facture --}}
    <div class="modal fade" id="changeInvoiceStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Changer le statut de la facture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="changeInvoiceStatusForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nouveau statut</label>
                            <select name="status" class="form-select" required>
                                <option value="draft">Brouillon</option>
                                <option value="sent">Envoyée</option>
                                <option value="paid">Payée</option>
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

    {{-- Modal Marquer comme Payé --}}
    <div class="modal fade" id="markInvoicePaidModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Marquer la facture comme payée</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="markInvoicePaidForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir marquer cette facture comme payée ?</p>
                        <div class="mb-3">
                            <label class="form-label">Date de paiement</label>
                            <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}"
                                required>
                        </div>
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
            const invoiceStatusRouteTemplate =
                "{{ route('admin.invoices.update-status', ['invoice' => ':invoiceId']) }}";
            const markPaidRouteTemplate = "{{ route('admin.invoices.mark-paid', ['invoice' => ':invoiceId']) }}";

            // Initialize DataTables
            $("#table_facture_tout, #table_facture_brouillon, #table_facture_envoyee, #table_facture_payee, #table_facture_annulee")
                .DataTable({
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
                });

            // Adjust DataTables on tab change
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                $.fn.dataTable.tables({
                    visible: true,
                    api: true
                }).columns.adjust();
            });

            // Modal handlers
            document.querySelectorAll('.change-invoice-status-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var invoiceId = this.dataset.invoiceId;
                    var currentStatus = this.dataset.currentStatus;
                    var form = document.getElementById('changeInvoiceStatusForm');
                    form.action = invoiceStatusRouteTemplate.replace(':invoiceId', invoiceId);
                    form.querySelector('select[name="status"]').value = currentStatus;
                });
            });

            document.querySelectorAll('.mark-invoice-paid-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var invoiceId = this.dataset.invoiceId;
                    var form = document.getElementById('markInvoicePaidForm');
                    form.action = markPaidRouteTemplate.replace(':invoiceId', invoiceId);
                });
            });
        });
    </script>
@endsection
