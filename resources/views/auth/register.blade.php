<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <title>Sign Up | BOLDROOTS</title>
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
            overflow-x: hidden;
            padding: 20px 0;
        }

        /* Background decorations */
        .bg-decoration {
            position: fixed;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
            top: 0;
            left: 0;
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

        /* Register Card */
        .register-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 520px;
            padding: 20px;
        }

        .register-card {
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

        /* Title */
        .register-title {
            text-align: center;
            margin-bottom: 8px;
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .register-subtitle {
            text-align: center;
            color: var(--text-muted);
            font-size: 14px;
            margin-bottom: 28px;
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

        .input-group input,
        .input-group textarea {
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

        .input-group textarea {
            min-height: 80px;
            resize: vertical;
            padding-top: 14px;
        }

        .input-group textarea + .input-icon {
            top: 20px;
            transform: none;
        }

        .input-group input:focus,
        .input-group textarea:focus {
            outline: none;
            border-color: var(--primary-red);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(204, 0, 0, 0.1);
        }

        .input-group input::placeholder,
        .input-group textarea::placeholder {
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

        /* Row for side by side inputs */
        .input-row {
            display: flex;
            gap: 12px;
        }

        .input-row .input-group {
            flex: 1;
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
            margin-top: 8px;
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

        /* Login Link */
        .login-link {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: var(--text-muted);
        }

        .login-link a {
            color: var(--primary-red);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
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
            align-items: flex-start;
            gap: 8px;
        }

        .alert-error i {
            font-size: 18px;
            margin-top: 2px;
        }

        .alert-error ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .field-error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
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
            .register-card {
                padding: 32px 24px;
            }

            .register-title {
                font-size: 20px;
            }

            .input-row {
                flex-direction: column;
                gap: 0;
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

    <!-- Register Container -->
    <div class="register-container">
        <div class="register-card">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="logo-link">
                <img src="{{ asset('images/BOLDROOTS-logo.avif') }}" alt="BOLDROOTS">
            </a>

            <!-- Title -->
            <h1 class="register-title">CREATE ACCOUNT</h1>
            <p class="register-subtitle">Join BOLDROOTS and enjoy our premium urban streetwear collection.</p>

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

            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf
                
                <!-- Nom complet -->
                <div class="input-group">
                    <i class="bi bi-person input-icon"></i>
                    <input 
                        type="text" 
                        name="name" 
                        id="name"
                        placeholder="Full Name" 
                        value="{{ old('name') }}"
                        required
                    >
                </div>

                <!-- Email -->
                <div class="input-group">
                    <i class="bi bi-envelope input-icon"></i>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        placeholder="Your Email" 
                        value="{{ old('email') }}"
                        required
                    >
                </div>

                <!-- Téléphone et Ville -->
                <div class="input-row">
                    <div class="input-group">
                        <i class="bi bi-telephone input-icon"></i>
                        <input 
                            type="tel" 
                            name="telephone" 
                            id="telephone"
                            placeholder="Phone Number" 
                            value="{{ old('telephone') }}"
                            required
                        >
                    </div>
                    <div class="input-group">
                        <i class="bi bi-geo-alt input-icon"></i>
                        <input 
                            type="text" 
                            name="ville" 
                            id="ville"
                            placeholder="City" 
                            value="{{ old('ville') }}"
                            required
                        >
                    </div>
                </div>

                <!-- Adresse -->
                <div class="input-group">
                    <i class="bi bi-house input-icon"></i>
                    <textarea 
                        name="adresse" 
                        id="adresse"
                        placeholder="Full Address" 
                        required
                    >{{ old('adresse') }}</textarea>
                </div>

                <!-- Mot de passe -->
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

                <!-- Confirmation mot de passe -->
                <div class="input-group">
                    <i class="bi bi-lock-fill input-icon"></i>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation"
                        placeholder="Confirm Password" 
                        required
                    >
                    <button type="button" class="toggle-password" id="togglePasswordConfirm">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit" id="submitBtn">
                    <span>CREATE ACCOUNT</span>
                    <div class="spinner"></div>
                </button>
            </form>

            <!-- Login Link -->
            <p class="login-link">
                Already have an account? <a href="{{ route('login') }}">Login</a>
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

            // Toggle password confirmation visibility
            const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
            const passwordConfirmField = document.getElementById('password_confirmation');
            
            togglePasswordConfirm.addEventListener('click', function() {
                const type = passwordConfirmField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordConfirmField.setAttribute('type', type);
                
                const icon = this.querySelector('i');
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-slash');
            });

            // Form submission with loading state
            const registerForm = document.getElementById('registerForm');
            const submitBtn = document.getElementById('submitBtn');
            
            registerForm.addEventListener('submit', function() {
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
            });

            // Input focus effects
            const inputs = document.querySelectorAll('.input-group input, .input-group textarea');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    const icon = this.parentElement.querySelector('.input-icon');
                    if (icon) icon.style.color = '#cc0000';
                });
                
                input.addEventListener('blur', function() {
                    const icon = this.parentElement.querySelector('.input-icon');
                    if (icon) icon.style.color = '#6b7280';
                });
            });

            // Password match validation
            const password = document.getElementById('password');
            const passwordConfirm = document.getElementById('password_confirmation');

            passwordConfirm.addEventListener('input', function() {
                if (this.value && password.value !== this.value) {
                    this.style.borderColor = '#dc2626';
                } else if (this.value && password.value === this.value) {
                    this.style.borderColor = '#22c55e';
                }
            });
        });
    </script>
</body>

</html>