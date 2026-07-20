<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Startup task for hosted deploys (Railway, etc.):
 *   1. Run any pending migrations (never aborts the deploy if one fails).
 *   2. Guarantee critical newer tables exist even if migrations are blocked.
 *   3. Seed the initial data ONLY when the database is fresh.
 *   4. Link public storage.
 */
class DeployInit extends Command
{
    protected $signature = 'app:deploy';

    protected $description = 'Migrate, ensure schema, seed a fresh database once, and link storage for hosted deploys.';

    public function handle(): int
    {
        $this->info('▸ Running migrations...');
        try {
            Artisan::call('migrate', ['--force' => true], $this->output);
        } catch (\Throwable $e) {
            // A single failing migration must not block the whole deploy — log and continue.
            $this->error('Migration error (continuing): ' . $e->getMessage());
        }

        // Safety net: ensure newer tables exist even if a migration is stuck.
        $this->ensureSchema();

        $fresh = ! Schema::hasTable('users') || User::query()->count() === 0;

        if ($fresh) {
            $this->info('▸ Fresh database — seeding initial data (super admin + public content)...');
            Artisan::call('db:seed', [
                '--class' => \Database\Seeders\ProductionSeeder::class,
                '--force' => true,
            ], $this->output);
        } else {
            $this->info('▸ Existing data found — refreshing permissions & roles...');
            try {
                Artisan::call('db:seed', [
                    '--class' => \Database\Seeders\ShieldSeeder::class,
                    '--force' => true,
                ], $this->output);
            } catch (\Throwable $e) {
                $this->error('Shield seed error (continuing): ' . $e->getMessage());
            }
        }

        // Seed sample course outlines + fee structures (idempotent: only when empty).
        try {
            Artisan::call('db:seed', [
                '--class' => \Database\Seeders\DemoContentSeeder::class,
                '--force' => true,
            ], $this->output);
        } catch (\Throwable $e) {
            $this->error('Demo content seed error (continuing): ' . $e->getMessage());
        }

        try {
            Artisan::call('storage:link');
        } catch (\Throwable) {
            // symlink may already exist — ignore
        }

        return self::SUCCESS;
    }

    /**
     * Create critical newer tables directly if their migrations did not run,
     * so the Message Desk and Course Outlines features always work.
     */
    private function ensureSchema(): void
    {
        try {
            if (! Schema::hasTable('leadership_messages')) {
                $this->warn('▸ Creating missing table: leadership_messages');
                Schema::create('leadership_messages', function (Blueprint $t) {
                    $t->id();
                    $t->string('name');
                    $t->string('designation');
                    $t->string('organization')->nullable();
                    $t->text('message');
                    $t->string('photo')->nullable();
                    $t->unsignedInteger('sort_order')->default(0);
                    $t->boolean('is_active')->default(true);
                    $t->timestamps();
                });
            }

            if (Schema::hasTable('leadership_messages') && DB::table('leadership_messages')->count() === 0) {
                $principal = DB::table('college_settings')->where('key', 'college_principal')->value('value') ?: 'Arif Ali';
                $now = now();
                DB::table('leadership_messages')->insert([
                    ['name' => 'Vice Chancellor', 'designation' => 'Vice Chancellor', 'organization' => 'Karakoram International University', 'message' => 'It gives me immense pleasure to welcome you to Jinnah Degree College Astore. The college upholds the highest standards of academic excellence and character building, and I am confident our students will emerge as capable, responsible citizens ready to serve the nation.', 'photo' => null, 'sort_order' => 1, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
                    ['name' => 'Directorate of Colleges', 'designation' => 'Director of Colleges', 'organization' => 'Government of Gilgit-Baltistan', 'message' => 'The Government of Gilgit-Baltistan is deeply committed to expanding access to quality higher education across all districts, including the valleys of Astore. Jinnah Degree College Astore brings quality education to the doorstep of every deserving student in the region.', 'photo' => null, 'sort_order' => 2, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
                    ['name' => $principal, 'designation' => 'Principal', 'organization' => 'Jinnah Degree College Astore', 'message' => 'On behalf of the faculty and staff, I warmly welcome you to our institution. We are committed to providing quality education, discipline and a supportive environment that nurtures every student to realise their full potential.', 'photo' => null, 'sort_order' => 3, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
                ]);
            }

            if (! Schema::hasTable('course_outlines')) {
                $this->warn('▸ Creating missing table: course_outlines');
                Schema::create('course_outlines', function (Blueprint $t) {
                    $t->id();
                    $t->unsignedBigInteger('department_id')->index();
                    $t->unsignedBigInteger('academic_program_id')->nullable();
                    $t->unsignedTinyInteger('semester_number')->nullable();
                    $t->string('title');
                    $t->string('file_path')->nullable();
                    $t->string('external_url')->nullable();
                    $t->text('description')->nullable();
                    $t->unsignedInteger('sort_order')->default(0);
                    $t->boolean('is_active')->default(true);
                    $t->timestamps();
                });
            }
        } catch (\Throwable $e) {
            $this->error('ensureSchema error: ' . $e->getMessage());
        }
    }
}
