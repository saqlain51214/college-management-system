<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookIssueResource\Pages;
use App\Models\Book;
use App\Models\BookIssue;
use App\Models\Student;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BookIssueResource extends Resource
{
    protected static ?string $model = BookIssue::class;

    protected static ?string $navigationIcon  = 'heroicon-o-arrow-right-on-rectangle';
    protected static ?string $navigationGroup = 'Library';
    protected static ?string $navigationLabel = 'Book Issues';
    protected static ?int    $navigationSort  = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Issue Details')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('book_id')
                        ->label('Book')
                        ->options(fn() => Book::available()->active()->orderBy('title')
                            ->get()->mapWithKeys(fn($b) => [$b->id => $b->accession_number . ' — ' . $b->title]))
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\Select::make('student_id')
                        ->label('Student (Borrower)')
                        ->options(fn() => Student::where('is_active', true)->orderBy('name')
                            ->get()->mapWithKeys(fn($s) => [$s->id => $s->roll_number . ' — ' . $s->name]))
                        ->searchable()
                        ->preload()
                        ->live()
                        ->helperText('Either student OR teacher'),

                    Forms\Components\Select::make('teacher_id')
                        ->label('Teacher (Borrower)')
                        ->options(fn() => Teacher::where('is_active', true)->orderBy('name')->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->helperText('Leave blank if student'),

                    Forms\Components\DatePicker::make('issue_date')->label('Issue Date')->required()->default(now())->displayFormat('d M Y')->native(false),
                    Forms\Components\DatePicker::make('due_date')->label('Due Date')->required()->default(now()->addDays(14))->displayFormat('d M Y')->native(false),
                    Forms\Components\DatePicker::make('return_date')->label('Return Date')->displayFormat('d M Y')->native(false)->helperText('Fill when book is returned.'),

                    Forms\Components\Select::make('condition_on_issue')
                        ->label('Condition on Issue')
                        ->options(fn() => \App\Models\ListItem::getOptions('book_condition'))
                        ->default('good'),

                    Forms\Components\Select::make('condition_on_return')
                        ->label('Condition on Return')
                        ->options(fn() => \App\Models\ListItem::getOptions('book_condition'))
                        ->placeholder('N/A'),

                    Forms\Components\TextInput::make('fine_amount')->label('Fine Amount (PKR)')->numeric()->default(0)->prefix('Rs.'),
                    Forms\Components\Toggle::make('fine_paid')->label('Fine Paid')->default(false)->onColor('success'),
                    Forms\Components\TextInput::make('issued_by')->label('Issued By (Staff)')->maxLength(100)->placeholder('Librarian name'),
                    Forms\Components\Textarea::make('remarks')->label('Remarks')->rows(2)->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('book.accession_number')->label('Acc. No.')->badge()->color('gray')->searchable(),
                Tables\Columns\TextColumn::make('book.title')->label('Book Title')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('student.name')->label('Student')->searchable()->placeholder('—'),
                Tables\Columns\TextColumn::make('teacher.name')->label('Teacher')->searchable()->placeholder('—')->toggleable(),
                Tables\Columns\TextColumn::make('issue_date')->label('Issued')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('due_date')->label('Due')->date('d M Y')->sortable()
                    ->color(fn(BookIssue $r) => is_null($r->return_date) && $r->due_date < now()->toDateString() ? 'danger' : null),
                Tables\Columns\TextColumn::make('return_date')->label('Returned')->date('d M Y')->placeholder('Not Returned')->sortable(),
                Tables\Columns\TextColumn::make('fine_amount')->label('Fine')->money('PKR')->placeholder('—'),
                Tables\Columns\IconColumn::make('fine_paid')->label('Fine Paid')->boolean()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('not_returned')
                    ->label('Not Yet Returned')
                    ->query(fn($q) => $q->whereNull('return_date')),
                Tables\Filters\Filter::make('overdue')
                    ->label('Overdue')
                    ->query(fn($q) => $q->whereNull('return_date')->where('due_date', '<', now()->toDateString())),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('return')
                    ->label('Return Book')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('success')
                    ->iconButton()
                    ->requiresConfirmation()
                    ->visible(fn(BookIssue $r) => is_null($r->return_date))
                    ->action(function (BookIssue $record) {
                        $record->update(['return_date' => now()->toDateString()]);
                        $record->book->increment('available_copies');
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->defaultSort('issue_date', 'desc')
            ->paginated([10, 25, 50, 100])
            ->striped();
    }

    public static function getNavigationBadge(): ?string
    {
        try { return (string) BookIssue::whereNull('return_date')->where('due_date', '<', now()->toDateString())->count() ?: null; }
        catch (\Exception) { return null; }
    }

    public static function getNavigationBadgeColor(): ?string { return 'danger'; }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBookIssues::route('/'),
            'create' => Pages\CreateBookIssue::route('/create'),
            'edit'   => Pages\EditBookIssue::route('/{record}/edit'),
        ];
    }
}
