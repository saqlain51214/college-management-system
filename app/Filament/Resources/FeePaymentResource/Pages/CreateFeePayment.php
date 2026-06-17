<?php

namespace App\Filament\Resources\FeePaymentResource\Pages;

use App\Filament\Resources\FeePaymentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFeePayment extends CreateRecord
{
    protected static string $resource = FeePaymentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
