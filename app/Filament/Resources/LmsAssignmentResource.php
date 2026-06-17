<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LmsAssignmentResource\Pages;
use App\Models\Course;
use App\Models\LmsAssignment;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LmsAssignmentResource extends Resource
{
    protected static ?string $model = LmsAssignment::class;

    protected static ?string $navigationIcon  = 'heroicon-o-pencil-square';
    protected static ?string $navigationGroup = 'LMS Portal';
    protected static ?string $navigationLabel = 'Assignments';
    protected static ?int    $navigationSort  = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Assignment Details')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('title')->required()->maxLength(200)->columnSpanFull(),

                    Forms\Components\Select::make('course_id')
                        ->label('Course')
                        ->options(fn() => Course::active()->orderBy('code')->get()->mapWithKeys(fn($c) => [$c->id => $c->code . ' — ' . $c->name]))
                        ->searchable()->preload()->required(),

                    Forms\Components\Select::make('teacher_id')
                        ->label('Assigned By (Teacher)')
                        ->options(fn() => Teacher::where('is_active', true)->orderBy('name')->pluck('name', 'id'))
                        ->searchable()->preload(),

                    Forms\Components\TextInput::make('total_marks')->label('Total Marks')->numeric()->default(10)->minValue(1),

                    Forms\Components\DateTimePicker::make('due_datetime')
                        ->label('Submission Deadline')
                        ->displayFormat('d M Y, h:i A')
                        ->native(false),

                    Forms\Components\Select::make('submission_type')
                        ->options(fn() => \App\Models\ListItem::getOptions('lms_submission_type'))
                        ->default('file'),

                    Forms\Components\Toggle::make('allow_late_submission')->label('Allow Late Submission')->default(false)->onColor('warning'),
                    Forms\Components\Toggle::make('is_published')->label('Published')->default(true)->onColor('success'),

                    Forms\Components\FileUpload::make('attachment')->label('Reference File / Question Paper')->directory('lms/assignments')->maxSize(10240)->columnSpanFull(),
                    Forms\Components\Textarea::make('description')->label('Description')->rows(3)->columnSpanFull(),
                    Forms\Components\Textarea::make('instructions')->label('Submission Instructions')->rows(3)->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->wrap()->sortable(),
                Tables\Columns\TextColumn::make('course.code')->label('Course')->badge()->color('primary')->placeholder('—'),
                Tables\Columns\TextColumn::make('teacher.name')->label('Teacher')->placeholder('—')->toggleable(),
                Tables\Columns\TextColumn::make('total_marks')->label('Marks')->suffix(' pts'),
                Tables\Columns\TextColumn::make('due_datetime')->label('Deadline')->dateTime('d M Y, h:i A')->sortable()->placeholder('No deadline'),
                Tables\Columns\IconColumn::make('allow_late_submission')->label('Late Allowed')->boolean()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_published')->label('Published')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course_id')->label('Course')->relationship('course', 'name'),
                Tables\Filters\TernaryFilter::make('is_published')->label('Published'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->defaultSort('due_datetime', 'desc')
            ->paginated([10, 25, 50, 100])
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListLmsAssignments::route('/'),
            'create' => Pages\CreateLmsAssignment::route('/create'),
            'edit'   => Pages\EditLmsAssignment::route('/{record}/edit'),
        ];
    }
}
