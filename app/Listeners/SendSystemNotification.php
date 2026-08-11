<?php

namespace App\Listeners;

use App\Contracts\NotifiableSystemEvent;
use App\Services\SystemNotificationService;

class SendSystemNotification
{
    private SystemNotificationService $notifier;

    public function __construct(SystemNotificationService $notifier)
    {
        $this->notifier = $notifier;
    }

    public function handle($event): void
    {
        if (! $event instanceof NotifiableSystemEvent) {
            return;
        }

        $this->notifier->notify(
            $event->notificationEventKey(),
            $event->notificationPayload()
        );
    }
}
