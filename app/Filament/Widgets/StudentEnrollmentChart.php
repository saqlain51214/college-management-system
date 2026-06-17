<?php

namespace App\Filament\Widgets;

use App\Models\AcademicProgram;
use App\Models\Student;
use Filament\Widgets\ChartWidget;

class StudentEnrollmentChart extends ChartWidget
{
    protected static ?string $heading = 'Student Enrollment by Batch & Program';

    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'Developer', 'panel_user']) ?? false;
    }

    protected int | string | array $columnSpan = 2;

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $programs = AcademicProgram::orderBy('name')->get();

        $batchYears = Student::select('batch_year')
            ->distinct()
            ->orderBy('batch_year')
            ->pluck('batch_year')
            ->filter()
            ->values();

        $colors = [
            '#3b82f6', // blue
            '#10b981', // green
            '#f59e0b', // amber
            '#ef4444', // red
            '#8b5cf6', // violet
            '#06b6d4', // cyan
        ];

        $datasets = [];
        foreach ($programs as $idx => $program) {
            $data = [];
            foreach ($batchYears as $year) {
                $data[] = Student::where('academic_program_id', $program->id)
                    ->where('batch_year', $year)
                    ->count();
            }
            $color = $colors[$idx % count($colors)];
            $datasets[] = [
                'label'           => $program->name,
                'data'            => $data,
                'backgroundColor' => $color,
                'borderColor'     => $color,
                'borderRadius'    => 4,
            ];
        }

        return [
            'datasets' => $datasets,
            'labels'   => $batchYears->map(fn($y) => 'Batch ' . $y)->toArray(),
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
                'x' => ['stacked' => false],
                'y' => [
                    'stacked'    => false,
                    'beginAtZero' => true,
                    'ticks'      => ['stepSize' => 1],
                ],
            ],
            'plugins' => [
                'legend' => ['position' => 'top'],
                'tooltip' => ['mode' => 'index'],
            ],
        ];
    }
}
