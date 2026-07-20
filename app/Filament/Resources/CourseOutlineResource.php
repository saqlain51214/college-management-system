<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseOutlineResource\Pages;
use App\Models\AcademicProgram;
use App\Models\CourseOutline;
use App\Models\Department;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CourseOutlineResource extends Resource
{
    protected static ?string $model = CourseOutline::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'College Setup';

    protected static ?string $navigationLabel = 'Course Outlines';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Outline')
                ->schema([
                    Forms\Components\Select::make('department_id')
                        ->label('Department')->required()->searchable()->live()
                        ->options(fn () => Department::orderBy('name')->pluck('name', 'id')),
                    Forms\Components\Select::make('academic_program_id')
                        ->label('Programme (optional)')->searchable()
                        ->options(fn (Forms\Get $get) => AcademicProgram::query()
                            ->when($get('department_id'), fn ($q, $d) => $q->where('department_id', $d))
                            ->orderBy('name')->pluck('name', 'id')),
                    Forms\Components\Select::make('semester_number')
                        ->label('Semester')
                        ->options(collect(range(1, 8))->mapWithKeys(fn ($n) => [$n => "Semester $n"])->all())
                        ->placeholder('All / Not specific'),
                    Forms\Components\TextInput::make('title')
                        ->label('Title')->required()->maxLength(200)
                        ->placeholder('e.g. Semester 1 — Course Outline'),
                    Forms\Components\FileUpload::make('file_path')
                        ->label('Upload PDF')->disk('public')->directory('course-outlines')
                        ->acceptedFileTypes(['application/pdf'])->maxSize(20480)
                        ->helperText('Upload the outline as a PDF (up to 20 MB). Or use a link below instead.')
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('external_url')
                        ->label('…or External Link (optional)')->url()->maxLength(500)
                        ->placeholder('https://...')->columnSpanFull(),
                    Forms\Components\Textarea::make('description')
                        ->label('Short Description (optional)')->rows(2)->maxLength(500)->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Display')
                ->schema([
                    Forms\Components\TextInput::make('sort_order')->label('Order')->numeric()->default(0),
                    Forms\Components\Toggle::make('is_active')->label('Show on website')->default(true),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('department_id')
            ->groups([
                Tables\Grouping\Group::make('department.name')->label('Department'),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('department.name')->label('Department')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('semester_number')->label('Sem')
                    ->formatStateUsing(fn ($state) => $state ? "S{$state}" : '—')->alignCenter(),
                Tables\Columns\TextColumn::make('title')->label('Title')->searchable()->wrap(),
                Tables\Columns\IconColumn::make('file_path')->label('PDF')->boolean()
                    ->trueIcon('heroicon-o-document-arrow-down')->falseIcon('heroicon-o-link'),
                Tables\Columns\ToggleColumn::make('is_active')->label('Active'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('department_id')->label('Department')
                    ->relationship('department', 'name'),
                Tables\Filters\SelectFilter::make('semester_number')->label('Semester')
                    ->options(collect(range(1, 8))->mapWithKeys(fn ($n) => [$n => "Semester $n"])->all()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCourseOutlines::route('/'),
            'create' => Pages\CreateCourseOutline::route('/create'),
            'edit'   => Pages\EditCourseOutline::route('/{record}/edit'),
        ];
    }
}
