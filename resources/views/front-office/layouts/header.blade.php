<style>
    /* Animated Top Menu */
    .top-menu {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.95);
        padding: 10px 0;
        z-index: 1000;
        border-bottom: 1px solid rgba(255, 0, 0, 0.3);
    }

    .top-menu-text {
        text-align: center;
        font-weight: bold;
        font-size: 14px;
        letter-spacing: 2px;
        color: #fff;
        animation: slideText 20s linear infinite;
        white-space: nowrap;
    }

    @keyframes slideText {
        0% {
            transform: translateX(100%);
        }

        100% {
            transform: translateX(-100%);
        }
    }

    /* Header */
    .main-header {
        position: fixed;
        top: 40px;
        left: 0;
        right: 0;
        background: transparent;
        padding: 20px 50px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 999;
    }

    .menu-toggle {
        cursor: pointer;
        font-size: 24px;
        color: #fff;
        transition: all 0.3s;
    }

    .menu-toggle:hover {
        color: #ff0000;
        transform: scale(1.1);
    }

    .logo {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
    }

    .logo img {
        height: 50px;
        filter: brightness(1.2);
    }

    .header-right {
        display: flex;
        gap: 30px;
        align-items: center;
    }

    .header-right a {
        color: #fff;
        text-decoration: none;
        font-weight: bold;
        transition: all 0.3s;
        font-size: 14px;
        letter-spacing: 1px;
    }

    .header-right a:hover {
        color: #ff0000;
    }

    .cart-icon {
        position: relative;
        cursor: pointer;
    }

    /* User Dropdown */
    .user-dropdown {
        position: relative;
    }

    .user-dropdown-btn {
        background: transparent;
        border: 1px solid rgba(0, 0, 0, 0.3);
        color: #000000ff;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: bold;
        font-size: 14px;
        letter-spacing: 1px;
        transition: all 0.3s;
    }

    .user-dropdown-btn:hover {
        border-color: #ff0000;
        background: rgba(255, 0, 0, 0.1);
        color: #ff0000;
    }

    .user-dropdown-btn i {
        font-size: 16px;
    }

    .user-dropdown-menu {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        background: rgba(0, 0, 0, 0.95);
        border: 1px solid rgba(255, 0, 0, 0.3);
        border-radius: 8px;
        min-width: 220px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        z-index: 1000;
    }

    .user-dropdown:hover .user-dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-header {
        padding: 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        color: #fff;
        font-size: 14px;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 15px;
        color: #fff !important;
        text-decoration: none;
        transition: all 0.3s;
        border: none;
        background: transparent;
        width: 100%;
        text-align: left;
        cursor: pointer;
        font-size: 14px;
    }

    .dropdown-item:hover {
        background: rgba(255, 0, 0, 0.1);
        color: #ff0000;
    }

    .dropdown-item i {
        font-size: 16px;
        width: 20px;
    }

    .dropdown-divider {
        height: 1px;
        background: rgba(255, 255, 255, 0.1);
        margin: 8px 0;
    }

    .dropdown-item-form {
        margin: 0;
        padding: 0;
    }

    .header-link {
        color: #fff;
        text-decoration: none;
        font-weight: bold;
        transition: all 0.3s;
        font-size: 14px;
        letter-spacing: 1px;
    }

    .header-link:hover {
        color: #ff0000;
    }

    /* Cart Sidebar */
    .cart-sidebar {
        position: fixed;
        right: -450px;
        top: 0;
        width: 450px;
        height: 100vh;
        background: #ffffff;
        z-index: 1002;
        transition: right 0.4s ease;
        box-shadow: -5px 0 20px rgba(0, 0, 0, 0.3);
        display: flex;
        flex-direction: column;
    }

    .cart-sidebar.active {
        right: 0;
    }

    .cart-sidebar-header {
        padding: 30px;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #111111;
        color: #ffffff;
    }

    .cart-sidebar-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        letter-spacing: 2px;
    }

    .cart-close {
        cursor: pointer;
        font-size: 24px;
        color: #ffffff;
        transition: all 0.3s;
    }

    .cart-close:hover {
        color: #ff0000;
        transform: rotate(90deg);
    }

    .cart-sidebar-body {
        flex: 1;
        overflow-y: auto;
        padding: 20px 30px;
    }

    .cart-empty {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .cart-empty i {
        font-size: 80px;
        color: #e0e0e0;
        margin-bottom: 20px;
    }

    .cart-empty p {
        font-size: 16px;
        margin-bottom: 25px;
    }

    .cart-item {
        display: flex;
        gap: 15px;
        padding: 20px 0;
        border-bottom: 1px solid #f0f0f0;
        position: relative;
    }

    .cart-item-image {
        width: 100px;
        height: 100px;
        background: #f5f5f5;
        border-radius: 8px;
        overflow: hidden;
    }

    .cart-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cart-item-details {
        flex: 1;
    }

    .cart-item-title {
        font-size: 15px;
        font-weight: 600;
        color: #111111;
        margin-bottom: 8px;
    }

    .cart-item-options {
        font-size: 13px;
        color: #888;
        margin-bottom: 10px;
    }

    .cart-item-price {
        font-size: 16px;
        font-weight: 700;
        color: #111111;
    }

    .cart-item-remove {
        position: absolute;
        top: 20px;
        right: 0;
        cursor: pointer;
        color: #999;
        font-size: 18px;
        transition: all 0.3s;
    }

    .cart-item-remove:hover {
        color: #ff0000;
    }

    .cart-item-quantity {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 10px;
    }

    .qty-btn {
        width: 28px;
        height: 28px;
        border: 1px solid #e0e0e0;
        background: #ffffff;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        transition: all 0.3s;
        border-radius: 4px;
    }

    .qty-btn:hover {
        background: #111111;
        color: #ffffff;
        border-color: #111111;
    }

    .qty-value {
        font-size: 14px;
        font-weight: 600;
        min-width: 30px;
        text-align: center;
    }

    .cart-sidebar-footer {
        padding: 25px 30px;
        border-top: 2px solid #f0f0f0;
        background: #fafafa;
    }

    .cart-subtotal {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        font-size: 16px;
    }

    .cart-subtotal-label {
        font-weight: 600;
        color: #111111;
        letter-spacing: 1px;
    }

    .cart-subtotal-amount {
        font-size: 22px;
        font-weight: 700;
        color: #ff0000;
    }

    .cart-actions {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .cart-btn {
        padding: 15px;
        text-align: center;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s;
        border: none;
        text-decoration: none;
        display: block;
    }

    .cart-btn-primary {
        background: #111111;
        color: #ffffff;
    }

    .cart-btn-primary:hover {
        background: #ff0000;
        color: #ffffff;
    }

    .cart-btn-secondary {
        background: transparent;
        color: #111111;
        border: 2px solid #111111;
    }

    .cart-btn-secondary:hover {
        background: #111111;
        color: #ffffff;
    }

    .cart-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1001;
        opacity: 0;
        visibility: hidden;
        transition: all 0.4s;
    }

    .cart-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    /* Sidebar Navigation */
    .sidebar {
        position: fixed;
        left: -400px;
        top: 0;
        width: 400px;
        height: 100vh;
        background: rgba(0, 0, 0, 0.98);
        z-index: 1001;
        transition: left 0.4s ease;
        border-right: 1px solid rgba(255, 0, 0, 0.3);
        display: flex;
        flex-direction: column;
    }

    .sidebar.active {
        left: 0;
    }

    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.4s;
    }

    .sidebar-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .sidebar-close {
        position: absolute;
        top: 30px;
        right: 30px;
        z-index: 20;
        background: transparent;
        border: none;
        color: #fff;
        font-size: 24px;
        cursor: pointer;
        transition: all 0.3s;
        z-index: 10;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .sidebar-close:hover {
        color: #ff0000;
        background: rgba(255, 0, 0, 0.1);
        transform: rotate(90deg);
    }

    .sidebar-tagline {
        padding: 30px 30px 20px 30px;
        font-size: 12px;
        letter-spacing: 1px;
        color: #888;
        flex-shrink: 0;
    }

    .sidebar-menu-container {
        flex: 1;
        overflow-y: auto;
        padding: 0 30px 30px 30px;
    }

    /* Custom Scrollbar */
    .sidebar-menu-container::-webkit-scrollbar {
        width: 8px;
    }

    .sidebar-menu-container::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
    }

    .sidebar-menu-container::-webkit-scrollbar-thumb {
        background: rgba(204, 0, 0, 0.6);
        border-radius: 10px;
        transition: background 0.3s;
    }

    .sidebar-menu-container::-webkit-scrollbar-thumb:hover {
        background: rgba(204, 0, 0, 0.9);
    }

    /* Firefox Scrollbar */
    .sidebar-menu-container {
        scrollbar-width: thin;
        scrollbar-color: rgba(204, 0, 0, 0.6) rgba(255, 255, 255, 0.05);
    }

    .sidebar-menu {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .sidebar-menu>li {
        margin-bottom: 20px;
        opacity: 0;
        transform: translateX(-20px);
        transition: all 0.3s;
        position: relative;
    }

    .sidebar.active .sidebar-menu>li {
        opacity: 1;
        transform: translateX(0);
    }

    .sidebar-menu>li>a {
        color: #fff;
        text-decoration: none;
        font-size: 18px;
        font-weight: bold;
        letter-spacing: 2px;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
    }

    .sidebar-menu>li>a:hover {
        color: #ff0000;
        padding-left: 10px;
    }

    /* Submenu Styles */
    .sidebar-menu>li>a .arrow-icon {
        font-size: 12px;
        transition: transform 0.3s;
    }

    .sidebar-menu>li:hover>a .arrow-icon {
        transform: translateX(5px);
    }

    .submenu {
        list-style: none;
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        transition: all 0.4s ease;
        padding-left: 0;
        margin-top: 0;
    }

    .sidebar-menu>li:hover .submenu {
        max-height: 500px;
        opacity: 1;
        margin-top: 10px;
    }

    .submenu li {
        margin-bottom: 12px;
        transform: translateX(-10px);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .sidebar-menu>li:hover .submenu li {
        transform: translateX(0);
        opacity: 1;
    }

    .sidebar-menu>li:hover .submenu li:nth-child(1) {
        transition-delay: 0.1s;
    }

    .sidebar-menu>li:hover .submenu li:nth-child(2) {
        transition-delay: 0.15s;
    }

    .sidebar-menu>li:hover .submenu li:nth-child(3) {
        transition-delay: 0.2s;
    }

    .sidebar-menu>li:hover .submenu li:nth-child(4) {
        transition-delay: 0.25s;
    }

    .sidebar-menu>li:hover .submenu li:nth-child(5) {
        transition-delay: 0.3s;
    }

    .submenu li a {
        color: #999;
        text-decoration: none;
        font-size: 14px;
        font-weight: normal;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        padding: 8px 20px;
        border-left: 2px solid transparent;
        transition: all 0.3s;
    }

    .submenu li a::before {
        content: '→';
        margin-right: 10px;
        opacity: 0;
        transform: translateX(-10px);
        transition: all 0.3s;
    }

    .submenu li a:hover {
        color: #ff0000;
        border-left-color: #ff0000;
        padding-left: 25px;
        background: rgba(255, 0, 0, 0.05);
    }

    .submenu li a:hover::before {
        opacity: 1;
        transform: translateX(0);
    }
</style>

<!-- Animated Top Menu -->
<div class="top-menu" style="background: {{ \App\Models\SiteSetting::get('top_bar_bg_color', '#000000') }}; @if(\App\Models\SiteSetting::where('key', 'top_bar_bg_image')->first() && \App\Models\SiteSetting::where('key', 'top_bar_bg_image')->first()->value) background-image: url('{{ asset('storage/' . \App\Models\SiteSetting::where('key', 'top_bar_bg_image')->first()->value) }}'); background-size: cover; background-position: center; @endif">
    <div class="top-menu-text">
        {{ \App\Models\SiteSetting::get('top_bar_text', 'DEVOTE YOURSELF TO THE BOLD ROOTS') }}
    </div>
</div>

<!-- Main Header -->
<header class="main-header">
    <div class="menu-toggle" id="menuToggle">
        <i class="fas fa-bars"></i>
    </div>
    <div class="logo">
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/BOLDROOTS-logo.avif') }}" alt="Logo">
        </a>
    </div>
    <div class="header-right">
        @auth('web')
            <!-- Utilisateur connecté -->
            <div class="user-dropdown">
                <button class="user-dropdown-btn">
                    <i class="fas fa-user"></i>
                    <span>{{ Auth::guard('web')->user()->name }}</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="user-dropdown-menu">
                    <div class="dropdown-header">
                        <strong>Bonjour !</strong>
                    </div>
                    
                    @if(Auth::guard('web')->user()->isClient())
                        <!-- Menu pour les clients -->
                        <a href="{{ route('profile.index') }}" class="dropdown-item" style="color:white !important;" >
                            <i class="fas fa-user"></i>
                            Mon profil
                        </a>
                        <a href="{{ route('orders.index') }}" class="dropdown-item" style="color:white !important;" >
                            <i class="fas fa-shopping-bag"></i>
                            Mes commandes
                        </a>
                    @else
                        <!-- Menu pour les administrateurs -->
                        <a href="" class="dropdown-item" style="color:white !important;" >
                            <i class="fas fa-tachometer-alt"></i>
                            Tableau de bord Admin
                        </a>
                    @endif
                    
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}" class="dropdown-item-form">
                        @csrf
                        <button type="submit" class="dropdown-item" style="color:white !important;" >
                            <i class="fas fa-sign-out-alt"></i>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        @else
            <!-- Utilisateur non connecté -->
            <a href="{{ route('register') }}" class="header-link">REGISTER</a>
            <a href="{{ route('login') }}" class="cart-icon">
                <i class="fas fa-user"></i>
            </a>
        @endauth
        
        <div class="cart-icon" id="cartToggle">
            <i class="fas fa-shopping-cart"></i>
            <span id="cartCount"
                style="position: absolute; top: -8px; right: -8px; background: #ff0000; border-radius: 50%; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; font-size: 10px;">0</span>
        </div>
    </div>
</header>

<!-- Sidebar Navigation -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<nav class="sidebar" id="sidebar">
    <button class="sidebar-close" id="sidebarClose">
        <i class="fas fa-times"></i>
    </button>
    <div class="sidebar-tagline">STRUGGLE | ENDURE | WIN</div>
    <div class="sidebar-menu-container">
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('home') }}">HOME</a>
            </li>
            
            <li>
                <a href="{{ route('products.index') }}">ALL PRODUCTS</a>
            </li>
            @foreach($globalCategories as $category)
                <li>
                        <a href="{{ route('products.category', $category->slug) }}">
                            {{ $category->name }}
                            <i class="fas fa-chevron-right arrow-icon"></i>
                        </a>
                        <ul class="submenu">
                            @foreach($category->children as $child)
                                <li>
                                    <a href="{{ route('products.category', $child->slug) }}">
                                        {{ $child->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                </li>
            @endforeach
            <li>
                <a href="{{ route('artists-collabs') }}">ARTISTS COLLABS</a>
            </li>
            <li>
                <a href="{{ route('about') }}">ABOUT US</a>
            </li>
        </ul>
    </div>
</nav>

<!-- Cart Sidebar -->
<div class="cart-overlay" id="cartOverlay"></div>
<div class="cart-sidebar" id="cartSidebar">
    <div class="cart-sidebar-header">
        <h3>PANIER</h3>
        <i class="fas fa-times cart-close" id="cartClose"></i>
    </div>
    <div class="cart-sidebar-body" id="cartBody">
        <!-- Empty Cart State -->
        <div class="cart-empty" id="cartEmpty">
            <i class="fas fa-shopping-bag"></i>
            <p>Votre panier est vide</p>
            <a href="{{ route('products.index') }}" class="cart-btn cart-btn-secondary">Continuer vos achats</a>
        </div>
        
        <!-- Cart Items Container -->
        <div id="cartItems" style="display: none;">
            <!-- Cart items will be dynamically added here -->
        </div>
    </div>
    <div class="cart-sidebar-footer" id="cartFooter" style="display: none;">
        <div class="cart-subtotal">
            <span class="cart-subtotal-label">SOUS-TOTAL</span>
            <span class="cart-subtotal-amount" id="cartTotal">0.00 MAD</span>
        </div>
        <div class="cart-actions">
            <a href="{{ route('checkout.index') }}" class="cart-btn cart-btn-primary">Commander</a>
            <a href="{{ route('cart.index') }}" class="cart-btn cart-btn-secondary">Voir le panier</a>
        </div>
    </div>
</div>

<script>
    // Sidebar Toggle
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('active');
        sidebarOverlay.classList.toggle('active');
    });

    sidebarOverlay.addEventListener('click', () => {
        sidebar.classList.remove('active');
        sidebarOverlay.classList.remove('active');
    });

    // Sidebar Close Button
    const sidebarClose = document.getElementById('sidebarClose');
    sidebarClose.addEventListener('click', () => {
        sidebar.classList.remove('active');
        sidebarOverlay.classList.remove('active');
    });

    // Cart Sidebar Toggle
    const cartToggle = document.getElementById('cartToggle');
    const cartSidebar = document.getElementById('cartSidebar');
    const cartOverlay = document.getElementById('cartOverlay');
    const cartClose = document.getElementById('cartClose');

    cartToggle.addEventListener('click', () => {
        cartSidebar.classList.add('active');
        cartOverlay.classList.add('active');
    });

    cartClose.addEventListener('click', () => {
        cartSidebar.classList.remove('active');
        cartOverlay.classList.remove('active');
    });

    cartOverlay.addEventListener('click', () => {
        cartSidebar.classList.remove('active');
        cartOverlay.classList.remove('active');
    });

    // Charger le panier depuis le serveur
    function loadCart() {
        fetch('/cart/data')
            .then(response => response.json())
            .then(data => {
                console.log('Cart data:', data);
                updateCartUI(data);
            })
            .catch(error => console.error('Erreur lors du chargement du panier:', error));
    }

    // Mettre à jour l'interface du panier
    function updateCartUI(data) {
        const cartCount = document.getElementById('cartCount');
        const cartEmpty = document.getElementById('cartEmpty');
        const cartItems = document.getElementById('cartItems');
        const cartFooter = document.getElementById('cartFooter');
        const cartTotal = document.getElementById('cartTotal');

        // Mettre à jour le compteur
        cartCount.textContent = data.count || 0;

        if (!data.items || data.items.length === 0) {
            cartEmpty.style.display = 'block';
            cartItems.style.display = 'none';
            cartFooter.style.display = 'none';
        } else {
            cartEmpty.style.display = 'none';
            cartItems.style.display = 'block';
            cartFooter.style.display = 'block';

            // Afficher les articles
            cartItems.innerHTML = data.items.map((item) => {
                const product = item.item.product;
                const variant = item.item.variant;
                const image = product.images && product.images.length > 0 
                    ? `/storage/${product.images[0].image_path}` 
                    : '/images/No-Product-Image-Available.webp';
                
                return `
                    <div class="cart-item">
                        <div class="cart-item-image">
                            <img src="${image}" alt="${product.name}">
                        </div>
                        <div class="cart-item-details">
                            <div class="cart-item-title">${product.name}</div>
                            <div class="cart-item-options">
                                ${variant && variant.size ? 'Taille: ' + variant.size.value : ''} 
                                ${variant && variant.color ? '| Couleur: ' + variant.color.value : ''}
                            </div>
                            <div class="cart-item-price">${parseFloat(item.price).toFixed(2)} DH</div>
                            <div class="cart-item-quantity">
                                <button class="qty-btn" onclick="updateCartQuantity(${item.item.id}, ${item.item.quantity - 1})">-</button>
                                <span class="qty-value">${item.item.quantity}</span>
                                <button class="qty-btn" onclick="updateCartQuantity(${item.item.id}, ${item.item.quantity + 1})">+</button>
                            </div>
                        </div>
                        <i class="fas fa-trash cart-item-remove" onclick="removeCartItem(${item.item.id})"></i>
                    </div>
                `;
            }).join('');

            // Afficher le total
            cartTotal.textContent = data.total.toFixed(2) + ' DH';
        }
    }

    // Mettre à jour la quantité d'un article
    function updateCartQuantity(cartItemId, newQuantity) {
        if (newQuantity < 1) {
            removeCartItem(cartItemId);
            return;
        }

        fetch(`/cart/${cartItemId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ quantity: newQuantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadCart();
            }
        })
        .catch(error => console.error('Erreur:', error));
    }

    // Supprimer un article du panier
    function removeCartItem(cartItemId) {
        fetch(`/cart/${cartItemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadCart();
            }
        })
        .catch(error => console.error('Erreur:', error));
    }

    // Ouvrir le panier
    function openCart() {
        cartSidebar.classList.add('active');
        cartOverlay.classList.add('active');
        loadCart();
    }

    // Charger le panier au chargement de la page
    window.addEventListener('DOMContentLoaded', loadCart);
    
    // Recharger le panier quand on clique sur l'icône
    cartToggle.addEventListener('click', loadCart);
</script>
