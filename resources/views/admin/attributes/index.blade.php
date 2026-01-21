@extends('admin.layouts.master')

@section('title', 'Gestion des Attributs')
@section('head')
<!-- third party css -->
<link href="{{ asset('assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/vendor/responsive.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/vendor/buttons.bootstrap5.css') }}" rel="stylesheet" type="text/css" />

@endsection

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                    <li class="breadcrumb-item active">Attributs</li>
                </ol>
            </div>
            <h4 class="page-title">Gestion des attributs (Couleurs & Tailles)</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <!-- Flash Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <h4 class="header-title mb-0">Liste des attributs</h4>
                        <p class="text-muted mt-1">Gérez les attributs de produits (couleurs, tailles, etc.)</p>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-end">
                            <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createAttributeModal">
                                <i class="mdi mdi-plus-circle me-1"></i> Nouvel attribut
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table w-100 nowrap table-striped" id="attributesTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Slug</th>
                                <th>Type</th>
                                <th>Nb Valeurs</th>
                                <th>Créé le</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attributes as $attribute)
                            <tr>
                                <td>{{ $attribute->id }}</td>
                                <td>{{ $attribute->name }}</td>
                                <td>{{ $attribute->slug }}</td>
                                <td>
                                    @if($attribute->type === 'color')
                                        <span class="badge bg-info">Couleur</span>
                                    @else
                                        <span class="badge bg-secondary">Sélection</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $attribute->values_count }}</span>
                                </td>
                                <td>{{ $attribute->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.attribute-values.index', $attribute) }}" class="action-icon" title="Gérer les valeurs">
                                        <i class="mdi mdi-format-list-bulleted"></i>
                                    </a>
                                    <a href="javascript:void(0);" class="action-icon edit-attribute" data-id="{{ $attribute->id }}" title="Modifier">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    <a href="javascript:void(0);" class="action-icon delete-attribute" data-id="{{ $attribute->id }}" data-name="{{ $attribute->name }}" title="Supprimer">
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
</div>

<!-- Modal Création -->
<div class="modal fade" id="createAttributeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un attribut</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createAttributeForm" action="{{ route('admin.attributes.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom *</label>
                        <input type="text" class="form-control" name="name" required>
                        <small class="text-muted">Ex: Couleur, Taille, Matière</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" class="form-control" name="slug">
                        <small class="text-muted">Généré automatiquement si vide</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type *</label>
                        <select class="form-select" name="type" required>
                            <option value="select">Sélection (Taille, Matière, etc.)</option>
                            <option value="color">Couleur (avec code couleur)</option>
                        </select>
                        <small class="text-muted">Le type "Couleur" permet d'ajouter un code couleur hexadécimal</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Édition -->
<div class="modal fade" id="editAttributeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier l'attribut</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editAttributeForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom *</label>
                        <input type="text" class="form-control" name="name" id="edit_attribute_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" class="form-control" name="slug" id="edit_attribute_slug">
                        <small class="text-muted">Généré automatiquement si vide</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type *</label>
                        <select class="form-select" name="type" id="edit_attribute_type" required>
                            <option value="select">Sélection (Taille, Matière, etc.)</option>
                            <option value="color">Couleur (avec code couleur)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Modifier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Suppression -->
<div class="modal fade" id="deleteAttributeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Supprimer l'attribut</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer l'attribut <strong id="attribute-name-to-delete"></strong> ?</p>
                <p class="text-danger"><strong>Cette action est irréversible.</strong></p>
                <p>Toutes les valeurs associées à cet attribut seront également supprimées.</p>
            </div>
            <form id="deleteAttributeForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- third party js -->
<script src="{{ asset('assets/js/vendor/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/dataTables.bootstrap5.js') }}"></script>
<script src="{{ asset('assets/js/vendor/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/responsive.bootstrap5.min.js') }}"></script>
<script>
    $(document).ready(function() {
        // DataTable initialization
        $('#attributesTable').DataTable({
            order: [],
            scrollX: !0,
            pageLength: 10,
            lengthMenu: [
                [5, 10, 20, -1],
                [5, 10, 25, 50, 100],
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

        // Edit Attribute - Load data
        $('.edit-attribute').on('click', function() {
            const attributeId = $(this).data('id');
            const attributeRow = $(this).closest('tr');
            
            // Get data from the table row
            const name = attributeRow.find('td:eq(1)').text().trim();
            const slug = attributeRow.find('td:eq(2)').text().trim();
            const typeBadge = attributeRow.find('td:eq(3) .badge').text().trim();
            const type = typeBadge === 'Couleur' ? 'color' : 'select';
            
            // Set form action
            $('#editAttributeForm').attr('action', `/admin/attributes/${attributeId}`);
            
            // Fill form fields
            $('#edit_attribute_name').val(name);
            $('#edit_attribute_slug').val(slug !== '-' ? slug : '');
            $('#edit_attribute_type').val(type);
            
            $('#editAttributeModal').modal('show');
        });

        // Delete Attribute - Show modal
        $('.delete-attribute').on('click', function() {
            const attributeId = $(this).data('id');
            const attributeName = $(this).data('name');

            $('#attribute-name-to-delete').text(attributeName);
            $('#deleteAttributeForm').attr('action', `/admin/attributes/${attributeId}`);
            $('#deleteAttributeModal').modal('show');
        });
    });
</script>
@endsection
