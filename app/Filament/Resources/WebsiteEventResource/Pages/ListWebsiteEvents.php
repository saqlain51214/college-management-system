<?php

namespace App\Filament\Resources\WebsiteEventResource\Pages;

use App\Filament\Resources\WebsiteEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebsiteEvents extends ListRecords
{
    protected static string $resource = WebsiteEventResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
