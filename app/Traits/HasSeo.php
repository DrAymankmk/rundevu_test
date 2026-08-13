<?php

namespace App\Traits;

use App\Models\SeoMeta;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasSeo
{
    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    public function seoForLocale(?string $locale = null): ?SeoMeta
    {
        $seo = $this->seoMeta;
        if (!$seo || !$seo->is_active) {
            return null;
        }

        return $seo;
    }
}
