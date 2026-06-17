<?php

namespace App\Filament\Resources;

use App\Enums\AttendanceStatusEnum;
use App\Filament\Resources\AttendanceSessionResource\Pages;
use App\Models\AcademicProgram;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AttendanceSessionResource extends Resource
{
    protected static ?string $model = AttendanceSession::class;

    protected static ?string $navigationIcon  = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Academic';
    protected static ?string $navigationLabel = 'Attendance';
    protected static ?int    $navigationSort  = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Session Details')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('course_id')
                        ->label('Course')
                        ->options(fn() => Course::active()->orderBy('code')->get()
                            ->mapWithKeys(fn($c) => [$c->id => $c->code . ' — ' . $c->name]))
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live(),

                    Forms\Components\Select::make('teacher_id')
                        ->label('Teacher')
                        ->options(fn() => Teacher::where('is_active', true)->orderBy('name')->pluck('name', 'id'))
                        ->searchable()
                        ->preload(),

                    Forms\Components\Select::make('academic_program_id')
                        ->label('Program')
                        ->options(fn() => AcademicProgram::active()->ordered()->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\Select::make('semester_number')
                        ->label('Semester')
                        ->options(collect(range(1, 8))->mapWithKeys(fn($n) => [$n => "Semester $n"])->all())
                        ->required(),

                    Forms\Components\DatePicker::make('session_date')
                        ->label('Session Date')
                        ->required()
                        ->default(now())
                        ->maxDate(now())
                        ->displayFormat('d M Y')
                        ->native(false),

                    Forms\Components\TextInput::make('section')->label('Section')->maxLength(10)->placeholder('A / B'),

                    Forms\Components\TimePicker::make('start_time')->label('Start Time'),
                    Forms\Components\TimePicker::make('end_time')->label('End Time'),

                    Forms\Components\TextInput::make('topic_covered')
                        ->label('Topic Covered')
                        ->maxLength(200)
                        ->placeholder('e.g. Chapter 3: OOP Concepts')
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('remarks')->label('Remarks')->rows(2)->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('session_date')->label('Date')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('course.code')->label('Course')->badge()->color('primary'),
                Tables\Columns\TextColumn::make('course.name')->label('Course Name')->wrap()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('academicProgram.short_name')->label('Program')->placeholder('—'),
                Tables\Columns\TextColumn::make('semester_number')->label('Sem')->prefix('S')->placeholder('—'),
                Tables\Columns\TextColumn::make('teacher.name')->label('Teacher')->placeholder('—')->toggleable(),
                Tables\Columns\TextColumn::make('topic_covered')->label('Topic')->wrap()->placeholder('—')->toggleable(),
                Tables\Columns\IconColumn::make('is_locked')->label('Locked')->boolean()->trueColor('danger')->falseColor('gray'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('academic_program_id')->label('Program')->relationship('academicProgram', 'name'),
                Tables\Filters\SelectFilter::make('course_id')->label('Course')->relationship('course', 'name'),
                Tables\Filters\SelectFilter::make('semester_number')->label('Semester')
                    ->options(collect(range(1, 8))->mapWithKeys(fn($n) => [$n => "Semester $n"])->all()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('takeAttendance')
                    ->label('Take Attendance')
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->iconButton()
                    ->visible(fn(AttendanceSession $r) => ! $r->is_locked)
                    ->url(fn(AttendanceSession $r) => static::getUrl('edit', ['record' => $r])),

                Tables\Actions\Action::make('lockSession')
                    ->label('Lock')
                    ->icon('heroicon-o-lock-closed')
                    ->color('warning')
                    ->iconButton()
                    ->requiresConfirmation()
                    ->visible(fn(AttendanceSession $r) => ! $r->is_locked)
                    ->action(fn(AttendanceSession $r) => $r->update(['is_locked' => true])),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->defaultSort('session_date', 'desc')
            ->paginated([10, 25, 50, 100])
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAttendanceSessions::route('/'),
            'create' => Pages\CreateAttendanceSession::route('/create'),
            'edit'   => Pages\EditAttendanceSession::route('/{record}/edit'),
        ];
    }
}
