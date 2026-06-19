<?php

namespace App\Mail;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentPortalWelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Student $student)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your student portal account is ready'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.students.portal-welcome'
        );
    }
}
