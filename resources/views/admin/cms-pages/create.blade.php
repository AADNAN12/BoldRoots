@extends('admin.layouts.master')

@section('title', 'Créer une Page CMS')

@section('head')
<link href="{{ asset('assets/css/vendor/quill.core.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/vendor/quill.snow.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">Créer une Page CMS</h2>
        </div>
    </div>

    <form action="{{ route('admin.cms-pages.store') }}" method="POST">
        @csrf
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug (URL)</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                   id="slug" name="slug" value="{{ old('slug') }}" placeholder="Généré automatiquement si vide">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Laissez vide pour générer automatiquement depuis le titre</small>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Contenu</label>
                            <div id="editor" style="height: 400px;">
                                {!! old('content') !!}
                            </div>
                            <input type="hidden" name="content" id="content">
                            @error('content')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Paramètres</h5>
                        
                        <div class="mb-3">
                            <label for="order" class="form-label">Ordre d'affichage</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                   id="order" name="order" value="{{ old('order', 0) }}" min="0">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Plus le nombre est petit, plus la page apparaît en premier</small>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Page active
                            </label>
                        </div>

                        <hr>

                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="mdi mdi-content-save me-1"></i> Créer la Page
                        </button>
                        
                        <a href="{{ route('admin.cms-pages.index') }}" class="btn btn-secondary w-100">
                            <i class="mdi mdi-arrow-left me-1"></i> Annuler
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/vendor/quill.min.js') }}"></script>
<script>
    var quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'align': [] }],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    // Mettre à jour le champ caché en temps réel
    quill.on('text-change', function() {
        document.querySelector('#content').value = quill.root.innerHTML;
    });

    // S'assurer que le contenu est copié avant la soumission
    document.querySelector('form').addEventListener('submit', function(e) {
        document.querySelector('#content').value = quill.root.innerHTML;
    });

    document.getElementById('title').addEventListener('input', function() {
        var slug = this.value
            .toLowerCase()
            .replace(/[àáâãäå]/g, 'a')
            .replace(/[èéêë]/g, 'e')
            .replace(/[ìíîï]/g, 'i')
            .replace(/[òóôõö]/g, 'o')
            .replace(/[ùúûü]/g, 'u')
            .replace(/[ç]/g, 'c')
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        
        if (!document.getElementById('slug').value || document.getElementById('slug').dataset.auto) {
            document.getElementById('slug').value = slug;
            document.getElementById('slug').dataset.auto = 'true';
        }
    });

    document.getElementById('slug').addEventListener('input', function() {
        if (this.value) {
            delete this.dataset.auto;
        }
    });
</script>
@endsection
