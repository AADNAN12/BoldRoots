<?php

// Admin Controllers
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Helpers\LogActivity;
// Front-Office Controllers
use App\Http\Controllers\FrontOffice\HomeController;
use App\Http\Controllers\FrontOffice\ProductController;
use App\Http\Controllers\FrontOffice\AboutController;
use App\Http\Controllers\FrontOffice\ContactController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Routes de maintenance (sans middleware)
Route::get('/maintenance', [\App\Http\Controllers\FrontOffice\MaintenanceController::class, 'index'])->name('maintenance.index');
Route::post('/maintenance/verify', [\App\Http\Controllers\FrontOffice\MaintenanceController::class, 'verify'])->name('maintenance.verify');

// Route newsletter
Route::post('/newsletter/subscribe', [\App\Http\Controllers\FrontOffice\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Routes publiques
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');
Route::get('/packs', [HomeController::class, 'packs'])->name('packs');
Route::get('/terms-conditions', [HomeController::class, 'terms_conditions'])->name('terms-conditions');
Route::get('/privacy-policy', [HomeController::class, 'privacy_policy'])->name('privacy-policy');

// Pages CMS dynamiques
Route::get('/page/{slug}', [\App\Http\Controllers\FrontOffice\CmsPageController::class, 'show'])->name('cms.show');

// Routes des produits
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/products/category/{slug}', [ProductController::class, 'category'])->name('products.category');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');






// ===================================
// ROUTES ADMINISTRATION (Guard: admin)
// ===================================
Route::middleware(['admin.auth'])->prefix('admin')->name('admin.')->group(function () {

    // Page de bienvenue admin
    Route::get('/welcome', [\App\Http\Controllers\Admin\WelcomeController::class, 'index'])->name('welcome');

    // Gestion des utilisateurs
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    Route::post('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
    Route::post('/users/{user}/roles', [UserController::class, 'updateRoles'])->name('users.roles.update');
    Route::post('/users/{user}/change-password', [UserController::class, 'changePassword'])->name('users.change-password');
    Route::post('/users/{user}/permissions', [UserController::class, 'updatePermissions'])->name('users.permissions.update');
    Route::post('users/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Gestion des rôles
    Route::resource('roles', RoleController::class);

    // Gestion des categories
    Route::resource('categories', AdminCategoryController::class);

    // Gestion des attributs (Couleurs et Tailles)
    Route::resource('attributes', \App\Http\Controllers\Admin\AttributeController::class)->except(['show', 'create', 'edit']);
    
    // Gestion des valeurs d'attributs
    Route::prefix('attributes/{attribute}')->name('attribute-values.')->group(function () {
        Route::get('/values', [\App\Http\Controllers\Admin\AttributeValueController::class, 'index'])->name('index');
        Route::post('/values', [\App\Http\Controllers\Admin\AttributeValueController::class, 'store'])->name('store');
        Route::put('/values/{attributeValue}', [\App\Http\Controllers\Admin\AttributeValueController::class, 'update'])->name('update');
        Route::delete('/values/{attributeValue}', [\App\Http\Controllers\Admin\AttributeValueController::class, 'destroy'])->name('destroy');
    });

    // Gestion des produits
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::delete('products/{product}/images/{image}', [\App\Http\Controllers\Admin\ProductController::class, 'deleteImage'])->name('products.images.destroy');
    Route::post('products/{product}/set-homepage-image', [\App\Http\Controllers\Admin\ProductController::class, 'setHomepageImage'])->name('products.set-homepage-image');

    // Gestion des promotions
    Route::resource('promotions', \App\Http\Controllers\Admin\PromotionController::class);
    Route::post('promotions/{promotion}/toggle-status', [\App\Http\Controllers\Admin\PromotionController::class, 'toggleStatus'])->name('promotions.toggle-status');
    Route::post('promotions/{promotion}/duplicate', [\App\Http\Controllers\Admin\PromotionController::class, 'duplicate'])->name('promotions.duplicate');
    Route::get('promotions-stats', [\App\Http\Controllers\Admin\PromotionController::class, 'stats'])->name('promotions.stats');

    // Gestion des coupons
    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);
    Route::post('coupons/{coupon}/toggle-status', [\App\Http\Controllers\Admin\CouponController::class, 'toggleStatus'])->name('coupons.toggle-status');
    Route::post('coupons/{coupon}/duplicate', [\App\Http\Controllers\Admin\CouponController::class, 'duplicate'])->name('coupons.duplicate');
    Route::get('coupons-bulk-create', [\App\Http\Controllers\Admin\CouponController::class, 'bulkCreate'])->name('coupons.bulk-create');
    Route::post('coupons-bulk-store', [\App\Http\Controllers\Admin\CouponController::class, 'bulkStore'])->name('coupons.bulk-store');
    Route::post('coupons-validate', [\App\Http\Controllers\Admin\CouponController::class, 'validateCouponCode'])->name('coupons.validate');
    Route::get('coupons-stats', [\App\Http\Controllers\Admin\CouponController::class, 'stats'])->name('coupons.stats');
    Route::get('coupons-export', [\App\Http\Controllers\Admin\CouponController::class, 'export'])->name('coupons.export');

    // Gestion des commandes
    Route::get('orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders-stats', [\App\Http\Controllers\Admin\OrderController::class, 'stats'])->name('orders.stats');
    Route::get('orders-export', [\App\Http\Controllers\Admin\OrderController::class, 'export'])->name('orders.export');
    Route::put('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('orders/{order}/payment-status', [\App\Http\Controllers\Admin\OrderController::class, 'updatePaymentStatus'])->name('orders.update-payment-status');
    Route::post('orders/{order}/cancel', [\App\Http\Controllers\Admin\OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('orders/{order}/generate-invoice', [\App\Http\Controllers\Admin\OrderController::class, 'generateInvoice'])->name('orders.generate-invoice');
    Route::get('orders/{order}/generate-delivery-note', [\App\Http\Controllers\Admin\OrderController::class, 'generateDeliveryNote'])->name('orders.generate-delivery-note');
    Route::get('orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');

    // Gestion des factures
    Route::get('invoices', [\App\Http\Controllers\Admin\InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/{invoice}', [\App\Http\Controllers\Admin\InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('invoices/{invoice}/pdf', [\App\Http\Controllers\Admin\InvoiceController::class, 'downloadPDF'])->name('invoices.download-pdf');
    Route::get('invoices/{invoice}/generate-pdf', [\App\Http\Controllers\Admin\InvoiceController::class, 'generatePDF'])->name('invoices.generate-pdf');
    Route::post('invoices/{invoice}/status', [\App\Http\Controllers\Admin\InvoiceController::class, 'updateStatus'])->name('invoices.update-status');
    Route::post('invoices/{invoice}/mark-paid', [\App\Http\Controllers\Admin\InvoiceController::class, 'markAsPaid'])->name('invoices.mark-paid');
    Route::post('invoices/{invoice}/cancel', [\App\Http\Controllers\Admin\InvoiceController::class, 'cancel'])->name('invoices.cancel');
    Route::get('invoices/{invoice}/send-email', [\App\Http\Controllers\Admin\InvoiceController::class, 'sendEmail'])->name('invoices.send-email');

    // Gestion des bons de livraison
    Route::get('delivery-notes', [\App\Http\Controllers\Admin\DeliveryNoteController::class, 'index'])->name('delivery-notes.index');
    Route::get('delivery-notes/{deliveryNote}', [\App\Http\Controllers\Admin\DeliveryNoteController::class, 'show'])->name('delivery-notes.show');
    Route::get('delivery-notes/{deliveryNote}/pdf', [\App\Http\Controllers\Admin\DeliveryNoteController::class, 'downloadPDF'])->name('delivery-notes.download-pdf');
    Route::get('delivery-notes/{deliveryNote}/generate-pdf', [\App\Http\Controllers\Admin\DeliveryNoteController::class, 'generatePDF'])->name('delivery-notes.generate-pdf');
    Route::post('delivery-notes/{deliveryNote}/status', [\App\Http\Controllers\Admin\DeliveryNoteController::class, 'updateStatus'])->name('delivery-notes.update-status');
    Route::post('delivery-notes/{deliveryNote}/tracking', [\App\Http\Controllers\Admin\DeliveryNoteController::class, 'updateTracking'])->name('delivery-notes.update-tracking');
    Route::post('delivery-notes/{deliveryNote}/mark-delivered', [\App\Http\Controllers\Admin\DeliveryNoteController::class, 'markAsDelivered'])->name('delivery-notes.mark-delivered');

    // Gestion des méthodes de livraison
    Route::resource('shipping-methods', \App\Http\Controllers\Admin\ShippingMethodController::class);
    Route::post('shipping-methods/{shippingMethod}/toggle-status', [\App\Http\Controllers\Admin\ShippingMethodController::class, 'toggleStatus'])->name('shipping-methods.toggle-status');

    // Gestion des clients
    Route::get('customers', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/{customer}', [\App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('customers.show');
    Route::get('customers/{customer}/edit', [\App\Http\Controllers\Admin\CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('customers/{customer}', [\App\Http\Controllers\Admin\CustomerController::class, 'update'])->name('customers.update');
    Route::delete('customers/{customer}', [\App\Http\Controllers\Admin\CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::patch('customers/{customer}/toggle-status', [\App\Http\Controllers\Admin\CustomerController::class, 'toggleStatus'])->name('customers.toggle-status');

    // Gestion des informations d'entreprise
    Route::get('company-info', [\App\Http\Controllers\Admin\CompanyInfoController::class, 'index'])->name('company-info.index');
    Route::put('company-info', [\App\Http\Controllers\Admin\CompanyInfoController::class, 'update'])->name('company-info.update');

    // Gestion des paramètres du site
    Route::get('settings', [\App\Http\Controllers\Admin\SiteSettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [\App\Http\Controllers\Admin\SiteSettingsController::class, 'update'])->name('settings.update');

    // Gestion des pages CMS
    Route::resource('cms-pages', \App\Http\Controllers\Admin\CmsPagesController::class);

    //Routes LogActivity
    Route::get('/logActivity', [LogActivity::class, 'index'])->name('logActivity');
});

// ===================================
// ROUTES CLIENT (Guard: web)
// ===================================

// Routes publiques du panier (accessible sans authentification)
Route::get('/cart', [\App\Http\Controllers\Front\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [\App\Http\Controllers\Front\CartController::class, 'add'])->name('cart.add');
Route::post('/cart/coupon', [\App\Http\Controllers\Front\CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
Route::delete('/cart/coupon', [\App\Http\Controllers\Front\CartController::class, 'removeCoupon'])->name('cart.remove-coupon');
Route::get('/cart/count', [\App\Http\Controllers\Front\CartController::class, 'getCount'])->name('cart.count');
Route::get('/cart/data', [\App\Http\Controllers\Front\CartController::class, 'getData'])->name('cart.data');
Route::put('/cart/{cartItem}', [\App\Http\Controllers\Front\CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{cartItem}', [\App\Http\Controllers\Front\CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart', [\App\Http\Controllers\Front\CartController::class, 'clear'])->name('cart.clear');

// Checkout
Route::get('/checkout', [\App\Http\Controllers\Front\CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/validate-cart', [\App\Http\Controllers\Front\CheckoutController::class, 'validateCart'])->name('checkout.validate-cart');
Route::post('/checkout/calculate-shipping', [\App\Http\Controllers\Front\CheckoutController::class, 'calculateShipping'])->name('checkout.calculate-shipping');
Route::post('/checkout/process', [\App\Http\Controllers\Front\CheckoutController::class, 'processPayment'])->name('checkout.process');
Route::get('/checkout/success/{order}', [\App\Http\Controllers\Front\CheckoutController::class, 'success'])->name('checkout.success');
// Routes authentifiées pour les clients
Route::middleware(['auth', 'verified'])->group(function () {

    // Mes commandes
    Route::get('/my-orders', [\App\Http\Controllers\Front\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\Front\OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [\App\Http\Controllers\Front\OrderController::class, 'downloadInvoice'])->name('orders.download-invoice');
    Route::get('/orders/{order}/track', [\App\Http\Controllers\Front\OrderController::class, 'trackOrder'])->name('orders.track');
    Route::post('/orders/{order}/cancel', [\App\Http\Controllers\Front\OrderController::class, 'cancel'])->name('orders.cancel');

    // Mon profil
    Route::get('/profile', [\App\Http\Controllers\Front\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [\App\Http\Controllers\Front\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\Front\ProfileController::class, 'updatePassword'])->name('profile.password');
});




require __DIR__ . '/auth.php';
