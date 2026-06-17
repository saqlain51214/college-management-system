<?php

namespace App\Filament\Widgets;

use App\Models\AcademicProgram;
use App\Models\Course;
use App\Models\ExamResult;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ExamPerformanceChart extends ChartWidget
{
    protected static ?string $heading = 'Exam Results — Grade Distribution';

    protected static ?int $sort = 5;

    public static function canView(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'Developer', 'panel_user']) ?? false;
    }

    protected int | string | array $columnSpan = 1;

    protected static ?string $maxHeight = '300px';

    public ?string $filter = 'all';

    protected function getFilters(): ?array
    {
        $filters = ['all' => 'All Programs'];
        AcademicProgram::orderBy('name')->get(['id', 'name'])->each(function ($p) use (&$filters) {
            $filters[$p->id] = $p->name;
        });
        return $filters;
    }

    protected function getData(): array
    {
        $gradeOrder = ['A+', 'A', 'B+', 'B', 'C+', 'C', 'D', 'F'];

        $query = ExamResult::query()->whereNotNull('grade')->where('is_absent', false);

        if ($this->filter !== 'all') {
            $query->whereHas(
                'student',
                fn($q) => $q->where('academic_program_id', $this->filter)
            );
        }

        $gradeCounts = $query->select('grade', DB::raw('count(*) as total'))
            ->groupBy('grade')
            ->pluck('total', 'grade');

        $data = collect($gradeOrder)->map(fn($g) => $gradeCounts->get($g, 0))->toArray();

        $backgroundColors = [
            '#10b981', // A+ — emerald
            '#22c55e', // A  — green
            '#84cc16', // B+ — lime
            '#a3e635', // B  — lime light
            '#facc15', // C+ — yellow
            '#f59e0b', // C  — amber
            '#f97316', // D  — orange
            '#ef4444', // F  — red
        ];

        return [
            'datasets' => [
                [
                    'label'           => 'Number of Students',
                    'data'            => $data,
                    'backgroundColor' => $backgroundColors,
                    'borderRadius'    => 6,
                ],
            ],
            'labels' => $gradeOrder,
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
            'indexAxis'  => 'x',
            'scales'     => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks'       => ['stepSize' => 1],
                    'title'       => [
                        'display' => true,
                        'text'    => 'Number of Students',
                    ],
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text'    => 'Grade',
                    ],
                ],
            ],
            'plugins' => [
                'legend'  => ['display' => false],
                'tooltip' => ['mode' => 'index'],
            ],
        ];
    }
}
