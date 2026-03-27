@extends('account.layout')

@section('title', __('Order History | Kashmir Grill House'))
@section('account_heading', __('Order History'))
@section('account_intro', __('Review your takeaway, delivery, and dine-in orders without the clutter of a long single-page dashboard.'))

@section('account_content')
    <section class="account-panel">
        <div class="account-panel__head">
            <div>
                <p class="account-panel__kicker mb-1">{{ __('Orders') }}</p>
                <h2 class="account-panel__title mb-0">{{ __('All Orders') }}</h2>
            </div>
        </div>

        @forelse($orders as $order)
            @php
                $statusClass = match ($order->status) {
                    \App\Models\Order::STATUS_COMPLETED => 'is-success',
                    \App\Models\Order::STATUS_CANCELLED, \App\Models\Order::STATUS_PAYMENT_FAILED => 'is-danger',
                    default => 'is-warn',
                };
            @endphp
            <article class="account-history-card">
                <div class="account-history-card__head">
                    <div>
                        <strong>{{ $order->reference }}</strong>
                        <p class="mb-0">{{ $orderFulfillmentLabels[$order->fulfillment_type] ?? ucfirst((string) $order->fulfillment_type) }}</p>
                    </div>
                    <span class="account-badge {{ $statusClass }}">{{ $orderStatusLabels[$order->status] ?? ucfirst((string) $order->status) }}</span>
                </div>
                <div class="account-history-grid">
                    <div>
                        <span class="account-history-grid__label">{{ __('Placed') }}</span>
                        <strong>{{ $order->placed_at?->translatedFormat('M j, Y g:i A') ?? __('Not available') }}</strong>
                    </div>
                    <div>
                        <span class="account-history-grid__label">{{ __('Amount') }}</span>
                        <strong>EUR {{ number_format((float) $order->total, 2) }}</strong>
                    </div>
                    <div>
                        <span class="account-history-grid__label">{{ __('Items') }}</span>
                        <strong>{{ $order->items_count }}</strong>
                    </div>
                    <div>
                        <span class="account-history-grid__label">{{ __('Reservation / Delivery') }}</span>
                        <strong>{{ $order->delivery_address ?: ($order->reservation_date?->translatedFormat('M j, Y') ?? __('Standard order')) }}</strong>
                    </div>
                </div>
                <div class="account-history-actions">
                    <a href="{{ route('account.orders.show', $order) }}" class="account-history-action">{{ __('View Details') }}</a>
                </div>
            </article>
        @empty
            <p class="account-empty mb-0">{{ __('No orders yet. Once you place an order, it will appear here.') }}</p>
        @endforelse

        @if($orders->hasPages())
            <div class="account-pager">
                @if($orders->onFirstPage())
                    <span class="account-pager__item is-disabled">{{ __('Previous') }}</span>
                @else
                    <a href="{{ $orders->previousPageUrl() }}" class="account-pager__item">{{ __('Previous') }}</a>
                @endif
                <span class="account-pager__meta">{{ __('Page :current of :last', ['current' => $orders->currentPage(), 'last' => $orders->lastPage()]) }}</span>
                @if($orders->hasMorePages())
                    <a href="{{ $orders->nextPageUrl() }}" class="account-pager__item">{{ __('Next') }}</a>
                @else
                    <span class="account-pager__item is-disabled">{{ __('Next') }}</span>
                @endif
            </div>
        @endif
    </section>
@endsection
