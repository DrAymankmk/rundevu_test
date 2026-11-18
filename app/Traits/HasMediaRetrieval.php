<?php

namespace App\Traits;

use App\Support\Cms\CmsGalleryMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasMediaRetrieval
{
    /**
     * Get media URL with fallback support for language-specific collections
     *
     * @param string $collectionName Base collection name (e.g., 'images', 'icons')
     * @param string|null $locale Locale code (e.g., 'en', 'ar'). If null, uses current locale
     * @param string|null $fallbackUrl URL to use if no media is found
     * @param bool $useLanguageFallback Whether to try language-specific collections first
     * @param string $conversion Optional conversion to prefer (e.g. 'preview_webp'); falls back to the original file
     * @return string|null Media URL or fallback URL
     */
    public function getMediaUrl(
        string $collectionName,
        ?string $locale = null,
        ?string $fallbackUrl = null,
        bool $useLanguageFallback = true,
        string $conversion = ''
    ): ?string {
        $locale = $locale ?? app()->getLocale();
        $modelClass = get_class($this);

        // Try language-specific collection first if enabled
        if ($useLanguageFallback) {
            $languageCollection = "{$collectionName}_{$locale}";
            $media = Media::where('model_type', $modelClass)
                ->where('model_id', $this->id)
                ->where('collection_name', $languageCollection)
                ->first();

            if ($media) {
                $url = $this->resolveMediaUrl($media, $conversion);
                if ($url !== null) {
                    return $url;
                }
            }
        }

        // Try base collection
        $media = Media::where('model_type', $modelClass)
            ->where('model_id', $this->id)
            ->where('collection_name', $collectionName)
            ->first();

        if ($media) {
            $url = $this->resolveMediaUrl($media, $conversion);
            if ($url !== null) {
                return $url;
            }
        }

        // Try to find any collection matching the pattern (e.g., images_*)
        if ($useLanguageFallback) {
            $media = Media::where('model_type', $modelClass)
                ->where('model_id', $this->id)
                ->where('collection_name', 'like', "{$collectionName}_%")
                ->get();

            foreach ($media as $candidate) {
                $url = $this->resolveMediaUrl($candidate, $conversion);
                if ($url !== null) {
                    return $url;
                }
            }
        }

        return $fallbackUrl;
    }

    /**
     * Get media object with fallback support
     *
     * @param string $collectionName Base collection name
     * @param string|null $locale Locale code
     * @param bool $useLanguageFallback Whether to try language-specific collections first
     * @return Media|null
     */
    public function getMediaWithFallback(
        string $collectionName,
        ?string $locale = null,
        bool $useLanguageFallback = true
    ): ?Media {
        $locale = $locale ?? app()->getLocale();
        $modelClass = get_class($this);

        // Try language-specific collection first if enabled
        if ($useLanguageFallback) {
            $languageCollection = "{$collectionName}_{$locale}";
            $media = Media::where('model_type', $modelClass)
                ->where('model_id', $this->id)
                ->where('collection_name', $languageCollection)
                ->first();

            if ($media) {
                return $media;
            }
        }

        // Try base collection
        $media = Media::where('model_type', $modelClass)
            ->where('model_id', $this->id)
            ->where('collection_name', $collectionName)
            ->first();

        if ($media) {
            return $media;
        }

        // Try to find any collection matching the pattern
        if ($useLanguageFallback) {
            $media = Media::where('model_type', $modelClass)
                ->where('model_id', $this->id)
                ->where('collection_name', 'like', "{$collectionName}_%")
                ->first();

            if ($media) {
                return $media;
            }
        }

        return null;
    }

    /**
     * Original file URL, or the given conversion when it has been generated.
     */
    protected function resolveMediaUrl(Media $media, string $conversion = ''): ?string
    {
        return $conversion !== ''
            ? CmsGalleryMedia::webpUrl($media, $conversion)
            : CmsGalleryMedia::accessibleUrl($media);
    }

    /**
     * `srcset` value (original + generated smaller conversions) labelled with each file's
     * real pixel width, or null when there is nothing smaller than the original to offer.
     *
     * @param array<int, string> $conversions Conversions to offer, best format first.
     */
    public function getMediaSrcset(
        string $collectionName,
        ?string $locale = null,
        array $conversions = ['preview_webp', 'preview'],
        bool $useLanguageFallback = true
    ): ?string {
        $media = $this->getMediaWithFallback($collectionName, $locale, $useLanguageFallback);
        if (! $media || CmsGalleryMedia::isVideo($media) || ! file_exists($media->getPath())) {
            return null;
        }

        $width = static function (string $path): int {
            $size = @getimagesize($path);

            return $size ? (int) $size[0] : 0;
        };

        $originalWidth = $width($media->getPath());
        if ($originalWidth < 1) {
            return null;
        }

        // One file per width; the first conversion listed wins (e.g. WebP over JPEG).
        $byWidth = [];
        foreach ($conversions as $conversion) {
            if (! $media->hasGeneratedConversion($conversion)) {
                continue;
            }
            $path = $media->getPath($conversion);
            $w = file_exists($path) ? $width($path) : 0;
            if ($w > 0 && $w < $originalWidth && ! isset($byWidth[$w])) {
                $byWidth[$w] = $media->getUrl($conversion);
            }
        }

        if ($byWidth === []) {
            return null;
        }

        $byWidth[$originalWidth] = $media->getUrl();
        ksort($byWidth);

        return implode(', ', array_map(
            static fn ($url, $w) => $url . ' ' . $w . 'w',
            $byWidth,
            array_keys($byWidth)
        ));
    }

    public function getFirstAccessibleMediaUrl(string $collectionName): ?string
    {
        $media = $this->getFirstMedia($collectionName);

        return $media ? CmsGalleryMedia::accessibleUrl($media) : null;
    }

    public function getMediaAlt(
        string $collectionName,
        ?string $locale = null,
        bool $useLanguageFallback = true
    ): string {
        $media = $this->getMediaWithFallback($collectionName, $locale, $useLanguageFallback);

        return CmsGalleryMedia::alt($media);
    }

    /**
     * Get gallery media for the current locale with sensible fallbacks.
     *
     * @return \Illuminate\Support\Collection<int, Media>
     */
    public function getGalleryMedia(
        ?string $locale = null,
        bool $useLanguageFallback = true
    ): \Illuminate\Support\Collection {
        $locale = $locale ?? app()->getLocale();
        $fallbackLocale = config('app.fallback_locale', 'en');

        if ($useLanguageFallback) {
            $localized = $this->getMedia('gallery_' . $locale);
            if ($localized->isNotEmpty()) {
                return $localized;
            }

            if ($locale !== $fallbackLocale) {
                $fallback = $this->getMedia('gallery_' . $fallbackLocale);
                if ($fallback->isNotEmpty()) {
                    return $fallback;
                }
            }
        }

        $legacy = $this->getMedia('gallery');
        if ($legacy->isNotEmpty()) {
            return $legacy;
        }

        return collect();
    }
}
