<?php

namespace App\Filament\Resources\TeacherSalaryPaymentResource\Pages;

use App\Filament\Resources\TeacherSalaryPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeacherSalaryPayment extends EditRecord
{
    protected static string $resource = TeacherSalaryPaymentResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}
