<?php

namespace App\Support\Cms;

use App\Models\CmsSection;

class AboutUsSectionPresenter
{
    public static function data(CmsSection $section): array
    {
        $fb = config('app.fallback_locale', 'en');
        $locale = app()->getLocale();
        $st = $section->translation($locale) ?? $section->translation($fb);
        $title = $st?->title ?: __('Expert Doctors, Seamless Appointments, Quality Care');
        $sub = $st?->subtitle ?: __('About us');
        $desc = $st?->description ?: '<p class="fs-18 mb-30 wow fadeInUp" data-wow-delay=".1s">' . e(__('A belief that knowledge is power—we connect our patients with their results and quality care when they need it most.')) . '</p>';
        $descHasListItems = stripos($desc, '<li') !== false;
        $aboutAlt = $section->getMediaAlt('images', $locale, true);
        if ($aboutAlt === '') {
            $aboutAlt = strip_tags($title) ?: __('About');
        }

        $resolveHref = static function (?string $raw): string {
            $raw = trim((string) $raw);
            if ($raw === '') {
                return '#';
            }
            if (preg_match('#^(https?:)?//#i', $raw) || str_starts_with($raw, 'mailto:') || str_starts_with($raw, 'tel:')) {
                return $raw;
            }

            return str_starts_with($raw, '/') ? url($raw) : url('/' . ltrim($raw, '/'));
        };

        $isExternalEmbedUrl = static function (string $url): bool {
            return (bool) preg_match('#(youtube\.com|youtu\.be|vimeo\.com)#i', $url);
        };

        $galleryVideo = null;
        $galleryImages = collect();

        foreach ($section->getMedia('gallery') as $media) {
            if (CmsGalleryMedia::isVideo($media)) {
                if ($galleryVideo === null) {
                    $galleryVideo = $media;
                }

                continue;
            }

            $url = CmsGalleryMedia::accessibleUrl($media) ?? $media->getUrl();
            if (! filled($url)) {
                continue;
            }

            $galleryImages->push([
                'url' => $url,
                'alt' => CmsGalleryMedia::alt($media),
            ]);
        }

        $galleryImages = $galleryImages->unique('url')->values();
        $firstMedia = $galleryImages->get(0);
        $secondMedia = $galleryImages->get(1);
        $thirdMedia = $galleryImages->get(2);

        $defaults = [
            asset('frontend/assets/img/normal/about_1_1.jpg'),
            asset('frontend/assets/img/normal/about_1_2.jpg'),
            asset('frontend/assets/img/normal/about_1_3.jpg'),
        ];

        $primaryImg = is_array($firstMedia) ? $firstMedia['url'] : $defaults[0];
        $primaryAlt = is_array($firstMedia) ? ($firstMedia['alt'] ?? '') : '';
        if ($primaryAlt !== '') {
            $aboutAlt = $primaryAlt;
        }

        $secondaryImg = is_array($secondMedia) ? $secondMedia['url'] : $defaults[1];
        $secondaryAlt = is_array($secondMedia) ? ($secondMedia['alt'] ?? '') : $aboutAlt;

        $videoImg = is_array($thirdMedia) ? $thirdMedia['url'] : $defaults[2];
        $videoAlt = is_array($thirdMedia) ? ($thirdMedia['alt'] ?? '') : $aboutAlt;

        $sideImg1 = $primaryImg;
        $sideImg2 = $secondaryImg;

        $videoUrl = $galleryVideo ? (string) $galleryVideo->getUrl() : '';
        $videoMimeType = $galleryVideo?->mime_type ?? 'video/mp4';
        $videoIsLocal = filled($videoUrl);
        $callLink = null;
        $aboutButtons = collect();

        if ($section->relationLoaded('links')) {
            foreach ($section->links->where('is_active', true)->sortBy('order')->values() as $idx => $link) {
                $raw = trim((string) ($link->link ?? ''));
                if ($raw === '') {
                    continue;
                }

                $href = $resolveHref($raw);
                $type = strtolower((string) ($link->type ?? ''));
                $isVideo = $type === 'video'
                    || in_array($type, ['youtube', 'vimeo'], true)
                    || preg_match('#(youtube\.com|youtu\.be|vimeo\.com)#i', $raw);
                $isPhone = $type === 'phone'
                    || in_array($type, ['call', 'tel'], true)
                    || str_starts_with(strtolower($raw), 'tel:');

                if ($isVideo) {
                    $videoUrl = $href;
                    $videoIsLocal = ! $isExternalEmbedUrl($href);
                    continue;
                }

                if ($isPhone && $callLink === null) {
                    $tr = null;
                    if ($link->relationLoaded('translations')) {
                        $tr = $link->translations->firstWhere('locale', $locale)
                            ?? $link->translations->firstWhere('locale', $fb)
                            ?? $link->translations->first();
                    }
                    $callLink = [
                        'href' => $href,
                        'label' => $tr?->name ?? $link->name ?? __('main.phone_number'),
                        'caption' => __('main.for_emergency_call_now'),
                    ];
                    continue;
                }

                if ($isVideo || $isPhone) {
                    continue;
                }

                $tr = null;
                if ($link->relationLoaded('translations')) {
                    $tr = $link->translations->firstWhere('locale', $locale)
                        ?? $link->translations->firstWhere('locale', $fb)
                        ?? $link->translations->first();
                }
                $label = $tr?->name ?? $link->name ?? __('Link');
                $target = in_array($link->target, ['_blank', '_self'], true) ? $link->target : '_self';
                $rel = $target === '_blank' ? 'noopener noreferrer' : null;
                $icon = trim((string) ($link->icon ?? ''));
                if (in_array($type, ['secondary', 'outline', 'border'], true)) {
                    $btnClass = 'th-btn th-border';
                } elseif (in_array($type, ['primary', 'solid', 'main'], true)) {
                    $btnClass = 'th-btn style2';
                } else {
                    $btnClass = $idx === 0 ? 'th-btn style2' : 'th-btn th-border';
                }
                $aboutButtons->push(compact('href', 'label', 'target', 'rel', 'icon', 'btnClass'));
            }
        }

        // $discountLabel = trim(strip_tags((string) $sub)) !== ''
        //     ? strip_tags($sub) . ' * ' . __('main.app_name') . ' *'
        //     : __('main.app_name') . ' * ' . __('main.services') . ' *';
                $discountLabel = __('main.app_name');
        $htmlLang = explode('-', strtolower(str_replace('_', '-', $locale)))[0];
        $discountAnimeClass = in_array($htmlLang, ['ar', 'fa', 'he', 'ur'], true)
            ? 'discount-anime discount-anime-plain'
            : 'discount-anime';

        return compact(
            'title',
            'sub',
            'desc',
            'descHasListItems',
            'aboutAlt',
            'primaryImg',
            'primaryAlt',
            'secondaryImg',
            'secondaryAlt',
            'videoImg',
            'videoAlt',
            'sideImg1',
            'sideImg2',
            'videoUrl',
            'videoMimeType',
            'videoIsLocal',
            'callLink',
            'aboutButtons',
            'discountLabel',
            'discountAnimeClass',
        );
    }
}
