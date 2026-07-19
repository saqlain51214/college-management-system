<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

/**
 * Startup task for hosted deploys (Railway, etc.):
 *   1. Run any pending migrations (safe/idempotent).
 *   2. Seed the initial data ONLY when the database is fresh (no users),
 *      so admin edits are never overwritten on later deploys.
 *   3. Link public storage.
 */
class DeployInit extends Command
{
    protected $signature = 'app:deploy';

    protected $description = 'Migrate, seed a fresh database once, and link storage for hosted deploys.';

    public function handle(): int
    {
        $this->info('▸ Running migrations...');
        Artisan::call('migrate', ['--force' => true], $this->output);

        $fresh = ! Schema::hasTable('users') || User::query()->count() === 0;

        if ($fresh) {
            $this->info('▸ Fresh database — seeding initial data (super admin + public content)...');
            Artisan::call('db:seed', [
                '--class' => \Database\Seeders\ProductionSeeder::class,
                '--force' => true,
            ], $this->output);
        } else {
            $this->info('▸ Existing data found — refreshing permissions & roles...');
            // Idempotent: keeps Shield permissions/roles in sync on existing installs
            // (e.g. after new resources are added) without touching other data.
            Artisan::call('db:seed', [
                '--class' => \Database\Seeders\ShieldSeeder::class,
                '--force' => true,
            ], $this->output);
        }

        try {
            Artisan::call('storage:link');
        } catch (\Throwable) {
            // symlink may already exist — ignore
        }

        return self::SUCCESS;
    }
}
