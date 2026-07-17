<?php

namespace App\Filament\Resources;

use App\Enums\FeeTypeEnum;
use App\Filament\Resources\FeeStructureResource\Pages;
use App\Models\AcademicProgram;
use App\Models\AcademicYear;
use App\Models\FeeStructure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FeeStructureResource extends Resource
{
    protected static ?string $model = FeeStructure::class;

    protected static ?string $navigationIcon  = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Fee Structure';
    protected static ?int    $navigationSort  = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Fee Details')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Fee Title')
                        ->required()
                        ->maxLength(150)
                        ->placeholder('e.g. BS CS Semester 1 Tuition Fee 2024-25')
                        ->columnSpanFull(),

                    Forms\Components\Select::make('fee_type')
                        ->label('Fee Type')
                        ->options(FeeTypeEnum::options())
                        ->required()
                        ->default(FeeTypeEnum::Tuition->value),

                    Forms\Components\Select::make('semester_number')
                        ->label('Semester')
                        ->options(collect(range(1, 8))->mapWithKeys(fn($n) => [$n => "Semester $n"])->all())
                        ->placeholder('All Semesters'),

                    Forms\Components\Select::make('academic_program_id')
                        ->label('Academic Program')
                        ->options(fn() => AcademicProgram::active()->ordered()->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->placeholder('All Programs'),

                    Forms\Components\Select::make('academic_year_id')
                        ->label('Academic Year')
                        ->options(fn() => AcademicYear::active()->orderByDesc('start_date')->pluck('name', 'id'))
                        ->searchable()
                        ->placeholder('Select Year'),

                    Forms\Components\TextInput::make('amount')
                        ->label('Fee Amount (PKR)')
                        ->numeric()
                        ->required()
                        ->minValue(0)
                        ->prefix('Rs.')
                        ->placeholder('e.g. 25000'),

                    Forms\Components\TextInput::make('late_fine_per_day')
                        ->label('Late Fine Per Day (PKR)')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->prefix('Rs.'),

                    Forms\Components\DatePicker::make('due_date')
                        ->label('Due Date')
                        ->displayFormat('d M Y')
                        ->native(false),

                    Forms\Components\Select::make('frequency')
                        ->label('Frequency')
                        ->options(fn() => \App\Models\ListItem::getOptions('fee_frequency'))
                        ->default('semester'),

                    Forms\Components\Toggle::make('is_mandatory')->label('Mandatory')->default(true)->onColor('warning'),
                    Forms\Components\Toggle::make('is_active')->label('Active')->default(true)->onColor('success'),

                    Forms\Components\Textarea::make('description')
                        ->label('Description / Notes')
                        ->rows(2)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->wrap()->sortable(),
                Tables\Columns\TextColumn::make('fee_type')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state instanceof FeeTypeEnum ? $state->label() : $state)
                    ->color(fn($state) => $state instanceof FeeTypeEnum ? $state->color() : 'gray'),
                Tables\Columns\TextColumn::make('academicProgram.short_name')->label('Program')->badge()->color('info')->placeholder('All'),
                Tables\Columns\TextColumn::make('academicYear.name')->label('Year')->placeholder('â€”'),
                Tables\Columns\TextColumn::make('semester_number')->label('Sem')->prefix('S')->placeholder('All'),
                Tables\Columns\TextColumn::make('amount')->label('Amount')->money('PKR')->sortable(),
                Tables\Columns\TextColumn::make('due_date')->label('Due Date')->date('d M Y')->placeholder('â€”'),
                Tables\Columns\IconColumn::make('is_mandatory')->label('Mandatory')->boolean(),
                Tables\Columns\IconColumn::make('is_active')->label('Active')->boolean()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('fee_type')->options(FeeTypeEnum::options()),
                Tables\Filters\SelectFilter::make('academic_program_id')->label('Program')->relationship('academicProgram', 'name'),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->defaultSort('fee_type')
            ->paginated([10, 25, 50, 100])
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFeeStructures::route('/'),
            'create' => Pages\CreateFeeStructure::route('/create'),
            'edit'   => Pages\EditFeeStructure::route('/{record}/edit'),
        ];
    }
}
