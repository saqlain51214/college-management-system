<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeeSlipTemplateResource\Pages;
use App\Models\FeeSlipTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FeeSlipTemplateResource extends Resource
{
    protected static ?string $model = FeeSlipTemplate::class;

    protected static ?string $navigationIcon  = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Fee Slip Templates';
    protected static ?int    $navigationSort  = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Template Settings')
                ->tabs([

                    // ── Tab 1: General ───────────────────────────────────────
                    Forms\Components\Tabs\Tab::make('General')
                        ->icon('heroicon-o-adjustments-horizontal')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Template Name')
                                ->required()
                                ->maxLength(100)
                                ->columnSpanFull(),

                            Forms\Components\Select::make('variant')
                                ->label('Design Variant')
                                ->options([
                                    'kiu'     => 'KCBL Style (Landscape 3-col, Teal)',
                                    'classic' => 'Classic JDCA (Landscape 3-col, Green)',
                                ])
                                ->required()
                                ->reactive()
                                ->default('kiu'),

                            Forms\Components\Select::make('orientation')
                                ->label('Page Orientation')
                                ->options([
                                    'landscape' => 'Landscape (A4 wide)',
                                    'portrait'  => 'Portrait (A4 tall)',
                                ])
                                ->required()
                                ->default('landscape'),

                            Forms\Components\Toggle::make('is_active')
                                ->label('Set as Active Template')
                                ->helperText('Only one template can be active at a time. Setting this active will deactivate others.')
                                ->default(false)
                                ->columnSpanFull(),
                        ])
                        ->columns(2),

                    // ── Tab 2: Design & Colors ───────────────────────────────
                    Forms\Components\Tabs\Tab::make('Design & Colors')
                        ->icon('heroicon-o-swatch')
                        ->schema([
                            Forms\Components\TextInput::make('college_name')
                                ->label('College Name')
                                ->placeholder('Uses CollegeSetting value if blank')
                                ->maxLength(200)
                                ->columnSpanFull(),

                            Forms\Components\TextInput::make('college_subtitle')
                                ->label('College Subtitle')
                                ->placeholder('e.g. (EIDGAH ASTORE)')
                                ->maxLength(200),

                            Forms\Components\TextInput::make('college_short_name')
                                ->label('Short Name / Abbreviation')
                                ->placeholder('e.g. JDCA')
                                ->maxLength(15),

                            Forms\Components\FileUpload::make('logo_path')
                                ->label('College Logo')
                                ->disk('public')
                                ->directory('fee-slip-logos')
                                ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/svg+xml', 'image/webp'])
                                ->image()
                                ->imagePreviewHeight('80')
                                ->columnSpanFull(),

                            Forms\Components\ColorPicker::make('primary_color')
                                ->label('Primary / Brand Color')
                                ->default('#009999'),

                            Forms\Components\ColorPicker::make('accent_color')
                                ->label('Accent / InWords Color')
                                ->default('#1a56db'),

                            Forms\Components\ColorPicker::make('text_color')
                                ->label('Body Text Color')
                                ->default('#111111'),

                            Forms\Components\Toggle::make('show_barcode')
                                ->label('Show Barcode')
                                ->default(true),

                            Forms\Components\Toggle::make('show_watermark')
                                ->label('Show Watermark')
                                ->reactive()
                                ->default(false),

                            Forms\Components\TextInput::make('watermark_text')
                                ->label('Watermark Text')
                                ->placeholder('e.g. JDCA OFFICIAL')
                                ->visible(fn (Forms\Get $get) => (bool) $get('show_watermark'))
                                ->maxLength(100),
                        ])
                        ->columns(3),

                    // ── Tab 3: Bank & References ─────────────────────────────
                    Forms\Components\Tabs\Tab::make('Bank & References')
                        ->icon('heroicon-o-building-library')
                        ->schema([
                            Forms\Components\TextInput::make('bank_name')
                                ->label('Bank Name')
                                ->placeholder('e.g. KCBL')
                                ->maxLength(100),

                            Forms\Components\FileUpload::make('bank_logo_path')
                                ->label('Bank Logo')
                                ->disk('public')
                                ->directory('fee-slip-bank-logos')
                                ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/svg+xml', 'image/webp'])
                                ->image()
                                ->imagePreviewHeight('60')
                                ->helperText('Upload bank logo (PNG/JPG). Shown in the fee challan header.'),

                            Forms\Components\TextInput::make('bank_account')
                                ->label('Account Number')
                                ->placeholder('e.g. 0368421')
                                ->maxLength(50),

                            Forms\Components\TextInput::make('bank_account_title')
                                ->label('Account Title')
                                ->placeholder('e.g. Jinnah School & Degree College Astore')
                                ->maxLength(200)
                                ->columnSpanFull(),

                            Forms\Components\TextInput::make('bank_branch')
                                ->label('Branch Name')
                                ->placeholder('e.g. Eidgah Astore')
                                ->maxLength(100),

                            Forms\Components\TextInput::make('ref_prefix')
                                ->label('Reference No. Prefix')
                                ->placeholder('e.g. 2024-JDCA')
                                ->maxLength(30),

                            Forms\Components\TextInput::make('bill_prefix')
                                ->label('1-Bill Consumer No. Prefix')
                                ->placeholder('e.g. JDCA-')
                                ->maxLength(30),

                            Forms\Components\TagsInput::make('copies')
                                ->label('Copy Labels')
                                ->helperText('Press Enter after each label')
                                ->default(['Bank Copy', 'Accounts Copy', 'Student Copy'])
                                ->columnSpanFull(),
                        ])
                        ->columns(2),

                    // ── Tab 4: Fee Table ─────────────────────────────────────
                    Forms\Components\Tabs\Tab::make('Fee Table')
                        ->icon('heroicon-o-table-cells')
                        ->schema([
                            Forms\Components\Radio::make('fee_display_mode')
                                ->label('Fee Display Mode')
                                ->options([
                                    'dynamic' => 'Dynamic – show actual payment amounts',
                                    'static'  => 'Static – show all template fee rows (blank amounts)',
                                ])
                                ->default('dynamic')
                                ->reactive()
                                ->columnSpanFull(),

                            Forms\Components\Repeater::make('fee_items')
                                ->label('Fee Categories')
                                ->addActionLabel('Add Fee Item')
                                ->schema([
                                    Forms\Components\TextInput::make('label')
                                        ->label('Fee Item Name')
                                        ->required()
                                        ->placeholder('e.g. Tuition Fee')
                                        ->columnSpanFull(),
                                ])
                                ->visible(fn (Forms\Get $get) => $get('fee_display_mode') === 'static')
                                ->reorderable()
                                ->collapsible()
                                ->defaultItems(0)
                                ->columnSpanFull(),
                        ])
                        ->columns(1),

                    // ── Tab 5: Content ───────────────────────────────────────
                    Forms\Components\Tabs\Tab::make('Content')
                        ->icon('heroicon-o-document')
                        ->schema([
                            Forms\Components\Toggle::make('show_in_words')
                                ->label('Show Amount In Words')
                                ->default(true),

                            Forms\Components\Toggle::make('show_depositor_fields')
                                ->label('Show Depositor Mobile / CNIC fields')
                                ->default(true),

                            Forms\Components\Toggle::make('show_ref_no')
                                ->label('Show Reference No.')
                                ->default(true),

                            Forms\Components\Toggle::make('show_consumer_no')
                                ->label('Show Consumer / 1-Bill No.')
                                ->default(true),

                            Forms\Components\Toggle::make('show_accountant_sig')
                                ->label('Show Accountant Signature Line')
                                ->default(false),

                            Forms\Components\Textarea::make('instructions')
                                ->label('Instructions')
                                ->rows(5)
                                ->placeholder('Instructions shown at the bottom of each copy...')
                                ->columnSpanFull(),

                            Forms\Components\TextInput::make('footer_text')
                                ->label('Footer Text')
                                ->placeholder('e.g. Hunza Printing Press Gilgit. Ph: 05811-451868-69')
                                ->maxLength(255)
                                ->columnSpanFull(),
                        ])
                        ->columns(2),
                ])
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Template Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\BadgeColumn::make('variant')
                    ->label('Variant')
                    ->colors([
                        'warning' => 'kiu',
                        'success' => 'classic',
                        'primary' => 'modern',
                        'gray'    => 'minimal',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'kiu'     => 'KIU Style',
                        'classic' => 'Classic',
                        'modern'  => 'Modern Blue',
                        'minimal' => 'Minimal',
                        default   => $state,
                    }),

                Tables\Columns\BadgeColumn::make('orientation')
                    ->label('Orientation')
                    ->colors([
                        'info' => 'landscape',
                        'gray' => 'portrait',
                    ]),

                Tables\Columns\TextColumn::make('bank_name')
                    ->label('Bank')
                    ->placeholder('—'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->url(fn (FeeSlipTemplate $record) => route('admin.fee-slip.preview', $record))
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('activate')
                    ->label('Set Active')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Set as Active Template')
                    ->modalDescription('This will deactivate all other templates and make this one the active template used for generating fee challans.')
                    ->action(function (FeeSlipTemplate $record) {
                        FeeSlipTemplate::where('id', '!=', $record->id)
                            ->where('is_active', true)
                            ->update(['is_active' => false]);
                        $record->update(['is_active' => true]);
                    })
                    ->hidden(fn (FeeSlipTemplate $record) => $record->is_active),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('is_active', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFeeSlipTemplates::route('/'),
            'create' => Pages\CreateFeeSlipTemplate::route('/create'),
            'edit'   => Pages\EditFeeSlipTemplate::route('/{record}/edit'),
        ];
    }
}
