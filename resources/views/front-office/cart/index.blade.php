@extends('front-office.layouts.app')

@section('title', 'Mon Panier')

@section('head')
    <!-- Css Styles -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/nice-select.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/slicknav.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css">
@endsection

@section('content')
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option" style="margin-top:120px">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Shopping Cart</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Home</a>
                            <a href="{{ route('products.index') }}">Shop</a>
                            <span>Shopping Cart</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Shopping Cart Section Begin -->
    <section class="shopping-cart spad">
        <div class="container">
            @if($cart && $cart->items->count() > 0)
            <div class="row">
                <div class="col-lg-8">
                    <div class="shopping__cart__table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart->items as $item)
                                <tr data-item-id="{{ $item->id }}">
                                    <td class="product__cart__item">
                                        <div class="product__cart__item__pic">
                                            @if($item->product && $item->product->images->first())
                                                <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" alt="{{ $item->product->name }}">
                                            @else
                                                <img src="{{ asset('images/No-Product-Image-Available.webp') }}" alt="{{ $item->product->name }}">
                                            @endif
                                        </div>
                                        <div class="product__cart__item__text">
                                            <h6>{{ $item->product->name }}</h6>
                                            @if($item->variant)
                                                <p class="text-muted mb-0">
                                                    <small>
                                                        @if($item->variant->size)
                                                            Taille: {{ $item->variant->size->value }}
                                                        @endif
                                                        @if($item->variant->color)
                                                            @if($item->variant->size) | @endif
                                                            Couleur: {{ $item->variant->color->value }}
                                                        @endif
                                                    </small>
                                                </p>
                                            @endif
                                            <h5>{{ number_format($item->product->price, 2) }} DH</h5>
                                        </div>
                                    </td>
                                    <td class="quantity__item">
                                        <div class="quantity">
                                            <div class="pro-qty-2" data-item-id="{{ $item->id }}">
                                                <input type="text" value="{{ $item->quantity }}" id="qty_{{ $item->id }}">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="cart__price item-total">{{ number_format($item->product->price * $item->quantity, 2) }} DH</td>
                                    <td class="cart__close" onclick="removeItem({{ $item->id }})"><i class="fa fa-close" style="cursor: pointer;"></i></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="continue__btn">
                                <a href="{{ route('products.index') }}">Continue Shopping</a>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="continue__btn update__btn">
                                <a href="#" onclick="clearCart(); return false;"><i class="fa fa-trash"></i> Clear cart</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="cart__discount">
                        <h6>Discount codes</h6>
                        @if($appliedCoupon)
                            <div class="alert alert-success">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $appliedCoupon->code }}</strong><br>
                                        <small>{{ $appliedCoupon->description }}</small>
                                    </div>
                                    <button class="btn btn-sm btn-outline-danger" onclick="removeCoupon()">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        @else
                            <form id="couponForm" onsubmit="applyCoupon(event)">
                                <input type="text" id="couponCode" placeholder="Coupon code">
                                <button type="submit">Apply</button>
                            </form>
                            <div id="couponError" class="text-danger mt-2" style="display: none;"></div>
                        @endif
                    </div>
                    <div class="cart__total">
                        <h6>Cart total</h6>
                        <ul>
                            <li>Subtotal <span id="subtotal">{{ number_format($totals['subtotal'], 2) }} DH</span></li>
                            @if($totals['discount'] > 0)
                                <li class="text-success">Discount <span id="discount">-{{ number_format($totals['discount'], 2) }} DH</span></li>
                            @endif
                            <li>Total <span id="total">{{ number_format($totals['total'], 2) }} DH</span></li>
                        </ul>
                        <a href="{{ route('checkout.index') }}" class="primary-btn">Proceed to checkout</a>
                    </div>
                </div>
            </div>
            @else
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center py-5">
                        <i class="fa fa-shopping-cart" style="font-size: 5rem; color: #ccc; margin-bottom: 2rem;"></i>
                        <h3>Your cart is empty</h3>
                        <p class="text-muted mb-4">Discover our products and add them to your cart</p>
                        <a href="{{ route('products.index') }}" class="primary-btn">
                            <i class="fa fa-shopping-bag"></i> Shop Now
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
    <!-- Shopping Cart Section End -->
@endsection

@section('scripts')
 <!-- Js Plugins -->
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('js/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('js/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('js/jquery.slicknav.js') }}"></script>
    <script src="{{ asset('js/mixitup.min.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>

    <script>
    // Attendre que main.js ait initialisé les boutons +/-
    $(document).ready(function() {
        // Écouter les clics sur les boutons +/- créés par main.js
        $('.pro-qty-2').on('click', '.qtybtn', function() {
            var $proQty = $(this).parent();
            var itemId = $proQty.data('item-id');
            
            // Attendre que main.js ait mis à jour la valeur
            setTimeout(function() {
                var newQuantity = $proQty.find('input').val();
                updateQuantity(itemId, newQuantity);
            }, 100);
        });
        
        // Écouter aussi les changements manuels dans l'input
        $('.pro-qty-2 input').on('change', function() {
            var itemId = $(this).parent().data('item-id');
            var newQuantity = $(this).val();
            updateQuantity(itemId, newQuantity);
        });
    });
    
    function updateQuantity(itemId, quantity) {
        quantity = parseInt(quantity);
        if (quantity < 1) quantity = 1;
        
        fetch(`/cart/${itemId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error updating cart');
        });
    }

    function removeItem(itemId) {
        if (!confirm('Remove this item from cart?')) return;
        
        fetch(`/cart/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error removing item');
        });
    }

    function clearCart() {
        if (!confirm('Clear your entire cart?')) return;
        
        fetch('/cart', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error clearing cart');
        });
    }

    function applyCoupon(event) {
        event.preventDefault();
        const code = document.getElementById('couponCode').value;
        const errorDiv = document.getElementById('couponError');
        
        fetch('/cart/coupon', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ coupon_code: code })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                errorDiv.textContent = data.message;
                errorDiv.style.display = 'block';
            }
        })
        .catch(error => {
            errorDiv.textContent = 'Error applying coupon';
            errorDiv.style.display = 'block';
        });
    }

    function removeCoupon() {
        fetch('/cart/coupon', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error removing coupon');
        });
    }
    </script>
@endsection
