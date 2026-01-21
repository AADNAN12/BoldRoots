@extends('front-office.layouts.app')
@section('title', $product->name . ' - BOLDROOTS')
@section('head')
    <!-- Css Styles -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/nice-select.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/slicknav.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css">
@endsection
@section('styles')
    <style>
        .menu-toggle {
            color: #000000ff !important;
        }
        .header-right a {
            color: #000000ff !important;
        }
        .header-right a:hover {
            color: #ff0000 !important;
        }
        
        /* Product Gallery Styles */
        .product-gallery-main {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .product-gallery-main img {
            width: 100%;
            height: auto;
            max-height: 600px;
            object-fit: contain;
            cursor: zoom-in;
            transition: transform 0.3s ease;
        }
        .product-gallery-main img:hover {
            transform: scale(1.02);
        }
        
        .product__details__pic__item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 400px;
        }
        
        #main-product-image {
            max-width: 100%;
            max-height: 400px;
            width: auto;
            height: auto;
            object-fit: contain;
        }
        .product-gallery-thumbs {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 15px;
        }
        .product-gallery-thumbs img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 10px;
            cursor: pointer;
            border: 3px solid #e0e0e0;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .product-gallery-thumbs img:hover {
            border-color: #ca1515;
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(202, 21, 21, 0.3);
        }
        .product-gallery-thumbs img.active {
            border-color: #ca1515;
            box-shadow: 0 4px 12px rgba(202, 21, 21, 0.4);
        }
        
        /* Promotion Badge */
        .promo-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 14px;
            z-index: 10;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
        }
        
        /* Price Section */
        .product-price {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 20px 0;
        }
        .product-price .current-price {
            font-size: 36px;
            font-weight: bold;
            color: #ca1515;
        }
        .product-price .old-price {
            font-size: 24px;
            color: #999;
            text-decoration: line-through;
        }
        .product-price .save-amount {
            background: #28a745;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
        }
        
        /* Variant Selection */
        .variant-option {
            display: inline-block;
            margin: 5px;
            padding: 10px 20px;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .variant-option:hover {
            border-color: #ca1515;
            background: #fff5f5;
        }
        .variant-option.active {
            border-color: #ca1515;
            background: #ca1515;
            color: white;
        }
        .variant-option.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        /* Stock Badge */
        .stock-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        .stock-badge.in-stock {
            background: #d4edda;
            color: #155724;
        }
        .stock-badge.low-stock {
            background: #fff3cd;
            color: #856404;
        }
        .stock-badge.out-of-stock {
            background: #f8d7da;
            color: #721c24;
        }
        
        /* Quantity Selector */
        .qty-selector {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .qty-selector button {
            width: 40px;
            height: 40px;
            border: 2px solid #ddd;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s ease;
        }
        .qty-selector button:hover {
            border-color: #ca1515;
            color: #ca1515;
        }
        .qty-selector input {
            width: 80px;
            height: 40px;
            text-align: center;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
        }
        
        /* Add to Cart Button */
        .btn-add-cart {
            background: linear-gradient(135deg, #ca1515 0%, #a01212 100%);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(202, 21, 21, 0.3);
        }
        .btn-add-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(202, 21, 21, 0.4);
        }
        .btn-add-cart:disabled {
            background: #ccc;
            cursor: not-allowed;
            box-shadow: none;
        }
        
        /* Product Info Tabs */
        .product-tabs {
            margin-top: 50px;
        }
        .product-tabs .nav-tabs {
            border-bottom: 2px solid #ddd;
        }
        .product-tabs .nav-link {
            color: #666;
            font-weight: 600;
            padding: 15px 30px;
            border: none;
            border-bottom: 3px solid transparent;
        }
        .product-tabs .nav-link.active {
            color: #ca1515;
            border-bottom-color: #ca1515;
        }
        
        /* Related Products */
        .related-products {
            margin-top: 60px;
        }
    </style>
@endsection
@section('content')
   <!-- Shop Details Section Begin -->
    <section class="shop-details" style="margin-top: 130px;">
        <div class="container">
            <!-- Breadcrumb -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="product__details__breadcrumb">
                        <a href="{{ route('home') }}">Home</a>
                        <a href="{{ route('products.index') }}">Shop</a>
                        <span>Product Details</span>
                    </div>
                </div>
            </div>
            
            @php
                $hasPromotion = $product->promotions->isNotEmpty();
                $finalPrice = $product->price;
                $discountPercent = 0;
                $promotion = null;
                
                if ($hasPromotion) {
                    $promotion = $product->promotions->first();
                    if ($promotion->discount_type === 'percentage') {
                        $discountPercent = $promotion->discount_value;
                        $finalPrice = $product->price * (1 - $promotion->discount_value / 100);
                    } else {
                        $finalPrice = max(0, $product->price - $promotion->discount_value);
                        $discountPercent = $product->price > 0 ? (($product->price - $finalPrice) / $product->price) * 100 : 0;
                    }
                }
                
                $mainImage = $product->images->first() 
                    ? asset('storage/' . $product->images->first()->image_path) 
                    : asset('img/shop-details/product-big-2.png');
            @endphp

            <!-- Product Image (Left) and Details (Right) -->
            <div class="row">
                <!-- Image Section - Left -->
                <div class="col-lg-6 col-md-6">
                    <div class="product__details__pic">
                        @if($hasPromotion)
                            <span class="promo-badge">-{{ number_format($discountPercent, 0) }}%</span>
                        @endif
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                <div class="product__details__pic__item">
                                    <img id="main-product-image" src="{{ $mainImage }}" alt="{{ $product->name }}">
                                </div>
                            </div>
                        </div>
                        @if($product->images->count() > 1)
                            <div class="product-gallery-thumbs mt-3" id="image-gallery">
                                @foreach($product->images as $index => $image)
                                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                                         alt="{{ $product->name }}" 
                                         class="gallery-thumb {{ $index === 0 ? 'active' : '' }}"
                                         data-color-id="{{ $image->color_id ?? 'all' }}"
                                         onclick="changeMainImage(this, '{{ asset('storage/' . $image->image_path) }}')">
                                @endforeach
                            </div>
                        @endif
                    </div>
                    
                   
                </div>
                 <!-- Product Details - Right -->
                    <div class="col-lg-6 col-md-6">
                        <div class="product__details__content">
                            <div class="product__details__text">
                                <h4>{{ $product->name }}</h4>
                                
                                @if($hasPromotion)
                                    <h3>{{ number_format($finalPrice, 2) }} DH <span>{{ number_format($product->price, 2) }} DH</span></h3>
                                    <div class="alert alert-success py-2 px-3 d-inline-block mb-3">
                                        <i class="fa fa-tags"></i> <strong>{{ $promotion->name }}</strong>
                                        @if($promotion->description)
                                            <br><small>{!! $promotion->description !!}</small>
                                        @endif
                                        <br><small>Économisez {{ number_format($product->price - $finalPrice, 2) }} DH (-{{ number_format($discountPercent, 0) }}%)</small>
                                    </div>
                                @else
                                    <h3>{{ number_format($product->price, 2) }} DH</h3>
                                @endif
                                
                                
                                
                                @if($product->variants->isNotEmpty())
                                    <div class="product__details__option">
                                        @php
                                            // Récupérer les tailles uniques via la relation size
                                            $sizes = $product->variants
                                                ->filter(fn($v) => $v->size)
                                                ->map(fn($v) => $v->size)
                                                ->unique('id');
                                            
                                            // Récupérer les couleurs uniques via la relation color
                                            $colors = $product->variants
                                                ->filter(fn($v) => $v->color)
                                                ->map(fn($v) => $v->color)
                                                ->unique('id');
                                        @endphp
                                        
                                        @if($sizes->isNotEmpty())
                                            <div class="product__details__option__size">
                                                <span>Size:</span>
                                                @foreach($sizes as $size)
                                                    <label for="size-{{ $size->id }}" class="{{ $loop->first ? 'active' : '' }}">
                                                        {{ $size->value }}
                                                        <input type="radio" name="size" id="size-{{ $size->id }}" value="{{ $size->id }}" {{ $loop->first ? 'checked' : '' }}>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @endif
                                        
                                        @if($colors->isNotEmpty())
                                            <div class="product__details__option__color" style="display: block; margin-top: 15px;">
                                                <span>Color:</span>
                                                <div class="color-options" style="display: inline-flex; gap: 10px; margin-left: 10px;">
                                                    @foreach($colors as $color)
                                                        <label class="color-option {{ $loop->first ? 'selected' : '' }}" for="color-{{ $color->id }}" style="background-color: {{ $color->color_code ?? '#000' }}; width: 30px; height: 30px; border-radius: 50%; cursor: pointer; display: inline-block; border: 3px solid {{ $loop->first ? '#ca1515' : 'transparent' }}; box-shadow: 0 0 0 1px #ddd;" title="{{ $color->value }}" onclick="selectColor(this, {{ $color->id }})">
                                                            <input type="radio" name="color" id="color-{{ $color->id }}" value="{{ $color->id }}" {{ $loop->first ? 'checked' : '' }} style="display: none;">
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                
                                <div class="product__details__cart__option">
                                    <div class="quantity">
                                        <div class="pro-qty">
                                            <input type="text" id="product-qty" value="1" min="1" max="{{ $product->stock_quantity }}">
                                        </div>
                                    </div>
                                    <a href="#" class="primary-btn" onclick="addToCart({{ $product->id }}); return false;">add to cart</a>
                                </div>
                                
                                <div class="product__details__last__option">
                                    <h5><span>Guaranteed Safe Checkout</span></h5>
                                    <img src="{{ asset('img/shop-details/details-payment.png') }}" alt="">
                                    <ul>
                                        <li><span>SKU:</span> {{ $product->sku ?? 'N/A' }}</li>
                                        <li><span>Categories:</span> {{ $product->category->name ?? 'N/A' }}</li>
                                        @if($product->stock_quantity > 0)
                                            <li><span>Stock:</span> <span class="text-success">{{ $product->stock_quantity }} disponible(s)</span></li>
                                        @else
                                            <li><span>Stock:</span> <span class="text-danger">Rupture de stock</span></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- Product Details Tabs - Below -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product__details__tab">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tabs-5"
                                    role="tab">Description</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tabs-5" role="tabpanel">
                                   <p>{!! $product->description !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Shop Details Section End -->

        <!-- Related Section Begin -->
        @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())
        <section class="related spad">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="related-title">Related Product</h3>
                    </div>
                </div>
                <div class="row">
                    @foreach($relatedProducts as $related)
                        @php
                            $relatedHasPromo = $related->promotions->isNotEmpty();
                            $relatedFinalPrice = $related->price;
                            $relatedDiscountPercent = 0;
                            
                            if ($relatedHasPromo) {
                                $relatedPromo = $related->promotions->first();
                                if ($relatedPromo->discount_type === 'percentage') {
                                    $relatedDiscountPercent = $relatedPromo->discount_value;
                                    $relatedFinalPrice = $related->price * (1 - $relatedPromo->discount_value / 100);
                                } else {
                                    $relatedFinalPrice = max(0, $related->price - $relatedPromo->discount_value);
                                    $relatedDiscountPercent = $related->price > 0 ? (($related->price - $relatedFinalPrice) / $related->price) * 100 : 0;
                                }
                            }
                            
                            $relatedImage = $related->images->first() 
                                ? asset('storage/' . $related->images->first()->image_path) 
                                : asset('images/No-Product-Image-Available.webp');
                        @endphp
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="product__item {{ $relatedHasPromo ? 'sale' : '' }}">
                                <div class="product__item__pic set-bg" data-setbg="{{ $relatedImage }}">
                                    @if($relatedHasPromo)
                                        <span class="label">-{{ number_format($relatedDiscountPercent, 0) }}%</span>
                                    @endif
                                    <ul class="product__hover">
                                        <li><a href="#" onclick="addToWishlist({{ $related->id }}); return false;"><img src="{{ asset('img/icon/heart.png') }}" alt=""></a></li>
                                        <li><a href="{{ route('products.show', $related->id) }}"><img src="{{ asset('img/icon/search.png') }}" alt=""></a></li>
                                    </ul>
                                </div>
                                <div class="product__item__text">
                                    <h6><a href="{{ route('products.show', $related->id) }}">{{ $related->name }}</a></h6>
                                    <a href="#" class="add-cart" onclick="addToCart({{ $related->id }}); return false;">+ Add To Cart</a>
                                    @if($relatedHasPromo)
                                        <h5>{{ number_format($relatedFinalPrice, 2) }} DH</h5>
                                        <span class="text-muted" style="text-decoration: line-through; font-size: 12px;">{{ number_format($related->price, 2) }} DH</span>
                                    @else
                                        <h5>{{ number_format($related->price, 2) }} DH</h5>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif
        <!-- Related Section End -->

    @endsection

    @section('scripts')
        <!-- Js Plugins -->
        <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/jquery.nice-select.min.js') }}"></script>
        <script src="{{ asset('js/jquery.nicescroll.min.js') }}"></script>
        <script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
        <script src="{{ asset('js/jquery.countdown.min.js') }}"></script>
        <script src="{{ asset('js/jquery.slicknav.js') }}"></script>
        <script src="{{ asset('js/mixitup.min.js') }}"></script>
        <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('js/main.js') }}"></script>
        
        <script>
        // Changer l'image principale
        function changeMainImage(thumb, imageUrl) {
            document.getElementById('main-product-image').src = imageUrl;
            document.querySelectorAll('.product-gallery-thumbs img').forEach(img => img.classList.remove('active'));
            thumb.classList.add('active');
        }
        
        // Ajouter au panier
        function addToCart(productId) {
            const quantity = document.getElementById('product-qty') ? parseInt(document.getElementById('product-qty').value) || 1 : 1;
            const size = document.querySelector('input[name="size"]:checked')?.value || null;
            const color = document.querySelector('input[name="color"]:checked')?.value || null;
            
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity,
                    size: size,
                    color: color
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Ouvrir le panier automatiquement
                    if (typeof openCart === 'function') {
                        openCart();
                    }
                } else {
                    alert('Erreur: ' + (data.message || 'Une erreur est survenue'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de l\'ajout au panier');
            });
        }
        
        // Ajouter à la wishlist
        function addToWishlist(productId) {
            alert('Fonctionnalité wishlist à venir!');
        }
        
        // Mettre à jour le compteur du panier
        function updateCartCount() {
            fetch('/cart/count')
                .then(response => response.json())
                .then(data => {
                    const cartCountElement = document.querySelector('.cart-count');
                    if (cartCountElement && data.count !== undefined) {
                        cartCountElement.textContent = data.count;
                    }
                })
                .catch(error => console.error('Error updating cart count:', error));
        }
        
        // Sélectionner une couleur et afficher les images correspondantes
        function selectColor(element, colorId) {
            // Mettre à jour la sélection visuelle de la couleur
            document.querySelectorAll('.color-option').forEach(opt => {
                opt.style.border = '3px solid transparent';
                opt.classList.remove('selected');
            });
            element.style.border = '3px solid #ca1515';
            element.classList.add('selected');
            element.querySelector('input').checked = true;
            
            // Filtrer et afficher les images correspondant à la couleur sélectionnée
            const allThumbs = document.querySelectorAll('.gallery-thumb');
            let colorImages = [];
            let firstVisibleImage = null;
            
            allThumbs.forEach(thumb => {
                const thumbColorId = thumb.getAttribute('data-color-id');
                
                // Afficher les images qui correspondent à la couleur ou qui n'ont pas de couleur spécifique
                if (thumbColorId === colorId.toString() || thumbColorId === 'all') {
                    thumb.style.display = 'inline-block';
                    colorImages.push(thumb);
                    if (!firstVisibleImage) {
                        firstVisibleImage = thumb;
                    }
                } else {
                    thumb.style.display = 'none';
                }
            });
            
            // Si aucune image spécifique à la couleur, afficher toutes les images
            if (colorImages.length === 0) {
                allThumbs.forEach(thumb => {
                    thumb.style.display = 'inline-block';
                    if (!firstVisibleImage) {
                        firstVisibleImage = thumb;
                    }
                });
            }
            
            // Changer l'image principale pour la première image visible
            if (firstVisibleImage) {
                const imageUrl = firstVisibleImage.getAttribute('src');
                changeMainImage(firstVisibleImage, imageUrl);
            }
        }
        </script>
    @endsection