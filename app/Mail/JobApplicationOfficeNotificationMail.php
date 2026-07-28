<?php

namespace App\Mail;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobApplicationOfficeNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public JobApplication $jobApplication)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New Job Application: {$this->jobApplication->position} — {$this->jobApplication->name}",
            replyTo: [$this->jobApplication->email],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.jobs.office-notification'
        );
    }

    public function attachments(): array
    {
        if (! $this->jobApplication->cv_path || ! \Illuminate\Support\Facades\Storage::disk('public')->exists($this->jobApplication->cv_path)) {
            return [];
        }

        return [
            Attachment::fromStorageDisk('public', $this->jobApplication->cv_path)
                ->as('CV - ' . $this->jobApplication->name . '.' . pathinfo($this->jobApplication->cv_path, PATHINFO_EXTENSION)),
        ];
    }
}
