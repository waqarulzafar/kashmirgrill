@extends('layouts.master')

@section('title', 'Checkout | Kashmir Grill House Como')
@section('meta_description', 'Complete your Kashmir Grill House order with takeaway, delivery, or dine-in reservation checkout.')
@section('body_class', 'checkout-home-theme')

@php
    $cartItems = collect($cart['items'] ?? []);
    $cartSubtotal = (float) ($cart['subtotal'] ?? 0);
    $cartQuantity = (int) $cartItems->sum('quantity');
    $prefillName = old('full_name', auth()->user()?->name);
    $prefillEmail = old('email', auth()->user()?->email);
    $prefillPhone = old('phone');
    $selectedFulfillment = old('fulfillment_type', \App\Models\Order::FULFILLMENT_TAKEAWAY);
    $selectedPaymentMethod = old('payment_method', \App\Models\Order::PAYMENT_METHOD_STRIPE);
    $fulfillmentMeta = [
        \App\Models\Order::FULFILLMENT_TAKEAWAY => [
            'icon' => 'fa-bag-shopping',
            'note' => 'Pick up at our Como counter.',
        ],
        \App\Models\Order::FULFILLMENT_DELIVERY => [
            'icon' => 'fa-motorcycle',
            'note' => 'Delivered to your saved address.',
        ],
        \App\Models\Order::FULFILLMENT_DINE_IN => [
            'icon' => 'fa-utensils',
            'note' => 'Reserve table, time, and guests.',
        ],
    ];
    $paymentMeta = [
        \App\Models\Order::PAYMENT_METHOD_STRIPE => [
            'icon' => 'fa-solid fa-credit-card',
            'note' => 'Pay securely by card via Stripe Checkout.',
        ],
        \App\Models\Order::PAYMENT_METHOD_PAYPAL => [
            'icon' => 'fa-brands fa-paypal',
            'note' => 'Pay with your PayPal wallet or linked card.',
        ],
    ];
@endphp

@section('content')
    <section class="container pt-0 pb-4 pb-lg-5 checkout-page" data-checkout-page data-slots-url="{{ route('checkout.slots') }}">
        <div class="checkout-shell">
            @if ($errors->any())
                <div class="alert alert-danger checkout-alert">
                    <strong>Please fix the following:</strong>
                    <ul class="mb-0 mt-2 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-4">
                <div class="col-12 col-xl-8">
                    <article class="checkout-card checkout-card--form">
                        <form method="POST" action="{{ route('checkout.store') }}" class="row g-4">
                            @csrf

                            <div class="col-12 checkout-block">
                                <div class="checkout-block-head">
                                    <p class="checkout-block-step mb-1">Step 1</p>
                                    <h3 class="checkout-block-title mb-0">Contact Details</h3>
                                </div>
                                <div class="row g-3 mt-1">
                                    <div class="col-12 col-md-6">
                                        <label for="full_name" class="form-label">Full Name</label>
                                        <input id="full_name" name="full_name" type="text" class="form-control" value="{{ $prefillName }}" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input id="email" name="email" type="email" class="form-control" value="{{ $prefillEmail }}" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input id="phone" name="phone" type="text" class="form-control" value="{{ $prefillPhone }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 checkout-block">
                                <div class="checkout-block-head">
                                    <p class="checkout-block-step mb-1">Step 2</p>
                                    <h3 class="checkout-block-title mb-0">Choose Order Type</h3>
                                </div>
                                <div class="checkout-fulfillment-grid mt-3">
                                    @foreach($fulfillmentOptions as $value => $label)
                                        <label class="checkout-fulfillment-option">
                                            <input
                                                type="radio"
                                                name="fulfillment_type"
                                                value="{{ $value }}"
                                                @checked($selectedFulfillment === $value)
                                                data-fulfillment-input
                                            >
                                            <span class="checkout-fulfillment-copy">
                                                <strong>{{ $label }}</strong>
                                                <small>{{ $fulfillmentMeta[$value]['note'] ?? '' }}</small>
                                            </span>
                                            <i class="fa-solid {{ $fulfillmentMeta[$value]['icon'] ?? 'fa-circle' }}" aria-hidden="true"></i>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-12" data-fulfillment-section="delivery" @if($selectedFulfillment !== \App\Models\Order::FULFILLMENT_DELIVERY) hidden @endif>
                                <div class="checkout-inline-card">
                                    <label for="delivery_address" class="form-label">Delivery Address</label>
                                    <textarea id="delivery_address" name="delivery_address" class="form-control" rows="3" placeholder="Street, building, floor, city">{{ old('delivery_address') }}</textarea>
                                </div>
                            </div>

                            <div class="col-12" data-fulfillment-section="dine_in" @if($selectedFulfillment !== \App\Models\Order::FULFILLMENT_DINE_IN) hidden @endif>
                                <div class="checkout-inline-card">
                                    <h4 class="checkout-inline-title mb-3">Table Reservation Details</h4>
                                    <div class="row g-3">
                                        <div class="col-12 col-md-4">
                                            <label for="reservation_date" class="form-label">Reservation Date</label>
                                            <input id="reservation_date" name="reservation_date" type="date" class="form-control" value="{{ old('reservation_date', $selectedDate) }}" data-slot-date>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label for="guest_count" class="form-label">Guests</label>
                                            <input id="guest_count" name="guest_count" type="number" class="form-control" min="1" max="20" value="{{ old('guest_count', 2) }}" data-slot-guests>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label for="reservation_slot_id" class="form-label">Available Time Slot</label>
                                            <select id="reservation_slot_id" name="reservation_slot_id" class="form-select" data-slot-select>
                                                <option value="">Select a slot</option>
                                                @foreach($slotAvailability as $slot)
                                                    <option value="{{ $slot['id'] }}" @selected((string) old('reservation_slot_id') === (string) $slot['id']) @disabled(!$slot['can_book'])>
                                                        {{ $slot['name'] }} ({{ $slot['time_range'] }}) - {{ $slot['remaining'] }} seats left
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 checkout-block">
                                <div class="checkout-block-head">
                                    <p class="checkout-block-step mb-1">Step 3</p>
                                    <h3 class="checkout-block-title mb-0">Order Notes</h3>
                                </div>
                                <textarea id="notes" name="notes" class="form-control mt-3" rows="3" placeholder="Delivery instructions, allergy notes, or table request">{{ old('notes') }}</textarea>
                            </div>

                            <div class="col-12 checkout-block">
                                <div class="checkout-block-head">
                                    <p class="checkout-block-step mb-1">Step 4</p>
                                    <h3 class="checkout-block-title mb-0">Payment Method</h3>
                                </div>
                                <div class="checkout-fulfillment-grid mt-3">
                                    @foreach($paymentOptions as $value => $label)
                                        <label class="checkout-fulfillment-option">
                                            <input
                                                type="radio"
                                                name="payment_method"
                                                value="{{ $value }}"
                                                @checked($selectedPaymentMethod === $value)
                                            >
                                            <span class="checkout-fulfillment-copy">
                                                <strong>{{ $label }}</strong>
                                                <small>{{ $paymentMeta[$value]['note'] ?? '' }}</small>
                                            </span>
                                            <i class="{{ $paymentMeta[$value]['icon'] ?? 'fa-solid fa-wallet' }}" aria-hidden="true"></i>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            @guest
                                <div class="col-12 checkout-block">
                                    <div class="checkout-inline-card">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" id="create_account" name="create_account" @checked(old('create_account')) data-create-account-toggle>
                                            <label class="form-check-label" for="create_account">
                                                Create account during checkout
                                            </label>
                                        </div>

                                        <div class="mt-3" data-create-account-fields @if(!old('create_account')) hidden @endif>
                                            <div class="row g-3">
                                                <div class="col-12 col-md-4">
                                                    <label for="account_name" class="form-label">Account Name</label>
                                                    <input id="account_name" name="account_name" type="text" class="form-control" value="{{ old('account_name', $prefillName) }}">
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <label for="password" class="form-label">Password</label>
                                                    <input id="password" name="password" type="password" class="form-control">
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endguest

                            <div class="col-12">
                                <button type="submit" class="btn btn-brand checkout-submit-btn">
                                    <i class="fa-solid fa-check-to-slot" aria-hidden="true"></i>
                                    Place Order
                                </button>
                            </div>
                        </form>
                    </article>
                </div>

                <div class="col-12 col-xl-4">
                    <article class="checkout-card checkout-summary">
                        <div class="checkout-summary-head">
                            <div>
                                <p class="checkout-kicker mb-1">Order Summary</p>
                                <h3 class="checkout-summary-title mb-0">Cart Overview</h3>
                            </div>
                            <span class="checkout-summary-count">{{ $cartQuantity }} items</span>
                        </div>

                        @if($cartItems->isEmpty())
                            <p class="checkout-empty mb-0">Your cart is empty. Add menu items first to continue checkout.</p>
                        @else
                            <div class="checkout-summary-list">
                                @foreach($cartItems as $item)
                                    <div class="checkout-summary__item">
                                        <div>
                                            <p class="checkout-summary-name mb-1">{{ $item['name'] }}</p>
                                            <p class="checkout-summary-meta mb-0">Qty {{ $item['quantity'] }} x &euro;{{ number_format((float) $item['price'], 2) }}</p>
                                        </div>
                                        <strong>&euro;{{ number_format((float) $item['line_total'], 2) }}</strong>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="checkout-summary-totals">
                            <p class="mb-1 d-flex justify-content-between">
                                <span>Subtotal</span>
                                <strong>&euro;{{ number_format($cartSubtotal, 2) }}</strong>
                            </p>
                            <p class="mb-0 checkout-summary-note">Delivery fee is added only when Delivery is selected.</p>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        body.checkout-home-theme {
            background:
                radial-gradient(circle at 86% -12%, rgba(219, 29, 48, 0.22), transparent 42%),
                radial-gradient(circle at 12% 8%, rgba(255, 149, 44, 0.14), transparent 45%),
                linear-gradient(180deg, #050505 0%, #090909 34%, #0d0d0d 100%);
            color: #f2f2f2;
        }

        body.checkout-home-theme main {
            background: transparent;
            padding-top: 0 !important;
        }

        .checkout-page {
            --checkout-bg: #000;
            --checkout-panel: #0b0b0b;
            --checkout-panel-soft: #101010;
            --checkout-line: rgba(255, 255, 255, 0.12);
            --checkout-copy: rgba(255, 255, 255, 0.76);
        }

        .checkout-shell {
            position: relative;
            overflow: visible;
            border: 0;
            border-radius: 0;
            background: transparent;
            padding: 0;
            box-shadow: none;
        }

        .checkout-kicker {
            color: rgba(255, 255, 255, 0.62);
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .12em;
            font-weight: 700;
        }

        .checkout-page .checkout-card {
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0)),
                var(--checkout-panel);
            color: #f3f3f3;
            box-shadow: 0 18px 34px rgba(0, 0, 0, 0.22);
            padding: 1.2rem;
        }

        .checkout-card--form {
            height: 100%;
        }

        .checkout-summary {
            position: sticky;
            top: calc(var(--nav-height, 84px) + 1rem);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0)),
                var(--checkout-panel-soft);
        }

        .checkout-block {
            border-radius: .95rem;
            border: 1px solid var(--checkout-line);
            background: rgba(255, 255, 255, 0.02);
            padding: 1rem;
        }

        .checkout-block-head {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: .8rem;
        }

        .checkout-block-step {
            color: rgba(255, 255, 255, 0.58);
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .14em;
            font-weight: 700;
        }

        .checkout-block-title {
            color: #fff;
            font-size: 1.04rem;
            font-weight: 700;
            letter-spacing: .01em;
        }

        .checkout-inline-card {
            border-radius: .95rem;
            border: 1px solid var(--checkout-line);
            background: rgba(255, 255, 255, 0.02);
            padding: 1rem;
        }

        .checkout-inline-title {
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: .01em;
        }

        .checkout-page .form-label {
            color: rgba(255, 255, 255, 0.82);
            font-weight: 600;
            margin-bottom: .35rem;
        }

        .checkout-page .form-control,
        .checkout-page .form-select {
            border: 1px solid rgba(255, 255, 255, 0.16);
            background: rgba(255, 255, 255, 0.03);
            color: #fff;
        }

        .checkout-page .form-control::placeholder {
            color: rgba(255, 255, 255, 0.46);
        }

        .checkout-page .form-control:focus,
        .checkout-page .form-select:focus {
            border-color: rgba(255, 149, 44, 0.55);
            box-shadow: 0 0 0 .2rem rgba(255, 149, 44, 0.15);
            background: rgba(255, 255, 255, 0.04);
            color: #fff;
        }

        .checkout-fulfillment-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .7rem;
        }

        .checkout-fulfillment-option {
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            gap: .65rem;
            padding: .8rem .85rem;
            border-radius: .8rem;
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: rgba(255, 255, 255, 0.03);
            cursor: pointer;
            transition: border-color .2s ease, background .2s ease, transform .2s ease;
        }

        .checkout-fulfillment-option:hover {
            border-color: rgba(255, 149, 44, 0.35);
            background: rgba(255, 255, 255, 0.05);
            transform: translateY(-1px);
        }

        .checkout-fulfillment-option input[type="radio"] {
            accent-color: #ff952c;
            margin-top: .15rem;
        }

        .checkout-fulfillment-option i {
            color: rgba(255, 255, 255, 0.82);
        }

        .checkout-fulfillment-copy {
            display: flex;
            flex-direction: column;
            gap: .2rem;
        }

        .checkout-fulfillment-copy strong {
            color: #fff;
            font-size: .95rem;
            line-height: 1.1;
        }

        .checkout-fulfillment-copy small {
            color: rgba(255, 255, 255, 0.66);
            font-size: .76rem;
            line-height: 1.2;
        }

        .checkout-fulfillment-option:has(input:checked) {
            border-color: rgba(255, 149, 44, 0.5);
            box-shadow: inset 0 0 0 1px rgba(255, 149, 44, 0.18);
            background: linear-gradient(180deg, rgba(255, 149, 44, 0.12), rgba(219, 29, 48, 0.08));
        }

        .checkout-page .form-check-input {
            border-color: rgba(255, 255, 255, 0.35);
            background-color: rgba(255, 255, 255, 0.06);
        }

        .checkout-page .form-check-input:checked {
            background-color: var(--brand-red, #db1d30);
            border-color: var(--brand-red, #db1d30);
        }

        .checkout-page .form-check-label {
            color: rgba(255, 255, 255, 0.9);
        }

        .checkout-submit-btn {
            width: 100%;
            min-height: 2.9rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .45rem;
            font-weight: 700;
            letter-spacing: .04em;
        }

        .checkout-alert {
            border: 1px solid rgba(255, 76, 76, 0.35);
            background: rgba(185, 30, 30, 0.15);
            color: #ffd7d7;
            margin-bottom: 1rem;
        }

        .checkout-summary-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: .9rem;
            margin-bottom: .95rem;
        }

        .checkout-summary-title {
            color: #fff;
            font-size: 1.15rem;
            font-weight: 700;
        }

        .checkout-summary-count {
            display: inline-flex;
            align-items: center;
            min-height: 1.8rem;
            padding: .25rem .6rem;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            font-size: .76rem;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .checkout-summary-list {
            display: flex;
            flex-direction: column;
            gap: .25rem;
            margin-bottom: .95rem;
        }

        .checkout-summary__item {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: .8rem;
            padding: .65rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .checkout-summary__item:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .checkout-summary-name {
            color: #fff;
            font-weight: 600;
            line-height: 1.2;
        }

        .checkout-summary-meta {
            color: rgba(255, 255, 255, 0.64);
            font-size: .86rem;
            line-height: 1.3;
        }

        .checkout-summary-totals {
            border-top: 1px solid rgba(255, 255, 255, 0.12);
            padding-top: .9rem;
            color: #fff;
        }

        .checkout-summary-note {
            color: rgba(255, 255, 255, 0.64);
            font-size: .86rem;
            line-height: 1.35;
        }

        .checkout-empty {
            border: 1px dashed rgba(255, 255, 255, 0.24);
            border-radius: .8rem;
            padding: .8rem;
            color: rgba(255, 255, 255, 0.74);
            background: rgba(255, 255, 255, 0.03);
        }

        @media (max-width: 1199.98px) {
            .checkout-summary {
                position: static;
            }
        }

        @media (max-width: 767.98px) {
            .checkout-fulfillment-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush
