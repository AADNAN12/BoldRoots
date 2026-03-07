@extends('front-office.layouts.app')

@section('title', 'Contact Us ')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css">
    <style>
        .contact-header {
            background: linear-gradient(135deg, var(--background-color) 0%, var(--primary-color) 100%);
            color: var(--primary-text-color);
            padding: 60px 0;
            margin-top: 130px;
            text-align: center;
        }
        
        .contact-header h1 {
            font-weight: bold;
            color: #fff !important;
            letter-spacing: 3px;
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .contact-header p {
            font-size: 18px;
            color: var(--secondary-text-color) !important;
            letter-spacing: 1px;
            opacity: 0.9;
        }
        
        .info-box {
            background: #fff;
            border: 1px solid var(--secondary-text-color);
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
            border-bottom: 3px solid var(--secondary-text-color);
            font-size: 24px;
        }
        
        .contact-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 25px;
        }
        
        .contact-icon {
            width: 50px;
            height: 50px;
            background: #fff;
            color: var(--primary-text-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-right: 20px;
            flex-shrink: 0;
        }
        
        .contact-details h5 {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }
        
        .contact-details p {
            color: var(--secondary-text-color);
            font-size: 14px;
            margin: 0;
            line-height: 1.6;
        }
        
        .form-control {
            border: 2px solid var(--secondary-text-color);
            border-radius: 5px;
            padding: 12px 20px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--secondary-text-color);
            box-shadow: 0 0 0 0.2rem color-mix(in srgb, var(--secondary-text-color) 10%, transparent);
        }
        
        .submit-btn {
            background: var(--primary-color);
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
            background: color-mix(in srgb, var(--primary-color) 80%, white);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px color-mix(in srgb, var(--primary-color) 30%, transparent);
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-link {
            width: 45px;
            height: 45px;
            background: var(--background-color);
            color: var(--primary-text-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.3s;
            text-decoration: none;
        }
        
        .social-link:hover {
            background: var(--primary-color);
            color: #fff;
            transform: translateY(-3px);
        }
    </style>
@endsection

@section('content')
    <!-- Header Section -->
    <section class="contact-header">
        <div class="container">
            <h1>CONTACT US</h1>
            <p>We'd love to hear from you</p>
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
                            <span>Contact</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section style="padding: 60px 0;">
        <div class="container">
            <div class="row">
                <!-- Contact Information -->
                <div class="col-lg-4">
                    <div class="info-box">
                        <h3>GET IN TOUCH</h3>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-details">
                                <h5>Address</h5>
                                <p>{{ $companyInfo->address ?? 'BOLDROOTS HQ, Fashion District' }}</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-details">
                                <h5>Phone</h5>
                                <p>{{ $companyInfo->phone ?? '+1 234 567 890' }}</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-details">
                                <h5>Email</h5>
                                <p>{{ $companyInfo->email ?? 'contact@boldroots.com' }}</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="contact-details">
                                <h5>Working Hours</h5>
                                <p>Mon - Fri: 9:00 AM - 6:00 PM<br>Sat: 10:00 AM - 4:00 PM<br>Sun: Closed</p>
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
                
                <!-- Contact Form -->
                <div class="col-lg-8">
                    <div class="info-box">
                        <h3>SEND US A MESSAGE</h3>
                        
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        <form action="{{ route('contact.send') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Your Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Your Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}" maxlength="20" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode == 0" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="0123456789">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="subject" class="form-label">Subject *</label>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                           id="subject" name="subject" value="{{ old('subject') }}" required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <label for="message" class="form-label">Message *</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" 
                                              id="message" name="message" rows="6" required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="submit-btn">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
