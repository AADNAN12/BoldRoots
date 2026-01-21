@extends('admin.layouts.master')

@section('title', 'Modifier le produit')

@section('head')
<link href="{{ asset('assets/css/vendor/quill.core.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/vendor/quill.snow.css') }}" rel="stylesheet" type="text/css" />
<style>
    .progress-bar-container {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        position: relative;
    }
    
    .progress-bar-container::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 2px;
        background: #e3e3e3;
        z-index: 0;
    }
    
    .progress-step {
        flex: 1;
        text-align: center;
        position: relative;
        z-index: 1;
    }
    
    .progress-step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e3e3e3;
        color: #666;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-bottom: 10px;
        transition: all 0.3s;
    }
    
    .progress-step.active .progress-step-circle {
        background: #727cf5;
        color: white;
    }
    
    .progress-step.completed .progress-step-circle {
        background: #0acf97;
        color: white;
    }
    
    .progress-step-label {
        font-size: 12px;
        color: #666;
    }
    
    .progress-step.active .progress-step-label {
        color: #727cf5;
        font-weight: bold;
    }
    
    .form-step {
        display: none;
    }
    
    .form-step.active {
        display: block;
    }
    
    .variant-row {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 10px;
    }
    
    .variant-row.stock-zero {
        border-left: 4px solid #fa5c7c;
    }
    
    .variant-row.stock-low {
        border-left: 4px solid #ffbc00;
    }
    
    .variant-row.stock-ok {
        border-left: 4px solid #0acf97;
    }
    
    .image-preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }
    
    .image-preview-item {
        position: relative;
        width: 120px;
        height: 120px;
        border: 2px solid #e3e3e3;
        border-radius: 5px;
        overflow: hidden;
    }
    
    .image-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .image-preview-item .remove-image {
        position: absolute;
        top: 5px;
        right: 5px;
        background: #fa5c7c;
        color: white;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        cursor: pointer;
    }
    
    .image-preview-item .homepage-image-btn {
        position: absolute;
        top: 5px;
        left: 5px;
        background: #e3e3e3;
        color: #666;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .image-preview-item .homepage-image-btn.active {
        background: #ffbc00;
        color: white;
    }
    
    .image-preview-item .homepage-image-btn:hover {
        background: #ffbc00;
        color: white;
    }
    
    .image-preview-item .primary-badge {
        position: absolute;
        bottom: 5px;
        left: 5px;
        background: #0acf97;
        color: white;
        padding: 2px 8px;
        border-radius: 3px;
        font-size: 10px;
    }
    
    .image-preview-item .homepage-badge {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: #ffbc00;
        color: white;
        padding: 2px 8px;
        border-radius: 3px;
        font-size: 10px;
    }
    
    .color-checkbox {
        display: inline-block;
        margin-right: 15px;
        margin-bottom: 10px;
    }
    
    .color-checkbox input[type="checkbox"] {
        display: none;
    }
    
    .color-checkbox label {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 15px;
        border: 2px solid #e3e3e3;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .color-checkbox input[type="checkbox"]:checked + label {
        border-color: #727cf5;
        background: #f0f1ff;
    }
    
    .color-swatch {
        width: 25px;
        height: 25px;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 0 0 1px #e3e3e3;
    }
    
    .size-checkbox {
        display: inline-block;
        margin-right: 10px;
        margin-bottom: 10px;
    }
    
    .size-checkbox input[type="checkbox"] {
        display: none;
    }
    
    .size-checkbox label {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 50px;
        height: 50px;
        padding: 8px 15px;
        border: 2px solid #e3e3e3;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 600;
        font-size: 14px;
        color: #6c757d;
        background: #fff;
    }
    
    .size-checkbox input[type="checkbox"]:checked + label {
        border-color: #727cf5;
        background: #727cf5;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(114, 124, 245, 0.3);
    }
    
    .size-checkbox label:hover {
        border-color: #727cf5;
        transform: translateY(-1px);
    }
    
    .validation-error {
        color: #fa5c7c;
        font-size: 12px;
        margin-top: 5px;
        display: none;
    }
    
    .validation-error.show {
        display: block;
    }
    
    .auto-save-indicator {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #0acf97;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        display: none;
        z-index: 1000;
    }
    
    .auto-save-indicator.show {
        display: block;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Produits</a></li>
                    <li class="breadcrumb-item active">Modifier</li>
                </ol>
            </div>
            <h4 class="page-title">Modifier le produit</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Progress Bar -->
                <div class="progress-bar-container">
                    <div class="progress-step active" data-step="1">
                        <div class="progress-step-circle">1</div>
                        <div class="progress-step-label">Informations</div>
                    </div>
                    <div class="progress-step" data-step="2">
                        <div class="progress-step-circle">2</div>
                        <div class="progress-step-label">Variantes</div>
                    </div>
                    <div class="progress-step" data-step="3">
                        <div class="progress-step-circle">3</div>
                        <div class="progress-step-label">Images</div>
                    </div>
                    <div class="progress-step" data-step="4">
                        <div class="progress-step-circle">4</div>
                        <div class="progress-step-label">Aperçu</div>
                    </div>
                </div>

                <!-- Form -->
                <form id="productForm" action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Champ caché pour l'image homepage -->
                    <input type="hidden" name="homepage_image_info" id="homepage_image_info" value="">
                    
                    <!-- ÉTAPE 1: Informations générales -->
                    <div class="form-step active" data-step="1">
                        <h4 class="mb-3">Informations générales du produit</h4>
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Nom du produit *</label>
                                    <input type="text" class="form-control" name="name" id="product_name" value="{{ old('name', $product->name) }}" required>
                                    <div class="validation-error" id="error_name">Le nom est obligatoire</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">SKU (Code produit)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="sku" id="product_sku" value="{{ old('sku', $product->sku) }}">
                                        <button type="button" class="btn btn-secondary" id="generateSku">
                                            <i class="mdi mdi-refresh"></i> Générer
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Description *</label>
                                    <div id="product_description_editor" style="height: 200px;"></div>
                                    <textarea name="description" id="product_description" style="display:none;">{{ old('description', $product->description) }}</textarea>
                                    <div class="validation-error" id="error_description">La description doit contenir au moins 50 caractères</div>
                                    <small class="text-muted">Minimum 50 caractères - <span id="char_count">0</span> caractères</small>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Catégorie *</label>
                                    <select class="form-select" name="category_id" id="product_category" required>
                                        <option value="">Sélectionner une catégorie</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                            @if($category->children->count() > 0)
                                                @foreach($category->children as $child)
                                                    <option value="{{ $child->id }}" {{ old('category_id', $product->category_id) == $child->id ? 'selected' : '' }}>
                                                        -- {{ $child->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </select>
                                    <div class="validation-error" id="error_category">Veuillez sélectionner une catégorie</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Prix *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">MAD</span>
                                        <input type="number" class="form-control" name="price" id="product_price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
                                    </div>
                                    <div class="validation-error" id="error_price">Le prix est obligatoire</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Prix barré (optionnel)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">MAD</span>
                                        <input type="number" class="form-control" name="compare_price" id="product_compare_price" value="{{ old('compare_price', $product->compare_price) }}" step="0.01" min="0">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="is_new" id="product_is_new" value="1" {{ old('is_new', $product->is_new) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="product_is_new">Nouveau produit</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="is_featured" id="product_is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="product_is_featured">Produit en vedette</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="is_active" id="product_is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="product_is_active">Actif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Annuler
                            </a>
                            <button type="button" class="btn btn-primary" id="nextStep1">
                                Suivant <i class="mdi mdi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- ÉTAPE 2: Variantes & Stock -->
                    <div class="form-step" data-step="2">
                        <h4 class="mb-3">Variantes & Gestion du stock</h4>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Couleurs disponibles *</label>
                                <div id="colors_container">
                                    @foreach($colors as $color)
                                        @php
                                            $isSelected = $product->variants->pluck('color_id')->unique()->contains($color->id);
                                        @endphp
                                        <div class="color-checkbox">
                                            <input type="checkbox" id="color_{{ $color->id }}" value="{{ $color->id }}" data-color-name="{{ $color->value }}" {{ $isSelected ? 'checked' : '' }}>
                                            <label for="color_{{ $color->id }}">
                                                <span class="color-swatch" style="background-color: {{ $color->color_code }}"></span>
                                                <span>{{ $color->value }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="validation-error" id="error_colors">Veuillez sélectionner au moins une couleur</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Tailles disponibles *</label>
                                <div id="sizes_container">
                                    @foreach($sizes as $size)
                                        @php
                                            $isSelected = $product->variants->pluck('size_id')->unique()->contains($size->id);
                                        @endphp
                                        <div class="size-checkbox">
                                            <input type="checkbox" id="size_{{ $size->id }}" value="{{ $size->id }}" data-size-name="{{ $size->value }}" {{ $isSelected ? 'checked' : '' }}>
                                            <label for="size_{{ $size->id }}">{{ $size->value }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="validation-error" id="error_sizes">Veuillez sélectionner au moins une taille</div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <button type="button" class="btn btn-sm btn-secondary" id="generateVariants">
                                <i class="mdi mdi-auto-fix"></i> Générer les variantes
                            </button>
                            <button type="button" class="btn btn-sm btn-info" id="fillAllStock">
                                <i class="mdi mdi-content-copy"></i> Remplir tout
                            </button>
                            <button type="button" class="btn btn-sm btn-warning" id="generateAllSku">
                                <i class="mdi mdi-barcode"></i> Générer tous les SKU
                            </button>
                        </div>
                        
                        <div id="variants_container"></div>
                        
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-secondary" id="prevStep2">
                                <i class="mdi mdi-arrow-left"></i> Précédent
                            </button>
                            <button type="button" class="btn btn-primary" id="nextStep2">
                                Suivant <i class="mdi mdi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- ÉTAPE 3: Images par couleur -->
                    <div class="form-step" data-step="3">
                        <h4 class="mb-3">Images du produit par couleur</h4>
                        
                        <div id="images_container"></div>
                        
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-secondary" id="prevStep3">
                                <i class="mdi mdi-arrow-left"></i> Précédent
                            </button>
                            <button type="button" class="btn btn-primary" id="nextStep3">
                                Suivant <i class="mdi mdi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- ÉTAPE 4: Aperçu -->
                    <div class="form-step" data-step="4">
                        <h4 class="mb-3">Aperçu du produit</h4>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Informations générales</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm">
                                            <tr>
                                                <th width="40%">Nom:</th>
                                                <td id="preview_name">-</td>
                                            </tr>
                                            <tr>
                                                <th>SKU:</th>
                                                <td id="preview_sku">-</td>
                                            </tr>
                                            <tr>
                                                <th>Catégorie:</th>
                                                <td id="preview_category">-</td>
                                            </tr>
                                            <tr>
                                                <th>Prix:</th>
                                                <td id="preview_price">-</td>
                                            </tr>
                                            <tr>
                                                <th>Description:</th>
                                                <td id="preview_description">-</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Résumé des variantes</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="preview_variants_summary">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Galerie d'images</h5>
                            </div>
                            <div class="card-body">
                                <div id="preview_images_gallery" class="image-preview-container">
                                    <p class="text-muted">Aucune image uploadée</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-secondary" id="prevStep4">
                                <i class="mdi mdi-arrow-left"></i> Précédent
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="mdi mdi-check"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Auto-save indicator -->
<div class="auto-save-indicator" id="autoSaveIndicator">
    <i class="mdi mdi-check"></i> Sauvegarde automatique effectuée
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/vendor/quill.min.js') }}"></script>
<script>
// Variables globales
let currentStep = 1;
let quillEditor;
let selectedColors = [];
let selectedSizes = [];
let variantsData = [];
let imagesData = {};
let existingVariants = @json($product->variants);
let existingImages = @json($product->images);

// Initialisation
$(document).ready(function() {
    initQuillEditor();
    initEventListeners();
    loadExistingData();
});

// Initialiser l'éditeur Quill
function initQuillEditor() {
    quillEditor = new Quill('#product_description_editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['clean']
            ]
        }
    });
    
    // Charger la description existante
    const existingDesc = $('#product_description').val();
    if (existingDesc) {
        quillEditor.root.innerHTML = existingDesc;
    }
    
    quillEditor.on('text-change', function() {
        const text = quillEditor.getText();
        $('#product_description').val(quillEditor.root.innerHTML);
        $('#char_count').text(text.trim().length);
        validateDescription();
    });
    
    // Initialiser le compteur
    $('#char_count').text(quillEditor.getText().trim().length);
}

// Charger les données existantes
function loadExistingData() {
    // Charger les couleurs et tailles sélectionnées
    updateSelectedColors();
    updateSelectedSizes();
    
    // Générer les variantes existantes
    if (existingVariants.length > 0) {
        generateVariantsFromExisting();
    }
    
    // Charger les images existantes
    if (existingImages.length > 0) {
        loadExistingImages();
    }
}

// Générer les variantes à partir des données existantes
function generateVariantsFromExisting() {
    variantsData = [];
    let html = '<div class="table-responsive"><table class="table table-bordered"><thead><tr><th>Couleur</th><th>Taille</th><th>Quantité *</th><th>Seuil alerte</th><th>SKU</th></tr></thead><tbody>';
    
    existingVariants.forEach((variant, index) => {
        const colorName = variant.color ? variant.color.value : '-';
        const sizeName = variant.size ? variant.size.value : '-';
        
        let stockClass = 'stock-ok';
        if (variant.quantity == 0) {
            stockClass = 'stock-zero';
        } else if (variant.quantity < variant.low_stock_threshold) {
            stockClass = 'stock-low';
        }
        
        variantsData.push({
            id: variant.id,
            color_id: variant.color_id,
            size_id: variant.size_id,
            quantity: variant.quantity,
            low_stock_threshold: variant.low_stock_threshold,
            sku: variant.sku
        });
        
        html += `
            <tr class="variant-row ${stockClass}" data-index="${index}">
                <td>${colorName}</td>
                <td>${sizeName}</td>
                <td>
                    <input type="number" class="form-control form-control-sm variant-quantity" 
                           name="variants[${index}][quantity]" min="0" value="${variant.quantity}" data-index="${index}" required>
                    <input type="hidden" name="variants[${index}][id]" value="${variant.id}">
                    <input type="hidden" name="variants[${index}][color_id]" value="${variant.color_id}">
                    <input type="hidden" name="variants[${index}][size_id]" value="${variant.size_id}">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm" 
                           name="variants[${index}][low_stock_threshold]" min="0" value="${variant.low_stock_threshold}">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm variant-sku" 
                           name="variants[${index}][sku]" value="${variant.sku}" data-index="${index}">
                </td>
            </tr>
        `;
    });
    
    html += '</tbody></table></div>';
    $('#variants_container').html(html);
    
    // Événements pour les variantes
    $('.variant-quantity').on('input', function() {
        const index = $(this).data('index');
        const quantity = parseInt($(this).val()) || 0;
        const threshold = parseInt($(this).closest('tr').find('input[name*="low_stock_threshold"]').val()) || 5;
        
        variantsData[index].quantity = quantity;
        
        const row = $(this).closest('tr');
        row.removeClass('stock-zero stock-low stock-ok');
        
        if (quantity === 0) {
            row.addClass('stock-zero');
        } else if (quantity < threshold) {
            row.addClass('stock-low');
        } else {
            row.addClass('stock-ok');
        }
    });
    
    $('.variant-sku').on('input', function() {
        const index = $(this).data('index');
        variantsData[index].sku = $(this).val();
    });
    
    // Générer les sections d'images
    generateImageSections();
}

// Charger les images existantes
function loadExistingImages() {
    existingImages.forEach(image => {
        if (!imagesData[image.color_id]) {
            imagesData[image.color_id] = [];
        }
        
        imagesData[image.color_id].push({
            existing: true,
            id: image.id,
            preview: `/storage/${image.image_path}`,
            isPrimary: image.is_primary,
            isHomepage: image.is_homepage_image,
            sort_order: image.sort_order
        });
    });
    
    // Mettre à jour les aperçus
    Object.keys(imagesData).forEach(colorId => {
        updateImagePreview(colorId);
    });
}

// Initialiser les écouteurs d'événements
function initEventListeners() {
    // Navigation entre les étapes
    $('#nextStep1').click(() => validateAndGoToStep(2));
    $('#nextStep2').click(() => validateAndGoToStep(3));
    $('#nextStep3').click(() => validateAndGoToStep(4));
    $('#prevStep2').click(() => goToStep(1));
    $('#prevStep3').click(() => goToStep(2));
    $('#prevStep4').click(() => goToStep(3));
    
    // Génération SKU
    $('#generateSku').click(generateProductSku);
    
    // Génération des variantes
    $('#generateVariants').click(generateVariants);
    $('#fillAllStock').click(fillAllStock);
    $('#generateAllSku').click(generateAllVariantsSku);
    
    // Sélection couleurs/tailles
    $('#colors_container input[type="checkbox"]').change(updateSelectedColors);
    $('#sizes_container input[type="checkbox"]').change(updateSelectedSizes);
    
    // Validation en temps réel
    $('#product_name').on('input', validateName);
    $('#product_category').change(validateCategory);
    $('#product_price').on('input', validatePrice);
    
    // Soumission du formulaire
    $('#productForm').submit(function(e) {
        if (!validateAllSteps()) {
            e.preventDefault();
            return false;
        }
        
        // Stocker l'information de l'image homepage
        storeHomepageImageInfo();
        
        // Attacher les images au formulaire avant soumission
        attachImagesToForm();
    });
}

// Stocker l'information de l'image homepage
function storeHomepageImageInfo() {
    let homepageInfo = null;
    
    // Parcourir toutes les images pour trouver celle marquée comme homepage
    Object.keys(imagesData).forEach(colorId => {
        imagesData[colorId].forEach((img, index) => {
            if (img.isHomepage) {
                homepageInfo = {
                    color_id: colorId,
                    index: index,
                    existing: img.existing || false,
                    image_id: img.id || null
                };
            }
        });
    });
    
    // Stocker dans le champ caché
    $('#homepage_image_info').val(JSON.stringify(homepageInfo));
}

// Attacher les images au FormData
function attachImagesToForm() {
    // Supprimer les anciens champs d'images s'ils existent
    $('input[name^="images["]').remove();
    
    // Créer un DataTransfer pour chaque couleur
    Object.keys(imagesData).forEach(colorId => {
        const images = imagesData[colorId];
        
        images.forEach((img, index) => {
            // Ne traiter que les nouvelles images (pas les existantes)
            if (img.file) {
                // Créer un input file caché pour cette image
                const input = document.createElement('input');
                input.type = 'file';
                input.name = `images[${colorId}][]`;
                input.style.display = 'none';
                
                // Créer un DataTransfer pour ajouter le fichier
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(img.file);
                input.files = dataTransfer.files;
                
                // Ajouter au formulaire
                $('#productForm').append(input);
            }
        });
    });
}

// Navigation vers une étape
function goToStep(step) {
    $('.form-step').removeClass('active');
    $(`.form-step[data-step="${step}"]`).addClass('active');
    
    $('.progress-step').removeClass('active');
    $(`.progress-step[data-step="${step}"]`).addClass('active');
    
    for (let i = 1; i < step; i++) {
        $(`.progress-step[data-step="${i}"]`).addClass('completed');
    }
    
    currentStep = step;
    
    if (step === 4) {
        updatePreview();
    }
}

// Valider et aller à l'étape suivante
function validateAndGoToStep(step) {
    let isValid = false;
    
    switch(currentStep) {
        case 1:
            isValid = validateStep1();
            break;
        case 2:
            isValid = validateStep2();
            break;
        case 3:
            isValid = validateStep3();
            break;
    }
    
    if (isValid) {
        goToStep(step);
    }
}

// Validations
function validateStep1() {
    let isValid = true;
    
    if (!validateName()) isValid = false;
    if (!validateDescription()) isValid = false;
    if (!validateCategory()) isValid = false;
    if (!validatePrice()) isValid = false;
    
    return isValid;
}

function validateName() {
    const name = $('#product_name').val().trim();
    if (name.length === 0) {
        $('#error_name').addClass('show');
        return false;
    }
    $('#error_name').removeClass('show');
    return true;
}

function validateDescription() {
    const text = quillEditor.getText().trim();
    if (text.length < 50) {
        $('#error_description').addClass('show');
        return false;
    }
    $('#error_description').removeClass('show');
    return true;
}

function validateCategory() {
    const category = $('#product_category').val();
    if (!category) {
        $('#error_category').addClass('show');
        return false;
    }
    $('#error_category').removeClass('show');
    return true;
}

function validatePrice() {
    const price = parseFloat($('#product_price').val());
    if (isNaN(price) || price <= 0) {
        $('#error_price').addClass('show');
        return false;
    }
    $('#error_price').removeClass('show');
    return true;
}

function validateStep2() {
    if (selectedColors.length === 0) {
        $('#error_colors').addClass('show');
        return false;
    }
    $('#error_colors').removeClass('show');
    
    if (selectedSizes.length === 0) {
        $('#error_sizes').addClass('show');
        return false;
    }
    $('#error_sizes').removeClass('show');
    
    if (variantsData.length === 0) {
        alert('Veuillez générer les variantes avant de continuer');
        return false;
    }
    
    return true;
}

function validateStep3() {
    // Vérifier qu'il y a au moins une image par couleur
    for (let colorId of selectedColors) {
        if (!imagesData[colorId] || imagesData[colorId].length === 0) {
            alert('Veuillez ajouter au moins une image pour chaque couleur');
            return false;
        }
    }
    return true;
}

function validateAllSteps() {
    return validateStep1() && validateStep2() && validateStep3();
}

// Mise à jour des couleurs sélectionnées
function updateSelectedColors() {
    selectedColors = [];
    $('#colors_container input[type="checkbox"]:checked').each(function() {
        selectedColors.push($(this).val());
    });
}

// Mise à jour des tailles sélectionnées
function updateSelectedSizes() {
    selectedSizes = [];
    $('#sizes_container input[type="checkbox"]:checked').each(function() {
        selectedSizes.push($(this).val());
    });
}

// Générer le SKU produit
function generateProductSku() {
    const name = $('#product_name').val().trim();
    if (!name) {
        alert('Veuillez d\'abord entrer un nom de produit');
        return;
    }
    
    const prefix = name.replace(/[^A-Za-z0-9]/g, '').substring(0, 3).toUpperCase();
    const random = Math.random().toString(36).substring(2, 8).toUpperCase();
    const sku = prefix + '-' + random;
    
    $('#product_sku').val(sku);
}

// Générer les variantes
function generateVariants() {
    if (selectedColors.length === 0 || selectedSizes.length === 0) {
        alert('Veuillez sélectionner au moins une couleur et une taille');
        return;
    }
    
    variantsData = [];
    let html = '<div class="table-responsive"><table class="table table-bordered"><thead><tr><th>Couleur</th><th>Taille</th><th>Quantité *</th><th>Seuil alerte</th><th>SKU</th></tr></thead><tbody>';
    
    selectedColors.forEach(colorId => {
        const colorName = $(`#color_${colorId}`).data('color-name');
        
        selectedSizes.forEach(sizeId => {
            const sizeName = $(`#size_${sizeId}`).data('size-name');
            const index = variantsData.length;
            
            // Chercher si une variante existante correspond
            const existingVariant = existingVariants.find(v => v.color_id == colorId && v.size_id == sizeId);
            
            if (existingVariant) {
                variantsData.push({
                    id: existingVariant.id,
                    color_id: colorId,
                    size_id: sizeId,
                    quantity: existingVariant.quantity,
                    low_stock_threshold: existingVariant.low_stock_threshold,
                    sku: existingVariant.sku
                });
                
                let stockClass = 'stock-ok';
                if (existingVariant.quantity == 0) stockClass = 'stock-zero';
                else if (existingVariant.quantity < existingVariant.low_stock_threshold) stockClass = 'stock-low';
                
                html += `
                    <tr class="variant-row ${stockClass}" data-index="${index}">
                        <td>${colorName}</td>
                        <td>${sizeName}</td>
                        <td>
                            <input type="number" class="form-control form-control-sm variant-quantity" 
                                   name="variants[${index}][quantity]" min="0" value="${existingVariant.quantity}" data-index="${index}" required>
                            <input type="hidden" name="variants[${index}][id]" value="${existingVariant.id}">
                            <input type="hidden" name="variants[${index}][color_id]" value="${colorId}">
                            <input type="hidden" name="variants[${index}][size_id]" value="${sizeId}">
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm" 
                                   name="variants[${index}][low_stock_threshold]" min="0" value="${existingVariant.low_stock_threshold}">
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm variant-sku" 
                                   name="variants[${index}][sku]" value="${existingVariant.sku}" data-index="${index}">
                        </td>
                    </tr>
                `;
            } else {
                variantsData.push({
                    color_id: colorId,
                    size_id: sizeId,
                    quantity: 0,
                    low_stock_threshold: 5,
                    sku: ''
                });
                
                html += `
                    <tr class="variant-row stock-zero" data-index="${index}">
                        <td>${colorName}</td>
                        <td>${sizeName}</td>
                        <td>
                            <input type="number" class="form-control form-control-sm variant-quantity" 
                                   name="variants[${index}][quantity]" min="0" value="0" data-index="${index}" required>
                            <input type="hidden" name="variants[${index}][color_id]" value="${colorId}">
                            <input type="hidden" name="variants[${index}][size_id]" value="${sizeId}">
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm" 
                                   name="variants[${index}][low_stock_threshold]" min="0" value="5">
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm variant-sku" 
                                   name="variants[${index}][sku]" placeholder="Auto" data-index="${index}">
                        </td>
                    </tr>
                `;
            }
        });
    });
    
    html += '</tbody></table></div>';
    $('#variants_container').html(html);
    
    // Événements pour les variantes
    $('.variant-quantity').on('input', function() {
        const index = $(this).data('index');
        const quantity = parseInt($(this).val()) || 0;
        const threshold = parseInt($(this).closest('tr').find('input[name*="low_stock_threshold"]').val()) || 5;
        
        variantsData[index].quantity = quantity;
        
        const row = $(this).closest('tr');
        row.removeClass('stock-zero stock-low stock-ok');
        
        if (quantity === 0) {
            row.addClass('stock-zero');
        } else if (quantity < threshold) {
            row.addClass('stock-low');
        } else {
            row.addClass('stock-ok');
        }
    });
    
    $('.variant-sku').on('input', function() {
        const index = $(this).data('index');
        variantsData[index].sku = $(this).val();
    });
    
    // Générer les sections d'images
    generateImageSections();
}

// Remplir tout le stock
function fillAllStock() {
    const quantity = prompt('Quantité pour toutes les variantes:', '10');
    if (quantity !== null) {
        $('.variant-quantity').val(quantity).trigger('input');
    }
}

// Générer tous les SKU des variantes
function generateAllVariantsSku() {
    const productSku = $('#product_sku').val();
    if (!productSku) {
        alert('Veuillez d\'abord générer ou entrer un SKU produit');
        return;
    }
    
    $('.variant-sku').each(function() {
        const index = $(this).data('index');
        const variant = variantsData[index];
        const colorName = $(`#color_${variant.color_id}`).data('color-name');
        const sizeName = $(`#size_${variant.size_id}`).data('size-name');
        
        const colorCode = colorName.substring(0, 2).toUpperCase();
        const sizeCode = sizeName.substring(0, 2).toUpperCase();
        const sku = `${productSku}-${colorCode}-${sizeCode}`;
        
        $(this).val(sku);
        variantsData[index].sku = sku;
    });
}

// Générer les sections d'upload d'images
function generateImageSections() {
    let html = '';
    
    selectedColors.forEach(colorId => {
        const colorName = $(`#color_${colorId}`).data('color-name');
        const colorCode = $(`#color_${colorId}`).siblings('label').find('.color-swatch').css('background-color');
        
        html += `
            <div class="card mb-3">
                <div class="card-header" style="background-color: ${colorCode}20;">
                    <h5 class="mb-0">
                        <span class="color-swatch" style="background-color: ${colorCode}; display: inline-block; width: 20px; height: 20px; border-radius: 50%; margin-right: 10px;"></span>
                        Images pour ${colorName}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Sélectionner les images</label>
                        <input type="file" class="form-control image-upload" data-color-id="${colorId}" multiple accept="image/jpeg,image/jpg,image/png,image/webp">
                        <small class="text-muted">JPG, PNG, WEBP - Max 5MB par image - Sélection multiple possible</small>
                    </div>
                    <div class="image-preview-container mt-3" id="preview_${colorId}"></div>
                    <div class="mt-2" id="primary_selector_container_${colorId}" style="display:none;">
                        <label class="form-label">Image principale:</label>
                        <div id="primary_selector_${colorId}"></div>
                    </div>
                </div>
            </div>
        `;
    });
    
    $('#images_container').html(html);
    
    // Événements pour les images
    $('.image-upload').change(function() {
        handleImageUpload(this);
    });
    
    // Afficher les images existantes
    Object.keys(imagesData).forEach(colorId => {
        if (selectedColors.includes(colorId.toString())) {
            updateImagePreview(colorId);
        }
    });
}

// Gérer l'upload d'images
function handleImageUpload(input) {
    const colorId = $(input).data('color-id');
    const files = input.files;
    
    if (!imagesData[colorId]) {
        imagesData[colorId] = [];
    }
    
    Array.from(files).forEach(file => {
        if (file.size > 5 * 1024 * 1024) {
            alert(`Le fichier ${file.name} dépasse 5MB`);
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            imagesData[colorId].push({
                file: file,
                preview: e.target.result,
                isPrimary: imagesData[colorId].length === 0
            });
            
            updateImagePreview(colorId);
        };
        reader.readAsDataURL(file);
    });
}

// Mettre à jour l'aperçu des images
function updateImagePreview(colorId) {
    const container = $(`#preview_${colorId}`);
    const primarySelector = $(`#primary_selector_${colorId}`);
    const primaryContainer = $(`#primary_selector_container_${colorId}`);
    
    container.empty();
    primarySelector.empty();
    
    if (imagesData[colorId].length === 0) {
        primaryContainer.hide();
        return;
    }
    
    primaryContainer.show();
    
    imagesData[colorId].forEach((img, index) => {
        const previewHtml = `
            <div class="image-preview-item" data-image-id="${img.existing ? img.id : ''}">
                <img src="${img.preview}" alt="Image ${index + 1}">
                <button type="button" class="remove-image" data-color-id="${colorId}" data-index="${index}" data-existing="${img.existing ? 'true' : 'false'}" data-image-id="${img.existing ? img.id : ''}">
                    <i class="mdi mdi-close"></i>
                </button>
                <button type="button" class="homepage-image-btn ${img.isHomepage ? 'active' : ''}" data-color-id="${colorId}" data-index="${index}" data-image-id="${img.existing ? img.id : ''}" title="Marquer comme image homepage">
                    <i class="mdi mdi-star"></i>
                </button>
                ${img.isPrimary ? '<span class="primary-badge">Principale</span>' : ''}
                ${img.isHomepage ? '<span class="homepage-badge">HP</span>' : ''}
            </div>
        `;
        container.append(previewHtml);
        
        const radioHtml = `
            <div class="form-check form-check-inline">
                <input type="radio" class="form-check-input primary-radio" name="images_primary[${colorId}]" 
                       value="${index}" ${img.isPrimary ? 'checked' : ''} data-color-id="${colorId}" data-index="${index}">
                <label class="form-check-label">Image ${index + 1}</label>
            </div>
        `;
        primarySelector.append(radioHtml);
    });
    
    // Événements - Utiliser .off() avant .on() pour éviter les doublons
    $('.remove-image').off('click').on('click', function() {
        const colorId = $(this).data('color-id');
        const index = $(this).data('index');
        const isExisting = $(this).data('existing') === true || $(this).data('existing') === 'true';
        const imageId = $(this).data('image-id');
        
        if (!confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
            return;
        }
        
        if (isExisting) {
            // Supprimer l'image existante via AJAX
            const productId = {{ $product->id }};
            $.ajax({
                url: `/admin/products/${productId}/images/${imageId}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Supprimer du tableau local
                    imagesData[colorId].splice(index, 1);
                    
                    // Si on supprime l'image principale, définir la première comme principale
                    if (imagesData[colorId].length > 0 && !imagesData[colorId].some(img => img.isPrimary)) {
                        imagesData[colorId][0].isPrimary = true;
                    }
                    
                    updateImagePreview(colorId);
                    
                    // Afficher un message de succès
                    showAutoSaveIndicator('Image supprimée avec succès');
                },
                error: function(xhr) {
                    alert('Erreur lors de la suppression de l\'image');
                }
            });
        } else {
            // Supprimer l'image nouvelle (non sauvegardée)
            imagesData[colorId].splice(index, 1);
            
            // Si on supprime l'image principale, définir la première comme principale
            if (imagesData[colorId].length > 0 && !imagesData[colorId].some(img => img.isPrimary)) {
                imagesData[colorId][0].isPrimary = true;
            }
            
            updateImagePreview(colorId);
        }
    });
    
    $('.primary-radio').off('change').on('change', function() {
        const colorId = $(this).data('color-id');
        const index = $(this).data('index');
        
        imagesData[colorId].forEach((img, i) => {
            img.isPrimary = (i === index);
        });
        
        updateImagePreview(colorId);
    });
    
    // Gestionnaire pour le bouton homepage image
    $('.homepage-image-btn').off('click').on('click', function() {
        const colorId = $(this).data('color-id');
        const index = $(this).data('index');
        const imageId = $(this).data('image-id');
        
        const isCurrentlyHomepage = imagesData[colorId][index].isHomepage;
        
        // Désactiver toutes les images homepage
        Object.keys(imagesData).forEach(cId => {
            imagesData[cId].forEach(img => {
                img.isHomepage = false;
            });
        });
        
        // Si l'image n'était pas déjà homepage, l'activer
        // Sinon, la laisser désactivée (permet de retirer le statut homepage)
        if (!isCurrentlyHomepage) {
            imagesData[colorId][index].isHomepage = true;
        }
        
        // Mettre à jour tous les aperçus
        Object.keys(imagesData).forEach(cId => {
            updateImagePreview(cId);
        });
        
        // Si l'image existe déjà en base de données, envoyer la mise à jour au serveur
        if (imageId && imageId !== '' && imageId !== 'undefined') {
            const productId = {{ $product->id }};
            $.ajax({
                url: `/admin/products/${productId}/set-homepage-image`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    image_id: isCurrentlyHomepage ? null : imageId
                },
                success: function(response) {
                    if (isCurrentlyHomepage) {
                        showAutoSaveIndicator('Image homepage désactivée');
                    } else {
                        showAutoSaveIndicator('Image homepage définie avec succès');
                    }
                },
                error: function(xhr) {
                    alert('Erreur lors de la définition de l\'image homepage');
                    location.reload();
                }
            });
        } else {
            // Pour les nouvelles images, juste afficher un message
            if (isCurrentlyHomepage) {
                showAutoSaveIndicator('Image homepage désactivée (sera appliqué à la sauvegarde)');
            } else {
                showAutoSaveIndicator('Image homepage sélectionnée (sera appliqué à la sauvegarde)');
            }
        }
    });
}

// Afficher l'indicateur de sauvegarde automatique
function showAutoSaveIndicator(message = 'Sauvegarde automatique effectuée') {
    const indicator = $('#autoSaveIndicator');
    indicator.html(`<i class="mdi mdi-check"></i> ${message}`);
    indicator.addClass('show');
    
    setTimeout(() => {
        indicator.removeClass('show');
    }, 3000);
}

// Mettre à jour l'aperçu final
function updatePreview() {
    // Informations générales
    $('#preview_name').text($('#product_name').val());
    $('#preview_sku').text($('#product_sku').val() || 'Auto-généré');
    $('#preview_category').text($('#product_category option:selected').text());
    
    const price = parseFloat($('#product_price').val());
    const comparePrice = parseFloat($('#product_compare_price').val());
    let priceHtml = price.toFixed(2) + ' MAD';
    if (comparePrice > 0) {
        priceHtml += ` <del>${comparePrice.toFixed(2)} MAD</del>`;
    }
    $('#preview_price').html(priceHtml);
    
    $('#preview_description').html(quillEditor.root.innerHTML);
    
    // Résumé des variantes
    const totalStock = variantsData.reduce((sum, v) => sum + v.quantity, 0);
    const outOfStock = variantsData.filter(v => v.quantity === 0).length;
    const lowStock = variantsData.filter(v => v.quantity > 0 && v.quantity < v.low_stock_threshold).length;
    
    const summaryHtml = `
        <p><strong>Total variantes:</strong> ${variantsData.length}</p>
        <p><strong>Stock total:</strong> ${totalStock} unités</p>
        <p class="text-danger"><strong>Ruptures de stock:</strong> ${outOfStock}</p>
        <p class="text-warning"><strong>Stock faible:</strong> ${lowStock}</p>
    `;
    $('#preview_variants_summary').html(summaryHtml);
    
    // Galerie d'images
    const gallery = $('#preview_images_gallery');
    gallery.empty();
    
    let hasImages = false;
    Object.keys(imagesData).forEach(colorId => {
        imagesData[colorId].forEach(img => {
            hasImages = true;
            gallery.append(`<div class="image-preview-item"><img src="${img.preview}" alt="Image"></div>`);
        });
    });
    
    if (!hasImages) {
        gallery.html('<p class="text-muted">Aucune image uploadée</p>');
    }
}
</script>
@endsection
