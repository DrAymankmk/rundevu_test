<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsLinkTranslation extends Model
{
    use HasFactory;

    protected $table = 'cms_link_translations';

    protected $fillable = [
        'cms_link_id',
        'locale',
        'name',
    ];

    /**
     * Get the link that owns the translation
     */
    public function link(): BelongsTo
    {
        return $this->belongsTo(CmsLink::class, 'cms_link_id');
    }
}
