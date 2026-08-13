<?php

namespace App\Console\Commands;

use App\Models\CmsPage;
use App\Models\SeoMeta;
use Illuminate\Console\Command;

class MigrateCmsPageSeoCommand extends Command
{
    protected $signature = 'seo:migrate-cms-pages';

    protected $description = 'Migrate CMS page translation meta fields into the SEO module';

    public function handle(): int
    {
        $pages = CmsPage::with('translations')->get();
        $migrated = 0;

        foreach ($pages as $page) {
            $seo = $page->seoMeta()->firstOrCreate(
                [
                    'seoable_id' => $page->id,
                    'seoable_type' => $page->getMorphClass(),
                ],
                [
                    'robots' => 'index,follow',
                    'og_type' => 'website',
                    'is_active' => true,
                ]
            );

            foreach ($page->translations as $translation) {
                $seo->translations()->updateOrCreate(
                    ['locale' => $translation->locale],
                    [
                        'meta_title' => $translation->title,
                        'meta_description' => $translation->meta_description,
                        'meta_keywords' => $translation->meta_keywords,
                    ]
                );
            }

            $migrated++;
        }

        $this->info("Migrated SEO data for {$migrated} CMS page(s).");

        return self::SUCCESS;
    }
}
