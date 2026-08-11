<?php

namespace App\Events\SystemNotifications;

use App\Contracts\NotifiableSystemEvent;
use App\Models\DemoRequest;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DemoRequested implements NotifiableSystemEvent
{
    use Dispatchable, SerializesModels;

    public DemoRequest $demoRequest;

    public function __construct(DemoRequest $demoRequest)
    {
        $this->demoRequest = $demoRequest;
    }

    public function notificationEventKey(): string
    {
        return 'demo.requested';
    }

    public function notificationPayload(): array
    {
        return [
            'demo_request' => $this->demoRequest,
        ];
    }
}
