@extends('front-office.layouts.app')
@section('title', 'Welcome To BOLDROOTS - The Eclipse Collection')
@section('head')
@endsection
@section('styles')
    <style>
        /* Navbar overrides */
        .menu-toggle, .cart-icon, .dropdown-item { color: #ffffff !important; }
        .header-right a { color: #ffffff !important; transition: color 0.3s ease; }
        .header-right a:hover { color: #cc0000 !important; }
        .user-dropdown-btn { border: 1px solid rgba(255, 255, 255, 0.3) !important; color: white !important; }

        /* --- Full Screen Background --- */
        .main-wrapper {
            position: relative;
            min-height: 100vh;
            color: #ffffff;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            /* Image en plein écran et fixe */
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed; 
        }

        /* Overlay sombre pour garantir la lisibilité du texte */
        .main-wrapper::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.65); /* Ajuste l'opacité (0.65) selon ton image */
            z-index: 0;
        }

        /* Conteneur pour s'assurer que le contenu est au-dessus du fond */
        .content-container {
            position: relative;
            z-index: 1;
            padding-bottom: 80px;
        }

        /* --- Hero Section --- */
        .hero-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
            padding: 80px 20px 40px;
            min-height: 60vh;
            flex-wrap: wrap;
        }

        .hero-left {
            flex: 1;
            min-width: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }

        .hero-featured-img {
            width: 100%;
            max-width: 450px;
            filter: drop-shadow(0 0 10px rgba(255, 0, 0, 0.8));
            animation: float 6s ease-in-out infinite;
        }

        .hero-featured-title {
            /* margin-top: 20px; */
            font-size: 1.4rem;
            font-weight: 600;
            color: #ffffff;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.9), 0 0 20px rgba(255, 0, 0, 0.3);
            letter-spacing: 1px;
            text-transform: uppercase;
            background: linear-gradient(45deg, #ffffff, #ff0000);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: titleGlow 3s ease-in-out infinite alternate;
        }

        @keyframes titleGlow {
            from {
                filter: drop-shadow(0 0 10px rgba(255, 0, 0, 0.5));
            }
            to {
                filter: drop-shadow(0 0 20px rgba(255, 0, 0, 0.8));
            }
        }

        .hero-right {
            flex: 1;
            min-width: 300px;
            padding-left: 50px;
        }

        .hero-title {
            font-size: 4.5rem;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: -1px;
            text-shadow: 3px 3px 10px rgba(0,0,0,0.9);
        }

        .hero-subtitle {
            font-size: 1.5rem;
            color: #cccccc;
            margin-bottom: 30px;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.8);
        }

        .btn-shop {
            background-color: transparent;
            color: white;
            padding: 14px 35px;
            font-size: 1.1rem;
            font-weight: bold;
            border-radius: 8px;
            border: 2px solid #ff1a1a;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(220, 20, 60, 0.2);
            backdrop-filter: blur(5px);
        }

        .btn-shop:hover {
            background-color: #8b0000;
            box-shadow: 0 6px 20px rgba(220, 20, 60, 0.5);
            transform: translateY(-2px);
            color: white;
            border-color: #8b0000;
        }

        /* --- Products Grid Section --- */
        .products-section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
        }

        /* Glassmorphism sur les cartes */
        .product-card {
            background: rgba(20, 20, 20, 0.4); 
            backdrop-filter: blur(12px); /* Crée le flou sur l'image de fond */
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 30px 20px 20px;
            text-align: left;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-5px);
            border-color: rgba(255, 255, 255, 0.3);
            background: rgba(30, 30, 30, 0.6);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .product-img-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 220px;
            margin-bottom: 25px;
            filter: drop-shadow(0 0 10px rgba(255, 0, 0, 0.8));
            transition: all 0.3s ease;
        }

        .product-card img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: transform 0.4s ease;
        }

        .product-card:hover img {
            transform: scale(1.08);
        }

        .product-title {
            color: #ffffff;
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .product-price {
            color: #cccccc;
            font-size: 0.9rem;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-section { flex-direction: column; text-align: center; padding-top: 40px; }
            .hero-right { padding-left: 0; margin-top: 40px; }
            .hero-title { font-size: 3rem; }
            .products-grid { grid-template-columns: repeat(2, 1fr); gap: 15px; }
            .product-img-wrapper { height: 160px; }
        }

        @media (max-width: 480px) {
            .products-grid { grid-template-columns: 1fr; }
            .hero-title { font-size: 2.5rem; }
        }
    </style>
@endsection

@section('content')
    @php
        // Récupération de ton image d'arrière-plan dynamique
        $heroBgImage = $settings['hero_bg_image']->value ?? 'images/bg_home_page.jpg';
        $heroBgUrl = str_starts_with($heroBgImage, 'images/') 
            ? asset($heroBgImage) 
            : asset('storage/' . $heroBgImage);
    @endphp

    <div class="main-wrapper" style="background-image: url('{{ $heroBgUrl }}');">
        
        <div class="content-container">
            
            <section class="hero-section">
                <div class="hero-left">
                    <img src="{{ asset('storage/products/default_1.webp') }}" alt="Berserk Sword Tee" class="hero-featured-img">
                    <div class="hero-featured-title">Berserk Sword Tee</div>
                </div>
                
                <div class="hero-right">
                    <h1 class="hero-title">THE ECLIPSE<br>COLLECTION</h1>
                    <p class="hero-subtitle">Limited Edition Drop</p>
                    <a href="#collection" class="btn-shop">SHOP THE LOOK</a>
                </div>
            </section>

            <section class="products-section" id="collection">
                <div class="products-grid">
                    {{-- @if($featuredProducts->count() > 0)
                        @foreach($featuredProducts->take(4) as $product)
                            <a href="{{ route('products.show', $product->id) }}" class="product-card">
                                <div class="product-img-wrapper">
                                    @php
                                        $homepageImage = $product->homepageImage();
                                    @endphp
                                    @if($homepageImage)
                                        <img src="{{ asset('storage/' . $homepageImage->image_path) }}" alt="{{ $product->name }}">
                                    @else
                                        <img src="{{ asset('images/No-Product-Image-Available.webp') }}" alt="{{ $product->name }}">
                                    @endif
                                </div>
                                <div class="product-title">{{ $product->name }}</div>
                                <div class="product-price">{{ number_format($product->price, 2) }} DH</div>
                            </a>
                        @endforeach
                    @else --}}
                        <a href="#" class="product-card">
                            <div class="product-img-wrapper">
                                <img src="{{ asset('storage/products/default_1.webp') }}" alt="BEHELIT TEE">
                            </div>
                            <div class="product-title">BEHELIT TEE</div>
                            <div class="product-price">299 DH</div>
                        </a>
                        <a href="#" class="product-card">
                            <div class="product-img-wrapper">
                                <img src="{{ asset('storage/products/default_2.webp') }}" alt="ECLIPSE TEE">
                            </div>
                            <div class="product-title">ECLIPSE TEE</div>
                            <div class="product-price">299 DH</div>
                        </a>
                        <a href="#" class="product-card">
                            <div class="product-img-wrapper">
                                <img src="{{ asset('storage/products/default_3.webp') }}" alt="GRIFFITH TEE">
                            </div>
                            <div class="product-title">GRIFFITH TEE</div>
                            <div class="product-price">209 DH</div>
                        </a>
                        <a href="#" class="product-card">
                            <div class="product-img-wrapper">
                                <img src="{{ asset('storage/products/default_4.webp') }}" alt="ZODD TEE">
                            </div>
                            <div class="product-title">ZODD TEE</div>
                            <div class="product-price">209 DH</div>
                        </a>
                    {{-- @endi --}}
                </div>
            </section>
        </div>
    </div>
@endsection