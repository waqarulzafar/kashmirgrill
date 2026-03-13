@extends('layouts.master')

@section('title', 'Reserve a Table | Kashmir Grill House Como')
@section('meta_description', 'Book your table or full restaurant event at Kashmir Grill House in Como with live slot availability and a guided reservation flow.')
@section('meta_keywords', 'book table Kashmir Grill House, event booking Como, reservation slots Como halal restaurant')
@section('body_class', 'booking-flow-theme')

@php
    $searchDate = old('date', $selectedDate ?? now()->toDateString());
    $searchGuests = (int) old('persons', $selectedGuests ?? 2);
    $searchTimeFilter = old('time_filter', $selectedTimeFilter ?? 'all');
    $bookingType = old('booking_type', $selectedBookingType ?? \App\Models\Booking::TYPE_TABLE);
    $selectedSlotId = old('selected_slot_id');
@endphp

@section('content')
    <section class="container py-4 py-lg-5 booking-flow" data-booking-flow data-availability-url="{{ route('bookings.availability') }}">
        @if ($errors->any())
            <div class="alert alert-danger booking-alert mb-4">
                <strong>Please fix the following before submitting:</strong>
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
                                <img src="{{ asset('assets/images/menu/griglia/mix-grill-tandoori.jpg') }}" alt="Kashmir Grill House signature grill platter" class="d-block w-100 booking-gallery__image" loading="lazy" decoding="async">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('assets/images/menu/primi-piati/butter-chicken.jpg') }}" alt="Butter chicken served for dining guests" class="d-block w-100 booking-gallery__image" loading="lazy" decoding="async">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('assets/images/menu/antipasti/samosa-chaat.jpg') }}" alt="Starter options for table and event bookings" class="d-block w-100 booking-gallery__image" loading="lazy" decoding="async">
                            </div>
                        </div>
                    </div>

                    <div class="booking-showcase__content">
                        <h2 class="booking-showcase__title mb-2">Find Your Perfect Reservation</h2>
                        <p class="booking-showcase__subtitle mb-3">
                            Reserve a table for regular service or request the whole restaurant for private events.
                        </p>
                        <ul class="booking-showcase__list mb-0">
                            <li>Live slot availability based on active admin time slots.</li>
                            <li>Dedicated event booking mode for exclusive restaurant use.</li>
                            <li>Fast guest details flow with international phone input.</li>
                        </ul>
                    </div>
                </article>
            </div>

            <div class="col-12 col-xl-6">
                <article class="booking-panel h-100">
                    <header class="booking-panel__header">
                        <p class="booking-kicker mb-1">Reservation Flow</p>
                        <h1 class="booking-panel__title mb-2">Book Table or Full Event</h1>
                        <p class="booking-panel__subtitle mb-0">Pick date, party size, and preferred timing first, then continue with guest and payment details.</p>
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
                            <label for="website">Website</label>
                            <input id="website" name="website" type="text" autocomplete="off" tabindex="-1">
                        </div>

                        <div class="booking-mode-grid" data-booking-type-grid>
                            @foreach($bookingTypeOptions as $value => $label)
                                <label class="booking-mode-option">
                                    <input type="radio" name="booking_type" value="{{ $value }}" @checked($bookingType === $value) data-booking-type>
                                    <span>
                                        <strong>{{ $label }}</strong>
                                        <small>{{ $value === \App\Models\Booking::TYPE_TABLE ? 'Choose from available seatings' : 'Reserve for private functions' }}</small>
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
                                    <p class="booking-search__label">Date</p>
                                    <button class="booking-search__control" type="button" id="openBookingFilterModal">
                                        <span data-search-date-label>{{ \Carbon\Carbon::parse($searchDate)->format('d M Y') }}</span>
                                        <i class="fa-regular fa-calendar"></i>
                                    </button>
                                </div>
                                <div class="col-6 col-md-4">
                                    <p class="booking-search__label">Size</p>
                                    <div class="booking-search__control">
                                        <span data-search-size-label>{{ $searchGuests }}</span>
                                        <i class="fa-solid fa-users"></i>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <p class="booking-search__label">Time</p>
                                    <div class="booking-search__control">
                                        <span data-search-time-label>{{ ucfirst($searchTimeFilter) }}</span>
                                        <i class="fa-regular fa-clock"></i>
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-brand booking-find-btn mt-3" type="button" id="findBookingAvailability">
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" data-find-spinner></span>
                                <span data-find-label>
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    Find Availability
                                </span>
                            </button>
                        </section>

                        <section class="booking-slots mt-4 d-none" data-slots-section data-step-slots>
                            <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
                                <h3 class="booking-step-title mb-0">Available Times</h3>
                                <span class="booking-slots__status" data-slots-status>Choose one slot</span>
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
                                        <small>{{ $slot['remaining'] }} seats left</small>
                                    </button>
                                @endforeach
                            </div>
                            <p class="booking-slots__empty mb-0 {{ $slotAvailability->isNotEmpty() ? 'd-none' : '' }}" data-slots-empty>
                                No slots found for this selection. Try another date or party size.
                            </p>
                        </section>

                        <section class="booking-details mt-4 d-none" data-step-details>
                            <div class="booking-steps mb-4">
                                <span class="is-active">1. Guest Details</span>
                                <span>2. Preferences</span>
                                <span>3. Checkout</span>
                            </div>

                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="full_name" class="form-label">Full Name</label>
                                    <input id="full_name" name="full_name" type="text" class="form-control" value="{{ old('full_name') }}" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input id="email" name="email" type="email" class="form-control" value="{{ old('email') }}" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="phone_display" class="form-label">Phone Number</label>
                                    <input id="phone_display" name="phone_display" type="tel" class="form-control" value="{{ old('phone') }}" required>
                                </div>
                                <div class="col-12 col-md-6 {{ $bookingType === \App\Models\Booking::TYPE_TABLE ? 'd-none' : '' }}" data-event-time-group>
                                    <label for="event_time" class="form-label">Preferred Start Time</label>
                                    <input id="event_time" type="time" class="form-control" value="{{ old('time') }}" data-event-time-input @if($bookingType === \App\Models\Booking::TYPE_EVENT) name="time" required @endif>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="table_preference" class="form-label">Seating Preference</label>
                                    <select id="table_preference" name="table_preference" class="form-select">
                                        <option value="">No preference</option>
                                        <option value="Window" @selected(old('table_preference') === 'Window')>Window</option>
                                        <option value="Quiet Corner" @selected(old('table_preference') === 'Quiet Corner')>Quiet Corner</option>
                                        <option value="Family Seating" @selected(old('table_preference') === 'Family Seating')>Family Seating</option>
                                        <option value="Outdoor" @selected(old('table_preference') === 'Outdoor')>Outdoor</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="selected_menu" class="form-label">Menu Focus</label>
                                    <select id="selected_menu" name="selected_menu" class="form-select">
                                        <option value="">Choose menu focus</option>
                                        <option value="A la carte" @selected(old('selected_menu') === 'A la carte')>A la carte</option>
                                        <option value="Family Sharing" @selected(old('selected_menu') === 'Family Sharing')>Family Sharing</option>
                                        <option value="Vegetarian" @selected(old('selected_menu') === 'Vegetarian')>Vegetarian</option>
                                        <option value="Chef Specials" @selected(old('selected_menu') === 'Chef Specials')>Chef Specials</option>
                                        <option value="Event Buffet" @selected(old('selected_menu') === 'Event Buffet')>Event Buffet</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Special Occasion</label>
                                    <button type="button" class="booking-inline-select" id="openOccasionModal">
                                        <span id="occasionLabel">{{ old('special_occasion', 'Select special occasions') }}</span>
                                        <i class="fa-solid fa-chevron-right"></i>
                                    </button>
                                    <input type="hidden" name="special_occasion" id="specialOccasionValue" value="{{ old('special_occasion') }}">
                                </div>

                                <div class="col-12">
                                    <label for="additional_notes" class="form-label">Message</label>
                                    <textarea id="additional_notes" name="additional_notes" class="form-control" rows="4" placeholder="Any special requests or preferences?">{{ old('additional_notes') }}</textarea>
                                </div>
                            </div>

                            <button type="button" class="btn btn-brand-outline booking-next-btn mt-4" data-open-payment-step>
                                Continue to Checkout
                            </button>
                        </section>

                        <section class="booking-payment mt-4 d-none" data-step-payment>
                            <h3 class="booking-step-title mb-3">Checkout Preference</h3>
                            <div class="booking-mode-grid">
                                @foreach($paymentMethodOptions as $value => $label)
                                    <label class="booking-mode-option booking-mode-option--payment">
                                        <input type="radio" name="payment_method" value="{{ $value }}" @checked(old('payment_method', \App\Models\Booking::PAYMENT_METHOD_PAY_ON_ARRIVAL) === $value)>
                                        <span>
                                            <strong>{{ $label }}</strong>
                                            <small>{{ $value === \App\Models\Booking::PAYMENT_METHOD_CARD_ON_CONFIRMATION ? 'Secure payment link sent once slot is confirmed' : 'Settle bill at the restaurant' }}</small>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            <p class="booking-payment-note mt-2 mb-0 d-none" data-card-checkout-note>
                                Card details are collected through a secure checkout link after availability confirmation.
                            </p>

                            <div class="form-check mt-3 booking-optin">
                                <input class="form-check-input" type="checkbox" value="1" id="marketing_opt_in" name="marketing_opt_in" @checked(old('marketing_opt_in'))>
                                <label class="form-check-label" for="marketing_opt_in">
                                    I would like to receive updates and offers from Kashmir Grill House.
                                </label>
                            </div>
                        </section>

                        <button type="submit" class="btn btn-brand booking-submit-btn mt-4 d-none" data-step-submit>
                            Continue Reservation
                        </button>
                    </form>
                </article>
            </div>
        </div>
    </section>

    <div class="booking-overlay d-none" id="bookingFilterModal" aria-hidden="true">
        <div class="booking-modal-card" role="dialog" aria-modal="true" aria-labelledby="bookingFilterTitle">
            <div class="booking-modal-head">
                <h4 id="bookingFilterTitle" class="mb-0">Select Date, Party Size, and Time</h4>
                <button type="button" class="booking-modal-close" data-close-booking-modal>
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="row g-3 mt-1">
                <div class="col-12">
                    <label for="bookingFilterDate" class="form-label">Date</label>
                    <input id="bookingFilterDate" type="date" class="form-control" value="{{ $searchDate }}" min="{{ now()->toDateString() }}">
                </div>
                <div class="col-12 col-md-6">
                    <label for="bookingFilterPersons" class="form-label">Persons</label>
                    <select id="bookingFilterPersons" class="form-select">
                        @for($i = 1; $i <= 80; $i++)
                            <option value="{{ $i }}" @selected($searchGuests === $i)>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-12 col-md-6">
                    <label for="bookingFilterTime" class="form-label">Time Window</label>
                    <select id="bookingFilterTime" class="form-select">
                        <option value="all" @selected($searchTimeFilter === 'all')>All</option>
                        <option value="lunch" @selected($searchTimeFilter === 'lunch')>Lunch</option>
                        <option value="dinner" @selected($searchTimeFilter === 'dinner')>Dinner</option>
                    </select>
                </div>
            </div>
            <button type="button" class="btn btn-brand booking-modal-apply mt-3" id="applyBookingFilters">Find Availability</button>
        </div>
    </div>

    <div class="booking-overlay d-none" id="occasionModal" aria-hidden="true">
        <div class="booking-modal-card" role="dialog" aria-modal="true" aria-labelledby="occasionTitle">
            <div class="booking-modal-head">
                <h4 id="occasionTitle" class="mb-0">Select Special Occasion</h4>
                <button type="button" class="booking-modal-close" data-close-occasion-modal>
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <p class="booking-modal-copy mb-3">Are you celebrating something special? Let us know so we can prepare better.</p>
            <div class="booking-occasion-grid" id="occasionGrid">
                @foreach($occasionOptions as $occasion)
                    <button type="button" class="booking-occasion-option" data-occasion-option="{{ $occasion }}">{{ $occasion }}</button>
                @endforeach
            </div>
            <button type="button" class="btn btn-brand booking-modal-apply mt-3" id="applyOccasionSelection">Done</button>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.12.4/build/css/intlTelInput.css">
    <style>
        body.booking-flow-theme {
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
            border-radius: 1rem;
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
            border-radius: .8rem;
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
            border-radius: .95rem;
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
            border-radius: .7rem;
            display: inline-flex;
            align-items: center;
            justify-content: space-between;
            gap: .6rem;
            padding: .75rem .9rem;
            text-decoration: none;
            font-weight: 500;
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
            border-radius: .75rem;
            min-height: 4.5rem;
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
            border-radius: .65rem;
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
            border-radius: .7rem;
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
            border-radius: .9rem;
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
            border-radius: .65rem;
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
            const dateLabel = root.querySelector('[data-search-date-label]');
            const sizeLabel = root.querySelector('[data-search-size-label]');
            const timeLabel = root.querySelector('[data-search-time-label]');
            const slotsSection = root.querySelector('[data-step-slots]');
            const detailsSection = root.querySelector('[data-step-details]');
            const paymentSection = root.querySelector('[data-step-payment]');
            const submitButton = root.querySelector('[data-step-submit]');
            const openPaymentStepButton = root.querySelector('[data-open-payment-step]');

            const filterModal = document.getElementById('bookingFilterModal');
            const openFilterModalButton = document.getElementById('openBookingFilterModal');
            const closeFilterModalButtons = filterModal?.querySelectorAll('[data-close-booking-modal]') || [];
            const applyFiltersButton = document.getElementById('applyBookingFilters');
            const filterDateInput = document.getElementById('bookingFilterDate');
            const filterPersonsInput = document.getElementById('bookingFilterPersons');
            const filterTimeInput = document.getElementById('bookingFilterTime');

            const occasionModal = document.getElementById('occasionModal');
            const openOccasionModalButton = document.getElementById('openOccasionModal');
            const closeOccasionModalButtons = occasionModal?.querySelectorAll('[data-close-occasion-modal]') || [];
            const occasionButtons = Array.from(document.querySelectorAll('[data-occasion-option]'));
            const occasionLabel = document.getElementById('occasionLabel');
            const occasionValue = document.getElementById('specialOccasionValue');
            const applyOccasionButton = document.getElementById('applyOccasionSelection');
            const paymentMethodInputs = Array.from(root.querySelectorAll('input[name=\"payment_method\"]'));
            const cardCheckoutNote = root.querySelector('[data-card-checkout-note]');

            const getBookingType = () => bookingTypeInputs.find((input) => input.checked)?.value || 'table';
            const autoOpenFiltersKey = 'kgh-booking-filter-autoshown';
            let availabilityChecked = false;
            let availabilityAbortController = null;

            [filterModal, occasionModal].forEach((modal) => {
                if (modal && modal.parentElement !== document.body) {
                    document.body.appendChild(modal);
                }
            });

            const formatDateLabel = (dateValue) => {
                if (!dateValue) {
                    return 'Select date';
                }

                const parsed = new Date(`${dateValue}T00:00:00`);
                return Number.isNaN(parsed.getTime())
                    ? dateValue
                    : parsed.toLocaleDateString(undefined, { day: '2-digit', month: 'short', year: 'numeric' });
            };

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
                        ? 'Checking availability...'
                        : '<i class="fa-solid fa-magnifying-glass"></i> Find Availability';
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

            const updateSearchLabels = () => {
                dateLabel.textContent = formatDateLabel(bookingDateInput.value);
                sizeLabel.textContent = bookingPersonsInput.value || '1';
                timeLabel.textContent = bookingTimeFilterInput.value
                    ? bookingTimeFilterInput.value.charAt(0).toUpperCase() + bookingTimeFilterInput.value.slice(1)
                    : 'All';
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
                    slotsStatus.textContent = 'Choose one slot';
                    resetAfterSearch();
                    return;
                }

                button.classList.add('is-active');
                selectedSlotInput.value = button.dataset.slotId || '';
                selectedTimeInput.value = button.dataset.slotTime || '';
                slotsStatus.textContent = `Selected ${button.dataset.slotTime || ''}`;
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
                    slotsStatus.textContent = 'No available slots';
                    selectedSlotInput.value = '';
                    selectedTimeInput.value = '';
                    resetAfterSearch();
                    return;
                }

                slotsEmpty?.classList.add('d-none');
                slotsStatus.textContent = 'Choose one slot';

                slots.forEach((slot) => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'booking-slot';
                    button.dataset.slotCard = '1';
                    button.dataset.slotId = String(slot.id);
                    button.dataset.slotTime = slot.start_time;
                    button.disabled = slot.can_book !== true;
                    button.innerHTML = `<strong>${slot.start_time}</strong><small>${slot.remaining} seats left</small>`;

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
                    slotsStatus.textContent = 'Please choose a date first';
                    return;
                }

                setStepVisible(slotsSection, true);
                slotsStatus.textContent = 'Checking availability...';
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

                    slotsStatus.textContent = 'Could not load slots';
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

            openFilterModalButton?.addEventListener('click', () => showModal(filterModal));
            closeFilterModalButtons.forEach((button) => {
                button.addEventListener('click', () => hideModal(filterModal));
            });

            filterModal?.addEventListener('click', (event) => {
                if (event.target === filterModal) {
                    hideModal(filterModal);
                }
            });

            applyFiltersButton?.addEventListener('click', () => {
                bookingDateInput.value = filterDateInput.value;
                bookingPersonsInput.value = filterPersonsInput.value;
                bookingTimeFilterInput.value = filterTimeInput.value;
                updateSearchLabels();
                availabilityChecked = false;
                resetSlotSelection();
                resetAfterSearch();
                hideModal(filterModal);
                refreshAvailability();
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
                    occasionLabel.textContent = occasionValue.value || 'Select special occasions';
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

            updateSearchLabels();
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

            if (!hasValidationErrors && !hasFilledGuestDetails && !selectedSlotInput.value && !sessionStorage.getItem(autoOpenFiltersKey)) {
                showModal(filterModal);
                sessionStorage.setItem(autoOpenFiltersKey, '1');
            }
        })();
    </script>
@endpush
