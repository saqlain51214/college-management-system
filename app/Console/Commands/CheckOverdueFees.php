<?php

namespace App\Console\Commands;

use App\Enums\PaymentStatusEnum;
use App\Models\FeePayment;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CheckOverdueFees extends Command
{
    protected $signature   = 'fees:check-overdue';
    protected $description = 'Mark overdue fee payments and notify admins';

    public function handle(): int
    {
        $today = Carbon::today();

        // Mark pending/partial payments past due date as overdue
        $updated = FeePayment::where('due_date', '<', $today)
            ->whereIn('payment_status', [
                PaymentStatusEnum::Pending->value,
                PaymentStatusEnum::Partial->value,
            ])
            ->update(['payment_status' => PaymentStatusEnum::Overdue->value]);

        if ($updated === 0) {
            $this->info('No overdue payments found.');
            return self::SUCCESS;
        }

        // Count total overdue for notification
        $totalOverdue = FeePayment::where('payment_status', PaymentStatusEnum::Overdue->value)->count();
        $totalAmount  = FeePayment::where('payment_status', PaymentStatusEnum::Overdue->value)->sum('amount_due');

        // Send Filament database notification to all super_admin users
        $admins = User::role(['super_admin', 'Developer'])->get();

        Notification::make()
            ->warning()
            ->title('Fee Overdue Alert')
            ->body(
                number_format($totalOverdue) . ' challan(s) are overdue. ' .
                'Total pending: PKR ' . number_format($totalAmount) . '. ' .
                '(' . $updated . ' newly marked today.)'
            )
            ->actions([
                Action::make('view')
                    ->label('View Overdue Fees')
                    ->button()
                    ->url(url('/admin/fee-payments?tableFilters[payment_status][value]=overdue')),
            ])
            ->sendToDatabase($admins);

        $this->info("Marked $updated payments as overdue. Notified " . $admins->count() . " admin(s).");

        return self::SUCCESS;
    }
}
