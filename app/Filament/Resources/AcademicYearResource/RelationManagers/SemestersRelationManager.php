<?php

namespace App\Filament\Resources\AcademicYearResource\RelationManagers;

use App\Enums\SemesterTypeEnum;
use App\Models\Semester;
use App\Services\SemesterService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SemestersRelationManager extends RelationManager
{
    protected static string $relationship = 'semesters';
    protected static ?string $title       = 'Semesters';

    // ─── Form ────────────────────────────────────────────────────────────────

    public function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Select::make('type')
                ->label('Semester Type')
                ->options(SemesterTypeEnum::options())
                ->required()
                ->live()
                ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                    if ($state) {
                        $year = $this->getOwnerRecord()->name ?? date('Y');
                        $set('name', SemesterTypeEnum::from($state)->label() . ' ' . substr($year, 0, 4));
                        $set('number', $state === 'fall' ? 1 : ($state === 'spring' ? 2 : 3));
                    }
                })
                ->validationMessages(['required' => 'Semester type is required.']),

            Forms\Components\TextInput::make('name')
                ->label('Semester Name')
                ->placeholder('e.g. Fall 2025')
                ->required()
                ->maxLength(50)
                ->live(onBlur: true)
                ->validationMessages(['required' => 'Semester name is required.']),

            Forms\Components\TextInput::make('number')
                ->label('Semester #')
                ->numeric()
                ->default(1)
                ->minValue(1)
                ->maxValue(3)
                ->helperText('1=Fall, 2=Spring, 3=Summer'),

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

            Forms\Components\Section::make('Key Dates')
                ->columns(2)
                ->collapsed()
                ->schema([
                    Forms\Components\DatePicker::make('registration_start')
                        ->label('Registration Opens')
                        ->native(false)
                        ->displayFormat('d M Y'),

                    Forms\Components\DatePicker::make('registration_end')
                        ->label('Registration Closes')
                        ->native(false)
                        ->displayFormat('d M Y'),

                    Forms\Components\DatePicker::make('exam_start')
                        ->label('Exams Start')
                        ->native(false)
                        ->displayFormat('d M Y'),

                    Forms\Components\DatePicker::make('exam_end')
                        ->label('Exams End')
                        ->native(false)
                        ->displayFormat('d M Y'),
                ]),

            Forms\Components\TextInput::make('sort_order')
                ->label('Sort Order')
                ->numeric()
                ->default(0)
                ->minValue(0)
                ->maxValue(99),

            Forms\Components\Toggle::make('is_current')
                ->label('Current Semester')
                ->onColor('success')
                ->helperText('Setting this will unset all other current semesters.'),

            Forms\Components\Toggle::make('is_active')
                ->label('Active')
                ->default(true)
                ->onColor('success'),
        ]);
    }

    // ─── Table ───────────────────────────────────────────────────────────────

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Semester')
                    ->weight('bold')
                    ->description(fn(Semester $r) => $r->start_date?->format('d M Y') . ' → ' . $r->end_date?->format('d M Y')),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state instanceof SemesterTypeEnum ? $state->label() : $state)
                    ->color(fn($state) => $state instanceof SemesterTypeEnum ? $state->color() : 'gray'),

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
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->using(fn(array $data) => app(SemesterService::class)->createModel($data)),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->using(fn(Semester $record, array $data) => app(SemesterService::class)->updateModel($record, $data)),

                Tables\Actions\Action::make('setCurrent')
                    ->label('Set Current')
                    ->icon('heroicon-o-star')
                    ->color('success')
                    ->iconButton()
                    ->hidden(fn(Semester $r) => $r->is_current)
                    ->requiresConfirmation()
                    ->action(function (Semester $r) {
                        app(SemesterService::class)->setCurrent($r);
                        Notification::make()->title($r->name . ' is now the current semester.')->success()->send();
                    }),

                Tables\Actions\DeleteAction::make(),
            ])
            ->striped();
    }
}
