<?php

namespace App\Http\Controllers\AdminPanel\CMS;

use App\Http\Controllers\Controller;
use App\Models\CmsItem;
use App\Models\CmsSection;
use App\Support\Cms\CmsGalleryMedia;
use App\Models\CmsLanguage;
use App\Models\CmsLink;
use App\Models\CmsPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CmsPageBuilderController extends Controller
{
    public function create()
    {
        $languages = CmsLanguage::active()->ordered()->get();
        $page = null;

        return view('cms.pages.builder', compact('languages', 'page'));
    }

    public function edit($id)
    {
        $page = CmsPage::query()->findOrFail($id);
        $page->load([
            'translations',
            'links.translations',
            'sections' => fn ($q) => $q->orderBy('order'),
            'sections.translations',
            'sections.links.translations',
            'sections.items' => fn ($q) => $q->orderBy('order'),
            'sections.items.translations',
            'sections.items.links.translations',
        ]);
        $languages = CmsLanguage::active()->ordered()->get();

        return view('cms.pages.builder', compact('languages', 'page'));
    }

    public function store(Request $request): RedirectResponse
    {
        $languages = CmsLanguage::active()->ordered()->get();
        $localeCodes = $languages->pluck('code')->all();
        $rules = $this->basePageRules($localeCodes, true);
        $rules = array_merge($rules, $this->nestedRules($localeCodes));

        $validated = $request->validate($rules);
        $validated['is_active'] = $request->boolean('is_active');

        $page = DB::transaction(function () use ($validated, $request, $localeCodes) {
            $page = CmsPage::create([
                'slug' => $validated['slug'],
                'name' => $validated['name'],
                'is_active' => $validated['is_active'],
                'order' => $validated['order'] ?? 0,
            ]);

            $this->syncPageTranslations($page, $validated['translations']);
            $this->syncMorphLinks($page, $validated['page_links'] ?? []);

            foreach ($validated['sections'] ?? [] as $order => $sec) {
                $section = $page->sections()->create([
                    'name' => $sec['name'],
                    'type' => $sec['type'],
                    'template' => $sec['template'] ?? null,
                    'section_layout' => CmsSection::normalizeLayout($sec['section_layout'] ?? null, $sec['type'] ?? null),
                    'settings' => $sec['settings'] ?? null,
                    'is_active' => (bool) ($sec['is_active'] ?? false),
                    'order' => $sec['order'] ?? $order,
                ]);
                $this->syncSectionTranslations($section, $sec['translations'] ?? []);
                $this->syncMorphLinks($section, $sec['links'] ?? []);
                $this->syncSectionTranslationGalleriesFromRequest($request, $order, $section, $localeCodes);

                foreach ($sec['items'] ?? [] as $iOrder => $itemRow) {
                    $item = $section->items()->create([
                        'type' => $itemRow['type'] ?? 'default',
                        'slug' => $itemRow['slug'],
                        'settings' => $itemRow['settings'] ?? null,
                        'is_active' => (bool) ($itemRow['is_active'] ?? false),
                        'order' => $itemRow['order'] ?? $iOrder,
                    ]);
                    $this->syncItemTranslations($item, $itemRow['translations'] ?? []);
                    $this->syncMorphLinks($item, $itemRow['links'] ?? []);
                    $this->syncItemMediaFromRequest($request, $order, $iOrder, $item);
                }
            }

            return $page;
        });

        return redirect()->route('cms.pages.builder.edit', $page->id)
            ->with('success', __('Page created. You can keep editing in the page builder.'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $page = CmsPage::query()->findOrFail($id);
        $languages = CmsLanguage::active()->ordered()->get();
        $localeCodes = $languages->pluck('code')->all();

        $rules = $this->basePageRules($localeCodes, false, $page->id);
        $rules = array_merge($rules, $this->nestedRules($localeCodes, true));

        $validated = $request->validate($rules);
        $validated['is_active'] = $request->boolean('is_active');
        $this->assertBuilderPayloadBelongsToPage($page, $validated);

        DB::transaction(function () use ($page, $validated, $request, $localeCodes) {
            $page->update([
                'slug' => $validated['slug'],
                'name' => $validated['name'],
                'is_active' => $validated['is_active'],
                'order' => $validated['order'] ?? 0,
            ]);

            $this->syncPageTranslations($page, $validated['translations']);
            $this->syncMorphLinks($page, $validated['page_links'] ?? []);

            $keptSectionIds = [];

            foreach ($validated['sections'] ?? [] as $order => $sec) {
                if (! empty($sec['id'])) {
                    $section = CmsSection::query()
                        ->where('cms_page_id', $page->id)
                        ->whereKey($sec['id'])
                        ->firstOrFail();
                    $section->update([
                        'name' => $sec['name'],
                        'type' => $sec['type'],
                        'template' => $sec['template'] ?? null,
                        'section_layout' => CmsSection::normalizeLayout($sec['section_layout'] ?? null, $sec['type'] ?? null),
                        'settings' => $sec['settings'] ?? null,
                        'is_active' => (bool) ($sec['is_active'] ?? false),
                        'order' => $sec['order'] ?? $order,
                    ]);
                } else {
                    $section = $page->sections()->create([
                        'name' => $sec['name'],
                        'type' => $sec['type'],
                        'template' => $sec['template'] ?? null,
                        'section_layout' => CmsSection::normalizeLayout($sec['section_layout'] ?? null, $sec['type'] ?? null),
                        'settings' => $sec['settings'] ?? null,
                        'is_active' => (bool) ($sec['is_active'] ?? false),
                        'order' => $sec['order'] ?? $order,
                    ]);
                }

                $keptSectionIds[] = $section->id;
                $this->syncSectionTranslations($section, $sec['translations'] ?? []);
                $this->syncMorphLinks($section, $sec['links'] ?? []);
                $this->syncSectionTranslationGalleriesFromRequest($request, $order, $section, $localeCodes);

                $keptItemIds = [];
                foreach ($sec['items'] ?? [] as $iOrder => $itemRow) {
                    if (! empty($itemRow['id'])) {
                        $item = CmsItem::query()
                            ->where('cms_section_id', $section->id)
                            ->whereKey($itemRow['id'])
                            ->firstOrFail();
                        $item->update([
                            'type' => $itemRow['type'] ?? 'default',
                            'slug' => $itemRow['slug'],
                            'settings' => $itemRow['settings'] ?? null,
                            'is_active' => (bool) ($itemRow['is_active'] ?? false),
                            'order' => $itemRow['order'] ?? $iOrder,
                        ]);
                    } else {
                        $item = $section->items()->create([
                            'type' => $itemRow['type'] ?? 'default',
                            'slug' => $itemRow['slug'],
                            'settings' => $itemRow['settings'] ?? null,
                            'is_active' => (bool) ($itemRow['is_active'] ?? false),
                            'order' => $itemRow['order'] ?? $iOrder,
                        ]);
                    }
                    $keptItemIds[] = $item->id;
                    $this->syncItemTranslations($item, $itemRow['translations'] ?? []);
                    $this->syncMorphLinks($item, $itemRow['links'] ?? []);
                    $this->syncItemMediaFromRequest($request, $order, $iOrder, $item);
                }

                if (count($keptItemIds) === 0) {
                    $section->items()->delete();
                } else {
                    $section->items()->whereNotIn('id', $keptItemIds)->delete();
                }
            }

            if (count($keptSectionIds) === 0) {
                $page->sections()->delete();
            } else {
                $page->sections()->whereNotIn('id', $keptSectionIds)->delete();
            }
        });

        return redirect()->route('cms.pages.builder.edit', $page->id)
            ->with('success', __('Page builder saved successfully.'));
    }

    /**
     * @return array<string, mixed>
     */
    private function basePageRules(array $localeCodes, bool $isNew, ?int $pageId = null): array
    {
        $slugRule = Rule::unique('cms_pages', 'slug');
        if (! $isNew && $pageId) {
            $slugRule = Rule::unique('cms_pages', 'slug')->ignore($pageId);
        }

        $rules = [
            'slug' => ['required', 'string', 'max:255', $slugRule],
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
            'translations' => ['required', 'array'],
            'page_links' => ['nullable', 'array'],
        ];

        foreach ($localeCodes as $code) {
            $rules["translations.{$code}.title"] = ['required', 'string', 'max:255'];
            $rules["translations.{$code}.meta_description"] = ['nullable', 'string'];
            $rules["translations.{$code}.meta_keywords"] = ['nullable', 'string', 'max:255'];
        }

        $rules['page_links.*.id'] = ['nullable', 'integer', 'exists:cms_links,id'];
        $rules['page_links.*.name'] = ['nullable', 'string', 'max:255'];
        $rules['page_links.*.link'] = ['nullable', 'string', 'max:2048'];
        $rules['page_links.*.icon'] = ['nullable', 'string', 'max:255'];
        $rules['page_links.*.target'] = ['nullable', Rule::in(['_self', '_blank'])];
        $rules['page_links.*.type'] = ['nullable', 'string', 'max:255'];
        $rules['page_links.*.order'] = ['nullable', 'integer', 'min:0'];
        $rules['page_links.*.is_active'] = ['nullable', 'boolean'];
        foreach ($localeCodes as $code) {
            $rules["page_links.*.translations.{$code}.name"] = ['nullable', 'string', 'max:255'];
        }

        return $rules;
    }

    /**
     * @return array<string, mixed>
     */
    private function nestedRules(array $localeCodes, bool $withIds = false): array
    {
        $rules = [
            'sections' => ['nullable', 'array'],
            'sections.*.name' => ['required', 'string', 'max:255'],
            'sections.*.type' => ['required', 'string', 'max:255'],
            'sections.*.template' => ['nullable', 'string', 'max:255'],
            'sections.*.section_layout' => ['nullable', 'string', 'max:255'],
            'sections.*.settings' => ['nullable', 'array'],
            'sections.*.order' => ['nullable', 'integer', 'min:0'],
            'sections.*.is_active' => ['nullable', 'boolean'],
            'sections.*.gallery' => ['nullable', 'array'],
            'sections.*.gallery.*' => ['nullable', CmsGalleryMedia::fileRule()],
            'sections.*.gallery_replace' => ['nullable', 'array'],
            'sections.*.gallery_replace.*' => ['nullable', CmsGalleryMedia::fileRule()],
            'sections.*.translations.*.gallery' => ['nullable', 'array'],
            'sections.*.translations.*.gallery.*' => ['nullable', CmsGalleryMedia::fileRule()],
            'sections.*.translations.*.gallery_existing_alt' => ['nullable', 'array'],
            'sections.*.translations.*.gallery_existing_alt.*' => ['nullable', 'string', 'max:255'],
            'sections.*.translations.*.gallery_new_alt' => ['nullable', 'array'],
            'sections.*.translations.*.gallery_new_alt.*' => ['nullable', 'string', 'max:255'],
            'sections.*.translations.*.gallery_replace' => ['nullable', 'array'],
            'sections.*.translations.*.gallery_replace.*' => ['nullable', CmsGalleryMedia::fileRule()],
            'sections.*.links' => ['nullable', 'array'],
            'sections.*.links.*.name' => ['nullable', 'string', 'max:255'],
            'sections.*.links.*.link' => ['nullable', 'string', 'max:2048'],
            'sections.*.links.*.icon' => ['nullable', 'string', 'max:255'],
            'sections.*.links.*.target' => ['nullable', Rule::in(['_self', '_blank'])],
            'sections.*.links.*.type' => ['nullable', 'string', 'max:255'],
            'sections.*.links.*.order' => ['nullable', 'integer', 'min:0'],
            'sections.*.links.*.is_active' => ['nullable', 'boolean'],
            'sections.*.items' => ['nullable', 'array'],
            'sections.*.items.*.slug' => ['required', 'string', 'max:255'],
            'sections.*.items.*.type' => ['nullable', 'string', 'max:255'],
            'sections.*.items.*.settings' => ['nullable', 'array'],
            'sections.*.items.*.order' => ['nullable', 'integer', 'min:0'],
            'sections.*.items.*.is_active' => ['nullable', 'boolean'],
            'sections.*.items.*.gallery' => ['nullable', 'array'],
            'sections.*.items.*.gallery.*' => ['nullable', CmsGalleryMedia::fileRule()],
            'sections.*.items.*.gallery_replace' => ['nullable', 'array'],
            'sections.*.items.*.gallery_replace.*' => ['nullable', CmsGalleryMedia::fileRule()],
            'sections.*.items.*.translations.*.gallery' => ['nullable', 'array'],
            'sections.*.items.*.translations.*.gallery.*' => ['nullable', CmsGalleryMedia::fileRule()],
            'sections.*.items.*.translations.*.gallery_existing_alt' => ['nullable', 'array'],
            'sections.*.items.*.translations.*.gallery_existing_alt.*' => ['nullable', 'string', 'max:255'],
            'sections.*.items.*.translations.*.gallery_new_alt' => ['nullable', 'array'],
            'sections.*.items.*.translations.*.gallery_new_alt.*' => ['nullable', 'string', 'max:255'],
            'sections.*.items.*.translations.*.gallery_replace' => ['nullable', 'array'],
            'sections.*.items.*.translations.*.gallery_replace.*' => ['nullable', CmsGalleryMedia::fileRule()],
            'sections.*.items.*.translations.*.image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'sections.*.items.*.translations.*.icon_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp,svg', 'max:2048'],
        ];

        if ($withIds) {
            $rules['sections.*.id'] = ['nullable', 'integer', 'exists:cms_sections,id'];
            $rules['sections.*.links.*.id'] = ['nullable', 'integer', 'exists:cms_links,id'];
            $rules['sections.*.items.*.id'] = ['nullable', 'integer', 'exists:cms_items,id'];
            $rules['sections.*.items.*.links.*.id'] = ['nullable', 'integer', 'exists:cms_links,id'];
        }

        foreach ($localeCodes as $code) {
            $rules["sections.*.translations.{$code}.title"] = ['nullable', 'string', 'max:255'];
            $rules["sections.*.translations.{$code}.subtitle"] = ['nullable', 'string'];
            $rules["sections.*.translations.{$code}.description"] = ['nullable', 'string'];
            $rules["sections.*.links.*.translations.{$code}.name"] = ['nullable', 'string', 'max:255'];

            $rules["sections.*.items.*.translations.{$code}.title"] = ['required', 'string', 'max:255'];
            $rules["sections.*.items.*.translations.{$code}.sub_title"] = ['nullable', 'string', 'max:255'];
            $rules["sections.*.items.*.translations.{$code}.content"] = ['nullable', 'string'];
            $rules["sections.*.items.*.translations.{$code}.icon"] = ['nullable', 'string', 'max:255'];

            $rules['sections.*.items.*.links.*.name'] = ['nullable', 'string', 'max:255'];
            $rules["sections.*.items.*.links.*.link"] = ['nullable', 'string', 'max:2048'];
            $rules["sections.*.items.*.links.*.icon"] = ['nullable', 'string', 'max:255'];
            $rules["sections.*.items.*.links.*.target"] = ['nullable', Rule::in(['_self', '_blank'])];
            $rules["sections.*.items.*.links.*.type"] = ['nullable', 'string', 'max:255'];
            $rules["sections.*.items.*.links.*.order"] = ['nullable', 'integer', 'min:0'];
            $rules["sections.*.items.*.links.*.is_active"] = ['nullable', 'boolean'];
            $rules["sections.*.items.*.links.*.translations.{$code}.name"] = ['nullable', 'string', 'max:255'];
        }

        return $rules;
    }

    private function syncPageTranslations(CmsPage $page, array $translations): void
    {
        foreach ($translations as $locale => $data) {
            $page->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title' => $data['title'],
                    'meta_description' => $data['meta_description'] ?? null,
                    'meta_keywords' => $data['meta_keywords'] ?? null,
                ]
            );
        }
    }

    private function syncSectionTranslations(CmsSection $section, array $translations): void
    {
        foreach ($translations as $locale => $data) {
            $section->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title' => $data['title'] ?? null,
                    'subtitle' => $data['subtitle'] ?? null,
                    'description' => $data['description'] ?? null,
                ]
            );
        }
    }

    private function syncItemMediaFromRequest(Request $request, int $sectionIndex, int $itemIndex, CmsItem $item): void
    {
        $translations = $request->input("sections.{$sectionIndex}.items.{$itemIndex}.translations", []);
        if (! is_array($translations)) {
            $translations = [];
        }

        foreach (array_keys($translations) as $locale) {
            $translationFiles = $request->file("sections.{$sectionIndex}.items.{$itemIndex}.translations.{$locale}", []);
            if (! is_array($translationFiles)) {
                $translationFiles = [];
            }

            $imageAlt = $request->input("sections.{$sectionIndex}.items.{$itemIndex}.translations.{$locale}.image_alt");
            if (isset($translationFiles['image']) && $translationFiles['image']?->isValid()) {
                $item->clearMediaCollection("images_{$locale}");
                CmsGalleryMedia::addFileWithAlt($item, $translationFiles['image'], "images_{$locale}", $imageAlt);
            } elseif ($request->exists("sections.{$sectionIndex}.items.{$itemIndex}.translations.{$locale}.image_alt")) {
                CmsGalleryMedia::persistCollectionAlt($item, "images_{$locale}", $imageAlt);
            }

            $iconAlt = $request->input("sections.{$sectionIndex}.items.{$itemIndex}.translations.{$locale}.icon_image_alt");
            if (isset($translationFiles['icon_image']) && $translationFiles['icon_image']?->isValid()) {
                $item->clearMediaCollection("icons_{$locale}");
                CmsGalleryMedia::addFileWithAlt($item, $translationFiles['icon_image'], "icons_{$locale}", $iconAlt);
            } elseif ($request->exists("sections.{$sectionIndex}.items.{$itemIndex}.translations.{$locale}.icon_image_alt")) {
                CmsGalleryMedia::persistCollectionAlt($item, "icons_{$locale}", $iconAlt);
            }

            CmsGalleryMedia::syncGalleryUploads(
                $item,
                $request->file("sections.{$sectionIndex}.items.{$itemIndex}.translations.{$locale}.gallery"),
                $request->input("sections.{$sectionIndex}.items.{$itemIndex}.translations.{$locale}.gallery_new_alt"),
                $request->input("sections.{$sectionIndex}.items.{$itemIndex}.translations.{$locale}.gallery_existing_alt"),
                'gallery_' . $locale,
                $request->file("sections.{$sectionIndex}.items.{$itemIndex}.translations.{$locale}.gallery_replace")
            );
        }

        // Legacy single gallery field (kept for backward compatibility).
        CmsGalleryMedia::syncGalleryUploads(
            $item,
            $request->file("sections.{$sectionIndex}.items.{$itemIndex}.gallery"),
            $request->input("sections.{$sectionIndex}.items.{$itemIndex}.gallery_new_alt"),
            $request->input("sections.{$sectionIndex}.items.{$itemIndex}.gallery_existing_alt"),
            'gallery',
            $request->file("sections.{$sectionIndex}.items.{$itemIndex}.gallery_replace")
        );
    }

    private function syncSectionTranslationGalleriesFromRequest(
        Request $request,
        int $sectionIndex,
        CmsSection $section,
        array $localeCodes
    ): void {
        $addedGalleryImages = false;

        foreach ($localeCodes as $locale) {
            $collection = 'gallery_' . $locale;
            $beforeCount = $section->getMedia($collection)->count();

            CmsGalleryMedia::syncGalleryUploads(
                $section,
                $request->file("sections.{$sectionIndex}.translations.{$locale}.gallery"),
                $request->input("sections.{$sectionIndex}.translations.{$locale}.gallery_new_alt"),
                $request->input("sections.{$sectionIndex}.translations.{$locale}.gallery_existing_alt"),
                $collection,
                $request->file("sections.{$sectionIndex}.translations.{$locale}.gallery_replace")
            );

            if ($section->getMedia($collection)->count() > $beforeCount) {
                $addedGalleryImages = true;
            }
        }

        // Legacy single gallery field (kept for backward compatibility).
        $legacyBeforeCount = $section->getMedia('gallery')->count();
        CmsGalleryMedia::syncGalleryUploads(
            $section,
            $request->file("sections.{$sectionIndex}.gallery"),
            $request->input("sections.{$sectionIndex}.gallery_new_alt"),
            $request->input("sections.{$sectionIndex}.gallery_existing_alt"),
            'gallery',
            $request->file("sections.{$sectionIndex}.gallery_replace")
        );

        if ($section->getMedia('gallery')->count() > $legacyBeforeCount) {
            $addedGalleryImages = true;
        }

        if ($addedGalleryImages) {
            $this->syncSectionPrimaryImageFromGallery($section, $localeCodes);
        }
    }

    private function syncSectionPrimaryImageFromGallery(CmsSection $section, array $localeCodes = []): void
    {
        if ($section->getFirstMedia('images')) {
            return;
        }

        foreach ($localeCodes as $locale) {
            $firstImage = $section->getMedia('gallery_' . $locale)->first(
                fn ($media) => CmsGalleryMedia::isImage($media)
            );
            if ($firstImage) {
                $firstImage->copy($section, 'images');

                return;
            }
        }

        $firstImage = $section->getMedia('gallery')->first(
            fn ($media) => CmsGalleryMedia::isImage($media)
        );
        if ($firstImage) {
            $firstImage->copy($section, 'images');
        }
    }

    private function syncItemTranslations(CmsItem $item, array $translations): void
    {
        foreach ($translations as $locale => $data) {
            $item->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title' => $data['title'],
                    'sub_title' => $data['sub_title'] ?? null,
                    'content' => $data['content'] ?? null,
                    'icon' => $data['icon'] ?? null,
                ]
            );
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $links
     */
    private function syncMorphLinks(\Illuminate\Database\Eloquent\Model $model, array $links): void
    {
        $keptIds = [];

        foreach ($links as $idx => $row) {
            $name = trim((string) ($row['name'] ?? ''));

            if ($name === '') {
                if (! empty($row['id'])) {
                    CmsLink::query()
                        ->whereKey($row['id'])
                        ->where('linkable_type', $model->getMorphClass())
                        ->where('linkable_id', $model->getKey())
                        ->delete();
                }

                continue;
            }

            $payload = [
                'name' => $name,
                'link' => $row['link'] ?? null,
                'icon' => $row['icon'] ?? null,
                'target' => $row['target'] ?? '_self',
                'type' => $row['type'] ?? null,
                'order' => (int) ($row['order'] ?? $idx),
                'is_active' => array_key_exists('is_active', $row) ? (bool) intval($row['is_active']) : true,
            ];

            if (! empty($row['id'])) {
                $link = CmsLink::query()
                    ->whereKey($row['id'])
                    ->where('linkable_type', $model->getMorphClass())
                    ->where('linkable_id', $model->getKey())
                    ->first();

                if ($link) {
                    $link->update($payload);
                    $this->syncLinkTranslations($link, $row['translations'] ?? []);
                    $keptIds[] = (int) $link->id;
                }
            } else {
                $link = $model->links()->create($payload);
                $this->syncLinkTranslations($link, $row['translations'] ?? []);
                $keptIds[] = (int) $link->id;
            }
        }

        $query = CmsLink::query()
            ->where('linkable_type', $model->getMorphClass())
            ->where('linkable_id', $model->getKey());

        if (count($keptIds) === 0) {
            $query->delete();

            return;
        }

        $query->whereNotIn('id', $keptIds)->delete();
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    private function assertBuilderPayloadBelongsToPage(CmsPage $page, array $validated): void
    {
        foreach ($validated['page_links'] ?? [] as $row) {
            if (empty($row['id'])) {
                continue;
            }
            $exists = CmsLink::query()
                ->whereKey($row['id'])
                ->where('linkable_type', $page->getMorphClass())
                ->where('linkable_id', $page->id)
                ->exists();
            if (! $exists) {
                abort(422, __('Invalid page link reference.'));
            }
        }

        foreach ($validated['sections'] ?? [] as $sec) {
            if (! empty($sec['id'])) {
                $exists = CmsSection::query()
                    ->whereKey($sec['id'])
                    ->where('cms_page_id', $page->id)
                    ->exists();
                if (! $exists) {
                    abort(422, __('Invalid section reference.'));
                }
            }

            $sectionId = $sec['id'] ?? null;

            foreach ($sec['links'] ?? [] as $row) {
                if (empty($row['id']) || ! $sectionId) {
                    continue;
                }
                $exists = CmsLink::query()
                    ->whereKey($row['id'])
                    ->where('linkable_type', (new CmsSection)->getMorphClass())
                    ->where('linkable_id', $sectionId)
                    ->exists();
                if (! $exists) {
                    abort(422, __('Invalid section link reference.'));
                }
            }

            foreach ($sec['items'] ?? [] as $itemRow) {
                if (! empty($itemRow['id'])) {
                    if (! $sectionId) {
                        abort(422, __('Invalid item reference.'));
                    }
                    $exists = CmsItem::query()
                        ->whereKey($itemRow['id'])
                        ->where('cms_section_id', $sectionId)
                        ->exists();
                    if (! $exists) {
                        abort(422, __('Invalid item reference.'));
                    }
                }

                $itemId = $itemRow['id'] ?? null;
                foreach ($itemRow['links'] ?? [] as $lrow) {
                    if (empty($lrow['id']) || ! $itemId) {
                        continue;
                    }
                    $exists = CmsLink::query()
                        ->whereKey($lrow['id'])
                        ->where('linkable_type', (new CmsItem)->getMorphClass())
                        ->where('linkable_id', $itemId)
                        ->exists();
                    if (! $exists) {
                        abort(422, __('Invalid item link reference.'));
                    }
                }
            }
        }
    }

    private function syncLinkTranslations(CmsLink $link, array $translations): void
    {
        foreach ($translations as $locale => $data) {
            if (empty($data['name'])) {
                continue;
            }
            $link->translations()->updateOrCreate(
                ['locale' => $locale],
                ['name' => $data['name']]
            );
        }
    }
}
