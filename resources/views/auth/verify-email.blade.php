<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <title>Verify Email | {{ env("APP_NAME") }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{ env("APP_NAME") }} - Urban Streetwear Brand" name="description" />
    <meta content="{{ env("APP_NAME") }}" name="author" />
    <link rel="shortcut icon" href="{{ asset('images/BOLDROOTS-logo.avif') }}">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: {{ \App\Models\SiteSetting::get('primary_color', '#ff0000') }};
            --primary-text-color: {{ \App\Models\SiteSetting::get('primary_text_color', '#ffffff') }};
            --secondary-text-color: {{ \App\Models\SiteSetting::get('secondary_text_color', '#000000') }};
            --background-color: {{ \App\Models\SiteSetting::get('background_color', '#000000') }};
            --gradient-primary: linear-gradient(135deg, var(--background-color) 0%, var(--primary-color) 100%);
            --bg-light: #f0f4f8;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
            --white: #ffffff;
            --border-color: #e5e7eb;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(180deg, color-mix(in srgb, var(--background-color) 90%, #1a1a1a) 0%, color-mix(in srgb, var(--background-color) 80%, #2d2d2d) 50%, var(--background-color) 100%);
            position: relative;
        }

        /* Background decorations */
        .bg-decoration {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .bg-decoration .circle {
            position: absolute;
            border-radius: 50%;
            background: var(--gradient-primary);
            opacity: 0.1;
        }

        .bg-decoration .circle:nth-child(1) {
            width: 300px;
            height: 300px;
            top: -150px;
            left: -150px;
        }

        .bg-decoration .circle:nth-child(2) {
            width: 200px;
            height: 200px;
            bottom: -100px;
            right: -100px;
        }

        .bg-decoration .circle:nth-child(3) {
            width: 150px;
            height: 150px;
            top: 50%;
            left: 10%;
            opacity: 0.05;
        }

        /* Login Container */
        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: var(--shadow-xl);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-section img {
            height: 50px;
            margin-bottom: 10px;
        }

        .logo-section h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
        }

        .logo-section p {
            color: var(--text-muted);
            font-size: 14px;
            margin-top: 8px;
        }

        /* Success Message */
        .success-message {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 25px;
            font-size: 14px;
            text-align: center;
        }

        .success-message i {
            font-size: 24px;
            margin-bottom: 10px;
            color: #28a745;
        }

        /* Button Styles */
        .btn {
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin: 0 8px;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: var(--white);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: transparent;
            color: var(--text-muted);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                padding: 15px;
            }
            
            .login-card {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>
    <!-- Background Decorations -->
    <div class="bg-decoration">
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
    </div>

    <!-- Login Container -->
    <div class="login-container">
        <div class="login-card">
            <!-- Logo Section -->
            <div class="logo-section">
                @php
                    $siteLogo = \App\Models\SiteSetting::get('site_logo', 'images/BOLDROOTS-logo.avif');
                    $logoUrl = str_starts_with($siteLogo, 'images/') ? asset($siteLogo) : asset('storage/' . $siteLogo);
                @endphp
                <img src="{{ $logoUrl }}" alt="{{ env("APP_NAME") }}">
                <h1>Verify Email</h1>
                <p>Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.</p>
            </div>

            <!-- Success Message -->
            @if (session('status') == 'verification-link-sent')
                <div class="success-message">
                    <i class="bi bi-check-circle-fill"></i>
                    <p>Please check your inbox and click the link to activate your account.</p>
                </div>
            @endif

            <!-- Action Buttons -->
            <div style="text-align: center; margin-top: 30px;">
                <!-- Resend Verification Email -->
                <form method="POST" action="{{ route('verification.send') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-envelope"></i> Resend Verification Email
                    </button>
                </form>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-secondary">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading states to buttons
            const buttons = document.querySelectorAll('button[type="submit"]');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    const originalContent = this.innerHTML;
                    this.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Traitement...';
                    this.disabled = true;
                    
                    setTimeout(() => {
                        this.innerHTML = originalContent;
                        this.disabled = false;
                    }, 2000);
                });
            });
        });
    </script>
</body>
</html>
