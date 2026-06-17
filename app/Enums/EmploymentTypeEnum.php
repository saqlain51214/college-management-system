<?php

namespace App\Enums;

enum EmploymentTypeEnum: string
{
    case Permanent   = 'permanent';
    case Contractual = 'contractual';
    case Visiting    = 'visiting';
    case PartTime    = 'part_time';
    case Adjunct     = 'adjunct';

    public function label(): string
    {
        return match($this) {
            self::Permanent   => 'Permanent',
            self::Contractual => 'Contractual',
            self::Visiting    => 'Visiting',
            self::PartTime    => 'Part-Time',
            self::Adjunct     => 'Adjunct',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Permanent   => 'success',
            self::Contractual => 'warning',
            self::Visiting    => 'info',
            self::PartTime    => 'gray',
            self::Adjunct     => 'gray',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($e) => [$e->value => $e->label()])->all();
    }
}
