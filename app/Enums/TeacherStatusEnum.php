<?php

namespace App\Enums;

enum TeacherStatusEnum: string
{
    case Active      = 'active';
    case Inactive    = 'inactive';
    case OnLeave     = 'on_leave';
    case Resigned    = 'resigned';
    case Retired     = 'retired';
    case Terminated  = 'terminated';

    public function label(): string
    {
        return match($this) {
            self::Active     => 'Active',
            self::Inactive   => 'Inactive',
            self::OnLeave    => 'On Leave',
            self::Resigned   => 'Resigned',
            self::Retired    => 'Retired',
            self::Terminated => 'Terminated',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Active     => 'success',
            self::Inactive   => 'gray',
            self::OnLeave    => 'warning',
            self::Resigned   => 'danger',
            self::Retired    => 'info',
            self::Terminated => 'danger',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($e) => [$e->value => $e->label()])->all();
    }
}
