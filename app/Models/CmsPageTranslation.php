<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsPageTranslation extends Model
{
    use HasFactory;

    protected $table = 'cms_page_translations';

    protected $fillable = [
        'cms_page_id',
        'locale',
        'title',
        'meta_description',
        'meta_keywords',
    ];

    /**
     * Get the page that owns the translation
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(CmsPage::class, 'cms_page_id');
    }
}
