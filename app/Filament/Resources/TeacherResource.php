<?php

namespace App\Filament\Resources;

use App\Enums\BloodGroupEnum;
use App\Enums\EmploymentTypeEnum;
use App\Enums\GenderEnum;
use App\Enums\TeacherStatusEnum;
use App\Filament\Resources\TeacherResource\Pages;
use App\Helpers\ValidationHelper;
use App\Models\Department;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Unique;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static ?string $navigationIcon  = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Faculty & Staff';
    protected static ?string $navigationLabel = 'Teachers / Faculty';
    protected static ?int    $navigationSort  = 1;
    protected static ?string $recordTitleAttribute = 'name';

    // ─── Form ────────────────────────────────────────────────────────────────

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Teacher Profile')
                ->tabs([

                    // ── Tab 1: Personal Info ───────────────────────────────────
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
                                        ->placeholder('e.g. Dr. Muhammad Ahmed')
                                        ->extraAttributes(ValidationHelper::nameAttrs(required: true))
                                        ->validationMessages([
                                            'required' => 'Name is required.',
                                            'min'      => 'Name must be at least 3 characters.',
                                        ]),

                                    Forms\Components\TextInput::make('father_name')
                                        ->label("Father's Name")
                                        ->maxLength(100)
                                        ->placeholder('e.g. Haji Muhammad Yusuf')
                                        ->extraAttributes(ValidationHelper::nameAttrs(required: false)),

                                    Forms\Components\TextInput::make('name_urdu')
                                        ->label('Name (Urdu)')
                                        ->maxLength(150)
                                        ->placeholder('ڈاکٹر محمد احمد')
                                        ->extraAttributes(['dir' => 'rtl', 'lang' => 'ur']),

                                    Forms\Components\DatePicker::make('date_of_birth')
                                        ->label('Date of Birth')
                                        ->maxDate(now()->subYears(22))
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
                                        ->unique(table: 'teachers', column: 'cnic',
                                            modifyRuleUsing: fn(Unique $rule, ?Teacher $record) =>
                                                $record ? $rule->ignore($record->id) : $rule
                                        )
                                        ->helperText('Format: 35202-1234567-1')
                                        ->validationMessages(['unique' => 'This CNIC is already registered.']),

                                    Forms\Components\TextInput::make('religion')
                                        ->label('Religion')
                                        ->maxLength(50)
                                        ->placeholder('e.g. Islam'),

                                    Forms\Components\TextInput::make('nationality')
                                        ->label('Nationality')
                                        ->default('Pakistani')
                                        ->maxLength(50),
                                ]),

                            Forms\Components\Section::make('Photo')
                                ->schema([
                                    Forms\Components\FileUpload::make('photo')
                                        ->label('Profile Photo')
                                        ->image()
                                        ->directory('teachers/photos')
                                        ->maxSize(2048)
                                        ->imageResizeMode('cover')
                                        ->imageCropAspectRatio('1:1')
                                        ->imageResizeTargetWidth('300')
                                        ->imageResizeTargetHeight('300')
                                        ->helperText('Max 2MB. Square photo recommended.')
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    // ── Tab 2: Contact & Address ───────────────────────────────
                    Forms\Components\Tabs\Tab::make('Contact')
                        ->icon('heroicon-o-phone')
                        ->schema([

                            Forms\Components\Section::make('Contact Details')
                                ->columns(2)
                                ->schema([

                                    Forms\Components\TextInput::make('email')
                                        ->label('Email Address')
                                        ->email()
                                        ->maxLength(150)
                                        ->placeholder('teacher@college.edu.pk')
                                        ->extraAttributes(ValidationHelper::emailAttrs())
                                        ->unique(table: 'teachers', column: 'email',
                                            modifyRuleUsing: fn(Unique $rule, ?Teacher $record) =>
                                                $record ? $rule->ignore($record->id) : $rule
                                        )
                                        ->validationMessages(['unique' => 'This email is already in use.']),

                                    Forms\Components\TextInput::make('phone')
                                        ->label('Mobile Number')
                                        ->tel()
                                        ->maxLength(20)
                                        ->placeholder('03XX-XXXXXXX')
                                        ->extraAttributes(ValidationHelper::phoneAttrs()),

                                    Forms\Components\TextInput::make('alternative_phone')
                                        ->label('Alternative / Home Phone')
                                        ->tel()
                                        ->maxLength(20)
                                        ->placeholder('042-XXXXXXX')
                                        ->extraAttributes(ValidationHelper::phoneAttrs()),

                                    Forms\Components\TextInput::make('city')
                                        ->label('City')
                                        ->maxLength(80),

                                    Forms\Components\Select::make('province')
                                        ->label('Province')
                                        ->options(fn() => \App\Models\ListItem::getOptions('province'))
                                        ->searchable()
                                        ->placeholder('Select Province'),

                                    Forms\Components\Textarea::make('address')
                                        ->label('Residential Address')
                                        ->rows(2)
                                        ->maxLength(500)
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    // ── Tab 3: Academic & Qualification ───────────────────────
                    Forms\Components\Tabs\Tab::make('Qualification')
                        ->icon('heroicon-o-book-open')
                        ->schema([

                            Forms\Components\Section::make('Highest Academic Qualification')
                                ->columns(2)
                                ->schema([

                                    Forms\Components\Select::make('highest_qualification')
                                        ->label('Highest Degree')
                                        ->options(fn() => \App\Models\ListItem::getOptions('teacher_qualification'))
                                        ->searchable()
                                        ->placeholder('Select Qualification'),

                                    Forms\Components\TextInput::make('specialization')
                                        ->label('Specialization / Subject')
                                        ->maxLength(150)
                                        ->placeholder('e.g. Computer Science, Mathematics'),

                                    Forms\Components\TextInput::make('qualification_institution')
                                        ->label('Awarding Institution')
                                        ->maxLength(200)
                                        ->placeholder('e.g. University of Punjab')
                                        ->columnSpanFull(),

                                    Forms\Components\TextInput::make('qualification_year')
                                        ->label('Year of Award')
                                        ->numeric()
                                        ->minValue(1970)
                                        ->maxValue(now()->year)
                                        ->placeholder(now()->year - 2)
                                        ->extraAttributes(ValidationHelper::numberAttrs(min: 1970, max: now()->year)),
                                ]),
                        ]),

                    // ── Tab 4: Employment ──────────────────────────────────────
                    Forms\Components\Tabs\Tab::make('Employment')
                        ->icon('heroicon-o-briefcase')
                        ->schema([

                            Forms\Components\Section::make('Employment Details')
                                ->columns(2)
                                ->schema([

                                    Forms\Components\TextInput::make('employee_id')
                                        ->label('Employee ID')
                                        ->maxLength(30)
                                        ->placeholder('Auto-generated if blank')
                                        ->unique(table: 'teachers', column: 'employee_id',
                                            modifyRuleUsing: fn(Unique $rule, ?Teacher $record) =>
                                                $record ? $rule->ignore($record->id) : $rule
                                        )
                                        ->helperText('Leave blank to auto-generate (EMP-0001 format).')
                                        ->validationMessages(['unique' => 'This employee ID is already in use.']),

                                    Forms\Components\Select::make('department_id')
                                        ->label('Department')
                                        ->options(fn() => Department::active()->ordered()->pluck('name', 'id'))
                                        ->searchable()
                                        ->preload()
                                        ->placeholder('Select Department'),

                                    Forms\Components\Select::make('designation')
                                        ->label('Designation')
                                        ->options(fn() => \App\Models\ListItem::getOptions('teacher_designation'))
                                        ->searchable()
                                        ->placeholder('Select Designation'),

                                    Forms\Components\Select::make('employment_type')
                                        ->label('Employment Type')
                                        ->options(EmploymentTypeEnum::options())
                                        ->default(EmploymentTypeEnum::Permanent->value)
                                        ->required(),

                                    Forms\Components\TextInput::make('experience_years')
                                        ->label('Total Experience (Years)')
                                        ->numeric()
                                        ->default(0)
                                        ->minValue(0)
                                        ->maxValue(50)
                                        ->extraAttributes(ValidationHelper::numberAttrs(min: 0, max: 50)),

                                    Forms\Components\DatePicker::make('joining_date')
                                        ->label('Joining Date')
                                        ->displayFormat('d M Y')
                                        ->native(false)
                                        ->maxDate(now()),

                                    Forms\Components\DatePicker::make('leaving_date')
                                        ->label('Leaving Date')
                                        ->displayFormat('d M Y')
                                        ->native(false)
                                        ->helperText('Fill only if no longer employed.'),
                                ]),

                            Forms\Components\Section::make('Salary & Grade')
                                ->columns(2)
                                ->schema([

                                    Forms\Components\Select::make('salary_grade')
                                        ->label('BPS / Pay Scale')
                                        ->options(
                                            collect(range(1, 22))
                                                ->mapWithKeys(fn($n) => ["BPS-$n" => "BPS-$n"])
                                                ->all()
                                        )
                                        ->searchable()
                                        ->placeholder('Select BPS Grade'),

                                    Forms\Components\TextInput::make('basic_salary')
                                        ->label('Basic Salary (PKR)')
                                        ->numeric()
                                        ->minValue(0)
                                        ->maxValue(9999999)
                                        ->prefix('Rs.')
                                        ->placeholder('e.g. 50000'),
                                ]),

                            Forms\Components\Section::make('Status')
                                ->columns(2)
                                ->schema([

                                    Forms\Components\Select::make('status')
                                        ->label('Employment Status')
                                        ->options(TeacherStatusEnum::options())
                                        ->default(TeacherStatusEnum::Active->value)
                                        ->required(),

                                    Forms\Components\Toggle::make('is_active')
                                        ->label('Active')
                                        ->default(true)
                                        ->onColor('success'),

                                    Forms\Components\Textarea::make('remarks')
                                        ->label('Remarks / Notes')
                                        ->rows(2)
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
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('employee_id')
                    ->label('Emp. ID')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->description(fn(Teacher $r) => $r->designation ?? '—'),

                Tables\Columns\TextColumn::make('department.name')
                    ->label('Department')
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('employment_type')
                    ->label('Type')
                    ->formatStateUsing(fn($state) => $state instanceof EmploymentTypeEnum ? $state->label() : $state)
                    ->badge()
                    ->color(fn($state) => $state instanceof EmploymentTypeEnum ? $state->color() : 'gray'),

                Tables\Columns\TextColumn::make('highest_qualification')
                    ->label('Qualification')
                    ->placeholder('—')
                    ->description(fn(Teacher $r) => $r->specialization)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('experience_years')
                    ->label('Exp.')
                    ->suffix(' yrs')
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => $state instanceof TeacherStatusEnum ? $state->label() : $state)
                    ->badge()
                    ->color(fn($state) => $state instanceof TeacherStatusEnum ? $state->color() : 'gray'),

                Tables\Columns\TextColumn::make('joining_date')
                    ->label('Joined')
                    ->date('M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordUrl(null)
            ->filters([
                Tables\Filters\SelectFilter::make('department_id')
                    ->label('Department')
                    ->relationship('department', 'name'),

                Tables\Filters\SelectFilter::make('employment_type')
                    ->label('Employment Type')
                    ->options(EmploymentTypeEnum::options()),

                Tables\Filters\SelectFilter::make('status')
                    ->options(TeacherStatusEnum::options()),

                Tables\Filters\SelectFilter::make('designation')
                    ->options(fn() => \App\Models\ListItem::getOptions('teacher_designation')),

                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('changeStatus')
                    ->label('Change Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->iconButton()
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('New Status')
                            ->options(TeacherStatusEnum::options())
                            ->required(),
                        Forms\Components\Textarea::make('remarks')
                            ->label('Reason')
                            ->rows(2),
                    ])
                    ->action(function (Teacher $record, array $data) {
                        $record->update([
                            'status'    => $data['status'],
                            'is_active' => $data['status'] === TeacherStatusEnum::Active->value,
                            'remarks'   => $data['remarks'] ?? $record->remarks,
                        ]);
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
            ->defaultSort('name')
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25)
            ->striped();
    }

    // ─── Pages ───────────────────────────────────────────────────────────────

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'edit'   => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        try {
            return (string) static::getModel()::where('is_active', true)
                ->where('status', TeacherStatusEnum::Active->value)
                ->count() ?: null;
        } catch (\Exception) {
            return null;
        }
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }
}
