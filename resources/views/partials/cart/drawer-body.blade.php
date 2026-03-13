@php
    $items = collect($cart['items'] ?? []);
    $count = (int) ($cart['count'] ?? 0);
    $subtotal = (float) ($cart['subtotal'] ?? 0);
@endphp

@if($items->isEmpty())
    <div class="floating-cart__empty">
        <p class="mb-2">Your cart is empty.</p>
        <p class="mb-0 text-secondary">Browse menu items and tap <strong>Add to Cart</strong> to begin your checkout.</p>
    </div>
@else
    <div class="floating-cart__items">
        @foreach($items as $item)
            @php
                $menuItemId = (int) ($item['menu_item_id'] ?? 0);
                $quantity = (int) ($item['quantity'] ?? 1);
                $price = (float) ($item['price'] ?? 0);
                $lineTotal = (float) ($item['line_total'] ?? 0);
                $image = $item['image_url'] ?? asset('assets/images/menu/main-course.jpg');
            @endphp
            <article class="floating-cart__item" data-cart-item-id="{{ $menuItemId }}">
                <img src="{{ $image }}" alt="{{ $item['name'] }}" class="floating-cart__item-image" loading="lazy" decoding="async">
                <div class="floating-cart__item-main">
                    <h3 class="floating-cart__item-title">{{ $item['name'] }}</h3>
                    @if(!empty($item['category_name']))
                        <p class="floating-cart__item-category">{{ $item['category_name'] }}</p>
                    @endif
                    <div class="floating-cart__item-meta">
                        <span class="floating-cart__item-price">&euro;{{ number_format($price, 2) }}</span>
                        <span class="floating-cart__item-line">Line: &euro;{{ number_format($lineTotal, 2) }}</span>
                    </div>
                    <div class="floating-cart__qty">
                        <button type="button" data-cart-action="set-qty" data-menu-item-id="{{ $menuItemId }}" data-quantity="{{ $quantity - 1 }}" aria-label="Decrease quantity">-</button>
                        <span>{{ $quantity }}</span>
                        <button type="button" data-cart-action="set-qty" data-menu-item-id="{{ $menuItemId }}" data-quantity="{{ $quantity + 1 }}" aria-label="Increase quantity">+</button>
                        <button type="button" class="floating-cart__remove" data-cart-action="remove" data-menu-item-id="{{ $menuItemId }}">Remove</button>
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    <div class="floating-cart__footer">
        <div class="floating-cart__totals">
            <p class="mb-1">Items: <strong>{{ $count }}</strong></p>
            <p class="mb-0">Subtotal: <strong>&euro;{{ number_format($subtotal, 2) }}</strong></p>
        </div>
        <div class="floating-cart__actions">
            <button type="button" class="btn btn-brand-outline btn-sm" data-cart-action="clear">Clear</button>
            <a href="{{ route('checkout.create') }}" class="btn btn-brand btn-sm">Checkout</a>
        </div>
    </div>
@endif
