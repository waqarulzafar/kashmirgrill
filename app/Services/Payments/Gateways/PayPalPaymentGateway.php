<?php

namespace App\Services\Payments\Gateways;

use App\Models\Order;
use App\Services\Payments\Contracts\PaymentGateway;
use App\Services\Payments\Data\CheckoutRedirect;
use App\Services\Payments\Data\PaymentConfirmation;
use App\Services\Payments\Exceptions\PaymentException;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Throwable;

class PayPalPaymentGateway implements PaymentGateway
{
    public function method(): string
    {
        return Order::PAYMENT_METHOD_PAYPAL;
    }

    public function createCheckout(Order $order, string $successUrl, string $cancelUrl): CheckoutRedirect
    {
        $provider = $this->provider();
        $currency = strtoupper((string) config('payments.currency', config('paypal.currency', 'EUR')));

        $items = $order->items
            ->map(fn ($item) => [
                'name' => mb_substr((string) $item->item_name, 0, 127),
                'quantity' => (string) (int) $item->quantity,
                'unit_amount' => [
                    'currency_code' => $currency,
                    'value' => $this->formatAmount((float) $item->unit_price),
                ],
            ])
            ->values()
            ->all();

        $payload = [
            'intent' => 'CAPTURE',
            'application_context' => [
                'return_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'brand_name' => config('app.name', 'Kashmir Grill House'),
                'user_action' => 'PAY_NOW',
                'shipping_preference' => 'NO_SHIPPING',
            ],
            'purchase_units' => [[
                'reference_id' => (string) $order->reference,
                'custom_id' => (string) $order->id,
                'description' => 'Order '.(string) $order->reference,
                'amount' => [
                    'currency_code' => $currency,
                    'value' => $this->formatAmount((float) $order->total),
                    'breakdown' => [
                        'item_total' => [
                            'currency_code' => $currency,
                            'value' => $this->formatAmount((float) $order->subtotal),
                        ],
                        'shipping' => [
                            'currency_code' => $currency,
                            'value' => $this->formatAmount((float) $order->delivery_fee),
                        ],
                    ],
                ],
                'items' => $items,
            ]],
        ];

        try {
            $response = $provider->createOrder($payload);
        } catch (Throwable $exception) {
            throw new PaymentException('PayPal checkout initialization failed: '.$exception->getMessage(), previous: $exception);
        }

        if (($response['status'] ?? null) !== 'CREATED' || empty($response['id'])) {
            throw new PaymentException('PayPal order creation failed.');
        }

        $approvalUrl = collect($response['links'] ?? [])->firstWhere('rel', 'approve')['href'] ?? null;
        if (! $approvalUrl) {
            throw new PaymentException('PayPal approval URL was not returned.');
        }

        return new CheckoutRedirect(
            method: $this->method(),
            url: (string) $approvalUrl,
            sessionId: (string) $response['id'],
            reference: (string) $response['id'],
            meta: ['paypal_order' => $response],
        );
    }

    public function confirmCheckout(Order $order, Request $request): PaymentConfirmation
    {
        $paypalOrderId = (string) $request->query('token', $order->payment_session_id ?? '');
        if ($paypalOrderId === '') {
            throw new PaymentException('PayPal order token is missing.');
        }

        try {
            $provider = $this->provider();
            $response = $provider->capturePaymentOrder($paypalOrderId);
        } catch (Throwable $exception) {
            throw new PaymentException('PayPal payment verification failed: '.$exception->getMessage(), previous: $exception);
        }

        $purchaseUnit = $response['purchase_units'][0] ?? [];
        $capturedCustomId = (string) ($purchaseUnit['payments']['captures'][0]['custom_id'] ?? $purchaseUnit['custom_id'] ?? '');

        if ($capturedCustomId !== '' && $capturedCustomId !== (string) $order->id) {
            throw new PaymentException('PayPal payment does not belong to the requested order.');
        }

        $capture = $purchaseUnit['payments']['captures'][0] ?? [];
        $captureId = (string) ($capture['id'] ?? '');
        $status = (string) ($response['status'] ?? '');

        if ($status !== 'COMPLETED') {
            $message = (string) ($response['message'] ?? 'PayPal reports this payment as incomplete.');

            return PaymentConfirmation::failed(
                message: $message,
                transactionId: $captureId !== '' ? $captureId : null,
                sessionId: $paypalOrderId,
                payload: ['paypal_capture' => $response],
            );
        }

        return PaymentConfirmation::paid(
            transactionId: $captureId !== '' ? $captureId : $paypalOrderId,
            sessionId: $paypalOrderId,
            payload: ['paypal_capture' => $response],
        );
    }

    private function provider(): PayPalClient
    {
        $client = new PayPalClient;
        $config = config('paypal', []);

        if (empty($config)) {
            throw new PaymentException('PayPal configuration is missing. Configure PAYPAL_* values in .env.');
        }

        $client->setApiCredentials($config);
        $client->getAccessToken();

        return $client;
    }

    private function formatAmount(float $amount): string
    {
        return number_format($amount, 2, '.', '');
    }
}
