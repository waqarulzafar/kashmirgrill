@extends('layouts.master')

@section('title', __('Order Placed | Kashmir Grill House Como'))
@section('meta_description', __('Your Kashmir Grill House order has been placed successfully.'))
@section('meta_robots', 'noindex,follow')
@section('body_class', 'checkout-success-theme')

@section('hero')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10 text-center">
                <p class="checkout-success-kicker mb-2">{{ __('Order Status') }}</p>
                <h1 class="display-5 fw-bold mb-3 text-white">{{ __('Order Confirmed') }}</h1>
                <p class="lead mb-0 checkout-success-hero-copy">{{ __('Your payment has been received and the kitchen team now has your order.') }}</p>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section class="container pb-5 checkout-success-shell">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <article class="checkout-success-card" role="status" aria-live="polite">
                    <div class="checkout-success-head">
                        <span class="checkout-success-icon" aria-hidden="true">
                            <i class="fa-solid fa-bag-shopping"></i>
                        </span>
                        <div>
                            <h2 class="checkout-success-title mb-2">{{ __('Order Submitted Successfully') }}</h2>
                            <p class="checkout-success-copy mb-0">{{ __('We will start processing your order shortly. For urgent updates, contact the restaurant directly.') }}</p>
                        </div>
                    </div>

            @if(session('order_reference'))
                        <div class="checkout-success-reference">
                            <p class="mb-1">{{ __('Order Reference') }}</p>
                            <strong>{{ session('order_reference') }}</strong>
                        </div>
            @endif

                    <div class="checkout-success-meta">
                        <div class="checkout-success-meta__item">
                            <h3>{{ __('1. Payment Captured') }}</h3>
                            <p>{{ __('Your checkout completed successfully and your order is now queued for review.') }}</p>
                        </div>
                        <div class="checkout-success-meta__item">
                            <h3>{{ __('2. Kitchen Review') }}</h3>
                            <p>{{ __('Our team will confirm preparation timing and any delivery or dine-in details.') }}</p>
                        </div>
                        <div class="checkout-success-meta__item">
                            <h3>{{ __('3. Live Updates') }}</h3>
                            <p>{{ __('We will send status emails as your order moves through confirmation and service.') }}</p>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 gap-md-3">
                        @auth
                            <a href="{{ route('account.dashboard') }}" class="btn btn-brand checkout-success-btn">{{ __('Open Dashboard') }}</a>
                        @endauth
                        <a href="{{ route('menu') }}" class="btn btn-brand-outline checkout-success-btn">{{ __('Back to Menu') }}</a>
                        <a href="tel:+393511203141" class="btn btn-brand-outline checkout-success-btn">{{ __('Call Restaurant') }}</a>
                    </div>
                </article>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        body.checkout-success-theme {
            background:
                radial-gradient(circle at 86% -12%, rgba(219, 29, 48, 0.22), transparent 42%),
                radial-gradient(circle at 12% 8%, rgba(255, 149, 44, 0.14), transparent 45%),
                linear-gradient(180deg, #050505 0%, #090909 34%, #0d0d0d 100%);
            color: #f2f2f2;
        }

        body.checkout-success-theme main {
            background: transparent;
            padding-top: 0 !important;
        }

        .checkout-success-kicker {
            color: rgba(255, 255, 255, .62);
            font-size: .82rem;
            text-transform: uppercase;
            letter-spacing: .14em;
            font-weight: 700;
        }

        .checkout-success-hero-copy {
            color: rgba(255, 255, 255, .78);
            max-width: 780px;
            margin-inline: auto;
        }

        .checkout-success-shell {
            margin-top: 1.5rem;
        }

        .checkout-success-card {
            border-radius: 1.2rem;
            border: 1px solid rgba(255, 255, 255, .12);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, .04), rgba(255, 255, 255, .01)),
                #0b0b0b;
            box-shadow: 0 24px 44px rgba(0, 0, 0, .34);
            padding: clamp(1.2rem, 3.4vw, 2.6rem);
        }

        .checkout-success-head {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 1rem;
            align-items: flex-start;
            margin-bottom: 1.2rem;
        }

        .checkout-success-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 999px;
            display: inline-grid;
            place-items: center;
            font-size: 1.2rem;
            color: #fff;
            background: linear-gradient(135deg, var(--brand-red, #db1d30), var(--brand-orange, #ff952c));
            box-shadow: 0 10px 22px rgba(219, 29, 48, .33);
        }

        .checkout-success-title {
            color: #fff;
            font-size: clamp(1.45rem, 2.2vw, 2rem);
            font-weight: 700;
        }

        .checkout-success-copy {
            color: rgba(255, 255, 255, .78);
            font-size: 1.02rem;
        }

        .checkout-success-reference {
            border: 1px solid rgba(255, 149, 44, .36);
            background: rgba(255, 149, 44, .11);
            border-radius: .9rem;
            padding: .95rem 1rem;
            margin: 0 0 1.2rem;
        }

        .checkout-success-reference p {
            color: rgba(255, 255, 255, .72);
            font-size: .86rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            font-weight: 600;
        }

        .checkout-success-reference strong {
            color: #fff;
            font-size: clamp(1.15rem, 2.4vw, 1.5rem);
            letter-spacing: .04em;
        }

        .checkout-success-meta {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .8rem;
            margin-bottom: 1.3rem;
        }

        .checkout-success-meta__item {
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: .85rem;
            background: rgba(255, 255, 255, .02);
            padding: .9rem;
        }

        .checkout-success-meta__item h3 {
            color: #fff;
            font-size: .98rem;
            margin: 0 0 .3rem;
        }

        .checkout-success-meta__item p {
            color: rgba(255, 255, 255, .7);
            margin: 0;
            font-size: .88rem;
            line-height: 1.4;
        }

        .checkout-success-btn {
            min-height: 2.85rem;
            min-width: 13rem;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            font-weight: 700;
            letter-spacing: .03em;
        }

        @media (max-width: 991.98px) {
            .checkout-success-meta {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767.98px) {
            .checkout-success-head {
                grid-template-columns: 1fr;
            }

            .checkout-success-meta {
                grid-template-columns: minmax(0, 1fr);
            }

            .checkout-success-btn {
                width: 100%;
            }
        }
    </style>
@endpush
