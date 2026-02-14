@extends('front-office.layouts.app')
@section('title', 'Welcome To BOLDROOTS')
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
    <style>
        .product__pagination a.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
            background-color: #f5f5f5;
        }
        
        .product__pagination a.disabled:hover {
            border-color: #e5e5e5;
        }

        .product__item__pic {
            overflow: hidden;
        }

        .product__item__pic .product-img-front,
        .product__item__pic .product-img-back {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center center;
            transition: opacity 0.5s ease;
        }

        .product__item__pic .product-img-front {
            opacity: 1;
            z-index: 1;
        }

        .product__item__pic .product-img-back {
            opacity: 0;
            z-index: 2;
        }

        .product__item__pic:hover .product-img-front {
            opacity: 0;
        }

        .product__item__pic:hover .product-img-back {
            opacity: 1;
        }

        .product__item__pic .label,
        .product__item__pic .label-new {
            z-index: 3;
        }
    </style>
@endsection
@section('content')
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option" style="margin-top: 130px !important;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Shop</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Home</a>
                            <span>Shop</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Shop Section Begin -->
    <section class="shop spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="shop__sidebar">
                        <div class="shop__sidebar__search">
                            <form action="{{ route('products.index') }}" method="GET">
                                <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}">
                                <button type="submit"><span class="icon_search"></span></button>
                            </form>
                        </div>
                        <div class="shop__sidebar__accordion">
                            <div class="accordion" id="accordionExample">
                                <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapseOne">Categories</a>
                                    </div>
                                    <div id="collapseOne" class="collapse show" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="shop__sidebar__categories">
                                                <ul class="nice-scroll">
                                                    <li>
                                                        <a href="{{ route('products.index') }}" class="{{ !request('category') ? 'active' : '' }}">
                                                            Tous les produits ({{ $products->total() }})
                                                        </a>
                                                    </li>
                                                    @foreach($categories as $cat)
                                                        <li>
                                                            <a href="{{ route('products.index', ['category' => $cat->slug]) }}" 
                                                               class="{{ request('category') == $cat->slug ? 'active' : '' }}">
                                                                {{ $cat->name }} ({{ $cat->products_count }})
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="shop__product__option">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="shop__product__option__left">
                                    <p>Showing {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} results</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @forelse($products as $product)
                            @php
                                $hasPromotion = $product->promotions->isNotEmpty();
                                $finalPrice = $product->price;
                                $discountPercent = 0;
                                
                                if ($hasPromotion) {
                                    $promotion = $product->promotions->first();
                                    if ($promotion->discount_type === 'percentage') {
                                        $discountPercent = $promotion->discount_value;
                                        $finalPrice = $product->price * (1 - $promotion->discount_value / 100);
                                    } else {
                                        $finalPrice = max(0, $product->price - $promotion->discount_value);
                                        $discountPercent = (($product->price - $finalPrice) / $product->price) * 100;
                                    }
                                }
                                
                                $homepageImg = $product->images->where('is_homepage_image', 1)->first();
                                $imageUrl = $homepageImg 
                                    ? asset('storage/' . $homepageImg->image_path) 
                                    : asset('images/No-Product-Image-Available.webp');
                                
                                $backImg = null;
                                if ($homepageImg) {
                                    $backImg = $product->images
                                        ->where('color_id', $homepageImg->color_id)
                                        ->where('id', '!=', $homepageImg->id)
                                        ->sortBy('sort_order')
                                        ->first();
                                }
                                $backImageUrl = $backImg ? asset('storage/' . $backImg->image_path) : null;
                            @endphp
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="product__item {{ $hasPromotion ? 'sale' : '' }}">
                                    <a href="{{ route('products.show', $product) }}">
                                    <div class="product__item__pic" style="position: relative;">
                                        <div class="product-img-front" style="background-image: url('{{ $imageUrl }}');"></div>
                                        @if($backImageUrl)
                                            <div class="product-img-back" style="background-image: url('{{ $backImageUrl }}');"></div>
                                        @endif
                                        @if($hasPromotion)
                                            <span class="label">-{{ number_format($discountPercent, 0) }}%</span>
                                        @endif
                                        @if($product->is_new)
                                            <span class="label-new">New</span>
                                        @endif
                                    </div>
                                    </a>
                                    <div class="product__item__text">
                                        <h6>{{ $product->name }}</h6>
                                        <a href="{{ route('products.show', $product) }}" class="add-cart" >View Details</a>
                                        <div class="product__price">
                                            @if($hasPromotion)
                                                <h5>{{ number_format($finalPrice, 2) }} DH</h5>
                                                <span class="text-muted" style="text-decoration: line-through;">{{ number_format($product->price, 2) }} DH</span>
                                            @else
                                                <h5>{{ number_format($product->price, 2) }} DH</h5>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <p>Aucun produit trouvé.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    @if($products->hasPages())
                        <div class="row mb-4">
                            <div class="col-lg-12">
                                <div class="product__pagination">
                                    @if ($products->onFirstPage())
                                        <a href="#" class="disabled"><i class="fa fa-long-arrow-left"></i></a>
                                    @else
                                        <a href="{{ $products->previousPageUrl() }}"><i class="fa fa-long-arrow-left"></i></a>
                                    @endif

                                    @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                        @if ($page == $products->currentPage())
                                            <a href="#" class="active">{{ $page }}</a>
                                        @else
                                            <a href="{{ $url }}">{{ $page }}</a>
                                        @endif
                                    @endforeach

                                    @if ($products->hasMorePages())
                                        <a href="{{ $products->nextPageUrl() }}"><i class="fa fa-long-arrow-right"></i></a>
                                    @else
                                        <a href="#" class="disabled"><i class="fa fa-long-arrow-right"></i></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- Shop Section End -->
@endsection

@section('scripts')
    <!-- Js Plugins -->
    <script src="{{ asset('js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('js/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('js/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('js/jquery.slicknav.js') }}"></script>
    <script src="{{ asset('js/mixitup.min.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
@endsection