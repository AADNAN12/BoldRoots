@extends('admin.layouts.master')

@section('title', 'Gestion des Newsletters')

@section('content')
<div class="container-fluid">


    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-2 mt-2">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Inscrits</h6>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="mdi mdi-email-multiple text-primary" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Actifs</h6>
                            <h3 class="mb-0">{{ $stats['active'] }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="mdi mdi-email-check text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Inactifs</h6>
                            <h3 class="mb-0">{{ $stats['inactive'] }}</h3>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                            <i class="mdi mdi-email-off text-danger" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
                <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Gestion des Newsletters</h2>
                <a href="{{ route('admin.newsletters.export') }}" class="btn btn-success">
                    <i class="mdi mdi-download me-1"></i> Exporter (CSV)
                </a>
            </div>
        </div>
    </div>
            <form method="GET" action="{{ route('admin.newsletters.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher par email..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Actif</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="mdi mdi-magnify me-1"></i> Rechercher
                        </button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Date d'inscription</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($newsletters as $newsletter)
                            <tr>
                                <td>{{ $newsletter->id }}</td>
                                <td>
                                    <i class="mdi mdi-email me-1"></i>
                                    {{ $newsletter->email }}
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="mdi mdi-calendar me-1"></i>
                                        {{ $newsletter->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </td>
                                <td>
                                    @if($newsletter->is_active)
                                        <span class="badge bg-success">
                                            <i class="mdi mdi-check-circle me-1"></i>Actif
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="mdi mdi-close-circle me-1"></i>Inactif
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <form action="{{ route('admin.newsletters.toggle', $newsletter->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-{{ $newsletter->is_active ? 'warning' : 'success' }}" 
                                                title="{{ $newsletter->is_active ? 'Désactiver' : 'Activer' }}">
                                            <i class="mdi mdi-{{ $newsletter->is_active ? 'close' : 'check' }}"></i>
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.newsletters.destroy', $newsletter->id) }}" method="POST" class="d-inline" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet email ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="mdi mdi-email-alert text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted mt-2">Aucun email trouvé</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $newsletters->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
