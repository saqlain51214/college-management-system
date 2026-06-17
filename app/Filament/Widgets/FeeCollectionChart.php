<?php

namespace App\Filament\Widgets;

use App\Enums\PaymentStatusEnum;
use App\Models\FeePayment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class FeeCollectionChart extends ChartWidget
{
    protected static ?string $heading = 'Fee Collection — Last 6 Months';

    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'Developer', 'panel_user']) ?? false;
    }

    protected int | string | array $columnSpan = 2;

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Anchor to the latest due_date in the DB so seed data (2024) always shows
        $latestDate = FeePayment::max('due_date');
        $anchor     = $latestDate ? Carbon::parse($latestDate) : Carbon::now();

        $months    = [];
        $collected = [];
        $pending   = [];
        $overdue   = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = $anchor->copy()->subMonths($i);
            $months[] = $date->format('M Y');

            $collected[] = (float) FeePayment::where('payment_status', PaymentStatusEnum::Paid->value)
                ->whereMonth('payment_date', $date->month)
                ->whereYear('payment_date', $date->year)
                ->sum('amount_paid');

            $pending[] = (float) FeePayment::where('payment_status', PaymentStatusEnum::Pending->value)
                ->whereMonth('due_date', $date->month)
                ->whereYear('due_date', $date->year)
                ->sum('amount_due');

            $overdue[] = (float) FeePayment::where('payment_status', PaymentStatusEnum::Overdue->value)
                ->whereMonth('due_date', $date->month)
                ->whereYear('due_date', $date->year)
                ->sum('amount_due');
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Collected (PKR)',
                    'data'            => $collected,
                    'backgroundColor' => '#10b981',
                    'borderColor'     => '#059669',
                    'borderRadius'    => 4,
                ],
                [
                    'label'           => 'Pending (PKR)',
                    'data'            => $pending,
                    'backgroundColor' => '#f59e0b',
                    'borderColor'     => '#d97706',
                    'borderRadius'    => 4,
                ],
                [
                    'label'           => 'Overdue (PKR)',
                    'data'            => $overdue,
                    'backgroundColor' => '#ef4444',
                    'borderColor'     => '#dc2626',
                    'borderRadius'    => 4,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
            'plugins' => [
                'legend' => ['position' => 'top'],
            ],
        ];
    }
}
