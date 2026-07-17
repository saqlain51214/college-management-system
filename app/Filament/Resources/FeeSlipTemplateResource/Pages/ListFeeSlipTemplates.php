<?php

namespace App\Filament\Resources\FeeSlipTemplateResource\Pages;

use App\Filament\Resources\FeeSlipTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFeeSlipTemplates extends ListRecords
{
    protected static string $resource = FeeSlipTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
