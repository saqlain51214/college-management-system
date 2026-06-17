<?php

namespace App\Filament\Resources;

use App\Enums\ScholarshipTypeEnum;
use App\Filament\Resources\ScholarshipResource\Pages;
use App\Models\Scholarship;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ScholarshipResource extends Resource
{
    protected static ?string $model = Scholarship::class;

    protected static ?string $navigationIcon  = 'heroicon-o-gift';
    protected static ?string $navigationGroup = 'Students & Admissions';
    protected static ?string $navigationLabel = 'Scholarships';
    protected static ?int    $navigationSort  = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Scholarship Details')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Scholarship Name')
                        ->required()
                        ->maxLength(150)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn($state, Forms\Set $set) => $set('slug', Str::slug($state)))
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('slug')->label('Slug')->maxLength(150)->required(),

                    Forms\Components\Select::make('scholarship_type')
                        ->label('Type')
                        ->options(ScholarshipTypeEnum::options())
                        ->required()
                        ->default(ScholarshipTypeEnum::Merit->value),

                    Forms\Components\TextInput::make('funding_source')->label('Funding Source')->maxLength(100)->placeholder('e.g. HEC, Government of Punjab'),
                    Forms\Components\TextInput::make('seats')->label('No. of Seats')->numeric()->minValue(0)->placeholder('Leave blank for unlimited'),

                    Forms\Components\TextInput::make('amount')->label('Amount (PKR)')->numeric()->minValue(0)->prefix('Rs.')->placeholder('Per semester / one time'),
                    Forms\Components\TextInput::make('coverage_percent')->label('Coverage %')->numeric()->minValue(0)->maxValue(100)->placeholder('e.g. 50 = 50% fee waiver'),

                    Forms\Components\DatePicker::make('application_start')->label('Application Opens')->displayFormat('d M Y')->native(false),
                    Forms\Components\DatePicker::make('application_end')->label('Application Closes')->displayFormat('d M Y')->native(false),

                    Forms\Components\Toggle::make('is_recurring')->label('Recurring (Each Semester)')->default(true)->onColor('info'),
                    Forms\Components\Toggle::make('is_active')->label('Active')->default(true)->onColor('success'),

                    Forms\Components\Textarea::make('description')->label('Description')->rows(3)->columnSpanFull(),
                    Forms\Components\Textarea::make('eligibility_criteria')->label('Eligibility Criteria')->rows(3)->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable()->wrap(),
                Tables\Columns\TextColumn::make('scholarship_type')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state instanceof ScholarshipTypeEnum ? $state->label() : $state)
                    ->color(fn($state) => $state instanceof ScholarshipTypeEnum ? $state->color() : 'gray'),
                Tables\Columns\TextColumn::make('funding_source')->placeholder('â€”')->toggleable(),
                Tables\Columns\TextColumn::make('amount')->money('PKR')->placeholder('â€”')->sortable(),
                Tables\Columns\TextColumn::make('coverage_percent')->suffix('%')->placeholder('â€”')->label('Coverage'),
                Tables\Columns\TextColumn::make('seats')->placeholder('Unlimited')->label('Seats'),
                Tables\Columns\TextColumn::make('application_end')->label('Closes')->date('d M Y')->placeholder('â€”'),
                Tables\Columns\IconColumn::make('is_active')->label('Active')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('scholarship_type')->options(ScholarshipTypeEnum::options()),
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
            ->paginated([10, 25, 50, 100])
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListScholarships::route('/'),
            'create' => Pages\CreateScholarship::route('/create'),
            'edit'   => Pages\EditScholarship::route('/{record}/edit'),
        ];
    }
}


