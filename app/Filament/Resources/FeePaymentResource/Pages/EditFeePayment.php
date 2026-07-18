<?php

namespace App\Filament\Resources\FeePaymentResource\Pages;

use App\Enums\FeeTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Filament\Resources\FeePaymentResource;
use App\Services\NotificationService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFeePayment extends EditRecord
{
    protected static string $resource = FeePaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(fn (): bool => ! $this->getRecord()->isLocked()),
            Actions\RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        $record = $this->getRecord();

        if (
            $record->wasChanged('payment_status') &&
            $record->payment_status === PaymentStatusEnum::Paid &&
            $record->student
        ) {
            $feeType = $record->fee_type instanceof FeeTypeEnum
                ? $record->fee_type->label()
                : ($record->fee_type ?? 'Fee');

            app(NotificationService::class)->send($record->student, 'fee_payment_confirmed', [
                'student_name' => $record->student->name,
                'amount'       => number_format((float) ($record->amount_paid ?? $record->amount_due)),
                'fee_type'     => $feeType,
                'payment_date' => now()->format('d M Y'),
            ]);
        }
    }
}
