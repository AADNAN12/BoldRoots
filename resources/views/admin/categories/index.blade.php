@extends('admin.layouts.master')

@section('title', 'Gestion des Catégories')
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
                    <li class="breadcrumb-item active">Catégories</li>
                </ol>
            </div>
            <h4 class="page-title">Gestion des catégories</h4>
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
                        <h4 class="header-title mb-0">Liste des catégories</h4>
                        <p class="text-muted mt-1">Gérez les catégories de produits</p>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-end">
                            <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                                <i class="mdi mdi-plus-circle me-1"></i> Nouvelle catégorie
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table w-100 nowrap table-striped" id="categoriesTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Slug</th>
                                <th>Parent</th>
                                <th>Description</th>
                                <th>Créé le</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->slug }}</td>
                                <td>{{ $category->parent ? $category->parent->name : '-' }}</td>
                                <td>{{ Str::limit($category->description, 50) }}</td>
                                <td>{{ $category->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="javascript:void(0);" class="action-icon edit-category" data-id="{{ $category->id }}" title="Modifier">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    <a href="javascript:void(0);" class="action-icon delete-category" data-id="{{ $category->id }}" data-name="{{ $category->name }}" title="Supprimer">
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
<div class="modal fade" id="createCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createCategoryForm" action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom *</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" class="form-control" name="slug">
                        <small class="text-muted">Généré automatiquement si vide</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catégorie parent</label>
                        <select class="form-select" name="parent_id">
                            <option value="">Aucune (catégorie racine)</option>
                            @foreach($parentCategories as $parentCategory)
                            <option value="{{ $parentCategory->id }}">{{ $parentCategory->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
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
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier la catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCategoryForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom *</label>
                        <input type="text" class="form-control" name="name" id="edit_category_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" class="form-control" name="slug" id="edit_category_slug">
                        <small class="text-muted">Généré automatiquement si vide</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catégorie parent</label>
                        <select class="form-select" name="parent_id" id="edit_category_parent">
                            <option value="">Aucune (catégorie racine)</option>
                            @foreach($parentCategories as $parentCategory)
                            <option value="{{ $parentCategory->id }}">{{ $parentCategory->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="edit_category_description" rows="3"></textarea>
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
<div class="modal fade" id="deleteCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Supprimer la catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer la catégorie <strong id="category-name-to-delete"></strong> ?</p>
                <p class="text-danger"><strong>Cette action est irréversible.</strong></p>
                <p>Si cette catégorie contient des sous-catégories ou des produits, vous devrez d'abord les supprimer ou les réaffecter.</p>
            </div>
            <form id="deleteCategoryForm" method="POST">
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
        $('#categoriesTable').DataTable({
            order: [],
            scrollX: !0,
            pageLength: 5,
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
        

        // No AJAX needed for create form - using traditional form submission

        // Edit Category - Load data
        $('.edit-category').on('click', function() {
            const categoryId = $(this).data('id');
            const categoryRow = $(this).closest('tr');
            
            // Get data from the table row
            const name = categoryRow.find('td:eq(1)').text().trim();
            const slug = categoryRow.find('td:eq(2)').text().trim();
            const parent = categoryRow.find('td:eq(3)').text().trim();
            const description = categoryRow.find('td:eq(4)').text().trim();
            
            // Set form action
            $('#editCategoryForm').attr('action', `/admin/categories/${categoryId}`);
            
            // Fill form fields
            $('#edit_category_name').val(name);
            $('#edit_category_slug').val(slug !== '-' ? slug : '');
            
            // Handle parent selection
            if (parent !== '-') {
                // Find the option with matching text
                $('#edit_category_parent option').each(function() {
                    if ($(this).text() === parent) {
                        $(this).prop('selected', true);
                        return false; // Break the loop
                    }
                });
            } else {
                $('#edit_category_parent').val(''); // No parent
            }
            
            $('#edit_category_description').val(description);
            $('#editCategoryModal').modal('show');
        });

        // No need for AJAX submit handler as we're using traditional form submission

        // Delete Category - Show modal
        $('.delete-category').on('click', function() {
            const categoryId = $(this).data('id');
            const categoryName = $(this).data('name');

            $('#category-name-to-delete').text(categoryName);
            $('#deleteCategoryForm').attr('action', `/admin/categories/${categoryId}`);
            $('#deleteCategoryModal').modal('show');
        });

        // No need for AJAX submit handler as we're using traditional form submission
    });
</script>
@endsection