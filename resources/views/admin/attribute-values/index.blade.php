@extends('admin.layouts.master')

@section('title', 'Gestion des Valeurs - ' . $attribute->name)
@section('head')
<!-- third party css -->
<link href="{{ asset('assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/vendor/responsive.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/vendor/buttons.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
<style>
    .color-preview {
        display: inline-block;
        width: 30px;
        height: 30px;
        border-radius: 4px;
        border: 1px solid #ddd;
        vertical-align: middle;
    }
</style>
@endsection

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.attributes.index') }}">Attributs</a></li>
                    <li class="breadcrumb-item active">{{ $attribute->name }}</li>
                </ol>
            </div>
            <h4 class="page-title">Valeurs de l'attribut : {{ $attribute->name }}</h4>
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
                        <h4 class="header-title mb-0">Liste des valeurs</h4>
                        <p class="text-muted mt-1">
                            Type: 
                            @if($attribute->type === 'color')
                                <span class="badge bg-info">Couleur</span>
                            @else
                                <span class="badge bg-secondary">Sélection</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-end">
                            <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary mb-3 me-2">
                                <i class="mdi mdi-arrow-left me-1"></i> Retour
                            </a>
                            <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createValueModal">
                                <i class="mdi mdi-plus-circle me-1"></i> Nouvelle valeur
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table w-100 nowrap table-striped" id="valuesTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                @if($attribute->type === 'color')
                                <th>Aperçu</th>
                                @endif
                                <th>Valeur</th>
                                @if($attribute->type === 'color')
                                <th>Code Couleur</th>
                                @endif
                                <th>Créé le</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($values as $value)
                            <tr>
                                <td>{{ $value->id }}</td>
                                @if($attribute->type === 'color')
                                <td>
                                    <span class="color-preview" style="background-color: {{ $value->color_code }}"></span>
                                </td>
                                @endif
                                <td>{{ $value->value }}</td>
                                @if($attribute->type === 'color')
                                <td><code>{{ $value->color_code }}</code></td>
                                @endif
                                <td>{{ $value->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="javascript:void(0);" class="action-icon edit-value" 
                                       data-id="{{ $value->id }}" 
                                       data-value="{{ $value->value }}"
                                       data-color="{{ $value->color_code }}"
                                       title="Modifier">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    <a href="javascript:void(0);" class="action-icon delete-value" 
                                       data-id="{{ $value->id }}" 
                                       data-value="{{ $value->value }}" 
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
</div>

<!-- Modal Création -->
<div class="modal fade" id="createValueModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une valeur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createValueForm" action="{{ route('admin.attribute-values.store', $attribute) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Valeur *</label>
                        <input type="text" class="form-control" name="value" required>
                        <small class="text-muted">
                            @if($attribute->type === 'color')
                                Ex: Noir, Blanc, Rouge
                            @else
                                Ex: S, M, L, XL
                            @endif
                        </small>
                    </div>
                    @if($attribute->type === 'color')
                    <div class="mb-3">
                        <label class="form-label">Code Couleur *</label>
                        <div class="input-group">
                            <input type="color" class="form-control form-control-color" id="colorPicker" value="#000000">
                            <input type="text" class="form-control" name="color_code" id="colorCode" value="#000000" pattern="^#[0-9A-Fa-f]{6}$" required>
                        </div>
                        <small class="text-muted">Format hexadécimal (ex: #FF0000)</small>
                    </div>
                    @endif
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
<div class="modal fade" id="editValueModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier la valeur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editValueForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Valeur *</label>
                        <input type="text" class="form-control" name="value" id="edit_value" required>
                    </div>
                    @if($attribute->type === 'color')
                    <div class="mb-3">
                        <label class="form-label">Code Couleur *</label>
                        <div class="input-group">
                            <input type="color" class="form-control form-control-color" id="editColorPicker" value="#000000">
                            <input type="text" class="form-control" name="color_code" id="edit_color_code" value="#000000" pattern="^#[0-9A-Fa-f]{6}$" required>
                        </div>
                        <small class="text-muted">Format hexadécimal (ex: #FF0000)</small>
                    </div>
                    @endif
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
<div class="modal fade" id="deleteValueModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Supprimer la valeur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer la valeur <strong id="value-to-delete"></strong> ?</p>
                <p class="text-danger"><strong>Cette action est irréversible.</strong></p>
            </div>
            <form id="deleteValueForm" method="POST">
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
        $('#valuesTable').DataTable({
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

        @if($attribute->type === 'color')
        // Color picker synchronization for create form
        $('#colorPicker').on('input', function() {
            $('#colorCode').val($(this).val().toUpperCase());
        });

        $('#colorCode').on('input', function() {
            const color = $(this).val();
            if (/^#[0-9A-Fa-f]{6}$/.test(color)) {
                $('#colorPicker').val(color);
            }
        });

        // Color picker synchronization for edit form
        $('#editColorPicker').on('input', function() {
            $('#edit_color_code').val($(this).val().toUpperCase());
        });

        $('#edit_color_code').on('input', function() {
            const color = $(this).val();
            if (/^#[0-9A-Fa-f]{6}$/.test(color)) {
                $('#editColorPicker').val(color);
            }
        });
        @endif

        // Edit Value - Load data
        $('.edit-value').on('click', function() {
            const valueId = $(this).data('id');
            const value = $(this).data('value');
            const color = $(this).data('color');
            
            // Set form action
            $('#editValueForm').attr('action', `/admin/attributes/{{ $attribute->id }}/values/${valueId}`);
            
            // Fill form fields
            $('#edit_value').val(value);
            
            @if($attribute->type === 'color')
            if (color) {
                $('#edit_color_code').val(color);
                $('#editColorPicker').val(color);
            }
            @endif
            
            $('#editValueModal').modal('show');
        });

        // Delete Value - Show modal
        $('.delete-value').on('click', function() {
            const valueId = $(this).data('id');
            const value = $(this).data('value');

            $('#value-to-delete').text(value);
            $('#deleteValueForm').attr('action', `/admin/attributes/{{ $attribute->id }}/values/${valueId}`);
            $('#deleteValueModal').modal('show');
        });
    });
</script>
@endsection
