@extends('admin.layouts.master')

@section('title', 'Modifier le coupon')

@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .form-section-title {
            font-size: 16px;
            font-weight: 600;
            color: #313a46;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #727cf5;
        }

        .conditional-field {
            display: none;
        }

        .conditional-field.show {
            display: block;
        }

        .coupon-code-display {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            font-size: 24px;
            background: #f0f1ff;
            padding: 20px;
            border-radius: 8px;
            color: #727cf5;
            text-align: center;
            margin: 15px 0;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-light-lighten p-2">
                            <li class="breadcrumb-item"><a href="#"><i class="uil-home-alt"></i> Accueil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.coupons.index') }}">Coupons</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Modifier</li>
                        </ol>
                    </nav>
                </div>
                <h4 class="page-title">Modifier le coupon</h4>
            </div>
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

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif


    <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST" id="couponForm">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-8">
                <!-- Code du coupon -->
                <div class="card">
                    <div class="card-body">
                        <div class="form-section-title">
                            <i class="mdi mdi-ticket-percent"></i> Code du coupon
                        </div>

                        <div class="coupon-code-display">
                            {{ $coupon->code }}
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Modifier le code *</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" name="code"
                                value="{{ old('code', $coupon->code) }}" style="text-transform: uppercase;" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Le code sera automatiquement converti en majuscules</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description', $coupon->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Configuration de la réduction -->
                <div class="card">
                    <div class="card-body">
                        <div class="form-section-title">
                            <i class="mdi mdi-percent"></i> Configuration de la réduction
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Type de réduction *</label>
                                <select class="form-select @error('type') is-invalid @enderror" name="type"
                                    id="discount_type" required>
                                    <option value="percentage" {{ old('type', $coupon->type) == 'percentage' ? 'selected' : '' }}>
                                        Pourcentage (%)</option>
                                    <option value="fixed_amount" {{ old('type', $coupon->type) == 'fixed_amount' ? 'selected' : '' }}>
                                        Montant fixe (MAD)</option>
                                    <option value="free_shipping" {{ old('type', $coupon->type) == 'free_shipping' ? 'selected' : '' }}>
                                        Livraison gratuite</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Valeur de la réduction *</label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('discount_value') is-invalid @enderror"
                                        name="discount_value" value="{{ old('discount_value', $coupon->discount_value) }}" step="0.01" min="0" required>
                                    <span class="input-group-text" id="discount_unit">%</span>
                                </div>
                                @error('discount_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Réduction maximale (pour pourcentage)</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="max_discount_amount"
                                    value="{{ old('max_discount_amount', $coupon->max_discount_amount) }}" step="0.01" min="0">
                                <span class="input-group-text">MAD</span>
                            </div>
                            <small class="text-muted">Plafond de réduction pour les pourcentages. Laisser vide pour illimité</small>
                        </div>
                    </div>
                </div>

                <!-- Utilisateurs spécifiques -->
                <div class="card">
                    <div class="card-body">
                        <div class="form-section-title">
                            <i class="mdi mdi-account-multiple"></i> Utilisateurs
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" class="form-check-input" id="user_specific" name="user_specific" value="1"
                                {{ old('user_specific', $coupon->user_specific) ? 'checked' : '' }}>
                            <label class="form-check-label" for="user_specific">Réserver à des utilisateurs spécifiques</label>
                        </div>

                        <div id="users_field" class="conditional-field">
                            <label class="form-label">Utilisateurs autorisés *</label>
                            <select class="form-control select2-multiple" name="user_ids[]" multiple="multiple"
                                data-placeholder="Sélectionner les utilisateurs...">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ in_array($user->id, old('user_ids', $coupon->users->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Seuls ces utilisateurs pourront utiliser ce coupon</small>
                        </div>
                    </div>
                </div>

                <!-- Produits et Catégories -->
                <div class="card">
                    <div class="card-body">
                        <div class="form-section-title">
                            <i class="mdi mdi-tag-multiple"></i> Restrictions produits/catégories
                        </div>

                        <div class="info-box">
                            <i class="mdi mdi-information"></i>
                            Si vous ne sélectionnez aucun produit ou catégorie, le coupon s'appliquera à tous les produits.
                        </div>

                        <!-- Produits -->
                        <div class="mb-3">
                            <label class="form-label">Produits spécifiques (optionnel)</label>
                            <select class="form-control select2-multiple" name="product_ids[]" multiple="multiple"
                                data-placeholder="Sélectionner les produits...">
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ in_array($product->id, old('product_ids', $coupon->products->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $product->name }} ({{ $product->sku }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Le coupon ne s'appliquera qu'à ces produits</small>
                        </div>

                        <!-- Catégories -->
                        <div class="mb-3">
                            <label class="form-label">Catégories spécifiques (optionnel)</label>
                            <select class="form-control select2-multiple" name="category_ids[]" multiple="multiple"
                                data-placeholder="Sélectionner les catégories...">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ in_array($category->id, old('category_ids', $coupon->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Le coupon ne s'appliquera qu'aux produits de ces catégories</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Statistiques -->
                <div class="card bg-light">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Statistiques</h5>
                        <div class="mb-2">
                            <strong>Utilisations:</strong> {{ $coupon->usage_count ?? 0 }}
                            @if($coupon->max_uses)
                                / {{ $coupon->max_uses }}
                            @endif
                        </div>
                        <div class="mb-2">
                            <strong>Créé le:</strong> {{ $coupon->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div>
                            <strong>Modifié le:</strong> {{ $coupon->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>

                <!-- Période de validité -->
                <div class="card">
                    <div class="card-body">
                        <div class="form-section-title">
                            <i class="mdi mdi-calendar-range"></i> Période de validité
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Date de début *</label>
                            <input type="text" class="form-control @error('valid_from') is-invalid @enderror"
                                name="valid_from" id="valid_from" value="{{ old('valid_from', $coupon->valid_from ? $coupon->valid_from->format('Y-m-d H:i') : '') }}" required>
                            @error('valid_from')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Date de fin</label>
                            <input type="text" class="form-control @error('valid_until') is-invalid @enderror" name="valid_until"
                                id="valid_until" value="{{ old('valid_until', $coupon->valid_until ? $coupon->valid_until->format('Y-m-d H:i') : '') }}">
                            @error('valid_until')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Laisser vide pour aucune date de fin</small>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                                {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Coupon actif</label>
                        </div>
                    </div>
                </div>

                <!-- Restrictions -->
                <div class="card">
                    <div class="card-body">
                        <div class="form-section-title">
                            <i class="mdi mdi-shield-check"></i> Restrictions
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Montant minimum du panier</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="min_cart_value"
                                    value="{{ old('min_cart_value', $coupon->min_cart_value) }}" step="0.01" min="0">
                                <span class="input-group-text">MAD</span>
                            </div>
                            <small class="text-muted">Laisser vide pour aucune restriction</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Utilisations maximales</label>
                            <input type="number" class="form-control" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" min="1">
                            <small class="text-muted">Nombre total d'utilisations. Laisser vide pour illimité</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Utilisations max par utilisateur</label>
                            <input type="number" class="form-control" name="usage_per_customer"
                                value="{{ old('usage_per_customer', $coupon->usage_per_customer) }}" min="1">
                            <small class="text-muted">Nombre d'utilisations par client</small>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" class="form-check-input" id="exclude_new_products" name="exclude_new_products" value="1"
                                {{ old('exclude_new_products', $coupon->exclude_new_products) ? 'checked' : '' }}>
                            <label class="form-check-label" for="exclude_new_products">Exclure les nouveaux produits</label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-check"></i> Mettre à jour
                            </button>
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-close"></i> Annuler
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        $(document).ready(function() {
            // Initialiser Select2
            $('.select2-multiple').select2({
                width: '100%'
            });

            // Initialiser Flatpickr avec heure comme les promotions
            flatpickr("#valid_from", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                locale: "fr"
            });

            flatpickr("#valid_until", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                locale: "fr"
            });

            // Gérer le changement de type de réduction
            $('#discount_type').on('change', function() {
                const unit = $(this).val() === 'percentage' ? '%' : 'MAD';
                $('#discount_unit').text(unit);
            });

            // Gérer les utilisateurs spécifiques
            $('#user_specific').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#users_field').addClass('show');
                } else {
                    $('#users_field').removeClass('show');
                }
            });

            // Déclencher les événements au chargement
            $('#user_specific').trigger('change');
            $('#discount_type').trigger('change');

            // Validation du formulaire
            $('#couponForm').on('submit', function(e) {
                const userSpecific = $('#user_specific').is(':checked');

                if (userSpecific) {
                    const users = $('select[name="user_ids[]"]').val();
                    if (!users || users.length === 0) {
                        e.preventDefault();
                        alert('Veuillez sélectionner au moins un utilisateur pour un coupon spécifique');
                        return false;
                    }
                }
            });
        });
    </script>
@endsection
