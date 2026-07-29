<?php

namespace Tests\Feature;

use App\Models\CollegeSetting;
use App\Models\HeroSlide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Smoke coverage for the two new Website Management admin surfaces added
 * alongside the 6-template Message Desk picker and the dedicated Hero
 * Slider resource — confirms both load and save without error.
 */
class WebsiteManagementPagesTest extends TestCase
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

    public function test_message_desk_settings_page_loads_and_saves_a_template(): void
    {
        Livewire::actingAs($this->admin)
            ->test(\App\Filament\Pages\MessageDeskSettings::class)
            ->assertSet('template', 'cards')
            ->set('template', 'glass')
            ->call('save');

        $this->assertEquals('glass', CollegeSetting::get('message_desk_layout'));
    }

    public function test_hero_slide_resource_can_be_created_and_listed(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        Livewire::actingAs($this->admin)
            ->test(\App\Filament\Resources\HeroSlideResource\Pages\CreateHeroSlide::class)
            ->fillForm([
                'image' => \Illuminate\Http\UploadedFile::fake()->image('slide.jpg'),
                'title' => 'Welcome to JDCA',
                'description' => 'A great place to learn.',
                'sort_order' => 1,
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('hero_slides', ['title' => 'Welcome to JDCA']);

        Livewire::actingAs($this->admin)
            ->test(\App\Filament\Resources\HeroSlideResource\Pages\ListHeroSlides::class)
            ->assertCanSeeTableRecords(HeroSlide::all());
    }

    public function test_home_page_renders_each_message_desk_template_without_error(): void
    {
        \App\Models\LeadershipMessage::create([
            'name' => 'Test Leader', 'designation' => 'Principal', 'organization' => 'JDCA',
            'message' => 'Welcome message.', 'sort_order' => 1, 'is_active' => true,
        ]);

        foreach (['cards', 'spotlight', 'diploma', 'ribbon', 'glass', 'minimal'] as $template) {
            CollegeSetting::set('message_desk_layout', $template, 'website');
            \Illuminate\Support\Facades\Cache::flush();

            $response = $this->get('/');
            $response->assertOk();
        }
    }
}
