# 🔐 Système de Sessions Séparées - Admin & Client

Ce document explique comment fonctionne le système de sessions séparées pour les administrateurs et les clients dans l'application BOLDROOTS.

## 📋 Vue d'ensemble

L'application utilise **deux guards Laravel distincts** pour gérer séparément les sessions admin et client :

- **Guard `admin`** : Pour les administrateurs (Super Admin, Admin)
- **Guard `web`** : Pour les clients (utilisateurs front-office)

Cela permet à un administrateur et un client de se connecter **simultanément** sur le même navigateur sans conflit de session.

---

## 🔧 Configuration

### 1. Guards d'authentification (`config/auth.php`)

```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    'admin' => [
        'driver' => 'session',
        'provider' => 'admins',
    ],
],

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
    'admins' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
],
```

**Note :** Les deux guards utilisent le même modèle `User`, mais avec des sessions séparées.

---

## 🛡️ Middlewares

### Middlewares créés

1. **`AdminAuth`** (`app/Http/Middleware/AdminAuth.php`)
   - Vérifie que l'utilisateur est connecté avec le guard `admin`
   - Vérifie que l'utilisateur a le rôle `Super Admin` ou `Admin`
   - Redirige vers `/admin/login` si non authentifié

2. **`ClientAuth`** (`app/Http/Middleware/ClientAuth.php`)
   - Vérifie que l'utilisateur est connecté avec le guard `web`
   - Redirige vers `/login` si non authentifié

3. **`RedirectIfAdmin`** (`app/Http/Middleware/RedirectIfAdmin.php`)
   - Redirige les admins déjà connectés vers le dashboard admin
   - Utilisé sur la page de login admin

### Enregistrement des middlewares (`bootstrap/app.php`)

```php
$middleware->alias([
    // ... autres middlewares
    'admin.auth' => \App\Http\Middleware\AdminAuth::class,
    'client.auth' => \App\Http\Middleware\ClientAuth::class,
    'admin.guest' => \App\Http\Middleware\RedirectIfAdmin::class,
]);
```

---

## 🚀 Routes

### Routes Admin (`routes/auth.php`)

```php
// Login Admin
Route::middleware('admin.guest')->prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.post');
});

// Logout Admin
Route::middleware('admin.auth')->prefix('admin')->name('admin.')->group(function () {
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
});
```

**URLs Admin :**
- Login : `/admin/login`
- Logout : `/admin/logout`

### Routes Client (`routes/auth.php`)

```php
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
```

**URLs Client :**
- Login : `/login`
- Logout : `/logout`

### Routes protégées (`routes/web.php`)

```php
// Routes Administration (Guard: admin)
Route::middleware(['admin.auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    // ... autres routes admin
});

// Routes Client (Guard: web)
Route::middleware(['auth', 'verified'])->group(function () {
    // Routes pour l'espace client
});
```

---

## 💻 Utilisation dans les Controllers

### Controller Admin (`AdminAuthController.php`)

```php
// Login
if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
    $user = Auth::guard('admin')->user();
    
    if ($user->hasRole(['Super Admin', 'Admin'])) {
        return redirect()->route('admin.users.index');
    }
    
    Auth::guard('admin')->logout();
    return redirect()->route('admin.login')->withErrors([...]);
}

// Logout
Auth::guard('admin')->logout();
return redirect()->route('admin.login');

// Vérifier si admin connecté
if (Auth::guard('admin')->check()) {
    // Admin connecté
}

// Récupérer l'admin connecté
$admin = Auth::guard('admin')->user();
```

### Controller Client

```php
// Login
if (Auth::guard('web')->attempt($credentials)) {
    return redirect()->route('home');
}

// Logout
Auth::guard('web')->logout();
return redirect()->route('login');

// Vérifier si client connecté
if (Auth::guard('web')->check()) {
    // Client connecté
}

// Récupérer le client connecté
$client = Auth::guard('web')->user();
```

---

## 🎯 Utilisation dans les Vues Blade

### Vérifier l'authentification

```blade
{{-- Vérifier si admin connecté --}}
@auth('admin')
    <p>Bienvenue Admin : {{ Auth::guard('admin')->user()->name }}</p>
@endauth

{{-- Vérifier si client connecté --}}
@auth('web')
    <p>Bienvenue Client : {{ Auth::guard('web')->user()->name }}</p>
@endauth

{{-- Vérifier si admin non connecté --}}
@guest('admin')
    <a href="{{ route('admin.login') }}">Connexion Admin</a>
@endguest

{{-- Vérifier si client non connecté --}}
@guest('web')
    <a href="{{ route('login') }}">Connexion Client</a>
@endguest
```

### Récupérer l'utilisateur connecté

```blade
{{-- Admin --}}
{{ Auth::guard('admin')->user()->name }}

{{-- Client --}}
{{ Auth::guard('web')->user()->name }}
{{ Auth::user()->name }} {{-- Équivalent à web (guard par défaut) --}}
```

---

## 📊 Flux d'authentification

### Flux Admin

```
1. Utilisateur accède à /admin/login
2. Middleware 'admin.guest' vérifie si déjà connecté
3. Si non connecté → Affiche formulaire login
4. Soumission du formulaire → AdminAuthController@login
5. Tentative avec Auth::guard('admin')->attempt()
6. Si succès + rôle admin → Redirection vers /admin/users
7. Si succès + pas admin → Déconnexion + erreur
8. Si échec → Retour au login avec erreur
```

### Flux Client

```
1. Utilisateur accède à /login
2. Middleware 'guest' vérifie si déjà connecté
3. Si non connecté → Affiche formulaire login
4. Soumission du formulaire → AuthenticatedSessionController@store
5. Tentative avec Auth::guard('web')->attempt()
6. Si succès → Redirection vers /home
7. Si échec → Retour au login avec erreur
```

---

## 🔑 Avantages du système

✅ **Sessions indépendantes** : Admin et client peuvent être connectés simultanément
✅ **Sécurité renforcée** : Séparation claire des espaces admin/client
✅ **Flexibilité** : Possibilité de tester l'espace client tout en étant admin
✅ **Gestion des rôles** : Vérification automatique des permissions admin
✅ **URLs distinctes** : `/admin/login` vs `/login`

---

## 🧪 Tests

### Tester la connexion Admin

1. Accéder à `/admin/login`
2. Se connecter avec un compte admin
3. Vérifier la redirection vers `/admin/users`
4. Ouvrir un nouvel onglet et accéder à `/login`
5. Se connecter avec un compte client
6. Les deux sessions doivent coexister

### Tester la protection des routes

```bash
# Sans authentification admin
curl http://localhost/admin/users
# Devrait rediriger vers /admin/login

# Sans authentification client
curl http://localhost/mon-compte
# Devrait rediriger vers /login
```

---

## 📝 Credentials de test

### Admin
- **Email** : `admin@boldroots.com`
- **Password** : `BoldRoots2026`
- **Rôle** : Super Admin

### Client
- Créer un compte via `/register`
- Ou utiliser un compte client existant

---

## 🔄 Migration depuis l'ancien système

Si vous aviez un système avec un seul guard, voici les changements à faire :

1. **Dans les controllers** : Remplacer `Auth::` par `Auth::guard('admin')::` ou `Auth::guard('web')::`
2. **Dans les vues** : Utiliser `@auth('admin')` au lieu de `@auth`
3. **Dans les routes** : Utiliser `admin.auth` au lieu de `auth` pour les routes admin
4. **Dans les middlewares** : Spécifier le guard dans les redirections

---

## 🐛 Dépannage

### Problème : "Session expired" après login admin
**Solution** : Vider le cache et les sessions
```bash
php artisan cache:clear
php artisan session:clear
php artisan config:clear
```

### Problème : Admin redirigé vers login client
**Solution** : Vérifier que les routes utilisent bien le middleware `admin.auth`

### Problème : Les deux sessions se déconnectent ensemble
**Solution** : Vérifier que les guards sont bien configurés dans `config/auth.php`

---

## 📚 Ressources

- [Documentation Laravel Guards](https://laravel.com/docs/11.x/authentication#authentication-quickstart)
- [Documentation Laravel Middleware](https://laravel.com/docs/11.x/middleware)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)

---

**Dernière mise à jour** : 10 Janvier 2026
**Version** : 1.0
