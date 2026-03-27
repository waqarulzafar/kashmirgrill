<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\User;
use App\Support\EventCatalog;
use App\Support\LocalizationManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class LocalizationFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_root_redirects_to_the_saved_default_locale(): void
    {
        app(LocalizationManager::class)->setDefaultLocale('fr');

        $this->get('/')
            ->assertRedirect(route('home', ['locale' => 'fr']));
    }

    public function test_localized_home_page_uses_the_requested_locale(): void
    {
        $italianTranslations = json_decode((string) file_get_contents(lang_path('it.json')), true);

        $this->get(route('home', ['locale' => 'it']))
            ->assertOk()
            ->assertSee('lang="it"', false)
            ->assertSee($italianTranslations['Book a Table'] ?? 'Prenota un tavolo', false);
    }

    public function test_locale_switch_route_persists_the_selected_locale_in_session(): void
    {
        $this->get(route('locale.switch', ['locale' => 'de', 'redirect' => '/fr']))
            ->assertRedirect('/fr')
            ->assertSessionHas('locale', 'de');
    }

    public function test_localized_event_detail_page_renders_for_a_slug(): void
    {
        $event = EventCatalog::all()[0];

        $this->get(route('events.show', ['locale' => 'en', 'slug' => $event['slug']]))
            ->assertOk()
            ->assertSee($event['name']);
    }

    public function test_admin_can_update_the_default_locale_setting(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.localization.update'), [
                'default_locale' => 'it',
            ])
            ->assertRedirect(route('admin.localization.edit'));

        $this->assertDatabaseHas('site_settings', [
            'key' => SiteSetting::KEY_DEFAULT_LOCALE,
            'value' => 'it',
        ]);
    }
}
