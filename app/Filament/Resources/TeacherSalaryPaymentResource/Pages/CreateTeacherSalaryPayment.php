<?php

namespace App\Filament\Resources\TeacherSalaryPaymentResource\Pages;

use App\Filament\Resources\TeacherSalaryPaymentResource;
use Illuminate\Support\Str;
use Filament\Resources\Pages\CreateRecord;

class CreateTeacherSalaryPayment extends CreateRecord
{
    protected static string $resource = TeacherSalaryPaymentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['reference_no'] = 'SAL-' . strtoupper(Str::random(8));

        return $data;
    }

    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}
