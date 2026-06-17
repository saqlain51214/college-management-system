<?php

namespace App\Enums;

enum AdmissionTypeEnum: string
{
    case Regular      = 'regular';
    case SelfFinance  = 'self_finance';
    case Scholarship  = 'scholarship';
    case LateralEntry = 'lateral_entry';
    case Transfer     = 'transfer';

    public function label(): string
    {
        return match($this) {
            self::Regular      => 'Regular',
            self::SelfFinance  => 'Self Finance',
            self::Scholarship  => 'Scholarship',
            self::LateralEntry => 'Lateral Entry',
            self::Transfer     => 'Transfer',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Regular      => 'primary',
            self::SelfFinance  => 'info',
            self::Scholarship  => 'success',
            self::LateralEntry => 'warning',
            self::Transfer     => 'gray',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($e) => [$e->value => $e->label()])->all();
    }
}
