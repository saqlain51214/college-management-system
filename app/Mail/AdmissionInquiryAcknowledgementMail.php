<?php

namespace App\Mail;

use App\Models\AdmissionInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdmissionInquiryAcknowledgementMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public AdmissionInquiry $admissionInquiry)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your admission application has been received'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admissions.acknowledgement'
        );
    }
}
