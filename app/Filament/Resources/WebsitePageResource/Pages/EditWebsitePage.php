<?php

namespace App\Filament\Resources\WebsitePageResource\Pages;

use App\Filament\Pages\AboutUsSections;
use App\Filament\Pages\AcademicsSections;
use App\Filament\Pages\AdmissionSections;
use App\Filament\Pages\HomePageSections;
use App\Filament\Resources\WebsitePageResource;
use App\Models\WebsitePage;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditWebsitePage extends EditRecord
{
    protected static string $resource = WebsitePageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview')
                ->label('Preview Page')
                ->icon('heroicon-o-eye')
                ->url(fn (): string => $this->record->previewUrl(true))
                ->openUrlInNewTab(),
        ];
    }

    /**
     * This edit screen is reached from 5 different places (the 4 Sections
     * pages, plus this resource's own "Other Pages" list) depending on the
     * page's slug — redirect back to wherever it actually belongs, not
     * always to the "Other Pages" list (which most slugs aren't even on).
     */
    protected function getRedirectUrl(): string
    {
        $section = WebsitePage::STATIC_PAGES[$this->record->slug]['section'] ?? 'Other';

        return match ($section) {
            'Home'      => HomePageSections::getUrl(),
            'About Us'  => AboutUsSections::getUrl(),
            'Academics' => AcademicsSections::getUrl(),
            'Admission' => AdmissionSections::getUrl(),
            default     => $this->getResource()::getUrl('index'),
        };
    }
}
