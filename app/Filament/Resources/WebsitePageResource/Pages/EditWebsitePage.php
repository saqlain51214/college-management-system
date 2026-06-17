<?php

namespace App\Filament\Resources\WebsitePageResource\Pages;

use App\Filament\Resources\WebsitePageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWebsitePage extends EditRecord
{
    protected static string $resource = WebsitePageResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make(), Actions\RestoreAction::make()]; }
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}
