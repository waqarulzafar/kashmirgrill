@extends('account.layout')

@section('title', __('My Dashboard | Kashmir Grill House'))
@section('account_heading', __('My Dashboard'))
@section('account_intro', __('Use this page as a clean overview, then move into separate pages for orders, bookings, and account settings.'))

@section('account_content')
    <section class="account-panel mb-4">
        <div class="account-panel__head">
            <div>
                <p class="account-panel__kicker mb-1">{{ __('Overview') }}</p>
                <h2 class="account-panel__title mb-0">{{ __('Your Activity') }}</h2>
            </div>
        </div>

        <div class="account-stats-grid">
            <article class="account-stat-card">
                <span class="account-stat-card__label">{{ __('Total Orders') }}</span>
                <strong>{{ $stats['orders_total'] }}</strong>
                <small>{{ __('Every completed and in-progress order tied to your account.') }}</small>
            </article>
            <article class="account-stat-card">
                <span class="account-stat-card__label">{{ __('Total Spent') }}</span>
                <strong>EUR {{ number_format((float) $stats['spent_total'], 2) }}</strong>
                <small>{{ __('Calculated from paid orders in your account history.') }}</small>
            </article>
            <article class="account-stat-card">
                <span class="account-stat-card__label">{{ __('Bookings') }}</span>
                <strong>{{ $stats['bookings_total'] }}</strong>
                <small>{{ __('All table and event reservations connected to your account.') }}</small>
            </article>
            <article class="account-stat-card">
                <span class="account-stat-card__label">{{ __('Upcoming Bookings') }}</span>
                <strong>{{ $stats['bookings_upcoming'] }}</strong>
                <small>{{ __('Your upcoming reservations from today onward.') }}</small>
            </article>
        </div>
    </section>

    <section class="account-panel mb-4">
        <div class="account-panel__head">
            <div>
                <p class="account-panel__kicker mb-1">{{ __('Latest Activity') }}</p>
                <h2 class="account-panel__title mb-0">{{ __('At a Glance') }}</h2>
            </div>
        </div>

        <div class="account-summary-grid">
            <article class="account-summary-card">
                <span class="account-summary-card__label">{{ __('Latest Order') }}</span>
                @if ($latestOrder)
                    @php
                        $orderStatusClass = match ($latestOrder->status) {
                            \App\Models\Order::STATUS_COMPLETED => 'is-success',
                            \App\Models\Order::STATUS_CANCELLED, \App\Models\Order::STATUS_PAYMENT_FAILED => 'is-danger',
                            default => 'is-warn',
                        };
                    @endphp
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex flex-wrap justify-content-between gap-2 align-items-start">
                            <strong>{{ $latestOrder->reference }}</strong>
                            <span class="account-badge {{ $orderStatusClass }}">{{ $orderStatusLabels[$latestOrder->status] ?? ucfirst((string) $latestOrder->status) }}</span>
                        </div>
                        <p class="mb-0">
                            {{ $orderFulfillmentLabels[$latestOrder->fulfillment_type] ?? ucfirst((string) $latestOrder->fulfillment_type) }}
                            ·
                            EUR {{ number_format((float) $latestOrder->total, 2) }}
                            ·
                            {{ $latestOrder->placed_at?->translatedFormat('M j, Y g:i A') ?? __('Recently placed') }}
                        </p>
                    </div>
                    <a href="{{ route('account.orders.show', $latestOrder) }}" class="account-summary-card__action mt-3">{{ __('View Order Details') }}</a>
                @else
                    <p class="mb-0">{{ __('You do not have any orders yet. Your latest order will appear here once you complete checkout.') }}</p>
                    <a href="{{ route('account.orders') }}" class="account-summary-card__action mt-3">{{ __('Open Order History') }}</a>
                @endif
            </article>

            <article class="account-summary-card">
                <span class="account-summary-card__label">{{ __('Latest Booking') }}</span>
                @if ($latestBooking)
                    @php
                        $bookingStatusClass = match ($latestBooking->status) {
                            \App\Models\Booking::STATUS_CONFIRMED => 'is-success',
                            \App\Models\Booking::STATUS_CANCELLED => 'is-danger',
                            default => 'is-warn',
                        };
                    @endphp
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex flex-wrap justify-content-between gap-2 align-items-start">
                            <strong>{{ $latestBooking->formattedReference() }}</strong>
                            <span class="account-badge {{ $bookingStatusClass }}">{{ $bookingStatusLabels[$latestBooking->status] ?? ucfirst((string) $latestBooking->status) }}</span>
                        </div>
                        <p class="mb-0">
                            {{ $bookingTypeLabels[$latestBooking->booking_type] ?? ucfirst((string) $latestBooking->booking_type) }}
                            ·
                            {{ $latestBooking->date?->translatedFormat('M j, Y') ?? __('Date pending') }}
                            ·
                            {{ \Illuminate\Support\Carbon::parse($latestBooking->time)->translatedFormat('g:i A') }}
                        </p>
                    </div>
                    <a href="{{ route('account.bookings.show', $latestBooking) }}" class="account-summary-card__action mt-3">{{ __('View Booking Details') }}</a>
                @else
                    <p class="mb-0">{{ __('You do not have any bookings yet. Your latest reservation or event request will appear here.') }}</p>
                    <a href="{{ route('account.bookings') }}" class="account-summary-card__action mt-3">{{ __('Open Booking History') }}</a>
                @endif
            </article>
        </div>
    </section>

    <section class="account-panel">
        <div class="account-panel__head">
            <div>
                <p class="account-panel__kicker mb-1">{{ __('Quick Access') }}</p>
                <h2 class="account-panel__title mb-0">{{ __('Go Where You Need') }}</h2>
            </div>
        </div>

        <div class="account-quicklinks">
            <a href="{{ route('account.orders') }}" class="account-quicklink-card">
                <span>{{ __('Orders') }}</span>
                <strong>{{ __('Order History') }}</strong>
                <small>{{ __('Review current and previous takeaway, delivery, or dine-in orders.') }}</small>
            </a>
            <a href="{{ route('account.bookings') }}" class="account-quicklink-card">
                <span>{{ __('Bookings') }}</span>
                <strong>{{ __('Booking History') }}</strong>
                <small>{{ __('Track your reservations and event requests in one place.') }}</small>
            </a>
            <a href="{{ route('account.profile') }}" class="account-quicklink-card">
                <span>{{ __('Profile') }}</span>
                <strong>{{ __('Account Details') }}</strong>
                <small>{{ __('Update your name, email, password, and manage your account.') }}</small>
            </a>
            <a href="{{ route('book-now') }}" class="account-quicklink-card">
                <span>{{ __('Reserve') }}</span>
                <strong>{{ __('Book a Table') }}</strong>
                <small>{{ __('Return to the reservation flow when you want to make your next booking.') }}</small>
            </a>
            <a href="{{ route('menu') }}" class="account-quicklink-card">
                <span>{{ __('Menu') }}</span>
                <strong>{{ __('Order Again') }}</strong>
                <small>{{ __('Jump back to the menu and place your next Kashmir Grill House order.') }}</small>
            </a>
        </div>
    </section>
@endsection
