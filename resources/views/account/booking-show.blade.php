@extends('account.layout')

@section('title', __('Booking Details | Kashmir Grill House'))
@section('account_heading', __('Booking Details'))
@section('account_intro', __('See the full reservation record, including guest details, booking preferences, and payment status.'))

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
        <a href="{{ route('account.bookings') }}" class="account-toolbar__back">{{ __('Back to Booking History') }}</a>
    </div>

    <div class="account-detail-grid">
        <div class="account-detail-stack">
            <section class="account-panel">
                <div class="account-panel__head">
                    <div>
                        <p class="account-panel__kicker mb-1">{{ __('Booking') }}</p>
                        <h2 class="account-panel__title mb-0">{{ $booking->formattedReference() }}</h2>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="account-badge {{ $statusClass }}">{{ $statusLabels[$booking->status] ?? ucfirst((string) $booking->status) }}</span>
                        <span class="account-badge {{ $paymentClass }}">{{ $paymentStatusLabels[$booking->payment_status] ?? ucfirst((string) $booking->payment_status) }}</span>
                    </div>
                </div>

                <div class="account-detail-meta">
                    <div>
                        <span class="account-detail-label">{{ __('Booking Type') }}</span>
                        <div class="account-detail-value">{{ $bookingTypeLabels[$booking->booking_type] ?? ucfirst((string) $booking->booking_type) }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">{{ __('Submitted') }}</span>
                        <div class="account-detail-value">{{ $booking->created_at?->translatedFormat('M j, Y g:i A') ?? __('Not available') }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">{{ __('Guest Name') }}</span>
                        <div class="account-detail-value">{{ $booking->full_name }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">{{ __('Phone') }}</span>
                        <div class="account-detail-value">{{ $booking->phone }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">{{ __('Email') }}</span>
                        <div class="account-detail-value">{{ $booking->email }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">{{ __('Guests') }}</span>
                        <div class="account-detail-value">{{ $booking->persons }}</div>
                    </div>
                </div>
            </section>

            <section class="account-panel">
                <div class="account-panel__head">
                    <div>
                        <p class="account-panel__kicker mb-1">{{ __('Preferences') }}</p>
                        <h2 class="account-panel__title mb-0">{{ __('Reservation Details') }}</h2>
                    </div>
                </div>

                <div class="account-detail-meta">
                    <div>
                        <span class="account-detail-label">{{ __('Date') }}</span>
                        <div class="account-detail-value">{{ $booking->date?->translatedFormat('M j, Y') ?? __('Not available') }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">{{ __('Time') }}</span>
                        <div class="account-detail-value">{{ \Illuminate\Support\Carbon::parse($booking->time)->translatedFormat('g:i A') }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">{{ __('Slot') }}</span>
                        <div class="account-detail-value">{{ $booking->dineInSlot?->name ?: __('No slot selected') }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">{{ __('Payment Method') }}</span>
                        <div class="account-detail-value">{{ $paymentMethodLabels[$booking->payment_method] ?? __('Not set') }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">{{ __('Special Occasion') }}</span>
                        <div class="account-detail-value">{{ $booking->special_occasion ?: __('Not specified') }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">{{ __('Table Preference') }}</span>
                        <div class="account-detail-value">{{ $booking->table_preference ?: __('Not specified') }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">{{ __('Selected Menu') }}</span>
                        <div class="account-detail-value">{{ $booking->selected_menu ?: __('Not specified') }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">{{ __('Marketing Opt In') }}</span>
                        <div class="account-detail-value">{{ $booking->marketing_opt_in ? __('Yes') : __('No') }}</div>
                    </div>
                </div>
            </section>
        </div>

        <div class="account-detail-stack">
            <section class="account-detail-card">
                <h3 class="account-detail-card__title">{{ __('Status Summary') }}</h3>
                <div class="account-detail-card__row mb-3">
                    <span class="account-detail-label">{{ __('Booking Status') }}</span>
                    <div class="account-detail-value">{{ $statusLabels[$booking->status] ?? ucfirst((string) $booking->status) }}</div>
                </div>
                <div class="account-detail-card__row mb-3">
                    <span class="account-detail-label">{{ __('Payment Status') }}</span>
                    <div class="account-detail-value">{{ $paymentStatusLabels[$booking->payment_status] ?? ucfirst((string) $booking->payment_status) }}</div>
                </div>
                <div class="account-detail-card__row">
                    <span class="account-detail-label">{{ __('Payment Method') }}</span>
                    <div class="account-detail-value">{{ $paymentMethodLabels[$booking->payment_method] ?? __('Not set') }}</div>
                </div>
            </section>

            <section class="account-detail-card">
                <h3 class="account-detail-card__title">{{ __('Additional Notes') }}</h3>
                <p class="account-detail-copy">{{ $booking->additional_notes ?: __('No additional notes were added to this booking.') }}</p>
            </section>
        </div>
    </div>
@endsection
