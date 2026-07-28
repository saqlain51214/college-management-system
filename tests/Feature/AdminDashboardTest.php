<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Smoke test for the admin dashboard. Filament v3 lazy-loads every widget
 * (via Livewire's x-intersect), so the initial /admin HTML response never
 * contains a widget's real content — only a placeholder div — which is why
 * this asserts the page itself loads, then renders each widget directly
 * (Livewire::test() always fully mounts a component, bypassing the
 * lazy-load optimization) to confirm no widget throws.
 */
class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('super_admin');
    }

    public function test_dashboard_page_loads_successfully(): void
    {
        $this->actingAs($this->admin)->get('/admin')->assertOk();
    }

    public function test_quick_links_widget_renders_its_links(): void
    {
        Livewire::actingAs($this->admin)
            ->test(\App\Filament\Widgets\QuickLinksWidget::class)
            ->assertSee('Quick Links')
            ->assertSee('Add Student')
            ->assertSee('Student Ledger');
    }

    public function test_action_center_widget_renders_without_error(): void
    {
        Livewire::actingAs($this->admin)
            ->test(\App\Filament\Widgets\ActionCenterWidget::class)
            ->assertSee('Needs Your Attention');
    }
}
