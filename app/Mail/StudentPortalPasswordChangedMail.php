<?php

namespace App\Mail;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentPortalPasswordChangedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Student $student)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your student portal password was changed'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.students.password-changed'
        );
    }
}
