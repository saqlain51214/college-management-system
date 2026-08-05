<?php

namespace App\Mail;

use App\Models\FeePayment;
use App\Models\FeeSlipTemplate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Sent alongside the fee_payment_confirmed NotificationService template —
 * that one just points the student back to the portal; this one attaches
 * the actual receipt PDF so the confirmation email is proof-of-payment on
 * its own (e.g. to forward to a parent), same rendering PdfController uses.
 */
class FeePaymentReceiptMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public FeePayment $payment)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Receipt — Challan ' . $this->payment->challan_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.fees.receipt',
        );
    }

    public function attachments(): array
    {
        $payment = $this->payment->loadMissing(['student.academicProgram', 'student.academicYear', 'feeStructure', 'academicYear']);
        $template = FeeSlipTemplate::active();

        $view = match ($template?->variant ?? 'kiu') {
            'classic' => 'pdf.slip-classic',
            'modern'  => 'pdf.slip-modern',
            'minimal' => 'pdf.slip-minimal',
            default   => 'pdf.slip-kiu',
        };

        $pdf = Pdf::loadView($view, compact('payment', 'template'))
            ->setPaper('a4', $template?->orientation ?? 'landscape')
            ->setOption(['defaultFont' => 'dejavu sans', 'isRemoteEnabled' => false, 'isPhpEnabled' => false]);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'challan-' . $this->payment->challan_number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
