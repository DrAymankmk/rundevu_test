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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => ['nullable', CmsGalleryMedia::fileRule()],
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

            // Handle language-specific image uploads
            $translationFiles = $request->file("translations.{$locale}", []);
            if (isset($translationFiles['image']) && $translationFiles['image']->isValid()) {
                $item->clearMediaCollection("images_{$locale}");
                $item->addMedia($translationFiles['image'])->toMediaCollection("images_{$locale}");
            }
            if (isset($translationFiles['icon_image']) && $translationFiles['icon_image']->isValid()) {
                $item->clearMediaCollection("icons_{$locale}");
                $item->addMedia($translationFiles['icon_image'])->toMediaCollection("icons_{$locale}");
            }
        }

        // Handle general image uploads (for backward compatibility)
        if ($request->hasFile('image')) {
            $item->addMediaFromRequest('image')->toMediaCollection('images');
        }
        if ($request->hasFile('icon')) {
            $item->addMediaFromRequest('icon')->toMediaCollection('icons');
        }

        // Handle gallery uploads
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                if ($file->isValid()) {
                    $item->addMedia($file)->toMediaCollection('gallery');
                }
            }
        }

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
            'translations.*.icon_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => ['nullable', CmsGalleryMedia::fileRule()],
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

            // Handle language-specific image uploads
            $translationFiles = $request->file("translations.{$locale}", []);
            if (isset($translationFiles['image']) && $translationFiles['image']->isValid()) {
                $item->clearMediaCollection("images_{$locale}");
                $item->addMedia($translationFiles['image'])->toMediaCollection("images_{$locale}");
            }
            if (isset($translationFiles['icon_image']) && $translationFiles['icon_image']->isValid()) {
                $item->clearMediaCollection("icons_{$locale}");
                $item->addMedia($translationFiles['icon_image'])->toMediaCollection("icons_{$locale}");
            }
        }

        // Handle general image uploads (for backward compatibility)
        if ($request->hasFile('image')) {
            $item->clearMediaCollection('images');
            $item->addMediaFromRequest('image')->toMediaCollection('images');
        }
        if ($request->hasFile('thumbnail')) {
            $item->clearMediaCollection('thumbnails');
            $item->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnails');
        }
        if ($request->hasFile('icon')) {
            $item->clearMediaCollection('icons');
            $item->addMediaFromRequest('icon')->toMediaCollection('icons');
        }

        // Handle gallery uploads
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                if ($file->isValid()) {
                    $item->addMedia($file)->toMediaCollection('gallery');
                }
            }
        }

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
}
