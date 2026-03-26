<?php

namespace Tests\Feature;

use App\Mail\BookingStatusUpdatedMail;
use App\Models\Booking;
use App\Models\DineInSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminBookingManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_booking_list_and_details(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $slot = DineInSlot::query()->create([
            'name' => 'Dinner Prime',
            'start_time' => '20:00:00',
            'end_time' => '21:30:00',
            'max_guests' => 24,
            'sort_order' => 10,
            'is_active' => true,
        ]);

        $booking = Booking::query()->create([
            'full_name' => 'Ayesha Khan',
            'email' => 'ayesha@example.com',
            'phone' => '+39 1234567',
            'booking_type' => Booking::TYPE_TABLE,
            'status' => Booking::STATUS_PENDING,
            'date' => now()->addDay()->toDateString(),
            'time' => '20:00:00',
            'dine_in_slot_id' => $slot->id,
            'persons' => 4,
            'payment_method' => Booking::PAYMENT_METHOD_PAY_ON_ARRIVAL,
            'payment_status' => Booking::PAYMENT_STATUS_PENDING,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.bookings.index'))
            ->assertOk()
            ->assertSee($booking->formattedReference())
            ->assertSee('Ayesha Khan');

        $this->actingAs($admin)
            ->get(route('admin.bookings.show', $booking))
            ->assertOk()
            ->assertSee('Dinner Prime')
            ->assertSee('ayesha@example.com');
    }

    public function test_admin_can_confirm_booking_and_notify_customer(): void
    {
        Mail::fake();

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $booking = Booking::query()->create([
            'full_name' => 'Bilal Ahmed',
            'email' => 'bilal@example.com',
            'phone' => '+39 4444444',
            'booking_type' => Booking::TYPE_EVENT,
            'status' => Booking::STATUS_PENDING,
            'date' => now()->addDays(2)->toDateString(),
            'time' => '18:30:00',
            'persons' => 25,
            'payment_method' => Booking::PAYMENT_METHOD_CARD_ON_CONFIRMATION,
            'payment_status' => Booking::PAYMENT_STATUS_PENDING,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.bookings.update', $booking), [
                'status' => Booking::STATUS_CONFIRMED,
                'payment_status' => Booking::PAYMENT_STATUS_PAID,
            ])
            ->assertRedirect(route('admin.bookings.show', $booking));

        $booking->refresh();

        $this->assertSame(Booking::STATUS_CONFIRMED, $booking->status);
        $this->assertSame(Booking::PAYMENT_STATUS_PAID, $booking->payment_status);

        Mail::assertSent(BookingStatusUpdatedMail::class, function (BookingStatusUpdatedMail $mail) use ($booking): bool {
            return $mail->booking->is($booking) && $mail->hasTo($booking->email);
        });
    }

    public function test_non_admin_user_cannot_access_booking_management(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $this->actingAs($customer)
            ->get(route('admin.bookings.index'))
            ->assertForbidden();
    }
}
