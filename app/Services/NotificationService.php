<?php

namespace App\Services;

use App\Models\NotificationTemplate;
use App\Notifications\GenericNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Send a template-driven notification to a single notifiable (Student or Teacher).
     */
    public function send(
        Model $notifiable,
        string $templateKey,
        array $variables = [],
        array $metadata = [],
    ): void {
        $template = NotificationTemplate::findByKey($templateKey);

        if (! $template) {
            Log::warning("NotificationService: template '{$templateKey}' not found or inactive.");
            return;
        }

        $notifiable->notify(new GenericNotification($template, $variables, $metadata));
    }

    /**
     * Send to a collection (e.g. all active students for an announcement).
     * Uses Notification::send() for efficient batch dispatch.
     */
    public function sendToAll(
        Collection $notifiables,
        string $templateKey,
        array $variables = [],
        array $metadata = [],
    ): void {
        $template = NotificationTemplate::findByKey($templateKey);

        if (! $template) {
            Log::warning("NotificationService: template '{$templateKey}' not found or inactive.");
            return;
        }

        Notification::send($notifiables, new GenericNotification($template, $variables, $metadata));
    }
}
