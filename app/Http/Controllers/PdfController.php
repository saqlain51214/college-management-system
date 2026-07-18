<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\CollegeSetting;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\FeePayment;
use App\Models\FeeSlipTemplate;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class PdfController extends Controller
{
    private function college(): array
    {
        return [
            'name'        => CollegeSetting::get('college_name',        'Jinnah Degree College Astore'),
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

        $template = FeeSlipTemplate::active();

        $orientation = $template?->orientation ?? 'landscape';
        $view = match ($template?->variant ?? 'kiu') {
            'classic' => 'pdf.slip-classic',
            'modern'  => 'pdf.slip-modern',
            'minimal' => 'pdf.slip-minimal',
            default   => 'pdf.slip-kiu',
        };

        $pdf = Pdf::loadView($view, compact('payment', 'template'))
            ->setPaper('a4', $orientation)
            ->setOption(['defaultFont' => 'dejavu sans', 'isRemoteEnabled' => false, 'isPhpEnabled' => false]);

        $filename = 'challan-' . $payment->challan_number . '.pdf';
        return response($pdf->output())
            ->header('Content-Type',        'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

    /**
     * Browser preview of a fee slip template using sample data.
     */
    public function feeSlipPreview(FeeSlipTemplate $template): \Illuminate\Http\Response
    {
        $data = $this->samplePaymentData();
        return response(view('pdf.slip-preview', compact('template', 'data'))->render())
            ->header('Content-Type', 'text/html');
    }

    /**
     * PDF download of a fee slip template using sample data.
     */
    public function feeSlipPreviewPdf(FeeSlipTemplate $template): Response
    {
        $data = $this->samplePaymentData();
        $orientation = $template->orientation ?? 'landscape';
        $view = match ($template->variant) {
            'classic' => 'pdf.slip-classic',
            'modern'  => 'pdf.slip-modern',
            'minimal' => 'pdf.slip-minimal',
            default   => 'pdf.slip-kiu',
        };

        // For preview, payment is null — views must handle null $payment gracefully
        $payment = null;
        $pdf = Pdf::loadView($view, compact('payment', 'template', 'data'))
            ->setPaper('a4', $orientation)
            ->setOption(['defaultFont' => 'dejavu sans', 'isRemoteEnabled' => false, 'isPhpEnabled' => false]);

        $filename = 'preview-' . \Illuminate\Support\Str::slug($template->name) . '.pdf';
        return response($pdf->output())
            ->header('Content-Type',        'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

    /**
     * Sample student/payment data used for template previews.
     */
    private function samplePaymentData(): array
    {
        return [
            'student_name'    => 'Jaffar Ali',
            'father_name'     => 'Muhammad Ali',
            'roll_number'     => '24609',
            'program'         => 'BS Information Technology',
            'semester_no'     => 4,
            'session'         => 'Spring 2026',
            'challan_number'  => 'JDCA-2026-0042',
            'amount_due'      => 48800,
            'fine_amount'     => 0,
            'discount_amount' => 0,
            'net_amount'      => 48800,
            'due_date'        => '30-06-2026',
            'payment_status'  => 'pending',
            'is_paid'         => false,
            'fee_label'       => 'Semester Fee',
        ];
    }

    public function feeChallanPreview(FeePayment $payment): \Illuminate\Http\Response
    {
        $payment->load(['student.academicProgram', 'student.academicYear', 'feeStructure', 'academicYear']);
        $template = \App\Models\FeeSlipTemplate::active();
        return response(view('portal.fee-challan-preview', compact('payment', 'template'))->render())
            ->header('Content-Type', 'text/html');
    }

    private function buildChallanHtml(FeePayment $payment): string
    {
        $s       = $payment->student;
        $name    = e($s?->name         ?? '—');
        $father  = e($s?->father_name  ?? '—');
        $roll    = e($s?->roll_number  ?? '—');
        $program = e($s?->academicProgram?->name ?? '—');
        $semNo   = e($payment->semester_number   ?? '—');
        $session = e($payment->academicYear?->name ?? '—');

        $due      = (float)($payment->amount_due      ?? 0);
        $fine     = (float)($payment->fine_amount     ?? 0);
        $disc     = (float)($payment->discount_amount ?? 0);
        $net      = $due + $fine - $disc;

        $statusVal = $payment->payment_status instanceof \BackedEnum
                     ? $payment->payment_status->value : (string)$payment->payment_status;
        $isPaid    = strtolower($statusVal) === 'paid';
        $paidDate  = $isPaid && $payment->payment_date
                     ? \Carbon\Carbon::parse($payment->payment_date)->format('d-m-Y') : '';
        $dueDate   = $payment->due_date
                     ? \Carbon\Carbon::parse($payment->due_date)->format('d-m-Y') : '';

        $bankName  = e(CollegeSetting::get('fee_bank_name',           'HBL'));
        $bankAcct  = e(CollegeSetting::get('fee_bank_account',        '—'));
        $bankTitle = e(CollegeSetting::get('fee_bank_account_title',  CollegeSetting::get('college_name','JDCA')));
        $bankBranch= e(CollegeSetting::get('fee_bank_branch',         ''));
        $billPfx   = CollegeSetting::get('fee_challan_1bill_prefix',  '');
        $refPfx    = e(CollegeSetting::get('fee_challan_ref_prefix',  'JDCA'));
        $sn        = e($payment->challan_number ?? '—');
        $refNo     = $refPfx . '-' . $sn;
        $billNo    = $billPfx ? e($billPfx . $payment->challan_number) : '';

        $college   = e(CollegeSetting::get('college_name', 'Jinnah Degree College Astore'));
        $city      = e(CollegeSetting::get('college_city', 'Astore, Gilgit-Baltistan'));
        $phone     = e(CollegeSetting::get('college_phone',''));

        $feeLabel  = e($payment->feeStructure?->title
            ?? ucwords(str_replace('_', ' ', $payment->fee_type instanceof \BackedEnum
                ? $payment->fee_type->value : ($payment->fee_type ?? 'Semester Fee'))));

        $inWords   = $this->numberToWords((int) round($net));
        $stamp     = $isPaid ? 'FEE PAID' : 'PENDING';
        $stampClr  = $isPaid ? '#2a7a2a'  : '#b94a00';
        $dateLabel = $paidDate ?: '________________';
        $bankBranchStr = $bankBranch ? ", $bankBranch" : '';
        $billLine  = $billNo ? " &nbsp;&#8226; 1-Bill no: <b>$billNo</b>." : '';
        $instr     = "&#8226; Submit at any {$bankName} branch or via {$bankName} Mobile App.{$billLine} &nbsp;&#8226; Keep as proof. Late fine applies after due date.";

        $feeRows = "<tr><td>1</td><td>{$feeLabel}</td><td align='right'>" . number_format($due, 0) . "</td></tr>";
        if ($fine > 0) {
            $lateLabel = 'Late Surcharge' . ($dueDate ? " After ($dueDate)" : '');
            $feeRows .= "<tr><td>2</td><td>$lateLabel</td><td align='right'>" . number_format($fine, 2) . "</td></tr>";
        }
        if ($disc > 0) {
            $discRow = $fine > 0 ? 3 : 2;
            $feeRows .= "<tr><td>$discRow</td><td>Discount / Concession</td><td align='right'>- " . number_format($disc, 0) . "</td></tr>";
        }
        $feeRows .= "<tr><td colspan='2' align='right'><b>Total Payable</b></td><td align='right'><b>" . number_format($net, 0) . "</b></td></tr>";

        $css = '
            body{font-family:dejavusans,sans-serif;font-size:7.5pt;color:#111;}
            .lbl{font-size:7pt;font-weight:bold;color:#444;}
            .ft{width:100%;font-size:7pt;}
            .ft th{background:#1a1a1a;color:#fff;padding:2pt 4pt;text-align:left;font-size:6.5pt;}
            .ft td{padding:2pt 4pt;border:0.4pt solid #ccc;}
        ';

        $copies = ['Bank Copy', 'Accounts Copy', 'Student Copy'];
        $body   = '';

        foreach ($copies as $i => $label) {
            if ($i > 0) {
                $body .= '<p style="text-align:center;font-size:6pt;color:#bbb;border-top:0.8pt dashed #bbb;padding-top:1.5pt;margin:4mm 0;">&#9988; &nbsp; cut here &nbsp; &#9988;</p>';
            }

            // ── Copy label ──
            $body .= '<table width="100%" cellpadding="0" cellspacing="0" style="border:0.7pt solid #444;margin-bottom:1mm;">';
            $body .= '<tr><td style="background:#1a1a1a;color:#fff;text-align:center;font-size:6.5pt;font-weight:bold;letter-spacing:1.5pt;padding:2.5pt 0;">' . strtoupper($label) . '</td></tr>';
            $body .= '<tr><td style="padding:5pt 7pt;">';

            // ── College + SN ──
            $body .= '<table width="100%" cellpadding="1" cellspacing="0"><tr>';
            $body .= '<td width="70%"><b style="font-size:8.5pt;">' . $college . '</b><br><span style="font-size:5.5pt;color:#555;">' . $city . ($phone ? " | $phone" : '') . '</span></td>';
            $body .= '<td width="30%" align="right" valign="top"><b style="font-size:8pt;">SN#: ' . $sn . '</b><br><span style="font-size:6pt;color:#555;">Date: ' . $dateLabel . '</span></td>';
            $body .= '</tr></table>';

            // ── Thick divider ──
            $body .= '<table width="100%" cellpadding="0" cellspacing="0" style="margin:3pt 0;"><tr><td style="border-top:0.8pt solid #333;font-size:0;">&nbsp;</td></tr></table>';

            // ── Bank info ──
            $body .= '<table width="100%" cellpadding="1" cellspacing="0">';
            $body .= '<tr><td width="16%" class="lbl">Account:</td><td width="34%">' . $bankAcct . '</td><td width="12%" class="lbl">Bank:</td><td width="38%">' . $bankName . $bankBranchStr . '</td></tr>';
            $body .= '<tr><td class="lbl">Title:</td><td colspan="3">' . $bankTitle . '</td></tr>';
            $body .= '</table>';

            // ── Thick divider ──
            $body .= '<table width="100%" cellpadding="0" cellspacing="0" style="margin:3pt 0;"><tr><td style="border-top:0.8pt solid #333;font-size:0;">&nbsp;</td></tr></table>';

            // ── Student info ──
            $body .= '<table width="100%" cellpadding="1" cellspacing="0">';
            $body .= '<tr><td width="16%" class="lbl">Student:</td><td width="34%">' . $name . '</td><td width="16%" class="lbl">Father:</td><td width="34%">' . $father . '</td></tr>';
            $body .= '<tr><td class="lbl">Roll No:</td><td>' . $roll . '</td><td class="lbl">Program:</td><td>' . $program . '</td></tr>';
            $body .= '<tr><td class="lbl">Semester:</td><td>' . $semNo . '</td><td class="lbl">Session:</td><td>' . $session . '</td></tr>';
            $body .= '</table>';

            // ── Light divider ──
            $body .= '<table width="100%" cellpadding="0" cellspacing="0" style="margin:3pt 0;"><tr><td style="border-top:0.5pt solid #aaa;font-size:0;">&nbsp;</td></tr></table>';

            // ── Fee table ──
            $body .= '<table class="ft" cellpadding="0" cellspacing="0">';
            $body .= '<tr><th width="18pt">S#.</th><th>Particular</th><th width="55pt" align="right">Rs.</th></tr>';
            $body .= $feeRows;
            $body .= '</table>';

            $body .= '<p style="font-size:6pt;margin:2pt 0;"><b>InWords:</b> ' . $inWords . '</p>';

            // ── Divider ──
            $body .= '<table width="100%" cellpadding="0" cellspacing="0" style="margin:3pt 0;"><tr><td style="border-top:0.5pt solid #aaa;font-size:0;">&nbsp;</td></tr></table>';

            // ── Stamp ──
            $body .= '<table width="100%" cellpadding="0" cellspacing="0" style="margin:3pt 0;"><tr>';
            $body .= '<td align="center"><span style="border:1.5pt solid ' . $stampClr . ';color:' . $stampClr . ';font-size:10pt;font-weight:bold;letter-spacing:2pt;padding:1.5pt 8pt;opacity:0.6;">' . $stamp . '</span></td>';
            $body .= '</tr></table>';

            // ── Divider ──
            $body .= '<table width="100%" cellpadding="0" cellspacing="0" style="margin:3pt 0;"><tr><td style="border-top:0.5pt solid #aaa;font-size:0;">&nbsp;</td></tr></table>';

            // ── Depositor fields ──
            $body .= '<table width="100%" cellpadding="1" cellspacing="0"><tr>';
            $body .= '<td width="48%"><span style="font-size:5.5pt;color:#555;">Depositor Mobile No:</span><br><table width="100%" cellpadding="0" cellspacing="0"><tr><td style="border-bottom:0.5pt solid #888;height:8pt;font-size:0;">&nbsp;</td></tr></table></td>';
            $body .= '<td width="4%"></td>';
            $body .= '<td width="48%"><span style="font-size:5.5pt;color:#555;">CNIC #:</span><br><table width="100%" cellpadding="0" cellspacing="0"><tr><td style="border-bottom:0.5pt solid #888;height:8pt;font-size:0;">&nbsp;</td></tr></table></td>';
            $body .= '</tr></table>';

            $body .= '<p style="font-size:6pt;color:#333;margin-top:3pt;"><b>Ref No:</b> ' . $refNo . '</p>';

            // ── Divider ──
            $body .= '<table width="100%" cellpadding="0" cellspacing="0" style="margin:3pt 0;"><tr><td style="border-top:0.5pt solid #aaa;font-size:0;">&nbsp;</td></tr></table>';

            // ── Instructions ──
            $body .= '<p style="font-size:5.5pt;color:#444;line-height:1.6;">' . $instr . '</p>';

            $body .= '</td></tr></table>'; // close copy outer table
        }

        return "<!DOCTYPE html><html><head><meta charset='UTF-8'><style>$css</style></head><body>$body</body></html>";
    }

    private function numberToWords(int $n): string
    {
        if ($n === 0) return 'Zero Only';
        $o = ['','One','Two','Three','Four','Five','Six','Seven','Eight','Nine','Ten',
              'Eleven','Twelve','Thirteen','Fourteen','Fifteen','Sixteen','Seventeen','Eighteen','Nineteen'];
        $t = ['','','Twenty','Thirty','Forty','Fifty','Sixty','Seventy','Eighty','Ninety'];
        $p = [];
        if ($v = intdiv($n, 10000000)) { $p[] = $o[$v].' Crore';    $n %= 10000000; }
        if ($v = intdiv($n, 100000))   { $p[] = ($v<20?$o[$v]:$t[intdiv($v,10)].($v%10?' '.$o[$v%10]:'')).' Lakh'; $n %= 100000; }
        if ($v = intdiv($n, 1000))     { $p[] = ($v<20?$o[$v]:$t[intdiv($v,10)].($v%10?' '.$o[$v%10]:'')).' Thousand'; $n %= 1000; }
        if ($v = intdiv($n, 100))      { $p[] = $o[$v].' Hundred';  $n %= 100; }
        if ($n >= 20)                  { $p[] = $t[intdiv($n,10)].($n%10?' '.$o[$n%10]:''); }
        elseif ($n > 0)                { $p[] = $o[$n]; }
        return implode(' ', $p).' Only';
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
