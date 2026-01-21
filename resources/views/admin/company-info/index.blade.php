@extends('admin.layouts.master')

@section('title', 'Informations de l\'Entreprise')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">Informations de l'Entreprise</h2>
        </div>
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

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.company-info.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <h5 class="mb-3">Informations Générales</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="company_name" class="form-label">Nom Commercial <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                       id="company_name" name="company_name" value="{{ old('company_name', $companyInfo->company_name) }}" required>
                                @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="legal_name" class="form-label">Raison Sociale</label>
                                <input type="text" class="form-control @error('legal_name') is-invalid @enderror" 
                                       id="legal_name" name="legal_name" value="{{ old('legal_name', $companyInfo->legal_name) }}">
                                @error('legal_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <h5 class="mb-3 mt-4">Adresse</h5>

                        <div class="mb-3">
                            <label for="address_line1" class="form-label">Adresse Ligne 1 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('address_line1') is-invalid @enderror" 
                                   id="address_line1" name="address_line1" value="{{ old('address_line1', $companyInfo->address_line1) }}" required>
                            @error('address_line1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address_line2" class="form-label">Adresse Ligne 2</label>
                            <input type="text" class="form-control @error('address_line2') is-invalid @enderror" 
                                   id="address_line2" name="address_line2" value="{{ old('address_line2', $companyInfo->address_line2) }}">
                            @error('address_line2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label">Ville <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city', $companyInfo->city) }}" required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="postal_code" class="form-label">Code Postal <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                       id="postal_code" name="postal_code" value="{{ old('postal_code', $companyInfo->postal_code) }}" required>
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="country" class="form-label">Pays <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                       id="country" name="country" value="{{ old('country', $companyInfo->country ?? 'Maroc') }}" required>
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <h5 class="mb-3 mt-4">Contact</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Téléphone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $companyInfo->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $companyInfo->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="website" class="form-label">Site Web</label>
                            <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                   id="website" name="website" value="{{ old('website', $companyInfo->website) }}" placeholder="https://www.example.com">
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <h5 class="mb-3 mt-4">Informations Légales</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tax_number" class="form-label">N° TVA / ICE</label>
                                <input type="text" class="form-control @error('tax_number') is-invalid @enderror" 
                                       id="tax_number" name="tax_number" value="{{ old('tax_number', $companyInfo->tax_number) }}">
                                @error('tax_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="registration_number" class="form-label">N° Registre Commerce</label>
                                <input type="text" class="form-control @error('registration_number') is-invalid @enderror" 
                                       id="registration_number" name="registration_number" value="{{ old('registration_number', $companyInfo->registration_number) }}">
                                @error('registration_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <h5 class="mb-3 mt-4">Informations Bancaires</h5>

                        <div class="mb-3">
                            <label for="bank_name" class="form-label">Nom de la Banque</label>
                            <input type="text" class="form-control @error('bank_name') is-invalid @enderror" 
                                   id="bank_name" name="bank_name" value="{{ old('bank_name', $companyInfo->bank_name) }}">
                            @error('bank_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bank_account" class="form-label">N° Compte Bancaire</label>
                                <input type="text" class="form-control @error('bank_account') is-invalid @enderror" 
                                       id="bank_account" name="bank_account" value="{{ old('bank_account', $companyInfo->bank_account) }}">
                                @error('bank_account')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="iban" class="form-label">IBAN</label>
                                <input type="text" class="form-control @error('iban') is-invalid @enderror" 
                                       id="iban" name="iban" value="{{ old('iban', $companyInfo->iban) }}">
                                @error('iban')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save me-1"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Logo de l'Entreprise</h5>
                    
                    @if($companyInfo->logo_path)
                        <div class="text-center mb-3">
                            <img src="{{ asset('storage/' . $companyInfo->logo_path) }}" alt="Logo" class="img-fluid rounded" style="max-height: 200px;">
                        </div>
                    @else
                        <div class="text-center mb-3 p-4 bg-light rounded">
                            <i class="mdi mdi-image-off" style="font-size: 4rem; color: #ccc;"></i>
                            <p class="text-muted mt-2">Aucun logo</p>
                        </div>
                    @endif

                    <form action="{{ route('admin.company-info.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        @foreach(['company_name', 'legal_name', 'address_line1', 'address_line2', 'city', 'postal_code', 'country', 'phone', 'email', 'website', 'tax_number', 'registration_number', 'bank_name', 'bank_account', 'iban'] as $field)
                            <input type="hidden" name="{{ $field }}" value="{{ $companyInfo->$field }}">
                        @endforeach

                        <div class="mb-3">
                            <label for="logo" class="form-label">Changer le Logo</label>
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                   id="logo" name="logo" accept="image/*">
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Format: JPG, PNG, GIF (Max: 2MB)</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="mdi mdi-upload me-1"></i> Télécharger
                        </button>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">Informations</h5>
                    <p class="small text-muted mb-0">
                        <i class="mdi mdi-information-outline me-1"></i>
                        Ces informations seront utilisées dans les factures, bons de livraison et autres documents officiels.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
