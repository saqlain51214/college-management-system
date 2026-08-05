<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationTemplateResource\Pages;
use App\Models\NotificationTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NotificationTemplateResource extends Resource
{
    protected static ?string $model = NotificationTemplate::class;
    protected static ?string $navigationIcon  = 'heroicon-o-envelope';
    protected static ?string $navigationGroup = 'Communications';
    protected static ?string $navigationLabel = 'Email Templates';
    protected static ?int    $navigationSort  = 20;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Template Identity')
                ->description('The "Key" is how the system code looks this template up — do not change it on an existing template unless you also update the code that sends it.')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('key')
                        ->label('Key')
                        ->required()
                        ->maxLength(80)
                        ->unique(ignoreRecord: true)
                        ->disabled(fn (?NotificationTemplate $record) => $record !== null)
                        ->dehydrated()
                        ->helperText('Fixed identifier used by the code, e.g. "fee_overdue". Cannot be changed after creation.'),

                    Forms\Components\TextInput::make('name')
                        ->label('Display Name')
                        ->required()
                        ->maxLength(150),

                    Forms\Components\Textarea::make('description')
                        ->columnSpanFull()
                        ->rows(2)
                        ->helperText('Internal note for admins — when/why this template is sent. Not shown to recipients.'),

                    Forms\Components\Select::make('channel')
                        ->options([
                            'mail'     => 'Email only',
                            'database' => 'In-app notification only',
                            'both'     => 'Email + in-app notification',
                        ])
                        ->required()
                        ->default('both'),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true)
                        ->helperText('Turn off to stop this notification from being sent, without deleting it.'),
                ]),

            Forms\Components\Section::make('Message Content')
                ->schema([
                    Forms\Components\Placeholder::make('variables_hint')
                        ->label('Available variables for this template')
                        ->content(fn (?NotificationTemplate $record): string => $record && filled($record->variables)
                            ? collect($record->variables)->map(fn ($v) => '{{' . $v . '}}')->implode('   ')
                            : 'None recorded — variables are set when this template was first created in code.'),

                    Forms\Components\TextInput::make('subject')
                        ->required()
                        ->maxLength(200)
                        ->columnSpanFull()
                        ->helperText('Used as the email subject line. You can use {{variable}} placeholders shown above.'),

                    Forms\Components\Textarea::make('body')
                        ->required()
                        ->rows(8)
                        ->columnSpanFull()
                        ->helperText('The message body. Use {{variable}} placeholders shown above — they get replaced with real values when sent.'),

                    Forms\Components\TextInput::make('action_label')
                        ->label('Button Label')
                        ->maxLength(80)
                        ->placeholder('e.g. View Results')
                        ->helperText('Leave empty for no button.'),

                    Forms\Components\TextInput::make('action_url')
                        ->label('Button Link')
                        ->maxLength(255)
                        ->placeholder('/portal/fees'),

                    Forms\Components\TextInput::make('in_app_icon')
                        ->label('In-app Icon')
                        ->maxLength(50)
                        ->placeholder('bell')
                        ->helperText('Heroicon name without the "heroicon-o-" prefix, e.g. "bell", "credit-card".'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (NotificationTemplate $r) => $r->key),

                Tables\Columns\TextColumn::make('channel')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'mail' => 'Email only',
                        'database' => 'In-app only',
                        'both' => 'Email + In-app',
                        default => $state,
                    })
                    ->color(fn (string $state) => match ($state) {
                        'mail' => 'primary',
                        'database' => 'gray',
                        'both' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('subject')
                    ->limit(50)
                    ->toggleable(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('channel')
                    ->options([
                        'mail' => 'Email only',
                        'database' => 'In-app only',
                        'both' => 'Email + In-app',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active Status'),
            ])
            ->recordUrl(null)
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('name')
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListNotificationTemplates::route('/'),
            'create' => Pages\CreateNotificationTemplate::route('/create'),
            'edit'   => Pages\EditNotificationTemplate::route('/{record}/edit'),
        ];
    }
}
