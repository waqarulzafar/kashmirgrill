@extends('account.layout')

@section('title', 'Order History | Kashmir Grill House')
@section('account_heading', 'Order History')
@section('account_intro', 'Review your takeaway, delivery, and dine-in orders without the clutter of a long single-page dashboard.')

@section('account_content')
    <section class="account-panel">
        <div class="account-panel__head">
            <div>
                <p class="account-panel__kicker mb-1">Orders</p>
                <h2 class="account-panel__title mb-0">All Orders</h2>
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
                        <span class="account-history-grid__label">Placed</span>
                        <strong>{{ $order->placed_at?->format('M j, Y g:i A') ?? 'Not available' }}</strong>
                    </div>
                    <div>
                        <span class="account-history-grid__label">Amount</span>
                        <strong>EUR {{ number_format((float) $order->total, 2) }}</strong>
                    </div>
                    <div>
                        <span class="account-history-grid__label">Items</span>
                        <strong>{{ $order->items_count }}</strong>
                    </div>
                    <div>
                        <span class="account-history-grid__label">Reservation / Delivery</span>
                        <strong>{{ $order->delivery_address ?: ($order->reservation_date?->format('M j, Y') ?? 'Standard order') }}</strong>
                    </div>
                </div>
                <div class="account-history-actions">
                    <a href="{{ route('account.orders.show', $order) }}" class="account-history-action">View Details</a>
                </div>
            </article>
        @empty
            <p class="account-empty mb-0">No orders yet. Once you place an order, it will appear here.</p>
        @endforelse

        @if($orders->hasPages())
            <div class="account-pager">
                @if($orders->onFirstPage())
                    <span class="account-pager__item is-disabled">Previous</span>
                @else
                    <a href="{{ $orders->previousPageUrl() }}" class="account-pager__item">Previous</a>
                @endif
                <span class="account-pager__meta">Page {{ $orders->currentPage() }} of {{ $orders->lastPage() }}</span>
                @if($orders->hasMorePages())
                    <a href="{{ $orders->nextPageUrl() }}" class="account-pager__item">Next</a>
                @else
                    <span class="account-pager__item is-disabled">Next</span>
                @endif
            </div>
        @endif
    </section>
@endsection
