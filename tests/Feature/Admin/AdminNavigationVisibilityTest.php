<?php

namespace Tests\Feature\Admin;

use App\Filament\Pages\Dashboard;
use App\Filament\Pages\Settings;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminNavigationVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_and_settings_navigation_are_hidden_for_user_without_permissions(): void
    {
        $user = User::factory()->create();
        Role::findOrCreate('panel_user', 'web');
        $user->assignRole('panel_user');

        $this->actingAs($user);

        $this->assertFalse(Dashboard::shouldRegisterNavigation());
        $this->assertFalse(Settings::shouldRegisterNavigation());
    }

    public function test_dashboard_and_settings_navigation_are_available_when_permissions_exist(): void
    {
        $user = User::factory()->create();
        Role::findOrCreate('panel_user', 'web');
        $user->assignRole('panel_user');

        Permission::findOrCreate('page_Settings', 'web');
        Permission::findOrCreate('view_any_home::section', 'web');

        $user->givePermissionTo(['page_Settings', 'view_any_home::section']);

        $this->actingAs($user);

        $this->assertTrue(Dashboard::shouldRegisterNavigation());
        $this->assertTrue(Settings::shouldRegisterNavigation());
    }
}
