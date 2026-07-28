<?php

namespace App\Mail;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Sent to the applicant whenever an admin changes their application status —
 * previously a status change was silent, the applicant had no way to know
 * whether they'd even been seen.
 */
class JobApplicationStatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public JobApplication $jobApplication)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectFor($this->jobApplication->status) . ' — ' . $this->jobApplication->position,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.jobs.status-update',
            with: [
                'heading' => $this->headingFor($this->jobApplication->status),
                'body'    => $this->bodyFor($this->jobApplication->status),
            ],
        );
    }

    private function subjectFor(string $status): string
    {
        return match ($status) {
            'reviewed'    => 'Your Application Is Under Review',
            'shortlisted' => 'You Have Been Shortlisted — Interview to Follow',
            'hired'       => 'Congratulations — You Have Been Selected',
            'rejected'    => 'Update on Your Application',
            default       => 'Application Received',
        };
    }

    private function headingFor(string $status): string
    {
        return match ($status) {
            'reviewed'    => 'Your Application Is Under Review',
            'shortlisted' => 'You Have Been Shortlisted',
            'hired'       => 'Congratulations!',
            'rejected'    => 'Application Update',
            default       => 'Application Received',
        };
    }

    private function bodyFor(string $status): string
    {
        $name = $this->jobApplication->name;

        return match ($status) {
            'reviewed'    => "Dear {$name},\n\nThank you for applying for the {$this->jobApplication->position} position. Your application is now under review by our team. We will contact you if you are shortlisted for the next stage.",
            'shortlisted' => "Dear {$name},\n\nCongratulations! You have been shortlisted for the {$this->jobApplication->position} position. Our team will contact you shortly to schedule an interview.",
            'hired'       => "Dear {$name},\n\nCongratulations! We are pleased to inform you that you have been selected for the {$this->jobApplication->position} position. The administration will contact you shortly with further details.",
            'rejected'    => "Dear {$name},\n\nThank you for your interest in the {$this->jobApplication->position} position and for taking the time to apply. After careful consideration, we have decided not to move forward with your application at this time. We encourage you to apply for future openings that match your qualifications.",
            default       => "Dear {$name},\n\nThank you for applying for the {$this->jobApplication->position} position. Your application has been received and is in our queue for review.",
        };
    }
}
