<?php

use App\Models\ActivityLog;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Runs the queue every minute (piggybacking on the existing `schedule:run` cron
// entry) so mail jobs process in the background instead of blocking web
// requests — needed now that QUEUE_CONNECTION is 'database', not 'sync'.
Schedule::command('queue:work --stop-when-empty --max-time=50')->everyMinute()->withoutOverlapping();

Schedule::command('fees:check-overdue')->dailyAt('01:00');
Schedule::command('fees:send-due-reminders')->dailyAt('08:00');
Schedule::command('scholarships:expire-awards')->dailyAt('01:15');
Schedule::command('queue:prune-failed --hours=72')->dailyAt('01:30');
Schedule::command('queue:prune-batches --hours=72')->dailyAt('02:00');
Schedule::call(function (): void {
    ActivityLog::query()
        ->where('created_at', '<', now()->subDays((int) config('platform.logs.activity_retention_days', 60)))
        ->delete();
})->dailyAt('02:30')->name('activity-logs:prune');
