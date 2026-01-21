@extends('admin.layouts.master')

@section('title', 'Paramètres du Site')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1"><i class="mdi mdi-cog-outline me-2"></i>Paramètres du Site</h2>
                    <p class="text-muted mb-0">Gérez les paramètres et configurations de votre site</p>
                </div>
                <a href="{{ route('admin.welcome') }}" class="btn btn-outline-secondary">
                    <i class="mdi mdi-arrow-left me-1"></i> Retour
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-3 col-md-4 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-2">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active show d-flex align-items-center" id="v-pills-general-tab" data-bs-toggle="pill" href="#v-pills-general" role="tab" aria-controls="v-pills-general"
                            aria-selected="true">
                            <i class="mdi mdi-cog-outline me-2 fs-5"></i>
                            <span>Paramètres Généraux</span>
                        </a>
                        <a class="nav-link d-flex align-items-center" id="v-pills-social-tab" data-bs-toggle="pill" href="#v-pills-social" role="tab" aria-controls="v-pills-social"
                            aria-selected="false">
                            <i class="mdi mdi-share-variant me-2 fs-5"></i>
                            <span>Réseaux Sociaux</span>
                        </a>
                        <a class="nav-link d-flex align-items-center" id="v-pills-maintenance-tab" data-bs-toggle="pill" href="#v-pills-maintenance" role="tab" aria-controls="v-pills-maintenance"
                            aria-selected="false">
                            <i class="mdi mdi-wrench me-2 fs-5"></i>
                            <span>Mode Maintenance</span>
                        </a>
                        <a class="nav-link d-flex align-items-center" id="v-pills-audio-tab" data-bs-toggle="pill" href="#v-pills-audio" role="tab" aria-controls="v-pills-audio"
                            aria-selected="false">
                            <i class="mdi mdi-music me-2 fs-5"></i>
                            <span>Audio d'Arrière-plan</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9 col-md-8">
            <div class="tab-content" id="v-pills-tabContent">
                <!-- TAB 1: Paramètres Généraux -->
                <div class="tab-pane fade active show" id="v-pills-general" role="tabpanel" aria-labelledby="v-pills-general-tab">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary bg-opacity-10 rounded p-2 me-2">
                                        <i class="mdi mdi-format-align-top text-primary fs-4"></i>
                                    </div>
                                    <h5 class="mb-0">Bandeau Supérieur (Top Bar)</h5>
                                </div>
                        
                                <div class="mb-3">
                                    <label for="top_bar_text" class="form-label">Texte du Bandeau</label>
                                    <input type="text" class="form-control @error('top_bar_text') is-invalid @enderror" 
                                           id="top_bar_text" name="top_bar_text" 
                                           value="{{ old('top_bar_text', $settings['top_bar_text']->value ?? 'DEVOTE YOURSELF TO THE BOLD ROOTS') }}">
                                    @error('top_bar_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="top_bar_bg_color" class="form-label">Couleur de Fond</label>
                                    <input type="color" class="form-control form-control-color @error('top_bar_bg_color') is-invalid @enderror" 
                                           id="top_bar_bg_color" name="top_bar_bg_color" 
                                           value="{{ old('top_bar_bg_color', $settings['top_bar_bg_color']->value ?? '#000000') }}">
                                    @error('top_bar_bg_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Choisissez une couleur de fond pour le bandeau supérieur</small>
                                </div>

                                <div class="mb-3">
                                    <label for="top_bar_bg_image" class="form-label">Image de Fond (Optionnel)</label>
                                    <input type="file" class="form-control @error('top_bar_bg_image') is-invalid @enderror" 
                                           id="top_bar_bg_image" name="top_bar_bg_image" accept="image/*">
                                    @error('top_bar_bg_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Format: JPG, PNG, GIF (Max: 2MB). L'image remplacera la couleur de fond.</small>
                                    
                                    @if(isset($settings['top_bar_bg_image']) && $settings['top_bar_bg_image']->value)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $settings['top_bar_bg_image']->value) }}" 
                                                 alt="Top Bar Background" class="img-thumbnail" style="max-height: 100px;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-success bg-opacity-10 rounded p-2 me-2">
                                        <i class="mdi mdi-image-area text-success fs-4"></i>
                                    </div>
                                    <h5 class="mb-0">Section Hero (Arrière-plan Principal)</h5>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="hero_bg_image" class="form-label">Image d'Arrière-plan</label>
                                    <input type="file" class="form-control @error('hero_bg_image') is-invalid @enderror" 
                                           id="hero_bg_image" name="hero_bg_image" accept="image/*">
                                    @error('hero_bg_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Format: JPG, PNG, GIF (Max: 2MB). Image principale de la page d'accueil.</small>
                                    
                                    @if(isset($settings['hero_bg_image']) && $settings['hero_bg_image']->value)
                                        <div class="mt-2">
                                            @if(str_starts_with($settings['hero_bg_image']->value, 'images/'))
                                                <img src="{{ asset($settings['hero_bg_image']->value) }}" 
                                                     alt="Hero Background" class="img-thumbnail" style="max-height: 200px;">
                                            @else
                                                <img src="{{ asset('storage/' . $settings['hero_bg_image']->value) }}" 
                                                     alt="Hero Background" class="img-thumbnail" style="max-height: 200px;">
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        
                        
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-info bg-opacity-10 rounded p-2 me-2">
                                        <i class="mdi mdi-cursor-default text-info fs-4"></i>
                                    </div>
                                    <h5 class="mb-0">Curseurs Personnalisés</h5>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="cursor_normal" class="form-label">Curseur Normal</label>
                                    <input type="file" class="form-control @error('cursor_normal') is-invalid @enderror" 
                                           id="cursor_normal" name="cursor_normal" accept="image/png,image/webp,image/x-icon,.cur">
                                    @error('cursor_normal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Format: PNG, ICO, CUR (Max: 1MB, Taille recommandée: 32x32px)</small>
                                    
                                    @if(isset($settings['cursor_normal']) && $settings['cursor_normal']->value)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $settings['cursor_normal']->value) }}" 
                                                 alt="Cursor Normal" class="img-thumbnail" style="max-height: 50px; background: #f0f0f0;">
                                            <small class="d-block text-muted mt-1">Curseur actuel</small>
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="cursor_hover" class="form-label">Curseur Hover (au survol des liens/boutons)</label>
                                    <input type="file" class="form-control @error('cursor_hover') is-invalid @enderror" 
                                           id="cursor_hover" name="cursor_hover" accept="image/png,image/webp,image/x-icon,.cur">
                                    <small class="text-muted">Format: PNG, ICO, CUR, WEBP (Max: 1MB, Taille recommandée: 32x32px)</small>
                                    @error('cursor_hover')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Format: PNG, ICO, CUR (Max: 1MB, Taille recommandée: 32x32px)</small>
                                    
                                    @if(isset($settings['cursor_hover']) && $settings['cursor_hover']->value)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $settings['cursor_hover']->value) }}" 
                                                 alt="Cursor Hover" class="img-thumbnail" style="max-height: 50px; background: #f0f0f0;">
                                            <small class="d-block text-muted mt-1">Curseur actuel</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 mb-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="mdi mdi-content-save me-2"></i> Enregistrer les Paramètres Généraux
                            </button>
                        </div>
                    </form>
                </div>

                <!-- TAB 2: Réseaux Sociaux -->
                <div class="tab-pane fade" id="v-pills-social" role="tabpanel" aria-labelledby="v-pills-social-tab">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-info bg-opacity-10 rounded p-2 me-2">
                                        <i class="mdi mdi-share-variant text-info fs-4"></i>
                                    </div>
                                    <h5 class="mb-0">Réseaux Sociaux</h5>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="social_facebook" class="form-label">
                                        <i class="mdi mdi-facebook"></i> Facebook
                                    </label>
                                    <input type="url" class="form-control @error('social_facebook') is-invalid @enderror" 
                                           id="social_facebook" name="social_facebook" 
                                           value="{{ old('social_facebook', $settings['social_facebook']->value ?? '') }}"
                                           placeholder="https://facebook.com/votre-page">
                                    @error('social_facebook')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="social_instagram" class="form-label">
                                        <i class="mdi mdi-instagram"></i> Instagram
                                    </label>
                                    <input type="url" class="form-control @error('social_instagram') is-invalid @enderror" 
                                           id="social_instagram" name="social_instagram" 
                                           value="{{ old('social_instagram', $settings['social_instagram']->value ?? '') }}"
                                           placeholder="https://instagram.com/votre-compte">
                                    @error('social_instagram')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="social_twitter" class="form-label">
                                        <i class="mdi mdi-twitter"></i> Twitter / X
                                    </label>
                                    <input type="url" class="form-control @error('social_twitter') is-invalid @enderror" 
                                           id="social_twitter" name="social_twitter" 
                                           value="{{ old('social_twitter', $settings['social_twitter']->value ?? '') }}"
                                           placeholder="https://twitter.com/votre-compte">
                                    @error('social_twitter')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="social_youtube" class="form-label">
                                        <i class="mdi mdi-youtube"></i> YouTube
                                    </label>
                                    <input type="url" class="form-control @error('social_youtube') is-invalid @enderror" 
                                           id="social_youtube" name="social_youtube" 
                                           value="{{ old('social_youtube', $settings['social_youtube']->value ?? '') }}"
                                           placeholder="https://youtube.com/@votre-chaine">
                                    @error('social_youtube')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="social_tiktok" class="form-label">
                                        <i class="mdi mdi-music-note"></i> TikTok
                                    </label>
                                    <input type="url" class="form-control @error('social_tiktok') is-invalid @enderror" 
                                           id="social_tiktok" name="social_tiktok" 
                                           value="{{ old('social_tiktok', $settings['social_tiktok']->value ?? '') }}"
                                           placeholder="https://tiktok.com/@votre-compte">
                                    @error('social_tiktok')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="social_linkedin" class="form-label">
                                        <i class="mdi mdi-linkedin"></i> LinkedIn
                                    </label>
                                    <input type="url" class="form-control @error('social_linkedin') is-invalid @enderror" 
                                           id="social_linkedin" name="social_linkedin" 
                                           value="{{ old('social_linkedin', $settings['social_linkedin']->value ?? '') }}"
                                           placeholder="https://linkedin.com/company/votre-entreprise">
                                    @error('social_linkedin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="social_whatsapp" class="form-label">
                                        <i class="mdi mdi-whatsapp"></i> WhatsApp
                                    </label>
                                    <input type="url" class="form-control @error('social_whatsapp') is-invalid @enderror" 
                                           id="social_whatsapp" name="social_whatsapp" 
                                           value="{{ old('social_whatsapp', $settings['social_whatsapp']->value ?? '') }}"
                                           placeholder="https://wa.me/33612345678">
                                    @error('social_whatsapp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Format: https://wa.me/33612345678 (avec indicatif pays)</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 mb-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="mdi mdi-content-save me-2"></i> Enregistrer les Réseaux Sociaux
                            </button>
                        </div>
                    </form>
                </div>

                <!-- TAB 3: Mode Maintenance -->
                <div class="tab-pane fade" id="v-pills-maintenance" role="tabpanel" aria-labelledby="v-pills-maintenance-tab">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-warning bg-opacity-10 rounded p-2 me-2">
                                        <i class="mdi mdi-wrench text-warning fs-4"></i>
                                    </div>
                                    <h5 class="mb-0">Mode Maintenance</h5>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="maintenance_mode" 
                                               name="maintenance_mode" value="1"
                                               {{ old('maintenance_mode', $settings['maintenance_mode']->value ?? '0') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="maintenance_mode">
                                            <strong>Activer le mode maintenance</strong>
                                        </label>
                                    </div>
                                    <small class="text-muted">Lorsque activé, seuls les administrateurs peuvent accéder au site. Les visiteurs verront la page de maintenance.</small>
                                </div>

                                <div class="mb-3">
                                    <label for="maintenance_password" class="form-label">Mot de passe d'accès</label>
                                    <input type="text" class="form-control @error('maintenance_password') is-invalid @enderror" 
                                           id="maintenance_password" name="maintenance_password" 
                                           value="{{ old('maintenance_password', $settings['maintenance_password']->value ?? 'boldroots2024') }}"
                                           placeholder="boldroots2024">
                                    @error('maintenance_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Les visiteurs pourront accéder au site en entrant ce mot de passe</small>
                                </div>

                                <div class="mb-3">
                                    <label for="maintenance_title" class="form-label">Titre de la page</label>
                                    <input type="text" class="form-control @error('maintenance_title') is-invalid @enderror" 
                                           id="maintenance_title" name="maintenance_title" 
                                           value="{{ old('maintenance_title', $settings['maintenance_title']->value ?? 'STRUGGLE | ENDURE | WIN !') }}"
                                           placeholder="STRUGGLE | ENDURE | WIN !">
                                    @error('maintenance_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="maintenance_message" class="form-label">Message</label>
                                    <textarea class="form-control @error('maintenance_message') is-invalid @enderror" 
                                              id="maintenance_message" name="maintenance_message" rows="3"
                                              placeholder="Notre site est actuellement en maintenance...">{{ old('maintenance_message', $settings['maintenance_message']->value ?? '') }}</textarea>
                                    @error('maintenance_message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="maintenance_end_date" class="form-label">Date et heure de fin (Countdown)</label>
                                    <input type="datetime-local" class="form-control @error('maintenance_end_date') is-invalid @enderror" 
                                           id="maintenance_end_date" name="maintenance_end_date" 
                                           value="{{ old('maintenance_end_date', $settings['maintenance_end_date']->value ?? '') }}">
                                    @error('maintenance_end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Définissez une date de fin pour afficher un countdown en temps réel. Laissez vide pour afficher le symbole infini (∞)</small>
                                </div>

                                <div class="mb-3">
                                    <label for="maintenance_bg_image" class="form-label">Image d'arrière-plan</label>
                                    <input type="file" class="form-control @error('maintenance_bg_image') is-invalid @enderror" 
                                           id="maintenance_bg_image" name="maintenance_bg_image" accept="image/*">
                                    @error('maintenance_bg_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Format: JPG, PNG, GIF (Max: 2MB)</small>
                                    
                                    @if(isset($settings['maintenance_bg_image']) && $settings['maintenance_bg_image']->value)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $settings['maintenance_bg_image']->value) }}" 
                                                 alt="Maintenance Background" class="img-thumbnail" style="max-height: 200px;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 mb-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="mdi mdi-content-save me-2"></i> Enregistrer les Paramètres de Maintenance
                            </button>
                        </div>
                    </form>
                </div>

                <!-- TAB 4: Audio d'Arrière-plan -->
                <div class="tab-pane fade" id="v-pills-audio" role="tabpanel" aria-labelledby="v-pills-audio-tab">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-danger bg-opacity-10 rounded p-2 me-2">
                                        <i class="mdi mdi-music text-danger fs-4"></i>
                                    </div>
                                    <h5 class="mb-0">Audio d'Arrière-plan</h5>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="background_audio_enabled" 
                                               name="background_audio_enabled" value="1"
                                               {{ old('background_audio_enabled', $settings['background_audio_enabled']->value ?? '0') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="background_audio_enabled">
                                            Activer l'audio d'arrière-plan
                                        </label>
                                    </div>
                                    <small class="text-muted">Lorsque activé, un audio sera joué en arrière-plan sur le site</small>
                                </div>

                                <div class="mb-3">
                                    <label for="background_audio_file" class="form-label">Fichier Audio</label>
                                    <input type="file" class="form-control @error('background_audio_file') is-invalid @enderror" 
                                           id="background_audio_file" name="background_audio_file" accept="audio/*">
                                    @error('background_audio_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Format: MP3, WAV, OGG (Max: 10MB)</small>
                                    
                                    @if(isset($settings['background_audio']) && $settings['background_audio']->value)
                                        <div class="mt-2">
                                            <audio controls class="w-100">
                                                <source src="{{ asset('storage/' . $settings['background_audio']->value) }}" type="audio/mpeg">
                                                Votre navigateur ne supporte pas l'élément audio.
                                            </audio>
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="background_audio_volume" class="form-label">
                                        Volume (<span id="volume-display">{{ old('background_audio_volume', $settings['background_audio_volume']->value ?? '50') }}</span>%)
                                    </label>
                                    <input type="range" class="form-range" id="background_audio_volume" 
                                           name="background_audio_volume" min="0" max="100" 
                                           value="{{ old('background_audio_volume', $settings['background_audio_volume']->value ?? '50') }}"
                                           oninput="document.getElementById('volume-display').textContent = this.value">
                                    <small class="text-muted">Réglez le volume de l'audio d'arrière-plan</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 mb-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="mdi mdi-content-save me-2"></i> Enregistrer les Paramètres Audio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
