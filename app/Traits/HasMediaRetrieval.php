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
     * @return string|null Media URL or fallback URL
     */
    public function getMediaUrl(
        string $collectionName,
        ?string $locale = null,
        ?string $fallbackUrl = null,
        bool $useLanguageFallback = true
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
                $url = CmsGalleryMedia::accessibleUrl($media);
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
            $url = CmsGalleryMedia::accessibleUrl($media);
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
                $url = CmsGalleryMedia::accessibleUrl($candidate);
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

        if ($useLanguageFallback) {
            $anyLocaleGallery = Media::where('model_type', get_class($this))
                ->where('model_id', $this->id)
                ->where('collection_name', 'like', 'gallery_%')
                ->orderBy('order_column')
                ->get();

            if ($anyLocaleGallery->isNotEmpty()) {
                return $anyLocaleGallery;
            }
        }

        return collect();
    }
}
