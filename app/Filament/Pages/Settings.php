<?php

namespace App\Filament\Pages;

use App\Models\CollegeSetting;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
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
use Illuminate\Support\Str;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;
    use HasPageShield;

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
                            ->maxSize(5120)
                            ->imageResizeMode('contain')
                            ->imageResizeTargetWidth('400')
                            ->imageResizeTargetHeight('120')
                            ->helperText('Recommended: transparent PNG, max 400×120px, 5MB.')
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
                            ->placeholder('e.g. HBL, UBL')
                            ->columnSpan(1),

                        TextInput::make('fee_bank_account')
                            ->label('Bank Account Number')
                            ->columnSpan(1),

                        TextInput::make('fee_bank_account_title')
                            ->label('Account Title')
                            ->placeholder('Jinnah Degree College Astore')
                            ->columnSpan(1),

                        TextInput::make('fee_bank_branch')
                            ->label('Bank Branch / Code')
                            ->columnSpan(1),

                        TextInput::make('fee_challan_1bill_prefix')
                            ->label('1-Bill Consumer Number Prefix')
                            ->helperText('Digits prepended to the challan SN for 1-Bill payment. Leave blank if not applicable.')
                            ->columnSpan(1),

                        TextInput::make('fee_challan_ref_prefix')
                            ->label('Reference Number Prefix')
                            ->placeholder('e.g. JDCA-2024')
                            ->helperText('Prepended to the challan number on printed vouchers.')
                            ->columnSpan(1),
                    ]),

                Section::make('Website Appearance')
                    ->icon('heroicon-o-paint-brush')
                    ->columns(2)
                    ->schema([
                        Placeholder::make('appearance_current_summary')
                            ->label('Current Active Style')
                            ->content(function (): string {
                                $state = $this->form->getRawState();

                                return sprintf(
                                    'Brand %s, Dark %s, Accent %s, Footer %s, Body %s, Surface %s, Body Font %s, Display Font %s',
                                    $this->normalizeSelectableSetting($state['website_theme_brand'] ?? '#6B2D39'),
                                    $this->normalizeSelectableSetting($state['website_theme_brand_dark'] ?? '#5A2430'),
                                    $this->normalizeSelectableSetting($state['website_theme_gold'] ?? '#C4973A'),
                                    $this->normalizeSelectableSetting($state['website_theme_footer_bg'] ?? '#1A1A1A'),
                                    $this->normalizeSelectableSetting($state['website_theme_body_bg'] ?? '#F8FAFC'),
                                    $this->normalizeSelectableSetting($state['website_theme_surface'] ?? '#F1F5F9'),
                                    $this->normalizeSelectableSetting($state['website_font_sans'] ?? 'open-sans'),
                                    $this->normalizeSelectableSetting($state['website_font_display'] ?? 'playfair-display')
                                );
                            })
                            ->columnSpanFull(),
                        Textarea::make('website_footer_about')
                            ->label('Footer About Text')
                            ->rows(3)
                            ->columnSpanFull(),
                        TextInput::make('website_footer_copyright')
                            ->label('Footer Copyright Text')
                            ->columnSpanFull(),
                        Select::make('website_theme_brand')
                            ->label('Brand Color')
                            ->options($this->withCurrentOption($this->themeColorOptions(), CollegeSetting::get('website_theme_brand', '#6B2D39')))
                            ->searchable()
                            ->native(false)
                            ->helperText('Preset list includes the current live value so it is not lost.'),
                        Select::make('website_theme_brand_dark')
                            ->label('Brand Dark Color')
                            ->options($this->withCurrentOption($this->themeColorOptions(), CollegeSetting::get('website_theme_brand_dark', '#5A2430')))
                            ->searchable()
                            ->native(false),
                        Select::make('website_theme_gold')
                            ->label('Accent Color')
                            ->options($this->withCurrentOption($this->accentColorOptions(), CollegeSetting::get('website_theme_gold', '#C4973A')))
                            ->searchable()
                            ->native(false),
                        Select::make('website_theme_footer_bg')
                            ->label('Footer Background')
                            ->options($this->withCurrentOption($this->footerColorOptions(), CollegeSetting::get('website_theme_footer_bg', '#1A1A1A')))
                            ->searchable()
                            ->native(false),
                        Select::make('website_theme_body_bg')
                            ->label('Global Body Background')
                            ->options($this->withCurrentOption($this->backgroundColorOptions(), CollegeSetting::get('website_theme_body_bg', '#F8FAFC')))
                            ->searchable()
                            ->native(false),
                        Select::make('website_theme_surface')
                            ->label('Global Surface Background')
                            ->options($this->withCurrentOption($this->surfaceColorOptions(), CollegeSetting::get('website_theme_surface', '#F1F5F9')))
                            ->searchable()
                            ->native(false),
                        Select::make('website_font_sans')
                            ->label('Body Font')
                            ->options($this->withCurrentOption($this->bodyFontOptions(), CollegeSetting::get('website_font_sans', 'open-sans')))
                            ->searchable()
                            ->native(false),
                        Select::make('website_font_display')
                            ->label('Heading Font')
                            ->options($this->withCurrentOption($this->displayFontOptions(), CollegeSetting::get('website_font_display', 'playfair-display')))
                            ->searchable()
                            ->native(false),
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

        foreach ([
            'website_theme_brand',
            'website_theme_brand_dark',
            'website_theme_gold',
            'website_theme_footer_bg',
            'website_theme_body_bg',
            'website_theme_surface',
            'website_font_sans',
            'website_font_display',
        ] as $key) {
            if (array_key_exists($key, $data)) {
                $data[$key] = $this->normalizeSelectableSetting($data[$key]);
            }
        }

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
            'website_footer_about'       => 'website',
            'website_footer_copyright'   => 'website',
            'website_theme_brand'        => 'website',
            'website_theme_brand_dark'   => 'website',
            'website_theme_gold'         => 'website',
            'website_theme_footer_bg'    => 'website',
            'website_theme_body_bg'      => 'website',
            'website_theme_surface'      => 'website',
            'website_font_sans'          => 'website',
            'website_font_display'       => 'website',
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
            if ($key === 'college_logo' && blank($value)) {
                continue;
            }
            CollegeSetting::set($key, $value, $groups[$key] ?? 'general');
        }

        Cache::flush();

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }

    protected function withCurrentOption(array $options, ?string $current): array
    {
        $current = $this->normalizeSelectableSetting($current);

        if (blank($current) || array_key_exists($current, $options)) {
            return $options;
        }

        return ['current:' . $current => 'Current (' . $current . ')'] + $options;
    }

    protected function normalizeSelectableSetting(null|string|int|float $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = (string) $value;

        return Str::startsWith($value, 'current:')
            ? Str::after($value, 'current:')
            : $value;
    }

    protected function themeColorOptions(): array
    {
        return [
            '#6B2D39' => 'JDCA Maroon',
            '#1D4ED8' => 'Royal Blue',
            '#166534' => 'Academic Green',
            '#7C3AED' => 'Deep Violet',
        ];
    }

    protected function accentColorOptions(): array
    {
        return [
            '#C4973A' => 'Classic Gold',
            '#D97706' => 'Warm Amber',
            '#0F766E' => 'Teal Accent',
            '#BE185D' => 'Rose Accent',
        ];
    }

    protected function footerColorOptions(): array
    {
        return [
            '#1A1A1A' => 'Charcoal',
            '#111827' => 'Slate Black',
            '#172554' => 'Navy',
            '#3F1D2E' => 'Deep Plum',
        ];
    }

    protected function backgroundColorOptions(): array
    {
        return [
            '#F8FAFC' => 'Soft Slate',
            '#F5F5F4' => 'Warm Stone',
            '#FAF7F2' => 'Light Beige',
            '#F4F7FB' => 'Cool Blue Tint',
        ];
    }

    protected function surfaceColorOptions(): array
    {
        return [
            '#F1F5F9' => 'Slate Surface',
            '#FFFFFF' => 'White Surface',
            '#F8F5F0' => 'Warm Surface',
            '#EEF6FF' => 'Cool Surface',
        ];
    }

    protected function bodyFontOptions(): array
    {
        return [
            'open-sans' => 'Open Sans',
            'inter' => 'Inter',
            'nunito' => 'Nunito',
            'lato' => 'Lato',
        ];
    }

    protected function displayFontOptions(): array
    {
        return [
            'playfair-display' => 'Playfair Display',
            'merriweather' => 'Merriweather',
            'lora' => 'Lora',
            'source-serif-4' => 'Source Serif 4',
        ];
    }
}
