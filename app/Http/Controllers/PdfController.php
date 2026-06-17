<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\CollegeSetting;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\FeePayment;
use App\Models\Student;
use Illuminate\Http\Response;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class PdfController extends Controller
{
    private function college(): array
    {
        return [
            'name'        => CollegeSetting::get('college_name',        'Jinnah School & Degree College Astore'),
            'short_name'  => CollegeSetting::get('college_short_name',  'JDCA'),
            'address'     => CollegeSetting::get('college_address',     'Distt. Astore Village Eidgah, Astore'),
            'city'        => CollegeSetting::get('college_city',        'Astore, Gilgit Baltistan 14300'),
            'phone'       => CollegeSetting::get('college_phone',       '+923129776585'),
            'email'       => CollegeSetting::get('college_email',       'jinnahschooldegreecollege@gmail.com'),
            'principal'   => CollegeSetting::get('college_principal',   'Arif Ali'),
            'affiliation' => CollegeSetting::get('college_affiliation', 'Karakoram International University'),
            'website'     => CollegeSetting::get('college_website',     'https://JDCA.edu.pk'),
        ];
    }

    private function pdf(int $marginTop = 30): Mpdf
    {
        return new Mpdf([
            'mode'          => 'utf-8',
            'format'        => 'A4',
            'margin_top'    => $marginTop,
            'margin_right'  => 15,
            'margin_bottom' => 22,
            'margin_left'   => 15,
            'margin_header' => 4,
            'margin_footer' => 4,
        ]);
    }

    private function send(string $view, array $data, string $filename, int $marginTop = 30): Response
    {
        $html = view($view, $data)->render();
        $pdf  = $this->pdf($marginTop);
        $pdf->WriteHTML($html);

        return response($pdf->Output($filename, Destination::STRING_RETURN))
            ->header('Content-Type',        'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function feeChallan(FeePayment $payment): Response
    {
        $payment->load(['student.academicProgram', 'student.academicYear', 'feeStructure', 'academicYear']);
        return $this->send('pdf.fee-challan', ['payment' => $payment, 'college' => $this->college()], 'challan-' . $payment->challan_number . '.pdf');
    }

    public function studentTranscript(Student $student): Response
    {
        $student->load(['academicProgram', 'academicYear', 'department']);
        $college = $this->college();
        $minPct  = (int) CollegeSetting::get('attendance_min_percent', 75);

        $results = ExamResult::with('exam.course')
            ->where('student_id', $student->id)
            ->orderBy('created_at')
            ->get();

        $attendanceSummary = AttendanceRecord::with('session.course')
            ->where('student_id', $student->id)
            ->get()
            ->groupBy(fn($r) => $r->session?->course_id)
            ->map(function ($records) use ($minPct) {
                $course  = $records->first()?->session?->course;
                $total   = $records->count();
                $present = $records->where('status', 'present')->count();
                $late    = $records->where('status', 'late')->count();
                $absent  = $records->where('status', 'absent')->count();
                $pct     = $total > 0 ? round(($present + $late) / $total * 100, 1) : 0;
                return [
                    'course'   => $course ? ($course->code . ' — ' . $course->name) : 'Unknown',
                    'total'    => $total,
                    'present'  => $present,
                    'late'     => $late,
                    'absent'   => $absent,
                    'pct'      => $pct,
                    'eligible' => $pct >= $minPct,
                ];
            })->values();

        return $this->send('pdf.student-transcript',
            compact('student', 'results', 'attendanceSummary', 'college', 'minPct'),
            'transcript-' . $student->roll_number . '.pdf', 32);
    }

    public function studentAttendance(Student $student): Response
    {
        $student->load(['academicProgram', 'department']);
        $college    = $this->college();
        $minPercent = (float) CollegeSetting::get('attendance_min_percent', 75);

        $courseSummary = AttendanceRecord::with('session.course')
            ->where('student_id', $student->id)
            ->get()
            ->groupBy(fn($r) => $r->session?->course_id)
            ->map(function ($records) use ($minPercent) {
                $course   = $records->first()?->session?->course;
                $total    = $records->count();
                $present  = $records->where('status', 'present')->count();
                $late     = $records->where('status', 'late')->count();
                $leave    = $records->where('status', 'leave')->count();
                $absent   = $records->where('status', 'absent')->count();
                $effective = $present + $late;
                $pct      = $total > 0 ? round($effective / $total * 100, 1) : 0;
                return [
                    'course'   => $course ? ($course->code . ' — ' . $course->name) : 'Unknown',
                    'total'    => $total, 'present' => $present, 'late' => $late,
                    'leave'    => $leave, 'absent' => $absent,
                    'pct'      => $pct, 'eligible' => $pct >= $minPercent,
                ];
            })->values();

        $overallTotal    = $courseSummary->sum('total');
        $overallEffective = $courseSummary->sum(fn($c) => $c['present'] + $c['late']);
        $overallPct      = $overallTotal > 0 ? round($overallEffective / $overallTotal * 100, 1) : 0;

        return $this->send('pdf.student-attendance',
            compact('student', 'college', 'courseSummary', 'overallPct', 'minPercent'),
            'attendance-' . $student->roll_number . '.pdf');
    }

    public function examResultSheet(Exam $exam): Response
    {
        $exam->load(['course', 'academicProgram', 'academicYear']);
        $college      = $this->college();
        $passingMarks = $exam->passing_marks ?? 40;

        $results = ExamResult::with('student')
            ->where('exam_id', $exam->id)
            ->orderByDesc('marks_obtained')
            ->get();

        $appeared = $results->where('is_absent', false)->count();
        $passed   = $results->where('is_absent', false)
                        ->filter(fn($r) => $r->marks_obtained !== null && $r->marks_obtained >= $passingMarks)
                        ->count();
        $highest  = $results->where('is_absent', false)->max('marks_obtained') ?? 0;
        $lowest   = $results->where('is_absent', false)->min('marks_obtained') ?? 0;
        $average  = $appeared > 0 ? round($results->where('is_absent', false)->avg('marks_obtained'), 1) : 0;
        $passRate = $appeared > 0 ? round($passed / $appeared * 100, 1) : 0;

        $stats = compact('appeared', 'passed', 'highest', 'lowest', 'average', 'passRate', 'passingMarks');

        return $this->send('pdf.exam-result-sheet',
            compact('exam', 'college', 'results', 'stats'),
            'result-' . \Illuminate\Support\Str::slug($exam->title) . '.pdf');
    }
}
