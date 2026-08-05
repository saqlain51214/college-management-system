<?php

namespace App\Models;

use App\Enums\RefundStatusEnum;
use App\Services\NotificationService;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeRefund extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id', 'fee_payment_id', 'amount', 'reason', 'status',
        'requested_by', 'approved_by', 'approved_at', 'remarks',
    ];

    protected $casts = [
        'status'      => RefundStatusEnum::class,
        'amount'      => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function student(): BelongsTo    { return $this->belongsTo(Student::class); }
    public function feePayment(): BelongsTo { return $this->belongsTo(FeePayment::class); }
    public function requestedBy(): BelongsTo { return $this->belongsTo(User::class, 'requested_by'); }
    public function approvedBy(): BelongsTo  { return $this->belongsTo(User::class, 'approved_by'); }

    public function approve(int $approverId, ?string $remarks = null): void
    {
        $this->status      = RefundStatusEnum::Approved;
        $this->approved_by = $approverId;
        $this->approved_at = now();
        if ($remarks) {
            $this->remarks = $remarks;
        }
        $this->save();

        if ($this->student) {
            app(NotificationService::class)->send($this->student, 'refund_approved', [
                'student_name' => $this->student->name,
                'amount'       => number_format((float) $this->amount),
                'reason'       => $this->reason,
            ]);
        }
    }

    public function reject(int $approverId, ?string $remarks = null): void
    {
        $this->status      = RefundStatusEnum::Rejected;
        $this->approved_by = $approverId;
        $this->approved_at = now();
        if ($remarks) {
            $this->remarks = $remarks;
        }
        $this->save();

        if ($this->student) {
            app(NotificationService::class)->send($this->student, 'refund_rejected', [
                'student_name' => $this->student->name,
                'amount'       => number_format((float) $this->amount),
                'reason'       => $this->remarks ?: 'Not specified.',
            ]);
        }
    }

    protected static function booted(): void
    {
        static::created(function (self $refund): void {
            // Only query roles that actually exist for this guard — mirrors the
            // same guard used by CheckOverdueFees's admin alert, so a missing
            // dev-only role never crashes a refund request in production.
            $existingRoles = \Spatie\Permission\Models\Role::whereIn('name', ['super_admin', 'Developer'])
                ->where('guard_name', 'web')->pluck('name')->all();
            $admins = $existingRoles ? User::role($existingRoles)->get() : collect();

            Notification::make()
                ->warning()
                ->title('Refund Request Pending')
                ->body(
                    ($refund->student?->name ?? 'A student') . ' requested a refund of Rs. ' .
                    number_format((float) $refund->amount) . '. Reason: ' . $refund->reason
                )
                ->actions([
                    Action::make('view')
                        ->label('Review Refund')
                        ->button()
                        ->url(url('/admin/fee-refunds')),
                ])
                ->sendToDatabase($admins);
        });
    }
}
