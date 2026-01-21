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
                                
                                $imageUrl = $product->images->where('is_homepage_image', 1)->first() 
                                    ? asset('storage/' . $product->images->where('is_homepage_image', 1)->first()->image_path) 
                                    : asset('images/No-Product-Image-Available.webp');
                            @endphp
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="product__item {{ $hasPromotion ? 'sale' : '' }}">
                                    <a href="{{ route('products.show', $product) }}">
                                    <div class="product__item__pic set-bg" data-setbg="{{ $imageUrl }}">
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
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="product__pagination">
                                    {{ $products->links() }}
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