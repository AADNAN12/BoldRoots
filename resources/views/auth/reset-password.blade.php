<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <title>Reset Password | {{ env("APP_NAME") }}</title>
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
            max-width: 50%;
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

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: var(--white);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 3px color-mix(in srgb, var(--primary-color) 10%, transparent);
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 18px;
        }

        .input-group .form-input {
            padding-left: 45px;
        }

        /* Password Strength */
        .password-strength {
            margin-top: 8px;
            height: 4px;
            border-radius: 2px;
            background: #e9ecef;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .strength-weak {
            width: 33%;
            background: #dc3545;
        }

        .strength-medium {
            width: 66%;
            background: #ffc107;
        }

        .strength-strong {
            width: 100%;
            background: #28a745;
        }

        /* Button Styles */
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--gradient-primary);
            color: var(--white);
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top: 2px solid var(--white);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 10px;
        }

        .btn-submit.loading .spinner {
            display: inline-block;
        }

        .btn-submit.loading span {
            display: none;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Back to Login */
        .back-to-login {
            text-align: center;
            margin-top: 25px;
        }

        .back-to-login a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .back-to-login a:hover {
            color: var(--primary-color);
        }

        /* Alert Styles */
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
                <h1>Reset Password</h1>
                <p>Create a new secure password</p>
            </div>

            <!-- Reset Password Form -->
            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <i class="bi bi-envelope input-icon"></i>
                        <input type="email" name="email" id="email" class="form-input"
                            placeholder="Enter your email address" value="{{ old('email', $request->email) }}" required
                            autocomplete="username">
                    </div>
                    @if ($errors->has('email'))
                        <div class="alert alert-error" style="margin-top: 8px;">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">New Password</label>
                    <div class="input-group">
                        <i class="bi bi-lock input-icon"></i>
                        <input type="password" name="password" id="password" class="form-input"
                            placeholder="Enter your new password" required autocomplete="new-password">
                    </div>
                    <div class="password-strength">
                        <div class="password-strength-bar" id="strengthBar"></div>
                    </div>
                    @if ($errors->has('password'))
                        <div class="alert alert-error" style="margin-top: 8px;">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <i class="bi bi-lock-fill input-icon"></i>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-input" placeholder="Confirm your new password" required
                            autocomplete="new-password">
                    </div>
                    @if ($errors->has('password_confirmation'))
                        <div class="alert alert-error" style="margin-top: 8px;">
                            {{ $errors->first('password_confirmation') }}
                        </div>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit" id="submitBtn">
                    <span>Reset Password</span>
                    <div class="spinner"></div>
                </button>
            </form>

            <!-- Back to Login -->
            <div class="back-to-login">
                <a href="{{ route('login') }}">Back to Login</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const submitBtn = document.getElementById('submitBtn');
            const form = document.querySelector('form');
            const passwordInput = document.getElementById('password');
            const strengthBar = document.getElementById('strengthBar');

            // Password strength checker
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;

                if (password.length >= 8) strength++;
                if (password.match(/[a-z]+/)) strength++;
                if (password.match(/[A-Z]+/)) strength++;
                if (password.match(/[0-9]+/)) strength++;
                if (password.match(/[^a-zA-Z0-9]+/)) strength++;

                strengthBar.className = 'password-strength-bar';
                if (strength <= 2) {
                    strengthBar.classList.add('strength-weak');
                } else if (strength <= 3) {
                    strengthBar.classList.add('strength-medium');
                } else {
                    strengthBar.classList.add('strength-strong');
                }
            });

            form.addEventListener('submit', function() {
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
            });
        });
    </script>
</body>

</html>
