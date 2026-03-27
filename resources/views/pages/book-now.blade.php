@extends('layouts.master')

@section('title', __('Reserve a Table | Kashmir Grill House Como'))
@section('meta_description', __('Book your table or full restaurant event at Kashmir Grill House in Como with live slot availability and a guided reservation flow.'))
@section('meta_keywords', __('book table Kashmir Grill House, event booking Como, reservation slots Como halal restaurant'))
@section('body_class', 'booking-flow-theme')

@php
    $searchDate = old('date', $selectedDate ?? now()->toDateString());
    $searchGuests = (int) old('persons', $selectedGuests ?? 2);
    $searchTimeFilter = old('time_filter', $selectedTimeFilter ?? 'all');
    $bookingType = old('booking_type', $selectedBookingType ?? \App\Models\Booking::TYPE_TABLE);
    $selectedSlotId = old('selected_slot_id');
    $defaultBookingPaymentMethod = array_key_first($paymentMethodOptions);
    $selectedBookingPaymentMethod = old('payment_method', \App\Models\Booking::PAYMENT_METHOD_PAY_ON_ARRIVAL);

    if (! array_key_exists((string) $selectedBookingPaymentMethod, $paymentMethodOptions) && $defaultBookingPaymentMethod) {
        $selectedBookingPaymentMethod = $defaultBookingPaymentMethod;
    }
@endphp

@section('content')
    <section class="container py-4 py-lg-5 booking-flow" data-booking-flow data-availability-url="{{ route('bookings.availability') }}">
        @if ($errors->any())
            <div class="alert alert-danger booking-alert mb-4">
                <strong>{{ __('Please fix the following before submitting:') }}</strong>
                <ul class="mb-0 mt-2 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-4 align-items-stretch">
            <div class="col-12 col-xl-6">
                <article class="booking-showcase h-100">
                    <div id="bookingGallery" class="carousel slide booking-gallery" data-bs-ride="carousel" data-bs-interval="4200">
                        <div class="carousel-inner rounded-4 overflow-hidden">
                            <div class="carousel-item active">
                                <img src="{{ asset('assets/images/menu/griglia/mix-grill-tandoori.jpg') }}" alt="{{ __('Kashmir Grill House signature grill platter') }}" class="d-block w-100 booking-gallery__image" loading="lazy" decoding="async">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('assets/images/menu/primi-piati/butter-chicken.jpg') }}" alt="{{ __('Butter chicken served for dining guests') }}" class="d-block w-100 booking-gallery__image" loading="lazy" decoding="async">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('assets/images/menu/antipasti/samosa-chaat.jpg') }}" alt="{{ __('Starter options for table and event bookings') }}" class="d-block w-100 booking-gallery__image" loading="lazy" decoding="async">
                            </div>
                        </div>
                    </div>

                    <div class="booking-showcase__content">
                        <h2 class="booking-showcase__title mb-2">{{ __('Find Your Perfect Reservation') }}</h2>
                        <p class="booking-showcase__subtitle mb-3">
                            {{ __('Reserve a table for regular service or request the whole restaurant for private events.') }}
                        </p>
                        <ul class="booking-showcase__list mb-0">
                            <li>{{ __('Live slot availability based on active admin time slots.') }}</li>
                            <li>{{ __('Dedicated event booking mode for exclusive restaurant use.') }}</li>
                            <li>{{ __('Fast guest details flow with international phone input.') }}</li>
                        </ul>
                    </div>
                </article>
            </div>

            <div class="col-12 col-xl-6">
                <article class="booking-panel h-100">
                    <header class="booking-panel__header">
                        <p class="booking-kicker mb-1">{{ __('Reservation Flow') }}</p>
                        <h1 class="booking-panel__title mb-2">{{ __('Book Table or Full Event') }}</h1>
                        <p class="booking-panel__subtitle mb-0">{{ __('Pick date, party size, and preferred timing first, then continue with guest and payment details.') }}</p>
                    </header>

                    <form method="POST" action="{{ route('bookings.store') }}" class="booking-form" data-booking-form>
                        @csrf
                        <input type="hidden" name="form_rendered_at" value="{{ now()->timestamp }}">
                        <input type="hidden" name="idempotency_key" value="{{ old('idempotency_key', (string) \Illuminate\Support\Str::uuid()) }}">
                        <input type="hidden" name="date" id="bookingDate" value="{{ $searchDate }}">
                        <input type="hidden" name="persons" id="bookingPersons" value="{{ $searchGuests }}">
                        <input type="hidden" name="time_filter" id="bookingTimeFilter" value="{{ $searchTimeFilter }}">
                        <input type="hidden" name="selected_slot_id" id="bookingSelectedSlot" value="{{ $selectedSlotId }}">
                        <input type="hidden" name="selected_slot_time" id="bookingSelectedTime" value="{{ old('selected_slot_time') }}">
                        <input type="hidden" name="phone" id="bookingPhoneHidden" value="{{ old('phone') }}">
                        <input type="hidden" name="phone_country_iso2" id="bookingPhoneCountry" value="{{ old('phone_country_iso2') }}">

                        <div class="d-none" aria-hidden="true">
                            <label for="website">{{ __('Website') }}</label>
                            <input id="website" name="website" type="text" autocomplete="off" tabindex="-1">
                        </div>

                        <div class="booking-mode-grid" data-booking-type-grid>
                            @foreach($bookingTypeOptions as $value => $label)
                                <label class="booking-mode-option">
                                    <input type="radio" name="booking_type" value="{{ $value }}" @checked($bookingType === $value) data-booking-type>
                                    <span>
                                        <strong>{{ $label }}</strong>
                                        <small>{{ $value === \App\Models\Booking::TYPE_TABLE ? __('Choose from available seatings') : __('Reserve for private functions') }}</small>
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        <section class="booking-search mt-4" data-booking-search>
                            <div class="booking-search__meta">
                                <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                                Via Milano, 253, 22100 Como CO, Italy
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-12 col-md-4">
                                    <p class="booking-search__label">{{ __('Date') }}</p>
                                    <div class="booking-search__control booking-search__control--field booking-search__control--date">
                                        <input
                                            id="bookingSearchDate"
                                            type="text"
                                            class="booking-search__control-input booking-search__control-input--date"
                                            value="{{ $searchDate }}"
                                            data-min-date="{{ now()->toDateString() }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#bookingDateModal"
                                            autocomplete="off"
                                            placeholder="{{ __('Select date') }}"
                                            aria-haspopup="dialog"
                                            readonly
                                        >
                                        <i class="fa-regular fa-calendar booking-search__control-icon" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <p class="booking-search__label">{{ __('Size') }}</p>
                                    <select id="bookingSearchPersons" class="booking-search__control booking-search__control--select">
                                        @for($i = 1; $i <= 80; $i++)
                                            <option value="{{ $i }}" @selected($searchGuests === $i)>{{ __(':count Guests', ['count' => $i]) }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-6 col-md-4">
                                    <p class="booking-search__label">{{ __('Time') }}</p>
                                    <select id="bookingSearchTime" class="booking-search__control booking-search__control--select">
                                        <option value="all" @selected($searchTimeFilter === 'all')>{{ __('All') }}</option>
                                        <option value="lunch" @selected($searchTimeFilter === 'lunch')>{{ __('Lunch') }}</option>
                                        <option value="dinner" @selected($searchTimeFilter === 'dinner')>{{ __('Dinner') }}</option>
                                    </select>
                                </div>
                            </div>

                            <button class="btn btn-brand booking-find-btn mt-3" type="button" id="findBookingAvailability">
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" data-find-spinner></span>
                                <span data-find-label>
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    {{ __('Find Availability') }}
                                </span>
                            </button>
                        </section>

                        <section class="booking-slots mt-4 d-none" data-slots-section data-step-slots>
                            <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
                                <h3 class="booking-step-title mb-0">{{ __('Available Times') }}</h3>
                                <span class="booking-slots__status" data-slots-status>{{ __('Choose one slot') }}</span>
                            </div>
                            <div class="booking-slots__grid" data-slots-grid>
                                @foreach($slotAvailability as $slot)
                                    <button
                                        type="button"
                                        class="booking-slot {{ (string) $selectedSlotId === (string) $slot['id'] ? 'is-active' : '' }}"
                                        data-slot-card
                                        data-slot-id="{{ $slot['id'] }}"
                                        data-slot-time="{{ $slot['start_time'] }}"
                                        @disabled(!$slot['can_book'])
                                    >
                                        <strong>{{ $slot['start_time'] }}</strong>
                                        <small>{{ __(':count seats left', ['count' => $slot['remaining']]) }}</small>
                                    </button>
                                @endforeach
                            </div>
                            <p class="booking-slots__empty mb-0 {{ $slotAvailability->isNotEmpty() ? 'd-none' : '' }}" data-slots-empty>
                                {{ __('No slots found for this selection. Try another date or party size.') }}
                            </p>
                        </section>

                        <section class="booking-details mt-4 d-none" data-step-details>
                            <div class="booking-steps mb-4">
                                <span class="is-active">{{ __('1. Guest Details') }}</span>
                                <span>{{ __('2. Preferences') }}</span>
                                <span>{{ __('3. Checkout') }}</span>
                            </div>

                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="full_name" class="form-label">{{ __('Full Name') }}</label>
                                    <input id="full_name" name="full_name" type="text" class="form-control" value="{{ old('full_name') }}" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                    <input id="email" name="email" type="email" class="form-control" value="{{ old('email') }}" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="phone_display" class="form-label">{{ __('Phone Number') }}</label>
                                    <input id="phone_display" name="phone_display" type="tel" class="form-control" value="{{ old('phone') }}" required>
                                </div>
                                <div class="col-12 col-md-6 {{ $bookingType === \App\Models\Booking::TYPE_TABLE ? 'd-none' : '' }}" data-event-time-group>
                                    <label for="event_time" class="form-label">{{ __('Preferred Start Time') }}</label>
                                    <input id="event_time" type="time" class="form-control" value="{{ old('time') }}" data-event-time-input @if($bookingType === \App\Models\Booking::TYPE_EVENT) name="time" required @endif>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="table_preference" class="form-label">{{ __('Seating Preference') }}</label>
                                    <select id="table_preference" name="table_preference" class="form-select">
                                        <option value="">{{ __('No preference') }}</option>
                                        <option value="Window" @selected(old('table_preference') === 'Window')>{{ __('Window') }}</option>
                                        <option value="Quiet Corner" @selected(old('table_preference') === 'Quiet Corner')>{{ __('Quiet Corner') }}</option>
                                        <option value="Family Seating" @selected(old('table_preference') === 'Family Seating')>{{ __('Family Seating') }}</option>
                                        <option value="Outdoor" @selected(old('table_preference') === 'Outdoor')>{{ __('Outdoor') }}</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="selected_menu" class="form-label">{{ __('Menu Focus') }}</label>
                                    <select id="selected_menu" name="selected_menu" class="form-select">
                                        <option value="">{{ __('Choose menu focus') }}</option>
                                        <option value="A la carte" @selected(old('selected_menu') === 'A la carte')>{{ __('A la carte') }}</option>
                                        <option value="Family Sharing" @selected(old('selected_menu') === 'Family Sharing')>{{ __('Family Sharing') }}</option>
                                        <option value="Vegetarian" @selected(old('selected_menu') === 'Vegetarian')>{{ __('Vegetarian') }}</option>
                                        <option value="Chef Specials" @selected(old('selected_menu') === 'Chef Specials')>{{ __('Chef Specials') }}</option>
                                        <option value="Event Buffet" @selected(old('selected_menu') === 'Event Buffet')>{{ __('Event Buffet') }}</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">{{ __('Special Occasion') }}</label>
                                    <button type="button" class="booking-inline-select" id="openOccasionModal">
                                        <span id="occasionLabel">{{ old('special_occasion', __('Select special occasions')) }}</span>
                                        <i class="fa-solid fa-chevron-right"></i>
                                    </button>
                                    <input type="hidden" name="special_occasion" id="specialOccasionValue" value="{{ old('special_occasion') }}">
                                </div>

                                <div class="col-12">
                                    <label for="additional_notes" class="form-label">{{ __('Message') }}</label>
                                    <textarea id="additional_notes" name="additional_notes" class="form-control" rows="4" placeholder="{{ __('Any special requests or preferences?') }}">{{ old('additional_notes') }}</textarea>
                                </div>
                            </div>

                            <button type="button" class="btn btn-brand-outline booking-next-btn mt-4" data-open-payment-step>
                                {{ __('Continue to Checkout') }}
                            </button>
                        </section>

                        <section class="booking-payment mt-4 d-none" data-step-payment>
                            <h3 class="booking-step-title mb-3">{{ __('Checkout Preference') }}</h3>
                            <div class="booking-mode-grid">
                                @foreach($paymentMethodOptions as $value => $label)
                                    <label class="booking-mode-option booking-mode-option--payment">
                                        <input type="radio" name="payment_method" value="{{ $value }}" @checked($selectedBookingPaymentMethod === $value)>
                                        <span>
                                            <strong>{{ $label }}</strong>
                                            <small>{{ $value === \App\Models\Booking::PAYMENT_METHOD_CARD_ON_CONFIRMATION ? __('Secure payment link sent once slot is confirmed') : __('Settle bill at the restaurant') }}</small>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            <p class="booking-payment-note mt-2 mb-0 d-none" data-card-checkout-note>
                                {{ __('Card details are collected through a secure checkout link after availability confirmation.') }}
                            </p>

                            <div class="form-check mt-3 booking-optin">
                                <input class="form-check-input" type="checkbox" value="1" id="marketing_opt_in" name="marketing_opt_in" @checked(old('marketing_opt_in'))>
                                <label class="form-check-label" for="marketing_opt_in">
                                    {{ __('I would like to receive updates and offers from Kashmir Grill House.') }}
                                </label>
                            </div>
                        </section>

                        <button type="submit" class="btn btn-brand booking-submit-btn mt-4 d-none" data-step-submit>
                            {{ __('Continue Reservation') }}
                        </button>
                    </form>
                </article>
            </div>
        </div>
    </section>

    <div class="modal booking-date-modal" id="bookingDateModal" tabindex="-1" aria-labelledby="bookingDateModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                        <div>
                            <p class="booking-date-modal__kicker mb-1">{{ __('Reservation Date') }}</p>
                            <h3 id="bookingDateModalTitle" class="booking-date-modal__title mb-1">{{ __('Choose your date') }}</h3>
                            <p class="booking-date-modal__copy mb-0">{{ __('Pick a date and we will refresh availability for that day.') }}</p>
                        </div>
                        <button type="button" class="booking-modal-close" data-bs-dismiss="modal" aria-label="{{ __('Close date picker') }}">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div class="booking-date-modal__selection mb-3">
                        <span class="booking-date-modal__selection-label">{{ __('Selected date') }}</span>
                        <strong data-booking-date-preview>{{ \Illuminate\Support\Carbon::parse($searchDate)->translatedFormat('D, d M Y') }}</strong>
                    </div>

                    <p class="booking-date-modal__hint mb-3">
                        {{ __('Tap any available day below. Your date will be applied instantly.') }}
                    </p>

                    <div class="booking-date-modal__calendar">
                        <input id="bookingDateModalInput" type="text" class="booking-date-modal__input" value="{{ $searchDate }}" tabindex="-1" aria-hidden="true">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="booking-overlay d-none" id="occasionModal" aria-hidden="true">
        <div class="booking-modal-card" role="dialog" aria-modal="true" aria-labelledby="occasionTitle">
            <div class="booking-modal-head">
                <h4 id="occasionTitle" class="mb-0">{{ __('Select Special Occasion') }}</h4>
                <button type="button" class="booking-modal-close" data-close-occasion-modal>
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <p class="booking-modal-copy mb-3">{{ __('Are you celebrating something special? Let us know so we can prepare better.') }}</p>
            <div class="booking-occasion-grid" id="occasionGrid">
                @foreach($occasionOptions as $occasion)
                    <button type="button" class="booking-occasion-option" data-occasion-option="{{ $occasion }}">{{ $occasion }}</button>
                @endforeach
            </div>
            <button type="button" class="btn btn-brand booking-modal-apply mt-3" id="applyOccasionSelection">{{ __('Done') }}</button>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.12.4/build/css/intlTelInput.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        body.booking-flow-theme {
            --booking-radius-lg: .45rem;
            --booking-radius-md: .35rem;
            background:
                radial-gradient(circle at 86% -12%, rgba(219, 29, 48, 0.22), transparent 42%),
                radial-gradient(circle at 12% 8%, rgba(255, 149, 44, 0.14), transparent 45%),
                linear-gradient(180deg, #050505 0%, #090909 34%, #0d0d0d 100%);
            color: #f2f2f2;
        }

        body.booking-flow-theme main {
            background: transparent;
            padding-top: 0 !important;
        }

        .booking-alert {
            border: 1px solid rgba(255, 76, 76, 0.35);
            background: rgba(185, 30, 30, 0.15);
            color: #ffd7d7;
        }

        .booking-showcase,
        .booking-panel {
            border-radius: var(--booking-radius-lg);
            border: 1px solid rgba(255, 255, 255, 0.08);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0)),
                #0b0b0b;
            box-shadow: 0 18px 34px rgba(0, 0, 0, 0.22);
        }

        .booking-showcase {
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .booking-gallery__image {
            height: clamp(280px, 42vw, 480px);
            object-fit: cover;
        }

        .booking-showcase__content {
            padding: 1.2rem;
        }

        .booking-showcase__title {
            color: #fff;
            font-size: 1.35rem;
            font-weight: 700;
        }

        .booking-showcase__subtitle,
        .booking-showcase__list {
            color: rgba(255, 255, 255, 0.72);
        }

        .booking-showcase__list {
            margin: 0;
            padding-left: 1.1rem;
            display: grid;
            gap: .4rem;
        }

        .booking-panel {
            padding: 1.2rem;
        }

        .booking-kicker {
            color: rgba(255, 255, 255, 0.62);
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .12em;
            font-weight: 700;
        }

        .booking-panel__title {
            color: #fff;
            font-size: 1.45rem;
            font-weight: 700;
        }

        .booking-panel__subtitle {
            color: rgba(255, 255, 255, 0.72);
        }

        .booking-mode-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .7rem;
        }

        .booking-mode-option {
            display: grid;
            grid-template-columns: auto 1fr;
            align-items: flex-start;
            gap: .65rem;
            padding: .8rem .85rem;
            border-radius: var(--booking-radius-md);
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: rgba(255, 255, 255, 0.03);
            cursor: pointer;
            transition: border-color .2s ease, background .2s ease, transform .2s ease;
        }

        .booking-mode-option:hover {
            border-color: rgba(255, 149, 44, 0.35);
            background: rgba(255, 255, 255, 0.05);
            transform: translateY(-1px);
        }

        .booking-mode-option input {
            accent-color: #ff952c;
            margin-top: .15rem;
        }

        .booking-mode-option strong {
            color: #fff;
            font-size: .94rem;
            display: block;
            line-height: 1.2;
        }

        .booking-mode-option small {
            color: rgba(255, 255, 255, 0.62);
            font-size: .75rem;
            line-height: 1.2;
        }

        .booking-mode-option:has(input:checked) {
            border-color: rgba(255, 149, 44, 0.5);
            box-shadow: inset 0 0 0 1px rgba(255, 149, 44, 0.18);
            background: linear-gradient(180deg, rgba(255, 149, 44, 0.12), rgba(219, 29, 48, 0.08));
        }

        .booking-search {
            border-radius: var(--booking-radius-lg);
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.02);
            padding: 1rem;
        }

        .booking-search__meta {
            color: rgba(255, 255, 255, 0.74);
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            font-size: .93rem;
        }

        .booking-search__label {
            color: rgba(255, 255, 255, 0.72);
            font-size: .74rem;
            text-transform: uppercase;
            letter-spacing: .12em;
            margin-bottom: .35rem;
            font-weight: 700;
        }

        .booking-search__control {
            width: 100%;
            min-height: 3rem;
            border: 1px solid rgba(255, 255, 255, 0.16);
            background: rgba(255, 255, 255, 0.03);
            color: #fff;
            border-radius: var(--booking-radius-md);
            display: inline-flex;
            align-items: center;
            justify-content: space-between;
            gap: .6rem;
            padding: .75rem .9rem;
            text-decoration: none;
            font-weight: 500;
        }

        .booking-search__control--field {
            position: relative;
            padding: 0;
            overflow: hidden;
        }

        .booking-search__control--date {
            padding-inline: .85rem;
            justify-content: flex-start;
        }

        .booking-search__control-input {
            width: 100%;
            min-height: 3rem;
            border: 0;
            background: transparent;
            color: #fff;
            padding: .75rem 2.9rem .75rem .9rem;
            font-weight: 500;
            outline: 0;
        }

        .booking-search__control-input--date {
            padding: .75rem 0;
            cursor: pointer;
            color-scheme: dark;
        }

        .booking-search__control-input--date::-webkit-calendar-picker-indicator {
            cursor: pointer;
            filter: invert(1) brightness(0.9);
            opacity: .85;
        }

        .booking-search__control-input::placeholder {
            color: rgba(255, 255, 255, 0.46);
        }

        .booking-search__control-icon {
            position: absolute;
            right: .9rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.72);
            pointer-events: none;
        }

        .booking-search__control--select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            cursor: pointer;
            padding-right: 2.6rem;
            background-image:
                linear-gradient(45deg, transparent 50%, rgba(255, 255, 255, 0.7) 50%),
                linear-gradient(135deg, rgba(255, 255, 255, 0.7) 50%, transparent 50%);
            background-position:
                calc(100% - 1.15rem) calc(50% - 2px),
                calc(100% - .8rem) calc(50% - 2px);
            background-size: 8px 8px, 8px 8px;
            background-repeat: no-repeat;
        }

        .booking-search__control:focus,
        .booking-search__control-input:focus {
            border-color: rgba(255, 149, 44, 0.55);
            box-shadow: 0 0 0 .2rem rgba(255, 149, 44, 0.15);
            background-color: rgba(255, 255, 255, 0.04);
            color: #fff;
        }

        .booking-search__control--field:focus-within {
            border-color: rgba(255, 149, 44, 0.55);
            box-shadow: 0 0 0 .2rem rgba(255, 149, 44, 0.15);
            background-color: rgba(255, 255, 255, 0.04);
        }

        .booking-find-btn {
            width: 100%;
            min-height: 2.9rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .45rem;
            font-weight: 700;
            letter-spacing: .04em;
        }

        .booking-find-btn.is-loading {
            opacity: .92;
            cursor: wait;
        }

        .booking-step-title {
            color: #fff;
            font-size: 1.08rem;
            font-weight: 700;
        }

        .booking-slots__status {
            color: rgba(255, 255, 255, 0.66);
            font-size: .82rem;
        }

        .booking-slots__grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .65rem;
        }

        .booking-slot {
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: rgba(255, 255, 255, 0.04);
            color: #fff;
            border-radius: var(--booking-radius-md);
            min-height: 4.75rem;
            padding: .65rem;
            display: grid;
            align-content: center;
            justify-items: center;
            gap: .15rem;
            transition: border-color .2s ease, transform .2s ease, background .2s ease;
        }

        .booking-slot:hover {
            border-color: rgba(255, 149, 44, 0.45);
            transform: translateY(-1px);
        }

        .booking-slot small {
            color: rgba(255, 255, 255, 0.64);
            font-size: .73rem;
        }

        .booking-slot.is-active {
            border-color: rgba(255, 149, 44, 0.6);
            box-shadow: inset 0 0 0 1px rgba(255, 149, 44, 0.24);
            background: linear-gradient(180deg, rgba(255, 149, 44, 0.12), rgba(219, 29, 48, 0.1));
        }

        .booking-slot:disabled {
            opacity: .46;
            cursor: not-allowed;
            transform: none;
        }

        .booking-slots__empty {
            color: rgba(255, 255, 255, 0.62);
        }

        .booking-steps {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .65rem;
        }

        .booking-steps span {
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: var(--booking-radius-md);
            min-height: 2.35rem;
            display: grid;
            place-items: center;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.7);
            background: rgba(255, 255, 255, 0.02);
        }

        .booking-steps span.is-active {
            border-color: rgba(255, 149, 44, 0.45);
            color: #fff;
            background: rgba(255, 149, 44, 0.1);
        }

        .booking-form .form-label {
            color: rgba(255, 255, 255, 0.82);
            font-weight: 600;
            margin-bottom: .35rem;
        }

        .booking-form .form-control,
        .booking-form .form-select,
        .booking-inline-select {
            border: 1px solid rgba(255, 255, 255, 0.16);
            background: rgba(255, 255, 255, 0.03);
            color: #fff;
        }

        .booking-form .form-control::placeholder,
        .booking-form .form-select::placeholder {
            color: rgba(255, 255, 255, 0.46);
        }

        .booking-form .form-control:focus,
        .booking-form .form-select:focus,
        .booking-inline-select:focus {
            border-color: rgba(255, 149, 44, 0.55);
            box-shadow: 0 0 0 .2rem rgba(255, 149, 44, 0.15);
            background: rgba(255, 255, 255, 0.04);
            color: #fff;
        }

        .booking-inline-select {
            width: 100%;
            min-height: 3rem;
            border-radius: var(--booking-radius-md);
            padding: .75rem .9rem;
            display: inline-flex;
            align-items: center;
            justify-content: space-between;
            text-align: left;
            text-decoration: none;
        }

        .booking-optin .form-check-input {
            border-color: rgba(255, 255, 255, 0.35);
            background-color: rgba(255, 255, 255, 0.06);
        }

        .booking-optin .form-check-input:checked {
            background-color: var(--brand-red, #db1d30);
            border-color: var(--brand-red, #db1d30);
        }

        .booking-optin .form-check-label {
            color: rgba(255, 255, 255, 0.9);
        }

        .booking-submit-btn {
            width: 100%;
            min-height: 3rem;
            font-weight: 700;
            letter-spacing: .04em;
        }

        .booking-next-btn {
            width: 100%;
            min-height: 2.9rem;
            font-weight: 700;
            letter-spacing: .04em;
        }

        .booking-payment-note {
            color: rgba(255, 255, 255, .72);
            font-size: .84rem;
        }

        .booking-overlay {
            position: fixed;
            inset: 0;
            background: rgba(5, 5, 5, 0.74);
            backdrop-filter: blur(4px);
            display: grid;
            place-items: center;
            z-index: 5000;
            padding: 1rem;
        }

        .booking-modal-card {
            width: min(760px, 100%);
            border-radius: var(--booking-radius-lg);
            border: 1px solid rgba(255, 255, 255, 0.15);
            background: linear-gradient(180deg, #111, #161616);
            padding: 1rem;
            color: #fff;
        }

        .booking-modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .8rem;
            margin-bottom: .75rem;
        }

        .booking-modal-close {
            width: 2.2rem;
            height: 2.2rem;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: transparent;
            color: rgba(255, 255, 255, 0.82);
        }

        .booking-modal-copy {
            color: rgba(255, 255, 255, 0.72);
        }

        .booking-modal-apply {
            width: 100%;
            min-height: 2.8rem;
            font-weight: 700;
        }

        .booking-occasion-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .65rem;
        }

        .booking-occasion-option {
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.03);
            color: #fff;
            border-radius: var(--booking-radius-md);
            min-height: 3.2rem;
            padding: .5rem;
            font-weight: 500;
        }

        .booking-occasion-option.is-active {
            border-color: rgba(255, 149, 44, 0.58);
            background: rgba(255, 149, 44, 0.14);
            color: #fff;
        }

        .iti {
            width: 100%;
        }

        .iti__country-list {
            background: #121212;
            border: 1px solid rgba(255, 255, 255, 0.16);
        }

        .iti__country {
            color: #fff;
        }

        .iti__dial-code {
            color: rgba(255, 255, 255, 0.66);
        }

        .booking-date-modal .modal-dialog {
            max-width: 36rem;
        }

        .booking-date-modal {
            z-index: 2005;
        }

        #bookingDateModal ~ .modal-backdrop.show {
            z-index: 2000;
        }

        .booking-date-modal .modal-content {
            border-radius: .45rem;
            border: 1px solid rgba(255, 255, 255, 0.18);
            background:
                radial-gradient(circle at top right, rgba(255, 149, 44, 0.22), transparent 42%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.06), rgba(255, 255, 255, 0.015)),
                #0f0f10;
            box-shadow: 0 36px 92px rgba(0, 0, 0, 0.58);
            color: #fff;
        }

        .booking-date-modal .modal-body {
            padding: 1.35rem;
        }

        .booking-date-modal .modal-content,
        .booking-date-modal .modal-body,
        .booking-date-modal .booking-date-modal__calendar,
        .booking-date-modal .flatpickr-calendar.booking-calendar,
        .booking-date-modal .flatpickr-day {
            pointer-events: auto;
        }

        .booking-date-modal__kicker {
            color: rgba(255, 255, 255, 0.56);
            font-size: .74rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .booking-date-modal__title {
            color: #fff;
            font-size: 1.35rem;
            font-weight: 700;
        }

        .booking-date-modal__copy {
            color: rgba(255, 255, 255, 0.68);
            font-size: .94rem;
        }

        .booking-date-modal__selection {
            min-height: 4rem;
            border-radius: .35rem;
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: linear-gradient(180deg, rgba(255, 149, 44, 0.12), rgba(255, 255, 255, 0.04));
            color: #fff;
            display: grid;
            gap: .2rem;
            align-content: center;
            padding: .75rem .95rem;
        }

        .booking-date-modal__selection-label {
            color: rgba(255, 255, 255, 0.58);
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .booking-date-modal__selection strong {
            color: #fff;
            font-size: 1.08rem;
            font-weight: 800;
            letter-spacing: .02em;
        }

        .booking-date-modal__hint {
            color: rgba(255, 255, 255, 0.72);
            font-size: .88rem;
        }

        .booking-date-modal__calendar {
            position: relative;
            padding: 1rem;
            border-radius: .4rem;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0.015)),
                #0a0a0a;
        }

        .booking-date-modal__input {
            position: absolute;
            inset: 0;
            width: 0;
            height: 0;
            opacity: 0;
            pointer-events: none;
        }

        .flatpickr-calendar.booking-calendar {
            --booking-calendar-width: 24.5rem;
            width: var(--booking-calendar-width);
            padding: .95rem;
            border-radius: .45rem;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background:
                radial-gradient(circle at top right, rgba(255, 149, 44, 0.14), transparent 38%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0.01)),
                #111;
            box-shadow: 0 26px 54px rgba(0, 0, 0, 0.42);
        }

        .booking-date-modal .flatpickr-calendar.booking-calendar {
            --booking-calendar-width: 100%;
            padding: 0;
            border: 0;
            background: transparent;
            box-shadow: none;
            margin: 0 auto;
        }

        .flatpickr-calendar.booking-calendar::before,
        .flatpickr-calendar.booking-calendar::after {
            display: none;
        }

        .flatpickr-calendar.booking-calendar.open,
        .flatpickr-calendar.booking-calendar.inline {
            display: block;
        }

        .flatpickr-calendar.booking-calendar .flatpickr-months {
            align-items: center;
            gap: .45rem;
            margin-bottom: .85rem;
        }

        .flatpickr-calendar.booking-calendar .flatpickr-month {
            height: 3rem;
        }

        .flatpickr-calendar.booking-calendar .flatpickr-current-month {
            height: 3rem;
            padding-top: .5rem;
            color: #fff;
            font-size: 1.06rem;
            font-weight: 700;
        }

        .flatpickr-calendar.booking-calendar .flatpickr-current-month .cur-month,
        .flatpickr-calendar.booking-calendar .flatpickr-current-month input.cur-year {
            color: #fff;
            font-weight: 700;
        }

        .flatpickr-calendar.booking-calendar .numInputWrapper:hover,
        .flatpickr-calendar.booking-calendar .flatpickr-current-month .flatpickr-monthDropdown-months:hover {
            background: transparent;
        }

        .flatpickr-calendar.booking-calendar .flatpickr-prev-month,
        .flatpickr-calendar.booking-calendar .flatpickr-next-month {
            top: .95rem;
            width: 2.3rem;
            height: 2.3rem;
            padding: 0;
            border-radius: .35rem;
            color: rgba(255, 255, 255, 0.86);
            display: grid;
            place-items: center;
        }

        .flatpickr-calendar.booking-calendar .flatpickr-prev-month:hover,
        .flatpickr-calendar.booking-calendar .flatpickr-next-month:hover {
            background: rgba(255, 255, 255, 0.06);
            color: #fff;
        }

        .flatpickr-calendar.booking-calendar .flatpickr-prev-month svg,
        .flatpickr-calendar.booking-calendar .flatpickr-next-month svg {
            width: 14px;
            height: 14px;
            fill: currentColor;
        }

        .flatpickr-calendar.booking-calendar .flatpickr-weekdays {
            height: auto;
            margin-bottom: .45rem;
        }

        .flatpickr-calendar.booking-calendar .flatpickr-weekdaycontainer,
        .flatpickr-calendar.booking-calendar .flatpickr-rContainer,
        .flatpickr-calendar.booking-calendar .flatpickr-days,
        .flatpickr-calendar.booking-calendar .dayContainer {
            width: 100%;
            min-width: 100%;
            max-width: 100%;
        }

        .flatpickr-calendar.booking-calendar span.flatpickr-weekday {
            height: 2.35rem;
            line-height: 2.35rem;
            color: rgba(255, 255, 255, 0.68);
            font-size: .76rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .flatpickr-calendar.booking-calendar .dayContainer {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: .28rem 0;
        }

        .flatpickr-calendar.booking-calendar .flatpickr-day {
            width: 3rem;
            max-width: 3rem;
            height: 3rem;
            line-height: 3rem;
            margin: 0 auto;
            border-radius: .35rem;
            border: 1px solid rgba(255, 255, 255, 0.04);
            color: rgba(255, 255, 255, 0.94);
            font-size: .95rem;
            font-weight: 600;
        }

        .flatpickr-calendar.booking-calendar .flatpickr-day:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.14);
        }

        .flatpickr-calendar.booking-calendar .flatpickr-day.today {
            border-color: rgba(255, 149, 44, 0.48);
        }

        .flatpickr-calendar.booking-calendar .flatpickr-day.selected,
        .flatpickr-calendar.booking-calendar .flatpickr-day.startRange,
        .flatpickr-calendar.booking-calendar .flatpickr-day.endRange {
            background: linear-gradient(180deg, rgba(255, 149, 44, 0.92), rgba(219, 29, 48, 0.92));
            border-color: rgba(255, 149, 44, 0.72);
            color: #fff;
            box-shadow: 0 10px 24px rgba(219, 29, 48, 0.22);
        }

        .flatpickr-calendar.booking-calendar .flatpickr-day.inRange {
            background: rgba(255, 149, 44, 0.18);
            border-color: transparent;
            box-shadow: none;
        }

        .flatpickr-calendar.booking-calendar .flatpickr-day.prevMonthDay,
        .flatpickr-calendar.booking-calendar .flatpickr-day.nextMonthDay,
        .flatpickr-calendar.booking-calendar .flatpickr-day.flatpickr-disabled {
            color: rgba(255, 255, 255, 0.24);
        }

        .flatpickr-calendar.booking-calendar .flatpickr-day.flatpickr-disabled:hover,
        .flatpickr-calendar.booking-calendar .flatpickr-day.prevMonthDay:hover,
        .flatpickr-calendar.booking-calendar .flatpickr-day.nextMonthDay:hover {
            background: transparent;
            border-color: transparent;
        }

        @media (max-width: 991.98px) {
            .booking-slot {
                min-height: 4rem;
            }

            .booking-slots__grid,
            .booking-mode-grid,
            .booking-occasion-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 575.98px) {
            .booking-date-modal .modal-dialog {
                max-width: calc(100vw - 1rem);
                margin-inline: auto;
            }

            .booking-date-modal .modal-body {
                padding: 1rem;
            }

            .booking-date-modal__calendar {
                padding: .8rem;
            }

            .flatpickr-calendar.booking-calendar {
                --booking-calendar-width: min(100vw - 1.5rem, 21rem);
                padding: .75rem;
            }

            .flatpickr-calendar.booking-calendar .flatpickr-day {
                width: 2.55rem;
                max-width: 2.55rem;
                height: 2.55rem;
                line-height: 2.55rem;
            }

            .booking-slots__grid,
            .booking-mode-grid,
            .booking-steps,
            .booking-occasion-grid {
                grid-template-columns: minmax(0, 1fr);
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.12.4/build/js/intlTelInput.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        (() => {
            const root = document.querySelector('[data-booking-flow]');
            if (!root) {
                return;
            }

            const availabilityUrl = root.dataset.availabilityUrl;
            const bookingForm = root.querySelector('[data-booking-form]');
            const bookingTypeInputs = Array.from(root.querySelectorAll('[data-booking-type]'));
            const selectedSlotInput = document.getElementById('bookingSelectedSlot');
            const selectedTimeInput = document.getElementById('bookingSelectedTime');
            const bookingDateInput = document.getElementById('bookingDate');
            const bookingPersonsInput = document.getElementById('bookingPersons');
            const bookingTimeFilterInput = document.getElementById('bookingTimeFilter');
            const bookingPhoneHidden = document.getElementById('bookingPhoneHidden');
            const bookingPhoneCountry = document.getElementById('bookingPhoneCountry');
            const slotsGrid = root.querySelector('[data-slots-grid]');
            const slotsStatus = root.querySelector('[data-slots-status]');
            const slotsEmpty = root.querySelector('[data-slots-empty]');
            const findButton = document.getElementById('findBookingAvailability');
            const findSpinner = root.querySelector('[data-find-spinner]');
            const findLabel = root.querySelector('[data-find-label]');
            const eventTimeGroup = root.querySelector('[data-event-time-group]');
            const eventTimeInput = root.querySelector('[data-event-time-input]');
            const bookingSearchDateInput = document.getElementById('bookingSearchDate');
            const bookingSearchPersonsInput = document.getElementById('bookingSearchPersons');
            const bookingSearchTimeInput = document.getElementById('bookingSearchTime');
            const slotsSection = root.querySelector('[data-step-slots]');
            const detailsSection = root.querySelector('[data-step-details]');
            const paymentSection = root.querySelector('[data-step-payment]');
            const submitButton = root.querySelector('[data-step-submit]');
            const openPaymentStepButton = root.querySelector('[data-open-payment-step]');
            const bookingDateModalElement = document.getElementById('bookingDateModal');
            const bookingDateModalInput = document.getElementById('bookingDateModalInput');
            const bookingDatePreview = document.querySelector('[data-booking-date-preview]');
            const bookingDateModalCloseButton = bookingDateModalElement?.querySelector('[data-bs-dismiss="modal"]');
            const occasionModal = document.getElementById('occasionModal');
            const openOccasionModalButton = document.getElementById('openOccasionModal');
            const closeOccasionModalButtons = occasionModal?.querySelectorAll('[data-close-occasion-modal]') || [];
            const occasionButtons = Array.from(document.querySelectorAll('[data-occasion-option]'));
            const occasionLabel = document.getElementById('occasionLabel');
            const occasionValue = document.getElementById('specialOccasionValue');
            const applyOccasionButton = document.getElementById('applyOccasionSelection');
            const paymentMethodInputs = Array.from(root.querySelectorAll('input[name=\"payment_method\"]'));
            const cardCheckoutNote = root.querySelector('[data-card-checkout-note]');
            const bookingCopy = {
                checkingAvailability: @json(__('Checking availability...')),
                findAvailability: @json(__('Find Availability')),
                selectReservationDate: @json(__('Select your reservation date')),
                chooseOneSlot: @json(__('Choose one slot')),
                selectedPrefix: @json(__('Selected')),
                noAvailableSlots: @json(__('No available slots')),
                seatsLeft: @json(__('seats left')),
                chooseDateFirst: @json(__('Please choose a date first')),
                loadSlotsFailed: @json(__('Could not load slots')),
                selectSpecialOccasions: @json(__('Select special occasions')),
            };

            const getBookingType = () => bookingTypeInputs.find((input) => input.checked)?.value || 'table';
            let availabilityChecked = false;
            let availabilityAbortController = null;
            let availabilityRefreshTimer = null;
            let bookingDatePicker = null;

            if (bookingDateModalElement && bookingDateModalElement.parentElement !== document.body) {
                document.body.appendChild(bookingDateModalElement);
            }

            if (occasionModal && occasionModal.parentElement !== document.body) {
                document.body.appendChild(occasionModal);
            }

            const setStepVisible = (element, shouldShow) => {
                if (!element) {
                    return;
                }

                element.classList.toggle('d-none', !shouldShow);
            };

            const setFindLoading = (isLoading) => {
                if (!findButton) {
                    return;
                }

                findButton.toggleAttribute('disabled', isLoading);
                findButton.classList.toggle('is-loading', isLoading);
                findSpinner?.classList.toggle('d-none', !isLoading);

                if (findLabel) {
                    findLabel.innerHTML = isLoading
                        ? bookingCopy.checkingAvailability
                        : `<i class="fa-solid fa-magnifying-glass"></i> ${bookingCopy.findAvailability}`;
                }
            };

            const resetAfterSearch = () => {
                setStepVisible(detailsSection, false);
                setStepVisible(paymentSection, false);
                setStepVisible(submitButton, false);
                openPaymentStepButton?.classList.remove('d-none');
            };

            const showDetailsStep = (scroll = true) => {
                setStepVisible(detailsSection, true);
                if (scroll) {
                    detailsSection?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            };

            const showPaymentStep = (scroll = true) => {
                setStepVisible(paymentSection, true);
                setStepVisible(submitButton, true);
                openPaymentStepButton?.classList.add('d-none');
                if (scroll) {
                    paymentSection?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            };

            const showModal = (modal) => {
                if (!modal) {
                    return;
                }
                modal.classList.remove('d-none');
                modal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('overflow-hidden');
            };

            const hideModal = (modal) => {
                if (!modal) {
                    return;
                }
                modal.classList.add('d-none');
                modal.setAttribute('aria-hidden', 'true');
                if (document.querySelectorAll('.booking-overlay:not(.d-none)').length === 0) {
                    document.body.classList.remove('overflow-hidden');
                }
            };

            const queueAvailabilityRefresh = () => {
                if (!availabilityUrl) {
                    return;
                }

                window.clearTimeout(availabilityRefreshTimer);
                availabilityRefreshTimer = window.setTimeout(() => {
                    refreshAvailability();
                }, 180);
            };

            const formatSelectedDate = (dateValue) => {
                if (!dateValue) {
                    return bookingCopy.selectReservationDate;
                }

                const parsedDate = new Date(`${dateValue}T00:00:00`);

                if (Number.isNaN(parsedDate.getTime())) {
                    return dateValue;
                }

                return new Intl.DateTimeFormat(document.documentElement.lang || 'en-GB', {
                    weekday: 'short',
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                }).format(parsedDate);
            };

            const updateDatePreview = (dateValue = '') => {
                if (bookingDatePreview) {
                    bookingDatePreview.textContent = formatSelectedDate(dateValue);
                }
            };

            const syncSearchControls = () => {
                if (bookingSearchDateInput) {
                    const selectedDate = bookingDateInput.value || '';

                    if (bookingDatePicker) {
                        bookingDatePicker.setDate(selectedDate, false, 'Y-m-d');
                    }

                    bookingSearchDateInput.value = selectedDate;
                    updateDatePreview(selectedDate);
                }

                if (bookingSearchPersonsInput) {
                    bookingSearchPersonsInput.value = bookingPersonsInput.value || '1';
                }

                if (bookingSearchTimeInput) {
                    bookingSearchTimeInput.value = bookingTimeFilterInput.value || 'all';
                }
            };

            const markFiltersDirty = () => {
                const shouldRefresh = availabilityChecked;
                availabilityChecked = false;
                resetSlotSelection();
                resetAfterSearch();

                if (shouldRefresh) {
                    queueAvailabilityRefresh();
                }
            };

            const syncSelectedDate = (nextDate) => {
                if (!nextDate) {
                    return;
                }

                bookingDateInput.value = nextDate;
                syncSearchControls();
                markFiltersDirty();
            };

            const openBookingDateModal = () => {
                const selectedDate = bookingDateInput.value || bookingSearchDateInput?.value || '';

                if (bookingDatePicker) {
                    bookingDatePicker.setDate(selectedDate, false, 'Y-m-d');
                    bookingDatePicker.jumpToDate(selectedDate || bookingSearchDateInput?.dataset.minDate || 'today');
                }

                updateDatePreview(selectedDate);
            };

            const resetSlotSelection = () => {
                selectedSlotInput.value = '';
                selectedTimeInput.value = '';
                root.querySelectorAll('[data-slot-card]').forEach((button) => {
                    button.classList.remove('is-active');
                });
            };

            const selectSlotButton = (button) => {
                root.querySelectorAll('[data-slot-card]').forEach((slotButton) => {
                    slotButton.classList.remove('is-active');
                });

                if (!button) {
                    selectedSlotInput.value = '';
                    selectedTimeInput.value = '';
                    slotsStatus.textContent = bookingCopy.chooseOneSlot;
                    resetAfterSearch();
                    return;
                }

                button.classList.add('is-active');
                selectedSlotInput.value = button.dataset.slotId || '';
                selectedTimeInput.value = button.dataset.slotTime || '';
                slotsStatus.textContent = `${bookingCopy.selectedPrefix} ${button.dataset.slotTime || ''}`;
                showDetailsStep();
            };

            const renderSlots = (slots) => {
                if (!slotsGrid) {
                    return;
                }

                slotsGrid.innerHTML = '';
                const selectedBefore = selectedSlotInput.value;

                if (!Array.isArray(slots) || slots.length === 0) {
                    slotsEmpty?.classList.remove('d-none');
                    slotsStatus.textContent = bookingCopy.noAvailableSlots;
                    selectedSlotInput.value = '';
                    selectedTimeInput.value = '';
                    resetAfterSearch();
                    return;
                }

                slotsEmpty?.classList.add('d-none');
                slotsStatus.textContent = bookingCopy.chooseOneSlot;

                slots.forEach((slot) => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'booking-slot';
                    button.dataset.slotCard = '1';
                    button.dataset.slotId = String(slot.id);
                    button.dataset.slotTime = slot.start_time;
                    button.disabled = slot.can_book !== true;
                    button.innerHTML = `<strong>${slot.start_time}</strong><small>${slot.remaining} ${bookingCopy.seatsLeft}</small>`;

                    button.addEventListener('click', () => selectSlotButton(button));

                    if (String(slot.id) === String(selectedBefore) && !button.disabled) {
                        selectSlotButton(button);
                    }

                    slotsGrid.appendChild(button);
                });
            };

            const refreshAvailability = async () => {
                if (!availabilityUrl) {
                    return;
                }

                const isTableBooking = getBookingType() === 'table';
                availabilityChecked = true;
                resetAfterSearch();

                if (!isTableBooking) {
                    setStepVisible(slotsSection, false);
                    showDetailsStep();
                    return;
                }

                if (!bookingDateInput.value) {
                    setStepVisible(slotsSection, true);
                    slotsStatus.textContent = bookingCopy.chooseDateFirst;
                    return;
                }

                setStepVisible(slotsSection, true);
                slotsStatus.textContent = bookingCopy.checkingAvailability;
                setFindLoading(true);

                if (availabilityAbortController) {
                    availabilityAbortController.abort();
                }
                availabilityAbortController = new AbortController();

                const params = new URLSearchParams({
                    date: bookingDateInput.value,
                    guest_count: bookingPersonsInput.value || '2',
                    time_filter: bookingTimeFilterInput.value || 'all',
                    booking_type: getBookingType(),
                });

                try {
                    const response = await fetch(`${availabilityUrl}?${params.toString()}`, {
                        credentials: 'same-origin',
                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        signal: availabilityAbortController.signal,
                    });

                    if (!response.ok) {
                        throw new Error('Availability request failed.');
                    }

                    const payload = await response.json();
                    renderSlots(Array.isArray(payload.slots) ? payload.slots : []);
                } catch (error) {
                    if (error?.name === 'AbortError') {
                        return;
                    }

                    slotsStatus.textContent = bookingCopy.loadSlotsFailed;
                    console.error(error);
                } finally {
                    setFindLoading(false);
                    availabilityAbortController = null;
                }
            };

            const updateBookingTypeState = () => {
                const isTable = getBookingType() === 'table';
                setStepVisible(slotsSection, isTable && availabilityChecked);
                if (eventTimeGroup) {
                    eventTimeGroup.classList.toggle('d-none', isTable);
                }

                if (eventTimeInput) {
                    eventTimeInput.name = isTable ? '' : 'time';
                    eventTimeInput.required = !isTable;
                }

                if (!isTable) {
                    resetSlotSelection();
                    resetAfterSearch();
                }
            };

            const updatePaymentNote = () => {
                const selectedPayment = paymentMethodInputs.find((input) => input.checked)?.value;
                cardCheckoutNote?.classList.toggle('d-none', selectedPayment !== 'card_on_confirmation');
            };

            bookingSearchDateInput?.addEventListener('click', (event) => {
                openBookingDateModal();
            });

            bookingSearchDateInput?.addEventListener('keydown', (event) => {
                if (['Enter', ' ', 'Spacebar', 'ArrowDown'].includes(event.key)) {
                    event.preventDefault();
                    openBookingDateModal();
                    bookingSearchDateInput.click();
                }
            });

            bookingSearchPersonsInput?.addEventListener('change', () => {
                bookingPersonsInput.value = bookingSearchPersonsInput.value || '1';
                markFiltersDirty();
            });

            bookingSearchTimeInput?.addEventListener('change', () => {
                bookingTimeFilterInput.value = bookingSearchTimeInput.value || 'all';
                markFiltersDirty();
            });

            bookingDateModalElement?.addEventListener('show.bs.modal', () => {
                openBookingDateModal();
            });

            openOccasionModalButton?.addEventListener('click', () => showModal(occasionModal));
            closeOccasionModalButtons.forEach((button) => {
                button.addEventListener('click', () => hideModal(occasionModal));
            });

            occasionModal?.addEventListener('click', (event) => {
                if (event.target === occasionModal) {
                    hideModal(occasionModal);
                }
            });

            occasionButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    occasionButtons.forEach((item) => item.classList.remove('is-active'));
                    button.classList.add('is-active');
                    occasionValue.value = button.dataset.occasionOption || '';
                    occasionLabel.textContent = occasionValue.value || bookingCopy.selectSpecialOccasions;
                });
            });

            applyOccasionButton?.addEventListener('click', () => hideModal(occasionModal));

            findButton?.addEventListener('click', refreshAvailability);

            slotsGrid?.querySelectorAll('[data-slot-card]').forEach((button) => {
                button.addEventListener('click', () => selectSlotButton(button));
            });

            bookingTypeInputs.forEach((input) => {
                input.addEventListener('change', updateBookingTypeState);
            });

            paymentMethodInputs.forEach((input) => {
                input.addEventListener('change', updatePaymentNote);
            });

            openPaymentStepButton?.addEventListener('click', () => {
                const requiredFields = ['full_name', 'email', 'phone_display']
                    .map((fieldId) => document.getElementById(fieldId))
                    .filter(Boolean);

                if (getBookingType() === 'event' && eventTimeInput) {
                    requiredFields.push(eventTimeInput);
                }

                for (const field of requiredFields) {
                    if (!field.checkValidity()) {
                        field.reportValidity();
                        return;
                    }
                }

                showPaymentStep();
            });

            const phoneInput = document.getElementById('phone_display');
            let iti = null;
            if (bookingDateModalInput && window.flatpickr) {
                bookingDatePicker = window.flatpickr(bookingDateModalInput, {
                    altInput: false,
                    allowInput: false,
                    dateFormat: 'Y-m-d',
                    defaultDate: bookingDateInput.value || bookingDateModalInput.value || null,
                    disableMobile: true,
                    minDate: bookingSearchDateInput.dataset.minDate || 'today',
                    inline: true,
                    monthSelectorType: 'static',
                    nextArrow: '<svg aria-hidden="true" viewBox="0 0 17 17"><path d="M6.41 3.59 11.32 8.5l-4.91 4.91 1.18 1.18L13.68 8.5 7.59 2.41 6.41 3.59Z"></path></svg>',
                    prevArrow: '<svg aria-hidden="true" viewBox="0 0 17 17"><path d="m10.59 13.41-4.91-4.91 4.91-4.91-1.18-1.18L3.32 8.5l6.09 6.09 1.18-1.18Z"></path></svg>',
                    onChange: (_, dateString) => {
                        syncSelectedDate(dateString);
                        updateDatePreview(dateString);
                        window.setTimeout(() => {
                            bookingDateModalCloseButton?.click();
                        }, 80);
                    },
                    onReady: (_, __, instance) => {
                        instance.calendarContainer.classList.add('booking-calendar');
                        updateDatePreview(bookingDateInput.value || bookingDateModalInput.value || '');
                    },
                });
            }

            if (phoneInput && window.intlTelInput) {
                iti = window.intlTelInput(phoneInput, {
                    initialCountry: 'it',
                    preferredCountries: ['it', 'pk', 'gb', 'us'],
                    separateDialCode: true,
                    autoPlaceholder: 'aggressive',
                    utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@25.12.4/build/js/utils.js',
                });

                if (bookingPhoneHidden?.value) {
                    iti.setNumber(bookingPhoneHidden.value);
                }
            }

            bookingForm?.addEventListener('submit', (event) => {
                if (iti && bookingPhoneHidden && bookingPhoneCountry) {
                    const phoneNumber = iti.getNumber() || phoneInput.value;
                    bookingPhoneHidden.value = phoneNumber;
                    bookingPhoneCountry.value = iti.getSelectedCountryData()?.iso2 || '';
                }

                if (!availabilityChecked) {
                    event.preventDefault();
                    slotsStatus.textContent = 'Please check availability first.';
                    return;
                }

                if (getBookingType() === 'table' && !selectedSlotInput.value) {
                    event.preventDefault();
                    setStepVisible(slotsSection, true);
                    slotsStatus.textContent = 'Please choose one available slot before continuing.';
                    slotsSection?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    return;
                }

                if (paymentSection?.classList.contains('d-none')) {
                    event.preventDefault();
                    openPaymentStepButton?.click();
                    return;
                }

                if (bookingForm.dataset.submitting === '1') {
                    event.preventDefault();
                    return;
                }

                bookingForm.dataset.submitting = '1';
                submitButton?.setAttribute('disabled', 'disabled');
                if (submitButton) {
                    submitButton.textContent = 'Submitting Reservation...';
                }
            });

            syncSearchControls();
            updateBookingTypeState();
            updatePaymentNote();

            if (occasionValue.value) {
                occasionLabel.textContent = occasionValue.value;
                const selectedOccasionButton = occasionButtons.find((button) => button.dataset.occasionOption === occasionValue.value);
                selectedOccasionButton?.classList.add('is-active');
            }

            const hasValidationErrors = root.querySelector('.booking-alert') !== null;
            const hasFilledGuestDetails = ['full_name', 'email', 'phone_display'].some((fieldId) => {
                const field = document.getElementById(fieldId);

                return field && field.value.trim() !== '';
            });

            if (hasValidationErrors) {
                availabilityChecked = true;
                if (getBookingType() === 'table') {
                    setStepVisible(slotsSection, true);
                }
                setStepVisible(detailsSection, true);
                showPaymentStep(false);
            }

            if (selectedSlotInput.value) {
                const activeSlot = slotsGrid?.querySelector(`[data-slot-id="${selectedSlotInput.value}"]`);
                if (activeSlot) {
                    availabilityChecked = true;
                    setStepVisible(slotsSection, true);
                    selectSlotButton(activeSlot);
                }
            }

            if (!hasValidationErrors && getBookingType() === 'event' && hasFilledGuestDetails) {
                availabilityChecked = true;
                showDetailsStep(false);
            }

            if (!hasValidationErrors && !hasFilledGuestDetails && !selectedSlotInput.value) {
                setStepVisible(slotsSection, false);
                setStepVisible(detailsSection, false);
                setStepVisible(paymentSection, false);
                setStepVisible(submitButton, false);
            }
        })();
    </script>
@endpush
