<?php

namespace App\Rules;

use App\Support\Cms\CmsGalleryMedia;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;

class CmsGalleryMediaFile implements Rule
{
    protected ?string $message = null;

    public function passes($attribute, $value): bool
    {
        if (! $value instanceof UploadedFile || ! $value->isValid()) {
            return true;
        }

        $mime = strtolower((string) $value->getMimeType());
        $extension = strtolower((string) $value->getClientOriginalExtension());
        $sizeKb = (int) ceil($value->getSize() / 1024);

        if (CmsGalleryMedia::isImageMime($mime) || in_array($extension, CmsGalleryMedia::IMAGE_EXTENSIONS, true)) {
            if (! in_array($extension, CmsGalleryMedia::IMAGE_EXTENSIONS, true)) {
                $this->message = __('cms.gallery_invalid_media');

                return false;
            }

            if ($sizeKb > CmsGalleryMedia::IMAGE_MAX_KB) {
                $this->message = __('cms.gallery_image_too_large', ['max' => CmsGalleryMedia::IMAGE_MAX_KB]);

                return false;
            }

            return true;
        }

        if (CmsGalleryMedia::isVideoMime($mime) || in_array($extension, CmsGalleryMedia::VIDEO_EXTENSIONS, true)) {
            if (! in_array($extension, CmsGalleryMedia::VIDEO_EXTENSIONS, true)) {
                $this->message = __('cms.gallery_invalid_media');

                return false;
            }

            if ($sizeKb > CmsGalleryMedia::VIDEO_MAX_KB) {
                $this->message = __('cms.gallery_video_too_large', ['max' => CmsGalleryMedia::VIDEO_MAX_KB]);

                return false;
            }

            return true;
        }

        $this->message = __('cms.gallery_invalid_media');

        return false;
    }

    public function message(): string
    {
        return $this->message ?? __('cms.gallery_invalid_media');
    }
}
