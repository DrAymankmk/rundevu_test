<?php

namespace App\Services\Frontend;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Clinic;
use App\Models\CmsItem;
use App\Models\CmsSection;
use App\Support\Cms\CmsGalleryMedia;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FrontendMediaCatalog
{
    public const PER_PAGE = 24;

    public const SOURCES = [
        'theme',
        'cms',
        'blogs',
        'blog_categories',
        'clinics',
        'doctors',
    ];

    /**
     * Recommended pixel sizes for each frontend media slot.
     *
     * @return array<string, array{width: int, height: int, label: string, note: string}>
     */
    public static function sizeHints(): array
    {
        return [
            'theme_logo' => [
                'width' => 250,
                'height' => 60,
                'label' => '250 × 60 px',
                'note' => 'Header & footer logo. Displayed at about 250 × 60.',
            ],
            'theme_hero_bg' => [
                'width' => 1920,
                'height' => 1026,
                'label' => '1920 × 1026 px',
                'note' => 'Full-width hero background. Landscape, high resolution.',
            ],
            'theme_section_bg' => [
                'width' => 1920,
                'height' => 800,
                'label' => '1920 × 800 px',
                'note' => 'Full-width section background.',
            ],
            'theme_breadcrumb_bg' => [
                'width' => 1920,
                'height' => 400,
                'label' => '1920 × 400 px',
                'note' => 'Page breadcrumb banner background.',
            ],
            'clinic' => [
                'width' => 840,
                'height' => 560,
                'label' => '840 × 560 px',
                'note' => 'Clinic card & details photo. Landscape, 16:9 works well.',
            ],
            'doctor' => [
                'width' => 800,
                'height' => 800,
                'label' => '800 × 800 px',
                'note' => 'Doctor card & profile photo. Portrait or square crops to 800 × 800.',
            ],
            'blog_post' => [
                'width' => 524,
                'height' => 280,
                'label' => '524 × 280 px',
                'note' => 'Blog listing & article cover. 3:2 landscape.',
            ],
            'blog_category' => [
                'width' => 600,
                'height' => 400,
                'label' => '600 × 400 px',
                'note' => 'Category thumbnail.',
            ],
            'hero_bg' => [
                'width' => 1920,
                'height' => 900,
                'label' => '1920 × 900 px',
                'note' => 'Hero section background.',
            ],
            'hero_slide' => [
                'width' => 800,
                'height' => 1000,
                'label' => '800 × 1000 px',
                'note' => 'Hero slide / portrait image.',
            ],
            'about' => [
                'width' => 800,
                'height' => 900,
                'label' => '800 × 900 px',
                'note' => 'About section photo.',
            ],
            'about_us_1' => [
                'width' => 1000,
                'height' => 632,
                'label' => '1000 × 632 px',
                'note' => 'About Us photo 1 (primary). Landscape. Same for EN & AR.',
            ],
            'about_us_2' => [
                'width' => 1000,
                'height' => 632,
                'label' => '1000 × 632 px',
                'note' => 'About Us photo 2 (secondary). Landscape. Same for EN & AR.',
            ],
            'about_us_3' => [
                'width' => 387,
                'height' => 433,
                'label' => '387 × 433 px',
                'note' => 'About Us photo 3 (video/cover). Portrait. Same for EN & AR.',
            ],
            'why_choose_1' => [
                'width' => 401,
                'height' => 397,
                'label' => '401 × 397 px',
                'note' => 'Why Choose Us photo 1 (img1). Near-square, circular crop. Same for EN & AR.',
            ],
            'why_choose_2' => [
                'width' => 536,
                'height' => 532,
                'label' => '536 × 532 px',
                'note' => 'Why Choose Us photo 2 (img2). Near-square, circular crop. Same for EN & AR.',
            ],
            'why_choose_3' => [
                'width' => 282,
                'height' => 281,
                'label' => '282 × 281 px',
                'note' => 'Why Choose Us photo 3 (img3). Near-square, circular crop. Same for EN & AR.',
            ],
            'download_app' => [
                'width' => 700,
                'height' => 900,
                'label' => '700 × 900 px',
                'note' => 'App screenshot / phone mockup.',
            ],
            'gallery' => [
                'width' => 800,
                'height' => 600,
                'label' => '800 × 600 px',
                'note' => 'Gallery item. 4:3 landscape.',
            ],
            'testimonial' => [
                'width' => 400,
                'height' => 400,
                'label' => '400 × 400 px',
                'note' => 'Testimonial avatar. Square.',
            ],
            'services' => [
                'width' => 315,
                'height' => 132,
                'label' => '315 × 132 px',
                'note' => 'Service card image.',
            ],
            'cms_icon' => [
                'width' => 128,
                'height' => 128,
                'label' => '128 × 128 px',
                'note' => 'Feature / service icon. PNG or SVG with transparent background.',
            ],
            'cms_image' => [
                'width' => 800,
                'height' => 600,
                'label' => '800 × 600 px',
                'note' => 'CMS section or item image.',
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function themeSlots(): array
    {
        return [
            [
                'id' => 'logo',
                'path' => 'frontend/assets/img/logo.png',
                'title' => 'Website logo',
                'slot' => 'Header & footer',
                'hint_key' => 'theme_logo',
            ],
            [
                'id' => 'hero_bg',
                'path' => 'frontend/assets/img/hero/hero_bg_1_1.jpg',
                'title' => 'Hero background',
                'slot' => 'Home hero',
                'hint_key' => 'theme_hero_bg',
            ],
            [
                'id' => 'download_bg',
                'path' => 'frontend/assets/img/download_bg.jpeg',
                'title' => 'Download app background',
                'slot' => 'Download section',
                'hint_key' => 'theme_section_bg',
            ],
            [
                'id' => 'section_bg',
                'path' => 'frontend/assets/img/section_bg.jpeg',
                'title' => 'Section background',
                'slot' => 'Page sections',
                'hint_key' => 'theme_section_bg',
            ],
            [
                'id' => 'services_bg',
                'path' => 'frontend/assets/img/services_bg.jpeg',
                'title' => 'Services background',
                'slot' => 'Services section',
                'hint_key' => 'theme_section_bg',
            ],
            [
                'id' => 'breadcumb_clinics',
                'path' => 'frontend/assets/img/bg/breadcumb-clinics.jpg',
                'title' => 'Clinics list breadcrumb',
                'slot' => 'Clinics page',
                'hint_key' => 'theme_breadcrumb_bg',
            ],
            [
                'id' => 'breadcumb_clinic_details',
                'path' => 'frontend/assets/img/bg/breadcumb-clinic-details.jpg',
                'title' => 'Clinic details breadcrumb',
                'slot' => 'Clinic details page',
                'hint_key' => 'theme_breadcrumb_bg',
            ],
            [
                'id' => 'breadcumb_doctors',
                'path' => 'frontend/assets/img/bg/breadcumb-doctors.jpg',
                'title' => 'Doctors list breadcrumb',
                'slot' => 'Doctors page',
                'hint_key' => 'theme_breadcrumb_bg',
            ],
            [
                'id' => 'breadcumb_doctor_details',
                'path' => 'frontend/assets/img/bg/breadcumb-doctor-details.jpg',
                'title' => 'Doctor details breadcrumb',
                'slot' => 'Doctor details page',
                'hint_key' => 'theme_breadcrumb_bg',
            ],
            [
                'id' => 'why_choose_img_1',
                'path' => 'frontend/assets/img/normal/choose-img-1.jpg',
                'title' => 'Why Choose Us — photo 1',
                'slot' => 'Home · Why Choose Us (default)',
                'hint_key' => 'why_choose_1',
            ],
            [
                'id' => 'why_choose_img_2',
                'path' => 'frontend/assets/img/normal/choose-img-2.jpg',
                'title' => 'Why Choose Us — photo 2',
                'slot' => 'Home · Why Choose Us (default)',
                'hint_key' => 'why_choose_2',
            ],
            [
                'id' => 'why_choose_img_3',
                'path' => 'frontend/assets/img/normal/choose-img-3.jpg',
                'title' => 'Why Choose Us — photo 3',
                'slot' => 'Home · Why Choose Us (default)',
                'hint_key' => 'why_choose_3',
            ],
            [
                'id' => 'about_us_img_1',
                'path' => 'frontend/assets/img/normal/about_1_1.jpg',
                'title' => 'About Us — photo 1',
                'slot' => 'Home / About · About Us (default)',
                'hint_key' => 'about_us_1',
            ],
            [
                'id' => 'about_us_img_2',
                'path' => 'frontend/assets/img/normal/about_1_2.jpg',
                'title' => 'About Us — photo 2',
                'slot' => 'Home / About · About Us (default)',
                'hint_key' => 'about_us_2',
            ],
            [
                'id' => 'about_us_img_3',
                'path' => 'frontend/assets/img/normal/about_1_3.jpg',
                'title' => 'About Us — photo 3',
                'slot' => 'Home / About · About Us (default)',
                'hint_key' => 'about_us_3',
            ],
        ];
    }

    /**
     * @return array{data: array<int, array<string, mixed>>, meta: array<string, mixed>, counts: array<string, int>}
     */
    public function paginate(array $filters, int $page = 1, int $perPage = self::PER_PAGE): array
    {
        $items = $this->collect();
        $counts = $this->countBySource($items);

        $items = $this->filter($items, $filters);
        $total = $items->count();
        $page = max(1, $page);
        $perPage = max(1, min(48, $perPage));
        $slice = $items->forPage($page, $perPage)->values();

        $data = $slice->map(fn (array $item) => $this->hydrateDimensions($item))->all();

        $paginator = new LengthAwarePaginator($data, $total, $perPage, $page);

        return [
            'data' => $data,
            'meta' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
            'counts' => $counts,
        ];
    }

    public function collect(): Collection
    {
        return $this->themeItems()
            ->concat($this->clinicItems())
            ->concat($this->doctorItems())
            ->concat($this->blogPostItems())
            ->concat($this->blogCategoryItems())
            ->concat($this->cmsMediaItems())
            ->values();
    }

    public function update(string $key, UploadedFile $file): array
    {
        [$source, $id] = array_pad(explode(':', $key, 2), 2, null);

        if (! $source || $id === null || $id === '') {
            throw new \InvalidArgumentException(__('website_media.invalid_media'));
        }

        if (in_array($source, ['theme', 'clinic', 'doctor', 'blog_post', 'blog_category'], true)
            && ! Str::startsWith((string) $file->getMimeType(), 'image/')) {
            throw new \InvalidArgumentException(__('website_media.invalid_media'));
        }

        $item = null;

        switch ($source) {
            case 'theme':
                $this->replaceThemeFile($id, $file);
                break;
            case 'clinic':
            case 'doctor':
                $this->replaceClinicImage((int) $id, $file);
                break;
            case 'blog_post':
                $this->replaceBlogPostImage((int) $id, $file);
                break;
            case 'blog_category':
                $this->replaceBlogCategoryImage((int) $id, $file);
                break;
            case 'cms':
                $newMedia = $this->replaceSpatieMedia((int) $id, $file);
                $item = $this->collect()->firstWhere('key', 'cms:'.$newMedia->id);
                break;
            default:
                throw new \InvalidArgumentException(__('website_media.invalid_media'));
        }

        if (! $item) {
            $item = $this->collect()->firstWhere('key', $key);
        }
        if (! $item) {
            $item = ['key' => $key];
        }

        return $this->hydrateDimensions($item);
    }

    public function convertToRecommendedSize(string $key): array
    {
        $raw = $this->collect()->firstWhere('key', $key);
        if (! $raw) {
            throw new \InvalidArgumentException(__('website_media.invalid_media'));
        }

        $path = $raw['path'] ?? null;
        $hint = $raw['hint'] ?? $this->hint('cms_image');
        $width = (int) ($hint['width'] ?? 0);
        $height = (int) ($hint['height'] ?? 0);

        if (! $path || ! is_file($path) || ! empty($raw['is_video']) || $width < 1 || $height < 1) {
            throw new \InvalidArgumentException(__('website_media.cannot_convert'));
        }

        $mime = strtolower((string) ($raw['mime_type'] ?? $this->detectMime($path) ?? ''));
        $extension = strtolower((string) pathinfo($path, PATHINFO_EXTENSION));
        if (Str::contains($mime, 'svg') || $extension === 'svg') {
            throw new \InvalidArgumentException(__('website_media.cannot_convert_svg'));
        }

        $this->resizeImageFile($path, $width, $height);

        if (! empty($raw['media_id'])) {
            $this->refreshSpatieAfterResize((int) $raw['media_id'], $path);
        }

        $item = $this->find($key);
        if (! $item) {
            throw new \InvalidArgumentException(__('website_media.invalid_media'));
        }

        return $item;
    }

    public function find(string $key): ?array
    {
        $item = $this->collect()->firstWhere('key', $key);

        return $item ? $this->hydrateDimensions($item) : null;
    }

    private function themeItems(): Collection
    {
        return collect(self::themeSlots())->map(function (array $slot) {
            $relative = $slot['path'];
            $absolute = public_path($relative);
            $exists = is_file($absolute);
            $hint = $this->hint($slot['hint_key']);

            return $this->makeItem([
                'key' => 'theme:'.$slot['id'],
                'source' => 'theme',
                'source_label' => 'theme',
                'title' => $slot['title'],
                'subtitle' => $slot['slot'],
                'collection' => 'theme',
                'file_name' => basename($relative),
                'url' => $exists ? asset($relative).'?v='.filemtime($absolute) : null,
                'path' => $exists ? $absolute : null,
                'has_image' => $exists,
                'is_active' => true,
                'updated_at' => $exists ? filemtime($absolute) : 0,
                'hint' => $hint,
                'mime_type' => $exists ? ($this->detectMime($absolute) ?: 'image/jpeg') : 'image/jpeg',
            ]);
        });
    }

    private function clinicItems(): Collection
    {
        return Clinic::query()
            ->where('app_type', 1)
            ->orderBy('name')
            ->get(['id', 'name', 'image', 'status', 'updated_at'])
            ->map(function (Clinic $clinic) {
                return $this->entityFileItem(
                    $clinic,
                    'clinic',
                    'clinics',
                    'clinic',
                    $clinic->name,
                    __('website_media.clinic_photo'),
                    'media/clinics/',
                    asset('media/logo/logo.png')
                );
            });
    }

    private function doctorItems(): Collection
    {
        return Clinic::query()
            ->where('app_type', 3)
            ->orderBy('name')
            ->get(['id', 'name', 'image', 'status', 'updated_at'])
            ->map(function (Clinic $doctor) {
                return $this->entityFileItem(
                    $doctor,
                    'doctor',
                    'doctors',
                    'doctor',
                    $doctor->name,
                    __('website_media.doctor_photo'),
                    'media/clinics/',
                    asset('media/logo/logo.png')
                );
            });
    }

    private function entityFileItem(
        Clinic $entity,
        string $source,
        string $sourceGroup,
        string $hintKey,
        string $title,
        string $subtitle,
        string $directory,
        string $fallbackUrl
    ): array {
        $raw = $entity->getRawOriginal('image');
        $hasCustom = filled($raw);
        $absolute = $hasCustom ? public_path($directory.$raw) : null;
        $fileExists = $absolute && is_file($absolute);
        $url = $fileExists
            ? asset($directory.$raw).'?v='.filemtime($absolute)
            : $fallbackUrl;

        return $this->makeItem([
            'key' => $source.':'.$entity->id,
            'source' => $sourceGroup,
            'source_label' => $sourceGroup,
            'title' => $title,
            'subtitle' => $subtitle,
            'collection' => 'image',
            'file_name' => $raw ?: null,
            'url' => $url,
            'path' => $fileExists ? $absolute : null,
            'has_image' => $fileExists,
            'is_active' => (int) $entity->status === 1,
            'updated_at' => optional($entity->updated_at)->timestamp ?? 0,
            'hint' => $this->hint($hintKey),
            'mime_type' => $fileExists ? ($this->detectMime($absolute) ?: 'image/jpeg') : 'image/jpeg',
        ]);
    }

    private function blogPostItems(): Collection
    {
        $locale = app()->getLocale();

        return BlogPost::query()
            ->with(['translations', 'media'])
            ->orderByDesc('id')
            ->get()
            ->map(function (BlogPost $post) use ($locale) {
                $media = $post->getImageMedia($locale);
                $title = $post->getTranslatedAttribute('title', $locale) ?: $post->name;
                $path = $media && file_exists($media->getPath()) ? $media->getPath() : null;

                return $this->makeItem([
                    'key' => 'blog_post:'.$post->id,
                    'source' => 'blogs',
                    'source_label' => 'blogs',
                    'title' => $title,
                    'subtitle' => __('website_media.blog_cover'),
                    'collection' => $media?->collection_name ?: BlogPost::imageCollection($locale),
                    'file_name' => $media?->file_name,
                    'url' => $media ? ($post->getImageUrl('preview', $locale) ?: $post->getImageUrl('', $locale)) : null,
                    'path' => $path,
                    'has_image' => (bool) $path,
                    'is_active' => (bool) $post->is_active,
                    'updated_at' => optional($media?->updated_at ?? $post->updated_at)->timestamp ?? 0,
                    'hint' => $this->hint('blog_post'),
                    'mime_type' => $media?->mime_type ?: 'image/jpeg',
                    'media_id' => $media?->id,
                ]);
            });
    }

    private function blogCategoryItems(): Collection
    {
        $locale = app()->getLocale();

        return BlogCategory::query()
            ->with(['translations', 'media'])
            ->orderBy('name')
            ->get()
            ->map(function (BlogCategory $category) use ($locale) {
                $media = $category->getFirstMedia('image');
                $title = $category->getTranslatedAttribute('title', $locale) ?: $category->name;
                $path = $media && file_exists($media->getPath()) ? $media->getPath() : null;

                return $this->makeItem([
                    'key' => 'blog_category:'.$category->id,
                    'source' => 'blog_categories',
                    'source_label' => 'blog_categories',
                    'title' => $title,
                    'subtitle' => __('website_media.category_image'),
                    'collection' => 'image',
                    'file_name' => $media?->file_name,
                    'url' => $path ? $category->getImageUrl('preview') ?: $category->getImageUrl() : null,
                    'path' => $path,
                    'has_image' => (bool) $path,
                    'is_active' => (bool) $category->is_active,
                    'updated_at' => optional($media?->updated_at ?? $category->updated_at)->timestamp ?? 0,
                    'hint' => $this->hint('blog_category'),
                    'mime_type' => $media?->mime_type ?: 'image/jpeg',
                    'media_id' => $media?->id,
                ]);
            });
    }

    private function cmsMediaItems(): Collection
    {
        $mediaItems = Media::query()
            ->whereIn('model_type', [CmsSection::class, CmsItem::class])
            ->orderByDesc('id')
            ->get();

        $sectionIds = $mediaItems->where('model_type', CmsSection::class)->pluck('model_id')->unique()->filter();
        $itemIds = $mediaItems->where('model_type', CmsItem::class)->pluck('model_id')->unique()->filter();

        $sections = $sectionIds->isEmpty()
            ? collect()
            : CmsSection::with(['translations', 'page.translations'])->whereIn('id', $sectionIds)->get()->keyBy('id');
        $cmsItems = $itemIds->isEmpty()
            ? collect()
            : CmsItem::with(['translations', 'section.translations', 'section.page.translations'])->whereIn('id', $itemIds)->get()->keyBy('id');

        $whyChooseGalleryIndex = $this->sectionGalleryIndexes($mediaItems, $sections, 'why-choose-us');
        $aboutUsGalleryIndex = $this->sectionGalleryIndexes($mediaItems, $sections, 'about-us', true);

        return $mediaItems->map(function (Media $media) use ($sections, $cmsItems, $whyChooseGalleryIndex, $aboutUsGalleryIndex) {
            if ($media->model_type === CmsSection::class) {
                $media->setRelation('model', $sections->get($media->model_id));
            } elseif ($media->model_type === CmsItem::class) {
                $media->setRelation('model', $cmsItems->get($media->model_id));
            }

            $model = $media->model;
            $url = CmsGalleryMedia::displayUrl($media)
                ?? CmsGalleryMedia::accessibleUrl($media)
                ?? $media->getUrl();
            $path = file_exists($media->getPath()) ? $media->getPath() : null;
            $galleryIndex = $whyChooseGalleryIndex[$media->id]
                ?? $aboutUsGalleryIndex[$media->id]
                ?? null;
            $hintKey = $this->cmsHintKey($media, $model, $galleryIndex);
            $pageName = $this->cmsPageLabel($model);
            $sectionName = $this->cmsSectionLabel($model);
            $owner = $this->cmsOwnerLabel($model);
            $localeCode = $this->cmsMediaLocaleCode($media->collection_name);

            if ($galleryIndex !== null) {
                $slot = ($galleryIndex % 3) + 1;
                $sectionType = $model instanceof CmsSection
                    ? CmsSection::normalizeType($model->type)
                    : null;
                if ($sectionType === 'about-us') {
                    $owner = __('website_media.about_us_photo', [
                        'n' => $slot,
                        'locale' => strtoupper($localeCode ?: app()->getLocale()),
                    ]);
                } elseif ($sectionType === 'why-choose-us') {
                    $owner = __('website_media.why_choose_photo', [
                        'n' => $slot,
                        'locale' => strtoupper($localeCode ?: app()->getLocale()),
                    ]);
                }
            }

            $isActive = true;
            if ($model instanceof CmsSection || $model instanceof CmsItem) {
                $isActive = (bool) $model->is_active;
            }

            $subtitleParts = array_filter([
                $pageName,
                $sectionName,
                $localeCode ? strtoupper($localeCode) : null,
            ]);

            return $this->makeItem([
                'key' => 'cms:'.$media->id,
                'source' => 'cms',
                'source_label' => 'cms',
                'title' => $owner,
                'subtitle' => implode(' · ', $subtitleParts),
                'page_name' => $pageName,
                'section_name' => $sectionName,
                'collection' => $media->collection_name,
                'file_name' => $media->file_name,
                'url' => $url,
                'path' => $path,
                'has_image' => (bool) $path,
                'is_active' => $isActive,
                'updated_at' => optional($media->updated_at)->timestamp ?? 0,
                'hint' => $this->hint($hintKey),
                'mime_type' => $media->mime_type,
                'media_id' => $media->id,
                'is_video' => CmsGalleryMedia::isVideo($media),
            ]);
        });
    }

    /**
     * Map media id => 0-based gallery order for a CMS section type (per locale collection).
     *
     * @param  Collection<int, Media>  $mediaItems
     * @param  Collection<int, CmsSection>  $sections
     * @return array<int, int>
     */
    private function sectionGalleryIndexes(
        Collection $mediaItems,
        Collection $sections,
        string $sectionType,
        bool $imagesOnly = false
    ): array {
        $indexes = [];

        $groups = $mediaItems
            ->filter(function (Media $media) use ($sections, $sectionType, $imagesOnly) {
                if ($media->model_type !== CmsSection::class) {
                    return false;
                }

                $section = $sections->get($media->model_id);
                if (! $section) {
                    return false;
                }

                $type = CmsSection::normalizeType($section->type);
                $base = (string) preg_replace('/_[a-z]{2}$/i', '', (string) $media->collection_name);

                if ($type !== $sectionType || $base !== 'gallery') {
                    return false;
                }

                if ($imagesOnly && CmsGalleryMedia::isVideo($media)) {
                    return false;
                }

                return true;
            })
            ->groupBy(fn (Media $media) => $media->model_id.'|'.$media->collection_name);

        foreach ($groups as $group) {
            $ordered = $group->sortBy(function (Media $media) {
                return sprintf('%010d-%010d', (int) ($media->order_column ?? 0), (int) $media->id);
            })->values();

            foreach ($ordered as $index => $media) {
                $indexes[(int) $media->id] = (int) $index;
            }
        }

        return $indexes;
    }

    private function cmsMediaLocaleCode(?string $collection): ?string
    {
        if (! $collection) {
            return null;
        }

        if (preg_match('/_([a-z]{2})$/i', $collection, $matches)) {
            return strtolower($matches[1]);
        }

        return null;
    }

    private function cmsHintKey(Media $media, $model, ?int $galleryIndex = null): string
    {
        $collection = (string) $media->collection_name;
        $base = (string) preg_replace('/_[a-z]{2}$/i', '', $collection);

        if (Str::startsWith($base, 'icon')) {
            return 'cms_icon';
        }

        $sectionType = null;
        if ($model instanceof CmsSection) {
            $sectionType = CmsSection::normalizeType($model->type);
        } elseif ($model instanceof CmsItem && $model->section) {
            $sectionType = CmsSection::normalizeType($model->section->type);
        }

        if ($sectionType === 'hero') {
            return ($base === 'gallery' || $model instanceof CmsItem) ? 'hero_slide' : 'hero_bg';
        }
        if ($sectionType === 'about-us') {
            if ($base === 'gallery' && $galleryIndex !== null) {
                $slot = ($galleryIndex % 3) + 1;

                return 'about_us_'.$slot;
            }

            return 'about_us_1';
        }
        if ($sectionType === 'download-app') {
            return 'download_app';
        }
        if ($sectionType === 'gallery') {
            return 'gallery';
        }
        if ($sectionType === 'testimonial') {
            return 'testimonial';
        }
        if ($sectionType === 'services') {
            return $base === 'icons' ? 'cms_icon' : 'services';
        }
        if ($sectionType === 'features') {
            return $base === 'icons' ? 'cms_icon' : 'cms_image';
        }
        if ($sectionType === 'why-choose-us') {
            if ($base === 'icons') {
                return 'cms_icon';
            }
            if ($base === 'gallery' && $galleryIndex !== null) {
                $slot = ($galleryIndex % 3) + 1;

                return 'why_choose_'.$slot;
            }

            return 'why_choose_1';
        }

        return $base === 'gallery' ? 'gallery' : 'cms_image';
    }

    private function cmsOwnerLabel($model): string
    {
        $locale = app()->getLocale();

        if ($model instanceof CmsSection) {
            $title = $model->getTranslatedAttribute('title', $locale);

            return $title ?: ($model->name ?: __('website_media.cms_section'));
        }

        if ($model instanceof CmsItem) {
            $title = $model->getTranslatedAttribute('title', $locale);
            if ($title) {
                return $title;
            }
            $section = $this->cmsSectionLabel($model);

            return $section
                ? __('website_media.cms_item_of', ['section' => $section])
                : __('website_media.cms_item');
        }

        return class_basename($model ?? 'CMS');
    }

    private function cmsSectionLabel($model): string
    {
        $locale = app()->getLocale();
        $section = null;

        if ($model instanceof CmsSection) {
            $section = $model;
        } elseif ($model instanceof CmsItem) {
            $section = $model->section;
        }

        if (! $section) {
            return '';
        }

        $title = $section->getTranslatedAttribute('title', $locale);

        return $title
            ?: ($section->name
                ?: (CmsSection::normalizeType($section->type) ?: __('website_media.cms_section')));
    }

    private function cmsPageLabel($model): string
    {
        $locale = app()->getLocale();
        $page = null;

        if ($model instanceof CmsSection) {
            $page = $model->page;
        } elseif ($model instanceof CmsItem) {
            $page = $model->section?->page;
        }

        if (! $page) {
            return __('website_media.cms');
        }

        $title = method_exists($page, 'getTranslatedAttribute')
            ? $page->getTranslatedAttribute('title', $locale)
            : null;

        return $title ?: ($page->name ?: ($page->slug ?: __('website_media.cms')));
    }

    private function filter(Collection $items, array $filters): Collection
    {
        $source = trim((string) ($filters['source'] ?? ''));
        $search = mb_strtolower(trim((string) ($filters['search'] ?? '')));
        $status = $filters['status'] ?? '';
        $hasImage = $filters['has_image'] ?? '';

        if ($source !== '' && in_array($source, self::SOURCES, true)) {
            $items = $items->where('source', $source)->values();
        }

        if ($status === 'active') {
            $items = $items->where('is_active', true)->values();
        } elseif ($status === 'inactive') {
            $items = $items->where('is_active', false)->values();
        }

        if ($hasImage === '1' || $hasImage === 1 || $hasImage === true || $hasImage === 'yes') {
            $items = $items->where('has_image', true)->values();
        } elseif ($hasImage === '0' || $hasImage === 0 || $hasImage === 'missing') {
            $items = $items->where('has_image', false)->values();
        }

        if ($search !== '') {
            $items = $items->filter(function (array $item) use ($search) {
                $haystack = mb_strtolower(implode(' ', array_filter([
                    $item['title'] ?? '',
                    $item['subtitle'] ?? '',
                    $item['page_name'] ?? '',
                    $item['section_name'] ?? '',
                    $item['file_name'] ?? '',
                    $item['collection'] ?? '',
                    $item['source'] ?? '',
                ])));

                return Str::contains($haystack, $search);
            })->values();
        }

        return $items->sortBy(function (array $item) {
            return ($item['source'] ?? '').'-'.mb_strtolower((string) ($item['title'] ?? ''));
        })->values();
    }

    private function countBySource(Collection $items): array
    {
        $counts = ['all' => $items->count()];
        foreach (self::SOURCES as $source) {
            $counts[$source] = $items->where('source', $source)->count();
        }

        return $counts;
    }

    private function hydrateDimensions(array $item): array
    {
        $path = $item['path'] ?? null;
        unset($item['path']);

        $current = null;
        if ($path && is_file($path) && empty($item['is_video'])) {
            $info = @getimagesize($path);
            if ($info) {
                $current = [
                    'width' => (int) $info[0],
                    'height' => (int) $info[1],
                    'label' => (int) $info[0].' × '.(int) $info[1].' px',
                ];
            }
            $item['file_size'] = $this->formatBytes((int) filesize($path));
            $item['file_size_raw'] = (int) filesize($path);
        } else {
            $item['file_size'] = null;
            $item['file_size_raw'] = 0;
        }

        $hint = $item['hint'] ?? $this->hint('cms_image');
        $item['current_size'] = $current;
        $item['size_mismatch'] = $this->isSizeMismatch($current, $hint);
        $item['is_image'] = empty($item['is_video']) && Str::startsWith((string) ($item['mime_type'] ?? 'image/'), 'image/');
        $item['can_convert'] = $this->canConvertItem($item, $path);

        return $item;
    }

    private function canConvertItem(array $item, ?string $path): bool
    {
        if (empty($item['has_image']) || empty($item['is_image']) || ! empty($item['is_video'])) {
            return false;
        }

        $mime = strtolower((string) ($item['mime_type'] ?? ''));
        $extension = strtolower((string) pathinfo((string) ($item['file_name'] ?? $path ?? ''), PATHINFO_EXTENSION));
        if (Str::contains($mime, 'svg') || $extension === 'svg') {
            return false;
        }

        $hint = $item['hint'] ?? [];

        return (int) ($hint['width'] ?? 0) > 0
            && (int) ($hint['height'] ?? 0) > 0
            && $path
            && is_file($path);
    }

    private function isSizeMismatch(?array $current, array $hint): bool
    {
        if (! $current) {
            return false;
        }

        $w = max(1, (int) $hint['width']);
        $h = max(1, (int) $hint['height']);
        $cw = (int) $current['width'];
        $ch = (int) $current['height'];

        return abs($cw - $w) / $w > 0.15 || abs($ch - $h) / $h > 0.15;
    }

    private function hint(string $key): array
    {
        $hints = self::sizeHints();

        return $hints[$key] ?? $hints['cms_image'];
    }

    private function makeItem(array $item): array
    {
        $item['is_video'] = $item['is_video'] ?? false;
        $item['media_id'] = $item['media_id'] ?? null;

        return $item;
    }

    private function replaceThemeFile(string $id, UploadedFile $file): void
    {
        $slot = collect(self::themeSlots())->firstWhere('id', $id);
        if (! $slot) {
            throw new \InvalidArgumentException(__('website_media.invalid_media'));
        }

        $absolute = public_path($slot['path']);
        $directory = dirname($absolute);
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        if (is_file($absolute)) {
            @unlink($absolute);
        }

        $file->move($directory, basename($absolute));
    }

    private function replaceClinicImage(int $id, UploadedFile $file): void
    {
        $entity = Clinic::query()->whereIn('app_type', [1, 3])->findOrFail($id);
        $directory = public_path('media/clinics');
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $old = $entity->getRawOriginal('image');
        $entity->image = $file;
        $entity->save();

        if ($old && $old !== $entity->getRawOriginal('image')) {
            $oldPath = public_path('media/clinics/'.$old);
            if (is_file($oldPath)) {
                @unlink($oldPath);
            }
        }
    }

    private function replaceBlogPostImage(int $id, UploadedFile $file): void
    {
        $post = BlogPost::findOrFail($id);
        $locale = app()->getLocale();
        $collection = BlogPost::imageCollection($locale);
        $post->clearMediaCollection($collection);
        $post->clearMediaCollection('image');
        CmsGalleryMedia::addFileWithAlt($post, $file, $collection, null);
    }

    private function replaceBlogCategoryImage(int $id, UploadedFile $file): void
    {
        $category = BlogCategory::findOrFail($id);
        $category->clearMediaCollection('image');
        CmsGalleryMedia::addFileWithAlt($category, $file, 'image', null);
    }

    private function replaceSpatieMedia(int $id, UploadedFile $file): Media
    {
        $media = Media::query()
            ->whereIn('model_type', [CmsSection::class, CmsItem::class])
            ->findOrFail($id);

        $model = $media->model;
        if (! $model) {
            throw new \InvalidArgumentException(__('website_media.invalid_media'));
        }

        return CmsGalleryMedia::replaceMedia($media, $file);
    }

    private function resizeImageFile(string $path, int $width, int $height): void
    {
        $extension = strtolower((string) pathinfo($path, PATHINFO_EXTENSION));
        $image = Image::make($path)->orientate()->fit($width, $height);

        if (in_array($extension, ['jpg', 'jpeg'], true)) {
            $image->encode('jpg', 90)->save($path);
        } elseif ($extension === 'webp') {
            $image->encode('webp', 90)->save($path);
        } elseif ($extension === 'png') {
            $image->encode('png')->save($path);
        } elseif ($extension === 'gif') {
            $image->encode('gif')->save($path);
        } else {
            $image->save($path);
        }
    }

    private function refreshSpatieAfterResize(int $mediaId, string $path): void
    {
        $media = Media::find($mediaId);
        if (! $media) {
            return;
        }

        $media->size = is_file($path) ? (int) filesize($path) : $media->size;
        $media->save();

        try {
            $manipulator = app(\Spatie\MediaLibrary\Conversions\FileManipulator::class);
            $manipulator->createDerivedFiles($media->refresh());
        } catch (\Throwable $e) {
            // Conversions may be queued or unavailable; the original file is already resized.
        }
    }

    private function detectMime(string $path): ?string
    {
        if (function_exists('mime_content_type')) {
            $mime = @mime_content_type($path);
            if (is_string($mime) && $mime !== '') {
                return $mime;
            }
        }

        return null;
    }

    private function formatBytes(int $bytes, int $precision = 1): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        $value = (float) $bytes;
        while ($value >= 1024 && $i < count($units) - 1) {
            $value /= 1024;
            $i++;
        }

        return round($value, $precision).' '.$units[$i];
    }
}
