<?php

namespace App\Notifications;

use App\Mail\NotificationTemplateMail;
use App\Models\NotificationTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class GenericNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly NotificationTemplate $template,
        public readonly array $variables = [],
        public readonly array $metadata = [],
    ) {}

    public function via(mixed $notifiable): array
    {
        return match ($this->template->channel) {
            'mail'     => ['mail'],
            'database' => ['database'],
            default    => ['database', 'mail'],
        };
    }

    public function toDatabase(mixed $notifiable): array
    {
        return [
            'type_key'     => $this->template->key,
            'title'        => $this->replaceVars($this->template->subject),
            'message'      => $this->replaceVars($this->template->body),
            'action_url'   => $this->replaceVars($this->template->action_url ?? ''),
            'action_label' => $this->template->action_label ?? '',
            'icon'         => $this->template->in_app_icon,
            'metadata'     => $this->metadata,
        ];
    }

    public function toMail(mixed $notifiable): NotificationTemplateMail
    {
        $email = method_exists($notifiable, 'routeNotificationForMail')
            ? $notifiable->routeNotificationForMail($this)
            : ($notifiable->email ?? null);

        $mailable = new NotificationTemplateMail(
            template: $this->template,
            variables: $this->variables,
            recipientName: $notifiable->name ?? 'Student',
        );

        return $email ? $mailable->to($email) : $mailable;
    }

    private function replaceVars(string $text): string
    {
        return $this->template->replaceVariables($text, $this->variables);
    }
}
