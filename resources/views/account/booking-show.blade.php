@extends('account.layout')

@section('title', 'Booking Details | Kashmir Grill House')
@section('account_heading', 'Booking Details')
@section('account_intro', 'See the full reservation record, including guest details, booking preferences, and payment status.')

@section('account_content')
    @php
        $statusClass = match ($booking->status) {
            \App\Models\Booking::STATUS_CONFIRMED => 'is-success',
            \App\Models\Booking::STATUS_CANCELLED => 'is-danger',
            default => 'is-warn',
        };
        $paymentClass = match ($booking->payment_status) {
            \App\Models\Booking::PAYMENT_STATUS_PAID => 'is-success',
            \App\Models\Booking::PAYMENT_STATUS_CANCELLED => 'is-danger',
            default => 'is-warn',
        };
    @endphp

    <div class="account-toolbar">
        <a href="{{ route('account.bookings') }}" class="account-toolbar__back">Back to Booking History</a>
    </div>

    <div class="account-detail-grid">
        <div class="account-detail-stack">
            <section class="account-panel">
                <div class="account-panel__head">
                    <div>
                        <p class="account-panel__kicker mb-1">Booking</p>
                        <h2 class="account-panel__title mb-0">{{ $booking->formattedReference() }}</h2>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="account-badge {{ $statusClass }}">{{ $statusLabels[$booking->status] ?? ucfirst((string) $booking->status) }}</span>
                        <span class="account-badge {{ $paymentClass }}">{{ $paymentStatusLabels[$booking->payment_status] ?? ucfirst((string) $booking->payment_status) }}</span>
                    </div>
                </div>

                <div class="account-detail-meta">
                    <div>
                        <span class="account-detail-label">Booking Type</span>
                        <div class="account-detail-value">{{ $bookingTypeLabels[$booking->booking_type] ?? ucfirst((string) $booking->booking_type) }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">Submitted</span>
                        <div class="account-detail-value">{{ $booking->created_at?->format('M j, Y g:i A') ?? 'Not available' }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">Guest Name</span>
                        <div class="account-detail-value">{{ $booking->full_name }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">Phone</span>
                        <div class="account-detail-value">{{ $booking->phone }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">Email</span>
                        <div class="account-detail-value">{{ $booking->email }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">Guests</span>
                        <div class="account-detail-value">{{ $booking->persons }}</div>
                    </div>
                </div>
            </section>

            <section class="account-panel">
                <div class="account-panel__head">
                    <div>
                        <p class="account-panel__kicker mb-1">Preferences</p>
                        <h2 class="account-panel__title mb-0">Reservation Details</h2>
                    </div>
                </div>

                <div class="account-detail-meta">
                    <div>
                        <span class="account-detail-label">Date</span>
                        <div class="account-detail-value">{{ $booking->date?->format('M j, Y') ?? 'Not available' }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">Time</span>
                        <div class="account-detail-value">{{ \Illuminate\Support\Carbon::parse($booking->time)->format('g:i A') }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">Slot</span>
                        <div class="account-detail-value">{{ $booking->dineInSlot?->name ?: 'No slot selected' }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">Payment Method</span>
                        <div class="account-detail-value">{{ $paymentMethodLabels[$booking->payment_method] ?? 'Not set' }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">Special Occasion</span>
                        <div class="account-detail-value">{{ $booking->special_occasion ?: 'Not specified' }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">Table Preference</span>
                        <div class="account-detail-value">{{ $booking->table_preference ?: 'Not specified' }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">Selected Menu</span>
                        <div class="account-detail-value">{{ $booking->selected_menu ?: 'Not specified' }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">Marketing Opt In</span>
                        <div class="account-detail-value">{{ $booking->marketing_opt_in ? 'Yes' : 'No' }}</div>
                    </div>
                </div>
            </section>
        </div>

        <div class="account-detail-stack">
            <section class="account-detail-card">
                <h3 class="account-detail-card__title">Status Summary</h3>
                <div class="account-detail-card__row mb-3">
                    <span class="account-detail-label">Booking Status</span>
                    <div class="account-detail-value">{{ $statusLabels[$booking->status] ?? ucfirst((string) $booking->status) }}</div>
                </div>
                <div class="account-detail-card__row mb-3">
                    <span class="account-detail-label">Payment Status</span>
                    <div class="account-detail-value">{{ $paymentStatusLabels[$booking->payment_status] ?? ucfirst((string) $booking->payment_status) }}</div>
                </div>
                <div class="account-detail-card__row">
                    <span class="account-detail-label">Payment Method</span>
                    <div class="account-detail-value">{{ $paymentMethodLabels[$booking->payment_method] ?? 'Not set' }}</div>
                </div>
            </section>

            <section class="account-detail-card">
                <h3 class="account-detail-card__title">Additional Notes</h3>
                <p class="account-detail-copy">{{ $booking->additional_notes ?: 'No additional notes were added to this booking.' }}</p>
            </section>
        </div>
    </div>
@endsection
