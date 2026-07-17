<?php

namespace App\Filament\Resources\FeeSlipTemplateResource\Pages;

use App\Filament\Resources\FeeSlipTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFeeSlipTemplate extends EditRecord
{
    protected static string $resource = FeeSlipTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
