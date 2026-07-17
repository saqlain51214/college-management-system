<?php

namespace App\Console\Commands;

use App\Enums\FeeTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\FeePayment;
use App\Models\User;
use App\Services\NotificationService;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CheckOverdueFees extends Command
{
    protected $signature   = 'fees:check-overdue';
    protected $description = 'Mark overdue fee payments, notify students, and alert admins';

    public function handle(): int
    {
        $today = Carbon::today();

        // Collect payments that will become overdue (before mass update so we have the collection)
        $toMark = FeePayment::where('due_date', '<', $today)
            ->whereIn('payment_status', [
                PaymentStatusEnum::Pending->value,
                PaymentStatusEnum::Partial->value,
            ])
            ->with('student')
            ->get();

        if ($toMark->isEmpty()) {
            $this->info('No overdue payments found.');
            return self::SUCCESS;
        }

        // Mass update status
        FeePayment::whereIn('id', $toMark->pluck('id'))
            ->update(['payment_status' => PaymentStatusEnum::Overdue->value]);

        // Notify each student individually
        $svc = app(NotificationService::class);
        $notified = 0;

        foreach ($toMark as $payment) {
            if (!$payment->student) continue;

            $feeType = $payment->fee_type instanceof FeeTypeEnum
                ? $payment->fee_type->label()
                : ($payment->fee_type ?? 'Fee');

            $svc->send($payment->student, 'fee_overdue', [
                'student_name' => $payment->student->name,
                'amount'       => number_format((float) $payment->amount_due),
                'fee_type'     => $feeType,
                'due_date'     => $payment->due_date?->format('d M Y') ?? 'N/A',
            ]);
            $notified++;
        }

        // Notify Filament admin panel users
        $totalOverdue = FeePayment::where('payment_status', PaymentStatusEnum::Overdue->value)->count();
        $totalAmount  = FeePayment::where('payment_status', PaymentStatusEnum::Overdue->value)->sum('amount_due');
        $admins       = User::role(['super_admin', 'Developer'])->get();

        Notification::make()
            ->warning()
            ->title('Fee Overdue Alert')
            ->body(
                number_format($totalOverdue) . ' challan(s) are overdue. ' .
                'Total pending: PKR ' . number_format($totalAmount) . '. ' .
                '(' . $toMark->count() . ' newly marked today.)'
            )
            ->actions([
                Action::make('view')
                    ->label('View Overdue Fees')
                    ->button()
                    ->url(url('/admin/fee-payments?tableFilters[payment_status][value]=overdue')),
            ])
            ->sendToDatabase($admins);

        $this->info("Marked {$toMark->count()} payment(s) as overdue. Notified {$notified} student(s) and {$admins->count()} admin(s).");

        return self::SUCCESS;
    }
}
