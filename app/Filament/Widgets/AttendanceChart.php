<?php

namespace App\Filament\Widgets;

use App\Enums\AttendanceStatusEnum;
use App\Models\AttendanceRecord;
use App\Models\Course;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AttendanceChart extends ChartWidget
{
    protected static ?string $heading = 'Attendance Overview';

    protected static ?int $sort = 4;

    public static function canView(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'Developer', 'panel_user']) ?? false;
    }

    protected int | string | array $columnSpan = 1;

    protected static ?string $maxHeight = '300px';

    public ?string $filter = 'overall';

    protected function getFilters(): ?array
    {
        $filters = ['overall' => 'Overall (All Courses)'];

        Course::orderBy('name')
            ->get(['id', 'name', 'code'])
            ->each(function ($c) use (&$filters) {
                $filters[$c->id] = $c->code . ' — ' . $c->name;
            });

        return $filters;
    }

    protected function getData(): array
    {
        $query = AttendanceRecord::query();

        if ($this->filter !== 'overall') {
            $query->whereHas(
                'session',
                fn($q) => $q->where('course_id', $this->filter)
            );
        }

        $present = (clone $query)->where('status', AttendanceStatusEnum::Present->value)->count();
        $absent  = (clone $query)->where('status', AttendanceStatusEnum::Absent->value)->count();
        $late    = (clone $query)->where('status', AttendanceStatusEnum::Late->value)->count();
        $leave   = (clone $query)->where('status', AttendanceStatusEnum::Leave->value)->count();
        $excused = (clone $query)->where('status', AttendanceStatusEnum::Excused->value)->count();

        return [
            'datasets' => [
                [
                    'label'           => 'Attendance Breakdown',
                    'data'            => [$present, $absent, $late, $leave, $excused],
                    'backgroundColor' => [
                        '#10b981', // green   — Present
                        '#ef4444', // red     — Absent
                        '#f59e0b', // amber   — Late
                        '#3b82f6', // blue    — On Leave
                        '#8b5cf6', // violet  — Excused
                    ],
                    'hoverOffset' => 6,
                ],
            ],
            'labels' => ['Present', 'Absent', 'Late', 'On Leave', 'Excused'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'cutout'     => '65%',
            'plugins'    => [
                'legend'  => ['position' => 'right'],
                'tooltip' => ['mode' => 'point'],
            ],
        ];
    }
}
