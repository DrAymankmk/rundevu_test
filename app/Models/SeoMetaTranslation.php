<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeoMetaTranslation extends Model
{
    protected $table = 'seo_meta_translations';

    protected $fillable = [
        'seo_meta_id',
        'locale',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'twitter_card',
        'schema_json',
    ];

    protected $casts = [
        'schema_json' => 'array',
    ];

    public function seoMeta(): BelongsTo
    {
        return $this->belongsTo(SeoMeta::class);
    }
}
