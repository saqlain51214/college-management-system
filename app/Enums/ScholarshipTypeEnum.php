<?php

namespace App\Enums;

enum ScholarshipTypeEnum: string
{
    case Merit       = 'merit';
    case NeedBased   = 'need_based';
    case Sports      = 'sports';
    case Disability  = 'disability';
    case Government  = 'government';
    case Institutional = 'institutional';
    case Other       = 'other';

    public function label(): string
    {
        return match($this) {
            self::Merit        => 'Merit-Based',
            self::NeedBased    => 'Need-Based',
            self::Sports       => 'Sports',
            self::Disability   => 'Disability',
            self::Government   => 'Government (HEC/PM)',
            self::Institutional => 'Institutional',
            self::Other        => 'Other',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Merit        => 'success',
            self::NeedBased    => 'warning',
            self::Sports       => 'info',
            self::Disability   => 'gray',
            self::Government   => 'primary',
            self::Institutional => 'info',
            self::Other        => 'gray',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($e) => [$e->value => $e->label()])->all();
    }
}
