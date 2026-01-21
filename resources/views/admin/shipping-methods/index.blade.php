@extends('admin.layouts.master')

@section('title', 'Méthodes de Livraison')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Méthodes de Livraison</h1>
        <a href="{{ route('admin.shipping-methods.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouvelle Méthode
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Coût</th>
                            <th>Délai Estimé</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shippingMethods as $method)
                            <tr>
                                <td><strong>{{ $method->name }}</strong></td>
                                <td>{{ number_format($method->cost, 2) }} DH</td>
                                <td>{{ $method->estimated_days ?? '-' }}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               {{ $method->is_active ? 'checked' : '' }}
                                               onchange="toggleStatus({{ $method->id }})"
                                               id="status_{{ $method->id }}">
                                        <label class="form-check-label" for="status_{{ $method->id }}">
                                            {{ $method->is_active ? 'Active' : 'Inactive' }}
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.shipping-methods.edit', $method) }}" class="btn btn-sm btn-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.shipping-methods.destroy', $method) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette méthode ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucune méthode de livraison configurée</p>
                                    <a href="{{ route('admin.shipping-methods.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Créer la première méthode
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleStatus(id) {
    fetch(`/admin/shipping-methods/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const label = document.querySelector(`label[for="status_${id}"]`);
            label.textContent = data.is_active ? 'Active' : 'Inactive';
        } else {
            alert('Erreur: ' + data.message);
            location.reload();
        }
    })
    .catch(error => {
        alert('Erreur lors de la mise à jour');
        location.reload();
    });
}
</script>
@endpush
