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
                        ->disabled(),
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(200)
                        ->disabled(),
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
                    Forms\Components\FileUpload::make('featured_image')
                        ->label('Page Banner Image')
                        ->image()
                        ->disk('public')
                        ->directory('website/pages')
                        ->maxSize(2048)
                        ->columnSpanFull(),
                    Placeholder::make('current_featured_image')
                        ->label('Current Banner Preview')
                        ->content(fn (?WebsitePage $record): HtmlString => static::imagePreviewHtml($record?->featured_image, 'Banner preview'))
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Home Page Content')
                ->visible(fn (Get $get): bool => $get('slug') === 'home')
                ->schema([
                    Placeholder::make('home_sections_guide')
                        ->label('Home Page Sections Guide')
                        ->content(fn (?WebsitePage $record): HtmlString => new HtmlString(
                            '<div class="space-y-2 text-sm leading-6">'
                            . '<div><strong>1. Announcement Bar</strong> - updates from <a class="text-primary-600 underline" href="' . e(AnnouncementResource::getUrl('index')) . '">Notices module</a></div>'
                            . '<div><strong>2. Hero Slider</strong> - update below in <strong>Hero Slides</strong> (3 images)</div>'
                            . '<div><strong>3. Feature Cards</strong> - update below in <strong>Feature Cards</strong></div>'
                            . '<div><strong>4. Elevate Your Learning</strong> - update from <a class="text-primary-600 underline" href="' . e(HomeSectionResource::getUrl('index')) . '">Home Sections module</a></div>'
                            . '<div><strong>5. Campus Life</strong> - update from <a class="text-primary-600 underline" href="' . e(HomeSectionResource::getUrl('index')) . '">Home Sections module</a></div>'
                            . '<div><strong>6. Testimonials</strong> - update from <a class="text-primary-600 underline" href="' . e(HomeSectionResource::getUrl('index')) . '">Home Sections module</a></div>'
                            . '<div><strong>7. Discover the Minds Shaping Future</strong> - update below in <strong>Home About Section</strong> (1 image)</div>'
                            . '<div><strong>8. Featured Programs</strong> - headings from this page, program cards from Academic Programs module</div>'
                            . '<div><strong>9. News</strong> - headings from this page, articles from <a class="text-primary-600 underline" href="' . e(NewsArticleResource::getUrl('index')) . '">News module</a></div>'
                            . '<div><strong>10. Events</strong> - headings from this page, records from <a class="text-primary-600 underline" href="' . e(WebsiteEventResource::getUrl('index')) . '">Events module</a></div>'
                            . '</div>'
                        ))
                        ->columnSpanFull(),
                    Placeholder::make('home_sections_access')
                        ->label('Quick Access')
                        ->content(fn (): HtmlString => new HtmlString(
                            '<a href="' . e(HomeSectionResource::getUrl('index')) . '" class="text-sm font-semibold text-primary-600 underline">Open Home Sections Module</a>'
                        ))
                        ->columnSpanFull(),
                    Placeholder::make('home_image_preview')
                        ->label('Current Home Images')
                        ->content(fn (?WebsitePage $record): HtmlString => new HtmlString(collect(data_get($record?->content, 'hero.slides', []))
                            ->pluck('image')
                            ->filter()
                            ->map(function (string $path): string {
                                $url = str_starts_with($path, 'assets/')
                                    ? asset($path)
                                    : \Illuminate\Support\Facades\Storage::url($path);

                                return '<img src="' . e($url) . '" alt="Home image" style="height: 84px; width: 120px; object-fit: cover; border-radius: 10px; border: 1px solid #e5e7eb;" />';
                            })
                            ->implode(' ')))
                        ->columnSpanFull(),
                    Forms\Components\Repeater::make('content.hero.slides')
                        ->label('Hero Slides')
                        ->minItems(1)
                        ->maxItems(5)
                        ->collapsed()
                        ->schema([
                            Forms\Components\TextInput::make('title')->required(),
                            Forms\Components\Textarea::make('description')->rows(3),
                            Forms\Components\FileUpload::make('image')
                                ->image()
                                ->disk('public')
                                ->directory('website/pages'),
                            Placeholder::make('image_preview')
                                ->label('Current Slide Image')
                                ->content(fn (Get $get): HtmlString => static::imagePreviewHtml($get('image'), 'Slide image')),
                            Forms\Components\TextInput::make('primary_btn_text')->label('Primary Button Text'),
                            Forms\Components\TextInput::make('primary_btn_link')->label('Primary Route Name'),
                            Forms\Components\TextInput::make('secondary_btn_text')->label('Secondary Button Text'),
                            Forms\Components\TextInput::make('secondary_btn_link')->label('Secondary Route Name'),
                        ]),
                    Forms\Components\Repeater::make('content.features')
                        ->label('Feature Cards')
                        ->minItems(3)
                        ->maxItems(3)
                        ->schema([
                            Forms\Components\TextInput::make('title')->required(),
                            Forms\Components\Textarea::make('description')->rows(3)->required(),
                        ]),
                    Forms\Components\Section::make('Discover the Minds Shaping Future Section')
                        ->columns(2)
                        ->schema([
                            Forms\Components\TextInput::make('content.about.title')->required()->columnSpanFull(),
                            Forms\Components\Textarea::make('content.about.description')->rows(4)->required()->columnSpanFull(),
                            Forms\Components\FileUpload::make('content.about.image')
                                ->image()
                                ->disk('public')
                                ->directory('website/pages')
                                ->columnSpan(1),
                            Placeholder::make('about_image_preview')
                                ->label('Current About Image')
                                ->content(fn (Get $get): HtmlString => static::imagePreviewHtml($get('content.about.image'), 'About image'))
                                ->columnSpan(1),
                            Forms\Components\Group::make([
                                Forms\Components\TextInput::make('content.about.badge_title'),
                                Forms\Components\TextInput::make('content.about.badge_text'),
                                Forms\Components\TextInput::make('content.about.button_text'),
                                Forms\Components\TextInput::make('content.about.button_link')->label('Button Route Name'),
                            ])->columnSpan(1),
                            Forms\Components\Repeater::make('content.about.stats')
                                ->label('About Statistics')
                                ->helperText('Example: 2,500+ / Active Students, 98% / Graduate Rate, 50+ / Programs Offered')
                                ->minItems(3)
                                ->maxItems(4)
                                ->schema([
                                    Forms\Components\TextInput::make('value')->required()->placeholder('e.g. 2,500+'),
                                    Forms\Components\TextInput::make('label')->required()->placeholder('e.g. Active Students'),
                                ])
                                ->columnSpanFull(),
                        ]),
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
                Tables\Columns\TextColumn::make('sort_order')->label('#')->sortable()->alignCenter(),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable()->wrap(),
                Tables\Columns\TextColumn::make('slug')->searchable(),
                Tables\Columns\ToggleColumn::make('is_published')->label('Published')->onColor('success')->offColor('gray'),
                Tables\Columns\TextColumn::make('updated_at')->label('Last Updated')->dateTime('d M Y')->sortable(),
            ])
            ->filters([Tables\Filters\TernaryFilter::make('is_published'), Tables\Filters\TrashedFilter::make()])
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
