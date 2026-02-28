@extends('front-office.layouts.app')

@section('title', 'About Us - BOLDROOTS')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css">
    <style>
        .about-header {
            background: linear-gradient(135deg, var(--background-color) 0%, var(--primary-color) 100%);
            color: var(--primary-text-color);
            padding: 60px 0;
            margin-top: 130px;
            text-align: center;
        }
        
        .about-header h1 {
            font-weight: bold;
            letter-spacing: 3px;
            color: #fff !important;
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .about-header p {
            font-size: 18px;
            color: var(--secondary-text-color) !important;
            letter-spacing: 1px;
            opacity: 0.9;
        }
        
        .info-box {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 30px;
            transition: all 0.3s;
        }
        
        .info-box:hover {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }
        
        .info-box h3 {
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid var(--secondary-text-color);
            font-size: 24px;
        }
        
        .info-box p {
            font-size: 15px;
            line-height: 1.8;
            color: var(--secondary-text-color);
            margin-bottom: 15px;
        }
        .about-content {
            line-height: 1.8;
            color: var(--secondary-text-color);
            font-size: 16px;
        }
        
        .about-content p {
            margin-bottom: 20px;
        }
        
        .stats-section {
            background: var(--background-color);
            color: var(--primary-text-color);
            padding: 60px 0;
            margin: 50px 0;
        }
        
        .stat-item {
            text-align: center;
            padding: 20px;
        }
        
        .stat-number {
            font-size: 48px;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .stat-label {
            font-size: 16px;
            letter-spacing: 2px;
            text-transform: uppercase;
            opacity: 0.8;
        }
        
        .action-buttons {
            text-align: center;
            margin: 50px 0;
        }
        
        .action-btn {
            display: inline-block;
            padding: 15px 40px;
            background: var(--background-color);
            color: var(--primary-text-color);
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            border-radius: 0;
            transition: all 0.3s;
            margin: 0 10px;
            text-decoration: none;
        }
        
        .action-btn:hover {
            background: var(--primary-color);
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px color-mix(in srgb, var(--primary-color) 30%, transparent);
        }
       
    </style>
@endsection

@section('content')
    <!-- Header Section -->
    <section class="about-header">
        <div class="container">
            <h1>ABOUT US</h1>
        </div>
    </section>

    <!-- Breadcrumb Section -->
    <section class="breadcrumb-option" style="padding: 20px 0;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Home</a>
                            <span>About Us</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <section style="padding: 40px 0;">
        <div class="container">
            <div class="row">
                <!-- Our Story -->
                <div class="col-lg-12">
                    <div class="info-box">
                        <h3>OUR STORY</h3>
                        <div class="about-content">
                            {!! \App\Models\SiteSetting::get('about_text', '
                            BOLDROOTS was born from a vision to create more than just clothing – we create a lifestyle. 
                            Our journey began with a simple belief: that fashion should empower, inspire, and reflect 
                            the bold spirit within each of us.
                            <br><br>
                            Every piece we design carries the essence of strength, resilience, and authenticity. 
                            We don\'t just follow trends; we set them. Our collections are crafted for those who dare 
                            to be different, who embrace challenges, and who live by the mantra: Struggle, Endure, Win.
                            <br><br>
                            From our humble beginnings to becoming a recognized name in urban fashion, BOLDROOTS has 
                            remained committed to quality, innovation, and the relentless pursuit of excellence. 
                            We are more than a brand – we are a movement.
                            ') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">5+</div>
                        <div class="stat-label">Years Experience</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">10K+</div>
                        <div class="stat-label">Happy Customers</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Products</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Collections</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Call to Action -->
    <section class="action-buttons">
        <div class="container">
            <h2 style="margin-bottom: 30px; font-weight: bold; letter-spacing: 2px;">JOIN THE MOVEMENT</h2>
            <a href="{{ route('products.index') }}" class="action-btn">Shop Now</a>
            <a href="{{ route('contact') }}" class="action-btn">Contact Us</a>
        </div>
    </section>
@endsection