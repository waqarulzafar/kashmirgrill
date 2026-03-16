<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_item_quantity_can_be_updated_and_removed_via_post_method_spoofing(): void
    {
        $this->withoutMiddleware();
        $this->seed(DatabaseSeeder::class);

        $menuItem = MenuItem::query()->firstOrFail();

        $addResponse = $this->postJson(route('cart.items.add'), [
            'menu_item_id' => $menuItem->id,
            'quantity' => 1,
        ]);

        $addResponse->assertOk();
        $addResponse->assertJsonPath('cart.count', 1);

        $updateResponse = $this->post(route('cart.items.update', $menuItem->id), [
            '_method' => 'PATCH',
            'quantity' => 3,
        ], [
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $updateResponse->assertOk();
        $updateResponse->assertJsonPath('cart.count', 3);
        $updateResponse->assertJsonPath('cart.items.0.quantity', 3);
        $updateResponse->assertJsonPath('cart.items.0.line_total', number_format((float) $menuItem->price * 3, 2, '.', ''));

        $removeResponse = $this->post(route('cart.items.remove', $menuItem->id), [
            '_method' => 'DELETE',
        ], [
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $removeResponse->assertOk();
        $removeResponse->assertJsonPath('cart.count', 0);
        $removeResponse->assertJsonPath('cart.subtotal', '0.00');
        $removeResponse->assertJsonCount(0, 'cart.items');
    }
}
