<?php
namespace App\Filament\Widgets;
use App\Models\Book;
use App\Models\BookIssue;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LibraryStatsWidget extends BaseWidget
{
    protected ?string $heading = 'Library Overview';
    protected static ?int $sort = 7;
    protected int|string|array $columnSpan = 1;

    public static function canView(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin','Developer','panel_user']) ?? false;
    }

    protected function getStats(): array
    {
        $totalBooks     = Book::sum('total_copies');
        $availableBooks = Book::sum('available_copies');
        $issuedNow      = BookIssue::whereNull('return_date')->count();
        $overdueBooks   = BookIssue::whereNull('return_date')->where('due_date','<',now())->count();
        $totalFine      = BookIssue::where('fine_amount','>',0)->where('fine_paid',false)->sum('fine_amount');

        return [
            Stat::make('Total Books', number_format($totalBooks))
                ->description($availableBooks . ' available')
                ->icon('heroicon-o-book-open')
                ->color('primary'),
            Stat::make('Currently Issued', number_format($issuedNow))
                ->description($overdueBooks . ' overdue')
                ->icon('heroicon-o-arrow-up-tray')
                ->color($overdueBooks > 0 ? 'danger' : 'success'),
            Stat::make('Pending Fines', 'PKR ' . number_format($totalFine))
                ->description('Unpaid book return fines')
                ->icon('heroicon-o-exclamation-triangle')
                ->color($totalFine > 0 ? 'warning' : 'gray'),
        ];
    }
}
