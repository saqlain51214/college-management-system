<?php

namespace App\Filament\Pages;

use App\Enums\PaymentStatusEnum;
use App\Models\Student;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;

class StudentLedger extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'Finance';

    protected static ?string $navigationLabel = 'Student Account';

    protected static ?string $title = 'Student Account — Fee Ledger';

    protected static ?int $navigationSort = 8;

    protected static string $view = 'filament.pages.student-ledger';

    /** Search box value (registration number or roll number). */
    public string $q = '';

    public bool $searched = false;

    public ?string $notFound = null;

    /** @var array<string,mixed>|null */
    public ?array $student = null;

    /** @var array<int,array<string,mixed>> */
    public array $payments = [];

    /** @var array<string,float|int> */
    public array $totals = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin', 'Developer', 'panel_user']) ?? false;
    }

    public function search(): void
    {
        $this->reset(['student', 'payments', 'totals', 'notFound']);
        $this->searched = true;

        $term = trim($this->q);
        if ($term === '') {
            $this->notFound = 'Please enter a registration number or roll number.';
            return;
        }

        $student = Student::with(['academicProgram', 'department'])
            ->where('registration_number', $term)
            ->orWhere('roll_number', $term)
            ->first();

        if (! $student) {
            $this->notFound = "No student found for \"{$term}\". Check the registration/roll number.";
            return;
        }

        $this->student = [
            'name'        => $student->name,
            'father'      => $student->father_name,
            'reg'         => $student->registration_number,
            'roll'        => $student->roll_number,
            'program'     => $student->academicProgram?->name,
            'department'  => $student->department?->name,
            'phone'       => $student->phone,
            'gender'      => $student->gender instanceof \BackedEnum ? $student->gender->value : $student->gender,
            'active'      => (bool) $student->is_active,
        ];

        $today   = Carbon::today();
        $billed  = 0.0;
        $paid    = 0.0;
        $rows    = [];

        foreach ($student->feePayments()->orderByDesc('due_date')->orderByDesc('id')->get() as $p) {
            $net       = (float) $p->amount_due + (float) $p->fine_amount - (float) $p->discount_amount;
            $amtPaid   = (float) $p->amount_paid;
            $balance   = max(0, $net - $amtPaid);
            $billed   += $net;
            $paid     += $amtPaid;
            $status    = $p->payment_status instanceof PaymentStatusEnum ? $p->payment_status->value : (string) $p->payment_status;
            $daysLate  = ($balance > 0 && $p->due_date && $today->gt(Carbon::parse($p->due_date)))
                ? $today->diffInDays(Carbon::parse($p->due_date))
                : 0;

            $rows[] = [
                'id'       => $p->id,
                'challan'  => $p->challan_number,
                'fee_type' => $p->fee_type instanceof \BackedEnum ? ucwords(str_replace('_', ' ', $p->fee_type->value)) : ucwords(str_replace('_', ' ', (string) $p->fee_type)),
                'semester' => $p->semester_number,
                'net'      => $net,
                'paid'     => $amtPaid,
                'balance'  => $balance,
                'status'   => $status,
                'due'      => $p->due_date ? Carbon::parse($p->due_date)->format('d M Y') : '—',
                'paid_on'  => $p->payment_date ? Carbon::parse($p->payment_date)->format('d M Y') : '—',
                'days_late'=> $daysLate,
            ];
        }

        $this->payments = $rows;
        $this->totals   = [
            'billed'      => $billed,
            'paid'        => $paid,
            'outstanding' => max(0, $billed - $paid),
            'count'       => count($rows),
            'unpaid'      => collect($rows)->where('balance', '>', 0)->count(),
        ];
    }
}
