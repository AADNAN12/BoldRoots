@extends('admin.layouts.master')

@section('title', 'Gestion des produits')

@section('head')
    <link href="{{ asset('assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/vendor/responsive.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }

        .product-name {
            font-weight: 600;
            color: #313a46;
        }

        .product-sku {
            font-size: 12px;
            color: #98a6ad;
        }

        .badge-stock {
            font-size: 10px;
            padding: 3px 6px;
        }

        .action-buttons .btn {
            padding: 4px 8px;
            font-size: 12px;
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
                            <li class="breadcrumb-item"><a href="#"><i class="uil-home-alt"></i> Acceuil</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Gestion des produits</li>
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
                            <h4 class="header-title mb-0">Liste des produits</h4>
                            <p class="text-muted mt-1">Gérez les produits</p>
                        </div>
                        <div class="col-sm-6">
                            <div class="float-end">
                                <a href="{{ route('admin.products.create') }}" class="btn btn-info mb-3">
                                    <i class="mdi mdi-plus-circle me-1"></i> Nouveau produit
                                </a>
                            </div>
                        </div>
                    </div>



                    <table id="products-table" class="table table-striped table-centered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Produit</th>
                                <th>Catégorie</th>
                                <th>Prix</th>
                                <th>Stock</th>
                                <th>Variantes</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                @php
                                    $primaryImage = $product->images->where('is_homepage_image', true)->first();
                                    $totalStock = $product->variants->sum('quantity');
                                    $lowStockVariants = $product->variants
                                        ->filter(function ($v) {
                                            return $v->quantity > 0 && $v->quantity < $v->low_stock_threshold;
                                        })
                                        ->count();
                                    $outOfStockVariants = $product->variants->where('quantity', 0)->count();
                                @endphp
                                <tr>
                                    <td>
                                        @if ($primaryImage)
                                            <img src="{{ asset('storage/' . $primaryImage->image_path) }}"
                                                alt="{{ $product->name }}" class="product-image">
                                        @else
                                            <div
                                                class="product-image bg-light d-flex align-items-center justify-content-center">
                                                <i class="mdi mdi-image-off text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="product-name">{{ $product->name }}</div>
                                        <div class="product-sku">SKU: {{ $product->sku }}</div>
                                        @if ($product->is_new)
                                            <span class="badge bg-info badge-stock">Nouveau</span>
                                        @endif
                                        @if ($product->is_featured)
                                            <span class="badge bg-warning badge-stock">Vedette</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->category->name ?? '-' }}</td>
                                    <td>
                                        <strong>{{ number_format($product->price, 2) }} MAD</strong>
                                        @if ($product->compare_price)
                                            <br>
                                            <small class="text-muted">
                                                <del>{{ number_format($product->compare_price, 2) }} MAD</del>
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $totalStock }}</strong> unités
                                        </div>
                                        @if ($outOfStockVariants > 0)
                                            <span class="badge bg-danger badge-stock">
                                                {{ $outOfStockVariants }} rupture(s)
                                            </span>
                                        @endif
                                        @if ($lowStockVariants > 0)
                                            <span class="badge bg-warning badge-stock">
                                                {{ $lowStockVariants }} faible(s)
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $product->variants->count() }} variante(s)
                                        </span>
                                    </td>
                                    <td>
                                        @if ($product->is_active)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.products.edit', $product->id) }}" class="action-icon"
                                                title="Modifier">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <a type="button" class="action-icon delete-product"
                                                data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                                title="Supprimer">
                                                <i class="mdi mdi-delete"></i>
                                            </a>
                                        </div>
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
                    <p>Êtes-vous sûr de vouloir supprimer le produit <strong id="productName"></strong> ?</p>
                    <p class="text-danger">
                        <i class="mdi mdi-alert"></i>
                        Cette action supprimera également toutes les variantes et images associées.
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
            const table = $('#products-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
                },
                responsive: true,
                order: [
                    [1, 'asc']
                ],
                pageLength: 25,
                columnDefs: [{
                    orderable: false,
                    targets: [0, 7]
                }]
            });


            // Gestion de la suppression avec délégation d'événements
            $(document).on('click', '.delete-product', function(e) {
                e.preventDefault();
                const productId = $(this).data('id');
                const productName = $(this).data('name');

                $('#productName').text(productName);
                $('#deleteForm').attr('action', `/admin/products/${productId}`);

                const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                deleteModal.show();
            });
        });
    </script>
@endsection
