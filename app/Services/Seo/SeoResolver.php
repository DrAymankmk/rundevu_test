<?php

namespace App\Services\Seo;

use App\Models\CmsPage;
use Illuminate\Database\Eloquent\Model;

class SeoResolver
{
    public function resolve(?Model $model, ?string $locale = null, array $defaults = []): array
    {
        $locale = $locale ?? app()->getLocale();
        $fallback = config('app.fallback_locale', 'en');

        $seo = ($model && method_exists($model, 'seoMeta')) ? $model->seoMeta : null;
        if (!$seo || !$seo->is_active) {
            return $this->defaults($defaults);
        }

        $translation = $seo->translation($locale) ?? $seo->translation($fallback);

        $title = $translation?->meta_title
            ?? $translation?->og_title
            ?? $defaults['title']
            ?? config('app.name');

        $description = $translation?->meta_description
            ?? $translation?->og_description
            ?? $defaults['description']
            ?? '';

        $ogImage = $this->resolveImageUrl(
            $translation?->og_image
                ?? ($seo->getFirstMediaUrl('og_image') ?: null)
                ?? ($defaults['image'] ?? null)
        );

        $canonical = $seo->canonical_url ?: ($defaults['canonical'] ?? url()->current());

        return [
            'title' => $title,
            'description' => strip_tags((string) $description),
            'keywords' => $translation?->meta_keywords ?? $defaults['keywords'] ?? '',
            'robots' => $seo->robots ?? 'index,follow',
            'canonical' => $canonical,
            'og' => [
                'title' => $translation?->og_title ?? $title,
                'description' => strip_tags((string) ($translation?->og_description ?? $description)),
                'image' => $ogImage,
                'type' => $seo->og_type ?? 'website',
                'url' => $canonical,
            ],
            'twitter' => [
                'card' => $translation?->twitter_card ?? 'summary_large_image',
                'title' => $translation?->twitter_title ?? $title,
                'description' => strip_tags((string) ($translation?->twitter_description ?? $description)),
                'image' => $this->resolveImageUrl($translation?->twitter_image ?? $ogImage),
            ],
            'schema_json' => $translation?->schema_json,
        ];
    }

    public function resolveForCmsSlug(string $slug, string $routeName, ?string $fallbackTitle = null): array
    {
        $locale = app()->getLocale();

        $cmsPage = CmsPage::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with(['seoMeta.translations', 'translations'])
            ->first();

        $translation = $cmsPage?->translation($locale);

        return $this->resolve($cmsPage, $locale, [
            'title' => $translation?->title ?? $fallbackTitle ?? config('app.name'),
            'description' => $translation?->meta_description,
            'keywords' => $translation?->meta_keywords,
            'canonical' => route($routeName),
        ]);
    }

    public function defaults(array $defaults = []): array
    {
        $image = $this->resolveImageUrl($defaults['image'] ?? null);

        return [
            'title' => $defaults['title'] ?? config('app.name'),
            'description' => strip_tags((string) ($defaults['description'] ?? '')),
            'keywords' => $defaults['keywords'] ?? '',
            'robots' => $defaults['robots'] ?? 'index,follow',
            'canonical' => $defaults['canonical'] ?? url()->current(),
            'og' => [
                'title' => $defaults['title'] ?? config('app.name'),
                'description' => strip_tags((string) ($defaults['description'] ?? '')),
                'image' => $image,
                'type' => 'website',
                'url' => $defaults['canonical'] ?? url()->current(),
            ],
            'twitter' => [
                'card' => 'summary_large_image',
                'title' => $defaults['title'] ?? config('app.name'),
                'description' => strip_tags((string) ($defaults['description'] ?? '')),
                'image' => $image,
            ],
            'schema_json' => null,
        ];
    }

    private function resolveImageUrl(?string $image): string
    {
        if (filled($image)) {
            if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
                return $image;
            }

            return asset($image);
        }

        return asset('frontend/assets/img/logo.png');
    }
}
