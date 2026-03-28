<?php

namespace App\Services\Payments;

use App\Models\Booking;
use App\Services\Payments\Data\CheckoutRedirect;
use App\Services\Payments\Data\PaymentConfirmation;
use App\Services\Payments\Exceptions\PaymentException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Stripe\StripeClient;
use Stripe\StripeObject;
use Throwable;

class BookingStripeCheckoutService
{
    public function createCheckout(Booking $booking, string $successUrl, string $cancelUrl): CheckoutRedirect
    {
        $amount = (float) ($booking->payment_amount ?? 0);
        if ($amount <= 0) {
            throw new PaymentException('Cannot create Stripe checkout for a booking without a payment amount.');
        }

        try {
            $session = $this->client()->checkout->sessions->create([
                'mode' => 'payment',
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'payment_method_types' => ['card'],
                'customer_email' => (string) $booking->email,
                'line_items' => [[
                    'price_data' => [
                        'currency' => strtolower((string) config('payments.currency', 'EUR')),
                        'unit_amount' => $this->amountToMinor($amount),
                        'product_data' => [
                            'name' => 'Reservation Payment - '.$booking->formattedReference(),
                            'description' => sprintf(
                                '%s on %s at %s',
                                $booking->booking_type === Booking::TYPE_EVENT ? 'Whole Restaurant Event' : 'Table Reservation',
                                $booking->date?->format('F j, Y'),
                                Carbon::createFromFormat('H:i:s', $booking->time)->format('g:i A')
                            ),
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'metadata' => [
                    'booking_id' => (string) $booking->id,
                    'booking_reference' => $booking->formattedReference(),
                ],
            ]);
        } catch (Throwable $exception) {
            throw new PaymentException('Stripe checkout initialization failed: '.$exception->getMessage(), previous: $exception);
        }

        if (empty($session->url) || empty($session->id)) {
            throw new PaymentException('Stripe checkout session did not return a redirect URL.');
        }

        return new CheckoutRedirect(
            method: 'stripe',
            url: (string) $session->url,
            sessionId: (string) $session->id,
            reference: $this->paymentIntentReference($session->payment_intent ?? null),
            meta: ['session' => $session->toArray()],
        );
    }

    public function confirmCheckout(Booking $booking, Request $request): PaymentConfirmation
    {
        $sessionId = (string) $request->query('session_id', $booking->payment_session_id ?? '');
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

        $metadataBookingId = (string) ($session->metadata->booking_id ?? '');
        if ($metadataBookingId !== '' && $metadataBookingId !== (string) $booking->id) {
            throw new PaymentException('Stripe session does not belong to the requested booking.');
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
