<?php

namespace Tests\Feature;

use App\Mail\BookingPaymentRequestedMail;
use App\Models\Booking;
use App\Models\User;
use App\Services\Payments\BookingStripeCheckoutService;
use App\Services\Payments\Data\CheckoutRedirect;
use App\Services\Payments\Data\PaymentConfirmation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class BookingPaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_confirming_card_booking_sends_payment_request_email(): void
    {
        Mail::fake();

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $booking = Booking::query()->create([
            'full_name' => 'Amina Noor',
            'email' => 'amina@example.com',
            'phone' => '+39 123123123',
            'booking_type' => Booking::TYPE_TABLE,
            'status' => Booking::STATUS_PENDING,
            'date' => now()->addDay()->toDateString(),
            'time' => '19:00:00',
            'persons' => 4,
            'payment_method' => Booking::PAYMENT_METHOD_PAY_ON_ARRIVAL,
            'payment_status' => Booking::PAYMENT_STATUS_PENDING,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.bookings.update', $booking), [
                'status' => Booking::STATUS_CONFIRMED,
                'payment_method' => Booking::PAYMENT_METHOD_CARD_ON_CONFIRMATION,
                'payment_status' => Booking::PAYMENT_STATUS_PENDING,
                'payment_amount' => 55.00,
            ])
            ->assertRedirect(route('admin.bookings.show', $booking));

        $booking->refresh();

        $this->assertNotNull($booking->payment_token);
        $this->assertSame('55.00', (string) $booking->payment_amount);

        Mail::assertSent(BookingPaymentRequestedMail::class, function (BookingPaymentRequestedMail $mail) use ($booking): bool {
            $html = $mail->render();

            return $mail->booking->is($booking)
                && $mail->hasTo($booking->email)
                && str_contains($html, 'Review Summary and Pay')
                && str_contains($html, route('bookings.payment.show', [
                    'locale' => 'en',
                    'booking' => $booking,
                    'token' => $booking->payment_token,
                ]));
        });
    }

    public function test_guest_can_view_booking_payment_summary(): void
    {
        $booking = $this->payableBooking();

        $this->get(route('bookings.payment.show', [
            'locale' => 'en',
            'booking' => $booking,
            'token' => $booking->payment_token,
        ]))
            ->assertOk()
            ->assertSee($booking->formattedReference())
            ->assertSee('42.50')
            ->assertSee('Continue to Stripe');
    }

    public function test_guest_can_start_booking_stripe_checkout(): void
    {
        $booking = $this->payableBooking();

        $service = $this->mock(BookingStripeCheckoutService::class);
        $service->shouldReceive('createCheckout')
            ->once()
            ->andReturn(new CheckoutRedirect(
                method: 'stripe',
                url: 'https://stripe.test/checkout/session_123',
                sessionId: 'cs_test_123',
                reference: 'pi_test_123',
                meta: ['session' => ['id' => 'cs_test_123']]
            ));

        $this->post(route('bookings.payment.checkout', [
            'locale' => 'en',
            'booking' => $booking,
            'token' => $booking->payment_token,
        ]))
            ->assertRedirect('https://stripe.test/checkout/session_123');

        $booking->refresh();

        $this->assertSame('cs_test_123', $booking->payment_session_id);
        $this->assertSame('pi_test_123', $booking->payment_reference);
    }

    public function test_successful_booking_payment_marks_booking_paid(): void
    {
        $booking = $this->payableBooking([
            'payment_session_id' => 'cs_test_existing',
        ]);

        $service = $this->mock(BookingStripeCheckoutService::class);
        $service->shouldReceive('confirmCheckout')
            ->once()
            ->andReturn(PaymentConfirmation::paid(
                transactionId: 'pi_test_paid',
                sessionId: 'cs_test_paid',
                payload: ['session' => ['id' => 'cs_test_paid']]
            ));

        $this->get(route('bookings.payment.stripe.success', [
            'locale' => 'en',
            'booking' => $booking,
            'token' => $booking->payment_token,
            'session_id' => 'cs_test_paid',
        ]))
            ->assertRedirect(route('bookings.payment.success', [
                'locale' => 'en',
                'booking' => $booking,
                'token' => $booking->payment_token,
            ]));

        $booking->refresh();

        $this->assertSame(Booking::PAYMENT_STATUS_PAID, $booking->payment_status);
        $this->assertSame('pi_test_paid', $booking->payment_reference);
        $this->assertSame('cs_test_paid', $booking->payment_session_id);
        $this->assertNotNull($booking->paid_at);

        $this->get(route('bookings.payment.success', [
            'locale' => 'en',
            'booking' => $booking,
            'token' => $booking->payment_token,
        ]))
            ->assertOk()
            ->assertSee('Payment Complete')
            ->assertSee($booking->formattedReference());
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function payableBooking(array $overrides = []): Booking
    {
        return Booking::query()->create($overrides + [
            'full_name' => 'Guest User',
            'email' => 'guest@example.com',
            'phone' => '+39 5551234',
            'booking_type' => Booking::TYPE_TABLE,
            'status' => Booking::STATUS_CONFIRMED,
            'date' => now()->addDays(2)->toDateString(),
            'time' => '20:00:00',
            'persons' => 3,
            'payment_method' => Booking::PAYMENT_METHOD_CARD_ON_CONFIRMATION,
            'payment_status' => Booking::PAYMENT_STATUS_PENDING,
            'payment_amount' => 42.50,
            'payment_token' => 'token-123-booking-payment',
        ]);
    }
}
