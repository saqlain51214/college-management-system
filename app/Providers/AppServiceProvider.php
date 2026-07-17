<?php

namespace App\Providers;

use App\Models\AcademicProgram;
use App\Models\Announcement;
use App\Models\Course;
use App\Models\Department;
use App\Models\HomeSection;
use App\Models\ListItem;
use App\Models\NewsArticle;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\WebsiteEvent;
use App\Models\WebsitePage;
use App\Observers\ActivityLogObserver;
use App\Observers\AnnouncementNotificationObserver;
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

        foreach ([
            AcademicProgram::class,
            Announcement::class,
            Course::class,
            Department::class,
            HomeSection::class,
            ListItem::class,
            NewsArticle::class,
            Student::class,
            Teacher::class,
            WebsiteEvent::class,
            WebsitePage::class,
        ] as $modelClass) {
            $modelClass::observe(ActivityLogObserver::class);
        }

        Announcement::observe(AnnouncementNotificationObserver::class);

        // All table row actions → icon-only with label as tooltip on hover
        EditAction::configureUsing(fn($a)        => $a->iconButton());
        DeleteAction::configureUsing(fn($a)      => $a->iconButton());
        ForceDeleteAction::configureUsing(fn($a) => $a->iconButton());
        RestoreAction::configureUsing(fn($a)     => $a->iconButton());
    }
}
