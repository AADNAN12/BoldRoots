@extends('admin.layouts.master')
@section('title', 'Gestion des utilisateurs')
@section('head')
    <!-- third party css -->
    <link href="{{ asset('assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/vendor/responsive.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/vendor/buttons.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/vendor/select.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <!-- third party css end -->
    <!-- App css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />

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
                        <li class="breadcrumb-item active">Utilisateurs</li>
                    </ol>
                </div>
                <h4 class="page-title">Gestion des utilisateurs</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <strong>Succès - </strong> {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <strong>Erreur - </strong> {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <strong>Erreur(s) - </strong>
                    <ul>
                        @foreach ($errors->all() as $error)
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
                                        <i class="mdi mdi-account-multiple me-2 fs-4"></i>Liste des utilisateurs
                                    </h3>
                                </div>
                                <div class="col-md-6 d-flex justify-content-end p-0 ">
                                    @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->can('create_users'))
                                    <button type="button" class="btn btn-info me-1" data-bs-toggle="modal"
                                        data-bs-target="#AjouterUtilisateur"><i
                                            class="mdi mdi-account-plus me-2"></i>Nouveau utilisateur</button>
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
                                            <th>Email</th>
                                            <th>Téléphone</th>
                                            <th>Rôles</th>
                                            <th>Actif?</th>
                                            <th>Dernière connexion</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $key => $user)
                                            <tr>
                                                <td>{{ $user->id }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->phone ?? 'Non renseigné' }}</td>
                                                <td>
                                                    @foreach ($user->roles as $role)
                                                        <span class="badge bg-primary me-1">
                                                            {{ $role->name }}
                                                        </span>
                                                    @endforeach
                                                </td>
                                                <td> <input type="checkbox" class="checkActive"
                                                        id="switch{{ $key }}" data-id="{{ $user->id }}"
                                                        {{ $user->is_active == 1 ? 'checked' : '' }}
                                                        data-switch="success" />
                                                    <label for="switch{{ $key }}" data-on-label="on"
                                                        data-off-label="off" class="mb-0 d-block"></label>
                                                </td>
                                                <td>{{ $user->last_login ? $user->last_login->format('d/m/Y H:i') : 'Jamais' }}
                                                </td>
                                                <td>
                                                    @if(Auth::guard('admin')->user()->can('edit_users'))
                                                    <a class="action-icon edit-user" data-bs-toggle="modal"
                                                        data-bs-target="#ModifierUtilisateur" data-id="{{ $user->id }}"
                                                        data-name="{{ $user->name }}" data-email="{{ $user->email }}"
                                                        data-phone="{{ $user->phone }}"
                                                        data-roles="{{ json_encode($user->roles->pluck('name')) }}"
                                                        data-is-active="{{ $user->is_active ? 'active' : 'inactive' }}">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>
                                                    @endif
                                                    @if(Auth::guard('admin')->user()->can('delete_users'))
                                                    @if ($user->id !== auth()->id())
                                                        <a class="action-icon delete-user" data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal" data-id="{{ $user->id }}"
                                                            data-name="{{ $user->name }}">
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

    <!-- Modal Ajouter Utilisateur -->
    <div id="AjouterUtilisateur" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-white">
                <div class="modal-header modal-header-custom">
                    <h4 class="modal-title-custom">
                        <span class="modal-title-icon">
                            <i class="mdi mdi-account-plus"></i>
                        </span>
                        Ajouter un utilisateur
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-user" method="post" action="{{ route('admin.users.store') }}"
                        class="needs-validation" novalidate>
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_name" class="form-label">Nom complet <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="user_name" name="name"
                                        placeholder="Entrez le nom complet" required maxlength="255">
                                    <div class="invalid-feedback">
                                        Le nom complet est requis
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_email" class="form-label">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="user_email" name="email"
                                        placeholder="exemple@email.com" required maxlength="255">
                                    <div class="invalid-feedback">
                                        Veuillez entrer une adresse email valide
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div class="mb-3">
                                    <label for="user_password" class="form-label">Mot de passe <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" class="form-control" id="user_password" name="password"
                                            placeholder="Entrez le mot de passe" required minlength="8">
                                            
                                        <div class="input-group-text" data-password="false">
                                            <span class="password-eye"></span>
                                        </div>
                                        <div class="invalid-feedback">
                                            Le mot de passe est requis (minimum 8 caractères)
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_password_confirmation" class="form-label">Confirmer le mot de passe
                                        <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-merge">
                                    <input type="password" class="form-control" id="user_password_confirmation"
                                        name="password_confirmation" placeholder="Confirmez le mot de passe" required
                                        minlength="8">
                                        <div class="input-group-text" data-password="false">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        La confirmation du mot de passe est requise
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_phone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control" id="user_phone" name="phone"
                                        placeholder="Ex: 0612345678" maxlength="20">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_roles" class="form-label">Rôles <span
                                            class="text-danger">*</span></label>
                                    <select name="roles[]" id="user_roles" class="form-control select2"
                                        data-toggle="select2" required>
                                        <option value="">Sélectionner un rôle</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Veuillez sélectionner un rôle
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

    <!-- Modal Modifier Utilisateur -->
    <div id="ModifierUtilisateur" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-white">
                <div class="modal-header modal-header-custom">
                    <h4 class="modal-title-custom">
                        <span class="modal-title-icon">
                            <i class="mdi mdi-account-edit"></i>
                        </span>
                        Modifier l'utilisateur
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-user-edit" method="post" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_user_name" class="form-label">Nom complet <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_user_name" name="name"
                                        placeholder="Entrez le nom complet" required maxlength="255">
                                    <div class="invalid-feedback">
                                        Le nom complet est requis
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_user_email" class="form-label">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="edit_user_email" name="email"
                                        placeholder="exemple@email.com" required maxlength="255">
                                    <div class="invalid-feedback">
                                        Veuillez entrer une adresse email valide
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_user_password" class="form-label">Nouveau mot de passe
                                        (optionnel)</label>
                                    <input type="password" class="form-control" id="edit_user_password" name="password"
                                        placeholder="Laissez vide pour ne pas changer" minlength="8">
                                    <div class="invalid-feedback">
                                        Le mot de passe doit contenir au moins 8 caractères
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_user_password_confirmation" class="form-label">Confirmer le nouveau
                                        mot de passe</label>
                                    <input type="password" class="form-control" id="edit_user_password_confirmation"
                                        name="password_confirmation" placeholder="Confirmez le mot de passe"
                                        minlength="8">
                                    <div class="invalid-feedback">
                                        La confirmation doit correspondre au mot de passe
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_user_phone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control" id="edit_user_phone" name="phone"
                                        placeholder="Ex: 0612345678" maxlength="20">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_user_roles" class="form-label">Rôles <span
                                            class="text-danger">*</span></label>
                                    <select name="roles[]" id="edit_user_roles" class="form-control select2"
                                        data-toggle="select2" required>
                                        <option value="">Sélectionner un rôle</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Veuillez sélectionner un rôle
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_user_status" class="form-label">Statut</label>
                                    <select name="is_active" id="edit_user_status" class="form-control">
                                        <option value="active">Actif</option>
                                        <option value="inactive">Inactif</option>
                                    </select>
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
    <div class="modal fade" id="deleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Confirmation de suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer cet utilisateur : <strong><span id="userName"></span></strong> ?
                    </p>
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="POST">
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
    <script src="{{ asset('assets/js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/buttons.print.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%',
                dropdownParent: $('.select2').closest('.modal')
            });
            // DataTable initialization
            var table = $('#scroll-horizontal-datatable').DataTable({
                "scrollX": true,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"
                }
            });

            $('.checkActive').on('change', function() {
                var $switch = $(this);
                var id = $switch.data('id');
                var isActive = $switch.prop('checked') ? 1 : 0;

                $.ajax({
                    url: '{{ route('admin.users.toggle-status') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        user_id: id,
                        active: isActive,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        if (data.success) {
                            $.NotificationApp.send("Succès", data.message, "bottom-right",
                                "#089f74", "success");
                        } else {
                            $switch.prop('checked', !isActive);
                            $.NotificationApp.send("Erreur", data.message, "bottom-right",
                                "#e6294b", "error");
                        }
                    },
                    error: function(xhr, status, error) {
                        $switch.prop('checked', !isActive);
                        $.NotificationApp.send("Erreur", error, "bottom-right", "#e6294b",
                            "error");
                    }
                });
            });


            // Edit user
            $('.edit-user').click(function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var email = $(this).data('email');
                var phone = $(this).data('phone');
                var roles = $(this).data('roles');
                var isActive = $(this).data('is-active');

                var form = $('#form-user-edit');
                form.attr('action', '{{ url('admin/users') }}/' + id);
                form.find('input[name="name"]').val(name);
                form.find('input[name="email"]').val(email);
                form.find('input[name="phone"]').val(phone);
                form.find('select[name="roles[]"]').val(roles);
                form.find('select[name="is_active"]').val(isActive || 'active');
            });

            // Delete user
            $('.delete-user').click(function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('#deleteForm').attr('action', '{{ url('admin/users') }}/' + id);
                $('#userName').text(name);
            });

            // Validation Bootstrap
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    var password = form.querySelector('input[name="password"]');
                    var passwordConfirmation = form.querySelector(
                        'input[name="password_confirmation"]');

                    // Vérifier si les mots de passe correspondent
                    if (password && passwordConfirmation && password.value !== '' && password
                        .value !== passwordConfirmation.value) {
                        event.preventDefault();
                        event.stopPropagation();

                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur de confirmation',
                            text: 'Le mot de passe et la confirmation ne correspondent pas!',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#3085d6'
                        });

                        passwordConfirmation.classList.add('is-invalid');
                        return false;
                    }

                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    form.classList.add('was-validated');
                }, false);
            });

            // Réinitialiser la validation lors de la fermeture du modal
            $('.modal').on('hidden.bs.modal', function() {
                var form = $(this).find('form');
                form.removeClass('was-validated');
                form[0].reset();
                form.find('.is-invalid').removeClass('is-invalid');

                // Réinitialiser Select2
                form.find('.select2').val(null).trigger('change');
            });

            // Réinitialiser Select2 lors de l'ouverture du modal d'édition
            $('#ModifierUtilisateur').on('show.bs.modal', function() {
                setTimeout(function() {
                    $('#edit_user_roles').select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        dropdownParent: $('#ModifierUtilisateur')
                    });
                }, 100);
            });

            // Réinitialiser Select2 lors de l'ouverture du modal d'ajout
            $('#AjouterUtilisateur').on('show.bs.modal', function() {
                setTimeout(function() {
                    $('#user_roles').select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        dropdownParent: $('#AjouterUtilisateur')
                    });
                }, 100);
            });
        });
    </script>
@endsection
