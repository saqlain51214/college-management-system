<?php

namespace App\Console\Commands;

use App\Enums\FeeTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\CollegeSetting;
use App\Models\FeePayment;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendFeeDueReminders extends Command
{
    protected $signature   = 'fees:send-due-reminders';
    protected $description = 'Remind students of fee challans due in the next few days (sent once per challan)';

    public function handle(): int
    {
        $today      = Carbon::today();
        $daysBefore = (int) CollegeSetting::get('fee_reminder_days_before', 3);
        $windowEnd  = $today->copy()->addDays($daysBefore);

        $upcoming = FeePayment::whereBetween('due_date', [$today, $windowEnd])
            ->whereIn('payment_status', [PaymentStatusEnum::Pending->value, PaymentStatusEnum::Partial->value])
            ->whereNull('due_reminder_sent_at')
            ->with('student')
            ->get();

        if ($upcoming->isEmpty()) {
            $this->info('No upcoming due challans to remind.');
            return self::SUCCESS;
        }

        $svc     = app(NotificationService::class);
        $sent    = 0;

        foreach ($upcoming as $payment) {
            if (! $payment->student) {
                continue;
            }

            $feeType = $payment->fee_type instanceof FeeTypeEnum ? $payment->fee_type->label() : ($payment->fee_type ?? 'Fee');

            $svc->send($payment->student, 'fee_due_reminder', [
                'student_name' => $payment->student->name,
                'amount'       => number_format((float) $payment->amount_due),
                'fee_type'     => $feeType,
                'challan'      => $payment->challan_number,
                'due_date'     => $payment->due_date?->format('d M Y') ?? 'N/A',
            ]);

            $payment->due_reminder_sent_at = now();
            $payment->saveQuietly();
            $sent++;
        }

        $this->info("Sent {$sent} due-date reminder(s).");

        return self::SUCCESS;
    }
}
