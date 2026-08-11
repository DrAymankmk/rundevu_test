<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactUs extends Model
{
    use HasFactory;

    protected $table = 'contact_us';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'is_read',
        'read_by',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function readByClinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class, 'read_by');
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function markAsRead(?int $clinicId = null): void
    {
        $this->update([
            'is_read' => true,
            'read_by' => $clinicId,
        ]);
    }
}
