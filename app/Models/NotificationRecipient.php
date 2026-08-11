<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class NotificationRecipient extends Model
{
    protected $fillable = [
        'email',
        'label',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(
            NotificationEvent::class,
            'notification_event_recipient',
            'notification_recipient_id',
            'notification_event_id'
        );
    }

    public function syncEvents(array $eventIds): void
    {
        $this->events()->sync($eventIds);
    }
}
