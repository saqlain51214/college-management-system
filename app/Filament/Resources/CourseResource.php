<?php

namespace App\Filament\Resources;

use App\Enums\CourseTypeEnum;
use App\Filament\Resources\CourseResource\Pages;
use App\Helpers\ValidationHelper;
use App\Models\AcademicProgram;
use App\Models\Course;
use App\Models\Department;
use App\Services\CourseService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon  = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'College Setup';
    protected static ?string $navigationLabel = 'Courses';
    protected static ?int    $navigationSort  = 4;

    // ─── Form ────────────────────────────────────────────────────────────────

    public static function form(Form $form): Form
    {
        return $form->schema([

            // ── Section 1: Identity ───────────────────────────────────────────
            Forms\Components\Section::make('Course Identity')
                ->columns(2)
                ->schema([

                    Forms\Components\TextInput::make('name')
                        ->label('Course Name')
                        ->placeholder('e.g. Introduction to Programming')
                        ->required()
                        ->minLength(3)
                        ->maxLength(150)
                        ->unique(
                            table: 'courses', column: 'name',
                            modifyRuleUsing: fn(Unique $rule, ?Course $record) => $record ? $rule->ignore($record->id) : $rule
                        )
                        ->live(onBlur: true)
                        ->extraAttributes(ValidationHelper::textAttrs(min: 3, max: 150, required: true))
                        ->afterStateUpdated(fn($state, Forms\Set $set) => $set('slug', Str::slug($state)))
                        ->validationMessages([
                            'required' => 'Course name is required.',
                            'min'      => 'Name must be at least 3 characters.',
                            'max'      => 'Name cannot exceed 150 characters.',
                            'unique'   => 'A course with this name already exists.',
                        ])
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('code')
                        ->label('Course Code')
                        ->placeholder('CS-101')
                        ->required()
                        ->minLength(2)
                        ->maxLength(30)
                        ->unique(
                            table: 'courses', column: 'code',
                            modifyRuleUsing: fn(Unique $rule, ?Course $record) => $record ? $rule->ignore($record->id) : $rule
                        )
                        ->regex('/^[A-Z0-9\-]+$/')
                        ->live(onBlur: true)
                        ->extraAttributes(ValidationHelper::codeAttrs(required: true))
                        ->dehydrateStateUsing(fn($state) => strtoupper($state))
                        ->helperText('Uppercase letters, numbers, hyphens. e.g. CS-101, MATH-201')
                        ->validationMessages([
                            'required' => 'Course code is required.',
                            'unique'   => 'This course code is already in use.',
                            'regex'    => 'Code must be uppercase letters, numbers and hyphens only.',
                        ]),

                    Forms\Components\TextInput::make('name_urdu')
                        ->label('Course Name (Urdu)')
                        ->maxLength(200)
                        ->extraAttributes(ValidationHelper::textAttrs(max: 200))
                        ->helperText('Optional'),

                    Forms\Components\TextInput::make('slug')
                        ->label('URL Slug')
                        ->maxLength(150)
                        ->unique(
                            table: 'courses', column: 'slug',
                            modifyRuleUsing: fn(Unique $rule, ?Course $record) => $record ? $rule->ignore($record->id) : $rule
                        )
                        ->regex('/^[a-z0-9\-]+$/')
                        ->live(onBlur: true)
                        ->extraAttributes(ValidationHelper::slugAttrs())
                        ->helperText('Auto-generated from name.')
                        ->validationMessages([
                            'unique' => 'This slug is already taken.',
                            'regex'  => 'Lowercase letters, numbers, hyphens only.',
                        ]),
                ]),

            // ── Section 2: Classification ─────────────────────────────────────
            Forms\Components\Section::make('Classification')
                ->columns(2)
                ->schema([

                    Forms\Components\Select::make('department_id')
                        ->label('Department')
                        ->options(fn() => Department::active()->ordered()->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->live()
                        ->placeholder('Select Department'),

                    Forms\Components\Select::make('academic_program_id')
                        ->label('Academic Program')
                        ->options(fn(Forms\Get $get) => AcademicProgram::active()
                            ->when($get('department_id'), fn($q, $id) => $q->where('department_id', $id))
                            ->ordered()
                            ->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->placeholder('Select Program (optional)'),

                    Forms\Components\Select::make('course_type')
                        ->label('Course Type')
                        ->options(CourseTypeEnum::options())
                        ->required()
                        ->default(CourseTypeEnum::Core->value)
                        ->validationMessages(['required' => 'Course type is required.']),

                    Forms\Components\TextInput::make('semester_number')
                        ->label('Semester #')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(12)
                        ->placeholder('e.g. 1')
                        ->extraAttributes(ValidationHelper::numberAttrs(min: 1, max: 12))
                        ->helperText('Which semester this course is taught in.'),
                ]),

            // ── Section 3: Credit Hours ───────────────────────────────────────
            Forms\Components\Section::make('Credit Hours (HEC Standard)')
                ->columns(4)
                ->description('HEC: 1 credit = 1 lecture/week for 17 weeks. Lab: 3 hours = 1 credit.')
                ->schema([

                    Forms\Components\TextInput::make('credit_hours')
                        ->label('Total Credit Hours')
                        ->numeric()
                        ->required()
                        ->default(3)
                        ->minValue(0.5)
                        ->maxValue(9)
                        ->step(0.5)
                        ->extraAttributes(ValidationHelper::numberAttrs(min: 1, max: 9, required: true))
                        ->validationMessages([
                            'required' => 'Credit hours are required.',
                            'min'      => 'Minimum 0.5 credit hours.',
                            'max'      => 'Maximum 9 credit hours.',
                        ]),

                    Forms\Components\TextInput::make('theory_hours')
                        ->label('Theory Hours')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(6)
                        ->step(0.5)
                        ->placeholder('e.g. 3')
                        ->helperText('Lecture credit hours'),

                    Forms\Components\TextInput::make('lab_hours')
                        ->label('Lab Hours')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(6)
                        ->step(0.5)
                        ->placeholder('e.g. 1')
                        ->helperText('Lab credit hours'),

                    Forms\Components\TextInput::make('contact_hours_per_week')
                        ->label('Contact Hrs/Week')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(20)
                        ->placeholder('e.g. 4')
                        ->helperText('Total classroom contact'),
                ]),

            // ── Section 4: Course Content ─────────────────────────────────────
            Forms\Components\Section::make('Course Content')
                ->schema([

                    Forms\Components\Textarea::make('description')
                        ->label('Course Description')
                        ->rows(3)
                        ->maxLength(2000)
                        ->extraAttributes(ValidationHelper::textAttrs(max: 2000))
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('objectives')
                        ->label('Course Objectives')
                        ->rows(3)
                        ->maxLength(2000)
                        ->extraAttributes(ValidationHelper::textAttrs(max: 2000))
                        ->helperText('What will students learn?')
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('outcomes')
                        ->label('Course Learning Outcomes (CLOs)')
                        ->rows(3)
                        ->maxLength(2000)
                        ->extraAttributes(ValidationHelper::textAttrs(max: 2000))
                        ->helperText('Measurable skills after completing this course.')
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('pre_requisites')
                        ->label('Pre-requisites')
                        ->rows(2)
                        ->maxLength(500)
                        ->placeholder('e.g. CS-101, MATH-101')
                        ->extraAttributes(ValidationHelper::textAttrs(max: 500))
                        ->helperText('Course codes of prerequisite courses.')
                        ->columnSpanFull(),
                ]),

            // ── Section 5: Settings ───────────────────────────────────────────
            Forms\Components\Section::make('Settings')
                ->columns(3)
                ->schema([

                    Forms\Components\TextInput::make('sort_order')
                        ->label('Sort Order')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->maxValue(999)
                        ->extraAttributes(ValidationHelper::numberAttrs(min: 0, max: 999)),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true)
                        ->onColor('success'),

                    Forms\Components\Toggle::make('show_on_website')
                        ->label('Show on Website')
                        ->default(false)
                        ->onColor('success')
                        ->helperText('Show course catalog on public website.'),
                ]),
        ]);
    }

    // ─── Table ───────────────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Course Name')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->description(fn(Course $r) => $r->academicProgram?->short_name ?? $r->department?->code ?? '—'),

                Tables\Columns\TextColumn::make('course_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state instanceof CourseTypeEnum ? $state->label() : $state)
                    ->color(fn($state) => $state instanceof CourseTypeEnum ? $state->color() : 'gray'),

                Tables\Columns\TextColumn::make('credit_hours')
                    ->label('Credits')
                    ->sortable()
                    ->suffix(' cr')
                    ->description(fn(Course $r) => $r->credit_summary),

                Tables\Columns\TextColumn::make('semester_number')
                    ->label('Sem')
                    ->sortable()
                    ->placeholder('—')
                    ->prefix('S'),

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
            ->filters([
                Tables\Filters\SelectFilter::make('department_id')
                    ->label('Department')
                    ->relationship('department', 'name'),

                Tables\Filters\SelectFilter::make('academic_program_id')
                    ->label('Program')
                    ->relationship('academicProgram', 'name'),

                Tables\Filters\SelectFilter::make('course_type')
                    ->options(CourseTypeEnum::options()),

                Tables\Filters\SelectFilter::make('semester_number')
                    ->label('Semester')
                    ->options(collect(range(1, 8))->mapWithKeys(fn($n) => [$n => "Semester $n"])->all()),

                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('toggleStatus')
                    ->label(fn(Course $r) => $r->is_active ? 'Deactivate' : 'Activate')
                    ->icon(fn(Course $r) => $r->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn(Course $r) => $r->is_active ? 'danger' : 'success')
                    ->iconButton()
                    ->requiresConfirmation()
                    ->action(fn(Course $r) => $r->update(['is_active' => ! $r->is_active])),

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
            ->modifyQueryUsing(fn(\Illuminate\Database\Eloquent\Builder $query) => $query->orderBy('semester_number')->orderBy('code'))
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25)
            ->striped();
    }

    // ─── Pages ───────────────────────────────────────────────────────────────

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit'   => Pages\EditCourse::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        try {
            return (string) static::getModel()::where('is_active', true)->count() ?: null;
        } catch (\Exception) {
            return null;
        }
    }
}
