@extends('admin.layouts.master')

@section('title', 'Gestion des FAQs')

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
                    <li class="breadcrumb-item"><a href="{{ route('admin.welcome') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">FAQs</li>
                </ol>
            </div>
            <h4 class="page-title">Gestion des FAQs</h4>
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
                        <h4 class="header-title mb-0">Liste des FAQs</h4>
                        <p class="text-muted mt-1">Gérez les questions fréquentes et leurs réponses</p>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-end">
                            @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->can('create_faqs'))
                            <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createFaqModal">
                                <i class="mdi mdi-plus-circle me-1"></i> Nouvelle FAQ
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table w-100 nowrap table-striped" id="faqsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Question</th>
                                <th>Catégorie</th>
                                <th>Ordre</th>
                                <th>Statut</th>
                                <th>Créé le</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($faqs as $faq)
                            <tr>
                                <td>{{ $faq->id }}</td>
                                <td>
                                    <strong>{{ Str::limit($faq->question, 80) }}</strong>
                                    @if(strlen($faq->question) > 80)
                                    <br><small class="text-muted">{{ Str::limit($faq->question, 150) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($faq->category)
                                    <span class="badge bg-info">{{ $faq->category }}</span>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $faq->order }}</span>
                                </td>
                                <td>
                                    @if($faq->is_active)
                                    <span class="badge bg-success">Active</span>
                                    @else
                                    <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $faq->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if(Auth::guard('admin')->user()->can('edit_faqs'))
                                    <a href="javascript:void(0);" class="action-icon edit-faq" 
                                       data-id="{{ $faq->id }}" 
                                       data-question="{{ $faq->question }}"
                                       data-answer="{{ $faq->answer }}"
                                       data-order="{{ $faq->order }}"
                                       data-is-active="{{ $faq->is_active ? 'true' : 'false' }}"
                                       title="Modifier">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    @endif
                                    @if(Auth::guard('admin')->user()->can('delete_faqs'))
                                    <a href="javascript:void(0);" class="action-icon delete-faq" data-id="{{ $faq->id }}" data-question="{{ Str::limit($faq->question, 50) }}" title="Supprimer">
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
</div>

<!-- Modal Création -->
<div class="modal fade" id="createFaqModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une FAQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createFaqForm" action="{{ route('admin.faqs.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Question <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="question" rows="3" required
                                          placeholder="Ex: Comment puis-je suivre ma commande ?"></textarea>
                                <small class="text-muted">La question que les visiteurs pourront voir</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Réponse <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="answer" rows="8" required
                                          placeholder="Réponse détaillée à la question..."></textarea>
                                <small class="text-muted">Vous pouvez utiliser le HTML pour formater votre réponse</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Ordre d'affichage</label>
                                <input type="number" class="form-control" name="order" min="0" value="0">
                                <small class="text-muted">0 = premier, nombres plus élevés = après</small>
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                                    <label class="form-check-label">
                                        <strong>FAQ Active</strong>
                                    </label>
                                </div>
                                <small class="text-muted">Cochez pour rendre cette FAQ visible sur le site</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save me-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Édition -->
<div class="modal fade" id="editFaqModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier la FAQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editFaqForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Question <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="question" id="edit_faq_question" rows="3" required></textarea>
                                <small class="text-muted">La question que les visiteurs pourront voir</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Réponse <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="answer" id="edit_faq_answer" rows="8" required></textarea>
                                <small class="text-muted">Vous pouvez utiliser le HTML pour formater votre réponse</small>
                            </div>
                        </div>

                        <div class="col-md-4">

                            <div class="mb-3">
                                <label class="form-label">Ordre d'affichage</label>
                                <input type="number" class="form-control" name="order" id="edit_faq_order" min="0">
                                <small class="text-muted">0 = premier, nombres plus élevés = après</small>
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="edit_faq_is_active" value="1">
                                    <label class="form-check-label">
                                        <strong>FAQ Active</strong>
                                    </label>
                                </div>
                                <small class="text-muted">Cochez pour rendre cette FAQ visible sur le site</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save me-1"></i> Modifier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Suppression -->
<div class="modal fade" id="deleteFaqModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Supprimer la FAQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer la FAQ <strong id="faq-question-to-delete"></strong> ?</p>
                <p class="text-danger"><strong>Cette action est irréversible.</strong></p>
            </div>
            <form id="deleteFaqForm" method="POST">
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
        $('#faqsTable').DataTable({
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

        // Edit FAQ - Load data
        $('.edit-faq').on('click', function() {
            const faqId = $(this).data('id');
            const question = $(this).data('question');
            const answer = $(this).data('answer');
            const order = $(this).data('order');
            const isActive = $(this).data('is-active') === 'true';
            
            // Set form action
            $('#editFaqForm').attr('action', `/admin/faqs/${faqId}`);
            
            // Fill form fields
            $('#edit_faq_question').val(question);
            $('#edit_faq_answer').val(answer);
            $('#edit_faq_order').val(order);
            $('#edit_faq_is_active').prop('checked', isActive);
            
            $('#editFaqModal').modal('show');
        });

        // No need for AJAX submit handler as we're using traditional form submission

        // Delete FAQ - Show modal
        $('.delete-faq').on('click', function() {
            const faqId = $(this).data('id');
            const faqQuestion = $(this).data('question');

            $('#faq-question-to-delete').text(faqQuestion);
            $('#deleteFaqForm').attr('action', `/admin/faqs/${faqId}`);
            $('#deleteFaqModal').modal('show');
        });

        // No need for AJAX submit handler as we're using traditional form submission
    });
</script>
@endsection
