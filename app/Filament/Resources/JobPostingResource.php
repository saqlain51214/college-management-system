<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobPostingResource\Pages;
use App\Models\JobPosting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JobPostingResource extends Resource
{
    protected static ?string $model = JobPosting::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Faculty & Staff';

    protected static ?string $navigationLabel = 'Job Postings';

    protected static ?string $modelLabel = 'Job Posting';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Position')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Job Title')
                        ->required()
                        ->maxLength(200)
                        ->placeholder('e.g. Lecturer — Computer Science')
                        ->columnSpanFull(),

                    Forms\Components\Select::make('employment_type')
                        ->label('Employment Type')
                        ->options(JobPosting::employmentTypeOptions())
                        ->default('full_time')
                        ->required(),

                    Forms\Components\TextInput::make('department')
                        ->label('Department')
                        ->required()
                        ->maxLength(150)
                        ->placeholder('e.g. Department of Computer Science'),

                    Forms\Components\Textarea::make('qualification')
                        ->label('Qualification / Requirements')
                        ->required()
                        ->rows(2)
                        ->columnSpanFull()
                        ->placeholder('e.g. MS / BS (Hons) Computer Science from an HEC-recognised university'),

                    Forms\Components\Textarea::make('description')
                        ->label('Additional Details (Optional)')
                        ->rows(3)
                        ->columnSpanFull()
                        ->helperText('Responsibilities, experience required, or anything else applicants should know.'),
                ])->columns(2),

            Forms\Components\Section::make('Display')
                ->schema([
                    Forms\Components\DatePicker::make('closing_date')
                        ->label('Apply By (Optional)')
                        ->displayFormat('d M Y')
                        ->native(false)
                        ->helperText('Posting is automatically hidden from the website after this date. Leave blank to keep it open indefinitely.'),

                    Forms\Components\TextInput::make('sort_order')
                        ->label('Order')
                        ->numeric()
                        ->default(0)
                        ->helperText('Lower number shows first.'),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Show on website')
                        ->default(true),
                ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable()->weight('bold')->limit(40),
                Tables\Columns\TextColumn::make('department')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('employment_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => JobPosting::employmentTypeOptions()[$state] ?? $state),
                Tables\Columns\TextColumn::make('closing_date')->label('Apply By')->date('d M Y')->placeholder('Open-ended'),
                Tables\Columns\TextColumn::make('sort_order')->label('Order')->sortable()->alignCenter(),
                Tables\Columns\ToggleColumn::make('is_active')->label('Active'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('employment_type')->options(JobPosting::employmentTypeOptions()),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
            ])
            ->recordUrl(null)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListJobPostings::route('/'),
            'create' => Pages\CreateJobPosting::route('/create'),
            'edit'   => Pages\EditJobPosting::route('/{record}/edit'),
        ];
    }
}
