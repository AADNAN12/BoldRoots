@extends('front-office.layouts.app')

@section('title', 'Finaliser ma commande')

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
    <section class="breadcrumb-option" style="margin-top:120px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Check Out</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Home</a>
                            <a href="{{ route('cart.index') }}">Cart</a>
                            <span>Check Out</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Messages d'erreur et de succès -->
    <div class="container mt-4">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </div>

    <!-- Checkout Section Begin -->
    <section class="checkout spad">
        <div class="container">
            <div class="checkout__form">
                <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                    @csrf
                    <div class="row">
                        <div class="col-lg-8 col-md-6">
                            @if(!$appliedCoupon)
                            <h6 class="coupon__code"><span class="icon_tag_alt"></span> Have a coupon? <a
                                    href="#" onclick="toggleCouponForm(); return false;">Click
                                    here</a> to enter your code</h6>
                            <div id="couponFormSection" style="display: none;" class="mb-4">
                                <div class="input-group">
                                    <input type="text" id="couponCode" class="form-control" placeholder="Enter coupon code">
                                    <button type="button" class="btn btn-primary" onclick="applyCoupon()">Apply</button>
                                </div>
                                <div id="couponError" class="text-danger mt-2" style="display: none;"></div>
                            </div>
                            @else
                            <div class="alert alert-success d-flex justify-content-between align-items-center" style="background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 8px; padding: 15px 20px; margin-bottom: 20px;">
                                <div>
                                    <i class="fa fa-check-circle" style="color: #28a745; margin-right: 8px;"></i>
                                    <strong style="color: #155724;">Coupon applied:</strong> 
                                    <span style="color: #155724; font-weight: 600;">{{ $appliedCoupon->code }}</span>
                                    @if($appliedCoupon->type === 'percentage')
                                        <span style="color: #155724; font-size: 14px;"> ({{ $appliedCoupon->discount_value }}% off)</span>
                                    @elseif($appliedCoupon->type === 'fixed_amount')
                                        <span style="color: #155724; font-size: 14px;"> ({{ number_format($appliedCoupon->discount_value, 2) }} DH off)</span>
                                    @elseif($appliedCoupon->type === 'free_shipping')
                                        <span style="color: #155724; font-size: 14px;"> (Free Shipping)</span>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeCoupon()" style="padding: 5px 15px; font-size: 13px; border-radius: 5px;">
                                    <i class="fa fa-times"></i> Remove
                                </button>
                            </div>
                            @endif
                            <h6 class="checkout__title">Billing Details</h6>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>First Name<span>*</span></p>
                                        <input type="text" name="first_name" value="{{ old('first_name', auth()->user()->name ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Last Name<span>*</span></p>
                                        <input type="text" name="last_name" value="{{ old('last_name') }}" required>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="checkout__input">
                                <p>Country<span>*</span></p>
                                <input type="text">
                            </div> --}}
                            <div class="checkout__input">
                                <p>Address<span>*</span></p>
                                <input type="text" name="address" placeholder="Street Address" class="checkout__input__add" value="{{ old('address') }}" required>
                            </div>
                            {{-- <div class="row">
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>City<span>*</span></p>
                                        <input type="text" name="city" value="{{ old('city') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Postal Code<span>*</span></p>
                                        <input type="text" name="postal_code" value="{{ old('postal_code') }}" required>
                                    </div>
                                </div>
                            </div> --}}
                            {{-- <div class="checkout__input">
                                <p>Town/City<span>*</span></p>
                                <input type="text">
                            </div>
                            <div class="checkout__input">
                                <p>Country/State<span>*</span></p>
                                <input type="text">
                            </div>
                            <div class="checkout__input">
                                <p>Postcode / ZIP<span>*</span></p>
                                <input type="text">
                            </div> --}}
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Phone<span>*</span></p>
                                        <input type="tel" name="phone" value="{{ old('phone') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Email<span>*</span></p>
                                        <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required>
                                    </div>
                                </div>
                            </div>
                            @guest
                            <div class="checkout__input__checkbox">
                                <label for="acc">
                                    Create an account?
                                    <input type="checkbox" id="acc" name="create_account" value="1">
                                    <span class="checkmark"></span>
                                </label>
                                <p>Create an account by entering the information below. If you are a returning customer
                                    please login at the top of the page</p>
                            </div>
                            <div class="checkout__input" id="passwordField" style="display: none;">
                                <p>Account Password<span>*</span></p>
                                <input type="password" name="password" id="accountPassword">
                            </div>
                            @endguest
                            <div class="checkout__input__checkbox">
                                <label for="diff-acc">
                                    Note about your order, e.g, special note for delivery
                                    <input type="checkbox" id="diff-acc">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="checkout__input" id="notesField" style="display: none;">
                                <p>Order notes</p>
                                <input type="text" name="notes" id="orderNotes"
                                    placeholder="Notes about your order, e.g. special notes for delivery.">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="checkout__order">
                                <h4 class="order__title">Your order</h4>
                                <div class="checkout__order__products">Product <span>Total</span></div>
                                <ul class="checkout__total__products">
                                    @foreach($cart->items as $index => $item)
                                    <li>{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}. {{ $item->product->name }} x{{ $item->quantity }} 
                                        <span>{{ number_format($item->product->price * $item->quantity, 2) }} DH</span>
                                    </li>
                                    @endforeach
                                </ul>
                                <ul class="checkout__total__all">
                                    <li>Subtotal <span>{{ number_format($totals['subtotal'], 2) }} DH</span></li>
                                    @if($totals['promotion_discount'] > 0)
                                    <li>Promotion Discount <span class="text-success">-{{ number_format($totals['promotion_discount'], 2) }} DH</span></li>
                                    @endif
                                    @if($totals['coupon_discount'] > 0 && $appliedCoupon)
                                    <li>Coupon ({{ $appliedCoupon->code }}) <span class="text-success">-{{ number_format($totals['coupon_discount'], 2) }} DH</span></li>
                                    @endif
                                    @if($shippingMethods->first())
                                    <li>Shipping <span>{{ number_format($shippingMethods->first()->cost, 2) }} DH</span></li>
                                    @endif
                                    <li>Total <span>{{ number_format($totals['total'] + ($shippingMethods->first() ? $shippingMethods->first()->cost : 0), 2) }} DH</span></li>
                                </ul>
                                <h6 class="mb-3">Payment Method</h6>
                                <div class="checkout__input__checkbox">
                                    <label for="payment_cod">
                                        Cash on Delivery
                                        <input type="radio" name="payment_method" id="payment_cod" value="cash_on_delivery" checked required>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <p class="mb-3">Pay with cash upon delivery.</p>
                                <div class="checkout__input__checkbox">
                                    <label for="payment_stripe">
                                        Stripe (Coming Soon)
                                        <input type="radio" name="payment_method" id="payment_stripe" value="stripe" disabled>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <p class="text-muted mb-3">Online payment via Stripe will be available soon.</p>
                                @if($shippingMethods->first())
                                <input type="hidden" name="shipping_method_id" value="{{ $shippingMethods->first()->id }}">
                                @endif
                                <button type="submit" class="site-btn">PLACE ORDER</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- Checkout Section End -->
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
    // Toggle coupon form
    function toggleCouponForm() {
        $('#couponFormSection').slideToggle();
    }

    // Apply coupon
    function applyCoupon() {
        const code = $('#couponCode').val();
        const errorDiv = $('#couponError');
        
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
                errorDiv.text(data.message).show();
            }
        })
        .catch(error => {
            errorDiv.text('Error applying coupon').show();
        });
    }

    // Remove coupon
    function removeCoupon() {
        if (!confirm('Are you sure you want to remove this coupon?')) {
            return;
        }
        
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
                alert('Error removing coupon: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error removing coupon. Please try again.');
        });
    }

    // Toggle password field when create account is checked
    $('#acc').on('change', function() {
        if ($(this).is(':checked')) {
            $('#passwordField').slideDown();
            $('#accountPassword').prop('required', true);
        } else {
            $('#passwordField').slideUp();
            $('#accountPassword').prop('required', false);
        }
    });

    // Toggle notes field when checkbox is checked
    $('#diff-acc').on('change', function() {
        if ($(this).is(':checked')) {
            $('#notesField').slideDown();
        } else {
            $('#notesField').slideUp();
        }
    });

    // Form validation
    $('#checkoutForm').on('submit', function(e) {
        const paymentMethod = $('input[name="payment_method"]:checked').val();
        
        if (!paymentMethod) {
            e.preventDefault();
            alert('Please select a payment method');
            return false;
        }
        
        // Additional validation can be added here
        return true;
    });
    </script>
@endsection
