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

        // All unpaid challans past their due date — includes already-overdue ones so
        // their late fine keeps growing each day the command runs.
        $duePayments = FeePayment::where('due_date', '<', $today)
            ->whereIn('payment_status', [
                PaymentStatusEnum::Pending->value,
                PaymentStatusEnum::Partial->value,
                PaymentStatusEnum::Overdue->value,
            ])
            ->with(['student', 'feeStructure'])
            ->get();

        if ($duePayments->isEmpty()) {
            $this->info('No overdue payments found.');
            return self::SUCCESS;
        }

        // Set status to Overdue and auto-calculate the late fine (days late × daily rate).
        // $toMark = only those NEWLY becoming overdue today (so we notify them once).
        $toMark = collect();

        foreach ($duePayments as $payment) {
            $wasOverdue = $payment->payment_status === PaymentStatusEnum::Overdue;

            $rate = (float) ($payment->feeStructure->late_fine_per_day ?? 0);
            if ($rate > 0 && $payment->due_date) {
                $daysLate = $payment->due_date->diffInDays($today);
                $payment->fine_amount = round($daysLate * $rate, 2);
            }
            $payment->payment_status = PaymentStatusEnum::Overdue;
            $payment->saveQuietly(); // skip audit-log spam on daily fine recalculation

            if (! $wasOverdue) {
                $toMark->push($payment);
            }
        }

        if ($toMark->isEmpty()) {
            $this->info('Late fines updated; no newly overdue payments to notify.');
            return self::SUCCESS;
        }

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
