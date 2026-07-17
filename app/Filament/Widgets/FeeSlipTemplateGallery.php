<?php

namespace App\Filament\Widgets;

use App\Models\FeeSlipTemplate;
use Filament\Widgets\Widget;

class FeeSlipTemplateGallery extends Widget
{
    protected static string $view = 'filament.widgets.fee-slip-template-gallery';

    protected int|string|array $columnSpan = 'full';

    protected static bool $isLazy = false;

    protected function getViewData(): array
    {
        return [
            'templates' => FeeSlipTemplate::orderByDesc('is_active')->orderBy('id')->get(),
        ];
    }
}
