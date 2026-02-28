<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance - BOLDROOTS</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('images/BOLDROOTS-logo.avif') }}" rel="icon">
    @php
        // Get dynamic colors from SiteSettings
        $primaryColor = \App\Models\SiteSetting::get('primary_color', '#ff0000');
        $primaryTextColor = \App\Models\SiteSetting::get('primary_text_color', '#ffffff');
        $secondaryTextColor = \App\Models\SiteSetting::get('secondary_text_color', '#cccccc');
        $backgroundColor = \App\Models\SiteSetting::get('background_color', '#000000');
        $cursorNormal = \App\Models\SiteSetting::get('cursor_normal');
        $cursorHover = \App\Models\SiteSetting::get('cursor_hover');
    @endphp

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Dynamic cursors */
        @if($cursorNormal)
            * {
                cursor: url('{{ asset('storage/' . $cursorNormal) }}'), auto !important;
            }
        @endif

        @if($cursorHover)
            a, button, .btn, img, input, [role="button"], .clickable, .password-toggle, .action-btn, .submit-btn, .newsletter-submit, .modal-close {
                cursor: url('{{ asset('storage/' . $cursorHover) }}'), pointer !important;
            }
        @endif

        /* CSS Variables */
        :root {
            --primary-color: {{ $primaryColor }};
            --primary-text-color: {{ $primaryTextColor }};
            --secondary-text-color: {{ $secondaryTextColor }};
            --background-color: {{ $backgroundColor }};
            --primary-color-rgb: {{ hex2rgb($primaryColor)[0] }}, {{ hex2rgb($primaryColor)[1] }}, {{ hex2rgb($primaryColor)[2] }};
        }

        body {
            font-family: 'Arial', sans-serif;
            overflow: hidden;
            height: 100vh;
            position: relative;
        }

        .maintenance-container {
            position: relative;
            width: 100%;
            height: 100vh;
            background: linear-gradient(135deg, color-mix(in srgb, var(--background-color) 90%, black) 0%, color-mix(in srgb, var(--primary-color) 50%, var(--background-color)) 50%, color-mix(in srgb, var(--background-color) 90%, black) 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: var(--primary-text-color);
            overflow: hidden;
        }

        .maintenance-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;

            @if ($bgImage)
                background-image: url('{{ asset('storage/' . $bgImage) }}');
                background-size: cover;
                background-position: center;
                opacity: 0.3;
            @else
                background-image:
                    radial-gradient(circle at 20% 50%, rgba(var(--primary-color-rgb), 0.3) 0%, transparent 50%),
                    radial-gradient(circle at 80% 50%, rgba(var(--primary-color-rgb), 0.3) 0%, transparent 50%);
            @endif
            z-index: 1;
        }

        .content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 800px;
            padding: 20px;
        }

        .logo {
            width: 150px;
            height: auto;
            margin-bottom: 40px;
        }

        .title {
            font-size: 48px;
            font-weight: bold;
            letter-spacing: 3px;
            margin-bottom: 30px;
            text-shadow: 0 0 20px rgba(var(--primary-color-rgb), 0.3);
        }

        .message {
            font-size: 18px;
            margin-bottom: 50px;
            color: var(--secondary-text-color);
        }

        .countdown {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-bottom: 50px;
        }

        .countdown-item {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .countdown-number {
            font-size: 60px;
            font-weight: bold;
            color: var(--primary-text-color);
            text-shadow: 0 0 20px rgba(var(--primary-color-rgb), 0.4);
            margin-bottom: 10px;
        }

        .countdown-label {
            font-size: 14px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .password-section {
            margin-top: 30px;
        }

        .password-toggle {
            color: var(--primary-text-color);
            cursor: pointer;
            text-decoration: underline;
            font-size: 14px;
            margin-bottom: 20px;
            display: inline-block;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--primary-color);
        }

        .password-form {
            display: none;
        }

        .password-form.active {
            display: block;
        }

        .password-input-group {
            display: flex;
            justify-content: center;
            gap: 10px;
            max-width: 400px;
            margin: 0 auto;
        }

        .password-input {
            flex: 1;
            padding: 15px 20px;
            background: color-mix(in srgb, var(--background-color) 50%, transparent);
            border: 2px solid rgba(var(--primary-color-rgb), 0.3);
            color: var(--primary-text-color);
            font-size: 16px;
            border-radius: 5px;
            outline: none;
            transition: all 0.3s;
        }

        .password-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 20px rgba(var(--primary-color-rgb), 0.1);
        }

        .password-input::placeholder {
            color: color-mix(in srgb, var(--secondary-text-color) 60%, transparent);
        }

        .submit-btn {
            padding: 15px 30px;
            background: var(--primary-color);
            border: none;
            color: var(--primary-text-color);
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .submit-btn:hover {
            background: color-mix(in srgb, var(--primary-color) 80%, black);
            box-shadow: 0 0 20px rgba(var(--primary-color-rgb), 0.2);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            max-width: 400px;
            margin: 0 auto 20px;
        }

        .alert-error {
            background: rgba(var(--primary-color-rgb), 0.2);
            border: 1px solid rgba(var(--primary-color-rgb), 0.5);
            color: color-mix(in srgb, var(--primary-color) 70%, var(--primary-text-color));
        }

        .alert-success {
            background: rgba(0, 255, 0, 0.2);
            border: 1px solid rgba(0, 255, 0, 0.5);
            color: color-mix(in srgb, #00ff00 70%, var(--primary-text-color));
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 40px;
        }

        .action-btn {
            padding: 15px 40px;
            background: color-mix(in srgb, var(--background-color) 50%, transparent);
            border: 2px solid var(--primary-color);
            color: white;
            font-size: 16px;
            font-weight: bold;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .action-btn:hover {
            background: var(--primary-color);
            color: white;
            box-shadow: 0 0 20px rgba(var(--primary-color-rgb), 0.2);
            transform: translateY(-2px);
        }

        .action-btn i {
            font-size: 20px;
        }

        /* Modal Newsletter */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: linear-gradient(135deg, color-mix(in srgb, var(--background-color) 90%, black) 0%, color-mix(in srgb, var(--primary-color) 50%, var(--background-color)) 100%);
            padding: 40px;
            border-radius: 10px;
            border: 2px solid rgba(var(--primary-color-rgb), 0.1);
            max-width: 500px;
            width: 90%;
            position: relative;
            box-shadow: 0 0 40px rgba(var(--primary-color-rgb), 0.1);
        }

        .modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            color: var(--primary-text-color);
            font-size: 30px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .modal-close:hover {
            color: #ff0000;
            transform: rotate(90deg);
        }

        .modal-title {
            font-size: 28px;
            font-weight: bold;
            color: var(--primary-text-color);
            margin-bottom: 20px;
            text-align: center;
        }

        .modal-description {
            color: #ccc;
            margin-bottom: 30px;
            text-align: center;
        }

        .newsletter-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .newsletter-input {
            padding: 15px 20px;
            background: color-mix(in srgb, var(--background-color) 50%, transparent);
            border: 2px solid rgba(var(--primary-color-rgb), 0.3);
            color: var(--primary-text-color);
            font-size: 16px;
            border-radius: 5px;
            outline: none;
            transition: all 0.3s;
        }

        .newsletter-input:focus {
            border-color: var(--primary-color);
        }

        .newsletter-input::placeholder {
            color: color-mix(in srgb, var(--secondary-text-color) 60%, transparent);
        }

        .newsletter-submit {
            padding: 15px 30px;
            background: var(--primary-color);
            border: none;
            color: #ffffffff;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .newsletter-submit:hover {
            background: color-mix(in srgb, var(--primary-color) 80%, black);
            color:white;
            box-shadow: 0 0 20px rgba(var(--primary-color-rgb), 0.2);
        }

        .newsletter-message {
            padding: 15px 20px;
            border-radius: 5px;
            margin-top: 15px;
            text-align: center;
            display: none;
        }

        .newsletter-message.success {
            background: rgba(0, 255, 0, 0.2);
            border: 1px solid rgba(0, 255, 0, 0.5);
            color: color-mix(in srgb, #00ff00 70%, var(--primary-text-color));
            display: block;
        }

        .newsletter-message.error {
            background: rgba(var(--primary-color-rgb), 0.2);
            border: 1px solid rgba(var(--primary-color-rgb), 0.5);
            color: color-mix(in srgb, var(--primary-color) 70%, var(--primary-text-color));
            display: block;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .title {
                font-size: 36px;
            }

            .countdown {
                gap: 20px;
            }

            .countdown-number {
                font-size: 48px;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .modal-content {
                padding: 30px 20px;
            }
        }
    </style>

    @php
        // Helper function to convert hex to RGB
        function hex2rgb($hex) {
            $hex = str_replace("#", "", $hex);
            
            if(strlen($hex) == 3) {
                $r = hexdec(substr($hex,0,1).substr($hex,0,1));
                $g = hexdec(substr($hex,1,1).substr($hex,1,1));
                $b = hexdec(substr($hex,2,1).substr($hex,2,1));
            } else {
                $r = hexdec(substr($hex,0,2));
                $g = hexdec(substr($hex,2,2));
                $b = hexdec(substr($hex,4,2));
            }
            
            return array($r, $g, $b);
        }
    @endphp
</head>

<body>
    <div class="maintenance-container">
        <div class="content">
            @php
                $siteLogo = \App\Models\SiteSetting::get('site_logo', 'images/BOLDROOTS-logo.avif');
                $logoUrl = str_starts_with($siteLogo, 'images/') ? asset($siteLogo) : asset('storage/' . $siteLogo);
            @endphp
            <img src="{{ $logoUrl }}" alt="BOLDROOTS" class="logo">

            <h1 class="title">{{ $title }}</h1>

            <p class="message">{{ $message }}</p>

            <div class="countdown">
                <div class="countdown-item">
                    <div class="countdown-number" id="days">∞</div>
                    <div class="countdown-label">DAYS</div>
                </div>
                <div class="countdown-item">
                    <div class="countdown-number" id="hours">∞</div>
                    <div class="countdown-label">HOURS</div>
                </div>
                <div class="countdown-item">
                    <div class="countdown-number" id="mins">∞</div>
                    <div class="countdown-label">MINS</div>
                </div>
                <div class="countdown-item">
                    <div class="countdown-number" id="secs">∞</div>
                    <div class="countdown-label">SECS</div>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="action-buttons">
                @if ($whatsappUrl)
                    <a href="{{ $whatsappUrl }}" target="_blank" class="action-btn">
                        <i class="fab fa-whatsapp"></i>
                        WhatsApp
                    </a>
                @endif

                <button class="action-btn" onclick="openNewsletterModal()">
                    <i class="fas fa-envelope"></i>
                    Newsletter
                </button>
            </div>

            <div class="password-section">
                <div class="password-toggle" onclick="togglePasswordForm()">
                    Enter your mofuggin password
                </div>

                <div class="password-form" id="passwordForm">
                    @if (session('error'))
                        <div class="alert alert-error">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('maintenance.verify') }}" method="POST">
                        @csrf
                        <div class="password-input-group">
                            <input type="password" name="password" class="password-input" placeholder="Enter password"
                                required autofocus>
                            <button type="submit" class="submit-btn">
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Newsletter -->
    <div class="modal-overlay" id="newsletterModal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeNewsletterModal()">&times;</button>
            <h2 class="modal-title">Subscribe to our Newsletter</h2>
            <p class="modal-description">Stay informed about our news and the site reopening!</p>

            <form class="newsletter-form" id="newsletterForm" onsubmit="subscribeNewsletter(event)">
                <input type="email" class="newsletter-input" id="newsletterEmail" placeholder="Your email address"
                    required>
                <button type="submit" class="newsletter-submit">
                    Subscribe
                </button>
            </form>

            <div class="newsletter-message" id="newsletterMessage"></div>
        </div>
    </div>

    <script>
        function togglePasswordForm() {
            const form = document.getElementById('passwordForm');
            form.classList.toggle('active');
        }

        // If there is an error, display the form automatically
        @if (session('error'))
            document.getElementById('passwordForm').classList.add('active');
        @endif

        // Newsletter modal management
        function openNewsletterModal() {
            document.getElementById('newsletterModal').classList.add('active');
        }

        function closeNewsletterModal() {
            document.getElementById('newsletterModal').classList.remove('active');
            document.getElementById('newsletterForm').reset();
            document.getElementById('newsletterMessage').className = 'newsletter-message';
            document.getElementById('newsletterMessage').textContent = '';
        }

        // Close modal by clicking outside
        document.getElementById('newsletterModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeNewsletterModal();
            }
        });

        // Newsletter subscription
        async function subscribeNewsletter(event) {
            event.preventDefault();

            const email = document.getElementById('newsletterEmail').value;
            const messageDiv = document.getElementById('newsletterMessage');
            const submitBtn = event.target.querySelector('button[type="submit"]');

            // Disable button during submission
            submitBtn.disabled = true;
            submitBtn.textContent = 'Subscribing...';

            try {
                console.log('Sending request to:', '{{ route('newsletter.subscribe') }}');
                console.log('Email:', email);

                const response = await fetch('{{ route('newsletter.subscribe') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: email
                    })
                });

                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);

                const data = await response.json();
                console.log('Response data:', data);

                if (response.ok && data.success) {
                    messageDiv.className = 'newsletter-message success';
                    messageDiv.textContent = data.message;
                    document.getElementById('newsletterForm').reset();

                    // Close modal after 3 seconds
                    setTimeout(() => {
                        closeNewsletterModal();
                    }, 3000);
                } else {
                    messageDiv.className = 'newsletter-message error';
                    messageDiv.textContent = data.message || 'An error occurred.';
                }
            } catch (error) {
                console.error('Newsletter subscription error:', error);
                messageDiv.className = 'newsletter-message error';
                messageDiv.textContent = 'An error occurred. Please try again. (Error: ' + error.message + ')';
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Subscribe';
            }
        }

        // Dynamic countdown
        @if ($endDate)
            const endDate = new Date('{{ $endDate }}').getTime();

            function updateCountdown() {
                const now = new Date().getTime();
                const distance = endDate - now;

                if (distance < 0) {
                    // Countdown is finished
                    document.getElementById('days').textContent = '0';
                    document.getElementById('hours').textContent = '0';
                    document.getElementById('mins').textContent = '0';
                    document.getElementById('secs').textContent = '0';
                    return;
                }

                // Calculate remaining time
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Display with leading zero if necessary
                document.getElementById('days').textContent = days.toString().padStart(2, '0');
                document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
                document.getElementById('mins').textContent = minutes.toString().padStart(2, '0');
                document.getElementById('secs').textContent = seconds.toString().padStart(2, '0');
            }

            // Update countdown every second
            updateCountdown();
            setInterval(updateCountdown, 1000);
        @endif
    </script>
</body>

</html>