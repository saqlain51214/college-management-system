<?php

namespace App\Filament\Resources\FeeRefundResource\Pages;

use App\Filament\Resources\FeeRefundResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFeeRefund extends EditRecord
{
    protected static string $resource = FeeRefundResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}
