<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\FeeSlipTemplateGallery;
use Filament\Facades\Filament;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    /**
     * Exclude the Fee Slip Templates gallery from the dashboard.
     * It stays available as a widget but no longer clutters the home page.
     */
    public function getWidgets(): array
    {
        return array_values(array_filter(
            Filament::getWidgets(),
            fn (string $widget): bool => $widget !== FeeSlipTemplateGallery::class,
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
