<?php

namespace Tests\Feature;

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
}
