<?php

use App\Models\Booking;
use App\Models\Order;

return [
    'currency' => env('CHECKOUT_CURRENCY', 'EUR'),

    'methods' => [
        'orders' => [
            Order::PAYMENT_METHOD_STRIPE => [
                'enabled' => true,
                'label' => 'Stripe (Card)',
            ],
            Order::PAYMENT_METHOD_PAYPAL => [
                'enabled' => env('PAYMENTS_PAYPAL_ENABLED', false),
                'label' => 'PayPal',
            ],
        ],
        'bookings' => [
            Booking::PAYMENT_METHOD_PAY_ON_ARRIVAL => [
                'enabled' => true,
                'label' => 'Pay at Restaurant',
            ],
            Booking::PAYMENT_METHOD_CARD_ON_CONFIRMATION => [
                'enabled' => env('BOOKINGS_CARD_CHECKOUT_ENABLED', false),
                'label' => 'Card Checkout After Confirmation',
            ],
        ],
    ],
];
