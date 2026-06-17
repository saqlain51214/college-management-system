<?php

namespace App\Filament\Resources\WebsiteEventResource\Pages;

use App\Filament\Resources\WebsiteEventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWebsiteEvent extends EditRecord
{
    protected static string $resource = WebsiteEventResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make(), Actions\RestoreAction::make()]; }
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}
