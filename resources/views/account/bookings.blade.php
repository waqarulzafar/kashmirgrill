@extends('account.layout')

@section('title', __('Booking History | Kashmir Grill House'))
@section('account_heading', __('Booking History'))
@section('account_intro', __('See every table reservation and event request on its own page with a cleaner browsing experience.'))

@section('account_content')
    <section class="account-panel">
        <div class="account-panel__head">
            <div>
                <p class="account-panel__kicker mb-1">{{ __('Bookings') }}</p>
                <h2 class="account-panel__title mb-0">{{ __('All Reservations') }}</h2>
            </div>
        </div>

        @forelse($bookings as $booking)
            @php
                $bookingClass = match ($booking->status) {
                    \App\Models\Booking::STATUS_CONFIRMED => 'is-success',
                    \App\Models\Booking::STATUS_CANCELLED => 'is-danger',
                    default => 'is-warn',
                };
            @endphp
            <article class="account-history-card">
                <div class="account-history-card__head">
                    <div>
                        <strong>{{ $booking->formattedReference() }}</strong>
                        <p class="mb-0">{{ $bookingTypeLabels[$booking->booking_type] ?? ucfirst((string) $booking->booking_type) }}</p>
                    </div>
                    <span class="account-badge {{ $bookingClass }}">{{ $bookingStatusLabels[$booking->status] ?? ucfirst((string) $booking->status) }}</span>
                </div>
                <div class="account-history-grid">
                    <div>
                        <span class="account-history-grid__label">{{ __('Date') }}</span>
                        <strong>{{ $booking->date?->translatedFormat('M j, Y') ?? __('Not available') }}</strong>
                    </div>
                    <div>
                        <span class="account-history-grid__label">{{ __('Time') }}</span>
                        <strong>{{ \Illuminate\Support\Carbon::parse($booking->time)->translatedFormat('g:i A') }}</strong>
                    </div>
                    <div>
                        <span class="account-history-grid__label">{{ __('Guests') }}</span>
                        <strong>{{ $booking->persons }}</strong>
                    </div>
                    <div>
                        <span class="account-history-grid__label">{{ __('Payment') }}</span>
                        <strong>{{ $bookingPaymentStatusLabels[$booking->payment_status] ?? ucfirst((string) $booking->payment_status) }}</strong>
                    </div>
                </div>
                <div class="account-history-actions">
                    <a href="{{ route('account.bookings.show', $booking) }}" class="account-history-action">{{ __('View Details') }}</a>
                </div>
            </article>
        @empty
            <p class="account-empty mb-0">{{ __('No booking history yet. Your reservations will appear here after submission.') }}</p>
        @endforelse

        @if($bookings->hasPages())
            <div class="account-pager">
                @if($bookings->onFirstPage())
                    <span class="account-pager__item is-disabled">{{ __('Previous') }}</span>
                @else
                    <a href="{{ $bookings->previousPageUrl() }}" class="account-pager__item">{{ __('Previous') }}</a>
                @endif
                <span class="account-pager__meta">{{ __('Page :current of :last', ['current' => $bookings->currentPage(), 'last' => $bookings->lastPage()]) }}</span>
                @if($bookings->hasMorePages())
                    <a href="{{ $bookings->nextPageUrl() }}" class="account-pager__item">{{ __('Next') }}</a>
                @else
                    <span class="account-pager__item is-disabled">{{ __('Next') }}</span>
                @endif
            </div>
        @endif
    </section>
@endsection
