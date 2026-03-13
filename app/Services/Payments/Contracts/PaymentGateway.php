<?php

namespace App\Services\Payments\Contracts;

use App\Models\Order;
use App\Services\Payments\Data\CheckoutRedirect;
use App\Services\Payments\Data\PaymentConfirmation;
use Illuminate\Http\Request;

interface PaymentGateway
{
    public function method(): string;

    public function createCheckout(Order $order, string $successUrl, string $cancelUrl): CheckoutRedirect;

    public function confirmCheckout(Order $order, Request $request): PaymentConfirmation;
}
