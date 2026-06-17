<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AcademicYearResource\Pages;
use App\Filament\Resources\AcademicYearResource\RelationManagers\SemestersRelationManager;
use App\Helpers\ValidationHelper;
use App\Models\AcademicYear;
use App\Services\AcademicYearService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Unique;

class AcademicYearResource extends Resource
{
    protected static ?string $model = AcademicYear::class;

    protected static ?string $navigationIcon  = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'College Setup';
    protected static ?string $navigationLabel = 'Academic Years';
    protected static ?int    $navigationSort  = 3;

    // ─── Form ────────────────────────────────────────────────────────────────

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Academic Year Details')
                ->columns(2)
                ->schema([

                    Forms\Components\TextInput::make('name')
                        ->label('Academic Year')
                        ->placeholder('e.g. 2025-2026')
                        ->required()
                        ->maxLength(20)
                        ->unique(
                            table: 'academic_years',
                            column: 'name',
                            modifyRuleUsing: fn(Unique $rule, ?AcademicYear $record) => $record
                                ? $rule->ignore($record->id)
                                : $rule
                        )
                        ->regex('/^\d{4}-\d{4}$/')
                        ->live(onBlur: true)
                        ->extraAttributes(['required' => true, 'pattern' => '\d{4}-\d{4}', 'title' => 'Format: YYYY-YYYY e.g. 2025-2026'])
                        ->helperText('Format: YYYY-YYYY e.g. 2025-2026')
                        ->validationMessages([
                            'required' => 'Academic year name is required.',
                            'unique'   => 'This academic year already exists.',
                            'regex'    => 'Format must be YYYY-YYYY e.g. 2025-2026.',
                        ]),

                    Forms\Components\DatePicker::make('start_date')
                        ->label('Start Date')
                        ->required()
                        ->native(false)
                        ->displayFormat('d M Y')
                        ->validationMessages(['required' => 'Start date is required.']),

                    Forms\Components\DatePicker::make('end_date')
                        ->label('End Date')
                        ->required()
                        ->native(false)
                        ->displayFormat('d M Y')
                        ->after('start_date')
                        ->validationMessages([
                            'required' => 'End date is required.',
                            'after'    => 'End date must be after start date.',
                        ]),

                    Forms\Components\Textarea::make('description')
                        ->label('Notes')
                        ->rows(3)
                        ->maxLength(500)
                        ->extraAttributes(ValidationHelper::textAttrs(max: 500))
                        ->helperText('Optional internal notes.')
                        ->columnSpanFull(),

                    Forms\Components\Toggle::make('is_current')
                        ->label('Current Academic Year')
                        ->onColor('success')
                        ->helperText('Only one academic year can be current at a time. Setting this will unset others.')
                        ->live(),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true)
                        ->onColor('success'),
                ]),
        ]);
    }

    // ─── Table ───────────────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Academic Year')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn(AcademicYear $r) => $r->start_date?->format('d M Y') . ' → ' . $r->end_date?->format('d M Y')),

                Tables\Columns\TextColumn::make('semesters_count')
                    ->label('Semesters')
                    ->counts('semesters')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\IconColumn::make('is_current')
                    ->label('Current')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-minus'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->date('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('start_date', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_current')->label('Current Year'),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('setCurrent')
                    ->label('Set as Current')
                    ->icon('heroicon-o-star')
                    ->color('success')
                    ->iconButton()
                    ->hidden(fn(AcademicYear $r) => $r->is_current)
                    ->requiresConfirmation()
                    ->modalHeading(fn(AcademicYear $r) => 'Set ' . $r->name . ' as Current Year?')
                    ->modalDescription('This will unset the current academic year. Are you sure?')
                    ->action(function (AcademicYear $r) {
                        app(AcademicYearService::class)->setCurrent($r);
                        Notification::make()->title($r->name . ' is now the current academic year.')->success()->send();
                    }),

                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->paginated([10, 25, 50, 100])
            ->striped();
    }

    public static function getRelationManagers(): array
    {
        return [
            SemestersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAcademicYears::route('/'),
            'create' => Pages\CreateAcademicYear::route('/create'),
            'edit'   => Pages\EditAcademicYear::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        try {
            $current = AcademicYear::where('is_current', true)->value('name');
            return $current ?? null;
        } catch (\Exception) {
            return null;
        }
    }
}
