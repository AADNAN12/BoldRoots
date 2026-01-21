@extends('front-office.layouts.app')

@section('title', 'About Us - BOLDROOTS')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css">
    <style>
        .about-header {
            background: linear-gradient(135deg, #000000 0%, #cc0000 100%);
            color: #fff;
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
            color:#b6b6b6 !important;
            letter-spacing: 1px;
            opacity: 0.9;
        }
        
        .info-box {
            background: #fff;
            border: 1px solid #e3e6f0;
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
            border-bottom: 3px solid #000;
            font-size: 24px;
        }
        
        .info-box p {
            font-size: 15px;
            line-height: 1.8;
            color: #666;
            margin-bottom: 15px;
        }
        
        .value-card {
            background: #f8f9fa;
            border-left: 4px solid #cc0000;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .value-card h4 {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 10px;
            color: #000;
        }
        
        .value-card p {
            font-size: 14px;
            color: #666;
            margin: 0;
            line-height: 1.6;
        }
        
        .team-member {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .team-member img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #000;
            margin-bottom: 20px;
        }
        
        .team-member h5 {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 5px;
        }
        
        .team-member .position {
            color: #cc0000;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        
        .team-member p {
            font-size: 13px;
            color: #666;
            line-height: 1.6;
        }
        
        .stats-section {
            background: #000;
            color: #fff;
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
            color: #cc0000;
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
            background: #000;
            color: #fff;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            border-radius: 0;
            transition: all 0.3s;
            margin: 0 10px;
            text-decoration: none;
        }
        
        .action-btn:hover {
            background: #cc0000;
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(204, 0, 0, 0.3);
        }
       
    </style>
@endsection

@section('content')
    <!-- Header Section -->
    <section class="about-header">
        <div class="container">
            <h1>ABOUT BOLDROOTS</h1>
            <p>STRUGGLE | ENDURE | WIN</p>
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
                        <p>
                            BOLDROOTS was born from a vision to create more than just clothing – we create a lifestyle. 
                            Our journey began with a simple belief: that fashion should empower, inspire, and reflect 
                            the bold spirit within each of us.
                        </p>
                        <p>
                            Every piece we design carries the essence of strength, resilience, and authenticity. 
                            We don't just follow trends; we set them. Our collections are crafted for those who dare 
                            to be different, who embrace challenges, and who live by the mantra: Struggle, Endure, Win.
                        </p>
                        <p>
                            From our humble beginnings to becoming a recognized name in urban fashion, BOLDROOTS has 
                            remained committed to quality, innovation, and the relentless pursuit of excellence. 
                            We are more than a brand – we are a movement.
                        </p>
                    </div>
                </div>

                <!-- Mission & Vision -->
                <div class="col-lg-6">
                    <div class="info-box">
                        <h3>OUR MISSION</h3>
                        <p>
                            To empower individuals through bold, high-quality fashion that reflects their inner strength 
                            and unique identity. We strive to create clothing that inspires confidence and celebrates 
                            the journey of every person who wears our brand.
                        </p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="info-box">
                        <h3>OUR VISION</h3>
                        <p>
                            To become the global leader in urban streetwear, recognized for our commitment to quality, 
                            innovation, and authenticity. We envision a world where BOLDROOTS is synonymous with 
                            courage, resilience, and the relentless pursuit of greatness.
                        </p>
                    </div>
                </div>

                <!-- Our Values -->
                <div class="col-lg-12">
                    <div class="info-box">
                        <h3>OUR VALUES</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="value-card">
                                    <h4><i class="fa fa-bolt"></i> BOLDNESS</h4>
                                    <p>We encourage taking risks, standing out, and being unapologetically yourself.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="value-card">
                                    <h4><i class="fa fa-star"></i> QUALITY</h4>
                                    <p>Every product is crafted with premium materials and meticulous attention to detail.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="value-card">
                                    <h4><i class="fa fa-users"></i> COMMUNITY</h4>
                                    <p>We build a family of like-minded individuals who support and inspire each other.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="value-card">
                                    <h4><i class="fa fa-lightbulb-o"></i> INNOVATION</h4>
                                    <p>We constantly push boundaries and explore new designs, styles, and technologies.</p>
                                </div>
                            </div>
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
