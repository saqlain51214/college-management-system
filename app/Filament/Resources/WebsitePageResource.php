<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WebsitePageResource\Pages;
use App\Models\WebsitePage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class WebsitePageResource extends Resource
{
    protected static ?string $model = WebsitePage::class;

    protected static ?string $navigationIcon  = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'System';
    protected static ?string $navigationLabel = 'Website Pages';
    protected static ?int    $navigationSort  = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Page')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(200)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn($state, Forms\Set $set) => $set('slug', Str::slug($state)))
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('slug')->required()->maxLength(200),
                    Forms\Components\TextInput::make('sort_order')->numeric()->default(0)->label('Menu Order'),
                    Forms\Components\Toggle::make('in_menu')->label('Show in Navigation Menu')->default(false)->onColor('info'),
                    Forms\Components\Toggle::make('is_published')->label('Published')->default(true)->onColor('success'),

                    Forms\Components\TextInput::make('meta_title')->label('SEO Title')->maxLength(200)->columnSpanFull(),
                    Forms\Components\Textarea::make('meta_description')->label('SEO Description')->rows(2)->maxLength(300)->columnSpanFull(),

                    Forms\Components\FileUpload::make('featured_image')->image()->directory('website/pages')->maxSize(2048)->columnSpanFull(),

                    Forms\Components\RichEditor::make('content')
                        ->toolbarButtons(['bold','italic','underline','bulletList','orderedList','link','h2','h3','blockquote','attachFiles','table','undo','redo'])
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
                Tables\Columns\TextColumn::make('slug')->searchable()->toggleable(),
                Tables\Columns\IconColumn::make('in_menu')->label('In Menu')->boolean(),
                Tables\Columns\IconColumn::make('is_published')->label('Published')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->label('Last Updated')->dateTime('d M Y')->sortable(),
            ])
            ->filters([Tables\Filters\TernaryFilter::make('is_published'), Tables\Filters\TrashedFilter::make()])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->defaultSort('sort_order')
            ->paginated([10, 25, 50, 100])
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListWebsitePages::route('/'),
            'create' => Pages\CreateWebsitePage::route('/create'),
            'edit'   => Pages\EditWebsitePage::route('/{record}/edit'),
        ];
    }
}
