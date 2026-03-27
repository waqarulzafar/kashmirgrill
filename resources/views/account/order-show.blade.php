@extends('account.layout')

@section('title', __('Order Details | Kashmir Grill House'))
@section('account_heading', __('Order Details'))
@section('account_intro', __('See the full details for this order, including status, payment information, fulfillment details, and every item in the basket.'))

@section('account_content')
    @php
        $statusClass = match ($order->status) {
            \App\Models\Order::STATUS_COMPLETED => 'is-success',
            \App\Models\Order::STATUS_CANCELLED, \App\Models\Order::STATUS_PAYMENT_FAILED => 'is-danger',
            default => 'is-warn',
        };
        $paymentClass = match ($order->payment_status) {
            \App\Models\Order::PAYMENT_STATUS_PAID => 'is-success',
            \App\Models\Order::PAYMENT_STATUS_FAILED, \App\Models\Order::PAYMENT_STATUS_CANCELLED => 'is-danger',
            default => 'is-warn',
        };
    @endphp

    <div class="account-toolbar">
        <a href="{{ route('account.orders') }}" class="account-toolbar__back">{{ __('Back to Order History') }}</a>
    </div>

    <div class="account-detail-grid">
        <div class="account-detail-stack">
            <section class="account-panel">
                <div class="account-panel__head">
                    <div>
                        <p class="account-panel__kicker mb-1">{{ __('Order') }}</p>
                        <h2 class="account-panel__title mb-0">{{ $order->reference }}</h2>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="account-badge {{ $statusClass }}">{{ $statusLabels[$order->status] ?? ucfirst((string) $order->status) }}</span>
                        <span class="account-badge {{ $paymentClass }}">{{ $paymentStatusLabels[$order->payment_status] ?? ucfirst((string) $order->payment_status) }}</span>
                    </div>
                </div>

                <div class="account-detail-meta">
                    <div>
                        <span class="account-detail-label">{{ __('Fulfillment') }}</span>
                        <div class="account-detail-value">{{ $fulfillmentLabels[$order->fulfillment_type] ?? ucfirst((string) $order->fulfillment_type) }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">{{ __('Placed') }}</span>
                        <div class="account-detail-value">{{ $order->placed_at?->translatedFormat('M j, Y g:i A') ?? __('Not available') }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">{{ __('Customer Name') }}</span>
                        <div class="account-detail-value">{{ $order->customer_name }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">{{ __('Phone') }}</span>
                        <div class="account-detail-value">{{ $order->customer_phone }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">{{ __('Email') }}</span>
                        <div class="account-detail-value">{{ $order->customer_email }}</div>
                    </div>
                    <div>
                        <span class="account-detail-label">{{ __('Payment Method') }}</span>
                        <div class="account-detail-value">{{ $paymentMethodLabels[$order->payment_method] ?? __('Not set') }}</div>
                    </div>
                </div>
            </section>

            <section class="account-panel">
                <div class="account-panel__head">
                    <div>
                        <p class="account-panel__kicker mb-1">{{ __('Items') }}</p>
                        <h2 class="account-panel__title mb-0">{{ __('Order Basket') }}</h2>
                    </div>
                </div>

                <div class="account-line-items">
                    @foreach($order->items as $item)
                        <article class="account-line-item">
                            <div class="account-line-item__head">
                                <div>
                                    <div class="account-line-item__name">{{ $item->item_name }}</div>
                                    @if($item->menuItem)
                                        <p class="account-detail-copy">{{ __('Menu item: :name', ['name' => $item->menuItem->name]) }}</p>
                                    @endif
                                </div>
                                <strong class="account-detail-value">EUR {{ number_format((float) $item->line_total, 2) }}</strong>
                            </div>

                            <div class="account-line-item__meta">
                                <div>
                                    <span class="account-detail-label">{{ __('Quantity') }}</span>
                                    <div class="account-detail-value">{{ $item->quantity }}</div>
                                </div>
                                <div>
                                    <span class="account-detail-label">{{ __('Unit Price') }}</span>
                                    <div class="account-detail-value">EUR {{ number_format((float) $item->unit_price, 2) }}</div>
                                </div>
                                <div>
                                    <span class="account-detail-label">{{ __('Line Total') }}</span>
                                    <div class="account-detail-value">EUR {{ number_format((float) $item->line_total, 2) }}</div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        </div>

        <div class="account-detail-stack">
            <section class="account-detail-card">
                <h3 class="account-detail-card__title">{{ __('Fulfillment Details') }}</h3>

                @if($order->fulfillment_type === \App\Models\Order::FULFILLMENT_DELIVERY)
                    <div class="account-detail-card__row">
                        <span class="account-detail-label">{{ __('Delivery Address') }}</span>
                        <div class="account-detail-value">{{ $order->delivery_address ?: __('No delivery address provided.') }}</div>
                    </div>
                @elseif($order->fulfillment_type === \App\Models\Order::FULFILLMENT_DINE_IN)
                    <div class="account-detail-card__row mb-3">
                        <span class="account-detail-label">{{ __('Reservation Date') }}</span>
                        <div class="account-detail-value">
                            {{ $order->reservation_date?->translatedFormat('M j, Y') ?? __('Not available') }}
                            @if($order->reservation_time)
                                {{ __('at') }} {{ \Illuminate\Support\Carbon::parse($order->reservation_time)->translatedFormat('g:i A') }}
                            @endif
                        </div>
                    </div>
                    <div class="account-detail-card__row mb-3">
                        <span class="account-detail-label">{{ __('Guests') }}</span>
                        <div class="account-detail-value">{{ $order->guest_count ?: '0' }}</div>
                    </div>
                    <div class="account-detail-card__row">
                        <span class="account-detail-label">{{ __('Selected Slot') }}</span>
                        <div class="account-detail-value">{{ $order->dineInSlot?->name ?: __('No slot selected') }}</div>
                    </div>
                @else
                    <div class="account-detail-card__row">
                        <span class="account-detail-label">{{ __('Collection') }}</span>
                        <div class="account-detail-value">{{ __('This order is set for takeaway collection.') }}</div>
                    </div>
                @endif
            </section>

            <section class="account-detail-card">
                <h3 class="account-detail-card__title">{{ __('Payment Summary') }}</h3>
                <div class="account-detail-card__row mb-3">
                    <span class="account-detail-label">{{ __('Payment Status') }}</span>
                    <div class="account-detail-value">{{ $paymentStatusLabels[$order->payment_status] ?? ucfirst((string) $order->payment_status) }}</div>
                </div>
                <div class="account-detail-card__row mb-3">
                    <span class="account-detail-label">{{ __('Paid At') }}</span>
                    <div class="account-detail-value">{{ $order->paid_at?->translatedFormat('M j, Y g:i A') ?? __('Not paid yet') }}</div>
                </div>
                <div class="account-detail-card__row">
                    <span class="account-detail-label">{{ __('Reference') }}</span>
                    <div class="account-detail-value">{{ $order->payment_reference ?: ($order->payment_session_id ?: __('Not available')) }}</div>
                </div>
            </section>

            <section class="account-detail-card">
                <h3 class="account-detail-card__title">{{ __('Totals') }}</h3>
                <div class="account-detail-card__row mb-3">
                    <span class="account-detail-label">{{ __('Subtotal') }}</span>
                    <div class="account-detail-value">EUR {{ number_format((float) $order->subtotal, 2) }}</div>
                </div>
                <div class="account-detail-card__row mb-3">
                    <span class="account-detail-label">{{ __('Delivery Fee') }}</span>
                    <div class="account-detail-value">EUR {{ number_format((float) $order->delivery_fee, 2) }}</div>
                </div>
                <div class="account-detail-card__row">
                    <span class="account-detail-label">{{ __('Total') }}</span>
                    <div class="account-detail-value">EUR {{ number_format((float) $order->total, 2) }}</div>
                </div>
            </section>

            <section class="account-detail-card">
                <h3 class="account-detail-card__title">{{ __('Notes') }}</h3>
                <p class="account-detail-copy">{{ $order->notes ?: __('No notes were added to this order.') }}</p>
            </section>
        </div>
    </div>
@endsection
