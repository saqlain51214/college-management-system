<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\StudentLedger;
use App\Filament\Resources\FeePaymentResource;
use App\Filament\Resources\JobApplicationResource;
use App\Filament\Resources\ScholarshipAwardResource;
use App\Filament\Resources\StudentResource;
use App\Filament\Resources\TeacherResource;
use Filament\Widgets\Widget;

/**
 * The handful of destinations an admin reaches for every single day —
 * saves hunting through the sidebar for the same few pages repeatedly.
 */
class QuickLinksWidget extends Widget
{
    protected static string $view = 'filament.widgets.quick-links-widget';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin', 'Developer', 'panel_user']) ?? false;
    }

    /** @return array<int,array<string,string>> */
    public function getLinks(): array
    {
        return [
            ['label' => 'Add Student', 'icon' => 'heroicon-o-user-plus', 'url' => StudentResource::getUrl('create')],
            ['label' => 'Add Teacher', 'icon' => 'heroicon-o-academic-cap', 'url' => TeacherResource::getUrl('create')],
            ['label' => 'Generate Fee Challans', 'icon' => 'heroicon-o-document-plus', 'url' => FeePaymentResource::getUrl('index')],
            ['label' => 'Student Ledger', 'icon' => 'heroicon-o-identification', 'url' => StudentLedger::getUrl()],
            ['label' => 'Scholarship Awards', 'icon' => 'heroicon-o-trophy', 'url' => ScholarshipAwardResource::getUrl('index')],
            ['label' => 'Job Applications', 'icon' => 'heroicon-o-briefcase', 'url' => JobApplicationResource::getUrl('index')],
        ];
    }
}
