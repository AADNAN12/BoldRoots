@extends('admin.layouts.master')

@section('title', 'Création en masse de coupons')

@section('head')
    <link href="{{ asset('assets/css/vendor/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .form-section-title {
            font-size: 16px;
            font-weight: 600;
            color: #313a46;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #727cf5;
        }

        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #0acf97;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffbc00;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .preview-box {
            background: #f8f9fa;
            border: 2px dashed #727cf5;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .coupon-preview {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            font-size: 18px;
            background: #f0f1ff;
            padding: 15px;
            border-radius: 8px;
            color: #727cf5;
            margin: 10px 0;
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
                            <li class="breadcrumb-item active" aria-current="page">Création en masse</li>
                        </ol>
                    </nav>
                </div>
                <h4 class="page-title">Création en masse de coupons</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="info-box">
                <i class="mdi mdi-information text-info me-2"></i>
                <strong>Création en masse de coupons</strong><br>
                Cette fonctionnalité vous permet de générer plusieurs coupons identiques en une seule fois. Chaque coupon aura un code unique généré automatiquement.
            </div>

            <form action="{{ route('admin.coupons.bulk-store') }}" method="POST" id="bulkCouponForm">
                @csrf

                <!-- Configuration des codes -->
                <div class="card">
                    <div class="card-body">
                        <div class="form-section-title">
                            <i class="mdi mdi-ticket-percent"></i> Configuration des codes
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nombre de coupons *</label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                    name="quantity" id="quantity" value="{{ old('quantity', 10) }}" min="1" max="1000" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Maximum: 1000 coupons</small>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Préfixe (optionnel)</label>
                                <input type="text" class="form-control" name="prefix" id="prefix" 
                                    value="{{ old('prefix') }}" maxlength="10" style="text-transform: uppercase;">
                                <small class="text-muted">Ex: PROMO, WELCOME</small>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Longueur du code *</label>
                                <input type="number" class="form-control" name="code_length" id="code_length" 
                                    value="{{ old('code_length', 8) }}" min="4" max="20" required>
                                <small class="text-muted">Caractères aléatoires</small>
                            </div>
                        </div>

                        <div class="preview-box">
                            <h5>Aperçu des codes générés</h5>
                            <div id="code_examples">
                                <div class="coupon-preview">XXXXXXXX</div>
                                <div class="coupon-preview">XXXXXXXX</div>
                                <div class="coupon-preview">XXXXXXXX</div>
                            </div>
                            <small class="text-muted">Les codes réels seront générés de manière aléatoire</small>
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
                                    <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>
                                        Pourcentage (%)</option>
                                    <option value="fixed_amount" {{ old('discount_type') == 'fixed_amount' ? 'selected' : '' }}>
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
                                        name="discount_value" value="{{ old('discount_value') }}" step="0.01" min="0" required>
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
                                    value="{{ old('max_discount_amount') }}" step="0.01" min="0">
                                <span class="input-group-text">MAD</span>
                            </div>
                            <small class="text-muted">Plafond de réduction. Laisser vide pour illimité</small>
                        </div>
                    </div>
                </div>

                <!-- Période et restrictions -->
                <div class="card">
                    <div class="card-body">
                        <div class="form-section-title">
                            <i class="mdi mdi-calendar-clock"></i> Période et restrictions
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date de début *</label>
                                <input type="text" class="form-control @error('start_date') is-invalid @enderror"
                                    name="start_date" id="start_date" value="{{ old('start_date') }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date de fin</label>
                                <input type="text" class="form-control @error('end_date') is-invalid @enderror" 
                                    name="end_date" id="end_date" value="{{ old('end_date') }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Laisser vide pour illimité</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Montant minimum du panier</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="minimum_purchase_amount"
                                        value="{{ old('minimum_purchase_amount') }}" step="0.01" min="0">
                                    <span class="input-group-text">MAD</span>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Utilisations max par coupon</label>
                                <input type="number" class="form-control" name="max_uses" 
                                    value="{{ old('max_uses') }}" min="1">
                                <small class="text-muted">Laisser vide pour illimité</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Utilisations max par utilisateur</label>
                            <input type="number" class="form-control" name="max_uses_per_user"
                                value="{{ old('max_uses_per_user') }}" min="1">
                            <small class="text-muted">Laisser vide pour illimité</small>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="mdi mdi-check-all"></i> Créer <span id="quantity_display">10</span> coupons
                            </button>
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-close"></i> Annuler
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <!-- Informations -->
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="mdi mdi-information-outline text-info"></i> Informations
                    </h5>
                    <p><strong>Utilisation recommandée :</strong></p>
                    <ul class="mb-0">
                        <li>Campagnes marketing</li>
                        <li>Programmes de fidélité</li>
                        <li>Événements spéciaux</li>
                        <li>Partenariats</li>
                    </ul>
                </div>
            </div>

            <!-- Conseils -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="mdi mdi-lightbulb-on text-warning"></i> Conseils
                    </h5>
                    <ul class="mb-0">
                        <li class="mb-2">
                            <strong>Préfixe :</strong> Utilisez un préfixe court et mémorable (ex: PROMO, WELCOME)
                        </li>
                        <li class="mb-2">
                            <strong>Longueur :</strong> 6-8 caractères pour un bon équilibre sécurité/lisibilité
                        </li>
                        <li class="mb-2">
                            <strong>Quantité :</strong> Créez suffisamment de coupons pour votre campagne
                        </li>
                        <li>
                            <strong>Restrictions :</strong> Définissez des limites pour contrôler l'utilisation
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Avertissement -->
            <div class="warning-box">
                <i class="mdi mdi-alert text-warning me-2"></i>
                <strong>Attention :</strong><br>
                Les coupons créés en masse auront tous les mêmes paramètres de réduction et restrictions. Seuls les codes seront différents.
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/vendor/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/flatpickr.fr.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialiser Flatpickr
            flatpickr("#start_date", {
                enableTime: false,
                dateFormat: "Y-m-d",
                locale: "fr"
            });

            flatpickr("#end_date", {
                enableTime: false,
                dateFormat: "Y-m-d",
                locale: "fr"
            });

            // Gérer le changement de type de réduction
            $('#discount_type').on('change', function() {
                const unit = $(this).val() === 'percentage' ? '%' : 'MAD';
                $('#discount_unit').text(unit);
            });

            // Mettre à jour l'affichage de la quantité
            $('#quantity').on('input', function() {
                const quantity = $(this).val() || 10;
                $('#quantity_display').text(quantity);
            });

            // Mettre à jour l'aperçu des codes
            $('#prefix, #code_length').on('input', function() {
                updateCodePreview();
            });

            function updateCodePreview() {
                const prefix = $('#prefix').val().toUpperCase();
                const length = parseInt($('#code_length').val()) || 8;
                const randomPart = 'X'.repeat(length);
                
                $('#code_examples').html('');
                for (let i = 0; i < 3; i++) {
                    const code = prefix ? `${prefix}${randomPart}` : randomPart;
                    $('#code_examples').append(`<div class="coupon-preview">${code}</div>`);
                }
            }

            // Déclencher les événements au chargement
            $('#discount_type').trigger('change');
            $('#quantity').trigger('input');
            updateCodePreview();

            // Validation du formulaire
            $('#bulkCouponForm').on('submit', function(e) {
                const quantity = parseInt($('#quantity').val());
                
                if (quantity > 1000) {
                    e.preventDefault();
                    alert('Le nombre maximum de coupons est de 1000');
                    return false;
                }

                if (quantity < 1) {
                    e.preventDefault();
                    alert('Veuillez entrer un nombre de coupons valide');
                    return false;
                }

                // Confirmation
                if (!confirm(`Vous allez créer ${quantity} coupons. Continuer ?`)) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
@endsection
