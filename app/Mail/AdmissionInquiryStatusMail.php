<?php

namespace App\Mail;

use App\Models\AdmissionInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Covers the "contacted" / "enrolled" status transitions. "Rejected" keeps
 * its own dedicated AdmissionInquiryRejectedMail/action since it already
 * carries a distinct admin_notes-driven flow.
 */
class AdmissionInquiryStatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public AdmissionInquiry $admissionInquiry)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectFor($this->admissionInquiry->status),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admissions.status-update',
            with: [
                'heading' => $this->headingFor($this->admissionInquiry->status),
            ],
        );
    }

    private function subjectFor(string $status): string
    {
        return match ($status) {
            'contacted' => 'We Have Contacted You Regarding Your Admission Application',
            'enrolled'  => 'Welcome — Your Admission Is Confirmed',
            default     => 'Update on Your Admission Application',
        };
    }

    private function headingFor(string $status): string
    {
        return match ($status) {
            'contacted' => 'Our Admissions Office Has Been in Touch',
            'enrolled'  => 'Congratulations — You Are Enrolled!',
            default     => 'Admission Application Update',
        };
    }
}
