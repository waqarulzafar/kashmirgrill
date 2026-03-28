@extends('layouts.master')

@section('meta_title', __('Complete Booking Payment | Kashmir Grill House'))
@section('meta_description', __('Review your confirmed reservation and complete the Stripe payment for your booking.'))
@section('body_class', 'booking-payment-theme')

@section('content')
    @php
        $statusLabel = $statusLabels[$booking->status] ?? ucfirst($booking->status);
        $paymentStatusLabel = $paymentStatusLabels[$booking->payment_status] ?? ucfirst($booking->payment_status);
        $bookingTypeLabel = $bookingTypeLabels[$booking->booking_type] ?? ucfirst($booking->booking_type);
        $currentLocale = $locale;
    @endphp

    <section class="container booking-payment-shell py-5">
        @if($errors->has('payment'))
            <div class="alert alert-danger booking-payment-alert mb-4">
                {{ $errors->first('payment') }}
            </div>
        @endif

        <div class="booking-payment-card">
            <div class="booking-payment-card__head">
                <p class="booking-payment-kicker mb-2">{{ __('Booking Payment') }}</p>
                <h1 class="booking-payment-title mb-2">{{ __('Complete your confirmed reservation') }}</h1>
                <p class="booking-payment-copy mb-0">{{ __('Review the booking summary below, then continue to Stripe to complete the secure card payment.') }}</p>
            </div>

            <div class="booking-payment-grid">
                <div class="booking-payment-panel">
                    <div class="booking-payment-summary">
                        <div class="booking-payment-summary__row">
                            <span>{{ __('Reference') }}</span>
                            <strong>{{ $booking->formattedReference() }}</strong>
                        </div>
                        <div class="booking-payment-summary__row">
                            <span>{{ __('Guest') }}</span>
                            <strong>{{ $booking->full_name }}</strong>
                        </div>
                        <div class="booking-payment-summary__row">
                            <span>{{ __('Booking Type') }}</span>
                            <strong>{{ $bookingTypeLabel }}</strong>
                        </div>
                        <div class="booking-payment-summary__row">
                            <span>{{ __('Date') }}</span>
                            <strong>{{ optional($booking->date)->format('F j, Y') }}</strong>
                        </div>
                        <div class="booking-payment-summary__row">
                            <span>{{ __('Time') }}</span>
                            <strong>{{ \Illuminate\Support\Carbon::createFromFormat('H:i:s', $booking->time)->format('g:i A') }}</strong>
                        </div>
                        <div class="booking-payment-summary__row">
                            <span>{{ __('Guests') }}</span>
                            <strong>{{ $booking->persons }}</strong>
                        </div>
                        @if($booking->dineInSlot?->name)
                            <div class="booking-payment-summary__row">
                                <span>{{ __('Slot') }}</span>
                                <strong>{{ $booking->dineInSlot->name }}</strong>
                            </div>
                        @endif
                        <div class="booking-payment-summary__row">
                            <span>{{ __('Booking Status') }}</span>
                            <strong>{{ $statusLabel }}</strong>
                        </div>
                        <div class="booking-payment-summary__row">
                            <span>{{ __('Payment Status') }}</span>
                            <strong>{{ $paymentStatusLabel }}</strong>
                        </div>
                    </div>
                </div>

                <div class="booking-payment-panel booking-payment-panel--accent">
                    <div class="booking-payment-total">
                        <span>{{ __('Amount Due') }}</span>
                        <strong>&euro;{{ $paymentAmount }}</strong>
                    </div>

                    @if($booking->payment_status === \App\Models\Booking::PAYMENT_STATUS_PAID)
                        <div class="booking-payment-state booking-payment-state--paid">
                            <h2>{{ __('Payment already received') }}</h2>
                            <p>{{ __('This booking has already been paid successfully.') }}</p>
                            @if($booking->payment_reference)
                                <small>{{ __('Reference: :reference', ['reference' => $booking->payment_reference]) }}</small>
                            @endif
                        </div>
                        <a href="{{ route('bookings.payment.success', ['locale' => $currentLocale, 'booking' => $booking, 'token' => $booking->payment_token]) }}" class="btn btn-brand booking-payment-btn">
                            {{ __('View Payment Success') }}
                        </a>
                    @elseif(! $booking->canCollectCardPayment())
                        <div class="booking-payment-state">
                            <h2>{{ __('Payment not available yet') }}</h2>
                            <p>{{ __('This payment link becomes available once the booking is confirmed and ready for payment.') }}</p>
                        </div>
                    @else
                        <div class="booking-payment-state">
                            <h2>{{ __('Secure Stripe checkout') }}</h2>
                            <p>{{ __('You will be redirected to Stripe after this review step to complete the card payment securely.') }}</p>
                        </div>

                        <form method="POST" action="{{ route('bookings.payment.checkout', ['locale' => $currentLocale, 'booking' => $booking, 'token' => $booking->payment_token]) }}">
                            @csrf
                            <button type="submit" class="btn btn-brand booking-payment-btn">
                                {{ __('Continue to Stripe') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        body.booking-payment-theme {
            background: #f6f1ea;
        }

        body.booking-payment-theme main {
            background: transparent;
        }

        .booking-payment-shell {
            max-width: 1080px;
        }

        .booking-payment-card {
            background: #ffffff;
            border: 1px solid rgba(17, 17, 17, 0.08);
            border-radius: 2rem;
            box-shadow: 0 28px 60px rgba(17, 17, 17, 0.08);
            overflow: hidden;
        }

        .booking-payment-card__head {
            padding: clamp(1.75rem, 4vw, 3rem);
            border-bottom: 1px solid rgba(17, 17, 17, 0.08);
        }

        .booking-payment-kicker {
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: #a45210;
        }

        .booking-payment-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2.4rem, 5vw, 4rem);
            line-height: .95;
            color: #111111;
        }

        .booking-payment-copy {
            max-width: 44rem;
            color: rgba(17, 17, 17, 0.68);
            font-size: 1rem;
            line-height: 1.7;
        }

        .booking-payment-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.4fr) minmax(280px, .8fr);
            gap: 0;
        }

        .booking-payment-panel {
            padding: clamp(1.75rem, 3vw, 2.5rem);
        }

        .booking-payment-panel--accent {
            background: #111111;
            color: #ffffff;
        }

        .booking-payment-summary {
            display: grid;
            gap: .9rem;
        }

        .booking-payment-summary__row {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            padding-bottom: .9rem;
            border-bottom: 1px solid rgba(17, 17, 17, 0.08);
        }

        .booking-payment-summary__row span {
            color: rgba(17, 17, 17, 0.58);
        }

        .booking-payment-summary__row strong {
            text-align: right;
            color: #111111;
        }

        .booking-payment-total {
            padding: 1.25rem 1.35rem;
            border-radius: 1.35rem;
            background: rgba(255, 255, 255, 0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .booking-payment-total span {
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            font-size: .78rem;
            letter-spacing: .12em;
            font-weight: 700;
        }

        .booking-payment-total strong {
            font-size: clamp(2rem, 4vw, 3rem);
            line-height: 1;
            color: #ffffff;
        }

        .booking-payment-state {
            margin: 1.5rem 0;
            padding: 1.25rem 1.35rem;
            border-radius: 1.35rem;
            background: rgba(255, 255, 255, 0.06);
        }

        .booking-payment-state h2 {
            margin: 0 0 .5rem;
            font-size: 1.2rem;
            color: #ffffff;
        }

        .booking-payment-state p,
        .booking-payment-state small {
            color: rgba(255, 255, 255, 0.72);
        }

        .booking-payment-state--paid {
            background: rgba(80, 205, 137, 0.14);
        }

        .booking-payment-btn {
            width: 100%;
            justify-content: center;
        }

        .booking-payment-alert {
            border-radius: 1rem;
        }

        @media (max-width: 991.98px) {
            .booking-payment-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush
