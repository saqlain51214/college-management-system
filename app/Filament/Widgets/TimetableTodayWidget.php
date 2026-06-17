<?php
namespace App\Filament\Widgets;
use App\Models\Timetable;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TimetableTodayWidget extends BaseWidget
{
    protected static ?string $heading = "Today's Classes";
    protected static ?int $sort = 9;
    protected int|string|array $columnSpan = 1;

    public static function canView(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin','Developer','panel_user']) ?? false;
    }

    public function table(Table $table): Table
    {
        $today = strtolower(now()->format('l'));

        return $table
            ->query(
                Timetable::with(['course','teacher'])
                    ->where('day_of_week', $today)
                    ->where('is_active', true)
                    ->orderBy('start_time')
            )
            ->columns([
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Time')
                    ->formatStateUsing(fn($record) => substr($record->start_time,0,5) . ' – ' . substr($record->end_time,0,5)),
                Tables\Columns\TextColumn::make('course.code')->label('Course')
                    ->description(fn($record) => $record->course?->name),
                Tables\Columns\TextColumn::make('teacher.name')->label('Teacher')->limit(18),
                Tables\Columns\TextColumn::make('room')->label('Room')->placeholder('—'),
            ])
            ->emptyStateHeading('No classes today')
            ->emptyStateIcon('heroicon-o-calendar')
            ->paginated(false);
    }
}
