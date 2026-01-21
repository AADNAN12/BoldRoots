@extends('admin.layouts.master')

@section('title', 'Modifier Méthode de Livraison')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.shipping-methods.index') }}" class="btn btn-outline-secondary mb-2">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <h1 class="h3 mb-0">Modifier Méthode de Livraison</h1>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.shipping-methods.update', $shippingMethod) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nom de la méthode <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $shippingMethod->name) }}" 
                                   placeholder="Ex: Livraison Standard, Express, Gratuite..." required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="cost" class="form-label">Coût (DH) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" 
                                   class="form-control @error('cost') is-invalid @enderror" 
                                   id="cost" name="cost" value="{{ old('cost', $shippingMethod->cost) }}" required>
                            @error('cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Mettre 0 pour une livraison gratuite</small>
                        </div>

                        <div class="mb-3">
                            <label for="estimated_days" class="form-label">Délai estimé</label>
                            <input type="text" class="form-control @error('estimated_days') is-invalid @enderror" 
                                   id="estimated_days" name="estimated_days" value="{{ old('estimated_days', $shippingMethod->estimated_days) }}" 
                                   placeholder="Ex: 2-3 jours, 24h, 1 semaine...">
                            @error('estimated_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', $shippingMethod->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Méthode active
                                </label>
                            </div>
                            <small class="text-muted">Les méthodes inactives ne seront pas proposées aux clients</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.shipping-methods.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-info-circle"></i> Informations</h5>
                    <dl class="row mb-0">
                        <dt class="col-sm-6">Créée le:</dt>
                        <dd class="col-sm-6">{{ $shippingMethod->created_at->format('d/m/Y') }}</dd>

                        <dt class="col-sm-6">Modifiée le:</dt>
                        <dd class="col-sm-6">{{ $shippingMethod->updated_at->format('d/m/Y') }}</dd>

                        <dt class="col-sm-6">Statut:</dt>
                        <dd class="col-sm-6">
                            <span class="badge bg-{{ $shippingMethod->is_active ? 'success' : 'secondary' }}">
                                {{ $shippingMethod->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
