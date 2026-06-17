<?php

namespace App\Filament\Resources;

use App\Enums\ExamTypeEnum;
use App\Filament\Resources\ExamResource\Pages;
use App\Models\AcademicProgram;
use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Exam;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ExamResource extends Resource
{
    protected static ?string $model = Exam::class;

    protected static ?string $navigationIcon  = 'heroicon-o-document-check';
    protected static ?string $navigationGroup = 'Academic';
    protected static ?string $navigationLabel = 'Examinations';
    protected static ?int    $navigationSort  = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Exam Details')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Exam Title')
                        ->required()
                        ->maxLength(150)
                        ->placeholder('e.g. CS-302 Midterm Exam — Fall 2024')
                        ->columnSpanFull(),

                    Forms\Components\Select::make('exam_type')
                        ->label('Exam Type')
                        ->options(ExamTypeEnum::options())
                        ->required()
                        ->default(ExamTypeEnum::Midterm->value),

                    Forms\Components\Select::make('course_id')
                        ->label('Course')
                        ->options(fn() => Course::active()->orderBy('code')->get()
                            ->mapWithKeys(fn($c) => [$c->id => $c->code . ' — ' . $c->name]))
                        ->searchable()
                        ->preload(),

                    Forms\Components\Select::make('academic_program_id')
                        ->label('Program')
                        ->options(fn() => AcademicProgram::active()->ordered()->pluck('name', 'id'))
                        ->searchable()
                        ->preload(),

                    Forms\Components\Select::make('academic_year_id')
                        ->label('Academic Year')
                        ->options(fn() => AcademicYear::active()->orderByDesc('start_date')->pluck('name', 'id'))
                        ->searchable(),

                    Forms\Components\Select::make('semester_number')
                        ->label('Semester')
                        ->options(collect(range(1, 8))->mapWithKeys(fn($n) => [$n => "Semester $n"])->all()),

                    Forms\Components\DatePicker::make('exam_date')->label('Exam Date')->displayFormat('d M Y')->native(false),
                    Forms\Components\TimePicker::make('start_time')->label('Start Time'),
                    Forms\Components\TextInput::make('duration_minutes')->label('Duration (Min)')->numeric()->minValue(15)->placeholder('e.g. 120'),
                    Forms\Components\TextInput::make('venue')->label('Venue / Room')->maxLength(150)->placeholder('e.g. Room 201, Block A'),
                    Forms\Components\TextInput::make('total_marks')->label('Total Marks')->numeric()->required()->default(100)->minValue(1),
                    Forms\Components\TextInput::make('passing_marks')->label('Passing Marks')->numeric()->default(50)->minValue(0),
                    Forms\Components\TextInput::make('weightage_percent')->label('Weightage %')->numeric()->minValue(0)->maxValue(100)->placeholder('e.g. 30'),

                    Forms\Components\Toggle::make('is_published')->label('Published (visible to students)')->default(false)->onColor('success'),
                    Forms\Components\Toggle::make('results_published')->label('Results Published')->default(false)->onColor('success'),

                    Forms\Components\Textarea::make('instructions')->label('Exam Instructions')->rows(3)->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->wrap()->sortable(),
                Tables\Columns\TextColumn::make('exam_type')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state instanceof ExamTypeEnum ? $state->label() : $state)
                    ->color(fn($state) => $state instanceof ExamTypeEnum ? $state->color() : 'gray'),
                Tables\Columns\TextColumn::make('course.code')->label('Course')->badge()->color('gray')->placeholder('—'),
                Tables\Columns\TextColumn::make('academicProgram.short_name')->label('Program')->placeholder('—'),
                Tables\Columns\TextColumn::make('semester_number')->label('Sem')->prefix('S')->placeholder('—'),
                Tables\Columns\TextColumn::make('exam_date')->label('Date')->date('d M Y')->sortable()->placeholder('TBD'),
                Tables\Columns\TextColumn::make('total_marks')->label('Total')->suffix(' marks'),
                Tables\Columns\IconColumn::make('is_published')->label('Published')->boolean(),
                Tables\Columns\IconColumn::make('results_published')->label('Results')->boolean()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('exam_type')->options(ExamTypeEnum::options()),
                Tables\Filters\SelectFilter::make('academic_program_id')->label('Program')->relationship('academicProgram', 'name'),
                Tables\Filters\TernaryFilter::make('is_published')->label('Published'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('resultSheet')
                    ->label('Result Sheet')
                    ->icon('heroicon-o-document-chart-bar')
                    ->color('success')
                    ->iconButton()
                    ->url(fn(\App\Models\Exam $record) => route('pdf.exam-results', $record))
                    ->openUrlInNewTab()
                    ->visible(fn(\App\Models\Exam $record) => $record->results_published),
                Tables\Actions\Action::make('publish')
                    ->label('Publish')
                    ->icon('heroicon-o-eye')
                    ->color('success')
                    ->iconButton()
                    ->requiresConfirmation()
                    ->visible(fn(Exam $r) => ! $r->is_published)
                    ->action(fn(Exam $r) => $r->update(['is_published' => true])),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->defaultSort('exam_date', 'desc')
            ->paginated([10, 25, 50, 100])
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListExams::route('/'),
            'create' => Pages\CreateExam::route('/create'),
            'edit'   => Pages\EditExam::route('/{record}/edit'),
        ];
    }
}
