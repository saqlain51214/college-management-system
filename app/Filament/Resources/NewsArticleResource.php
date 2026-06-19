<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsArticleResource\Pages;
use App\Models\NewsArticle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class NewsArticleResource extends Resource
{
    protected static ?string $model = NewsArticle::class;

    protected static ?string $navigationIcon  = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'Website Management';
    protected static ?string $navigationLabel = 'News';
    protected static ?int    $navigationSort  = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Article')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(250)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn($state, Forms\Set $set) => $set('slug', Str::slug($state)))
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('slug')->required()->maxLength(250)->columnSpanFull(),

                    Forms\Components\Select::make('category')
                        ->options(fn() => \App\Models\ListItem::getOptions('news_category'))
                        ->default('news'),

                    Forms\Components\DatePicker::make('published_date')->label('Publish Date')->default(now())->displayFormat('d M Y')->native(false),
                    Forms\Components\Toggle::make('is_published')->label('Published')->default(false)->onColor('success'),
                    Forms\Components\Toggle::make('is_featured')->label('Featured')->default(false)->onColor('warning'),

                    Forms\Components\FileUpload::make('featured_image')->label('Featured Image')->image()->disk('public')->directory('news')->maxSize(2048)->columnSpanFull(),
                    Forms\Components\Textarea::make('excerpt')->label('Excerpt / Summary')->rows(2)->maxLength(500)->columnSpanFull(),
                    Forms\Components\RichEditor::make('content')->label('Full Content')
                        ->toolbarButtons(['bold','italic','underline','bulletList','orderedList','link','h2','h3','blockquote','undo','redo'])
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')->label('')->width(60)->height(40)->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('title')->searchable()->wrap()->sortable(),
                Tables\Columns\TextColumn::make('category')->badge()->color('info'),
                Tables\Columns\TextColumn::make('published_date')->label('Date')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('views')->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_featured')->label('Featured')->boolean(),
                Tables\Columns\IconColumn::make('is_published')->label('Published')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(fn() => \App\Models\ListItem::getOptions('news_category')),
                Tables\Filters\TernaryFilter::make('is_published')->label('Published'),
                Tables\Filters\TernaryFilter::make('is_featured')->label('Featured'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('publish')
                    ->label('Publish')
                    ->icon('heroicon-o-eye')
                    ->color('success')
                    ->iconButton()
                    ->visible(fn(NewsArticle $r) => ! $r->is_published)
                    ->action(fn(NewsArticle $r) => $r->update(['is_published' => true, 'published_date' => now()->toDateString()])),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50, 100])
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListNewsArticles::route('/'),
            'create' => Pages\CreateNewsArticle::route('/create'),
            'edit'   => Pages\EditNewsArticle::route('/{record}/edit'),
        ];
    }
}
