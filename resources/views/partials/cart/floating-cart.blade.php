@php
    $cart = $cart ?? ['items' => [], 'count' => 0, 'subtotal' => '0.00'];
@endphp

<div
    class="floating-cart"
    data-floating-cart
    data-cart-update-url-template="{{ route('cart.items.update', ['menuItem' => '__ID__']) }}"
    data-cart-remove-url-template="{{ route('cart.items.remove', ['menuItem' => '__ID__']) }}"
    data-cart-add-url="{{ route('cart.items.add') }}"
    data-cart-clear-url="{{ route('cart.clear') }}"
>
    <button type="button" class="floating-cart__toggle" data-cart-toggle aria-label="Open cart">
        <span class="floating-cart__toggle-icon" aria-hidden="true"><i class="fa-solid fa-cart-shopping"></i></span>
        <span class="floating-cart__toggle-label">Cart</span>
        <span class="floating-cart__toggle-count" data-cart-count>{{ (int) ($cart['count'] ?? 0) }}</span>
    </button>

    <div class="floating-cart__backdrop" data-cart-backdrop hidden></div>

    <aside class="floating-cart__drawer" data-cart-drawer hidden aria-hidden="true">
        <header class="floating-cart__head">
            <div>
                <p class="floating-cart__kicker mb-1">Order Summary</p>
                <h2 class="floating-cart__title mb-0">Your Cart</h2>
            </div>
            <button type="button" class="floating-cart__close" data-cart-close aria-label="Close cart">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </header>

        <div class="floating-cart__body" data-cart-body>
            @include('partials.cart.drawer-body', ['cart' => $cart])
        </div>
    </aside>
</div>
