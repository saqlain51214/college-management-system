<?php
namespace App\Filament\Resources;
use App\Filament\Resources\AdmissionInquiryResource\Pages;
use App\Models\AdmissionInquiry;
use App\Models\AcademicProgram;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AdmissionInquiryResource extends Resource
{
    protected static ?string $model = AdmissionInquiry::class;
    protected static ?string $navigationIcon  = 'heroicon-o-inbox-arrow-down';
    protected static ?string $navigationGroup = 'Students & Admissions';
    protected static ?string $navigationLabel = 'Admission Inquiries';
    protected static ?int    $navigationSort  = 5;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status','new')->count() ?: null;
    }
    public static function getNavigationBadgeColor(): ?string { return 'info'; }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Inquiry Details')->columns(2)->schema([
                Forms\Components\TextInput::make('reference_no')->disabled(),
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('father_name')->label("Father's Name"),
                Forms\Components\TextInput::make('phone')->required(),
                Forms\Components\TextInput::make('student_phone')->label('Student Mobile'),
                Forms\Components\TextInput::make('email')->email()->nullable(),
                Forms\Components\TextInput::make('cnic')->label('CNIC / B-Form'),
                Forms\Components\DatePicker::make('dob'),
                Forms\Components\TextInput::make('entry_path')->label('Applying For'),
                Forms\Components\TextInput::make('gender'),
                Forms\Components\TextInput::make('campus'),
                Forms\Components\TextInput::make('city'),
                Forms\Components\Select::make('program_id')->label('Program Interested')
                    ->options(AcademicProgram::active()->pluck('name','id'))->nullable()->searchable(),
                Forms\Components\Select::make('qualification')
                    ->options(['matric'=>'Matric','intermediate'=>'Intermediate','bachelor'=>'Bachelor','other'=>'Other'])
                    ->nullable(),
                Forms\Components\Toggle::make('declare_true')->label('Declaration Accepted'),
                Forms\Components\Textarea::make('address')->columnSpanFull()->rows(2)->nullable(),
                Forms\Components\KeyValue::make('academic_details')
                    ->columnSpanFull()
                    ->disabled()
                    ->dehydrated(false),
                Forms\Components\Select::make('status')
                    ->options(['new'=>'New','contacted'=>'Contacted','enrolled'=>'Enrolled','rejected'=>'Rejected'])
                    ->default('new')->required(),
                Forms\Components\Textarea::make('message')->label('Student Message')->columnSpanFull()->rows(3)->nullable(),
                Forms\Components\Textarea::make('admin_notes')->label('Admin Notes')->columnSpanFull()->rows(2)->nullable(),
            ]),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([

            // ── Row 1: Status strip ─────────────────────────────────────
            Infolists\Components\Section::make()->columns(3)->schema([
                Infolists\Components\TextEntry::make('reference_no')
                    ->label('Reference No')
                    ->weight('bold')
                    ->copyable(),
                Infolists\Components\TextEntry::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'new'       => 'info',
                        'contacted' => 'warning',
                        'enrolled'  => 'success',
                        'rejected'  => 'danger',
                        default     => 'gray',
                    }),
                Infolists\Components\TextEntry::make('created_at')
                    ->label('Submitted')
                    ->dateTime('d M Y, H:i'),
            ]),

            // ── Row 2: Personal information ─────────────────────────────
            Infolists\Components\Section::make('Personal Information')
                ->columns(3)
                ->schema([
                    Infolists\Components\TextEntry::make('name')->label('Full Name'),
                    Infolists\Components\TextEntry::make('father_name')->label("Father's Name"),
                    Infolists\Components\TextEntry::make('gender')
                        ->formatStateUsing(fn ($state) => ucfirst((string) $state)),

                    Infolists\Components\TextEntry::make('dob')->label('Date of Birth')->date('d M Y'),
                    Infolists\Components\TextEntry::make('cnic')->label('CNIC / B-Form'),
                    Infolists\Components\TextEntry::make('city'),

                    Infolists\Components\TextEntry::make('phone')->label('Phone'),
                    Infolists\Components\TextEntry::make('student_phone')->label('Student Mobile'),
                    Infolists\Components\TextEntry::make('email'),

                    Infolists\Components\TextEntry::make('campus'),
                    Infolists\Components\TextEntry::make('address')->columnSpan(2),
                ]),

            // ── Row 3: Academic background ──────────────────────────────
            Infolists\Components\Section::make('Academic Background')
                ->columns(4)
                ->schema([
                    Infolists\Components\TextEntry::make('entry_path')
                        ->label('Applying For')
                        ->formatStateUsing(fn ($state) => ucfirst((string) $state))
                        ->badge()
                        ->color('primary'),
                    Infolists\Components\TextEntry::make('qualification')
                        ->formatStateUsing(fn ($state) => ucwords(str_replace('_', ' ', $state ?? '')))
                        ->badge()
                        ->color('gray'),
                    Infolists\Components\TextEntry::make('program.name')->label('Program Interested'),
                    Infolists\Components\IconEntry::make('declare_true')
                        ->label('Declaration Accepted')
                        ->boolean(),
                ]),

            // ── Row 4: Academic record table ────────────────────────────
            Infolists\Components\Section::make('Academic Record')
                ->schema([
                    Infolists\Components\TextEntry::make('academic_details')
                        ->label('')
                        ->columnSpanFull()
                        ->html()
                        ->state(function ($record): string {
                            $details = $record->academic_details;

                            if (blank($details)) {
                                return '<p class="text-sm text-gray-400 italic">No academic details provided.</p>';
                            }

                            $rows = [
                                'ssc'       => 'SSC / O Level',
                                'hssc'      => 'HSSC / A Level',
                                'bachelors' => 'Bachelors',
                                'mabs'      => 'MA / BS',
                            ];

                            $html  = '<div class="overflow-x-auto">';
                            $html .= '<table style="width:100%;border-collapse:collapse;font-size:13px;">';
                            $html .= '<thead>';
                            $html .= '<tr style="background:#f3f4f6;text-align:left;font-size:11px;font-weight:600;color:#6b7280;">';
                            foreach (['Examination','Year','Division','Marks Obtained','Total Marks','Major Subjects','Board / University'] as $h) {
                                $html .= '<th style="padding:8px 12px;border:1px solid #e5e7eb;">' . e($h) . '</th>';
                            }
                            $html .= '</tr></thead><tbody>';

                            foreach ($rows as $key => $label) {
                                $d = is_array($details) ? ($details[$key] ?? null) : null;
                                $empty = !$d || empty(array_filter($d));
                                $bg = $empty ? 'background:#fafafa;color:#9ca3af;' : '';
                                $html .= '<tr style="border-bottom:1px solid #f3f4f6;' . $bg . '">';
                                $html .= '<td style="padding:8px 12px;border:1px solid #e5e7eb;font-weight:600;white-space:nowrap;">' . e($label) . '</td>';
                                $html .= '<td style="padding:8px 12px;border:1px solid #e5e7eb;">' . e($d['year']     ?? '—') . '</td>';
                                $html .= '<td style="padding:8px 12px;border:1px solid #e5e7eb;">' . e(ucfirst($d['division'] ?? '—')) . '</td>';
                                $html .= '<td style="padding:8px 12px;border:1px solid #e5e7eb;text-align:right;">' . e($d['obtained'] ?? '—') . '</td>';
                                $html .= '<td style="padding:8px 12px;border:1px solid #e5e7eb;text-align:right;">' . e($d['total']    ?? '—') . '</td>';
                                $html .= '<td style="padding:8px 12px;border:1px solid #e5e7eb;">' . e($d['major']    ?? '—') . '</td>';
                                $html .= '<td style="padding:8px 12px;border:1px solid #e5e7eb;">' . e($d['board']    ?? '—') . '</td>';
                                $html .= '</tr>';
                            }

                            $html .= '</tbody></table></div>';
                            return $html;
                        }),
                ]),

            // ── Row 4b: Uploaded documents ───────────────────────────────
            Infolists\Components\Section::make('Uploaded Documents')
                ->schema([
                    Infolists\Components\TextEntry::make('documents')
                        ->label('')
                        ->columnSpanFull()
                        ->html()
                        ->state(function ($record): string {
                            $documents = $record->documents;

                            if (blank($documents) || !is_array($documents)) {
                                return '<p class="text-sm text-gray-400 italic">No documents were uploaded with this application.</p>';
                            }

                            $html = '<div style="display:flex;flex-wrap:wrap;gap:10px;">';
                            foreach ($documents as $doc) {
                                $label = e($doc['label'] ?? 'Document');
                                $path  = $doc['path'] ?? null;
                                if (!$path) {
                                    continue;
                                }
                                $url = \Illuminate\Support\Facades\Storage::disk('public')->url($path);
                                $html .= '<a href="' . e($url) . '" target="_blank" rel="noopener" '
                                    . 'style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:8px;background:#f3f4f6;color:#374151;font-size:13px;font-weight:600;text-decoration:none;border:1px solid #e5e7eb;">'
                                    . '📄 ' . $label . '</a>';
                            }
                            $html .= '</div>';

                            return $html;
                        }),
                ]),

            // ── Row 5: Message & Notes ──────────────────────────────────
            Infolists\Components\Section::make('Message & Notes')
                ->columns(2)
                ->schema([
                    Infolists\Components\TextEntry::make('message')
                        ->label('Student Message')
                        ->placeholder('—'),
                    Infolists\Components\TextEntry::make('admin_notes')
                        ->label('Admin Notes')
                        ->placeholder('—'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference_no')->label('Ref')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('entry_path')->label('Type')->badge()->formatStateUsing(fn ($state) => ucfirst((string) $state)),
                Tables\Columns\TextColumn::make('phone')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('program.name')->label('Program')->toggleable(),
                Tables\Columns\TextColumn::make('qualification')->formatStateUsing(fn($state)=>ucfirst($state??''))->badge()->color('gray'),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn(string $state)=>match($state){
                        'new'=>'info','contacted'=>'warning','enrolled'=>'success','rejected'=>'danger',default=>'gray'
                    }),
                Tables\Columns\TextColumn::make('created_at')->label('Received')->date('d M Y')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(['new'=>'New','contacted'=>'Contacted','enrolled'=>'Enrolled','rejected'=>'Rejected']),
                Tables\Filters\SelectFilter::make('program_id')->label('Program')
                    ->options(AcademicProgram::active()->pluck('name','id')),
            ])
            ->actions([
                Tables\Actions\Action::make('convertToStudent')
                    ->label('Enroll as Student')
                    ->icon('heroicon-o-user-plus')
                    ->color('success')
                    ->tooltip('Create a student record pre-filled from this application')
                    ->visible(fn ($record) => $record->status !== 'enrolled')
                    ->url(fn ($record) => \App\Filament\Resources\StudentResource::getUrl('create', ['from_inquiry' => $record->id])),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->defaultSort('created_at','desc')->striped();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAdmissionInquiries::route('/'),
            'view'   => Pages\ViewAdmissionInquiry::route('/{record}'),
            'edit'   => Pages\EditAdmissionInquiry::route('/{record}/edit'),
        ];
    }
}
