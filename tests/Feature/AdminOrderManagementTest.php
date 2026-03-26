<?php

namespace Tests\Feature;

use App\Mail\OrderPlacedMail;
use App\Mail\OrderStatusUpdatedMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\Payments\Contracts\PaymentGateway;
use App\Services\Payments\Data\CheckoutRedirect;
use App\Services\Payments\Data\PaymentConfirmation;
use App\Services\Payments\PaymentGatewayManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminOrderManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_orders_and_update_status(): void
    {
        Mail::fake();

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $order = Order::query()->create([
            'reference' => 'KGH-20260325-00001',
            'status' => Order::STATUS_PENDING,
            'fulfillment_type' => Order::FULFILLMENT_TAKEAWAY,
            'customer_name' => 'Sara Ali',
            'customer_email' => 'sara@example.com',
            'customer_phone' => '+39 8888888',
            'subtotal' => 36.00,
            'delivery_fee' => 0,
            'total' => 36.00,
            'payment_method' => Order::PAYMENT_METHOD_STRIPE,
            'payment_provider' => Order::PAYMENT_METHOD_STRIPE,
            'payment_status' => Order::PAYMENT_STATUS_PAID,
            'paid_at' => now(),
            'placed_at' => now(),
        ]);

        OrderItem::query()->create([
            'order_id' => $order->id,
            'item_name' => 'Chicken Karahi',
            'unit_price' => 18.00,
            'quantity' => 2,
            'line_total' => 36.00,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.orders.index'))
            ->assertOk()
            ->assertSee($order->reference)
            ->assertSee('Sara Ali');

        $this->actingAs($admin)
            ->patch(route('admin.orders.update', $order), [
                'status' => Order::STATUS_PREPARING,
            ])
            ->assertRedirect(route('admin.orders.show', $order));

        $order->refresh();

        $this->assertSame(Order::STATUS_PREPARING, $order->status);

        Mail::assertSent(OrderStatusUpdatedMail::class, function (OrderStatusUpdatedMail $mail) use ($order): bool {
            return $mail->order->is($order) && $mail->hasTo($order->customer_email);
        });
    }

    public function test_unpaid_order_cannot_move_into_service_workflow(): void
    {
        Mail::fake();

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $order = Order::query()->create([
            'reference' => 'KGH-20260325-00002',
            'status' => Order::STATUS_PENDING_PAYMENT,
            'fulfillment_type' => Order::FULFILLMENT_DELIVERY,
            'customer_name' => 'Omar Shah',
            'customer_email' => 'omar@example.com',
            'customer_phone' => '+39 9999999',
            'delivery_address' => 'Via Roma 18, Como',
            'subtotal' => 24.00,
            'delivery_fee' => 3.50,
            'total' => 27.50,
            'payment_method' => Order::PAYMENT_METHOD_PAYPAL,
            'payment_provider' => Order::PAYMENT_METHOD_PAYPAL,
            'payment_status' => Order::PAYMENT_STATUS_PENDING,
            'placed_at' => now(),
        ]);

        $this->actingAs($admin)
            ->from(route('admin.orders.show', $order))
            ->patch(route('admin.orders.update', $order), [
                'status' => Order::STATUS_CONFIRMED,
            ])
            ->assertRedirect(route('admin.orders.show', $order))
            ->assertSessionHasErrors('status');

        Mail::assertNothingSent();
    }

    public function test_checkout_success_marks_order_paid_and_sends_confirmation_email(): void
    {
        Mail::fake();

        $gateway = new class implements PaymentGateway
        {
            public function method(): string
            {
                return Order::PAYMENT_METHOD_STRIPE;
            }

            public function createCheckout(Order $order, string $successUrl, string $cancelUrl): CheckoutRedirect
            {
                return new CheckoutRedirect(
                    method: Order::PAYMENT_METHOD_STRIPE,
                    url: 'https://example.com/checkout',
                    sessionId: 'sess_test',
                );
            }

            public function confirmCheckout(Order $order, Request $request): PaymentConfirmation
            {
                return PaymentConfirmation::paid('pi_test', (string) $request->query('session_id', 'sess_test'));
            }
        };

        $manager = $this->createMock(PaymentGatewayManager::class);
        $manager->method('for')
            ->with(Order::PAYMENT_METHOD_STRIPE)
            ->willReturn($gateway);

        $this->app->instance(PaymentGatewayManager::class, $manager);

        $order = Order::query()->create([
            'reference' => 'KGH-20260325-00003',
            'status' => Order::STATUS_PENDING_PAYMENT,
            'fulfillment_type' => Order::FULFILLMENT_TAKEAWAY,
            'customer_name' => 'Noor Fatima',
            'customer_email' => 'noor@example.com',
            'customer_phone' => '+39 7777777',
            'subtotal' => 31.00,
            'delivery_fee' => 0,
            'total' => 31.00,
            'payment_method' => Order::PAYMENT_METHOD_STRIPE,
            'payment_provider' => Order::PAYMENT_METHOD_STRIPE,
            'payment_status' => Order::PAYMENT_STATUS_PENDING,
            'payment_session_id' => 'sess_test',
            'placed_at' => now(),
        ]);

        OrderItem::query()->create([
            'order_id' => $order->id,
            'item_name' => 'Mutton Biryani',
            'unit_price' => 15.50,
            'quantity' => 2,
            'line_total' => 31.00,
        ]);

        $this->get(route('checkout.payment.stripe.success', $order).'?session_id=sess_test')
            ->assertRedirect(route('checkout.success'));

        $order->refresh();

        $this->assertSame(Order::STATUS_PENDING, $order->status);
        $this->assertSame(Order::PAYMENT_STATUS_PAID, $order->payment_status);

        Mail::assertSent(OrderPlacedMail::class, function (OrderPlacedMail $mail) use ($order): bool {
            return $mail->order->is($order) && $mail->hasTo($order->customer_email);
        });
    }
}
