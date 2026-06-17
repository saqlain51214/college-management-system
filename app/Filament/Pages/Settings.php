<?php

namespace App\Filament\Pages;

use App\Models\CollegeSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Settings';
    protected static ?string $title           = 'College Settings';
    protected static ?string $navigationGroup = 'System';
    protected static ?int    $navigationSort  = 10;

    protected static string $view = 'filament.pages.settings';

    public array $data = [];

    public function mount(): void
    {
        $settings = CollegeSetting::all()->pluck('value', 'key')->toArray();
        $this->form->fill($settings);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('College Information')
                    ->icon('heroicon-o-building-office-2')
                    ->columns(2)
                    ->schema([
                        TextInput::make('college_name')
                            ->label('College Name (English)')
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('college_name_urdu')
                            ->label('College Name (Urdu)')
                            ->columnSpan(1),

                        TextInput::make('college_short_name')
                            ->label('Short Name / Abbreviation')
                            ->columnSpan(1),

                        TextInput::make('college_principal')
                            ->label('Principal Name')
                            ->columnSpan(1),

                        Textarea::make('college_address')
                            ->label('Address')
                            ->rows(2)
                            ->columnSpanFull(),

                        TextInput::make('college_city')
                            ->label('City')
                            ->columnSpan(1),

                        TextInput::make('college_phone')
                            ->label('Phone')
                            ->tel()
                            ->columnSpan(1),

                        TextInput::make('college_email')
                            ->label('Email')
                            ->email()
                            ->columnSpan(1),

                        TextInput::make('college_website')
                            ->label('Website')
                            ->url()
                            ->columnSpan(1),

                        TextInput::make('college_established')
                            ->label('Established Year')
                            ->columnSpan(1),

                        TextInput::make('college_affiliation')
                            ->label('Affiliated University')
                            ->columnSpan(1),

                        TextInput::make('college_accreditation')
                            ->label('Accreditation / Certifications')
                            ->columnSpanFull(),

                        FileUpload::make('college_logo')
                            ->label('College Logo')
                            ->image()
                            ->disk('public')
                            ->directory('college')
                            ->maxSize(1024)
                            ->imageResizeMode('contain')
                            ->imageResizeTargetWidth('400')
                            ->imageResizeTargetHeight('120')
                            ->helperText('Recommended: transparent PNG, max 400×120px, 1MB.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Academic Settings')
                    ->icon('heroicon-o-academic-cap')
                    ->columns(2)
                    ->schema([
                        TextInput::make('current_academic_year')
                            ->label('Current Academic Year')
                            ->placeholder('2024-2025')
                            ->columnSpan(1),

                        TextInput::make('current_semester')
                            ->label('Current Semester')
                            ->placeholder('Fall 2024')
                            ->columnSpan(1),

                        TextInput::make('attendance_min_percent')
                            ->label('Minimum Attendance %')
                            ->numeric()
                            ->suffix('%')
                            ->columnSpan(1),

                        TextInput::make('passing_marks_percent')
                            ->label('Passing Marks %')
                            ->numeric()
                            ->suffix('%')
                            ->columnSpan(1),

                        TextInput::make('max_exam_marks')
                            ->label('Maximum Exam Marks')
                            ->numeric()
                            ->columnSpan(1),

                        TextInput::make('working_days_per_week')
                            ->label('Working Days Per Week')
                            ->numeric()
                            ->columnSpan(1),
                    ]),

                Section::make('Fee Settings')
                    ->icon('heroicon-o-banknotes')
                    ->columns(2)
                    ->schema([
                        TextInput::make('fee_late_fine_per_day')
                            ->label('Late Fine Per Day (PKR)')
                            ->numeric()
                            ->prefix('PKR')
                            ->columnSpan(1),

                        TextInput::make('fee_grace_days')
                            ->label('Grace Days (no fine)')
                            ->numeric()
                            ->suffix('days')
                            ->columnSpan(1),

                        TextInput::make('fee_bank_name')
                            ->label('Bank Name')
                            ->columnSpan(1),

                        TextInput::make('fee_bank_account')
                            ->label('Bank Account Number')
                            ->columnSpan(1),

                        TextInput::make('fee_bank_branch')
                            ->label('Bank Branch')
                            ->columnSpanFull(),
                    ]),

                Section::make('Library Settings')
                    ->icon('heroicon-o-book-open')
                    ->columns(2)
                    ->schema([
                        TextInput::make('library_issue_days_student')
                            ->label('Issue Period — Students')
                            ->numeric()
                            ->suffix('days')
                            ->columnSpan(1),

                        TextInput::make('library_issue_days_teacher')
                            ->label('Issue Period — Teachers')
                            ->numeric()
                            ->suffix('days')
                            ->columnSpan(1),

                        TextInput::make('library_fine_per_day')
                            ->label('Late Return Fine Per Day (PKR)')
                            ->numeric()
                            ->prefix('PKR')
                            ->columnSpan(1),

                        TextInput::make('library_max_books_student')
                            ->label('Max Books — Students')
                            ->numeric()
                            ->columnSpan(1),

                        TextInput::make('library_max_books_teacher')
                            ->label('Max Books — Teachers')
                            ->numeric()
                            ->columnSpan(1),
                    ]),

                Section::make('System Settings')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->columns(2)
                    ->schema([
                        Select::make('system_timezone')
                            ->label('Timezone')
                            ->options([
                                'Asia/Karachi'   => 'Asia/Karachi (PKT +5:00)',
                                'Asia/Kolkata'   => 'Asia/Kolkata (IST +5:30)',
                                'UTC'            => 'UTC',
                            ])
                            ->columnSpan(1),

                        Select::make('system_date_format')
                            ->label('Date Format')
                            ->options([
                                'd/m/Y'  => '31/12/2024 (DD/MM/YYYY)',
                                'm/d/Y'  => '12/31/2024 (MM/DD/YYYY)',
                                'Y-m-d'  => '2024-12-31 (YYYY-MM-DD)',
                                'd-M-Y'  => '31-Dec-2024',
                            ])
                            ->columnSpan(1),

                        Select::make('system_currency')
                            ->label('Currency')
                            ->options(['PKR' => 'PKR — Pakistani Rupee', 'USD' => 'USD', 'GBP' => 'GBP'])
                            ->columnSpan(1),

                        Select::make('system_language')
                            ->label('Default Language')
                            ->options(['en' => 'English', 'ur' => 'Urdu'])
                            ->columnSpan(1),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->icon('heroicon-o-check')
                ->action('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $groups = [
            'college_name'               => 'college',
            'college_name_urdu'          => 'college',
            'college_short_name'         => 'college',
            'college_principal'          => 'college',
            'college_address'            => 'college',
            'college_city'               => 'college',
            'college_phone'              => 'college',
            'college_email'              => 'college',
            'college_website'            => 'college',
            'college_established'        => 'college',
            'college_affiliation'        => 'college',
            'college_accreditation'      => 'college',
            'college_logo'               => 'college',
            'current_academic_year'      => 'academic',
            'current_semester'           => 'academic',
            'attendance_min_percent'     => 'academic',
            'passing_marks_percent'      => 'academic',
            'max_exam_marks'             => 'academic',
            'working_days_per_week'      => 'academic',
            'fee_late_fine_per_day'      => 'fee',
            'fee_grace_days'             => 'fee',
            'fee_bank_name'              => 'fee',
            'fee_bank_account'           => 'fee',
            'fee_bank_branch'            => 'fee',
            'library_issue_days_student' => 'library',
            'library_issue_days_teacher' => 'library',
            'library_fine_per_day'       => 'library',
            'library_max_books_student'  => 'library',
            'library_max_books_teacher'  => 'library',
            'system_timezone'            => 'system',
            'system_date_format'         => 'system',
            'system_currency'            => 'system',
            'system_language'            => 'system',
        ];

        foreach ($data as $key => $value) {
            CollegeSetting::set($key, $value, $groups[$key] ?? 'general');
        }

        Cache::flush();

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }
}
