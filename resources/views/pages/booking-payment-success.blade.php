@extends('layouts.master')

@section('meta_title', __('Booking Payment Complete | Kashmir Grill House'))
@section('meta_description', __('Your booking payment has been received successfully.'))
@section('body_class', 'booking-payment-success-theme')

@section('content')
    @php
        $currentLocale = $locale;
    @endphp

    <section class="container booking-payment-success-shell py-5">
        <article class="booking-payment-success-card">
            <div class="booking-payment-success-card__icon" aria-hidden="true">
                <svg viewBox="0 0 64 64" fill="none">
                    <circle cx="32" cy="32" r="30" stroke="currentColor" stroke-width="3"></circle>
                    <path d="M19 33.5L27 41.5L45 23.5" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </div>
            <p class="booking-payment-success-kicker mb-2">{{ __('Payment Complete') }}</p>
            <h1 class="booking-payment-success-title mb-3">{{ __('Your booking payment was received successfully') }}</h1>
            <p class="booking-payment-success-copy mb-4">{{ __('Your reservation is now marked as paid. Keep this reference for your records and contact the restaurant if you need any changes.') }}</p>

            <div class="booking-payment-success-meta">
                <div>
                    <span>{{ __('Reference') }}</span>
                    <strong>{{ $booking->formattedReference() }}</strong>
                </div>
                <div>
                    <span>{{ __('Paid Amount') }}</span>
                    <strong>&euro;{{ $paymentAmount }}</strong>
                </div>
                <div>
                    <span>{{ __('Payment Reference') }}</span>
                    <strong>{{ $booking->payment_reference ?: __('Not available') }}</strong>
                </div>
            </div>

            <div class="booking-payment-success-actions">
                <a href="{{ route('home', ['locale' => $currentLocale]) }}" class="btn btn-brand">{{ __('Back to Home') }}</a>
                <a href="{{ route('bookings.payment.show', ['locale' => $currentLocale, 'booking' => $booking, 'token' => $booking->payment_token]) }}" class="btn btn-brand-outline">{{ __('View Booking Summary') }}</a>
            </div>
        </article>
    </section>
@endsection

@push('styles')
    <style>
        body.booking-payment-success-theme {
            background: #f6f1ea;
        }

        body.booking-payment-success-theme main {
            background: transparent;
        }

        .booking-payment-success-shell {
            max-width: 860px;
        }

        .booking-payment-success-card {
            padding: clamp(2rem, 4vw, 3.5rem);
            border-radius: 2rem;
            background: #ffffff;
            border: 1px solid rgba(17, 17, 17, 0.08);
            box-shadow: 0 28px 60px rgba(17, 17, 17, 0.08);
            text-align: center;
        }

        .booking-payment-success-card__icon {
            width: 88px;
            height: 88px;
            margin: 0 auto 1.5rem;
            color: #db1d30;
        }

        .booking-payment-success-kicker {
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: #a45210;
        }

        .booking-payment-success-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2.4rem, 5vw, 4rem);
            line-height: .95;
            color: #111111;
        }

        .booking-payment-success-copy {
            max-width: 36rem;
            margin-inline: auto;
            color: rgba(17, 17, 17, 0.68);
            font-size: 1rem;
            line-height: 1.7;
        }

        .booking-payment-success-meta {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }

        .booking-payment-success-meta div {
            padding: 1.1rem 1rem;
            border-radius: 1.2rem;
            background: #f8f2ec;
        }

        .booking-payment-success-meta span {
            display: block;
            margin-bottom: .45rem;
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: rgba(17, 17, 17, 0.52);
        }

        .booking-payment-success-meta strong {
            display: block;
            color: #111111;
        }

        .booking-payment-success-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        @media (max-width: 767.98px) {
            .booking-payment-success-meta {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush
