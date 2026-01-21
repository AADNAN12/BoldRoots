# Modifications des Vues - Suppression de la table Addresses

## Vues à Modifier

Les vues suivantes utilisent `$order->shippingAddress` ou `$order->billingAddress` et doivent être modifiées pour utiliser `$order->user` à la place.

### 1. Factures PDF
**Fichier:** `resources/views/admin/invoices/pdf/invoice.blade.php`

**Remplacer:**
```blade
@if($invoice->order->billingAddress)
    <p class="mb-0"><strong>{{ $invoice->order->billingAddress->first_name }} {{ $invoice->order->billingAddress->last_name }}</strong></p>
    <p class="mb-0">{{ $invoice->order->billingAddress->address_line1 }}</p>
    @if($invoice->order->billingAddress->address_line2)
        <p class="mb-0">{{ $invoice->order->billingAddress->address_line2 }}</p>
    @endif
    <p class="mb-0">{{ $invoice->order->billingAddress->postal_code }} {{ $invoice->order->billingAddress->city }}</p>
    <p class="mb-0">Tél: {{ $invoice->order->billingAddress->phone }}</p>
@endif
```

**Par:**
```blade
@if($invoice->order->user)
    <p class="mb-0"><strong>{{ $invoice->order->user->name }}</strong></p>
    <p class="mb-0">{{ $invoice->order->user->address_line1 }}</p>
    @if($invoice->order->user->address_line2)
        <p class="mb-0">{{ $invoice->order->user->address_line2 }}</p>
    @endif
    @if($invoice->order->user->postal_code && $invoice->order->user->city)
        <p class="mb-0">{{ $invoice->order->user->postal_code }} {{ $invoice->order->user->city }}</p>
    @endif
    <p class="mb-0">Tél: {{ $invoice->order->user->phone }}</p>
@endif
```

---

### 2. Bons de Livraison PDF
**Fichier:** `resources/views/admin/delivery-notes/pdf/delivery-note.blade.php`

**Remplacer:**
```blade
@if($deliveryNote->order->shippingAddress)
    <p class="mb-0"><strong>{{ $deliveryNote->order->shippingAddress->first_name }} {{ $deliveryNote->order->shippingAddress->last_name }}</strong></p>
    <p class="mb-0">{{ $deliveryNote->order->shippingAddress->address_line1 }}</p>
    @if($deliveryNote->order->shippingAddress->address_line2)
        <p class="mb-0">{{ $deliveryNote->order->shippingAddress->address_line2 }}</p>
    @endif
    <p class="mb-0">{{ $deliveryNote->order->shippingAddress->postal_code }} {{ $deliveryNote->order->shippingAddress->city }}</p>
    <p class="mb-0">Tél: {{ $deliveryNote->order->shippingAddress->phone }}</p>
@endif
```

**Par:**
```blade
@if($deliveryNote->order->user)
    <p class="mb-0"><strong>{{ $deliveryNote->order->user->name }}</strong></p>
    <p class="mb-0">{{ $deliveryNote->order->user->address_line1 }}</p>
    @if($deliveryNote->order->user->address_line2)
        <p class="mb-0">{{ $deliveryNote->order->user->address_line2 }}</p>
    @endif
    @if($deliveryNote->order->user->postal_code && $deliveryNote->order->user->city)
        <p class="mb-0">{{ $deliveryNote->order->user->postal_code }} {{ $deliveryNote->order->user->city }}</p>
    @endif
    <p class="mb-0">Tél: {{ $deliveryNote->order->user->phone }}</p>
@endif
```

---

### 3. Page de Détails de Commande (Front)
**Fichier:** `resources/views/front-office/orders/show.blade.php`

**Remplacer les deux sections (Livraison et Facturation):**
```blade
<div class="col-md-6">
    <h6>Adresse de Livraison</h6>
    @if($order->shippingAddress)
        <address>
            <strong>{{ $order->shippingAddress->first_name }} {{ $order->shippingAddress->last_name }}</strong><br>
            {{ $order->shippingAddress->address_line1 }}<br>
            @if($order->shippingAddress->address_line2)
                {{ $order->shippingAddress->address_line2 }}<br>
            @endif
            {{ $order->shippingAddress->postal_code }} {{ $order->shippingAddress->city }}<br>
            Tél: {{ $order->shippingAddress->phone }}
        </address>
    @endif
</div>
<div class="col-md-6">
    <h6>Adresse de Facturation</h6>
    @if($order->billingAddress)
        <address>
            <strong>{{ $order->billingAddress->first_name }} {{ $order->billingAddress->last_name }}</strong><br>
            {{ $order->billingAddress->address_line1 }}<br>
            @if($order->billingAddress->address_line2)
                {{ $order->billingAddress->address_line2 }}<br>
            @endif
            {{ $order->billingAddress->postal_code }} {{ $order->billingAddress->city }}<br>
            Tél: {{ $order->billingAddress->phone }}
        </address>
    @endif
</div>
```

**Par:**
```blade
<div class="col-md-12">
    <h6>Informations de Livraison</h6>
    @if($order->user)
        <address>
            <strong>{{ $order->user->name }}</strong><br>
            {{ $order->user->address_line1 }}<br>
            @if($order->user->address_line2)
                {{ $order->user->address_line2 }}<br>
            @endif
            @if($order->user->postal_code && $order->user->city)
                {{ $order->user->postal_code }} {{ $order->user->city }}<br>
            @endif
            Tél: {{ $order->user->phone }}
        </address>
    @endif
</div>
```

---

### 4. Page de Suivi de Commande (Front)
**Fichier:** `resources/views/front-office/orders/track.blade.php`

**Remplacer:**
```blade
<h5 class="mb-0">Adresse de Livraison</h5>
@if($order->shippingAddress)
    <address>
        <strong>{{ $order->shippingAddress->first_name }} {{ $order->shippingAddress->last_name }}</strong><br>
        {{ $order->shippingAddress->address_line1 }}<br>
        @if($order->shippingAddress->address_line2)
            {{ $order->shippingAddress->address_line2 }}<br>
        @endif
        {{ $order->shippingAddress->postal_code }} {{ $order->shippingAddress->city }}<br>
        Tél: {{ $order->shippingAddress->phone }}
    </address>
@endif
```

**Par:**
```blade
<h5 class="mb-0">Adresse de Livraison</h5>
@if($order->user)
    <address>
        <strong>{{ $order->user->name }}</strong><br>
        {{ $order->user->address_line1 }}<br>
        @if($order->user->address_line2)
            {{ $order->user->address_line2 }}<br>
        @endif
        @if($order->user->postal_code && $order->user->city)
            {{ $order->user->postal_code }} {{ $order->user->city }}<br>
        @endif
        Tél: {{ $order->user->phone }}
    </address>
@endif
```

---

### 5. Page de Confirmation de Commande
**Fichier:** `resources/views/front-office/checkout/success.blade.php`

Vérifier et remplacer toute référence à `$order->shippingAddress` par `$order->user`.

---

## Étapes pour Appliquer les Modifications

1. **Exécuter les migrations:**
   ```bash
   php artisan migrate
   ```

2. **Modifier toutes les vues listées ci-dessus**

3. **Supprimer le modèle Address (optionnel):**
   ```bash
   rm app/Models/Address.php
   ```

4. **Tester le flux complet:**
   - Passer une commande en tant qu'invité
   - Passer une commande en tant qu'utilisateur connecté
   - Vérifier les PDFs de factures et bons de livraison
   - Vérifier les pages de détails de commande

---

## Notes Importantes

- Les champs `first_name` et `last_name` n'existent plus séparément, utilisez `name` du user
- Les champs `city` et `postal_code` sont maintenant optionnels dans users
- Le champ `phone` existe déjà dans users
- Toutes les relations avec Address ont été supprimées des modèles Order et User
