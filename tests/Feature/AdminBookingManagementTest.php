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
            'payment_amount' => 75.00,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.bookings.update', $booking), [
                'status' => Booking::STATUS_CONFIRMED,
                'payment_status' => Booking::PAYMENT_STATUS_PAID,
                'payment_amount' => 75.00,
            ])
            ->assertRedirect(route('admin.bookings.show', $booking));

        $booking->refresh();

        $this->assertSame(Booking::STATUS_CONFIRMED, $booking->status);
        $this->assertSame(Booking::PAYMENT_STATUS_PAID, $booking->payment_status);

        Mail::assertSent(BookingStatusUpdatedMail::class, function (BookingStatusUpdatedMail $mail) use ($booking): bool {
            $rendered = $mail->render();

            return $mail->booking->is($booking)
                && $mail->hasTo($booking->email)
                && str_contains($rendered, 'Previous Status')
                && str_contains($rendered, 'Pending')
                && str_contains($rendered, 'Confirmed')
                && str_contains($rendered, 'Previous Payment Status')
                && str_contains($rendered, 'Paid');
        });
    }

    public function test_admin_can_update_booking_details(): void
    {
        Mail::fake();

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $originalSlot = DineInSlot::query()->create([
            'name' => 'Lunch',
            'start_time' => '13:00:00',
            'end_time' => '14:30:00',
            'max_guests' => 18,
            'sort_order' => 5,
            'is_active' => true,
        ]);

        $updatedSlot = DineInSlot::query()->create([
            'name' => 'Late Dinner',
            'start_time' => '21:00:00',
            'end_time' => '22:30:00',
            'max_guests' => 30,
            'sort_order' => 15,
            'is_active' => true,
        ]);

        $booking = Booking::query()->create([
            'full_name' => 'Sara Ali',
            'email' => 'sara@example.com',
            'phone' => '+39 7777777',
            'booking_type' => Booking::TYPE_TABLE,
            'status' => Booking::STATUS_PENDING,
            'date' => now()->addDay()->toDateString(),
            'time' => '13:00:00',
            'dine_in_slot_id' => $originalSlot->id,
            'persons' => 2,
            'payment_method' => Booking::PAYMENT_METHOD_PAY_ON_ARRIVAL,
            'payment_status' => Booking::PAYMENT_STATUS_PENDING,
            'table_preference' => 'Window',
            'selected_menu' => 'Lunch Menu',
            'special_occasion' => null,
            'marketing_opt_in' => false,
            'additional_notes' => 'Original note',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.bookings.update', $booking), [
                'full_name' => 'Sara Malik',
                'email' => 'sara.malik@example.com',
                'phone' => '+39 8888888',
                'booking_type' => Booking::TYPE_EVENT,
                'status' => Booking::STATUS_PENDING,
                'date' => now()->addDays(4)->toDateString(),
                'time' => '21:15',
                'dine_in_slot_id' => $updatedSlot->id,
                'persons' => 16,
                'table_preference' => 'Private area',
                'selected_menu' => 'Celebration Menu',
                'special_occasion' => 'Birthday',
                'payment_method' => Booking::PAYMENT_METHOD_CARD_ON_CONFIRMATION,
                'payment_status' => Booking::PAYMENT_STATUS_PENDING,
                'payment_amount' => 120.00,
                'marketing_opt_in' => '1',
                'additional_notes' => 'Need projector setup',
            ])
            ->assertRedirect(route('admin.bookings.show', $booking));

        $booking->refresh();

        $this->assertSame('Sara Malik', $booking->full_name);
        $this->assertSame('sara.malik@example.com', $booking->email);
        $this->assertSame('+39 8888888', $booking->phone);
        $this->assertSame(Booking::TYPE_EVENT, $booking->booking_type);
        $this->assertSame(now()->addDays(4)->toDateString(), $booking->date?->toDateString());
        $this->assertSame('21:15:00', $booking->time);
        $this->assertSame($updatedSlot->id, $booking->dine_in_slot_id);
        $this->assertSame(16, $booking->persons);
        $this->assertSame('Private area', $booking->table_preference);
        $this->assertSame('Celebration Menu', $booking->selected_menu);
        $this->assertSame('Birthday', $booking->special_occasion);
        $this->assertSame(Booking::PAYMENT_METHOD_CARD_ON_CONFIRMATION, $booking->payment_method);
        $this->assertSame('120.00', (string) $booking->payment_amount);
        $this->assertTrue($booking->marketing_opt_in);
        $this->assertSame('Need projector setup', $booking->additional_notes);

        Mail::assertNothingSent();
    }

    public function test_admin_can_delete_booking(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $booking = Booking::query()->create([
            'full_name' => 'Delete Me',
            'email' => 'delete@example.com',
            'phone' => '+39 9999999',
            'booking_type' => Booking::TYPE_TABLE,
            'status' => Booking::STATUS_PENDING,
            'date' => now()->addDay()->toDateString(),
            'time' => '19:30:00',
            'persons' => 3,
            'payment_method' => Booking::PAYMENT_METHOD_PAY_ON_ARRIVAL,
            'payment_status' => Booking::PAYMENT_STATUS_PENDING,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.bookings.destroy', $booking))
            ->assertRedirect(route('admin.bookings.index'));

        $this->assertDatabaseMissing('bookings', [
            'id' => $booking->id,
        ]);
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
