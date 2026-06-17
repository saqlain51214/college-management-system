<?php

namespace App\Enums;

enum PaymentMethodEnum: string
{
    case Cash           = 'cash';
    case BankDraft      = 'bank_draft';
    case OnlineTransfer = 'online_transfer';
    case Cheque         = 'cheque';
    case JazzCash       = 'jazzcash';
    case EasyPaisa      = 'easypaisa';

    public function label(): string
    {
        return match($this) {
            self::Cash           => 'Cash',
            self::BankDraft      => 'Bank Draft',
            self::OnlineTransfer => 'Online Transfer',
            self::Cheque         => 'Cheque',
            self::JazzCash       => 'JazzCash',
            self::EasyPaisa      => 'EasyPaisa',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($e) => [$e->value => $e->label()])->all();
    }
}
