<?php

namespace App\Filament\Widgets;

use App\Enums\PaymentStatusEnum;
use App\Enums\StudentStatusEnum;
use App\Enums\TeacherStatusEnum;
use App\Models\BookIssue;
use App\Models\Exam;
use App\Models\FeePayment;
use App\Models\Scholarship;
use App\Models\Student;
use App\Models\Teacher;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class CollegeStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'Developer', 'panel_user']) ?? false;
    }

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $activeStudents  = Student::where('status', StudentStatusEnum::Active->value)->count();
        $totalStudents   = Student::count();
        $activeTeachers  = Teacher::where('status', TeacherStatusEnum::Active->value)->count();

        $feeCollected    = FeePayment::where('payment_status', PaymentStatusEnum::Paid->value)
            ->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount_paid');

        $overdueCount    = FeePayment::where('payment_status', PaymentStatusEnum::Overdue->value)->count();
        $pendingAmount   = FeePayment::whereIn('payment_status', [
            PaymentStatusEnum::Pending->value,
            PaymentStatusEnum::Overdue->value,
        ])->sum('amount_due');

        $booksIssued     = BookIssue::whereNull('return_date')->count();
        $overdueBooks    = BookIssue::whereNull('return_date')
            ->where('due_date', '<', now())
            ->count();

        $upcomingExams   = Exam::where('exam_date', '>=', now())->count();
        $activeScholarships = Scholarship::where('is_active', true)->count();

        $onLeaveStudents = Student::where('status', StudentStatusEnum::OnLeave->value)->count();

        return [
            Stat::make('Active Students', number_format($activeStudents))
                ->description($totalStudents . ' total enrolled')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success')
                ->icon('heroicon-o-user-group'),

            Stat::make('Faculty & Staff', number_format($activeTeachers))
                ->description(Teacher::count() . ' total in system')
                ->descriptionIcon('heroicon-m-users')
                ->color('info')
                ->icon('heroicon-o-users'),

            Stat::make('Fee Collected (This Month)', 'PKR ' . number_format($feeCollected))
                ->description($overdueCount . ' overdue challans')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($overdueCount > 0 ? 'warning' : 'success')
                ->icon('heroicon-o-banknotes'),

            Stat::make('Pending Fees', 'PKR ' . number_format($pendingAmount))
                ->description(FeePayment::where('payment_status', PaymentStatusEnum::Pending->value)->count() . ' pending challans')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger')
                ->icon('heroicon-o-receipt-percent'),

            Stat::make('Books Issued', number_format($booksIssued))
                ->description($overdueBooks . ' overdue returns')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color($overdueBooks > 0 ? 'warning' : 'primary')
                ->icon('heroicon-o-book-open'),

            Stat::make('Upcoming Exams', number_format($upcomingExams))
                ->description(Exam::count() . ' total exams in system')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('primary')
                ->icon('heroicon-o-clipboard-document-list'),

            Stat::make('Active Scholarships', number_format($activeScholarships))
                ->description('Scholarship programs available')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning')
                ->icon('heroicon-o-star'),

            Stat::make('Students on Leave', number_format($onLeaveStudents))
                ->description('Of ' . $totalStudents . ' enrolled students')
                ->descriptionIcon('heroicon-m-pause-circle')
                ->color('gray')
                ->icon('heroicon-o-pause-circle'),
        ];
    }
}
