<?php

namespace App\Enums;

enum BookStatusEnum: string
{
    case Available = 'available';
    case Issued    = 'issued';
    case Reserved  = 'reserved';
    case Lost      = 'lost';
    case Damaged   = 'damaged';
    case Retired   = 'retired';

    public function label(): string
    {
        return match($this) {
            self::Available => 'Available',
            self::Issued    => 'Issued',
            self::Reserved  => 'Reserved',
            self::Lost      => 'Lost',
            self::Damaged   => 'Damaged',
            self::Retired   => 'Retired',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Available => 'success',
            self::Issued    => 'warning',
            self::Reserved  => 'info',
            self::Lost      => 'danger',
            self::Damaged   => 'danger',
            self::Retired   => 'gray',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($e) => [$e->value => $e->label()])->all();
    }
}
