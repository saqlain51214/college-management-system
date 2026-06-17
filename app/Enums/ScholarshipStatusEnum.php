<?php

namespace App\Enums;

enum ScholarshipStatusEnum: string
{
    case Applied     = 'applied';
    case UnderReview = 'under_review';
    case Approved    = 'approved';
    case Rejected    = 'rejected';
    case Disbursed   = 'disbursed';
    case Expired     = 'expired';

    public function label(): string
    {
        return match($this) {
            self::Applied     => 'Applied',
            self::UnderReview => 'Under Review',
            self::Approved    => 'Approved',
            self::Rejected    => 'Rejected',
            self::Disbursed   => 'Disbursed',
            self::Expired     => 'Expired',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Applied     => 'info',
            self::UnderReview => 'warning',
            self::Approved    => 'success',
            self::Rejected    => 'danger',
            self::Disbursed   => 'success',
            self::Expired     => 'gray',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($e) => [$e->value => $e->label()])->all();
    }
}
