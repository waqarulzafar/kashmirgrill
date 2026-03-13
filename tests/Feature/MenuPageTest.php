<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_menu_page_renders_seeded_items_with_detail_links(): void
    {
        $this->seed(DatabaseSeeder::class);

        $menuItem = MenuItem::query()->firstOrFail();
        $response = $this->get(route('menu'));

        $response->assertOk();
        $response->assertSee($menuItem->name);
        $response->assertSee(route('menu.items.show', $menuItem), false);
        $this->assertNotNull($menuItem->slug);
    }
}
