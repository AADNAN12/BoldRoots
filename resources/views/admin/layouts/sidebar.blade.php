<div class="leftside-menu">

    <!-- LOGO -->
    <a href="#" class="logo text-center logo-light">
        <span class="logo-lg">
            <img src="{{ asset('images/BOLDROOTS-logo.avif') }}" alt="BLOOD ROOTS" height="40">
        </span>
        <span class="logo-sm">
            <img src="{{ asset('images/BOLDROOTS-logo.avif') }}" alt="" height="40">
        </span>
    </a>

    <!-- LOGO -->
    <a href="#" class="logo text-center logo-dark">
        <span class="logo-lg">
            <img src="{{ asset('images/BOLDROOTS-logo.avif') }}" alt="BLOOD ROOTS" height="40">
        </span>
        <span class="logo-sm">
            <img src="{{ asset('images/BOLDROOTS-logo.avif') }}" alt="" height="25">
        </span>
    </a>

    <div class="h-100" id="leftside-menu-container mt-2" data-simplebar>
        <!--- Sidemenu -->
        <ul class="side-nav mt-2">

            @if(Auth::guard('admin')->check() && (Auth::guard('admin')->user()->can('view_products') || Auth::guard('admin')->user()->can('view_categories')))
            <!-- CATALOGUE -->
            <li class="side-nav-title side-nav-item mt-1">Catalogue</li>

            <!-- PRODUITS -->
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarProduits" aria-expanded="false"
                    aria-controls="sidebarProduits" class="side-nav-link">
                    <i class="uil-tag-alt"></i>
                    <span> Produits </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarProduits">
                    <ul class="side-nav-second-level">
                        @if(Auth::guard('admin')->user()->can('view_products'))
                        <li><a href="{{ route('admin.products.index') }}">Liste des produits</a></li>
                        @endif
                    </ul>
                    <ul class="side-nav-second-level">
                        @if(Auth::guard('admin')->user()->can('view_categories'))
                        <li><a href="{{ route('admin.categories.index') }}">Liste des Categories</a></li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif

            <!-- ATTRIBUTS (Couleurs, Tailles) -->
            @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->can('view_attributes'))
            <li class="side-nav-item">
                <a href="{{ route('admin.attributes.index') }}" class="side-nav-link">
                    <i class="uil-palette"></i>
                    <span> Couleurs & Tailles</span>
                </a>
            </li>
            @endif

            @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->can('view_orders'))
            <!-- VENTES & COMMANDES -->
            <li class="side-nav-title side-nav-item mt-1">Ventes</li>

            <!-- COMMANDES -->
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarCommandes" aria-expanded="false"
                    aria-controls="sidebarCommandes" class="side-nav-link">
                    <i class="mdi mdi-cart"></i>
                    <span> Commandes </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarCommandes">
                    <ul class="side-nav-second-level">
                            <li><a href="{{ route('admin.orders.index') }}">List des commandes</a></li>
                    </ul>
                </div>
            </li>
            @endif

            <!-- FACTURES -->
            @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->can('view_invoices'))
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarFactures" aria-expanded="false"
                    aria-controls="sidebarFactures" class="side-nav-link">
                    <i class="uil-file-alt"></i>
                    <span> Factures </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarFactures">
                    <ul class="side-nav-second-level">
                        <li><a href="{{ route('admin.invoices.index') }}">Liste des factures</a></li>
                    </ul>
                </div>
            </li>
            @endif

            <!-- BONS DE LIVRAISON -->
            @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->can('view_delivery_notes'))
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarLivraison" aria-expanded="false"
                    aria-controls="sidebarLivraison" class="side-nav-link">
                    <i class="uil-truck"></i>
                    <span> Bons de Livraison </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarLivraison">
                    <ul class="side-nav-second-level">
                        <li><a href="{{ route('admin.delivery-notes.index') }}">Liste des bons</a></li>
                    </ul>
                </div>
            </li>
            @endif

            @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->can('view_promotions'))
            <!-- MARKETING & PROMOTIONS -->
            <li class="side-nav-title side-nav-item mt-1">Marketing</li>

            <!-- PROMOTIONS & FLASH DEALS -->
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarPromotions" aria-expanded="false"
                    aria-controls="sidebarPromotions" class="side-nav-link">
                    <i class="uil-bolt-alt"></i>
                    <span> Promotions </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarPromotions">
                    <ul class="side-nav-second-level">
                        <li><a href="{{ route('admin.promotions.index') }}">Toutes les promotions</a></li>
                    </ul>
                </div>
            </li>
            @endif

            <!-- COUPONS -->
            @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->can('view_coupons'))
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarCoupons" aria-expanded="false"
                    aria-controls="sidebarCoupons" class="side-nav-link">
                    <i class="uil-ticket"></i>
                    <span> Codes Promo </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarCoupons">
                    <ul class="side-nav-second-level">
                        <li><a href="{{ route('admin.coupons.index') }}">Liste des coupons</a></li>
                    </ul>
                </div>
            </li>
            @endif

            <!-- NEWSLETTER -->
            @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->can('view_newsletters'))
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarNewsletter" aria-expanded="false"
                    aria-controls="sidebarNewsletter" class="side-nav-link">
                    <i class="uil-envelope"></i>
                    <span> Newsletter </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarNewsletter">
                    <ul class="side-nav-second-level">
                        <li><a href="{{ route('admin.newsletters.index') }}">Tous les abonnés</a></li>
                    </ul>
                </div>
            </li>
            @endif

            @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->can('view_settings'))
            <!-- CONFIGURATION -->
            <li class="side-nav-title side-nav-item mt-1">Configuration</li>

            <!-- PARAMÈTRES DU SITE -->
            <li class="side-nav-item">
                <a href="{{ route('admin.settings.index') }}" class="side-nav-link">
                    <i class="uil-sliders-v-alt"></i>
                    <span> Paramètres du Site </span>
                </a>
            </li>
            @endif

            <!-- PAGES CMS -->
            @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->can('view_cms_pages'))
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarCMS" aria-expanded="false"
                    aria-controls="sidebarCMS" class="side-nav-link">
                    <i class="uil-file-alt"></i>
                    <span> Pages CMS </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarCMS">
                    <ul class="side-nav-second-level">
                        <li><a href="{{ route('admin.cms-pages.index') }}">Toutes les pages</a></li>
                        @if(Auth::guard('admin')->user()->can('create_cms_pages'))
                        <li><a href="{{ route('admin.cms-pages.create') }}">Créer une page</a></li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif

            <!-- MÉTHODES DE LIVRAISON -->
            @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->can('view_shipping_methods'))
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarShipping" aria-expanded="false"
                    aria-controls="sidebarShipping" class="side-nav-link">
                    <i class="uil-plane-departure"></i>
                    <span> Méthodes de Livraison </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarShipping">
                    <ul class="side-nav-second-level">
                        <li><a href="{{ route('admin.shipping-methods.index') }}">Toutes les méthodes</a></li>
                        @if(Auth::guard('admin')->user()->can('create_shipping_methods'))
                        <li><a href="{{ route('admin.shipping-methods.create') }}">Ajouter une méthode</a></li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif

            <!-- INFORMATIONS ENTREPRISE -->
            @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->can('view_company_info'))
            <li class="side-nav-item">
                <a href="{{ route('admin.company-info.index') }}" class="side-nav-link">
                    <i class="uil-building"></i>
                    <span> Infos Entreprise </span>
                </a>
            </li>
            @endif

            @if(Auth::guard('admin')->check() && (Auth::guard('admin')->user()->can('view_users') || Auth::guard('admin')->user()->can('view_roles')))
            <!-- SYSTÈME -->
            <li class="side-nav-title side-nav-item mt-1">Système</li>

            <!-- ADMINISTRATEURS -->
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarAdmin" aria-expanded="false"
                    aria-controls="sidebarAdmin" class="side-nav-link">
                    <i class="uil-user-circle"></i>
                    <span> Utilisateurs Admin </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarAdmin">
                    <ul class="side-nav-second-level">
                        @if(Auth::guard('admin')->user()->can('view_users'))
                        <li><a href="{{ route('admin.users.index') }}">Tous les admins</a></li>
                        @endif
                        @if(Auth::guard('admin')->user()->can('view_roles'))
                        <li><a href="{{route('admin.roles.index')}}">Rôles & Permissions</a></li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif

        </ul>
    </div>

    <div class="clearfix"></div>
</div>