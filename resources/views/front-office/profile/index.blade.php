@extends('front-office.layouts.app')

@section('title', 'Mon Profil')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css">
    <style>
        .profile-header {
            background: #000;
            color: #fff;
            padding: 40px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
        }
        .profile-header h2 {
            font-weight: bold;
            letter-spacing: 2px;
            margin: 15px 0 5px 0;
            color: white;
            font-size: 24px;
        }
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 36px;
            color: #000;
            font-weight: bold;
            border: 3px solid #ca1515;
        }
        .info-box {
            background: #fff;
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 20px;
        }
        .info-box h5 {
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #000;
            font-size: 16px;
        }
        .info-row {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #666;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            min-width: 140px;
        }
        .info-value {
            font-size: 14px;
            color: #000;
            font-weight: 500;
            flex: 1;
        }
        .btn-action {
            background: #000;
            color: #fff;
            padding: 10px 25px;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            letter-spacing: 1px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
            font-size: 13px;
        }
        .btn-action:hover {
            background: #ca1515;
            color: #fff;
            transform: scale(1.05);
        }
        .btn-secondary-action {
            background: transparent;
            color: #000;
            border: 2px solid #000;
        }
        .btn-secondary-action:hover {
            background: #000;
            color: #fff;
        }
        .stats-card {
            background: linear-gradient(135deg, #000 0%, #333 100%);
            color: #fff;
            padding: 25px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
        }
        .stats-number {
            font-size: 28px;
            font-weight: bold;
            color: #ca1515;
            margin-bottom: 5px;
        }
        .stats-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
        }
        .form-control {
            border-radius: 8px;
            border: 1px solid #e3e6f0;
            padding: 10px 15px;
            font-size: 14px;
        }
        .form-control:focus {
            border-color: #000;
            box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: 600;
            color: #333;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
    </style>
@endsection

@section('content')
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option" style="margin-top: 130px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Mon Profil</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Home</a>
                            <span>Mon Profil</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Profile Section Begin -->
    <section class="shop spad">
        <div class="container">
            <!-- Profile Header -->
            <div class="profile-header">
                <div class="profile-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <h2>{{ Auth::user()->name }}</h2>
                <p style="opacity: 0.8; margin: 0;">{{ Auth::user()->email }}</p>
            </div>

            <div class="row">
                <!-- Left Column - Stats -->
                <div class="col-lg-4">
                    <div class="stats-card">
                        <div class="stats-number">{{ Auth::user()->orders->count() }}</div>
                        <div class="stats-label">Commandes</div>
                    </div>
                    <div class="stats-card">
                        <div class="stats-number">
                            @if(Auth::user()->last_login)
                                {{ Auth::user()->last_login->diffForHumans() }}
                            @else
                                -
                            @endif
                        </div>
                        <div class="stats-label">Dernière Connexion</div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="info-box">
                        <h5><i class="fa fa-bolt"></i> ACTIONS RAPIDES</h5>
                        <a href="{{ route('orders.index') }}" class="btn-action w-100 mb-2 text-center">
                            <i class="fa fa-shopping-bag"></i> MES COMMANDES
                        </a>
                    </div>
                </div>

                <!-- Right Column - Profile Info -->
                <div class="col-lg-8">
                    <!-- Personal Information -->
                    <div class="info-box">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0"><i class="fa fa-user"></i> INFORMATIONS PERSONNELLES</h5>
                            <button type="button" class="btn-action btn-secondary-action" onclick="toggleEditMode('personal')">
                                <i class="fa fa-edit"></i> MODIFIER
                            </button>
                        </div>

                        <div id="personal-view">
                            <div class="info-row">
                                <div class="info-label">Nom Complet</div>
                                <div class="info-value">{{ Auth::user()->name }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Email</div>
                                <div class="info-value">{{ Auth::user()->email }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Téléphone</div>
                                <div class="info-value">{{ Auth::user()->phone ?? 'Non renseigné' }}</div>
                            </div>
                        </div>

                        <form id="personal-edit" style="display: none;" action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Nom Complet</label>
                                <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Téléphone</label>
                                <input type="text" name="phone" class="form-control" value="{{ Auth::user()->phone }}">
                            </div>
                            <div class="text-end">
                                <button type="button" class="btn-action btn-secondary-action me-2" onclick="toggleEditMode('personal')">
                                    ANNULER
                                </button>
                                <button type="submit" class="btn-action">
                                    <i class="fa fa-save"></i> ENREGISTRER
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Address Information -->
                    <div class="info-box">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0"><i class="fa fa-map-marker"></i> ADRESSE DE LIVRAISON</h5>
                            <button type="button" class="btn-action btn-secondary-action" onclick="toggleEditMode('address')">
                                <i class="fa fa-edit"></i> MODIFIER
                            </button>
                        </div>

                        <div id="address-view">
                            @if(Auth::user()->address_line1)
                                <div class="info-row">
                                    <div class="info-label">Adresse</div>
                                    <div class="info-value">
                                        {{ Auth::user()->address_line1 }}<br>
                                        @if(Auth::user()->address_line2)
                                            {{ Auth::user()->address_line2 }}<br>
                                        @endif
                                        {{ Auth::user()->postal_code }} {{ Auth::user()->city }}
                                    </div>
                                </div>
                            @else
                                <p class="text-muted text-center py-3">Aucune adresse enregistrée</p>
                            @endif
                        </div>

                        <form id="address-edit" style="display: none;" action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Adresse Ligne 1</label>
                                <input type="text" name="address_line1" class="form-control" value="{{ Auth::user()->address_line1 }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Adresse Ligne 2 (Optionnel)</label>
                                <input type="text" name="address_line2" class="form-control" value="{{ Auth::user()->address_line2 }}">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Code Postal</label>
                                    <input type="text" name="postal_code" class="form-control" value="{{ Auth::user()->postal_code }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ville</label>
                                    <input type="text" name="city" class="form-control" value="{{ Auth::user()->city }}">
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="button" class="btn-action btn-secondary-action me-2" onclick="toggleEditMode('address')">
                                    ANNULER
                                </button>
                                <button type="submit" class="btn-action">
                                    <i class="fa fa-save"></i> ENREGISTRER
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Password Change -->
                    <div class="info-box">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0"><i class="fa fa-lock"></i> SÉCURITÉ</h5>
                            <button type="button" class="btn-action btn-secondary-action" onclick="toggleEditMode('password')">
                                <i class="fa fa-key"></i> CHANGER MOT DE PASSE
                            </button>
                        </div>

                        <div id="password-view">
                            <p class="text-muted text-center py-3">
                                <i class="fa fa-shield" style="font-size: 48px; color: #ddd; margin-bottom: 10px;"></i><br>
                                Votre mot de passe est sécurisé
                            </p>
                        </div>

                        <form id="password-edit" style="display: none;" action="{{ route('profile.password') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Mot de passe actuel</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nouveau mot de passe</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirmer le mot de passe</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                            <div class="text-end">
                                <button type="button" class="btn-action btn-secondary-action me-2" onclick="toggleEditMode('password')">
                                    ANNULER
                                </button>
                                <button type="submit" class="btn-action">
                                    <i class="fa fa-save"></i> ENREGISTRER
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Profile Section End -->

    <script>
        function toggleEditMode(section) {
            const viewElement = document.getElementById(section + '-view');
            const editElement = document.getElementById(section + '-edit');
            
            if (viewElement.style.display === 'none') {
                viewElement.style.display = 'block';
                editElement.style.display = 'none';
            } else {
                viewElement.style.display = 'none';
                editElement.style.display = 'block';
            }
        }
    </script>
@endsection
