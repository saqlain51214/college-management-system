<?php

namespace App\Filament\Resources\WebsiteEventResource\Pages;

use App\Filament\Resources\WebsiteEventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWebsiteEvent extends CreateRecord
{
    protected static string $resource = WebsiteEventResource::class;
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}
