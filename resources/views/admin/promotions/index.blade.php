@extends('admin.layouts.master')

@section('title', 'Gestion des promotions')

@section('head')
    <link href="{{ asset('assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/vendor/responsive.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .promotion-badge {
            font-size: 10px;
            padding: 3px 8px;
        }

        .action-icon {
            font-size: 18px;
            margin: 0 5px;
            cursor: pointer;
        }

        .promotion-type-badge {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
        }

        .stats-card {
            border-left: 3px solid;
            padding: 15px;
            margin-bottom: 10px;
        }

        .stats-card.flash {
            border-color: #fa5c7c;
        }

        .stats-card.regular {
            border-color: #ffbc00;
        }

        .stats-card.buy-x-get-y {
            border-color: #0acf97;
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
                            <li class="breadcrumb-item"><a href="#"><i class="uil-home-alt"></i> Accueil</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Gestion des promotions</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-alert-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <h4 class="header-title mb-0">Liste des promotions</h4>
                            <p class="text-muted mt-1">Gérez les promotions et réductions</p>
                        </div>
                        <div class="col-sm-6">
                            <div class="float-end">
                                <a href="{{ route('admin.promotions.create') }}" class="btn btn-info mb-3">
                                    <i class="mdi mdi-plus-circle me-1"></i> Nouvelle promotion
                                </a>
                            </div>
                        </div>
                    </div>

                    <table id="promotions-table" class="table table-striped table-centered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Type</th>
                                <th>Réduction</th>
                                <th>Portée</th>
                                <th>Période</th>
                                <th>Utilisations</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($promotions as $promotion)
                                <tr>
                                    <td>
                                        <strong>{{ $promotion->name }}</strong>
                                        @if ($promotion->description)
                                            <br><small class="text-muted">{{ Str::limit($promotion->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($promotion->type === 'flash_deal')
                                            <span class="badge bg-danger promotion-type-badge">
                                                <i class="mdi mdi-flash"></i> Flash Deal
                                            </span>
                                        @elseif($promotion->type === 'regular_sale')
                                            <span class="badge bg-warning promotion-type-badge">
                                                <i class="mdi mdi-sale"></i> Promo
                                            </span>
                                        @else
                                            <span class="badge bg-success promotion-type-badge">
                                                <i class="mdi mdi-gift"></i> Achetez X Obtenez Y
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($promotion->discount_type === 'percentage')
                                            <strong class="text-primary">{{ $promotion->discount_value }}%</strong>
                                        @else
                                            <strong class="text-primary">{{ number_format($promotion->discount_value, 2) }} MAD</strong>
                                        @endif
                                        @if ($promotion->type === 'buy_x_get_y')
                                            <br><small class="text-muted">Achetez {{ $promotion->buy_quantity }}, obtenez {{ $promotion->get_quantity }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($promotion->scope === 'product')
                                            <span class="badge bg-info promotion-badge">
                                                <i class="mdi mdi-tag"></i> {{ $promotion->products->count() }} Produit(s)
                                            </span>
                                        @elseif($promotion->scope === 'collection')
                                            <span class="badge bg-purple promotion-badge">
                                                <i class="mdi mdi-folder"></i> {{ $promotion->categories->count() }} Catégorie(s)
                                            </span>
                                        @else
                                            <span class="badge bg-secondary promotion-badge">
                                                <i class="mdi mdi-cart"></i> Panier
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>
                                            <strong>Début:</strong> {{ $promotion->start_date ?? '-'}}<br>
                                            <strong>Fin:</strong> {{ $promotion->end_date ?? '-'}}
                                        </small>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $promotion->usage_count ?? 0 }}</strong>
                                            @if ($promotion->max_uses)
                                                / {{ $promotion->max_uses }}
                                            @endif
                                        </div>
                                        @if ($promotion->max_uses && $promotion->usage_count >= $promotion->max_uses)
                                            <span class="badge bg-danger promotion-badge">Limite atteinte</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($promotion->is_active && now()->between($promotion->start_date, $promotion->end_date))
                                            <span class="badge bg-success">Actif</span>
                                        @elseif($promotion->is_active && now()->lt($promotion->start_date))
                                            <span class="badge bg-info">Programmé</span>
                                        @elseif(now()->gt($promotion->end_date))
                                            <span class="badge bg-secondary">Expiré</span>
                                        @else
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.promotions.show', $promotion->id) }}" class="action-icon"
                                            title="Détails">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.promotions.edit', $promotion->id) }}" class="action-icon"
                                            title="Modifier">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="action-icon delete-promotion"
                                            data-id="{{ $promotion->id }}" data-name="{{ $promotion->name }}"
                                            title="Supprimer">
                                            <i class="mdi mdi-delete"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer la promotion <strong id="promotionName"></strong> ?</p>
                    <p class="text-danger">
                        <i class="mdi mdi-alert"></i>
                        Cette action est irréversible.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </div>
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
        $(document).ready(function() {
            // Initialiser DataTables
            $('#promotions-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
                },
                responsive: true,
                order: [
                    [4, 'desc']
                ],
                pageLength: 25,
                columnDefs: [{
                    orderable: false,
                    targets: [7]
                }]
            });

            // Supprimer
            $('.delete-promotion').on('click', function() {
                const promotionId = $(this).data('id');
                const promotionName = $(this).data('name');

                $('#promotionName').text(promotionName);
                $('#deleteForm').attr('action', `/admin/promotions/${promotionId}`);

                const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                deleteModal.show();
            });

            // Fonction pour afficher les alertes
            function showAlert(type, message) {
                const alertHtml = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-${type === 'success' ? 'check-circle' : 'alert-circle'} me-2"></i>
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                $('.card-body').prepend(alertHtml);
            }
        });
    </script>
@endsection
