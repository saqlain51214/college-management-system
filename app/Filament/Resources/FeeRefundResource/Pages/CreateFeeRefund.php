<?php

namespace App\Filament\Resources\FeeRefundResource\Pages;

use App\Filament\Resources\FeeRefundResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFeeRefund extends CreateRecord
{
    protected static string $resource = FeeRefundResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['requested_by'] = auth()->id();
        $data['status']       = 'pending';

        return $data;
    }

    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}
