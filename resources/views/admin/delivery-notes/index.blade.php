@extends('admin.layouts.master')

@section('title', 'Gestion des Bons de Livraison')

@section('head')
    <link href="{{ asset('assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/vendor/responsive.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="page-title align-items-center">Gestion des Bons de Livraison</h4>
                <div class="page-title-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-2">
                            <li class="breadcrumb-item"><a href="#"><i class="uil-home-alt"></i> Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Bons de Livraison</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <ul class="nav nav-tabs mb-4 justify-content-center">
                <li class="nav-item">
                    <a href="#tabAll" data-bs-toggle="tab" class="nav-link active">
                        <i class="mdi mdi-clipboard-list-outline d-md-none"></i>
                        <span class="d-none d-md-inline">Tous</span>
                        <span class="badge bg-secondary ms-1">{{ $deliveryNotes->total() }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#tabPending" data-bs-toggle="tab" class="nav-link">
                        <i class="mdi mdi-clock-outline d-md-none text-warning"></i>
                        <span class="d-none d-md-inline text-warning">En Préparation</span>
                        <span class="badge bg-warning ms-1">{{ $notesPending->count() }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#tabShipped" data-bs-toggle="tab" class="nav-link">
                        <i class="mdi mdi-truck-fast-outline d-md-none text-info"></i>
                        <span class="d-none d-md-inline text-info">En Transit</span>
                        <span class="badge bg-info ms-1">{{ $notesShipped->count() }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#tabDelivered" data-bs-toggle="tab" class="nav-link">
                        <i class="mdi mdi-package-variant-closed d-md-none text-success"></i>
                        <span class="d-none d-md-inline text-success">Livrés</span>
                        <span class="badge bg-success ms-1">{{ $notesDelivered->count() }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#tabFailed" data-bs-toggle="tab" class="nav-link">
                        <i class="mdi mdi-alert-circle d-md-none text-danger"></i>
                        <span class="d-none d-md-inline text-danger">Échec</span>
                        <span class="badge bg-danger ms-1">{{ $notesFailed->count() }}</span>
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                {{-- Tab Tous --}}
                <div class="tab-pane fade show active" id="tabAll">
                    <div class="table-responsive">
                        <table id="table_bl_tout" class="table table-hover w-100">
                            <thead>
                                <tr>
                                    <th>N° BL / Statut</th>
                                    <th>N° Commande</th>
                                    <th>Client</th>
                                    <th>Transporteur</th>
                                    <th>Date</th>
                                    <th class="text-center" style="width: 200px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deliveryNotes as $note)
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'shipped' => 'info',
                                            'delivered' => 'success',
                                            'failed' => 'danger',
                                            'returned' => 'secondary'
                                        ];
                                        $statusLabels = [
                                            'pending' => 'En préparation',
                                            'shipped' => 'En transit',
                                            'delivered' => 'Livré',
                                            'failed' => 'Échec',
                                            'returned' => 'Retourné'
                                        ];
                                    @endphp
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.delivery-notes.show', $note) }}" class="text-decoration-none">
                                                <strong>{{ $note->delivery_note_number }}</strong>
                                            </a>
                                            <br>
                                            <span class="badge bg-{{ $statusColors[$note->status] ?? 'secondary' }}">
                                                {{ $statusLabels[$note->status] ?? $note->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $note->order) }}" class="text-decoration-none">
                                                {{ $note->order->order_number }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $note->order->user_id && $note->order->user ? $note->order->user->name : ($note->order->guest_name ?? 'Invité') }}<br>
                                            <small class="text-muted">{{ $note->order->user_id && $note->order->user ? $note->order->user->email : ($note->order->guest_email ?? '') }}</small>
                                        </td>
                                        <td>
                                            {{ $note->carrier_name ?? 'N/A' }}<br>
                                            @if($note->tracking_number)
                                                <small class="text-muted">{{ $note->tracking_number }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $note->created_at->format('d/m/Y') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.delivery-notes.show', $note) }}" class="action-icon" title="Voir">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            @if(!$note->pdf_path)
                                                <a href="{{ route('admin.delivery-notes.generate-pdf', $note) }}" class="action-icon text-primary" title="Générer PDF">
                                                    <i class="mdi mdi-file-pdf-box"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('admin.delivery-notes.download-pdf', $note) }}" class="action-icon text-success" title="Télécharger PDF">
                                                    <i class="mdi mdi-download"></i>
                                                </a>
                                            @endif
                                            @if($note->status !== 'delivered' && $note->status !== 'failed')
                                                <a href="#" class="action-icon change-note-status-btn" 
                                                    data-bs-toggle="modal" data-bs-target="#changeNoteStatusModal"
                                                    data-note-id="{{ $note->id }}" data-current-status="{{ $note->status }}"
                                                    title="Changer statut">
                                                    <i class="mdi mdi-swap-horizontal"></i>
                                                </a>
                                                <a href="#" class="action-icon update-tracking-btn" 
                                                    data-bs-toggle="modal" data-bs-target="#updateTrackingModal"
                                                    data-note-id="{{ $note->id }}"
                                                    title="Mettre à jour suivi">
                                                    <i class="mdi mdi-truck-delivery"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tab En Préparation --}}
                <div class="tab-pane fade" id="tabPending">
                    <div class="table-responsive">
                        <table id="table_bl_preparation" class="table table-hover w-100">
                            <thead>
                                <tr>
                                    <th>N° BL</th>
                                    <th>N° Commande</th>
                                    <th>Client</th>
                                    <th>Transporteur</th>
                                    <th>Date</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notesPending as $note)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.delivery-notes.show', $note) }}" class="text-decoration-none">
                                                <strong>{{ $note->delivery_note_number }}</strong>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $note->order) }}" class="text-decoration-none">
                                                {{ $note->order->order_number }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $note->order->user_id && $note->order->user ? $note->order->user->name : ($note->order->guest_name ?? 'Invité') }}<br>
                                            <small class="text-muted">{{ $note->order->user_id && $note->order->user ? $note->order->user->email : ($note->order->guest_email ?? '') }}</small>
                                        </td>
                                        <td>{{ $note->carrier_name ?? 'N/A' }}</td>
                                        <td>{{ $note->created_at->format('d/m/Y') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.delivery-notes.show', $note) }}" class="action-icon" title="Voir">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            @if(!$note->pdf_path)
                                                <a href="{{ route('admin.delivery-notes.generate-pdf', $note) }}" class="action-icon text-primary" title="Générer PDF">
                                                    <i class="mdi mdi-file-pdf-box"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('admin.delivery-notes.download-pdf', $note) }}" class="action-icon text-success" title="Télécharger PDF">
                                                    <i class="mdi mdi-download"></i>
                                                </a>
                                            @endif
                                            <a href="#" class="action-icon change-note-status-btn" 
                                                data-bs-toggle="modal" data-bs-target="#changeNoteStatusModal"
                                                data-note-id="{{ $note->id }}" data-current-status="{{ $note->status }}"
                                                title="Changer statut">
                                                <i class="mdi mdi-swap-horizontal"></i>
                                            </a>
                                            <a href="#" class="action-icon update-tracking-btn" 
                                                data-bs-toggle="modal" data-bs-target="#updateTrackingModal"
                                                data-note-id="{{ $note->id }}"
                                                title="Mettre à jour suivi">
                                                <i class="mdi mdi-truck-delivery"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tab En Transit --}}
                <div class="tab-pane fade" id="tabShipped">
                    <div class="table-responsive">
                        <table id="table_bl_transit" class="table table-hover w-100">
                            <thead>
                                <tr>
                                    <th>N° BL</th>
                                    <th>N° Commande</th>
                                    <th>Client</th>
                                    <th>Transporteur</th>
                                    <th>N° Suivi</th>
                                    <th>Date</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notesShipped as $note)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.delivery-notes.show', $note) }}" class="text-decoration-none">
                                                <strong>{{ $note->delivery_note_number }}</strong>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $note->order) }}" class="text-decoration-none">
                                                {{ $note->order->order_number }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $note->order->user_id && $note->order->user ? $note->order->user->name : ($note->order->guest_name ?? 'Invité') }}<br>
                                            <small class="text-muted">{{ $note->order->user_id && $note->order->user ? $note->order->user->email : ($note->order->guest_email ?? '') }}</small>
                                        </td>
                                        <td>{{ $note->carrier_name ?? 'N/A' }}</td>
                                        <td><code>{{ $note->tracking_number ?? 'N/A' }}</code></td>
                                        <td>{{ $note->created_at->format('d/m/Y') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.delivery-notes.show', $note) }}" class="action-icon" title="Voir">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            @if($note->pdf_path)
                                                <a href="{{ route('admin.delivery-notes.download-pdf', $note) }}" class="action-icon text-success" title="Télécharger PDF">
                                                    <i class="mdi mdi-download"></i>
                                                </a>
                                            @endif
                                            <a href="#" class="action-icon text-success mark-delivered-btn" 
                                                data-bs-toggle="modal" data-bs-target="#markDeliveredModal"
                                                data-note-id="{{ $note->id }}"
                                                title="Marquer comme livré">
                                                <i class="mdi mdi-check-circle"></i>
                                            </a>
                                            <a href="#" class="action-icon update-tracking-btn" 
                                                data-bs-toggle="modal" data-bs-target="#updateTrackingModal"
                                                data-note-id="{{ $note->id }}"
                                                title="Mettre à jour suivi">
                                                <i class="mdi mdi-truck-delivery"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tab Livrés --}}
                <div class="tab-pane fade" id="tabDelivered">
                    <div class="table-responsive">
                        <table id="table_bl_livre" class="table table-hover w-100">
                            <thead>
                                <tr>
                                    <th>N° BL</th>
                                    <th>N° Commande</th>
                                    <th>Client</th>
                                    <th>Transporteur</th>
                                    <th>Date Livraison</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notesDelivered as $note)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.delivery-notes.show', $note) }}" class="text-decoration-none">
                                                <strong>{{ $note->delivery_note_number }}</strong>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $note->order) }}" class="text-decoration-none">
                                                {{ $note->order->order_number }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $note->order->user_id && $note->order->user ? $note->order->user->name : ($note->order->guest_name ?? 'Invité') }}<br>
                                            <small class="text-muted">{{ $note->order->user_id && $note->order->user ? $note->order->user->email : ($note->order->guest_email ?? '') }}</small>
                                        </td>
                                        <td>{{ $note->carrier_name ?? 'N/A' }}</td>
                                        <td>{{ $note->delivered_at ? $note->delivered_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.delivery-notes.show', $note) }}" class="action-icon" title="Voir">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            @if($note->pdf_path)
                                                <a href="{{ route('admin.delivery-notes.download-pdf', $note) }}" class="action-icon text-success" title="Télécharger PDF">
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

                {{-- Tab Échec --}}
                <div class="tab-pane fade" id="tabFailed">
                    <div class="table-responsive">
                        <table id="table_bl_echec" class="table table-hover w-100">
                            <thead>
                                <tr>
                                    <th>N° BL</th>
                                    <th>N° Commande</th>
                                    <th>Client</th>
                                    <th>Transporteur</th>
                                    <th>Date</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notesFailed as $note)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.delivery-notes.show', $note) }}" class="text-decoration-none">
                                                <strong>{{ $note->delivery_note_number }}</strong>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $note->order) }}" class="text-decoration-none">
                                                {{ $note->order->order_number }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $note->order->user_id && $note->order->user ? $note->order->user->name : ($note->order->guest_name ?? 'Invité') }}<br>
                                            <small class="text-muted">{{ $note->order->user_id && $note->order->user ? $note->order->user->email : ($note->order->guest_email ?? '') }}</small>
                                        </td>
                                        <td>{{ $note->carrier_name ?? 'N/A' }}</td>
                                        <td>{{ $note->created_at->format('d/m/Y') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.delivery-notes.show', $note) }}" class="action-icon" title="Voir">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            @if($note->pdf_path)
                                                <a href="{{ route('admin.delivery-notes.download-pdf', $note) }}" class="action-icon text-success" title="Télécharger PDF">
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

{{-- Modal Changer Statut --}}
<div class="modal fade" id="changeNoteStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Changer le statut du bon de livraison</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="changeNoteStatusForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nouveau statut</label>
                        <select name="status" class="form-select" required>
                            <option value="pending">En préparation</option>
                            <option value="shipped">En transit</option>
                            <option value="delivered">Livré</option>
                            <option value="failed">Échec</option>
                            <option value="returned">Retourné</option>
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

{{-- Modal Mettre à jour Suivi --}}
<div class="modal fade" id="updateTrackingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mettre à jour le suivi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="updateTrackingForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Transporteur</label>
                        <input type="text" name="carrier_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Numéro de suivi</label>
                        <input type="text" name="tracking_number" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Marquer comme Livré --}}
<div class="modal fade" id="markDeliveredModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Marquer comme livré</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="markDeliveredForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir marquer ce bon de livraison comme livré ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Oui, marquer comme livré</button>
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
    const noteStatusRouteTemplate = "{{ route('admin.delivery-notes.update-status', ['deliveryNote' => ':noteId']) }}";
    const updateTrackingRouteTemplate = "{{ route('admin.delivery-notes.update-tracking', ['deliveryNote' => ':noteId']) }}";
    const markDeliveredRouteTemplate = "{{ route('admin.delivery-notes.mark-delivered', ['deliveryNote' => ':noteId']) }}";
    
    // Initialize DataTables
    $("#table_bl_tout, #table_bl_preparation, #table_bl_transit, #table_bl_livre, #table_bl_echec")
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
    document.querySelectorAll('.change-note-status-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var noteId = this.dataset.noteId;
            var currentStatus = this.dataset.currentStatus;
            var form = document.getElementById('changeNoteStatusForm');
            form.action = noteStatusRouteTemplate.replace(':noteId', noteId);
            form.querySelector('select[name="status"]').value = currentStatus;
        });
    });

    document.querySelectorAll('.update-tracking-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var noteId = this.dataset.noteId;
            var form = document.getElementById('updateTrackingForm');
            form.action = updateTrackingRouteTemplate.replace(':noteId', noteId);
        });
    });

    document.querySelectorAll('.mark-delivered-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var noteId = this.dataset.noteId;
            var form = document.getElementById('markDeliveredForm');
            form.action = markDeliveredRouteTemplate.replace(':noteId', noteId);
        });
    });
});
</script>
@endsection
