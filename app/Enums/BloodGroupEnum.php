<?php

namespace App\Enums;

enum BloodGroupEnum: string
{
    case APositive  = 'A+';
    case ANegative  = 'A-';
    case BPositive  = 'B+';
    case BNegative  = 'B-';
    case ABPositive = 'AB+';
    case ABNegative = 'AB-';
    case OPositive  = 'O+';
    case ONegative  = 'O-';

    public function label(): string
    {
        return $this->value;
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($e) => [$e->value => $e->label()])->all();
    }
}
