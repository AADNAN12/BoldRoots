@extends('admin.layouts.master')
@section('title', 'Gestion des rôles')
@section('head')
<!-- third party css -->
<link href="{{asset('assets/css/vendor/dataTables.bootstrap5.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/vendor/responsive.bootstrap5.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/vendor/buttons.bootstrap5.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/vendor/select.bootstrap5.css')}}" rel="stylesheet" type="text/css" />
<!-- third party css end -->
<!-- App css -->
<link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" id="light-style" />

<style>
    .modal-content {
        border: none;
        border-radius: 20px;
        overflow: hidden;
    }

    .modal-header {
        padding: 1.5rem;
    }

    .btn-close-custom {
        width: 36px;
        height: 36px;
        border: none;
        background: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.3s;
    }

    .btn-close-custom:hover {
        background-color: rgba(0, 0, 0, 0.1);
    }

    .input-group-custom {
        position: relative;
        margin-top: 15px;
    }

    .input-group-custom input,
    .input-group-custom select {
        width: 100%;
        padding: 12px;
        border: none;
        border-bottom: 2px solid #e0e0e0;
        background: transparent;
        font-size: 14px;
        outline: none;
        transition: all 0.3s ease;
    }

    .input-group-custom label {
        position: absolute;
        left: 12px;
        top: 12px;
        color: #999;
        font-size: 14px;
        pointer-events: none;
        transition: all 0.3s ease;
    }

    .input-group-custom input:focus~label,
    .input-group-custom input:valid~label,
    .input-group-custom select:focus~label,
    .input-group-custom select:valid~label {
        top: -10px;
        font-size: 12px;
        color: #2196f3;
    }

    .actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-cancel,
    .btn-save {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-cancel {
        background-color: #f5f5f5;
        color: #666;
    }

    .btn-save {
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: white;
    }

    .btn-cancel:hover {
        background-color: #e0e0e0;
    }

    .btn-save:hover {
        opacity: 0.9;
    }

    @media (max-width: 768px) {
        .modal-dialog {
            margin: 10px;
        }

        .col-md-4,
        .col-md-6 {
            width: 100%;
        }
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
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Accueil</a></li>
                    <li class="breadcrumb-item active">Rôles</li>
                </ol>
            </div>
            <h4 class="page-title">Gestion des rôles</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <strong>Succès - </strong> {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <strong>Erreur - </strong> {{ session('error') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <strong>Erreur(s) - </strong>
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-6 d-flex align-items-center">
                                <h3 class="header-title m-0 fs-4">
                                    <i class="mdi mdi-shield-account me-2 fs-4"></i>Liste des rôles
                                </h3>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end p-0 ">
                                @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->can('create_roles'))
                                <button type="button" class="btn btn-info me-1" data-bs-toggle="modal"
                                    data-bs-target="#AjouterRole"><i
                                        class="mdi mdi-shield-plus me-2"></i>Nouveau rôle</button>
                                @endif
                                <a href="#" class="btn btn-light d-flex align-items-center"><i
                                        class="mdi mdi-arrow-left me-1"></i>
                                    Retour</a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="scroll-horizontal-datatable" class="table w-100 nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Permissions</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($roles as $role)
                                    <tr>
                                        <td>{{ $role->id }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>
                                            @foreach($role->permissions as $permission)
                                            <span class="badge bg-info me-1">{{ $permission->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if(Auth::guard('admin')->user()->can('edit_roles'))
                                            <a class="action-icon edit-role" data-bs-toggle="modal"
                                                data-bs-target="#ModifierRole" data-id="{{ $role->id }}"
                                                data-name="{{ $role->name }}"
                                                data-permissions="{{ $role->permissions->pluck('name') }}">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            @endif
                                            @if(Auth::guard('admin')->user()->can('delete_roles'))
                                            @if(!in_array($role->name, ['admin', 'user']))
                                            <a class="action-icon delete-role" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal" data-id="{{ $role->id }}">
                                                <i class="mdi mdi-delete"></i>
                                            </a>
                                            @endif
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
</div>

<!-- Modal Ajouter Rôle -->
<div id="AjouterRole" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-white">
            <div class="modal-header modal-header-custom">
                <h4 class="modal-title-custom">
                    <span class="modal-title-icon">
                        <i class="mdi mdi-shield-plus"></i>
                    </span>
                    Ajouter un rôle
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-role" method="post" action="{{ route('admin.roles.store') }}" class="needs-validation" novalidate>
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="role_name" class="form-label">Nom du rôle <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="role_name" name="name" placeholder="Entrez le nom du rôle" required maxlength="255">
                                <div class="invalid-feedback">
                                    Le nom du rôle est requis
                                </div>
                            </div>
                        </div>
                        <h3 class="mb-3">Permissions</h3>
                        <div class="row" data-simplebar style="max-height: 400px;" data-simplebar-lg
                            data-simplebar-primary>
                            <div class="col-md-12">
                                <div class="row m-2">
                                    @foreach ($permissions->groupBy('group') as $group => $groupPermissions)
                                    <div class="col-md-12 mb-3" style="background-color:#eef2f7;">
                                        <div class="col-md-12 mb-1 d-flex align-items-center">
                                            <input type="checkbox" class="form-check-input form-checkbox-info check-all"
                                                data-group="{{ Str::slug($group) }}">
                                            <h4 class="m-2">{{ $group }}</h4>
                                        </div>
                                        <div class="row">
                                            @foreach ($groupPermissions as $permission)
                                            <div class="col-md-4 mb-2">
                                                <div class="form-check">
                                                    <input type="checkbox"
                                                        class="form-check-input form-checkbox-info {{ Str::slug($group) }}"
                                                        name="permissions[]"
                                                        value="{{ $permission->name }}"
                                                        id="permission_{{ $permission->id }}">
                                                    <label class="form-check-label"
                                                        for="permission_{{ $permission->id }}">{{ $permission->name }}</label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="permissions-error text-danger mt-2" style="display: none;">
                                    Veuillez sélectionner au moins une permission
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="actions mt-4">
                        <button type="button" class="btn-cancel" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-info">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Modifier Rôle -->
<div id="ModifierRole" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-white">
            <div class="modal-header modal-header-custom">
                <h4 class="modal-title-custom">
                    <span class="modal-title-icon">
                        <i class="mdi mdi-shield-edit"></i>
                    </span>
                    Modifier le rôle
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-role-edit" method="post" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="edit_role_name" class="form-label">Nom du rôle <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_role_name" name="name" placeholder="Entrez le nom du rôle" required maxlength="255">
                                <div class="invalid-feedback">
                                    Le nom du rôle est requis
                                </div>
                            </div>
                        </div>
                        <h5 class="mb-3">Permissions</h5>
                        <div class="row" data-simplebar style="max-height: 400px;" data-simplebar-lg
                            data-simplebar-primary>
                            <div class="col-md-12">
                                <div class="row m-3">
                                    @foreach ($permissions->groupBy('group') as $group => $groupPermissions)
                                    <div class="col-md-12 mb-3" style="background-color:#eef2f7;">
                                        <div class="col-md-12 mb-1 d-flex align-items-center">
                                            <input type="checkbox" class="form-check-input form-checkbox-info edit-check-all"
                                                data-group="{{ Str::slug($group) }}">
                                            <h4 class="m-2">{{ $group }}</h4>
                                        </div>
                                        <div class="row">
                                            @foreach ($groupPermissions as $permission)
                                            <div class="col-md-4 mb-2">
                                                <div class="form-check">
                                                    <input type="checkbox"
                                                        class="form-check-input form-checkbox-info edit-permission {{ Str::slug($group) }}"
                                                        name="permissions[]"
                                                        value="{{ $permission->name }}"
                                                        id="edit_permission_{{ $permission->id }}">
                                                    <label class="form-check-label"
                                                        for="edit_permission_{{ $permission->id }}">{{ $permission->name }}</label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="permissions-error text-danger mt-2" style="display: none;">
                                    Veuillez sélectionner au moins une permission
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="actions mt-4">
                        <button type="button" class="btn-cancel" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-info">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Supprimer -->
<div class="modal fade" id="deleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Confirmation de suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer ce rôle ?
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- third party js -->
<script src="{{asset('assets/js/vendor/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/vendor/dataTables.bootstrap5.js')}}"></script>
<script src="{{asset('assets/js/vendor/responsive.bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/js/vendor/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/js/vendor/buttons.bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/js/vendor/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/js/vendor/buttons.flash.min.js')}}"></script>
<script src="{{asset('assets/js/vendor/buttons.print.min.js')}}"></script>
<script src="{{asset('assets/js/vendor/dataTables.keyTable.min.js')}}"></script>
<script src="{{asset('assets/js/vendor/dataTables.select.min.js')}}"></script>
<!-- third party js ends -->

<script>
    $(document).ready(function() {
        // DataTable initialization
        var table = $('#scroll-horizontal-datatable').DataTable({
            "scrollX": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"
            }
        });

        // Initialiser le formulaire d'ajout
        $('#form-role').attr('action', '{{ route("admin.roles.store") }}');

        // Gestion des cases à cocher pour l'ajout
        $('.check-all').change(function() {
            var group = $(this).data('group');
            $('.' + group).prop('checked', $(this).prop('checked'));
        });

        $('.form-check-input').change(function() {
            if (!$(this).hasClass('check-all')) {
                var group = $(this).attr('class').split(' ').filter(function(cls) {
                    return cls !== 'form-check-input';
                })[0];

                var allChecked = $('.' + group).length === $('.' + group + ':checked').length;
                $('input.check-all[data-group="' + group + '"]').prop('checked', allChecked);
            }
        });

        // Gestion des cases à cocher pour la modification
        $('.edit-check-all').change(function() {
            var group = $(this).data('group');
            $('.edit-permission.' + group).prop('checked', $(this).prop('checked'));
        });

        $('.edit-permission').change(function() {
            var group = $(this).attr('class').split(' ').filter(function(cls) {
                return cls !== 'form-check-input' && cls !== 'edit-permission';
            })[0];

            var allChecked = $('.edit-permission.' + group).length === $('.edit-permission.' + group + ':checked').length;
            $('input.edit-check-all[data-group="' + group + '"]').prop('checked', allChecked);
        });

        // Fonction pour mettre à jour les cases à cocher principales dans le modal de modification
        function updateEditCheckAll() {
            $('.edit-check-all').each(function() {
                var group = $(this).data('group');
                var allChecked = $('.edit-permission.' + group + ':checked').length === $('.edit-permission.' + group).length;
                $(this).prop('checked', allChecked);
            });
        }

        // Edit role
        $('.edit-role').click(function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var permissions = $(this).data('permissions');

            var form = $('#form-role-edit');
            form.attr('action', '{{ url("admin/roles") }}/' + id);
            form.find('input[name="name"]').val(name);

            // Réinitialiser toutes les cases à cocher
            form.find('input[type="checkbox"]').prop('checked', false);

            // Cocher les permissions du rôle
            permissions.forEach(function(permission) {
                form.find('input[value="' + permission + '"]').prop('checked', true);
            });

            // Mettre à jour les cases à cocher principales
            updateEditCheckAll();
        });

        // Delete role
        $('.delete-role').click(function() {
            var id = $(this).data('id');
            $('#deleteForm').attr('action', '{{ url("admin/roles") }}/' + id);
        });

        // Validation Bootstrap
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                var permissions = form.querySelectorAll('input[name="permissions[]"]:checked').length;
                
                // Vérifier si au moins une permission est sélectionnée
                if (permissions === 0) {
                    event.preventDefault();
                    event.stopPropagation();
                    $(form).find('.permissions-error').show();
                } else {
                    $(form).find('.permissions-error').hide();
                }
                
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                form.classList.add('was-validated');
            }, false);
        });

        // Réinitialisation des formulaires à la fermeture des modals
        $('#AjouterRole, #ModifierRole').on('hidden.bs.modal', function() {
            var form = $(this).find('form');
            form.removeClass('was-validated');
            form[0].reset();
            form.find('.is-invalid').removeClass('is-invalid');
            form.find('.permissions-error').hide();
        });

        // Confirmation de suppression
        $('#deleteModal').on('show.bs.modal', function() {
            var button = $(document.activeElement);
            var roleName = button.closest('tr').find('td:eq(1)').text();
            $(this).find('.modal-body').html(
                'Êtes-vous sûr de vouloir supprimer le rôle <strong>' + roleName + '</strong> ? ' +
                'Cette action est irréversible.'
            );
        });
    });
</script>
@endsection