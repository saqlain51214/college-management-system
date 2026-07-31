<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnnouncementResource;
use App\Filament\Resources\HomeSectionResource;
use App\Filament\Resources\NewsArticleResource;
use App\Filament\Resources\WebsiteEventResource;
use App\Filament\Resources\WebsitePageResource\Pages;
use App\Models\WebsitePage;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Get;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class WebsitePageResource extends Resource
{
    protected static ?string $model = WebsitePage::class;

    protected static ?string $navigationIcon  = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Website Management';
    protected static ?string $navigationLabel = 'Website Pages';
    protected static ?int    $navigationSort  = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Page Information')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(200)
                        ->helperText('The page\'s own heading. Does not change its web address.'),
                    Forms\Components\TextInput::make('menu_label')
                        ->label('Menu Label (Optional)')
                        ->maxLength(255)
                        ->placeholder('Leave blank to use the Title above')
                        ->helperText('What visitors see in the navigation menu. Changing this does NOT change the page\'s web address — safe to rename anytime.'),
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(200)
                        ->disabled()
                        ->helperText('The page\'s web address — fixed, cannot be changed here.'),
                    Forms\Components\Toggle::make('is_published')
                        ->label('Published (show on website)')
                        ->helperText('ON: page is live on the website and its menu link appears. OFF: page shows a 404 and its menu is hidden (you can still Preview it).')
                        ->default(true)->onColor('success'),
                    Forms\Components\TextInput::make('meta_title')->label('SEO Title')->maxLength(200)->columnSpanFull(),
                    Forms\Components\Textarea::make('meta_description')->label('SEO Description')->rows(2)->maxLength(300)->columnSpanFull(),
                    Placeholder::make('page_preview_link')
                        ->label('Preview')
                        ->content(fn (?WebsitePage $record): HtmlString => $record
                            ? new HtmlString('<a href="' . e($record->previewUrl(true)) . '" target="_blank" class="text-sm font-semibold text-primary-600 underline">Open page preview (draft)</a>')
                            : new HtmlString('<span class="text-sm text-gray-500">Save page to preview it.</span>'))
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Home Page Content')
                ->visible(fn (Get $get): bool => $get('slug') === 'home')
                ->schema([
                    Placeholder::make('home_sections_guide')
                        ->label('Home Page Sections Guide')
                        ->content(fn (?WebsitePage $record): HtmlString => new HtmlString(
                            '<div class="space-y-2 text-sm leading-6">'
                            . '<div><strong>1. Hero Slider</strong> - update at <a class="text-primary-600 underline" href="' . e(\App\Filament\Resources\HeroSlideResource::getUrl('index')) . '">Website Management &rarr; Homepage Slider</a></div>'
                            . '<div><strong>2. Quick Access tiles</strong> - fixed links (Admissions, Fee Challan, etc.)</div>'
                            . '<div><strong>3. Statistics band</strong> - numbers update automatically from live data</div>'
                            . '<div><strong>4. Featured Programmes</strong> - headings below, cards from Academic Programs module</div>'
                            . '<div><strong>5. Message Desk</strong> - update from the <strong>Message Desk</strong> module (leadership)</div>'
                            . '<div><strong>6. Latest News</strong> - headings below, articles from <a class="text-primary-600 underline" href="' . e(NewsArticleResource::getUrl('index')) . '">News module</a></div>'
                            . '</div>'
                        ))
                        ->columnSpanFull(),
                    Forms\Components\Section::make('Featured Programs, News & Events Section')
                        ->columns(2)
                        ->schema([
                            Forms\Components\TextInput::make('content.programs.section_title')->label('Programs Section Title'),
                            Forms\Components\Textarea::make('content.programs.section_text')->label('Programs Section Text')->rows(2),
                            Forms\Components\TextInput::make('content.programs.intro_label')->label('Programs Intro Label'),
                            Forms\Components\TextInput::make('content.programs.intro_title')->label('Programs Intro Title'),
                            Forms\Components\Textarea::make('content.programs.intro_text')->label('Programs Intro Text')->rows(3)->columnSpanFull(),
                            Forms\Components\TextInput::make('content.news.section_title')->label('News Section Title'),
                            Forms\Components\Textarea::make('content.news.section_text')->label('News Section Text')->rows(2),
                            Forms\Components\TextInput::make('content.events.section_title')->label('Events Section Title'),
                            Forms\Components\Textarea::make('content.events.section_text')->label('Events Section Text')->rows(2),
                            Forms\Components\TextInput::make('content.events.button_text')->label('Events Button Text'),
                            Forms\Components\Repeater::make('content.programs.stats')
                                ->label('Programs Statistics')
                                ->helperText('Example: 2,500+ / Active Students, 98% / Graduate Rate, 50+ / Programs Offered. Student and program counts are also updated from DB on frontend.')
                                ->minItems(3)
                                ->maxItems(3)
                                ->schema([
                                    Forms\Components\TextInput::make('value')->required()->placeholder('e.g. 2,500+'),
                                    Forms\Components\TextInput::make('label')->required()->placeholder('e.g. Active Students'),
                                ])
                                ->columnSpanFull(),
                        ]),
                ]),

            Forms\Components\Section::make('Gallery Images')
                ->visible(fn (Get $get): bool => $get('slug') === 'gallery')
                ->schema([
                    Placeholder::make('gallery_image_preview')
                        ->label('Current Gallery Images')
                        ->content(fn (?WebsitePage $record): HtmlString => new HtmlString(collect(data_get($record?->content, 'gallery_items', []))
                            ->pluck('image')
                            ->filter()
                            ->map(function (string $path): string {
                                $url = str_starts_with($path, 'assets/')
                                    ? asset($path)
                                    : \Illuminate\Support\Facades\Storage::url($path);

                                return '<img src="' . e($url) . '" alt="Gallery image" style="height: 84px; width: 120px; object-fit: cover; border-radius: 10px; border: 1px solid #e5e7eb;" />';
                            })
                            ->implode(' ')))
                        ->columnSpanFull(),
                    Forms\Components\Repeater::make('content.gallery_items')
                        ->label('Gallery Items')
                        ->collapsed()
                        ->schema([
                            Forms\Components\FileUpload::make('image')
                                ->image()
                                ->disk('public')
                                ->directory('website/gallery')
                                ->required(),
                            Placeholder::make('image_preview')
                                ->label('Current Gallery Image')
                                ->content(fn (Get $get): HtmlString => static::imagePreviewHtml($get('image'), 'Gallery image')),
                            Forms\Components\TextInput::make('title')->required(),
                            Forms\Components\Textarea::make('caption')->rows(2),
                            Forms\Components\Select::make('category')
                                ->options([
                                    'campus' => 'Campus',
                                    'labs' => 'Labs & Learning',
                                    'sports' => 'Sports & Life',
                                    'events' => 'Events',
                                ])
                                ->default('campus')
                                ->required(),
                        ]),
                ]),

            Forms\Components\Section::make('Facilities List')
                ->visible(fn (Get $get): bool => $get('slug') === 'campus-facilities')
                ->schema([
                    Forms\Components\Repeater::make('content.facilities')
                        ->label('Facilities')
                        ->collapsed()
                        ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                        ->schema([
                            Forms\Components\TextInput::make('title')->required()->columnSpanFull(),
                            Forms\Components\Textarea::make('description')->rows(2)->required()->columnSpanFull(),
                            Forms\Components\Select::make('icon')
                                ->options([
                                    'classrooms' => 'Classrooms',
                                    'library'    => 'Library',
                                    'computer'   => 'Computer Lab',
                                    'admin'      => 'Administrative Block',
                                    'sports'     => 'Sports Area',
                                    'prayer'     => 'Prayer Area',
                                    'canteen'    => 'Canteen',
                                    'security'   => 'Safe Environment',
                                    'wifi'       => 'Wi-Fi Campus',
                                ])
                                ->default('classrooms')
                                ->required(),
                        ])
                        ->addActionLabel('Add Facility')
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('How to Apply — Steps')
                ->visible(fn (Get $get): bool => $get('slug') === 'admission-procedure')
                ->schema([
                    Forms\Components\Repeater::make('content.steps')
                        ->label('Steps')
                        ->collapsed()
                        ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                        ->schema([
                            Forms\Components\TextInput::make('title')->required()->columnSpanFull(),
                            Forms\Components\Textarea::make('description')->rows(2)->required()->columnSpanFull(),
                        ])
                        ->addActionLabel('Add Step')
                        ->reorderable()
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Page Intro & Content')
                ->visible(fn (Get $get): bool => $get('slug') !== 'home')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('content.intro_title')
                        ->label('Intro Title')
                        ->required()
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('content.intro_text')
                        ->label('Intro Text')
                        ->rows(3)
                        ->columnSpanFull(),
                    Forms\Components\RichEditor::make('content.body_html')
                        ->label('Editable Content Section')
                        ->toolbarButtons(['bold', 'italic', 'underline', 'bulletList', 'orderedList', 'link', 'h2', 'h3', 'blockquote', 'undo', 'redo'])
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable()->wrap()->weight('bold'),
                Tables\Columns\TextColumn::make('menu_label')->label('Menu Label')->placeholder('(same as Title)')->toggleable(),
                Tables\Columns\TextColumn::make('slug')->searchable()->toggleable(),
                Tables\Columns\ToggleColumn::make('is_published')->label('Published')->onColor('success')->offColor('gray'),
                Tables\Columns\TextColumn::make('updated_at')->label('Last Updated')->dateTime('d M Y')->sortable(),
            ])
            ->groups([
                Tables\Grouping\Group::make('section')->label('Page Area'),
            ])
            ->defaultGroup('section')
            ->filters([Tables\Filters\TernaryFilter::make('is_published'), Tables\Filters\TrashedFilter::make()])
            ->recordUrl(null)
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-m-eye')
                    ->url(fn (WebsitePage $record): string => $record->previewUrl(true))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('sort_order')
            ->striped();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->staticPages();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    protected static function imagePreviewHtml(string|array|null $path, string $altText = 'Image preview'): HtmlString
    {
        if (is_array($path)) {
            $path = collect($path)
                ->flatten()
                ->first(fn ($value) => is_string($value) && filled($value));
        }

        if (blank($path)) {
            return new HtmlString('<span class="text-sm text-gray-500">No image uploaded.</span>');
        }

        $url = str_starts_with($path, 'assets/')
            ? asset($path)
            : \Illuminate\Support\Facades\Storage::url($path);

        return new HtmlString(
            '<img src="' . e($url) . '" alt="' . e($altText) . '" style="max-height: 180px; width: auto; max-width: 100%; border-radius: 12px; border: 1px solid #e5e7eb;" />'
        );
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWebsitePages::route('/'),
            'edit'  => Pages\EditWebsitePage::route('/{record}/edit'),
        ];
    }
}
