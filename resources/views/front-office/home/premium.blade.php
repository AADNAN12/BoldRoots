@extends('front-office.layouts.app')
@section('title', 'WELCOME TO - {{ env("APP_NAME") }}')
@section('head')
@endsection
@section('styles')
    <style>
        /* Base typography */
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #fff;
            color: #333;
        }

         .menu-toggle {
            color: #ffffffff !important;
        }

        .header-right a {
            color: #ffffffff !important;
        }

        .header-right a:hover {
            color: var(--primary-color) !important;
        }
        .cart-icon {
            color: white !important;
        }

        /* Centered brand name absolute */
        .brand-center {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: rgba(0, 0, 0, 0.5);
            text-decoration: none;
            text-transform: uppercase;
        }

        /* Right icons */
        .icon-link {
            color: rgba(0, 0, 0, 0.6);
            font-size: 1.1rem;
            margin-left: 1rem;
            transition: color 0.3s ease;
        }
        .icon-link:hover {
            color: #000;
        }

        /* Images section (Split Horizontal) */
        .hero-section {
            display: flex;
            flex-direction: column;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .hero-slider {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }

        .hero-slide {
            display:flex;
            justify-content:center;
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .hero-slide.active {
            opacity: 1;
        }

        .hero-image {
            width: 100%;
            height: 100%;
            padding:42px 0px 0px 0px;
            object-fit: cover;
            object-position: center;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.1) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }

        .hero-content {
            max-width: 600px;
            padding: 2rem;
            animation: fadeInUp 1s ease-out;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .hero-btn {
            display: inline-block;
            padding: 12px 30px;
            background: var(--primary-color, #000);
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .hero-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .slider-dots {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 10;
        }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255,255,255,0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dot.active {
            background: white;
            transform: scale(1.2);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Premium products section */
        .premium-products {
            padding: 4rem 0;
            background: #f8f9fa;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .product-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
        }

        .product-info {
            padding: 1.5rem;
        }

        .product-name {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .product-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color, #000);
            margin-bottom: 1rem;
        }

        .product-description {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .product-btn {
            width: 100%;
            padding: 12px;
            background: var(--primary-color, #000);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .product-btn:hover {
            background: color-mix(in srgb, var(--primary-color, #000) 85%, #000);
        }

        /* Minimalist text section */
        .content-section {
            padding: 6rem 1rem;
        }

        .philosophy-text {
            font-size: 0.95rem;
            line-height: 1.8;
            color: #777;
            margin: 0 auto;
        }

        .tagline {
            margin-top: 2.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: #888;
            text-transform: uppercase;
        }
    </style>
@endsection

@section('content')
    <main>
        <!-- Hero Slider -->
        <div class="hero-section">
            <div class="hero-slider">
                @php
                    $heroSlide1 = \App\Models\SiteSetting::get('hero_slide_1_image');
                    $heroSlide2 = \App\Models\SiteSetting::get('hero_slide_2_image');
                    $heroSlide3 = \App\Models\SiteSetting::get('hero_slide_3_image');
                    
                    $heroImages = [];
                    if ($heroSlide1) $heroImages[] = asset('storage/' . $heroSlide1);
                    if ($heroSlide2) $heroImages[] = asset('storage/' . $heroSlide2);
                    if ($heroSlide3) $heroImages[] = asset('storage/' . $heroSlide3);
                    
                    // Fallback images if no settings configured
                    if (empty($heroImages)) {
                        $heroImages = [
                            'https://images.unsplash.com/photo-1552374196-1ab2a1c593e8?auto=format&fit=crop&q=80&w=1920',
                            'https://images.unsplash.com/photo-1511556532299-8f662fc26c06?auto=format&fit=crop&q=80&w=1920',
                            'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&q=80&w=1920'
                        ];
                    }
                @endphp
                
                @foreach($heroImages as $index => $image)
                    <div class="hero-slide {{ $index === 0 ? 'active' : '' }}">
                        <img src="{{ $image }}" 
                             alt="Hero Slide {{ $index + 1 }}" 
                             class="hero-image">
                    </div>
                @endforeach
            </div>
            
            <div class="slider-dots">
                @foreach($heroImages as $index => $image)
                    <span class="dot {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}"></span>
                @endforeach
            </div>
        </div>

        <!-- Premium Products Section -->
        <section class="premium-products">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="display-4 fw-bold mb-3">Featured Products</h2>
                    <p class="text-muted">Our selection of the best premium items</p>
                </div>
                
                <div class="row g-4">
                    @php
                        $premiumProducts = \App\Models\Product::where('is_featured', true)
                            ->take(6)
                            ->get();
                    @endphp
                    
                    @foreach($premiumProducts as $product)
                        <div class="col-md-6 col-lg-4">
                            <div class="product-card">
                                <img src="{{ 'storage/'.$product->primaryImage()?->image_path ?? 'https://images.unsplash.com/photo-' . rand(1500000, 1600000) . '?auto=format&fit=crop&q=80&w=400' }}" 
                                     alt="{{ $product->name }}" 
                                     class="product-image">
                                <div class="product-info">
                                    <h3 class="product-name">{{ $product->name }}</h3>
                                    <div class="product-price">{{ number_format($product->price, 2) }} DH</div>
                                    <p class="product-description">{!! Str::limit($product->description ?? 'Premium product of exceptional quality', 100) !!}</p>
                                    <a href="{{ route('products.show', $product) }}" >
                                        <button class="product-btn">
                                        View Details
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    @if($premiumProducts->isEmpty())
                        <!-- Fallback products if database is empty -->
                        @for($i = 1; $i <= 6; $i++)
                            <div class="col-md-6 col-lg-4">
                                <div class="product-card">
                                    <img src="https://images.unsplash.com/photo-{{ 1500000 + $i * 100 }}?auto=format&fit=crop&q=80&w=400" 
                                         alt="Premium Product {{ $i }}" 
                                         class="product-image">
                                    <div class="product-info">
                                        <h3 class="product-name">Premium Product {{ $i }}</h3>
                                        <div class="product-price">{{ 150 + $i * 25 }}.00 €</div>
                                        <p class="product-description">Discover this exceptional product from our premium collection.</p>
                                        <button class="product-btn">
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    @endif
                </div>
            </div>
        </section>

        <!-- Philosophy Section -->
        <section class="content-section text-center container">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <p class="philosophy-text">
                         {!! \App\Models\SiteSetting::get('about_text', '
                            {{ env("APP_NAME") }} was born from a vision to create more than just clothing – we create a lifestyle. 
                            Our journey began with a simple belief: that fashion should empower, inspire, and reflect 
                            the bold spirit within each of us.
                            <br><br>
                            Every piece we design carries the essence of strength, resilience, and authenticity. 
                            We don\'t just follow trends; we set them. Our collections are crafted for those who dare 
                            to be different, who embrace challenges, and who live by the mantra: Struggle, Endure, Win.
                            <br><br>
                            From our humble beginnings to becoming a recognized name in urban fashion, {{ env("APP_NAME") }} has 
                            remained committed to quality, innovation, and the relentless pursuit of excellence. 
                            We are more than a brand – we are a movement.
                            ') !!}
                    </p>
                    <p class="tagline">
                        Created for life — Good for all seasons
                    </p>
                </div>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Slider functionality
            const slides = document.querySelectorAll('.hero-slide');
            const dots = document.querySelectorAll('.dot');
            let currentSlide = 0;
            
            function showSlide(index) {
                slides.forEach(slide => slide.classList.remove('active'));
                dots.forEach(dot => dot.classList.remove('active'));
                
                slides[index].classList.add('active');
                dots[index].classList.add('active');
                currentSlide = index;
            }
            
            function nextSlide() {
                currentSlide = (currentSlide + 1) % slides.length;
                showSlide(currentSlide);
            }
            
            // Auto-play slider
            setInterval(nextSlide, 5000);
            
            // Manual slide control
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => showSlide(index));
            });
            
            // Add to cart functionality
            window.addToCart = function(productId) {
                // Implement cart functionality
                console.log('Adding product to cart:', productId);
                // You can integrate with your existing cart system here
            };
        });
    </script>
@endsection
