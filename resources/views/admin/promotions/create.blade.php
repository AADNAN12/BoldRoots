@extends('admin.layouts.master')

@section('title', 'Créer une promotion')

@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        .form-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

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

        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #0acf97;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .info-box i {
            color: #0acf97;
            margin-right: 8px;
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
                            <li class="breadcrumb-item"><a href="{{ route('admin.promotions.index') }}">Promotions</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Créer</li>
                        </ol>
                    </nav>
                </div>
                <h4 class="page-title">Créer une promotion</h4>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.promotions.store') }}" method="POST" id="promotionForm">
        @csrf

        <div class="row">
            <div class="col-lg-8">
                <!-- Informations générales -->
                <div class="card">
                    <div class="card-body">
                        <div class="form-section-title">
                            <i class="mdi mdi-information"></i> Informations générales
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nom de la promotion *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Type de promotion *</label>
                                <select class="form-select @error('type') is-invalid @enderror" name="type"
                                    id="promotion_type" required>
                                    <option value="">Sélectionner...</option>
                                    <option value="flash_deal" {{ old('type') == 'flash_deal' ? 'selected' : '' }}>Flash
                                        Deal
                                    </option>
                                    <option value="regular_sale" {{ old('type') == 'regular_sale' ? 'selected' : '' }}>
                                        Promotion
                                        régulière</option>
                                    <option value="buy_x_get_y" {{ old('type') == 'buy_x_get_y' ? 'selected' : '' }}>Achetez
                                        X,
                                        obtenez Y</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Portée *</label>
                                <select class="form-select @error('scope') is-invalid @enderror" name="scope"
                                    id="scope" required>
                                    <option value="">Sélectionner...</option>
                                    <option value="product" {{ old('scope') == 'product' ? 'selected' : '' }}>Produits
                                        spécifiques</option>
                                    <option value="collection" {{ old('scope') == 'collection' ? 'selected' : '' }}>
                                        Catégories
                                    </option>
                                    <option value="cart" {{ old('scope') == 'cart' ? 'selected' : '' }}>Panier complet
                                    </option>
                                </select>
                                @error('scope')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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
                                <select class="form-select @error('discount_type') is-invalid @enderror"
                                    name="discount_type" id="discount_type" required>
                                    <option value="percentage"
                                        {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>
                                        Pourcentage (%)</option>
                                    <option value="fixed_amount"
                                        {{ old('discount_type') == 'fixed_amount' ? 'selected' : '' }}>
                                        Montant fixe (MAD)</option>
                                </select>
                                @error('discount_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Valeur de la réduction *</label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('discount_value') is-invalid @enderror"
                                        name="discount_value" value="{{ old('discount_value') }}" step="0.01"
                                        min="0" required>
                                    <span class="input-group-text" id="discount_unit">%</span>
                                </div>
                                @error('discount_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Champs pour Buy X Get Y -->
                        <div id="buy_x_get_y_fields" class="conditional-field">
                            <div class="info-box">
                                <i class="mdi mdi-information"></i>
                                <strong>Promotion "Achetez X, obtenez Y"</strong><br>
                                Exemple: Achetez 2 produits, obtenez 1 gratuit
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Quantité à acheter *</label>
                                    <input type="number" class="form-control" name="buy_quantity"
                                        value="{{ old('buy_quantity', 1) }}" min="1">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Quantité gratuite *</label>
                                    <input type="number" class="form-control" name="get_quantity"
                                        value="{{ old('get_quantity', 1) }}" min="1">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Produits/Catégories -->
                <div class="card">
                    <div class="card-body">
                        <div class="form-section-title">
                            <i class="mdi mdi-tag-multiple"></i> Sélection des produits/catégories
                        </div>

                        <!-- Produits -->
                        <div id="products_field" class="conditional-field mb-3">
                            <label class="form-label">Produits concernés *</label>
                            <select class="form-control select2-multiple" name="product_ids[]" multiple="multiple"
                                data-placeholder="Sélectionner les produits...">
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ in_array($product->id, old('product_ids', [])) ? 'selected' : '' }}>
                                        {{ $product->name }} ({{ $product->sku }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Catégories -->
                        <div id="categories_field" class="conditional-field mb-3">
                            <label class="form-label">Catégories concernées *</label>
                            <select class="form-control select2-multiple" name="category_ids[]" multiple="multiple"
                                data-placeholder="Sélectionner les catégories...">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ in_array($category->id, old('category_ids', [])) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Message pour panier -->
                        <div id="cart_message" class="conditional-field">
                            <div class="info-box">
                                <i class="mdi mdi-information"></i>
                                Cette promotion s'appliquera sur le montant total du panier.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Période de validité -->
                <div class="card">
                    <div class="card-body">
                        <div class="form-section-title">
                            <i class="mdi mdi-calendar-range"></i> Période de validité
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Date de début *</label>
                            <input type="text" class="form-control @error('start_date') is-invalid @enderror"
                                name="start_date" id="start_date" value="{{ old('start_date') }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Date de fin *</label>
                            <input type="text" class="form-control @error('end_date') is-invalid @enderror"
                                name="end_date" id="end_date" value="{{ old('end_date') }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
                                value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Activer immédiatement</label>
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
                                    value="{{ old('min_cart_value') }}" step="0.01" min="0">
                                <span class="input-group-text">MAD</span>
                            </div>
                            <small class="text-muted">Laisser vide pour aucune restriction</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Utilisations maximales</label>
                            <input type="number" class="form-control" name="max_uses" value="{{ old('max_uses') }}"
                                min="1">
                            <small class="text-muted">Laisser vide pour illimité</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Utilisations max par client</label>
                            <input type="number" class="form-control" name="max_per_customer"
                                value="{{ old('max_per_customer') }}" min="1">
                            <small class="text-muted">Laisser vide pour illimité</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Arrêter si stock inférieur à</label>
                            <input type="number" class="form-control" name="stop_when_stock_below"
                                value="{{ old('stop_when_stock_below', 0) }}" min="0">
                            <small class="text-muted">0 = pas de restriction</small>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-check"></i> Créer la promotion
                            </button>
                            <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">
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

            // Initialiser Flatpickr
            flatpickr("#start_date", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                locale: "fr"
            });

            flatpickr("#end_date", {
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

            // Gérer le changement de type de promotion
            $('#promotion_type').on('change', function() {
                const type = $(this).val();

                if (type === 'buy_x_get_y') {
                    $('#buy_x_get_y_fields').addClass('show');
                } else {
                    $('#buy_x_get_y_fields').removeClass('show');
                }
            });

            // Gérer le changement de portée
            $('#scope').on('change', function() {
                const scope = $(this).val();

                // Cacher tous les champs conditionnels
                $('#products_field, #categories_field, #cart_message').removeClass('show');

                // Afficher le champ approprié
                if (scope === 'product') {
                    $('#products_field').addClass('show');
                } else if (scope === 'collection') {
                    $('#categories_field').addClass('show');
                } else if (scope === 'cart') {
                    $('#cart_message').addClass('show');
                }
            });

            // Déclencher les événements au chargement
            $('#promotion_type').trigger('change');
            $('#scope').trigger('change');
            $('#discount_type').trigger('change');

            // Validation du formulaire
            $('#promotionForm').on('submit', function(e) {
                const type = $('#promotion_type').val();
                const scope = $('#scope').val();

                // Validation pour buy_x_get_y
                if (type === 'buy_x_get_y') {
                    const buyQty = $('input[name="buy_quantity"]').val();
                    const getQty = $('input[name="get_quantity"]').val();

                    if (!buyQty || !getQty) {
                        e.preventDefault();
                        alert('Veuillez renseigner les quantités pour la promotion "Achetez X, obtenez Y"');
                        return false;
                    }
                }

                // Validation pour la portée produit
                if (scope === 'product') {
                    const products = $('select[name="product_ids[]"]').val();
                    if (!products || products.length === 0) {
                        e.preventDefault();
                        alert('Veuillez sélectionner au moins un produit');
                        return false;
                    }
                }

                // Validation pour la portée catégorie
                if (scope === 'collection') {
                    const categories = $('select[name="category_ids[]"]').val();
                    if (!categories || categories.length === 0) {
                        e.preventDefault();
                        alert('Veuillez sélectionner au moins une catégorie');
                        return false;
                    }
                }
            });
        });
    </script>
@endsection
