<?php

namespace App\Filament\Resources;

use App\Enums\AdmissionTypeEnum;
use App\Enums\BloodGroupEnum;
use App\Enums\GenderEnum;
use App\Enums\StudentStatusEnum;
use App\Filament\Resources\StudentResource\Pages;
use App\Helpers\ValidationHelper;
use App\Models\AcademicProgram;
use App\Models\AcademicYear;
use App\Models\Department;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon  = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Students & Admissions';
    protected static ?string $navigationLabel = 'Students';
    protected static ?int    $navigationSort  = 1;
    protected static ?string $recordTitleAttribute = 'name';

    // ─── Form ────────────────────────────────────────────────────────────────

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Student Profile')
                ->tabs([

                    // ── Tab 1: Personal Information ───────────────────────────
                    Forms\Components\Tabs\Tab::make('Personal Info')
                        ->icon('heroicon-o-user')
                        ->schema([

                            Forms\Components\Section::make('Identity')
                                ->columns(2)
                                ->schema([

                                    Forms\Components\TextInput::make('name')
                                        ->label('Full Name')
                                        ->required()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->placeholder('e.g. Muhammad Ali')
                                        ->extraAttributes(ValidationHelper::nameAttrs(required: true))
                                        ->validationMessages([
                                            'required' => 'Student name is required.',
                                            'min'      => 'Name must be at least 3 characters.',
                                        ]),

                                    Forms\Components\TextInput::make('father_name')
                                        ->label("Father's Name")
                                        ->required()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->placeholder('e.g. Muhammad Hassan')
                                        ->extraAttributes(ValidationHelper::nameAttrs(required: true))
                                        ->validationMessages([
                                            'required' => "Father's name is required.",
                                        ]),

                                    Forms\Components\TextInput::make('name_urdu')
                                        ->label('Name (Urdu)')
                                        ->maxLength(150)
                                        ->placeholder('محمد علی')
                                        ->extraAttributes(['dir' => 'rtl', 'lang' => 'ur']),

                                    Forms\Components\TextInput::make('father_name_urdu')
                                        ->label("Father's Name (Urdu)")
                                        ->maxLength(150)
                                        ->placeholder('محمد حسن')
                                        ->extraAttributes(['dir' => 'rtl', 'lang' => 'ur']),

                                    Forms\Components\TextInput::make('mother_name')
                                        ->label("Mother's Name")
                                        ->maxLength(100)
                                        ->placeholder('e.g. Amina Bibi'),

                                    Forms\Components\DatePicker::make('date_of_birth')
                                        ->label('Date of Birth')
                                        ->maxDate(now()->subYears(14))
                                        ->displayFormat('d M Y')
                                        ->native(false),

                                    Forms\Components\Select::make('gender')
                                        ->label('Gender')
                                        ->options(GenderEnum::options())
                                        ->required()
                                        ->validationMessages(['required' => 'Gender is required.']),

                                    Forms\Components\Select::make('blood_group')
                                        ->label('Blood Group')
                                        ->options(BloodGroupEnum::options())
                                        ->placeholder('Select'),

                                    Forms\Components\TextInput::make('cnic')
                                        ->label('CNIC')
                                        ->maxLength(20)
                                        ->placeholder('35202-1234567-1')
                                        ->extraAttributes(ValidationHelper::cnicAttrs())
                                        ->unique(table: 'students', column: 'cnic',
                                            modifyRuleUsing: fn(\Illuminate\Validation\Rules\Unique $rule, ?Student $record) =>
                                                $record ? $rule->ignore($record->id) : $rule
                                        )
                                        ->helperText('Format: 35202-1234567-1')
                                        ->validationMessages(['unique' => 'This CNIC is already registered.']),

                                    Forms\Components\TextInput::make('father_cnic')
                                        ->label("Father's CNIC")
                                        ->maxLength(20)
                                        ->placeholder('35202-1234567-1')
                                        ->extraAttributes(ValidationHelper::cnicAttrs()),

                                    Forms\Components\TextInput::make('religion')
                                        ->label('Religion')
                                        ->maxLength(50)
                                        ->placeholder('e.g. Islam'),

                                    Forms\Components\TextInput::make('nationality')
                                        ->label('Nationality')
                                        ->maxLength(50)
                                        ->default('Pakistani'),
                                ]),

                            Forms\Components\Section::make('Photo')
                                ->schema([
                                    Forms\Components\FileUpload::make('photo')
                                        ->label('Student Photo')
                                        ->image()
                                        ->directory('students/photos')
                                        ->maxSize(2048)
                                        ->imageResizeMode('cover')
                                        ->imageCropAspectRatio('3:4')
                                        ->imageResizeTargetWidth('300')
                                        ->imageResizeTargetHeight('400')
                                        ->helperText('Max 2MB. Recommended: 3×4 passport size.')
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    // ── Tab 2: Contact & Address ───────────────────────────────
                    Forms\Components\Tabs\Tab::make('Contact & Address')
                        ->icon('heroicon-o-map-pin')
                        ->schema([

                            Forms\Components\Section::make('Contact Information')
                                ->columns(2)
                                ->schema([

                                    Forms\Components\TextInput::make('email')
                                        ->label('Email Address')
                                        ->email()
                                        ->maxLength(150)
                                        ->placeholder('student@example.com')
                                        ->extraAttributes(ValidationHelper::emailAttrs())
                                        ->unique(table: 'students', column: 'email',
                                            modifyRuleUsing: fn(\Illuminate\Validation\Rules\Unique $rule, ?Student $record) =>
                                                $record ? $rule->ignore($record->id) : $rule
                                        )
                                        ->validationMessages(['unique' => 'This email is already registered.']),

                                    Forms\Components\TextInput::make('phone')
                                        ->label('Mobile Number')
                                        ->tel()
                                        ->maxLength(20)
                                        ->placeholder('03XX-XXXXXXX')
                                        ->extraAttributes(ValidationHelper::phoneAttrs()),

                                    Forms\Components\TextInput::make('father_phone')
                                        ->label("Father's Mobile")
                                        ->tel()
                                        ->maxLength(20)
                                        ->placeholder('03XX-XXXXXXX')
                                        ->extraAttributes(ValidationHelper::phoneAttrs()),
                                ]),

                            Forms\Components\Section::make('Current Address')
                                ->columns(2)
                                ->schema([

                                    Forms\Components\Textarea::make('address')
                                        ->label('Current Address')
                                        ->rows(2)
                                        ->maxLength(500)
                                        ->columnSpanFull(),

                                    Forms\Components\TextInput::make('city')
                                        ->label('City')
                                        ->maxLength(80),

                                    Forms\Components\TextInput::make('district')
                                        ->label('District')
                                        ->maxLength(80),

                                    Forms\Components\Select::make('province')
                                        ->label('Province')
                                        ->options(fn() => \App\Models\ListItem::getOptions('province'))
                                        ->searchable()
                                        ->placeholder('Select Province'),

                                    Forms\Components\TextInput::make('domicile')
                                        ->label('Domicile District')
                                        ->maxLength(100)
                                        ->placeholder('e.g. Lahore'),
                                ]),

                            Forms\Components\Section::make('Permanent Address')
                                ->schema([
                                    Forms\Components\Textarea::make('permanent_address')
                                        ->label('Permanent Address')
                                        ->rows(2)
                                        ->maxLength(500)
                                        ->helperText('If different from current address.')
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    // ── Tab 3: Academic Information ────────────────────────────
                    Forms\Components\Tabs\Tab::make('Academic Info')
                        ->icon('heroicon-o-academic-cap')
                        ->schema([

                            Forms\Components\Section::make('Enrollment Details')
                                ->columns(2)
                                ->schema([

                                    Forms\Components\Select::make('department_id')
                                        ->label('Department')
                                        ->options(fn() => Department::active()->ordered()->pluck('name', 'id'))
                                        ->searchable()
                                        ->preload()
                                        ->live()
                                        ->required()
                                        ->placeholder('Select Department')
                                        ->validationMessages(['required' => 'Department is required.']),

                                    Forms\Components\Select::make('academic_program_id')
                                        ->label('Academic Program')
                                        ->options(fn(Forms\Get $get) => AcademicProgram::active()
                                            ->when($get('department_id'), fn($q, $id) => $q->where('department_id', $id))
                                            ->ordered()
                                            ->pluck('name', 'id'))
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->placeholder('Select Program')
                                        ->validationMessages(['required' => 'Program is required.']),

                                    Forms\Components\Select::make('academic_year_id')
                                        ->label('Admission Academic Year')
                                        ->options(fn() => AcademicYear::active()->orderByDesc('start_date')->pluck('name', 'id'))
                                        ->searchable()
                                        ->preload()
                                        ->placeholder('Select Year'),

                                    Forms\Components\TextInput::make('batch_year')
                                        ->label('Batch Year')
                                        ->numeric()
                                        ->minValue(2000)
                                        ->maxValue(2099)
                                        ->default(now()->year)
                                        ->placeholder(now()->year)
                                        ->extraAttributes(ValidationHelper::numberAttrs(min: 2000, max: 2099))
                                        ->helperText('Year of admission e.g. 2024'),

                                    Forms\Components\TextInput::make('registration_number')
                                        ->label('University Reg. No.')
                                        ->maxLength(50)
                                        ->unique(table: 'students', column: 'registration_number',
                                            modifyRuleUsing: fn(\Illuminate\Validation\Rules\Unique $rule, ?Student $record) =>
                                                $record ? $rule->ignore($record->id) : $rule
                                        )
                                        ->placeholder('e.g. 2024-CS-001')
                                        ->helperText('Assigned by the university/board.')
                                        ->validationMessages(['unique' => 'This registration number is already in use.']),

                                    Forms\Components\Select::make('current_semester')
                                        ->label('Current Semester')
                                        ->options(collect(range(1, 8))->mapWithKeys(fn($n) => [$n => "Semester $n"])->all())
                                        ->default(1),

                                    Forms\Components\TextInput::make('section')
                                        ->label('Section')
                                        ->maxLength(10)
                                        ->placeholder('A, B, or Morning')
                                        ->helperText('Optional class section.'),

                                    Forms\Components\DatePicker::make('admission_date')
                                        ->label('Admission Date')
                                        ->displayFormat('d M Y')
                                        ->native(false)
                                        ->default(now()),
                                ]),

                            Forms\Components\Section::make('Admission Type & Status')
                                ->columns(2)
                                ->schema([

                                    Forms\Components\Select::make('admission_type')
                                        ->label('Admission Type')
                                        ->options(AdmissionTypeEnum::options())
                                        ->default(AdmissionTypeEnum::Regular->value)
                                        ->required(),

                                    Forms\Components\Select::make('status')
                                        ->label('Student Status')
                                        ->options(StudentStatusEnum::options())
                                        ->default(StudentStatusEnum::Active->value)
                                        ->required(),

                                    Forms\Components\Toggle::make('is_hosteler')
                                        ->label('Hostel Resident')
                                        ->default(false)
                                        ->onColor('info'),

                                    Forms\Components\Toggle::make('is_active')
                                        ->label('Active')
                                        ->default(true)
                                        ->onColor('success'),
                                ]),
                        ]),

                    // ── Tab 4: Previous Education ──────────────────────────────
                    Forms\Components\Tabs\Tab::make('Previous Education')
                        ->icon('heroicon-o-book-open')
                        ->schema([

                            Forms\Components\Section::make('Last Qualification')
                                ->columns(2)
                                ->schema([

                                    Forms\Components\Select::make('previous_qualification')
                                        ->label('Previous Qualification')
                                        ->options(fn() => \App\Models\ListItem::getOptions('qualification_level'))
                                        ->searchable()
                                        ->placeholder('Select Qualification'),

                                    Forms\Components\TextInput::make('previous_marks')
                                        ->label('Marks / CGPA')
                                        ->numeric()
                                        ->minValue(0)
                                        ->maxValue(1100)
                                        ->step(0.01)
                                        ->placeholder('e.g. 850 or 3.50')
                                        ->helperText('Total marks or CGPA (as applicable).'),

                                    Forms\Components\TextInput::make('previous_board')
                                        ->label('Board / University')
                                        ->maxLength(100)
                                        ->placeholder('e.g. BISE Lahore'),

                                    Forms\Components\TextInput::make('previous_year')
                                        ->label('Passing Year')
                                        ->numeric()
                                        ->minValue(1990)
                                        ->maxValue(now()->year)
                                        ->placeholder(now()->year - 1)
                                        ->extraAttributes(ValidationHelper::numberAttrs(min: 1990, max: now()->year)),
                                ]),
                        ]),

                    // ── Tab 5: Guardian & Remarks ──────────────────────────────
                    Forms\Components\Tabs\Tab::make('Guardian & Remarks')
                        ->icon('heroicon-o-user-group')
                        ->schema([

                            Forms\Components\Section::make('Guardian Information')
                                ->columns(2)
                                ->description('Fill only if guardian is different from father.')
                                ->schema([

                                    Forms\Components\TextInput::make('guardian_name')
                                        ->label('Guardian Name')
                                        ->maxLength(100)
                                        ->placeholder('e.g. Uncle / Paternal Grandfather'),

                                    Forms\Components\TextInput::make('guardian_phone')
                                        ->label('Guardian Mobile')
                                        ->tel()
                                        ->maxLength(20)
                                        ->placeholder('03XX-XXXXXXX')
                                        ->extraAttributes(ValidationHelper::phoneAttrs()),

                                    Forms\Components\TextInput::make('guardian_relation')
                                        ->label('Relation with Student')
                                        ->maxLength(50)
                                        ->placeholder('e.g. Uncle, Grandfather'),
                                ]),

                            Forms\Components\Section::make('Additional Info')
                                ->columns(2)
                                ->schema([

                                    Forms\Components\TextInput::make('disability')
                                        ->label('Disability (if any)')
                                        ->maxLength(100)
                                        ->placeholder('e.g. Visual Impairment'),

                                    Forms\Components\Textarea::make('remarks')
                                        ->label('Remarks / Notes')
                                        ->rows(3)
                                        ->maxLength(1000)
                                        ->columnSpanFull(),
                                ]),
                        ]),

                ])
                ->columnSpanFull()
                ->persistTabInQueryString(),
        ]);
    }

    // ─── Table ───────────────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('')
                    ->circular()
                    ->width(40)
                    ->height(40)
                    ->defaultImageUrl(asset('images/student-placeholder.png'))
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('roll_number')
                    ->label('Roll No.')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Student Name')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->description(fn(Student $r) => $r->father_name ? 'S/O ' . $r->father_name : null),

                Tables\Columns\TextColumn::make('academicProgram.short_name')
                    ->label('Program')
                    ->badge()
                    ->color('info')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('current_semester')
                    ->label('Sem')
                    ->sortable()
                    ->prefix('S')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('batch_year')
                    ->label('Batch')
                    ->sortable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('gender')
                    ->label('Gender')
                    ->formatStateUsing(fn($state) => $state instanceof GenderEnum ? $state->label() : $state)
                    ->badge()
                    ->color(fn($state) => $state instanceof GenderEnum ? $state->color() : 'gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => $state instanceof StudentStatusEnum ? $state->label() : $state)
                    ->badge()
                    ->color(fn($state) => $state instanceof StudentStatusEnum ? $state->color() : 'gray'),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('admission_date')
                    ->label('Admitted')
                    ->date('M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('department_id')
                    ->label('Department')
                    ->relationship('department', 'name'),

                Tables\Filters\SelectFilter::make('academic_program_id')
                    ->label('Program')
                    ->relationship('academicProgram', 'name'),

                Tables\Filters\SelectFilter::make('status')
                    ->options(StudentStatusEnum::options()),

                Tables\Filters\SelectFilter::make('gender')
                    ->options(GenderEnum::options()),

                Tables\Filters\SelectFilter::make('current_semester')
                    ->label('Semester')
                    ->options(collect(range(1, 8))->mapWithKeys(fn($n) => [$n => "Semester $n"])->all()),

                Tables\Filters\SelectFilter::make('batch_year')
                    ->label('Batch Year')
                    ->options(
                        collect(range(now()->year, 2020, -1))
                            ->mapWithKeys(fn($y) => [$y => $y])
                            ->all()
                    ),

                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
                Tables\Filters\TernaryFilter::make('is_hosteler')->label('Hosteler'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('printTranscript')
                    ->label('Transcript')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('info')
                    ->iconButton()
                    ->url(fn(\App\Models\Student $r) => route('pdf.transcript', $r))
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('attendanceReport')
                    ->label('Attendance Report')
                    ->icon('heroicon-o-chart-bar')
                    ->color('success')
                    ->iconButton()
                    ->url(fn(\App\Models\Student $r) => route('pdf.attendance', $r))
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('changeStatus')
                    ->label('Change Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->iconButton()
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('New Status')
                            ->options(StudentStatusEnum::options())
                            ->required(),
                        Forms\Components\Textarea::make('remarks')
                            ->label('Reason / Remarks')
                            ->rows(2)
                            ->maxLength(500),
                    ])
                    ->action(function (Student $record, array $data) {
                        $record->update([
                            'status'    => $data['status'],
                            'is_active' => $data['status'] === StudentStatusEnum::Active->value,
                            'remarks'   => $data['remarks'] ?? $record->remarks,
                        ]);
                    }),

                Tables\Actions\Action::make('setPortalPassword')
                    ->label('Set Portal Password')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->iconButton()
                    ->tooltip('Set student portal password')
                    ->form([
                        Forms\Components\TextInput::make('password')
                            ->label('New Portal Password')
                            ->required()
                            ->minLength(6)
                            ->helperText('Set a password for the student portal.'),
                    ])
                    ->action(function (Student $record, array $data) {
                        $record->update(['portal_password' => $data['password']]);
                        \Filament\Notifications\Notification::make()->success()->title('Portal password updated.')->send();
                    }),

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
            ->defaultSort('roll_number')
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25)
            ->striped();
    }

    // ─── Pages ───────────────────────────────────────────────────────────────

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit'   => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        try {
            return (string) static::getModel()::where('is_active', true)
                ->where('status', StudentStatusEnum::Active->value)
                ->count() ?: null;
        } catch (\Exception) {
            return null;
        }
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
