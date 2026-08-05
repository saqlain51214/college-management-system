<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeroSlideResource\Pages;
use App\Models\HeroSlide;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HeroSlideResource extends Resource
{
    protected static ?string $model = HeroSlide::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Homepage Slider';

    protected static ?string $modelLabel = 'Hero Slide';

    protected static ?int $navigationSort = 0;

    // Reached only via Website Pages → Home Page → Hero Slider → "Manage" —
    // not a separate sidebar entry, to keep the sidebar to one "Website Pages" group.
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Slide')
                ->schema([
                    Forms\Components\FileUpload::make('image')
                        ->label('Background Image')
                        ->image()
                        ->disk('public')
                        ->directory('hero-slides')
                        ->maxSize(10240)
                        ->imageEditor()
                        ->required()
                        ->helperText('Wide landscape photo works best (1920×1080 or similar), up to 10 MB.')
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('title')
                        ->label('Title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('description')
                        ->label('Description')
                        ->rows(2)
                        ->maxLength(500)
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Buttons (Optional)')
                ->description('Pick which existing page each button opens. Leave "Page to link to" empty to hide that button entirely.')
                ->schema([
                    Forms\Components\TextInput::make('primary_btn_text')
                        ->label('Primary Button Text')
                        ->maxLength(60)
                        ->placeholder('e.g. Apply for Admission')
                        ->helperText('The words shown on the button, e.g. "Apply for Admission".'),
                    Forms\Components\Select::make('primary_btn_link')
                        ->label('Primary Button — Page to Link To')
                        ->options(fn () => static::pageOptions())
                        ->searchable()
                        ->native(false)
                        ->helperText('Only real, working pages are listed — pick one and the button always works. Nothing here → button is hidden.'),
                    Forms\Components\TextInput::make('secondary_btn_text')
                        ->label('Secondary Button Text')
                        ->maxLength(60)
                        ->placeholder('e.g. Explore Programs')
                        ->helperText('The words shown on the button, e.g. "Explore Programs".'),
                    Forms\Components\Select::make('secondary_btn_link')
                        ->label('Secondary Button — Page to Link To')
                        ->options(fn () => static::pageOptions())
                        ->searchable()
                        ->native(false)
                        ->helperText('Only real, working pages are listed — pick one and the button always works. Nothing here → button is hidden.'),
                ])->columns(2),

            Forms\Components\Section::make('Display')
                ->schema([
                    Forms\Components\TextInput::make('sort_order')
                        ->label('Order')
                        ->numeric()
                        ->default(0)
                        ->helperText('Lower number shows first.'),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Show on website')
                        ->default(true),
                ])->columns(2),
        ]);
    }

    /**
     * Curated list of param-free public routes safe to use as a slide button
     * target — deliberately NOT every named route in the app (portal/admin/
     * pdf routes, or routes needing an {id}/{slug} like a single news article,
     * are excluded since a hero button can't supply one). Keeping this as a
     * fixed list (rather than a free-text field) is what makes an invalid
     * link impossible instead of just validated — the button either links to
     * a real page or doesn't appear, never a dead "#" link.
     */
    public static function pageOptions(): array
    {
        return [
            'home'                          => 'Home',
            'about'                         => 'About Us',
            'about.history'                 => 'About — History',
            'about.mission'                 => 'About — Mission & Vision',
            'programs'                      => 'Academic Programs',
            'departments'                   => 'Departments',
            'faculty'                       => 'Faculty',
            'course-outlines'                => 'Course Outlines',
            'campus-facilities'              => 'Campus Facilities',
            'gallery'                       => 'Gallery',
            'downloads'                     => 'Downloads',
            'admissions'                    => 'Admissions — Overview',
            'admissions.procedure'          => 'Admissions — Procedure',
            'admissions.fee-structure'      => 'Admissions — Fee Structure',
            'admissions.semester-rules'     => 'Admissions — Semester Rules',
            'scholarships'                  => 'Scholarships',
            'jobs'                          => 'Careers / Job Openings',
            'news'                          => 'News',
            'events'                        => 'Events',
            'notices'                       => 'Notices',
            'contact'                       => 'Contact Us',
            'fee-challan.download'          => 'Fee Challan Lookup',
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\ImageColumn::make('image')->label('')->square()->height(50),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable()->weight('bold')->limit(40),
                Tables\Columns\TextColumn::make('description')->limit(50)->toggleable(),
                Tables\Columns\TextColumn::make('sort_order')->label('Order')->sortable()->alignCenter(),
                Tables\Columns\ToggleColumn::make('is_active')->label('Active'),
            ])
            ->recordUrl(null)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListHeroSlides::route('/'),
            'create' => Pages\CreateHeroSlide::route('/create'),
            'edit'   => Pages\EditHeroSlide::route('/{record}/edit'),
        ];
    }
}
