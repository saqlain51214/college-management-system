<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LmsMaterialResource\Pages;
use App\Models\Course;
use App\Models\LmsMaterial;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LmsMaterialResource extends Resource
{
    protected static ?string $model = LmsMaterial::class;

    protected static ?string $navigationIcon  = 'heroicon-o-folder-open';
    protected static ?string $navigationGroup = 'LMS Portal';
    protected static ?string $navigationLabel = 'Course Materials';
    protected static ?int    $navigationSort  = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Material Details')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('title')->required()->maxLength(200)->columnSpanFull(),

                    Forms\Components\Select::make('course_id')
                        ->label('Course')
                        ->options(fn() => Course::active()->orderBy('code')->get()->mapWithKeys(fn($c) => [$c->id => $c->code . ' — ' . $c->name]))
                        ->searchable()->preload()->required(),

                    Forms\Components\Select::make('teacher_id')
                        ->label('Uploaded By (Teacher)')
                        ->options(fn() => Teacher::where('is_active', true)->orderBy('name')->pluck('name', 'id'))
                        ->searchable()->preload(),

                    Forms\Components\Select::make('material_type')
                        ->label('Material Type')
                        ->options(fn() => \App\Models\ListItem::getOptions('lms_material_type'))
                        ->default('document')
                        ->live(),

                    Forms\Components\TextInput::make('week_number')->label('Week #')->numeric()->minValue(1)->maxValue(18)->placeholder('e.g. 3'),

                    Forms\Components\FileUpload::make('file_path')
                        ->label('Upload File')
                        ->directory('lms/materials')
                        ->maxSize(20480)
                        ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'])
                        ->visible(fn(Forms\Get $get) => ! in_array($get('material_type'), ['video', 'link']))
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('external_url')
                        ->label('External URL / Video Link')
                        ->url()
                        ->maxLength(500)
                        ->visible(fn(Forms\Get $get) => in_array($get('material_type'), ['video', 'link']))
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('description')->rows(2)->columnSpanFull(),
                    Forms\Components\Toggle::make('is_published')->label('Published')->default(true)->onColor('success'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->wrap()->sortable(),
                Tables\Columns\TextColumn::make('course.code')->label('Course')->badge()->color('primary')->placeholder('—'),
                Tables\Columns\TextColumn::make('material_type')->label('Type')->badge()->color('info'),
                Tables\Columns\TextColumn::make('week_number')->label('Week')->prefix('W')->placeholder('—'),
                Tables\Columns\TextColumn::make('download_count')->label('Downloads')->sortable(),
                Tables\Columns\IconColumn::make('is_published')->label('Published')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->label('Added')->date('d M Y')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course_id')->label('Course')->relationship('course', 'name'),
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
            'index'  => Pages\ListLmsMaterials::route('/'),
            'create' => Pages\CreateLmsMaterial::route('/create'),
            'edit'   => Pages\EditLmsMaterial::route('/{record}/edit'),
        ];
    }
}
