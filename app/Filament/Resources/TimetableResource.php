<?php
namespace App\Filament\Resources;
use App\Filament\Resources\TimetableResource\Pages;
use App\Models\AcademicProgram;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Timetable;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TimetableResource extends Resource
{
    protected static ?string $model = Timetable::class;
    protected static ?string $navigationIcon  = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Academics';
    protected static ?string $navigationLabel = 'Timetable';
    protected static ?int    $navigationSort  = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Schedule Entry')->columns(2)->schema([
                Forms\Components\Select::make('academic_program_id')
                    ->label('Program')
                    ->options(AcademicProgram::active()->pluck('name','id'))
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('semester')
                    ->label('Semester')
                    ->options(collect(range(1,8))->mapWithKeys(fn($n)=>[$n=>"Semester $n"]))
                    ->required(),
                Forms\Components\Select::make('course_id')
                    ->label('Course')
                    ->options(Course::active()->ordered()->get()->mapWithKeys(fn($c)=>[$c->id=>"({$c->code}) {$c->name}"]))
                    ->required()->searchable(),
                Forms\Components\Select::make('teacher_id')
                    ->label('Teacher')
                    ->options(Teacher::where('is_active',true)->orderBy('name')->pluck('name','id'))
                    ->nullable()->searchable(),
                Forms\Components\Select::make('day_of_week')
                    ->label('Day')
                    ->options(['monday'=>'Monday','tuesday'=>'Tuesday','wednesday'=>'Wednesday','thursday'=>'Thursday','friday'=>'Friday','saturday'=>'Saturday'])
                    ->required(),
                Forms\Components\TextInput::make('room')->label('Room / Hall')->maxLength(50)->nullable(),
                Forms\Components\TimePicker::make('start_time')->label('Start Time')->required()->seconds(false),
                Forms\Components\TimePicker::make('end_time')->label('End Time')->required()->seconds(false),
                Forms\Components\Toggle::make('is_active')->label('Active')->default(true)->onColor('success'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('academicProgram.name')->label('Program')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('semester')->label('Sem')->sortable()->badge(),
                Tables\Columns\TextColumn::make('day_of_week')->label('Day')->formatStateUsing(fn($state)=>ucfirst($state??''))->badge()->color('info'),
                Tables\Columns\TextColumn::make('start_time')->label('Start')->time('H:i'),
                Tables\Columns\TextColumn::make('end_time')->label('End')->time('H:i'),
                Tables\Columns\TextColumn::make('course.code')->label('Course')->searchable(),
                Tables\Columns\TextColumn::make('teacher.name')->label('Teacher')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('room')->label('Room')->toggleable(),
                Tables\Columns\IconColumn::make('is_active')->label('Active')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('academic_program_id')->label('Program')
                    ->options(AcademicProgram::active()->pluck('name','id')),
                Tables\Filters\SelectFilter::make('semester')->options(collect(range(1,8))->mapWithKeys(fn($n)=>[$n=>"Semester $n"])),
                Tables\Filters\SelectFilter::make('day_of_week')
                    ->options(['monday'=>'Monday','tuesday'=>'Tuesday','wednesday'=>'Wednesday','thursday'=>'Thursday','friday'=>'Friday','saturday'=>'Saturday']),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
            ])
            ->headerActions([
                \Filament\Tables\Actions\Action::make('view_public')
                    ->label('View Public Schedule')
                    ->icon('heroicon-o-eye')
                    ->url(fn() => route('timetable'))
                    ->openUrlInNewTab()
                    ->color('info'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->defaultSort('day_of_week')->striped();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTimetables::route('/'),
            'create' => Pages\CreateTimetable::route('/create'),
            'edit'   => Pages\EditTimetable::route('/{record}/edit'),
        ];
    }
}
