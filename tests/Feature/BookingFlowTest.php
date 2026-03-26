<?php

namespace Tests\Feature;

use App\Mail\BookingReceivedMail;
use App\Mail\BookingSubmittedMail;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_flow_hides_card_checkout_when_disabled(): void
    {
        config()->set('payments.methods.bookings.'.Booking::PAYMENT_METHOD_CARD_ON_CONFIRMATION.'.enabled', false);

        $this->get(route('book-now'))
            ->assertOk()
            ->assertDontSee('Card Checkout After Confirmation');
    }

    public function test_booking_submission_sends_restaurant_and_customer_confirmation_emails(): void
    {
        Mail::fake();

        config()->set('payments.methods.bookings.'.Booking::PAYMENT_METHOD_CARD_ON_CONFIRMATION.'.enabled', false);

        $response = $this->post(route('bookings.store'), [
            'form_rendered_at' => now()->subSeconds(5)->timestamp,
            'idempotency_key' => (string) Str::uuid(),
            'booking_type' => Booking::TYPE_EVENT,
            'full_name' => 'Fatima Noor',
            'email' => 'fatima@example.com',
            'phone' => '+39 12345678',
            'date' => now()->addDays(3)->toDateString(),
            'persons' => 18,
            'time_filter' => 'all',
            'time' => '19:30',
            'payment_method' => Booking::PAYMENT_METHOD_PAY_ON_ARRIVAL,
            'selected_menu' => 'Event Buffet',
            'special_occasion' => 'Anniversary',
            'additional_notes' => 'Please prepare one birthday dessert plate.',
            'marketing_opt_in' => '1',
        ]);

        $response
            ->assertRedirect(route('bookings.success'))
            ->assertSessionHas('booking_reference');

        Mail::assertSent(BookingSubmittedMail::class, function (BookingSubmittedMail $mail): bool {
            $rendered = $mail->render();

            return $mail->hasTo(config('mail.restaurant_email'))
                && str_contains($rendered, 'Fatima Noor')
                && str_contains($rendered, 'Event Buffet');
        });

        Mail::assertSent(BookingReceivedMail::class, function (BookingReceivedMail $mail): bool {
            $rendered = $mail->render();

            return $mail->hasTo('fatima@example.com')
                && str_contains($rendered, 'Fatima Noor')
                && str_contains($rendered, 'Anniversary')
                && str_contains($rendered, 'Pay at Restaurant');
        });
    }
}
