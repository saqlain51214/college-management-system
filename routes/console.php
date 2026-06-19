<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('fees:check-overdue')->dailyAt('01:00');
Schedule::command('queue:prune-failed --hours=72')->dailyAt('01:30');
Schedule::command('queue:prune-batches --hours=72')->dailyAt('02:00');
