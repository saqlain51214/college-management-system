<?php

namespace App\Filament\Pages;

use App\Enums\PaymentStatusEnum;
use App\Models\FeePayment;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;

class FeeReports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Finance';

    protected static ?string $navigationLabel = 'Fee Reports';

    protected static ?string $title = 'Fee Reports';

    protected static ?int $navigationSort = 7;

    protected static string $view = 'filament.pages.fee-reports';

    /** @var array<string,float|int> */
    public array $summary = [];

    /** @var array<int,array<string,mixed>> */
    public array $outstanding = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin', 'Developer', 'panel_user']) ?? false;
    }

    public function mount(): void
    {
        $today = Carbon::today();

        $billed = (float) FeePayment::sum('amount_due')
            + (float) FeePayment::sum('fine_amount')
            - (float) FeePayment::sum('discount_amount');
        $collected = (float) FeePayment::sum('amount_paid');

        $this->summary = [
            'billed'          => $billed,
            'collected'       => $collected,
            'outstanding'     => max(0, $billed - $collected),
            'overdue_count'   => FeePayment::where('payment_status', PaymentStatusEnum::Overdue->value)->count(),
            'overdue_amount'  => (float) FeePayment::where('payment_status', PaymentStatusEnum::Overdue->value)->sum('amount_due'),
            'collected_today' => (float) FeePayment::whereDate('payment_date', $today)->sum('amount_paid'),
            'collected_month' => (float) FeePayment::whereYear('payment_date', $today->year)
                ->whereMonth('payment_date', $today->month)
                ->sum('amount_paid'),
            'paid_count'      => FeePayment::where('payment_status', PaymentStatusEnum::Paid->value)->count(),
        ];

        $this->outstanding = FeePayment::with('student')
            ->where('payment_status', '!=', PaymentStatusEnum::Paid->value)
            ->orderByRaw('due_date is null, due_date asc')
            ->limit(200)
            ->get()
            ->map(function (FeePayment $p) use ($today): array {
                $status = $p->payment_status instanceof PaymentStatusEnum
                    ? $p->payment_status->value
                    : (string) $p->payment_status;

                return [
                    'challan'   => $p->challan_number,
                    'student'   => $p->student?->name ?? '—',
                    'roll'      => $p->student?->roll_number ?? '—',
                    'net'       => $p->net_amount,
                    'paid'      => (float) $p->amount_paid,
                    'balance'   => max(0, $p->net_amount - (float) $p->amount_paid),
                    'status'    => $status,
                    'due'       => $p->due_date?->format('d M Y') ?? '—',
                    'days_late' => ($p->due_date && $p->due_date->isPast()) ? $p->due_date->diffInDays($today) : 0,
                ];
            })
            ->all();
    }
}
