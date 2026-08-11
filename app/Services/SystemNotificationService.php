<?php

namespace App\Services;

use App\Models\NotificationEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SystemNotificationService
{
    public function notify(string $eventKey, array $payload): void
    {
        $event = NotificationEvent::query()
            ->where('key', $eventKey)
            ->where('is_active', true)
            ->first();

        if (! $event) {
            Log::warning('System notification skipped: unknown or inactive event.', ['event' => $eventKey]);

            return;
        }

        $eventConfig = config('notifications.events', [])[$eventKey] ?? null;
        $mailableClass = is_array($eventConfig) ? ($eventConfig['mailable'] ?? null) : null;

        if (! $mailableClass || ! class_exists($mailableClass)) {
            Log::warning('System notification skipped: mailable not configured.', ['event' => $eventKey]);

            return;
        }

        $recipients = $event->activeRecipients()->get();

        if ($recipients->isEmpty()) {
            $fallback = config('notifications.fallback_email');

            if ($fallback) {
                $this->queueMail($mailableClass, $fallback, $payload, $eventKey);

                return;
            }

            Log::warning('System notification skipped: no recipients.', ['event' => $eventKey]);

            return;
        }

        foreach ($recipients as $recipient) {
            $this->queueMail($mailableClass, $recipient->email, $payload, $eventKey);
        }
    }

    private function queueMail(string $mailableClass, string $email, array $payload, string $eventKey): void
    {
        try {
            Mail::to($email)->queue(new $mailableClass($payload));
        } catch (Throwable $e) {
            report($e);
            Log::error('Failed to queue system notification.', [
                'event' => $eventKey,
                'email' => $email,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
