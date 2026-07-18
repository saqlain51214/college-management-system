<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationTemplateResource\Pages;
use App\Models\NotificationTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class NotificationTemplateResource extends Resource
{
    protected static ?string $model = NotificationTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'System';

    protected static ?string $navigationLabel = 'Notification Templates';

    protected static ?string $modelLabel = 'Notification Template';

    protected static ?int $navigationSort = 11;

    // Templates are system-defined (seeded); staff edit wording, not add/remove.
    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Template')
                ->description('Edit the message students/parents receive. Keep the {{placeholders}} intact — they are replaced with real values automatically.')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Template Name')->required()->maxLength(150)->columnSpan(1),
                    Forms\Components\Select::make('channel')
                        ->label('Send Via')
                        ->options([
                            'both'     => 'Email + In-app bell',
                            'email'    => 'Email only',
                            'database' => 'In-app bell only',
                        ])
                        ->native(false)->required()->columnSpan(1),
                    Forms\Components\Textarea::make('description')
                        ->label('When is this sent?')->rows(2)->maxLength(255)->columnSpanFull(),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Active (uncheck to stop sending this notification)')
                        ->onColor('success')->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Message Content')
                ->schema([
                    Forms\Components\TextInput::make('subject')
                        ->label('Subject / Title')->maxLength(200)->columnSpanFull(),
                    Forms\Components\Textarea::make('body')
                        ->label('Message Body')->rows(6)->columnSpanFull(),
                    Forms\Components\TextInput::make('action_label')
                        ->label('Button Label (optional)')->maxLength(100),
                    Forms\Components\TextInput::make('action_url')
                        ->label('Button Link (optional)')->maxLength(255),
                    Forms\Components\Placeholder::make('available_variables')
                        ->label('Available placeholders')
                        ->content(function (?NotificationTemplate $record): HtmlString {
                            $vars = collect($record?->variables ?? [])->all();
                            if (empty($vars)) {
                                return new HtmlString('<span class="text-sm text-gray-500">No placeholders for this template.</span>');
                            }
                            $chips = collect($vars)->map(fn ($v) => '<code style="background:#f3f4f6;padding:2px 6px;border-radius:4px;margin:2px;display:inline-block;">{{' . e(is_array($v) ? ($v['key'] ?? '') : $v) . '}}</code>')->implode(' ');

                            return new HtmlString('<div>' . $chips . '</div>');
                        })
                        ->columnSpanFull(),
                ]),

            Forms\Components\TextInput::make('key')
                ->label('System Key (do not change)')->disabled()->dehydrated(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Template')->searchable()->wrap()->weight('bold'),
                Tables\Columns\TextColumn::make('key')->label('Key')->badge()->color('gray')->searchable(),
                Tables\Columns\TextColumn::make('channel')->label('Sent Via')->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'both'     => 'Email + In-app',
                        'email'    => 'Email',
                        'database' => 'In-app',
                        default    => $state,
                    }),
                Tables\Columns\ToggleColumn::make('is_active')->label('Active'),
                Tables\Columns\TextColumn::make('updated_at')->label('Last Edited')->since()->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotificationTemplates::route('/'),
            'edit'  => Pages\EditNotificationTemplate::route('/{record}/edit'),
        ];
    }
}
