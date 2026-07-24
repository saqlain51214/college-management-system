<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\FeeSlipTemplateGallery;
use Filament\Facades\Filament;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    /**
     * Hide the fee-slip template gallery from the dashboard (managed under
     * Finance instead).
     */
    protected const HIDDEN_WIDGETS = [
        FeeSlipTemplateGallery::class,
    ];

    public function getWidgets(): array
    {
        return array_values(array_filter(
            Filament::getWidgets(),
            // class_exists() guards against stale widget references left over from
            // a partial deploy (e.g. FTP uploads never delete removed files, or a
            // cached component/autoload list still names a class whose file was
            // since deleted) — skip it instead of crashing the whole dashboard.
            fn (string $widget): bool => class_exists($widget) && ! in_array($widget, self::HIDDEN_WIDGETS, true),
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
