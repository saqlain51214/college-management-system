<?php

namespace App\Enums;

enum AttendanceStatusEnum: string
{
    case Present = 'present';
    case Absent  = 'absent';
    case Late    = 'late';
    case Leave   = 'leave';
    case Excused = 'excused';

    public function label(): string
    {
        return match($this) {
            self::Present => 'Present',
            self::Absent  => 'Absent',
            self::Late    => 'Late',
            self::Leave   => 'On Leave',
            self::Excused => 'Excused',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Present => 'success',
            self::Absent  => 'danger',
            self::Late    => 'warning',
            self::Leave   => 'info',
            self::Excused => 'gray',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($e) => [$e->value => $e->label()])->all();
    }
}
