@extends('front-office.layouts.app')
@section('title', 'Welcome To BOLDROOTS')
@section('head')
@endsection
@section('styles')
    <style>
        .menu-toggle {
            color: #ffffffff !important;
        }

        .header-right a {
            color: #ffffffff !important;
        }

        .header-right a:hover {
            color: #ff0000 !important;
        }

        .header-right a {
            color: #ffffffff;
        }

        .header-right a:hover {
            color: #ff0000;
        }

        .user-dropdown-btn {
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            color: white !important;
        }

        .dropdown-item {
            color: #fff !important;
        }

        .cart-icon {
            color: white !important;
        }

        /* Hero Section */
        .hero-section {
            margin-top: 40px;
            height: calc(100vh - 89px);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 50% 50%, rgba(255, 0, 0, 0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 0.5;
            }

            50% {
                opacity: 1;
            }
        }

        /* Products Display - Overlapping Layout */
        .products-showcase {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            transform: translateY(-50%);
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            z-index: 10;
        }

        .product-group {
            display: flex;
            align-items: center;
        }

        .product-item {
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .product-item img {
            width: 400px;
            height: auto;
            filter: drop-shadow(0 0 10px rgba(255, 0, 0, 0.8));
            transition: all 0.3s ease;
        }

        /* Overlapping effect - second item in each group */
        .product-group .product-item:nth-child(2) {
            margin-left: -200px;
            z-index: 1;
        }

        .product-group .product-item:nth-child(1) {
            z-index: 2;
        }

        .product-item:hover {
            transform: scale(1.08);
            z-index: 10;
        }

        .product-item:hover img {
            filter: drop-shadow(0 0 12px rgba(255, 30, 30, 1));
        }

        /* Responsive Products */
        @media (max-width: 1400px) {
            .product-item img {
                width: 350px;
            }

            .product-group .product-item:nth-child(2) {
                margin-left: -180px;
            }
        }

        @media (max-width: 1200px) {
            .product-item img {
                width: 300px;
            }

            .product-group .product-item:nth-child(2) {
                margin-left: -160px;
            }
        }

        @media (max-width: 992px) {
            .product-item img {
                width: 260px;
            }

            .product-group .product-item:nth-child(2) {
                margin-left: -140px;
            }
        }

        @media (max-width: 768px) {
            .products-showcase {
                flex-direction: column;
                gap: 50px;
                padding: 0 10px;
            }

            .product-item img {
                width: 220px;
            }

            .product-group .product-item:nth-child(2) {
                margin-left: -120px;
            }
        }

        @media (max-width: 576px) {
            .products-showcase {
                gap: 30px;
            }

            .product-item img {
                width: 180px;
            }

            .product-group .product-item:nth-child(2) {
                margin-left: -90px;
            }
        }

        @media (max-width: 480px) {
            .product-item img {
                width: 150px;
            }

            .product-group .product-item:nth-child(2) {
                margin-left: -75px;
            }
        }

        @media (max-width: 375px) {
            .product-item img {
                width: 130px;
            }

            .product-group .product-item:nth-child(2) {
                margin-left: -65px;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Hero Section -->
    @php
        $heroBgImage = $settings['hero_bg_image']->value ?? 'images/bg_home_page.jpg';
        $heroBgUrl = str_starts_with($heroBgImage, 'images/') 
            ? asset($heroBgImage) 
            : asset('storage/' . $heroBgImage);
    @endphp
    <section class="hero-section" style="background-image: url('{{ $heroBgUrl }}');">
        <!-- Products Showcase -->
        <div class="products-showcase">
            @if($featuredProducts->count() >= 2)
                <div class="product-group left">
                    @foreach($featuredProducts->take(2) as $product)
                        <a href="{{ route('products.show', $product->id) }}" class="product-item">
                            @php
                                $homepageImage = $product->homepageImage();
                            @endphp
                            @if($homepageImage)
                                <img src="{{ asset('storage/' . $homepageImage->image_path) }}" 
                                     alt="{{ $product->name }}">
                            @else
                                <img src="{{ asset('images/No-Product-Image-Available.webp') }}" 
                                     alt="{{ $product->name }}">
                            @endif
                        </a>
                    @endforeach
                </div>

                <div class="product-group right">
                    @foreach($featuredProducts->slice(2, 2) as $product)
                        <a href="{{ route('products.show', $product->id) }}" class="product-item">
                            @php
                                $homepageImage = $product->homepageImage();
                            @endphp
                            @if($homepageImage)
                                <img src="{{ asset('storage/' . $homepageImage->image_path) }}" 
                                     alt="{{ $product->name }}">
                            @else
                                <img src="{{ asset('images/No-Product-Image-Available.webp') }}" 
                                     alt="{{ $product->name }}">
                            @endif
                        </a>
                    @endforeach
                </div>
            @else
                <!-- Fallback si pas assez de produits en vedette -->
                <div class="product-group left">
                    <a href="#" class="product-item">
                        <img src="https://theboldroots.com/cdn/shop/files/Berserker3_1080x1080.png?v=1767189463"
                            alt="T-shirt Left 1">
                    </a>
                    <a href="#" class="product-item">
                        <img src="https://theboldroots.com/cdn/shop/files/Berserker5_900x900.png?v=1767189607"
                            alt="T-shirt Left 2">
                    </a>
                </div>

                <div class="product-group right">
                    <a href="#" class="product-item">
                        <img src="https://theboldroots.com/cdn/shop/files/Berserker12_900x900.png?v=1766076968"
                            alt="T-shirt Right 1">
                    </a>
                    <a href="#" class="product-item">
                        <img src="https://theboldroots.com/cdn/shop/files/Swordofvalorwashed_900x900.png?v=1767195481"
                            alt="T-shirt Right 2">
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection
