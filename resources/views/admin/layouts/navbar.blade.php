<div class="navbar-custom">
    <ul class="list-unstyled topbar-menu float-end mb-0">
        <!-- Recherche mobile -->
        <li class="dropdown notification-list d-lg-none">
            <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button"
                aria-haspopup="false" aria-expanded="false">
                <i class="dripicons-search noti-icon"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-animated dropdown-lg p-0">
                <form class="p-3">
                    <input type="text" class="form-control mobile-search-input" placeholder="Rechercher..." aria-label="Rechercher">
                </form>
                <div class="mobile-search-results"></div>
            </div>
        </li>

        <!-- Notifications -->
        <li class="dropdown notification-list">
            <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button"
                aria-haspopup="false" aria-expanded="false">
                <i class="dripicons-bell noti-icon"></i>
                <span class="noti-icon-badge"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated dropdown-lg">
                <div class="dropdown-item noti-title">
                    <h5 class="m-0">
                        <span class="float-end">
                            <a href="javascript:void(0);" class="text-dark">
                                <small>Tout effacer</small>
                            </a>
                        </span>Notifications
                    </h5>
                </div>
                <div style="max-height: 230px;" data-simplebar>
                    <!-- Les notifications seront chargées dynamiquement -->
                    <div class="text-center py-3 text-muted">
                        <i class="mdi mdi-bell-outline font-24"></i>
                        <p class="mb-0">Aucune notification</p>
                    </div>
                </div>
                <a href="javascript:void(0);" class="dropdown-item text-center text-primary notify-item notify-all">
                    Voir tout
                </a>
            </div>
        </li>

        <!-- Menu utilisateur -->
        <li class="dropdown notification-list">
            <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown" href="#"
                role="button" aria-haspopup="false" aria-expanded="false">
                <span class="account-user-avatar">
                    <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="user-image" class="rounded-circle">
                </span>
                <span>
                    <span class="account-user-name">{{ Auth::guard('admin')->user()->name }}</span>
                    <span class="account-position">{{ Auth::guard('admin')->user()->roles->first()->name ?? 'Utilisateur' }}</span>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown">
                <div class="dropdown-header noti-title">
                    <h6 class="text-overflow m-0">Bonjour !</h6>
                </div>

                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <i class="mdi mdi-account-circle me-1"></i>
                    <span>Mon Compte</span>
                </a>

                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <i class="mdi mdi-account-edit me-1"></i>
                    <span>Paramètres</span>
                </a>

                <div class="dropdown-divider"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item notify-item">
                        <i class="mdi mdi-logout me-1"></i>
                        <span>Se Déconnecter</span>
                    </button>
                </form>
            </div>
        </li>
    </ul>

    <button class="button-menu-mobile open-left">
        <i class="mdi mdi-menu"></i>
    </button>

    <!-- Barre de recherche -->
    <div class="app-search dropdown d-none d-lg-block">
        <form id="search-form" autocomplete="off">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Rechercher équipements, interventions, produits..." id="top-search" autocomplete="off">
                <span class="mdi mdi-magnify search-icon"></span>
            </div>
        </form>
        <div class="dropdown-menu dropdown-menu-animated dropdown-lg" id="search-dropdown">
            <div id="search-results">
                <div class="text-center py-3 text-muted">
                    <p class="mb-0">Tapez pour rechercher...</p>
                </div>
            </div>
        </div>
    </div>
</div>

