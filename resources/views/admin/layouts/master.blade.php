<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'CheckManager')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />



    @yield('head')
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('images/BOLDROOTS-logo.avif') }}">

    <!-- third party css -->
    <link href="assets/css/vendor/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <!-- third party css end -->

    <!-- App css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />
    <link href="{{ asset('assets/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style" />

</head>

<body class="loading"
    data-layout-config='{"leftSideBarTheme":"light","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
    <!-- Begin page -->
    <div class="wrapper">
        <!-- ========== Left Sidebar Start ========== -->
        @include('admin.layouts.sidebar')
        <!-- Left Sidebar End -->
        <!-- Start Content-->
        <div class="content-page">
            <div class="content">
                <!-- Topbar Start -->
                @include('admin.layouts.navbar')
                <!-- end Topbar -->
                @yield('content')
            </div>
            <!-- Footer Start -->
            @include('admin.layouts.footer')
            <!-- end Footer -->
        </div>
        <!-- content-page -->

        <!-- end wrapper-->
    </div>
    <!-- END Container -->


    <!-- bundle -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>

    @yield('scripts')

    <script>
        // Recherche dynamique admin
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('admin-search-input');
            const searchDropdown = document.getElementById('admin-search-dropdown');
            
            if (!searchInput || !searchDropdown) return;

            let searchTimeout;

            // Recherche dynamique lors de la saisie
            searchInput.addEventListener('input', function(e) {
                const query = e.target.value.trim();

                clearTimeout(searchTimeout);

                if (query.length === 0) {
                    searchDropdown.classList.remove('show');
                    return;
                }

                searchTimeout = setTimeout(() => {
                    performAdminSearch(query);
                }, 300);
            });

            // Masquer les résultats quand on clique ailleurs
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
                    searchDropdown.classList.remove('show');
                }
            });

            // Afficher le dropdown quand on focus l'input
            searchInput.addEventListener('focus', function() {
                if (searchDropdown.innerHTML && searchInput.value.trim().length > 0) {
                    searchDropdown.classList.add('show');
                }
            });
        });

        function performAdminSearch(query) {
            const searchDropdown = document.getElementById('admin-search-dropdown');
            
            searchDropdown.innerHTML = '<div class="dropdown-header noti-title"><h5 class="text-overflow mb-2"><i class="mdi mdi-loading mdi-spin"></i> Recherche en cours...</h5></div>';
            searchDropdown.classList.add('show');

            fetch(`/admin/api/search?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    displayAdminSearchResults(data);
                })
                .catch(error => {
                    console.error('Erreur de recherche:', error);
                    searchDropdown.innerHTML = '<div class="dropdown-header noti-title"><h5 class="text-overflow mb-2 text-danger">Erreur lors de la recherche</h5></div>';
                });
        }

        function displayAdminSearchResults(data) {
            const searchDropdown = document.getElementById('admin-search-dropdown');
            
            if (data.total === 0) {
                searchDropdown.innerHTML = '<div class="dropdown-header noti-title"><h5 class="text-overflow mb-2">Aucun résultat trouvé</h5></div>';
                return;
            }

            let html = '<div class="dropdown-header noti-title"><h5 class="text-overflow mb-2">Résultats de recherche (' + data.total + ')</h5></div>';

            // Produits
            if (data.products.length > 0) {
                html += '<div class="dropdown-header"><h6 class="text-uppercase mb-1">Produits</h6></div>';
                data.products.forEach(product => {
                    html += `
                        <a href="${product.url}" class="dropdown-item notify-item">
                            <div class="d-flex align-items-center">
                                <i class="mdi mdi-package-variant me-2 text-primary"></i>
                                <div class="flex-grow-1">
                                    <h6 class="m-0">${product.name}</h6>
                                    <span class="font-12 text-muted">SKU: ${product.sku} • ${product.price} • ${product.category}</span>
                                </div>
                                <span class="badge badge-${product.status === 'Actif' ? 'success' : 'secondary'}-lighten">${product.status}</span>
                            </div>
                        </a>`;
                });
            }

            // Commandes
            if (data.orders.length > 0) {
                html += '<div class="dropdown-header"><h6 class="text-uppercase mb-1">Commandes</h6></div>';
                data.orders.forEach(order => {
                    const statusColors = {
                        'pending': 'warning',
                        'processing': 'info',
                        'shipped': 'primary',
                        'delivered': 'success',
                        'cancelled': 'danger'
                    };
                    const paymentColors = {
                        'pending': 'warning',
                        'paid': 'success',
                        'failed': 'danger'
                    };
                    html += `
                        <a href="${order.url}" class="dropdown-item notify-item">
                            <div class="d-flex align-items-center">
                                <i class="mdi mdi-cart me-2 text-info"></i>
                                <div class="flex-grow-1">
                                    <h6 class="m-0">${order.order_number}</h6>
                                    <span class="font-12 text-muted">${order.customer} • ${order.total} • ${order.date}</span>
                                </div>
                                <div>
                                    <span class="badge badge-${statusColors[order.status]}-lighten me-1">${order.status}</span>
                                    <span class="badge badge-${paymentColors[order.payment_status]}-lighten">${order.payment_status}</span>
                                </div>
                            </div>
                        </a>`;
                });
            }

            // Factures
            if (data.invoices.length > 0) {
                html += '<div class="dropdown-header"><h6 class="text-uppercase mb-1">Factures</h6></div>';
                data.invoices.forEach(invoice => {
                    const statusColors = {
                        'draft': 'secondary',
                        'sent': 'info',
                        'paid': 'success',
                        'cancelled': 'danger'
                    };
                    html += `
                        <a href="${invoice.url}" class="dropdown-item notify-item">
                            <div class="d-flex align-items-center">
                                <i class="mdi mdi-file-document me-2 text-success"></i>
                                <div class="flex-grow-1">
                                    <h6 class="m-0">${invoice.invoice_number}</h6>
                                    <span class="font-12 text-muted">${invoice.customer} • ${invoice.total} • ${invoice.date}</span>
                                </div>
                                <span class="badge badge-${statusColors[invoice.status]}-lighten">${invoice.status}</span>
                            </div>
                        </a>`;
                });
            }

            // Bons de livraison
            if (data.deliveryNotes.length > 0) {
                html += '<div class="dropdown-header"><h6 class="text-uppercase mb-1">Bons de Livraison</h6></div>';
                data.deliveryNotes.forEach(note => {
                    const statusColors = {
                        'pending': 'warning',
                        'in_transit': 'info',
                        'delivered': 'success',
                        'failed': 'danger',
                        'returned': 'secondary'
                    };
                    html += `
                        <a href="${note.url}" class="dropdown-item notify-item">
                            <div class="d-flex align-items-center">
                                <i class="mdi mdi-truck-delivery me-2 text-warning"></i>
                                <div class="flex-grow-1">
                                    <h6 class="m-0">${note.delivery_number}</h6>
                                    <span class="font-12 text-muted">${note.customer} • ${note.carrier} • ${note.date}</span>
                                </div>
                                <span class="badge badge-${statusColors[note.status]}-lighten">${note.status}</span>
                            </div>
                        </a>`;
                });
            }

            searchDropdown.innerHTML = html;
        }
    </script>

</body>

</html>
