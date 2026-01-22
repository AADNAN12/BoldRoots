@extends('admin.layouts.master')

@section('title', 'Gestion des coupons')

@section('head')
    <link href="{{ asset('assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/vendor/responsive.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .coupon-code {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            font-size: 14px;
            background: #f0f1ff;
            padding: 5px 10px;
            border-radius: 4px;
            color: #727cf5;
        }

        .action-icon {
            font-size: 18px;
            margin: 0 5px;
            cursor: pointer;
        }

        .badge-coupon {
            font-size: 10px;
            padding: 3px 8px;
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
                            <li class="breadcrumb-item active" aria-current="page">Gestion des coupons</li>
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
                            <h4 class="header-title mb-0">Liste des coupons</h4>
                            <p class="text-muted mt-1">Gérez les codes de réduction</p>
                        </div>
                        <div class="col-sm-6">
                            <div class="float-end">
                                @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->can('create_coupons'))
                                <a href="{{ route('admin.coupons.bulk-create') }}" class="btn btn-secondary mb-3 me-1">
                                    <i class="mdi mdi-content-duplicate me-1"></i> Création en masse
                                </a>
                                <a href="{{ route('admin.coupons.create') }}" class="btn btn-info mb-3">
                                    <i class="mdi mdi-plus-circle me-1"></i> Nouveau coupon
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <table id="coupons-table" class="table table-striped table-centered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Réduction</th>
                                <th>Restrictions</th>
                                <th>Période</th>
                                <th>Utilisations</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($coupons as $coupon)
                                <tr>
                                    <td>
                                        <span class="coupon-code">{{ $coupon->code }}</span>
                                        @if ($coupon->user_specific)
                                            <br><span class="badge bg-purple badge-coupon mt-1">
                                                <i class="mdi mdi-account"></i> Spécifique
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($coupon->description)
                                            {{ Str::limit($coupon->description, 50) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($coupon->type === 'percentage')
                                            <strong class="text-primary">{{ $coupon->discount_value }}%</strong>
                                        @elseif ($coupon->type === 'fixed_amount')
                                            <strong class="text-primary">{{ number_format($coupon->discount_value, 2) }} MAD</strong>
                                        @else
                                            <strong class="text-success">Livraison gratuite</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($coupon->min_cart_value)
                                            <span class="badge bg-info badge-coupon">
                                                Min: {{ number_format($coupon->min_cart_value, 2) }} MAD
                                            </span>
                                        @endif
                                        @if ($coupon->exclude_new_products)
                                            <br><span class="badge bg-warning badge-coupon mt-1">
                                                Exclut nouveaux produits
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>
                                            <strong>Début:</strong> {{ $coupon->valid_from ? $coupon->valid_from->format('d/m/Y H:i') : '-' }}<br>
                                            @if ($coupon->valid_until)
                                                <strong>Fin:</strong> {{ $coupon->valid_until->format('d/m/Y H:i') }}
                                            @else
                                                <strong>Fin:</strong> <span class="text-muted">Illimité</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $coupon->used_count ?? 0 }}</strong>
                                            @if ($coupon->usage_limit)
                                                / {{ $coupon->usage_limit }}
                                            @endif
                                        </div>
                                        @if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit)
                                            <span class="badge bg-danger badge-coupon">Épuisé</span>
                                        @endif
                                        @if ($coupon->usage_per_customer)
                                            <br><small class="text-muted">{{ $coupon->usage_per_customer }}/utilisateur</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($coupon->is_active && (!$coupon->valid_until || now()->lte($coupon->valid_until)))
                                            <span class="badge bg-success">Actif</span>
                                        @elseif($coupon->valid_until && now()->gt($coupon->valid_until))
                                            <span class="badge bg-secondary">Expiré</span>
                                        @else
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(Auth::guard('admin')->user()->can('view_coupons'))
                                        <a href="{{ route('admin.coupons.show', $coupon->id) }}" class="action-icon"
                                            title="Détails">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                        @endif
                                        @if(Auth::guard('admin')->user()->can('edit_coupons'))
                                        <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="action-icon"
                                            title="Modifier">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        @endif
                                        @if(Auth::guard('admin')->user()->can('delete_coupons'))
                                        <a href="javascript:void(0);" class="action-icon delete-coupon"
                                            data-id="{{ $coupon->id }}" data-code="{{ $coupon->code }}"
                                            title="Supprimer">
                                            <i class="mdi mdi-delete"></i>
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

    <!-- Modal de confirmation de suppression -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer le coupon <strong id="couponCode"></strong> ?</p>
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
            $('#coupons-table').DataTable({
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
            $('.delete-coupon').on('click', function() {
                const couponId = $(this).data('id');
                const couponCode = $(this).data('code');

                $('#couponCode').text(couponCode);
                $('#deleteForm').attr('action', `/admin/coupons/${couponId}`);

                const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                deleteModal.show();
            });

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
