<?php

namespace App\Enums;

enum DepartmentTypeEnum: string
{
    case Academic        = 'academic';
    case Administrative  = 'administrative';
    case Research        = 'research';

    public function label(): string
    {
        return match($this) {
            self::Academic       => 'Academic',
            self::Administrative => 'Administrative',
            self::Research       => 'Research',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Academic       => 'primary',
            self::Administrative => 'warning',
            self::Research       => 'info',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->label()])
            ->toArray();
    }
}
