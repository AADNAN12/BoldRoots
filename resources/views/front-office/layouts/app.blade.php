<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOLDROOTS - @yield('title')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('images/BOLDROOTS-logo.avif') }}" rel="icon">
    @yield('head')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            overflow-x: hidden;
            
        }

        @php
            $cursorNormal = \App\Models\SiteSetting::get('cursor_normal');
            $cursorHover = \App\Models\SiteSetting::get('cursor_hover');
        @endphp

        @if($cursorNormal)
            * {
                cursor: url('{{ asset('storage/' . $cursorNormal) }}'), auto !important;
            }
        @endif

        @if($cursorHover)
            a, button, .btn,img, input, [role="button"], .clickable {
                cursor: url('{{ asset('storage/' . $cursorHover) }}'), pointer !important;
            }
        @endif

        .menu-toggle {
            color: #000000ff !important;
        }

        .header-right a {
            color: #000000ff !important;
        }

        .header-right a:hover {
            color: #ff0000 !important;
        }
    </style>
    @yield('styles')
</head>

<body>
    @php
        $audioEnabled = \App\Models\SiteSetting::get('background_audio_enabled', '0');
        $audioPath = \App\Models\SiteSetting::get('background_audio');
        $audioVolume = \App\Models\SiteSetting::get('background_audio_volume', '50');
    @endphp

    <!-- Audio d'arrière-plan -->
    @if($audioEnabled == '1' && $audioPath)
        <audio id="backgroundAudio" autoplay loop style="display: none;">
            <source src="{{ asset('storage/' . $audioPath) }}" type="audio/mpeg">
            Votre navigateur ne supporte pas l'élément audio.
        </audio>
    @endif

    @include('front-office.layouts.header')

    @yield('content')

    @include('front-office.layouts.footer')

    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    @if($audioEnabled == '1' && $audioPath)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const audio = document.getElementById('backgroundAudio');
            
            if (audio) {
                // Définir le volume (0.0 à 1.0)
                audio.volume = {{ $audioVolume / 100 }};
                
                // Gérer l'autoplay (certains navigateurs le bloquent)
                const playPromise = audio.play();
                
                if (playPromise !== undefined) {
                    playPromise.then(() => {
                        console.log('Audio démarré automatiquement');
                    }).catch(error => {
                        console.log('Autoplay bloqué. L\'audio démarrera au premier mouvement de souris.');
                        
                        // Si l'autoplay est bloqué, démarrer l'audio au premier mouvement de souris
                        function startAudio() {
                            audio.play().then(() => {
                                console.log('Audio démarré après mouvement de souris');
                                // Retirer les écouteurs une fois l'audio démarré
                                document.removeEventListener('mousemove', startAudio);
                                document.removeEventListener('click', startAudio);
                            }).catch(err => {
                                console.error('Erreur lors du démarrage de l\'audio:', err);
                            });
                        }
                        
                        // Écouter le mouvement de souris ET le clic (double sécurité)
                        document.addEventListener('mousemove', startAudio, { once: true });
                        document.addEventListener('click', startAudio, { once: true });
                    });
                }
            }
        });
    </script>
    @endif

    @yield('scripts')
</body>

</html>
