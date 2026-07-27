<?php

namespace App\Filament\Widgets;

use App\Enums\PaymentStatusEnum;
use App\Enums\RefundStatusEnum;
use App\Enums\ScholarshipStatusEnum;
use App\Filament\Pages\ProofReview;
use App\Filament\Resources\FeePaymentResource;
use App\Filament\Resources\FeeRefundResource;
use App\Filament\Resources\ScholarshipAwardResource;
use App\Models\FeePayment;
use App\Models\FeeRefund;
use App\Models\ScholarshipAward;
use Filament\Widgets\Widget;

class ActionCenterWidget extends Widget
{
    protected static string $view = 'filament.widgets.action-center-widget';

    protected static ?int $sort = 0;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin', 'Developer', 'panel_user']) ?? false;
    }

    /** @return array<int,array<string,mixed>> */
    public function getCards(): array
    {
        $pendingProofs = FeePayment::whereNotNull('payment_proof_path')
            ->where('payment_status', '!=', PaymentStatusEnum::Paid->value)
            ->count();

        $overdue = FeePayment::where('payment_status', PaymentStatusEnum::Overdue->value)->count();

        $pendingRefunds = FeeRefund::where('status', RefundStatusEnum::Pending->value)->count();

        $pendingScholarships = ScholarshipAward::whereIn('status', [
            ScholarshipStatusEnum::Applied->value,
            ScholarshipStatusEnum::UnderReview->value,
        ])->count();

        return [
            [
                'label' => 'Pending Proof Reviews',
                'count' => $pendingProofs,
                'icon'  => 'heroicon-o-paper-clip',
                'url'   => ProofReview::getUrl(),
            ],
            [
                'label' => 'Overdue Fees',
                'count' => $overdue,
                'icon'  => 'heroicon-o-exclamation-triangle',
                'url'   => FeePaymentResource::getUrl('index') . '?tableFilters[payment_status][value]=overdue',
            ],
            [
                'label' => 'Pending Refund Requests',
                'count' => $pendingRefunds,
                'icon'  => 'heroicon-o-arrow-uturn-left',
                'url'   => FeeRefundResource::getUrl('index') . '?tableFilters[status][value]=pending',
            ],
            [
                'label' => 'Pending Scholarship Approvals',
                'count' => $pendingScholarships,
                'icon'  => 'heroicon-o-trophy',
                'url'   => ScholarshipAwardResource::getUrl('index'),
            ],
        ];
    }
}
