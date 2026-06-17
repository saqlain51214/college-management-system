<?php

namespace App\Enums;

enum PaymentStatusEnum: string
{
    case Pending  = 'pending';
    case Partial  = 'partial';
    case Paid     = 'paid';
    case Overdue  = 'overdue';
    case Waived   = 'waived';

    public function label(): string
    {
        return match($this) {
            self::Pending => 'Pending',
            self::Partial => 'Partially Paid',
            self::Paid    => 'Paid',
            self::Overdue => 'Overdue',
            self::Waived  => 'Waived',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending => 'warning',
            self::Partial => 'info',
            self::Paid    => 'success',
            self::Overdue => 'danger',
            self::Waived  => 'gray',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($e) => [$e->value => $e->label()])->all();
    }
}
