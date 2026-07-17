<?php

namespace App\Mail;

use App\Models\NotificationTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificationTemplateMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly NotificationTemplate $template,
        public readonly array $variables = [],
        public readonly string $recipientName = 'Student',
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->template->replaceVariables($this->template->subject, $this->variables),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.notification-wrapper',
            with: [
                'recipientName' => $this->recipientName,
                'bodyMarkdown'  => $this->template->replaceVariables($this->template->body, $this->variables),
                'actionLabel'   => $this->template->action_label,
                'actionUrl'     => $this->template->replaceVariables($this->template->action_url ?? '', $this->variables),
            ],
        );
    }
}
