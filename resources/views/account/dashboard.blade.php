@extends('account.layout')

@section('title', 'My Dashboard | Kashmir Grill House')
@section('account_heading', 'My Dashboard')
@section('account_intro', 'Use this page as a clean overview, then move into separate pages for orders, bookings, and account settings.')

@section('account_content')
    <section class="account-panel mb-4">
        <div class="account-panel__head">
            <div>
                <p class="account-panel__kicker mb-1">Overview</p>
                <h2 class="account-panel__title mb-0">Your Activity</h2>
            </div>
        </div>

        <div class="account-stats-grid">
            <article class="account-stat-card">
                <span class="account-stat-card__label">Total Orders</span>
                <strong>{{ $stats['orders_total'] }}</strong>
                <small>Every completed and in-progress order tied to your account.</small>
            </article>
            <article class="account-stat-card">
                <span class="account-stat-card__label">Total Spent</span>
                <strong>EUR {{ number_format((float) $stats['spent_total'], 2) }}</strong>
                <small>Calculated from paid orders in your account history.</small>
            </article>
            <article class="account-stat-card">
                <span class="account-stat-card__label">Bookings</span>
                <strong>{{ $stats['bookings_total'] }}</strong>
                <small>All table and event reservations connected to your account.</small>
            </article>
            <article class="account-stat-card">
                <span class="account-stat-card__label">Upcoming Bookings</span>
                <strong>{{ $stats['bookings_upcoming'] }}</strong>
                <small>Your upcoming reservations from today onward.</small>
            </article>
        </div>
    </section>

    <section class="account-panel mb-4">
        <div class="account-panel__head">
            <div>
                <p class="account-panel__kicker mb-1">Latest Activity</p>
                <h2 class="account-panel__title mb-0">At a Glance</h2>
            </div>
        </div>

        <div class="account-summary-grid">
            <article class="account-summary-card">
                <span class="account-summary-card__label">Latest Order</span>
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
                            {{ $latestOrder->placed_at?->format('M j, Y g:i A') ?? 'Recently placed' }}
                        </p>
                    </div>
                    <a href="{{ route('account.orders.show', $latestOrder) }}" class="account-summary-card__action mt-3">View Order Details</a>
                @else
                    <p class="mb-0">You do not have any orders yet. Your latest order will appear here once you complete checkout.</p>
                    <a href="{{ route('account.orders') }}" class="account-summary-card__action mt-3">Open Order History</a>
                @endif
            </article>

            <article class="account-summary-card">
                <span class="account-summary-card__label">Latest Booking</span>
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
                            {{ $latestBooking->date?->format('M j, Y') ?? 'Date pending' }}
                            ·
                            {{ \Illuminate\Support\Carbon::parse($latestBooking->time)->format('g:i A') }}
                        </p>
                    </div>
                    <a href="{{ route('account.bookings.show', $latestBooking) }}" class="account-summary-card__action mt-3">View Booking Details</a>
                @else
                    <p class="mb-0">You do not have any bookings yet. Your latest reservation or event request will appear here.</p>
                    <a href="{{ route('account.bookings') }}" class="account-summary-card__action mt-3">Open Booking History</a>
                @endif
            </article>
        </div>
    </section>

    <section class="account-panel">
        <div class="account-panel__head">
            <div>
                <p class="account-panel__kicker mb-1">Quick Access</p>
                <h2 class="account-panel__title mb-0">Go Where You Need</h2>
            </div>
        </div>

        <div class="account-quicklinks">
            <a href="{{ route('account.orders') }}" class="account-quicklink-card">
                <span>Orders</span>
                <strong>Order History</strong>
                <small>Review current and previous takeaway, delivery, or dine-in orders.</small>
            </a>
            <a href="{{ route('account.bookings') }}" class="account-quicklink-card">
                <span>Bookings</span>
                <strong>Booking History</strong>
                <small>Track your reservations and event requests in one place.</small>
            </a>
            <a href="{{ route('account.profile') }}" class="account-quicklink-card">
                <span>Profile</span>
                <strong>Account Details</strong>
                <small>Update your name, email, password, and manage your account.</small>
            </a>
            <a href="{{ route('book-now') }}" class="account-quicklink-card">
                <span>Reserve</span>
                <strong>Book a Table</strong>
                <small>Return to the reservation flow when you want to make your next booking.</small>
            </a>
            <a href="{{ route('menu') }}" class="account-quicklink-card">
                <span>Menu</span>
                <strong>Order Again</strong>
                <small>Jump back to the menu and place your next Kashmir Grill House order.</small>
            </a>
        </div>
    </section>
@endsection
