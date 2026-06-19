<?php

namespace Tests\Feature\Admin;

use App\Models\AcademicProgram;
use App\Models\ActivityLog;
use App\Models\User;
use App\Support\ActivityLogWriter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_model_create_and_update_are_written_to_activity_logs(): void
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);

        $program = AcademicProgram::factory()->create([
            'name' => 'BS Activity Logging',
            'slug' => 'bs-activity-logging',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'type' => ActivityLog::TYPE_ACTIVITY,
            'level' => ActivityLog::LEVEL_INFO,
            'event' => 'created',
            'actor_type' => 'user',
            'actor_id' => $admin->id,
            'subject_type' => 'academic_program',
            'subject_id' => $program->id,
        ]);

        $program->update([
            'name' => 'BS Activity Logging Updated',
            'slug' => 'bs-activity-logging-updated',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'type' => ActivityLog::TYPE_ACTIVITY,
            'level' => ActivityLog::LEVEL_INFO,
            'event' => 'updated',
            'subject_type' => 'academic_program',
            'subject_id' => $program->id,
        ]);
    }

    public function test_custom_error_log_is_written_to_database(): void
    {
        ActivityLogWriter::error('exception', 'Something went wrong', [
            'class' => 'RuntimeException',
            'line' => 99,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'type' => ActivityLog::TYPE_ERROR,
            'level' => ActivityLog::LEVEL_ERROR,
            'event' => 'exception',
            'message' => 'Something went wrong',
        ]);
    }
}
