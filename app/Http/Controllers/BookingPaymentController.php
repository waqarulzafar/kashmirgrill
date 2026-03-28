<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\Payments\BookingStripeCheckoutService;
use App\Services\Payments\Exceptions\PaymentException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View as ViewContract;

class BookingPaymentController extends Controller
{
    public function show(string $locale, Booking $booking, string $token): ViewContract
    {
        $this->ensureAccessible($booking, $token);
        $booking->loadMissing('dineInSlot');

        return view('pages.booking-payment', $this->viewData($booking, $locale));
    }

    public function checkout(
        Request $request,
        string $locale,
        Booking $booking,
        string $token,
        BookingStripeCheckoutService $stripeCheckout
    ): RedirectResponse {
        $this->ensureAccessible($booking, $token);
        $booking->loadMissing('dineInSlot');

        if ($booking->payment_status === Booking::PAYMENT_STATUS_PAID) {
            return redirect()->route('bookings.payment.success', [
                'locale' => $locale,
                'booking' => $booking,
                'token' => $token,
            ]);
        }

        if (! $booking->canCollectCardPayment()) {
            return redirect()
                ->route('bookings.payment.show', [
                    'locale' => $locale,
                    'booking' => $booking,
                    'token' => $token,
                ])
                ->withErrors(['payment' => __('This booking is not ready for card payment yet.')]);
        }

        try {
            $checkout = $stripeCheckout->createCheckout(
                booking: $booking,
                successUrl: route('bookings.payment.stripe.success', [
                    'locale' => $locale,
                    'booking' => $booking,
                    'token' => $token,
                ]).'?session_id={CHECKOUT_SESSION_ID}',
                cancelUrl: route('bookings.payment.stripe.cancel', [
                    'locale' => $locale,
                    'booking' => $booking,
                    'token' => $token,
                ]),
            );
        } catch (PaymentException $exception) {
            $booking->forceFill([
                'payment_meta' => $this->mergePaymentMeta($booking, [
                    'gateway_init_error' => $exception->getMessage(),
                ]),
            ])->save();

            return redirect()
                ->route('bookings.payment.show', [
                    'locale' => $locale,
                    'booking' => $booking,
                    'token' => $token,
                ])
                ->withErrors(['payment' => __('Unable to start Stripe checkout right now. Please try again.')]);
        }

        $booking->forceFill([
            'payment_session_id' => $checkout->sessionId,
            'payment_reference' => $checkout->reference,
            'payment_meta' => $this->mergePaymentMeta($booking, $checkout->meta),
        ])->save();

        return redirect()->away($checkout->url);
    }

    public function stripeSuccess(
        Request $request,
        string $locale,
        Booking $booking,
        string $token,
        BookingStripeCheckoutService $stripeCheckout
    ): RedirectResponse {
        $this->ensureAccessible($booking, $token);

        if ($booking->payment_status === Booking::PAYMENT_STATUS_PAID) {
            return redirect()->route('bookings.payment.success', [
                'locale' => $locale,
                'booking' => $booking,
                'token' => $token,
            ]);
        }

        try {
            $confirmation = $stripeCheckout->confirmCheckout($booking, $request);
        } catch (PaymentException $exception) {
            $booking->forceFill([
                'payment_meta' => $this->mergePaymentMeta($booking, [
                    'gateway_confirm_error' => $exception->getMessage(),
                ]),
            ])->save();

            return redirect()
                ->route('bookings.payment.show', [
                    'locale' => $locale,
                    'booking' => $booking,
                    'token' => $token,
                ])
                ->withErrors(['payment' => __('Payment verification failed. Please try again.')]);
        }

        if (! $confirmation->successful) {
            $booking->forceFill([
                'payment_session_id' => $confirmation->sessionId ?? $booking->payment_session_id,
                'payment_reference' => $confirmation->transactionId ?? $booking->payment_reference,
                'payment_meta' => $this->mergePaymentMeta($booking, $confirmation->payload),
            ])->save();

            return redirect()
                ->route('bookings.payment.show', [
                    'locale' => $locale,
                    'booking' => $booking,
                    'token' => $token,
                ])
                ->withErrors(['payment' => $confirmation->message ?? __('Payment could not be completed.')]);
        }

        $booking->forceFill([
            'payment_status' => Booking::PAYMENT_STATUS_PAID,
            'payment_session_id' => $confirmation->sessionId ?? $booking->payment_session_id,
            'payment_reference' => $confirmation->transactionId ?? $booking->payment_reference,
            'payment_meta' => $this->mergePaymentMeta($booking, $confirmation->payload),
            'paid_at' => now(),
        ])->save();

        return redirect()->route('bookings.payment.success', [
            'locale' => $locale,
            'booking' => $booking,
            'token' => $token,
        ]);
    }

    public function stripeCancel(string $locale, Booking $booking, string $token): RedirectResponse
    {
        $this->ensureAccessible($booking, $token);

        return redirect()
            ->route('bookings.payment.show', [
                'locale' => $locale,
                'booking' => $booking,
                'token' => $token,
            ])
            ->withErrors(['payment' => __('Payment was cancelled. You can try again when ready.')]);
    }

    public function success(string $locale, Booking $booking, string $token): ViewContract|RedirectResponse
    {
        $this->ensureAccessible($booking, $token);
        $booking->loadMissing('dineInSlot');

        if ($booking->payment_status !== Booking::PAYMENT_STATUS_PAID) {
            return redirect()->route('bookings.payment.show', [
                'locale' => $locale,
                'booking' => $booking,
                'token' => $token,
            ]);
        }

        return view('pages.booking-payment-success', $this->viewData($booking, $locale));
    }

    private function ensureAccessible(Booking $booking, string $token): void
    {
        $validToken = is_string($booking->payment_token) && $booking->payment_token !== ''
            && hash_equals($booking->payment_token, $token);

        abort_unless(
            $validToken && $booking->requiresCardConfirmationPayment(),
            404
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function viewData(Booking $booking, string $locale): array
    {
        return [
            'booking' => $booking,
            'locale' => $locale,
            'statusLabels' => Booking::statusLabels(),
            'paymentStatusLabels' => Booking::paymentStatusLabels(),
            'paymentMethodLabels' => Booking::paymentMethodLabels(),
            'bookingTypeLabels' => Booking::typeLabels(),
            'paymentAmount' => number_format((float) ($booking->payment_amount ?? 0), 2),
        ];
    }

    /**
     * @param  array<string, mixed>  $incoming
     * @return array<string, mixed>
     */
    private function mergePaymentMeta(Booking $booking, array $incoming): array
    {
        $current = is_array($booking->payment_meta) ? $booking->payment_meta : [];

        return array_merge($current, $incoming);
    }
}
