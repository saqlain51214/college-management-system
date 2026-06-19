<?php

namespace App\Filament\Resources\WebsitePageResource\Pages;

use App\Filament\Resources\WebsitePageResource;
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
                ->url(fn (): string => $this->record->previewUrl())
                ->openUrlInNewTab(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
