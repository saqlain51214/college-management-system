<?php

namespace App\Filament\Widgets;

use App\Models\ExamResult;
use App\Models\Student;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class TopPerformersWidget extends BaseWidget
{
    protected static ?string $heading = 'Top Performing Students';
    protected static ?int    $sort    = 8;
    protected int|string|array $columnSpan = 2;

    public static function canView(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'Developer', 'panel_user']) ?? false;
    }

    public function table(Table $table): Table
    {
        // Single query: fetch top 8 student IDs and their avg GPA together
        $topRows = ExamResult::select('student_id', DB::raw('ROUND(AVG(grade_points), 2) as cgpa'))
            ->where('is_absent', false)
            ->whereNotNull('grade_points')
            ->groupBy('student_id')
            ->orderByDesc('cgpa')
            ->limit(8)
            ->get();

        $topIds  = $topRows->pluck('student_id');
        $cgpaMap = $topRows->pluck('cgpa', 'student_id'); // keyed map, no N+1

        return $table
            ->query(Student::with(['academicProgram', 'department'])->whereIn('id', $topIds))
            ->columns([
                Tables\Columns\TextColumn::make('roll_number')
                    ->label('Roll No')
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Student')
                    ->searchable(),

                Tables\Columns\TextColumn::make('academicProgram.name')
                    ->label('Program')
                    ->limit(15),

                Tables\Columns\TextColumn::make('batch_year')
                    ->label('Batch')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('current_semester')
                    ->label('Sem')
                    ->badge(),

                Tables\Columns\TextColumn::make('cgpa')
                    ->label('CGPA')
                    ->getStateUsing(fn($record) => $cgpaMap->get($record->id, '0.00'))
                    ->badge()
                    ->color(fn($state): string => (float) $state >= 3.5
                        ? 'success'
                        : ((float) $state >= 3.0 ? 'warning' : 'gray')),
            ])
            ->paginated(false);
    }
}
