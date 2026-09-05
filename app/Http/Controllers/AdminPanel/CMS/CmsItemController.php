<?php

namespace App\Http\Controllers\AdminPanel\CMS;

use App\Models\CmsItem;
use App\Models\CmsSection;
use App\Models\CmsLanguage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Support\Cms\CmsGalleryMedia;

class CmsItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sections = CmsSection::with('page')->orderBy('order')->get();
        $selectedSectionId = $request->get('section_id');
        return view('cms.items.index', compact('sections', 'selectedSectionId'));
    }

    /**
     * Get items data for DataTable.
     */
    public function data(Request $request): JsonResponse
    {
        $query = CmsItem::with(['translations', 'section.page']);

        // Filter by section
        if ($request->has('section_id') && $request->section_id) {
            $query->where('cms_section_id', $request->section_id);
        }

        // Search
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->orWhereHas('translations', function ($q2) use ($search) {
                      $q2->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                  });
            });
        }

        $totalRecords = CmsItem::count();
        $filteredRecords = $query->count();

        // Ordering
        if ($request->has('order')) {
            $columnIndex = $request->order[0]['column'];
            $columnName = $request->columns[$columnIndex]['data'];
            $columnDirection = $request->order[0]['dir'];

            $sortableColumns = ['id', 'is_active', 'order'];
            if (in_array($columnName, $sortableColumns)) {
                $query->orderBy($columnName, $columnDirection);
            }
        } else {
            $query->orderBy('order');
        }

        // Pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $items = $query->skip($start)->take($length)->get();

        $data = $items->map(function ($item) {
            $translation = $item->translation(app()->getLocale()) ?? $item->translations->first();
            return [
                'id' => $item->id,
                'section_name' => $item->section->name ?? '-',
                'page_name' => $item->section->page->name ?? '-',
                'title' => $translation ? $translation->title : '-',
                'is_active' => $item->is_active,
                'order' => $item->order,
            ];
        });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $languages = CmsLanguage::active()->ordered()->get();
        $sections = CmsSection::with('page')->orderBy('order')->get();
        $selectedSectionId = $request->get('section_id');
        return view('cms.items.create', compact('languages', 'sections', 'selectedSectionId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cms_section_id' => 'required|exists:cms_sections,id',
            'slug' => 'required|string|max:255',
            'settings' => 'nullable|array',
            'is_active' => 'boolean',
            'order' => 'integer',
            'translations' => 'required|array',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.sub_title' => 'nullable|string|max:255',
            'translations.*.content' => 'nullable|string',
            'translations.*.icon' => 'nullable|string|max:255',
            'translations.*.image_alt' => 'nullable|string|max:255',
            'translations.*.icon_image_alt' => 'nullable|string|max:255',
            'translations.*.gallery' => 'nullable|array',
            'translations.*.gallery.*' => ['nullable', CmsGalleryMedia::fileRule()],
            'translations.*.gallery_existing_alt' => 'nullable|array',
            'translations.*.gallery_existing_alt.*' => 'nullable|string|max:255',
            'translations.*.gallery_new_alt' => 'nullable|array',
            'translations.*.gallery_new_alt.*' => 'nullable|string|max:255',
            'translations.*.gallery_replace' => 'nullable|array',
            'translations.*.gallery_replace.*' => ['nullable', CmsGalleryMedia::fileRule()],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_alt' => 'nullable|string|max:255',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => ['nullable', CmsGalleryMedia::fileRule()],
            'gallery_existing_alt' => 'nullable|array',
            'gallery_existing_alt.*' => 'nullable|string|max:255',
            'gallery_new_alt' => 'nullable|array',
            'gallery_new_alt.*' => 'nullable|string|max:255',
            'gallery_replace' => 'nullable|array',
            'gallery_replace.*' => ['nullable', CmsGalleryMedia::fileRule()],
        ]);

        $item = CmsItem::create([
            'cms_section_id' => $validated['cms_section_id'],
            'slug' => $validated['slug'],
            'settings' => $validated['settings'] ?? null,
            'is_active' => $validated['is_active'] ?? false,
            'order' => $validated['order'] ?? 0,
        ]);

        // Create translations and handle language-specific images
        foreach ($validated['translations'] as $locale => $translationData) {
            $item->translations()->create([
                'locale' => $locale,
                'title' => $translationData['title'],
                'sub_title' => $translationData['sub_title'] ?? null,
                'content' => $translationData['content'] ?? null,
                'icon' => $translationData['icon'] ?? null,
            ]);

            $this->syncItemTranslationMedia($request, $item, $locale);
            $this->syncItemTranslationGallery($request, $item, $locale);
        }

        $this->syncItemGeneralMedia($request, $item);
        CmsGalleryMedia::syncGalleryUploads(
            $item,
            $request->file('gallery'),
            $request->input('gallery_new_alt'),
            $request->input('gallery_existing_alt'),
            'gallery',
            $request->file('gallery_replace')
        );

        return redirect()->route('cms.items.index', ['section_id' => $item->cms_section_id])
            ->with('success', __('Item created successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $item = CmsItem::findOrFail($id);
        $item->load(['translations', 'section.page']);
        return view('cms.items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $languages = CmsLanguage::active()->ordered()->get();
        $sections = CmsSection::with('page')->orderBy('order')->get();
        $item = CmsItem::find($id);
        $item->load('translations');
        return view('cms.items.edit', compact('item', 'languages', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $item = CmsItem::find($id);
        $validated = $request->validate([
            'cms_section_id' => 'required|exists:cms_sections,id',
            'slug' => 'required|string|max:255',
            'settings' => 'nullable|array',
            'is_active' => 'boolean',
            'order' => 'integer',
            'translations' => 'required|array',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.sub_title' => 'nullable|string|max:255',
            'translations.*.content' => 'nullable|string',
            'translations.*.icon' => 'nullable|string|max:255',
            'translations.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'translations.*.image_alt' => 'nullable|string|max:255',
            'translations.*.icon_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'translations.*.icon_image_alt' => 'nullable|string|max:255',
            'translations.*.gallery' => 'nullable|array',
            'translations.*.gallery.*' => ['nullable', CmsGalleryMedia::fileRule()],
            'translations.*.gallery_existing_alt' => 'nullable|array',
            'translations.*.gallery_existing_alt.*' => 'nullable|string|max:255',
            'translations.*.gallery_new_alt' => 'nullable|array',
            'translations.*.gallery_new_alt.*' => 'nullable|string|max:255',
            'translations.*.gallery_replace' => 'nullable|array',
            'translations.*.gallery_replace.*' => ['nullable', CmsGalleryMedia::fileRule()],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_alt' => 'nullable|string|max:255',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => ['nullable', CmsGalleryMedia::fileRule()],
            'gallery_existing_alt' => 'nullable|array',
            'gallery_existing_alt.*' => 'nullable|string|max:255',
            'gallery_new_alt' => 'nullable|array',
            'gallery_new_alt.*' => 'nullable|string|max:255',
            'gallery_replace' => 'nullable|array',
            'gallery_replace.*' => ['nullable', CmsGalleryMedia::fileRule()],
        ]);

        $item->update([
            'cms_section_id' => $validated['cms_section_id'],
            'settings' => $validated['settings'] ?? null,
            'is_active' => $validated['is_active'] ?? false,
            'order' => $validated['order'] ?? 0,
        ]);

        // Update or create translations and handle language-specific images
        foreach ($validated['translations'] as $locale => $translationData) {
            $item->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title' => $translationData['title'],
                    'sub_title' => $translationData['sub_title'] ?? null,
                    'content' => $translationData['content'] ?? null,
                    'icon' => $translationData['icon'] ?? null,
                ]
            );

            $this->syncItemTranslationMedia($request, $item, $locale);
            $this->syncItemTranslationGallery($request, $item, $locale);
        }

        $this->syncItemGeneralMedia($request, $item);
        CmsGalleryMedia::syncGalleryUploads(
            $item,
            $request->file('gallery'),
            $request->input('gallery_new_alt'),
            $request->input('gallery_existing_alt'),
            'gallery',
            $request->file('gallery_replace')
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('Item updated successfully'),
            ]);
        }

        return redirect()->route('cms.items.index', ['section_id' => $item->cms_section_id])
            ->with('success', __('Item updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $item = CmsItem::find($id);
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => __('Item deleted successfully'),
        ]);
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus($id): JsonResponse
    {
        $item = CmsItem::find($id);
        $item->update(['is_active' => !$item->is_active]);

        return response()->json([
            'success' => true,
            'message' => __('Status updated successfully'),
            'is_active' => $item->is_active,
        ]);
    }

    private function syncItemTranslationMedia(Request $request, CmsItem $item, string $locale): void
    {
        $translationFiles = $request->file("translations.{$locale}", []);

        if (isset($translationFiles['image']) && $translationFiles['image']->isValid()) {
            $item->clearMediaCollection("images_{$locale}");
            CmsGalleryMedia::addFileWithAlt(
                $item,
                $translationFiles['image'],
                "images_{$locale}",
                $request->input("translations.{$locale}.image_alt")
            );
        } elseif ($request->exists("translations.{$locale}.image_alt")) {
            CmsGalleryMedia::persistCollectionAlt($item, "images_{$locale}", $request->input("translations.{$locale}.image_alt"));
        }

        if (isset($translationFiles['icon_image']) && $translationFiles['icon_image']->isValid()) {
            $item->clearMediaCollection("icons_{$locale}");
            CmsGalleryMedia::addFileWithAlt(
                $item,
                $translationFiles['icon_image'],
                "icons_{$locale}",
                $request->input("translations.{$locale}.icon_image_alt")
            );
        } elseif ($request->exists("translations.{$locale}.icon_image_alt")) {
            CmsGalleryMedia::persistCollectionAlt($item, "icons_{$locale}", $request->input("translations.{$locale}.icon_image_alt"));
        }
    }

    private function syncItemTranslationGallery(Request $request, CmsItem $item, string $locale): void
    {
        CmsGalleryMedia::syncGalleryUploads(
            $item,
            $request->file("translations.{$locale}.gallery"),
            $request->input("translations.{$locale}.gallery_new_alt"),
            $request->input("translations.{$locale}.gallery_existing_alt"),
            'gallery_' . $locale,
            $request->file("translations.{$locale}.gallery_replace")
        );
    }

    private function syncItemGeneralMedia(Request $request, CmsItem $item): void
    {
        if ($request->hasFile('image')) {
            $item->clearMediaCollection('images');
            CmsGalleryMedia::addFileWithAlt($item, $request->file('image'), 'images', $request->input('image_alt'));
        } elseif ($request->exists('image_alt')) {
            CmsGalleryMedia::persistCollectionAlt($item, 'images', $request->input('image_alt'));
        }

        if ($request->hasFile('thumbnail')) {
            $item->clearMediaCollection('thumbnails');
            CmsGalleryMedia::addFileWithAlt($item, $request->file('thumbnail'), 'thumbnails', $request->input('thumbnail_alt'));
        }

        if ($request->hasFile('icon')) {
            $item->clearMediaCollection('icons');
            CmsGalleryMedia::addFileWithAlt($item, $request->file('icon'), 'icons', $request->input('icon_alt'));
        }
    }
}
