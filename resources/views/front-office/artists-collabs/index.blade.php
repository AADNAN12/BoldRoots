@extends('front-office.layouts.app')

@section('title', 'Artists Collabs - BOLDROOTS')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css">
    <style>
        .collab-header {
            background: linear-gradient(135deg, #000000 0%, #cc0000 100%);
            color: #fff;
            padding: 60px 0;
            margin-top: 130px;
            text-align: center;
        }
        
        .collab-header h1 {
            font-weight: bold;
            color: #fff !important;
            letter-spacing: 3px;
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .collab-header p {
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
            height: 100%;
        }
        
        .info-box h3 {
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #000;
            font-size: 24px;
        }
        
        .collab-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 25px;
        }
        
        .collab-icon {
            width: 50px;
            height: 50px;
            background: #000;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-right: 20px;
            flex-shrink: 0;
        }
        
        .collab-details h5 {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }
        
        .collab-details p {
            color: #666;
            font-size: 14px;
            margin: 0;
            line-height: 1.6;
        }
        
        .form-control {
            border: 2px solid #e3e6f0;
            border-radius: 5px;
            padding: 12px 20px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: #000;
            box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.1);
        }
        
        .submit-btn {
            background: #000;
            color: #fff;
            padding: 15px 50px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .submit-btn:hover {
            background: #cc0000;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(204, 0, 0, 0.3);
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-link {
            width: 45px;
            height: 45px;
            background: #000;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.3s;
            text-decoration: none;
        }
        
        .social-link:hover {
            background: #cc0000;
            color: #fff;
            transform: translateY(-3px);
        }
    </style>
@endsection

@section('content')
    <!-- Header Section -->
    <section class="collab-header">
        <div class="container">
            <h1>ARTISTS COLLABS</h1>
            <p>Join forces with BOLDROOTS and create something unique</p>
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
                            <span>Artists Collabs</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Collaboration Section -->
    <section style="padding: 60px 0;">
        <div class="container">
            <div class="row">
                <!-- Collaboration Information -->
                <div class="col-lg-4">
                    <div class="info-box">
                        <h3>WHY COLLABORATE?</h3>
                        
                        <div class="collab-item">
                            <div class="collab-icon">
                                <i class="fas fa-palette"></i>
                            </div>
                            <div class="collab-details">
                                <h5>Creative Freedom</h5>
                                <p>Express your unique artistic vision through our premium streetwear collections.</p>
                            </div>
                        </div>
                        
                        <div class="collab-item">
                            <div class="collab-icon">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div class="collab-details">
                                <h5>Global Exposure</h5>
                                <p>Reach our worldwide community of fashion enthusiasts and streetwear lovers.</p>
                            </div>
                        </div>
                        
                        <div class="collab-item">
                            <div class="collab-icon">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <div class="collab-details">
                                <h5>Fair Partnership</h5>
                                <p>We believe in transparent and mutually beneficial collaborations.</p>
                            </div>
                        </div>
                        
                        <div class="collab-item">
                            <div class="collab-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="collab-details">
                                <h5>Quality Products</h5>
                                <p>Your designs on premium quality materials and craftsmanship.</p>
                            </div>
                        </div>
                        
                        <h5 style="margin-top: 30px; margin-bottom: 15px; font-weight: bold;">Follow Us</h5>
                        <div class="social-links">
                            @php
                                $socialLinks = [
                                    'facebook' => \App\Models\SiteSetting::get('social_facebook'),
                                    'instagram' => \App\Models\SiteSetting::get('social_instagram'),
                                    'twitter' => \App\Models\SiteSetting::get('social_twitter'),
                                    'youtube' => \App\Models\SiteSetting::get('social_youtube'),
                                    'tiktok' => \App\Models\SiteSetting::get('social_tiktok'),
                                ];
                            @endphp
                            
                            @if($socialLinks['facebook'])
                                <a href="{{ $socialLinks['facebook'] }}" target="_blank" class="social-link">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            @endif
                            @if($socialLinks['instagram'])
                                <a href="{{ $socialLinks['instagram'] }}" target="_blank" class="social-link">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            @endif
                            @if($socialLinks['twitter'])
                                <a href="{{ $socialLinks['twitter'] }}" target="_blank" class="social-link">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            @endif
                            @if($socialLinks['youtube'])
                                <a href="{{ $socialLinks['youtube'] }}" target="_blank" class="social-link">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            @endif
                            @if($socialLinks['tiktok'])
                                <a href="{{ $socialLinks['tiktok'] }}" target="_blank" class="social-link">
                                    <i class="fab fa-tiktok"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Collaboration Form -->
                <div class="col-lg-8">
                    <div class="info-box">
                        <h3>SUBMIT YOUR COLLABORATION REQUEST</h3>
                        
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        <form action="{{ route('artists-collabs.send') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">First Name *</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                           id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                           id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="social_handle" class="form-label">Instagram or TikTok Handle *</label>
                                    <input type="text" class="form-control @error('social_handle') is-invalid @enderror" 
                                           id="social_handle" name="social_handle" value="{{ old('social_handle') }}" 
                                           placeholder="@yourusername" required>
                                    @error('social_handle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <label for="message" class="form-label">Tell us about yourself and your collaboration idea *</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" 
                                              id="message" name="message" rows="6" required 
                                              placeholder="Share your artistic background, style, and what kind of collaboration you have in mind...">{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="submit-btn">Submit Request</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
