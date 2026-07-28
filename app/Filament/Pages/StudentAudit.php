<?php

namespace App\Filament\Pages;

use App\Models\ActivityLog;
use App\Models\FeePayment;
use App\Models\Student;
use Filament\Pages\Page;

/**
 * A single student's full history — profile changes, scholarship/discount
 * changes, challan generation, payments, refunds — pulled from the same
 * ActivityLog rows every other action in the system already writes to, just
 * filtered down to one student (directly, or via their fee challans) instead
 * of the noisy system-wide log. Reachable via the "Audit" button on the
 * Student table, or a direct link with ?student={id}.
 */
class StudentAudit extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Students & Admissions';

    protected static ?string $navigationLabel = 'Student Audit';

    protected static ?string $title = 'Student Audit History';

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.student-audit';

    public ?int $studentId = null;

    /** @var array<string,mixed>|null */
    public ?array $student = null;

    /** @var array<int,array<string,mixed>> */
    public array $logs = [];

    public function mount(): void
    {
        $id = (int) (request()->query('student') ?? 0);

        if (! $id) {
            return;
        }

        $student = Student::with(['academicProgram', 'department'])->find($id);
        if (! $student) {
            return;
        }

        $this->studentId = $student->id;
        $this->student = [
            'name' => $student->name,
            'father' => $student->father_name,
            'roll' => $student->roll_number,
            'reg' => $student->registration_number,
            'program' => $student->academicProgram?->name,
            'department' => $student->department?->name,
        ];

        $paymentIds = FeePayment::where('student_id', $student->id)->pluck('id')->all();

        $this->logs = ActivityLog::query()
            ->where(fn ($q) => $q->where('subject_type', 'student')->where('subject_id', $student->id))
            ->when($paymentIds, fn ($q) => $q->orWhere(
                fn ($q2) => $q2->where('subject_type', 'fee_payment')->whereIn('subject_id', $paymentIds)
            ))
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (ActivityLog $log) => [
                'date' => $log->created_at?->format('d M Y, h:i A'),
                'event' => $log->event,
                'message' => $log->message,
                'actor' => $log->actor_summary,
                'level' => $log->level_label,
                'level_color' => $log->levelColor(),
            ])
            ->all();
    }

    public function getPdfUrl(): ?string
    {
        return $this->studentId ? route('pdf.student-audit', $this->studentId) : null;
    }
}
