<?php

namespace App\Filament\Resources\WebsitePageResource\Pages;

use App\Filament\Resources\WebsitePageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebsitePages extends ListRecords
{
    protected static string $resource = WebsitePageResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
