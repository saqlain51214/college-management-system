<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomeSectionResource\Pages;
use App\Models\HomeSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class HomeSectionResource extends Resource
{
    protected static ?string $model = HomeSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationGroup = 'Website Management';
    protected static ?string $navigationLabel = 'Home Sections';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Section Information')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('title')->disabled()->required(),
                    Forms\Components\TextInput::make('key')->disabled()->required(),
                    Forms\Components\Toggle::make('is_active')->label('Active on Homepage')->default(true),
                    Forms\Components\TextInput::make('sort_order')->numeric()->disabled(),
                ]),

            Forms\Components\Section::make('Section Guide')
                ->schema([
                    Forms\Components\Placeholder::make('guide')
                        ->content(fn (?HomeSection $record): HtmlString => new HtmlString(match ($record?->key) {
                            'elevate-learning' => '<div class="text-sm">Update <strong>Elevate your learning</strong> heading, image, stats, feature cards, and CTA buttons from this section.</div>',
                            'campus-life' => '<div class="text-sm">Update <strong>Campus life</strong> text, hero image, support images, statistics, and three cards from this section.</div>',
                            'testimonials' => '<div class="text-sm">Update <strong>Testimonials</strong> heading and all testimonial slides with portraits, avatars, names, roles, and quotes from this section.</div>',
                            default => '<div class="text-sm">Update the content for this homepage section.</div>',
                        })),
                ]),

            Forms\Components\Section::make('Elevate Your Learning')
                ->visible(fn (Get $get): bool => $get('key') === 'elevate-learning')
                ->schema([
                    Forms\Components\TextInput::make('content.section_title')->required(),
                    Forms\Components\Textarea::make('content.section_text')->rows(2)->required(),
                    Forms\Components\TextInput::make('content.badge_text')->required(),
                    Forms\Components\TextInput::make('content.heading')->required(),
                    Forms\Components\Textarea::make('content.description')->rows(3)->required(),
                    Forms\Components\FileUpload::make('content.main_image')->image()->disk('public')->directory('website/home-sections'),
                    Forms\Components\Placeholder::make('elevate_image_preview')
                        ->label('Current Main Image')
                        ->content(fn (Get $get): HtmlString => self::imagePreview($get('content.main_image'))),
                    Forms\Components\TextInput::make('content.primary_button_text')->required(),
                    Forms\Components\TextInput::make('content.primary_button_link')->required(),
                    Forms\Components\TextInput::make('content.secondary_button_text')->required(),
                    Forms\Components\TextInput::make('content.secondary_button_link')->required(),
                    Forms\Components\Repeater::make('content.stats')
                        ->minItems(2)->maxItems(2)
                        ->schema([
                            Forms\Components\TextInput::make('value')->required(),
                            Forms\Components\TextInput::make('label')->required(),
                        ]),
                    Forms\Components\Repeater::make('content.feature_cards')
                        ->minItems(3)->maxItems(3)
                        ->schema([
                            Forms\Components\TextInput::make('icon')->required()->maxLength(10),
                            Forms\Components\TextInput::make('title')->required(),
                            Forms\Components\Textarea::make('text')->rows(2)->required(),
                        ]),
                ]),

            Forms\Components\Section::make('Campus Life')
                ->visible(fn (Get $get): bool => $get('key') === 'campus-life')
                ->schema([
                    Forms\Components\TextInput::make('content.section_title')->required(),
                    Forms\Components\Textarea::make('content.section_text')->rows(2)->required(),
                    Forms\Components\TextInput::make('content.intro_label')->required(),
                    Forms\Components\TextInput::make('content.heading')->required(),
                    Forms\Components\Textarea::make('content.description')->rows(3)->required(),
                    Forms\Components\TextInput::make('content.link_text')->required(),
                    Forms\Components\TextInput::make('content.link_route')->required(),
                    Forms\Components\Repeater::make('content.stats')
                        ->minItems(2)->maxItems(2)
                        ->schema([
                            Forms\Components\TextInput::make('value')->required(),
                            Forms\Components\TextInput::make('label')->required(),
                        ]),
                    Forms\Components\FileUpload::make('content.hero_image')->image()->disk('public')->directory('website/home-sections'),
                    Forms\Components\Placeholder::make('campus_hero_preview')
                        ->label('Current Hero Image')
                        ->content(fn (Get $get): HtmlString => self::imagePreview($get('content.hero_image'))),
                    Forms\Components\TextInput::make('content.hero_image_alt')->required(),
                    Forms\Components\TextInput::make('content.hero_badge')->required(),
                    Forms\Components\Repeater::make('content.support_images')
                        ->minItems(2)->maxItems(2)
                        ->schema([
                            Forms\Components\FileUpload::make('image')->image()->disk('public')->directory('website/home-sections')->required(),
                            Forms\Components\Placeholder::make('preview')
                                ->label('Current Image')
                                ->content(fn (Get $get): HtmlString => self::imagePreview($get('image'))),
                            Forms\Components\TextInput::make('alt')->required(),
                        ]),
                    Forms\Components\Repeater::make('content.cards')
                        ->minItems(3)->maxItems(3)
                        ->schema([
                            Forms\Components\FileUpload::make('image')->image()->disk('public')->directory('website/home-sections')->required(),
                            Forms\Components\Placeholder::make('preview')
                                ->label('Current Card Image')
                                ->content(fn (Get $get): HtmlString => self::imagePreview($get('image'))),
                            Forms\Components\TextInput::make('title')->required(),
                            Forms\Components\Textarea::make('description')->rows(2)->required(),
                        ]),
                ]),

            Forms\Components\Section::make('Testimonials')
                ->visible(fn (Get $get): bool => $get('key') === 'testimonials')
                ->schema([
                    Forms\Components\TextInput::make('content.section_title')->required(),
                    Forms\Components\Textarea::make('content.section_text')->rows(2)->required(),
                    Forms\Components\Repeater::make('content.items')
                        ->minItems(4)->maxItems(4)
                        ->schema([
                            Forms\Components\FileUpload::make('portrait')->image()->disk('public')->directory('website/home-sections')->required(),
                            Forms\Components\Placeholder::make('portrait_preview')
                                ->label('Current Portrait')
                                ->content(fn (Get $get): HtmlString => self::imagePreview($get('portrait'))),
                            Forms\Components\TextInput::make('portrait_alt')->required(),
                            Forms\Components\Textarea::make('quote')->rows(3)->required(),
                            Forms\Components\FileUpload::make('avatar')->image()->disk('public')->directory('website/home-sections')->required(),
                            Forms\Components\Placeholder::make('avatar_preview')
                                ->label('Current Avatar')
                                ->content(fn (Get $get): HtmlString => self::imagePreview($get('avatar'))),
                            Forms\Components\TextInput::make('name')->required(),
                            Forms\Components\TextInput::make('role')->required(),
                        ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')->label('#')->sortable()->alignCenter(),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('key')->toggleable(),
                Tables\Columns\ToggleColumn::make('is_active')->label('Active'),
                Tables\Columns\TextColumn::make('updated_at')->dateTime('d M Y'),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->defaultSort('sort_order');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('sort_order');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    protected static function imagePreview(string|array|null $path): HtmlString
    {
        if (is_array($path)) {
            $path = collect($path)->flatten()->first(fn ($value) => is_string($value) && filled($value));
        }

        if (blank($path)) {
            return new HtmlString('<span class="text-sm text-gray-500">No image uploaded.</span>');
        }

        $url = str_starts_with($path, 'assets/')
            ? asset($path)
            : \Illuminate\Support\Facades\Storage::disk('public')->url($path);

        return new HtmlString('<img src="' . e($url) . '" alt="Section image" style="max-height: 180px; max-width: 100%; border-radius: 12px; border: 1px solid #e5e7eb;" />');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHomeSections::route('/'),
            'edit' => Pages\EditHomeSection::route('/{record}/edit'),
        ];
    }
}
