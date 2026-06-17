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
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('father_name')->label("Father's Name"),
                Forms\Components\TextInput::make('phone')->required(),
                Forms\Components\TextInput::make('email')->email()->nullable(),
                Forms\Components\Select::make('program_id')->label('Program Interested')
                    ->options(AcademicProgram::active()->pluck('name','id'))->nullable()->searchable(),
                Forms\Components\Select::make('qualification')
                    ->options(['matric'=>'Matric','intermediate'=>'Intermediate','bachelor'=>'Bachelor','other'=>'Other'])
                    ->nullable(),
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
            Infolists\Components\Section::make('Inquiry Details')->columns(2)->schema([
                Infolists\Components\TextEntry::make('name'),
                Infolists\Components\TextEntry::make('father_name')->label("Father's Name"),
                Infolists\Components\TextEntry::make('phone'),
                Infolists\Components\TextEntry::make('email'),
                Infolists\Components\TextEntry::make('program.name')->label('Program'),
                Infolists\Components\TextEntry::make('qualification')->formatStateUsing(fn($state)=>ucfirst($state??'')),
                Infolists\Components\TextEntry::make('status')->badge()
                    ->color(fn($r)=>match($r->status){
                        'new'=>'info','contacted'=>'warning','enrolled'=>'success','rejected'=>'danger',default=>'gray'
                    }),
                Infolists\Components\TextEntry::make('created_at')->label('Submitted')->dateTime('d M Y, H:i'),
                Infolists\Components\TextEntry::make('message')->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
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
