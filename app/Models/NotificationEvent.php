<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class NotificationEvent extends Model
{
    protected $fillable = [
        'key',
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function recipients(): BelongsToMany
    {
        return $this->belongsToMany(
            NotificationRecipient::class,
            'notification_event_recipient',
            'notification_event_id',
            'notification_recipient_id'
        );
    }

    public function activeRecipients(): BelongsToMany
    {
        return $this->recipients()->where('notification_recipients.is_active', true);
    }

    public function localizedName(): string
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }
}
