<?php

namespace Tests\Feature;

use App\Models\JobPosting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobPostingModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_jobs_page_lists_active_postings_ordered_by_sort_order(): void
    {
        JobPosting::create([
            'title' => 'Office Assistant', 'employment_type' => 'full_time',
            'department' => 'Administration', 'qualification' => 'Intermediate',
            'sort_order' => 1, 'is_active' => true,
        ]);
        JobPosting::create([
            'title' => 'Lecturer — Computer Science', 'employment_type' => 'full_time',
            'department' => 'Department of Computer Science', 'qualification' => 'MS/BS CS',
            'sort_order' => 0, 'is_active' => true,
        ]);
        JobPosting::create([
            'title' => 'Old Closed Role', 'employment_type' => 'contract',
            'department' => 'Administration', 'qualification' => 'N/A',
            'sort_order' => 2, 'is_active' => false,
        ]);

        $response = $this->get(route('jobs'));

        $response->assertOk();
        $response->assertSeeInOrder(['Lecturer — Computer Science', 'Office Assistant']);
        $response->assertDontSee('Old Closed Role');
    }

    public function test_a_posting_past_its_closing_date_is_hidden_from_the_public_page(): void
    {
        JobPosting::create([
            'title' => 'Expired Role', 'employment_type' => 'full_time',
            'department' => 'Administration', 'qualification' => 'N/A',
            'closing_date' => now()->subDay()->toDateString(), 'is_active' => true,
        ]);

        $response = $this->get(route('jobs'));

        $response->assertOk();
        $response->assertDontSee('Expired Role');
    }

    public function test_admin_can_create_a_job_posting_via_filament(): void
    {
        \Spatie\Permission\Models\Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $admin = \App\Models\User::factory()->create();
        $admin->assignRole('super_admin');

        \Livewire\Livewire::actingAs($admin)
            ->test(\App\Filament\Resources\JobPostingResource\Pages\CreateJobPosting::class)
            ->fillForm([
                'title' => 'Lab Technician',
                'employment_type' => 'full_time',
                'department' => 'Science Labs',
                'qualification' => 'BSc with lab experience',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('job_postings', ['title' => 'Lab Technician']);
    }
}
