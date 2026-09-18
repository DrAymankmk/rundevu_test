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

        $canonical = $this->sanitizeCanonical(
            $seo->canonical_url ?: ($defaults['canonical'] ?? url()->current())
        );
        $schema = $this->normalizeSchema($translation?->schema_json ?? null) ?? $this->defaultSchema($canonical, $title, $description, $ogImage);

        return $this->withCommonMeta([
            'title' => $title,
            'description' => strip_tags((string) $description),
            'keywords' => $translation?->meta_keywords ?? $defaults['keywords'] ?? '',
            'robots' => $seo->robots ?? 'index,follow',
            'canonical' => $canonical,
            'lcp_image' => $defaults['lcp_image'] ?? $ogImage,
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
                'image_alt' => $title,
            ],
            'schema_json' => $schema,
        ], $defaults, $locale, $canonical);
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
            'canonical' => function_exists('frontend_route')
                ? frontend_route($routeName)
                : route($routeName),
            'lcp_image' => $slug === 'home'
                ? asset('frontend/assets/img/hero/hero_bg_1_1.jpg')
                : (function_exists('frontend_breadcrumb_image')
                    ? frontend_breadcrumb_image($slug)
                    : asset('frontend/assets/img/bg/breadcumb-bg.jpg')),
        ]);
    }

    public function defaults(array $defaults = []): array
    {
        $image = $this->resolveImageUrl($defaults['image'] ?? null);
        $canonical = $this->sanitizeCanonical($defaults['canonical'] ?? url()->current());
        $title = $defaults['title'] ?? config('app.name');
        $description = strip_tags((string) ($defaults['description'] ?? ''));
        $locale = $defaults['locale'] ?? app()->getLocale();

        return $this->withCommonMeta([
            'title' => $title,
            'description' => $description,
            'keywords' => $defaults['keywords'] ?? '',
            'robots' => $defaults['robots'] ?? 'index,follow',
            'canonical' => $canonical,
            'lcp_image' => $defaults['lcp_image'] ?? $image,
            'og' => [
                'title' => $title,
                'description' => $description,
                'image' => $image,
                'type' => 'website',
                'url' => $canonical,
            ],
            'twitter' => [
                'card' => 'summary_large_image',
                'title' => $title,
                'description' => $description,
                'image' => $image,
                'image_alt' => $title,
            ],
            'schema_json' => $this->normalizeSchema($defaults['schema_json'] ?? null)
                ?? $this->defaultSchema($canonical, $title, $description, $image),
        ], $defaults, $locale, $canonical);
    }

    private function withCommonMeta(array $seo, array $defaults, string $locale, string $canonical): array
    {
        $seo['site_name'] = $defaults['site_name'] ?? config('app.name', 'Randevu');
        $seo['og_locale'] = $defaults['og_locale'] ?? ($locale === 'ar' ? 'ar_SA' : 'en_US');
        $seo['og_locale_alternates'] = $defaults['og_locale_alternates'] ?? ($locale === 'ar' ? ['en_US'] : ['ar_SA']);
        $seo['hreflang'] = $defaults['hreflang'] ?? (function_exists('frontend_hreflang_urls')
            ? frontend_hreflang_urls(null, $defaults['hreflang_overrides'] ?? [])
            : ['x-default' => $canonical]);

        return $seo;
    }

    private function defaultSchema(string $canonical, string $title, string $description, string $image): array
    {
        $siteName = (string) config('app.name', 'Randevu');

        return [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Organization',
                    'name' => $siteName,
                    'url' => rtrim((string) config('app.url'), '/') ?: url('/'),
                    'logo' => asset('frontend/assets/img/logo.png'),
                    'email' => 'support@rundevo.net',
                    'telephone' => '+966580161257',
                ],
                [
                    '@type' => 'WebPage',
                    'name' => $title,
                    'description' => $description,
                    'url' => $canonical,
                    'image' => $image,
                    'isPartOf' => [
                        '@type' => 'WebSite',
                        'name' => $siteName,
                        'url' => rtrim((string) config('app.url'), '/') ?: url('/'),
                    ],
                ],
            ],
        ];
    }

    private function normalizeSchema($schema): ?array
    {
        if (is_array($schema) && $schema !== []) {
            return $schema;
        }

        if (is_string($schema) && trim($schema) !== '') {
            $decoded = json_decode($schema, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && $decoded !== []) {
                return $decoded;
            }
        }

        return null;
    }

    private function sanitizeCanonical(string $canonical): string
    {
        $fallback = url()->current();
        $canonicalHost = parse_url($canonical, PHP_URL_HOST);
        if (!$canonicalHost) {
            return $canonical !== '' ? $canonical : $fallback;
        }

        $allowedHosts = array_filter([
            parse_url((string) config('app.url'), PHP_URL_HOST),
            request()->getHost(),
        ]);

        if ($allowedHosts !== [] && !in_array($canonicalHost, $allowedHosts, true)) {
            return $fallback;
        }

        return $canonical;
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
