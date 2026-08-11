<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsSectionTranslation extends Model
{
    use HasFactory;

    protected $table = 'cms_section_translations';

    protected $fillable = [
        'cms_section_id',
        'locale',
        'title',
        'subtitle',
        'description',
    ];

    /**
     * Get the section that owns the translation
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(CmsSection::class, 'cms_section_id');
    }
}
