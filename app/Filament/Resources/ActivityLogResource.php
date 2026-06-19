<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use App\Models\ActivityLog;
use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'System';
    protected static ?string $navigationLabel = 'Activity Logs';
    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Time')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type_label')
                    ->label('Type')
                    ->badge()
                    ->color(fn (ActivityLog $record): string => $record->typeColor()),
                Tables\Columns\TextColumn::make('level_label')
                    ->label('Level')
                    ->badge()
                    ->color(fn (ActivityLog $record): string => $record->levelColor()),
                Tables\Columns\TextColumn::make('event')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('actor_summary')
                    ->label('Actor')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('subject_summary')
                    ->label('Subject')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('message')
                    ->searchable()
                    ->wrap()
                    ->limit(70),
                Tables\Columns\TextColumn::make('meta')
                    ->label('Meta')
                    ->formatStateUsing(function ($state): string {
                        if (blank($state)) {
                            return '';
                        }

                        if (is_array($state)) {
                            return json_encode($state, JSON_UNESCAPED_SLASHES) ?: '';
                        }

                        return (string) $state;
                    })
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->wrap()
                    ->limit(90),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(ActivityLog::typeOptions()),
                Tables\Filters\SelectFilter::make('level')
                    ->options(ActivityLog::levelOptions()),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from'),
                        \Filament\Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date))
                            ->when($data['until'] ?? null, fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([])
            ->bulkActions([])
            ->paginated([25, 50, 100]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->latest('created_at');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = Filament::auth()->user();

        return static::hasBackingTable()
            && (bool) ($user?->hasRole(config('filament-shield.super_admin.name')));
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canViewAny();
    }

    protected static function hasBackingTable(): bool
    {
        try {
            return Schema::hasTable('activity_logs');
        } catch (\Throwable) {
            return false;
        }
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}
