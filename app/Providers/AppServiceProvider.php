<?php

namespace App\Providers;

use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        if (config('platform.security.force_https', false)) {
            URL::forceScheme('https');
        }

        RateLimiter::for('public-contact', function (Request $request): Limit {
            return Limit::perMinute((int) config('platform.rate_limits.contact_per_minute', 5))
                ->by($request->ip() . '|' . mb_strtolower((string) $request->input('email', 'guest')));
        });

        RateLimiter::for('public-admissions', function (Request $request): Limit {
            return Limit::perMinute((int) config('platform.rate_limits.admissions_per_minute', 40))
                ->by($request->ip() . '|' . mb_strtolower((string) $request->input('email', 'guest')));
        });

        RateLimiter::for('student-login', function (Request $request): Limit {
            return Limit::perMinute((int) config('platform.rate_limits.student_login_per_minute', 5))
                ->by($request->ip() . '|' . mb_strtolower(trim((string) $request->input('roll_number', 'guest'))));
        });

        RateLimiter::for('teacher-login', function (Request $request): Limit {
            return Limit::perMinute((int) config('platform.rate_limits.teacher_login_per_minute', 5))
                ->by($request->ip() . '|' . mb_strtolower(trim((string) $request->input('login', 'guest'))));
        });

        // All table row actions → icon-only with label as tooltip on hover
        EditAction::configureUsing(fn($a)        => $a->iconButton());
        DeleteAction::configureUsing(fn($a)      => $a->iconButton());
        ForceDeleteAction::configureUsing(fn($a) => $a->iconButton());
        RestoreAction::configureUsing(fn($a)     => $a->iconButton());
    }
}
