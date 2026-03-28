<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_hides_paypal_when_disabled(): void
    {
        config()->set('payments.methods.orders.paypal.enabled', false);

        $response = $this->withSession([
            'cart.items' => [
                '1' => [
                    'menu_item_id' => 1,
                    'name' => 'Chicken Karahi',
                    'slug' => 'chicken-karahi',
                    'price' => 18.50,
                    'quantity' => 2,
                    'image_url' => null,
                    'category_name' => 'Main Course',
                ],
            ],
        ])->get(route('checkout.create'));

        $response
            ->assertOk()
            ->assertDontSee('PayPal');
    }

    public function test_checkout_prompts_existing_customer_to_login_without_leaving_checkout(): void
    {
        User::factory()->create([
            'email' => 'waqar@example.com',
        ]);

        $response = $this->withSession([
            'cart.items' => [
                '1' => [
                    'menu_item_id' => 1,
                    'name' => 'Chicken Karahi',
                    'slug' => 'chicken-karahi',
                    'price' => 18.50,
                    'quantity' => 2,
                    'image_url' => null,
                    'category_name' => 'Main Course',
                ],
            ],
        ])->post(route('checkout.store'), [
            'full_name' => 'Waqar Khan',
            'email' => 'waqar@example.com',
            'phone' => '+39 33221144',
            'fulfillment_type' => Order::FULFILLMENT_DELIVERY,
            'delivery_address' => 'Via Milano 12, Como',
            'payment_method' => Order::PAYMENT_METHOD_STRIPE,
        ]);

        $response
            ->assertRedirect(route('checkout.create'))
            ->assertSessionHasErrors(['email'])
            ->assertSessionHas('checkout_login_modal', true)
            ->assertSessionHas('checkout_login_email', 'waqar@example.com');

        $this->assertGuest();
    }

    public function test_checkout_page_reopens_login_modal_with_flashed_details_and_reset_link(): void
    {
        $response = $this->withSession([
            'cart.items' => [
                '1' => [
                    'menu_item_id' => 1,
                    'name' => 'Chicken Karahi',
                    'slug' => 'chicken-karahi',
                    'price' => 18.50,
                    'quantity' => 2,
                    'image_url' => null,
                    'category_name' => 'Main Course',
                ],
            ],
            '_old_input' => [
                'full_name' => 'Waqar Khan',
                'email' => 'waqar@example.com',
                'phone' => '+39 33221144',
                'fulfillment_type' => Order::FULFILLMENT_DELIVERY,
                'delivery_address' => 'Via Milano 12, Como',
                'payment_method' => Order::PAYMENT_METHOD_STRIPE,
            ],
            'checkout_login_modal' => true,
            'checkout_login_email' => 'waqar@example.com',
        ])->get(route('checkout.create'));

        $response
            ->assertOk()
            ->assertSee('data-open-on-load="true"', false)
            ->assertSee('value="Waqar Khan"', false)
            ->assertSee('value="waqar@example.com"', false)
            ->assertSee('Via Milano 12, Como')
            ->assertSee(route('password.request'), false)
            ->assertSee('Forgot your password? Reset it here');
    }

    public function test_customer_can_login_from_checkout_modal_and_return_to_checkout_page(): void
    {
        $user = User::factory()->create([
            'email' => 'waqar@example.com',
            'password' => 'password',
        ]);

        $response = $this->withSession([
            '_old_input' => [
                'full_name' => 'Waqar Khan',
                'email' => 'waqar@example.com',
                'phone' => '+39 33221144',
                'fulfillment_type' => Order::FULFILLMENT_DELIVERY,
                'delivery_address' => 'Via Milano 12, Como',
                'payment_method' => Order::PAYMENT_METHOD_STRIPE,
                'create_account' => '1',
            ],
        ])->post(route('checkout.login'), [
            'email' => 'waqar@example.com',
            'password' => 'password',
        ]);

        $response
            ->assertRedirect(route('checkout.create'))
            ->assertSessionHas('success', 'Signed in successfully. You can continue checkout now.')
            ->assertSessionHas('_old_input.delivery_address', 'Via Milano 12, Como')
            ->assertSessionHas('_old_input.email', 'waqar@example.com');

        $this->assertAuthenticatedAs($user);
    }
}
