<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_requires_authentication(): void
    {
        $this->get(route('account.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_dashboard_claims_guest_history_by_email_and_displays_it(): void
    {
        $user = User::factory()->create([
            'name' => 'Areeba Noor',
            'email' => 'areeba@example.com',
        ]);

        $order = Order::query()->create([
            'reference' => 'KGH-20260327-10001',
            'status' => Order::STATUS_PENDING,
            'fulfillment_type' => Order::FULFILLMENT_DELIVERY,
            'customer_name' => 'Areeba Noor',
            'customer_email' => 'areeba@example.com',
            'customer_phone' => '+39 99887766',
            'delivery_address' => 'Via Milano 253, Como',
            'subtotal' => 26.00,
            'delivery_fee' => 3.50,
            'total' => 29.50,
            'payment_method' => Order::PAYMENT_METHOD_STRIPE,
            'payment_provider' => Order::PAYMENT_METHOD_STRIPE,
            'payment_status' => Order::PAYMENT_STATUS_PAID,
            'placed_at' => now()->subDay(),
            'paid_at' => now()->subDay(),
        ]);

        $booking = Booking::query()->create([
            'full_name' => 'Areeba Noor',
            'email' => 'areeba@example.com',
            'phone' => '+39 99887766',
            'booking_type' => Booking::TYPE_TABLE,
            'status' => Booking::STATUS_CONFIRMED,
            'date' => now()->addDay()->toDateString(),
            'time' => '20:00:00',
            'persons' => 4,
            'payment_method' => Booking::PAYMENT_METHOD_PAY_ON_ARRIVAL,
            'payment_status' => Booking::PAYMENT_STATUS_PENDING,
        ]);

        $this->actingAs($user)
            ->get(route('account.dashboard'))
            ->assertOk()
            ->assertSee('My Dashboard')
            ->assertSee('Order History')
            ->assertSee('Booking History');

        $order->refresh();
        $booking->refresh();

        $this->assertSame($user->id, $order->user_id);
        $this->assertSame($user->id, $booking->user_id);

        $this->actingAs($user)
            ->get(route('account.orders'))
            ->assertOk()
            ->assertSee($order->reference);

        $this->actingAs($user)
            ->get(route('account.bookings'))
            ->assertOk()
            ->assertSee($booking->formattedReference());

        $this->actingAs($user)
            ->get(route('account.orders.show', $order))
            ->assertOk()
            ->assertSee($order->reference)
            ->assertSee('Payment Summary');

        $this->actingAs($user)
            ->get(route('account.bookings.show', $booking))
            ->assertOk()
            ->assertSee($booking->formattedReference())
            ->assertSee('Reservation Details');
    }

    public function test_user_can_view_owned_order_and_booking_detail_pages(): void
    {
        $user = User::factory()->create([
            'name' => 'Hina Ahmed',
            'email' => 'hina@example.com',
        ]);

        $order = Order::query()->create([
            'user_id' => $user->id,
            'reference' => 'KGH-20260327-20002',
            'status' => Order::STATUS_CONFIRMED,
            'fulfillment_type' => Order::FULFILLMENT_DELIVERY,
            'customer_name' => 'Hina Ahmed',
            'customer_email' => 'hina@example.com',
            'customer_phone' => '+39 22334455',
            'delivery_address' => 'Via Roma 88, Como',
            'notes' => 'Ring the doorbell once.',
            'subtotal' => 32.00,
            'delivery_fee' => 4.00,
            'total' => 36.00,
            'payment_method' => Order::PAYMENT_METHOD_STRIPE,
            'payment_provider' => Order::PAYMENT_METHOD_STRIPE,
            'payment_status' => Order::PAYMENT_STATUS_PAID,
            'payment_reference' => 'pi_test_123',
            'placed_at' => now()->subHour(),
            'paid_at' => now()->subHour(),
        ]);

        OrderItem::query()->create([
            'order_id' => $order->id,
            'item_name' => 'Chicken Karahi',
            'unit_price' => 16.00,
            'quantity' => 2,
            'line_total' => 32.00,
        ]);

        $booking = Booking::query()->create([
            'user_id' => $user->id,
            'full_name' => 'Hina Ahmed',
            'email' => 'hina@example.com',
            'phone' => '+39 22334455',
            'booking_type' => Booking::TYPE_TABLE,
            'status' => Booking::STATUS_CONFIRMED,
            'date' => now()->addDays(2)->toDateString(),
            'time' => '19:30:00',
            'persons' => 5,
            'table_preference' => 'Window table',
            'selected_menu' => 'Family sharing menu',
            'special_occasion' => 'Birthday dinner',
            'payment_method' => Booking::PAYMENT_METHOD_PAY_ON_ARRIVAL,
            'payment_status' => Booking::PAYMENT_STATUS_PENDING,
            'additional_notes' => 'Need a quiet corner.',
        ]);

        $this->actingAs($user)
            ->get(route('account.orders.show', $order))
            ->assertOk()
            ->assertSee('Chicken Karahi')
            ->assertSee('Via Roma 88, Como')
            ->assertSee('pi_test_123')
            ->assertSee('Ring the doorbell once.');

        $this->actingAs($user)
            ->get(route('account.bookings.show', $booking))
            ->assertOk()
            ->assertSee('Window table')
            ->assertSee('Family sharing menu')
            ->assertSee('Birthday dinner')
            ->assertSee('Need a quiet corner.');
    }

    public function test_user_cannot_view_another_users_order_or_booking_details(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $order = Order::query()->create([
            'user_id' => $otherUser->id,
            'reference' => 'KGH-20260327-30003',
            'status' => Order::STATUS_PENDING,
            'fulfillment_type' => Order::FULFILLMENT_TAKEAWAY,
            'customer_name' => 'Other User',
            'customer_email' => 'other@example.com',
            'customer_phone' => '+39 99887711',
            'subtotal' => 20.00,
            'delivery_fee' => 0,
            'total' => 20.00,
            'payment_method' => Order::PAYMENT_METHOD_STRIPE,
            'payment_provider' => Order::PAYMENT_METHOD_STRIPE,
            'payment_status' => Order::PAYMENT_STATUS_PENDING,
        ]);

        $booking = Booking::query()->create([
            'user_id' => $otherUser->id,
            'full_name' => 'Other User',
            'email' => 'other@example.com',
            'phone' => '+39 99887711',
            'booking_type' => Booking::TYPE_TABLE,
            'status' => Booking::STATUS_PENDING,
            'date' => now()->addDay()->toDateString(),
            'time' => '18:00:00',
            'persons' => 2,
            'payment_method' => Booking::PAYMENT_METHOD_PAY_ON_ARRIVAL,
            'payment_status' => Booking::PAYMENT_STATUS_PENDING,
        ]);

        $this->actingAs($user)
            ->get(route('account.orders.show', $order))
            ->assertNotFound();

        $this->actingAs($user)
            ->get(route('account.bookings.show', $booking))
            ->assertNotFound();
    }

    public function test_user_can_update_profile_and_password(): void
    {
        $user = User::factory()->create([
            'name' => 'Mariam Khan',
            'email' => 'mariam@example.com',
        ]);

        $this->actingAs($user)
            ->get(route('account.profile'))
            ->assertOk()
            ->assertSee('Profile & Security');

        $this->actingAs($user)
            ->put(route('account.profile.update'), [
                'name' => 'Mariam Noor',
                'email' => 'mariam.noor@example.com',
            ])
            ->assertRedirect(route('account.profile'));

        $user->refresh();

        $this->assertSame('Mariam Noor', $user->name);
        $this->assertSame('mariam.noor@example.com', $user->email);

        $this->actingAs($user)
            ->put(route('account.password.update'), [
                'current_password' => 'password',
                'password' => 'newsecure123',
                'password_confirmation' => 'newsecure123',
            ])
            ->assertRedirect(route('account.profile'));

        $user->refresh();

        $this->assertTrue(Hash::check('newsecure123', $user->password));
    }

    public function test_user_can_delete_account(): void
    {
        $user = User::factory()->create([
            'email' => 'delete-me@example.com',
        ]);

        $this->actingAs($user)
            ->delete(route('account.destroy'), [
                'current_password' => 'password',
            ])
            ->assertRedirect(route('home'));

        $this->assertGuest();
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
