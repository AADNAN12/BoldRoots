<div class="navbar-custom">
    <ul class="list-unstyled topbar-menu float-end mb-0">
        <!-- Recherche mobile -->
        <li class="dropdown notification-list d-lg-none">
            <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button"
                aria-haspopup="false" aria-expanded="false">
                <i class="dripicons-search noti-icon"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-animated dropdown-lg p-0" style="width:70%;">
                <form class="p-3">
                    <input type="text" class="form-control mobile-search-input" placeholder="Rechercher..." aria-label="Rechercher">
                </form>
                <div class="mobile-search-results"></div>
            </div>
        </li>



        <!-- Menu utilisateur -->
        <li class="dropdown notification-list">
            <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown" href="#"
                role="button" aria-haspopup="false" aria-expanded="false">
                <span class="account-user-avatar">
                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 14px; font-weight: bold;">
                        {{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)) }}
                    </div>
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
    <div class="app-search dropdown d-none d-lg-block" style="margin-top:16px;">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Rechercher produits, commandes, factures, BL..." id="admin-search-input" autocomplete="off">
            <span class="mdi mdi-magnify search-icon"></span>
        </div>
        <div class="dropdown-menu dropdown-menu-animated dropdown-lg" id="admin-search-dropdown"></div>
    </div>
</div>

