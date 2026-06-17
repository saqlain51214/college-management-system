<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnnouncementResource\Pages;
use App\Models\Announcement;
use App\Models\Department;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;

    protected static ?string $navigationIcon  = 'heroicon-o-megaphone';
    protected static ?string $navigationGroup = 'LMS Portal';
    protected static ?string $navigationLabel = 'Announcements';
    protected static ?int    $navigationSort  = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Announcement')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('title')->label('Title')->required()->maxLength(200)->columnSpanFull(),

                    Forms\Components\Select::make('audience')
                        ->label('Target Audience')
                        ->options(['all' => 'Everyone', 'students' => 'Students Only', 'teachers' => 'Teachers Only', 'department' => 'Specific Department'])
                        ->default('all')
                        ->required()
                        ->live(),

                    Forms\Components\Select::make('priority')
                        ->options(fn() => \App\Models\ListItem::getOptions('priority_level'))
                        ->default('normal'),

                    Forms\Components\Select::make('department_id')
                        ->label('Department (if audience = Department)')
                        ->options(fn() => Department::active()->ordered()->pluck('name', 'id'))
                        ->searchable()
                        ->visible(fn(Forms\Get $get) => $get('audience') === 'department'),

                    Forms\Components\DatePicker::make('publish_date')->label('Publish Date')->default(now())->displayFormat('d M Y')->native(false),
                    Forms\Components\DatePicker::make('expiry_date')->label('Expiry Date')->displayFormat('d M Y')->native(false),

                    Forms\Components\Toggle::make('is_published')->label('Published')->default(true)->onColor('success'),
                    Forms\Components\Toggle::make('send_email')->label('Send Email Notification')->default(false)->onColor('info'),

                    Forms\Components\FileUpload::make('attachment')->label('Attachment')->directory('announcements')->maxSize(5120)->columnSpanFull(),

                    Forms\Components\RichEditor::make('content')->label('Content')->required()->columnSpanFull()
                        ->toolbarButtons(['bold', 'italic', 'underline', 'bulletList', 'orderedList', 'link', 'h2', 'h3']),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->wrap()->sortable(),
                Tables\Columns\TextColumn::make('audience')->badge()
                    ->color(fn($state) => match($state) { 'all' => 'primary', 'students' => 'success', 'teachers' => 'info', default => 'gray' }),
                Tables\Columns\TextColumn::make('priority')->badge()
                    ->color(fn($state) => match($state) { 'urgent' => 'danger', 'high' => 'warning', 'normal' => 'info', default => 'gray' }),
                Tables\Columns\TextColumn::make('publish_date')->label('Published')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('expiry_date')->label('Expires')->date('d M Y')->placeholder('—'),
                Tables\Columns\IconColumn::make('is_published')->label('Published')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('audience')
                    ->options(['all' => 'Everyone', 'students' => 'Students', 'teachers' => 'Teachers']),
                Tables\Filters\TernaryFilter::make('is_published')->label('Published'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index'  => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'edit'   => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }
}
