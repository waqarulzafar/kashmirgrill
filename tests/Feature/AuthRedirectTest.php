<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_redirects_to_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'password' => 'password',
        ]);

        $this->post(route('login'), [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));
    }

    public function test_regular_user_login_redirects_to_home_page(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'password' => 'password',
        ]);

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('home'));
    }

    public function test_regular_user_is_not_redirected_back_into_admin_after_login(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'password' => 'password',
        ]);

        $this->withSession([
            'url.intended' => route('admin.dashboard'),
        ])->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('home'));
    }

    public function test_authenticated_users_are_redirected_from_guest_pages_by_role(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get(route('login'))
            ->assertRedirect(route('admin.dashboard'));

        $user = User::factory()->create([
            'role' => 'customer',
        ]);

        $this->actingAs($user)
            ->get(route('register'))
            ->assertRedirect(route('home'));
    }
}
