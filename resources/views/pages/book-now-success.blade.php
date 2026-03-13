@extends('layouts.master')

@section('title', 'Reservation Request Received | Kashmir Grill House Como')
@section('meta_description', 'Your reservation request has been received by Kashmir Grill House in Como. Our team will confirm your booking details shortly.')
@section('meta_robots', 'noindex,follow')
@section('body_class', 'booking-success-theme')

@section('hero')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10 text-center">
                <p class="booking-success-kicker mb-2">Reservation Status</p>
                <h1 class="display-5 fw-bold mb-3 text-white">Booking Request Received</h1>
                <p class="lead mb-0 booking-success-hero-copy">Thank you. Our team will verify your slot availability and contact you soon.</p>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section class="container pb-5 booking-success-shell">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <article class="booking-success-card" role="status" aria-live="polite">
                    <div class="booking-success-head">
                        <span class="booking-success-icon" aria-hidden="true">
                            <i class="fa-solid fa-circle-check"></i>
                        </span>
                        <div>
                            <h2 class="booking-success-title mb-2">Success</h2>
                            <p class="booking-success-copy mb-0">Your booking request is now in review. We usually respond quickly with confirmation details.</p>
                        </div>
                    </div>

                    @if(session('booking_reference'))
                        <div class="booking-success-reference">
                            <p class="mb-1">Reservation Reference</p>
                            <strong>#{{ session('booking_reference') }}</strong>
                        </div>
                    @endif

                    <div class="booking-success-meta">
                        <div class="booking-success-meta__item">
                            <h3>1. Review in Progress</h3>
                            <p>We are validating date, party size, and slot availability.</p>
                        </div>
                        <div class="booking-success-meta__item">
                            <h3>2. Confirmation</h3>
                            <p>You will receive confirmation by phone or email from our team.</p>
                        </div>
                        <div class="booking-success-meta__item">
                            <h3>3. Final Arrangement</h3>
                            <p>For special events, we will finalize menu and payment preference.</p>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 gap-md-3">
                        <a href="{{ route('home') }}" class="btn btn-brand booking-success-btn">Back to Home</a>
                        <a href="{{ route('book-now') }}" class="btn btn-brand-outline booking-success-btn">Create Another Booking</a>
                    </div>
                </article>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        body.booking-success-theme {
            background:
                radial-gradient(circle at 86% -12%, rgba(219, 29, 48, 0.22), transparent 42%),
                radial-gradient(circle at 12% 8%, rgba(255, 149, 44, 0.14), transparent 45%),
                linear-gradient(180deg, #050505 0%, #090909 34%, #0d0d0d 100%);
            color: #f2f2f2;
        }

        body.booking-success-theme main {
            background: transparent;
            padding-top: 0 !important;
        }

        .booking-success-kicker {
            color: rgba(255, 255, 255, .62);
            font-size: .82rem;
            text-transform: uppercase;
            letter-spacing: .14em;
            font-weight: 700;
        }

        .booking-success-hero-copy {
            color: rgba(255, 255, 255, .78);
            max-width: 780px;
            margin-inline: auto;
        }

        .booking-success-shell {
            margin-top: 1.5rem;
        }

        .booking-success-card {
            border-radius: 1.2rem;
            border: 1px solid rgba(255, 255, 255, .12);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, .04), rgba(255, 255, 255, .01)),
                #0b0b0b;
            box-shadow: 0 24px 44px rgba(0, 0, 0, .34);
            padding: clamp(1.2rem, 3.4vw, 2.6rem);
        }

        .booking-success-head {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 1rem;
            align-items: flex-start;
            margin-bottom: 1.2rem;
        }

        .booking-success-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 999px;
            display: inline-grid;
            place-items: center;
            font-size: 1.3rem;
            color: #fff;
            background: linear-gradient(135deg, var(--brand-red, #db1d30), var(--brand-orange, #ff952c));
            box-shadow: 0 10px 22px rgba(219, 29, 48, .33);
        }

        .booking-success-title {
            color: #fff;
            font-size: clamp(1.45rem, 2.2vw, 2rem);
            font-weight: 700;
        }

        .booking-success-copy {
            color: rgba(255, 255, 255, .78);
            font-size: 1.02rem;
        }

        .booking-success-reference {
            border: 1px solid rgba(255, 149, 44, .36);
            background: rgba(255, 149, 44, .11);
            border-radius: .9rem;
            padding: .95rem 1rem;
            margin: 0 0 1.2rem;
        }

        .booking-success-reference p {
            color: rgba(255, 255, 255, .72);
            font-size: .86rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            font-weight: 600;
        }

        .booking-success-reference strong {
            color: #fff;
            font-size: clamp(1.15rem, 2.4vw, 1.5rem);
            letter-spacing: .04em;
        }

        .booking-success-meta {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .8rem;
            margin-bottom: 1.3rem;
        }

        .booking-success-meta__item {
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: .85rem;
            background: rgba(255, 255, 255, .02);
            padding: .9rem;
        }

        .booking-success-meta__item h3 {
            color: #fff;
            font-size: .98rem;
            margin: 0 0 .3rem;
        }

        .booking-success-meta__item p {
            color: rgba(255, 255, 255, .7);
            margin: 0;
            font-size: .88rem;
            line-height: 1.4;
        }

        .booking-success-btn {
            min-height: 2.85rem;
            min-width: 13rem;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            font-weight: 700;
            letter-spacing: .03em;
        }

        @media (max-width: 991.98px) {
            .booking-success-meta {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767.98px) {
            .booking-success-head {
                grid-template-columns: 1fr;
            }

            .booking-success-meta {
                grid-template-columns: minmax(0, 1fr);
            }

            .booking-success-btn {
                width: 100%;
            }
        }
    </style>
@endpush
