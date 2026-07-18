<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AttendanceChart;
use App\Filament\Widgets\ExamPerformanceChart;
use App\Filament\Widgets\FeeSlipTemplateGallery;
use App\Filament\Widgets\LibraryStatsWidget;
use App\Filament\Widgets\TimetableTodayWidget;
use App\Filament\Widgets\TopPerformersWidget;
use Filament\Facades\Filament;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    /**
     * Hide dashboard widgets whose management modules are not present in the
     * panel (attendance, exams, library, timetable) so the dashboard never
     * shows data that staff cannot manage. Also hides the fee-slip gallery.
     */
    protected const HIDDEN_WIDGETS = [
        FeeSlipTemplateGallery::class,
        AttendanceChart::class,
        ExamPerformanceChart::class,
        LibraryStatsWidget::class,
        TimetableTodayWidget::class,
        TopPerformersWidget::class,
    ];

    public function getWidgets(): array
    {
        return array_values(array_filter(
            Filament::getWidgets(),
            fn (string $widget): bool => ! in_array($widget, self::HIDDEN_WIDGETS, true),
        ));
    }

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        $user = Filament::auth()->user();

        if (! $user) {
            return false;
        }

        return $user->hasRole(config('filament-shield.super_admin.name'))
            || $user->getAllPermissions()->isNotEmpty();
    }
}
