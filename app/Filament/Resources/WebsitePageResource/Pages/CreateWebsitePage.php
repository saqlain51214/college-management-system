<?php

namespace App\Filament\Resources\WebsitePageResource\Pages;

use App\Filament\Resources\WebsitePageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWebsitePage extends CreateRecord
{
    protected static string $resource = WebsitePageResource::class;
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}
