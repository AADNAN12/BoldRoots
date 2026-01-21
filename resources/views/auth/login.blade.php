<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <title>Login | BOLDROOTS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="BOLDROOTS - Urban Streetwear Brand" name="description" />
    <meta content="BOLDROOTS" name="author" />
    <link rel="shortcut icon" href="{{ asset('images/BOLDROOTS-logo.avif') }}">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-red: #cc0000;
            --primary-black: #000000;
            --gradient-primary: linear-gradient(135deg, #000000 0%, #cc0000 100%);
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
            background: linear-gradient(180deg, #1a1a1a 0%, #2d2d2d 50%, #000000 100%);
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

        .bg-circle {
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(204, 0, 0, 0.2);
        }

        .bg-circle-1 {
            width: 600px;
            height: 600px;
            top: -200px;
            right: -200px;
        }

        .bg-circle-2 {
            width: 400px;
            height: 400px;
            bottom: -100px;
            left: -100px;
        }

        .bg-circle-3 {
            width: 300px;
            height: 300px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        /* Cloud decorations */
        .cloud {
            position: absolute;
            background: rgba(204, 0, 0, 0.1);
            border-radius: 100px;
            filter: blur(1px);
        }

        .cloud-1 {
            width: 200px;
            height: 60px;
            bottom: 10%;
            left: 5%;
            animation: float 8s ease-in-out infinite;
        }

        .cloud-2 {
            width: 150px;
            height: 45px;
            bottom: 15%;
            right: 10%;
            animation: float 10s ease-in-out infinite reverse;
        }

        .cloud-3 {
            width: 180px;
            height: 50px;
            top: 20%;
            left: 10%;
            animation: float 12s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        /* Login Card */
        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 500px;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 24px;
            padding: 40px;
            box-shadow: var(--shadow-xl);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Icon Header */
        .icon-header {
            display: flex;
            justify-content: center;
            margin-bottom: 24px;
        }

        .icon-box {
            width: 64px;
            height: 64px;
            background: #f3f4f6;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-sm);
        }

        .icon-box i {
            font-size: 28px;
            color: var(--primary-red);
        }

        /* Title */
        .login-title {
            text-align: center;
            margin-bottom: 8px;
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .login-subtitle {
            text-align: center;
            color: var(--text-muted);
            font-size: 14px;
            margin-bottom: 32px;
            line-height: 1.5;
        }

        /* Form Inputs */
        .input-group {
            position: relative;
            margin-bottom: 16px;
        }

        .input-group i.input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 18px;
            z-index: 2;
        }

        .input-group input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            background: #f9fafb;
            transition: all 0.3s ease;
            color: var(--text-dark);
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--primary-red);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(204, 0, 0, 0.1);
        }

        .input-group input::placeholder {
            color: #9ca3af;
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 4px;
            font-size: 18px;
        }

        .toggle-password:hover {
            color: var(--primary-red);
        }

        /* Forgot Password */
        .forgot-link {
            display: block;
            text-align: right;
            color: var(--text-muted);
            font-size: 13px;
            text-decoration: none;
            margin-bottom: 24px;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            color: var(--primary-red);
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            padding: 14px 24px;
            background: var(--primary-black);
            color: var(--white);
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            background: var(--primary-red);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(204, 0, 0, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            display: none;
        }

        .btn-submit.loading .spinner {
            display: block;
        }

        .btn-submit.loading span {
            display: none;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            margin: 24px 0;
            color: var(--text-muted);
            font-size: 13px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border-color);
        }

        .divider span {
            padding: 0 16px;
        }

        /* Social Buttons */
        .social-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .btn-social {
            flex: 1;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            background: var(--white);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-social:hover {
            border-color: var(--primary-red);
            background: rgba(204, 0, 0, 0.05);
        }

        .btn-social img {
            width: 24px;
            height: 24px;
        }

        .btn-social i {
            font-size: 24px;
        }

        .btn-social.google i { color: #ea4335; }
        .btn-social.facebook i { color: #1877f2; }
        .btn-social.apple i { color: #000000; }

        /* Register Link */
        .register-link {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: var(--text-muted);
        }

        .register-link a {
            color: var(--primary-red);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
            color: #990000;
        }

        /* Error Alert */
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 16px;
            color: #dc2626;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-error i {
            font-size: 18px;
        }

        .alert-error ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        /* Logo */
        .logo-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            margin-bottom: 20px;
        }

        .logo-link img {
            height: 60px;
        }

        .logo-link span {
            font-size: 22px;
            font-weight: 700;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-card {
                padding: 32px 24px;
            }

            .login-title {
                font-size: 20px;
            }

            .social-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <!-- Background Decorations -->
    <div class="bg-decoration">
        <div class="bg-circle bg-circle-1"></div>
        <div class="bg-circle bg-circle-2"></div>
        <div class="bg-circle bg-circle-3"></div>
        <div class="cloud cloud-1"></div>
        <div class="cloud cloud-2"></div>
        <div class="cloud cloud-3"></div>
    </div>

    <!-- Login Container -->
    <div class="login-container">
        <div class="login-card">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="logo-link">
                <img src="{{ asset('images/BOLDROOTS-logo.avif') }}" alt="BOOLD ROOTS">
            </a>

            <!-- Title -->
            <h1 class="login-title">LOGIN</h1>
            <p class="login-subtitle">Access your account to manage your orders and enjoy our services.</p>

            <!-- Error Messages -->
            @if ($errors->any())
            <div class="alert-error">
                <i class="bi bi-exclamation-circle"></i>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                @csrf
                
                <!-- Email Input -->
                <div class="input-group">
                    <i class="bi bi-envelope input-icon"></i>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        placeholder="Your Email" 
                        value="{{ old('email') }}"
                        required 
                        autofocus
                    >
                </div>

                <!-- Password Input -->
                <div class="input-group">
                    <i class="bi bi-lock input-icon"></i>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        placeholder="Password" 
                        required
                    >
                    <button type="button" class="toggle-password" id="togglePassword">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>

                <!-- Forgot Password -->
                <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit" id="submitBtn">
                    <span>LOGIN</span>
                    <div class="spinner"></div>
                </button>
            </form>
{{-- 
            <!-- Divider -->
            <div class="divider">
                <span>Ou continuer avec</span>
            </div>

            <!-- Social Buttons -->
            <div class="social-buttons">
                <button type="button" class="btn-social google">
                    <i class="bi bi-google"></i>
                </button>
                <button type="button" class="btn-social facebook">
                    <i class="bi bi-facebook"></i>
                </button>
                <button type="button" class="btn-social apple">
                    <i class="bi bi-apple"></i>
                </button>
            </div> --}}

            <!-- Register Link -->
            <p class="register-link">
                Don't have an account? <a href="{{ route('register') }}">Sign Up</a>
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const passwordField = document.getElementById('password');
            
            togglePassword.addEventListener('click', function() {
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                
                const icon = this.querySelector('i');
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-slash');
            });

            // Form submission with loading state
            const loginForm = document.getElementById('loginForm');
            const submitBtn = document.getElementById('submitBtn');
            
            loginForm.addEventListener('submit', function() {
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
            });

            // Input focus effects
            const inputs = document.querySelectorAll('.input-group input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.querySelector('.input-icon').style.color = '#cc0000';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.querySelector('.input-icon').style.color = '#6b7280';
                });
            });
        });
    </script>
</body>

</html>