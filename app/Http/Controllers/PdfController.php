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
     * Build ONE combined PDF containing the fee challans of many students
     * (bulk / department-wise generation). Reuses the active slip template's
     * layout for each payment, separated by page breaks.
     */
    public function bulkChallansPdf(\Illuminate\Support\Collection $payments): string
    {
        @set_time_limit(300);

        $payments->load(['student.academicProgram', 'student.academicYear', 'feeStructure', 'academicYear']);

        $template    = FeeSlipTemplate::active();
        $orientation = $template?->orientation ?? 'landscape';
        $view = match ($template?->variant ?? 'kiu') {
            'classic' => 'pdf.slip-classic',
            'modern'  => 'pdf.slip-modern',
            'minimal' => 'pdf.slip-minimal',
            default   => 'pdf.slip-kiu',
        };

        $head = '';
        $bodies = [];
        foreach ($payments as $payment) {
            $html = view($view, compact('payment', 'template'))->render();

            if ($head === '' && preg_match('/<head[^>]*>(.*?)<\/head>/is', $html, $hm)) {
                $head = $hm[1];
            }
            $body = preg_match('/<body[^>]*>(.*?)<\/body>/is', $html, $bm) ? $bm[1] : $html;
            $bodies[] = '<div style="page-break-after:always;">' . $body . '</div>';
        }

        $combined = '<!DOCTYPE html><html><head><meta charset="utf-8">' . $head . '</head><body>'
            . implode('', $bodies) . '</body></html>';

        $pdf = Pdf::loadHTML($combined)
            ->setPaper('a4', $orientation)
            ->setOption(['defaultFont' => 'dejavu sans', 'isRemoteEnabled' => false, 'isPhpEnabled' => false]);

        return $pdf->output();
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

}
