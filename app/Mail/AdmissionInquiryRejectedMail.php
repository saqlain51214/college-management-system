<?php

namespace App\Mail;

use App\Models\AdmissionInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdmissionInquiryRejectedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public AdmissionInquiry $admissionInquiry)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Update on your admission application'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admissions.rejected'
        );
    }
}
