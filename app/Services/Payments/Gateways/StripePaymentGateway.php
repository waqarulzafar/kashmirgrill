<?php

namespace App\Services\Payments\Gateways;

use App\Models\Order;
use App\Services\Payments\Contracts\PaymentGateway;
use App\Services\Payments\Data\CheckoutRedirect;
use App\Services\Payments\Data\PaymentConfirmation;
use App\Services\Payments\Exceptions\PaymentException;
use Illuminate\Http\Request;
use Stripe\StripeClient;
use Stripe\StripeObject;
use Throwable;

class StripePaymentGateway implements PaymentGateway
{
    public function method(): string
    {
        return Order::PAYMENT_METHOD_STRIPE;
    }

    public function createCheckout(Order $order, string $successUrl, string $cancelUrl): CheckoutRedirect
    {
        $client = $this->client();
        $currency = strtolower((string) config('payments.currency', 'EUR'));

        $lineItems = $order->items
            ->map(fn ($item) => [
                'price_data' => [
                    'currency' => $currency,
                    'unit_amount' => $this->amountToMinor((float) $item->unit_price),
                    'product_data' => [
                        'name' => (string) $item->item_name,
                    ],
                ],
                'quantity' => (int) $item->quantity,
            ])
            ->values()
            ->all();

        if ((float) $order->delivery_fee > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => $currency,
                    'unit_amount' => $this->amountToMinor((float) $order->delivery_fee),
                    'product_data' => [
                        'name' => 'Delivery Fee',
                    ],
                ],
                'quantity' => 1,
            ];
        }

        if (empty($lineItems)) {
            throw new PaymentException('Cannot create Stripe checkout for an empty order.');
        }

        try {
            $session = $client->checkout->sessions->create([
                'mode' => 'payment',
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'payment_method_types' => ['card'],
                'customer_email' => (string) $order->customer_email,
                'line_items' => $lineItems,
                'metadata' => [
                    'order_id' => (string) $order->id,
                    'order_reference' => (string) $order->reference,
                ],
            ]);
        } catch (Throwable $exception) {
            throw new PaymentException('Stripe checkout initialization failed: '.$exception->getMessage(), previous: $exception);
        }

        if (empty($session->url) || empty($session->id)) {
            throw new PaymentException('Stripe checkout session did not return a redirect URL.');
        }

        return new CheckoutRedirect(
            method: $this->method(),
            url: (string) $session->url,
            sessionId: (string) $session->id,
            reference: $this->paymentIntentReference($session->payment_intent ?? null),
            meta: ['session' => $session->toArray()],
        );
    }

    public function confirmCheckout(Order $order, Request $request): PaymentConfirmation
    {
        $sessionId = (string) $request->query('session_id', $order->payment_session_id ?? '');
        if ($sessionId === '') {
            throw new PaymentException('Stripe session id is missing.');
        }

        try {
            $session = $this->client()->checkout->sessions->retrieve(
                $sessionId,
                ['expand' => ['payment_intent']]
            );
        } catch (Throwable $exception) {
            throw new PaymentException('Stripe payment verification failed: '.$exception->getMessage(), previous: $exception);
        }

        $metadataOrderId = (string) ($session->metadata->order_id ?? '');
        if ($metadataOrderId !== '' && $metadataOrderId !== (string) $order->id) {
            throw new PaymentException('Stripe session does not belong to the requested order.');
        }

        if (($session->payment_status ?? null) !== 'paid') {
            return PaymentConfirmation::failed(
                message: 'Stripe reports this payment as unpaid.',
                transactionId: $this->paymentIntentReference($session->payment_intent ?? null),
                sessionId: (string) $session->id,
                payload: ['session' => $session->toArray()],
            );
        }

        return PaymentConfirmation::paid(
            transactionId: $this->paymentIntentReference($session->payment_intent ?? null),
            sessionId: (string) $session->id,
            payload: ['session' => $session->toArray()],
        );
    }

    private function client(): StripeClient
    {
        $secret = (string) config('services.stripe.secret', '');
        if ($secret === '') {
            throw new PaymentException('Stripe secret key is missing. Configure STRIPE_SECRET in .env.');
        }

        return new StripeClient($secret);
    }

    private function amountToMinor(float $amount): int
    {
        return (int) round($amount * 100);
    }

    private function paymentIntentReference(mixed $paymentIntent): ?string
    {
        if (is_string($paymentIntent)) {
            return $paymentIntent !== '' ? $paymentIntent : null;
        }

        if (is_array($paymentIntent)) {
            $paymentIntentId = $paymentIntent['id'] ?? null;

            return is_string($paymentIntentId) && $paymentIntentId !== '' ? $paymentIntentId : null;
        }

        if ($paymentIntent instanceof StripeObject) {
            $paymentIntentId = $paymentIntent->id ?? null;

            return is_string($paymentIntentId) && $paymentIntentId !== '' ? $paymentIntentId : null;
        }

        if (is_object($paymentIntent) && isset($paymentIntent->id) && is_string($paymentIntent->id) && $paymentIntent->id !== '') {
            return $paymentIntent->id;
        }

        return null;
    }
}
