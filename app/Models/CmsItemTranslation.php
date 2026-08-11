<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsItemTranslation extends Model
{
    use HasFactory;

    protected $table = 'cms_item_translations';

    protected $fillable = [
        'cms_item_id',
        'locale',
        'title',
        'sub_title',
        'content',
        'icon',
    ];

    /**
     * Get the item that owns the translation
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(CmsItem::class, 'cms_item_id');
    }
}
