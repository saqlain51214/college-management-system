<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
    }

    public function test_admin_panel_redirects_unauthenticated_users_to_login(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirectContains('login');
    }

    public function test_super_admin_role_grants_panel_access_permission(): void
    {
        $user = $this->createSuperAdmin();

        // canAccessPanel() calls hasRole('super_admin') — test the logic directly
        // rather than via the full HTTP stack (which requires Shield gates to be
        // generated for the test DB, adding significant test-setup overhead).
        $panel = app(\Filament\Panel::class);

        $this->assertTrue($user->canAccessPanel($panel));
    }

    public function test_user_without_role_is_denied_panel_access(): void
    {
        $user = $this->createUser();

        $panel = app(\Filament\Panel::class);

        $this->assertFalse($user->canAccessPanel($panel));
    }

    public function test_login_page_does_not_support_direct_post(): void
    {
        // Filament login is Livewire-driven; POST to /admin/login is not a registered route.
        // This is expected Filament behaviour — Livewire submits to /livewire/update, not directly here.
        $response = $this->post('/admin/login', [
            'email'    => 'admin@test.com',
            'password' => 'password',
        ]);

        $response->assertStatus(405);
    }
}
