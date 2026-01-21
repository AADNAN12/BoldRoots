@extends('admin.layouts.master')

@section('title', 'Pages CMS')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Pages CMS</h2>
            <a href="{{ route('admin.cms-pages.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus me-1"></i> Nouvelle Page
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($pages->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Slug</th>
                                <th>Statut</th>
                                <th>Ordre</th>
                                <th>Date de création</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pages as $page)
                                <tr>
                                    <td>
                                        <strong>{{ $page->title }}</strong>
                                    </td>
                                    <td>
                                        <code>{{ $page->slug }}</code>
                                    </td>
                                    <td>
                                        @if($page->is_active)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td>{{ $page->order }}</td>
                                    <td>{{ $page->created_at->format('d/m/Y') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('cms.show', $page->slug) }}" class="btn btn-sm btn-info" target="_blank" title="Voir">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.cms-pages.edit', $page->id) }}" class="btn btn-sm btn-primary" title="Modifier">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.cms-pages.destroy', $page->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette page ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="mdi mdi-file-document-outline" style="font-size: 4rem; color: #ccc;"></i>
                    <p class="text-muted mt-3">Aucune page CMS créée</p>
                    <a href="{{ route('admin.cms-pages.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus me-1"></i> Créer votre première page
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
