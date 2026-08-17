<?php

namespace App\Support\Cms;

use App\Rules\CmsGalleryMediaFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CmsGalleryMedia
{
    public const IMAGE_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ];

    public const VIDEO_MIME_TYPES = [
        'video/mp4',
        'video/webm',
        'video/quicktime',
        'video/x-msvideo',
    ];

    public const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    public const VIDEO_EXTENSIONS = ['mp4', 'webm', 'mov', 'avi'];

    public const IMAGE_MAX_KB = 2048;

    public const VIDEO_MAX_KB = 51200;

    public static function allMimeTypes(): array
    {
        return array_merge(self::IMAGE_MIME_TYPES, self::VIDEO_MIME_TYPES);
    }

    public static function allExtensions(): array
    {
        return array_merge(self::IMAGE_EXTENSIONS, self::VIDEO_EXTENSIONS);
    }

    public static function fileRule(): CmsGalleryMediaFile
    {
        return new CmsGalleryMediaFile();
    }

    public static function isImageMime(?string $mime): bool
    {
        return in_array(strtolower((string) $mime), self::IMAGE_MIME_TYPES, true);
    }

    public static function isVideoMime(?string $mime): bool
    {
        return in_array(strtolower((string) $mime), self::VIDEO_MIME_TYPES, true);
    }

    public static function isImage(Media $media): bool
    {
        return self::isImageMime($media->mime_type);
    }

    public static function isVideo(Media $media): bool
    {
        return self::isVideoMime($media->mime_type);
    }

    public static function previewUrl(Media $media): string
    {
        return self::displayUrl($media) ?? $media->getUrl();
    }

    public static function displayUrl(Media $media, string $conversion = 'thumb'): ?string
    {
        if (self::isVideo($media)) {
            return self::accessibleUrl($media);
        }

        if ($conversion !== '' && $media->hasGeneratedConversion($conversion)) {
            $conversionPath = $media->getPath($conversion);

            if (file_exists($conversionPath)) {
                return $media->getUrl($conversion);
            }
        }

        return self::accessibleUrl($media);
    }

    public static function accessibleUrl(Media $media): ?string
    {
        if (file_exists($media->getPath())) {
            return $media->getUrl();
        }

        foreach (['thumb', 'preview'] as $conversion) {
            if (! $media->hasGeneratedConversion($conversion)) {
                continue;
            }

            if (file_exists($media->getPath($conversion))) {
                return $media->getUrl($conversion);
            }
        }

        return null;
    }

    public static function alt(?Media $media): string
    {
        if (! $media) {
            return '';
        }

        $alt = $media->getCustomProperty('alt');

        return is_string($alt) ? $alt : '';
    }

    public static function setAlt(Media $media, ?string $alt): void
    {
        $media->setCustomProperty('alt', trim((string) $alt));
        $media->save();
    }

    public static function persistCollectionAlt($model, string $collection, ?string $alt): void
    {
        $media = $model->getFirstMedia($collection);
        if ($media) {
            self::setAlt($media, $alt);
        }
    }

    public static function addFileWithAlt($model, $file, string $collection, ?string $alt): Media
    {
        return $model->addMedia($file)
            ->withCustomProperties(['alt' => trim((string) $alt)])
            ->toMediaCollection($collection);
    }

    public static function applyExistingAlts($model, $alts, string $collection = 'gallery'): void
    {
        if (! is_array($alts)) {
            return;
        }

        $mediaById = $model->getMedia($collection)->keyBy('id');
        foreach ($alts as $id => $alt) {
            $media = $mediaById->get((int) $id);
            if ($media) {
                self::setAlt($media, is_string($alt) ? $alt : '');
            }
        }
    }

    public static function replaceMedia(Media $old, $file, ?string $alt = null): Media
    {
        $model = $old->model;
        $collection = $old->collection_name;
        $order = $old->order_column;
        $props = $old->custom_properties ?? [];

        if ($alt !== null) {
            $props['alt'] = trim((string) $alt);
        } elseif (! array_key_exists('alt', $props)) {
            $props['alt'] = '';
        }

        $old->delete();

        $new = $model->addMedia($file)
            ->withCustomProperties($props)
            ->toMediaCollection($collection);

        if ($order !== null) {
            $new->order_column = $order;
            $new->save();
        }

        return $new;
    }

    public static function syncGalleryUploads(
        $model,
        $files,
        $newAlts = [],
        $existingAlts = [],
        string $collection = 'gallery',
        $replacements = []
    ): void {
        $replacedIds = [];

        if (is_array($replacements)) {
            foreach ($replacements as $mediaId => $file) {
                if (! $file || ! $file->isValid()) {
                    continue;
                }

                $media = $model->getMedia($collection)->firstWhere('id', (int) $mediaId);
                if (! $media) {
                    continue;
                }

                $alt = null;
                if (is_array($existingAlts)) {
                    $alt = $existingAlts[$mediaId] ?? $existingAlts[(string) $mediaId] ?? null;
                }
                if ($alt === null) {
                    $alt = self::alt($media);
                }

                self::replaceMedia($media, $file, is_string($alt) ? $alt : '');
                $replacedIds[] = (int) $mediaId;
            }
        }

        if (is_array($existingAlts) && $existingAlts !== []) {
            $altsToApply = [];
            foreach ($existingAlts as $id => $alt) {
                if (! in_array((int) $id, $replacedIds, true)) {
                    $altsToApply[$id] = $alt;
                }
            }
            self::applyExistingAlts($model, $altsToApply, $collection);
        }

        if ($files === null) {
            return;
        }

        if (! is_array($files)) {
            $files = [$files];
        }

        $newAlts = is_array($newAlts) ? array_values($newAlts) : [];
        $index = 0;

        foreach ($files as $file) {
            if ($file && $file->isValid()) {
                self::addFileWithAlt($model, $file, $collection, $newAlts[$index] ?? '');
                $index++;
            }
        }
    }

    public static function altInputName(string $fileInputName): string
    {
        if (preg_match('/^(.*)\[([^\]]+)\]$/', $fileInputName, $matches)) {
            return $matches[1].'['.$matches[2].'_alt]';
        }

        return $fileInputName.'_alt';
    }
}
