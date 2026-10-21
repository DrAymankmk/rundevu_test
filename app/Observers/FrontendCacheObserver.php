<?php

namespace App\Observers;

use App\Models\CmsItem;
use App\Models\CmsPage;
use App\Models\CmsSection;
use App\Models\Package;
use App\Models\Specialty;
use App\Services\Frontend\FrontendContentCache;

class FrontendCacheObserver
{
    public function saved($model): void
    {
        $this->flush($model);
    }

    public function deleted($model): void
    {
        $this->flush($model);
    }

    protected function flush($model): void
    {
        if ($model instanceof Specialty) {
            FrontendContentCache::flushSpecialties();

            return;
        }

        if ($model instanceof Package) {
            FrontendContentCache::flushPackages();

            return;
        }

        if ($model instanceof CmsPage) {
            FrontendContentCache::flushCms($model->slug);

            return;
        }

        if ($model instanceof CmsSection || $model instanceof CmsItem) {
            FrontendContentCache::flushCms();
        }
    }
}
