<?php

namespace App\Contracts;

interface NotifiableSystemEvent
{
    public function notificationEventKey(): string;

    public function notificationPayload(): array;
}
