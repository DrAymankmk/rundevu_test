<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebsiteLinkTranslation extends Model
{
    protected $table = 'website_link_translations';

    protected $fillable = [
        'website_link_id',
        'locale',
        'title',
        'description',
    ];

    public function link(): BelongsTo
    {
        return $this->belongsTo(WebsiteLink::class, 'website_link_id');
    }
}
