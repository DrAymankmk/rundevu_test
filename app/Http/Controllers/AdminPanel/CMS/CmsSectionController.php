<?php

namespace App\Http\Controllers\AdminPanel\CMS;

use App\Models\CmsLanguage;
use App\Models\CmsPage;
use App\Models\CmsSection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Support\Cms\CmsGalleryMedia;

class CmsSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pages = CmsPage::orderBy('order')->get();
        $selectedPageId = $request->get('page_id');

        return view('cms.sections.index', compact('pages', 'selectedPageId'));
    }

    /**
     * Get sections data for DataTable.
     */
    public function data(Request $request): JsonResponse
    {
        $query = CmsSection::with(['translations', 'page']);

        // Filter by page
        if ($request->has('page_id') && $request->page_id) {
            $query->where('cms_page_id', $request->page_id);
        }

        // Search
        if ($request->has('search') && ! empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhereHas('translations', function ($q2) use ($search) {
                        $q2->where('title', 'like', "%{$search}%");
                    });
            });
        }

        $totalRecords = CmsSection::count();
        $filteredRecords = $query->count();

        // Ordering
        if ($request->has('order')) {
            $columnIndex = $request->order[0]['column'];
            $columnName = $request->columns[$columnIndex]['data'];
            $columnDirection = $request->order[0]['dir'];

            $sortableColumns = ['id', 'name', 'type', 'is_active', 'order'];
            if (in_array($columnName, $sortableColumns)) {
                $query->orderBy($columnName, $columnDirection);
            }
        } else {
            $query->orderBy('order');
        }

        // Pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $sections = $query->skip($start)->take($length)->get();

        $data = $sections->map(function ($section) {
            $translation = $section->translation(app()->getLocale()) ?? $section->translations->first();

            return [
                'id' => $section->id,
                'name' => $section->name,
                'page_name' => $section->page->name ?? '-',
                'type' => $section->type,
                'title' => $translation ? $translation->title : '-',
                'is_active' => $section->is_active,
                'order' => $section->order,
                'items_count' => $section->items()->count(),
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
        $pages = CmsPage::orderBy('order')->get();
        $selectedPageId = $request->get('page_id');

        return view('cms.sections.create', compact('languages', 'pages', 'selectedPageId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cms_page_id' => 'required|exists:cms_pages,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'template' => 'nullable|string|max:255',
            'section_layout' => 'nullable|string|max:255',
            'settings' => 'nullable|array',
            'is_active' => 'boolean',
            'order' => 'integer',
            'translations' => 'required|array',
            'translations.*.title' => 'nullable|string|max:255',
            'translations.*.subtitle' => 'nullable|string',
            'translations.*.description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => ['nullable', CmsGalleryMedia::fileRule()],
        ]);

        $section = CmsSection::create([
            'cms_page_id' => $validated['cms_page_id'],
            'name' => $validated['name'],
            'type' => $validated['type'],
            'template' => $validated['template'] ?? null,
            'section_layout' => CmsSection::normalizeLayout($validated['section_layout'] ?? null, $validated['type'] ?? null),
            'settings' => $validated['settings'] ?? null,
            'is_active' => $validated['is_active'] ?? false,
            'order' => $validated['order'] ?? 0,
        ]);

        // Create translations and handle language-specific images
        foreach ($validated['translations'] as $locale => $translationData) {
            $section->translations()->create([
                'locale' => $locale,
                'title' => $translationData['title'] ?? null,
                'subtitle' => $translationData['subtitle'] ?? null,
                'description' => $translationData['description'] ?? null,
            ]);

            // Handle language-specific image uploads
            $translationFiles = $request->file("translations.{$locale}", []);
            if (isset($translationFiles['image']) && $translationFiles['image']->isValid()) {
                $section->clearMediaCollection("images_{$locale}");
                $section->addMedia($translationFiles['image'])->toMediaCollection("images_{$locale}");
            }
        }

        // Handle general image uploads (for backward compatibility)
        if ($request->hasFile('image')) {
            $section->addMediaFromRequest('image')->toMediaCollection('images');
        }

        // Handle gallery uploads
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                if ($file->isValid()) {
                    $section->addMedia($file)->toMediaCollection('gallery');
                }
            }
        }

        return redirect()->route('cms.sections.index', ['page_id' => $section->cms_page_id])
            ->with('success', __('Section created successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $section = CmsSection::find($id);
        $section->load(['translations', 'page', 'items' => function($query) {
            $query->orderBy('order');
        }, 'items.translations']);

        return view('cms.sections.show', compact('section'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $section = CmsSection::find($id);
        $languages = CmsLanguage::active()->ordered()->get();
        $pages = CmsPage::orderBy('order')->get();
        $section->load(['translations', 'items' => function ($query) {
            $query->orderBy('order');
        }, 'items.translations']);

        return view('cms.sections.edit', compact('section', 'languages', 'pages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'cms_page_id' => 'required|exists:cms_pages,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'template' => 'nullable|string|max:255',
            'section_layout' => 'nullable|string|max:255',
            'settings' => 'nullable|array',
            'is_active' => 'boolean',
            'order' => 'integer',
            'translations' => 'required|array',
            'translations.*.title' => 'nullable|string|max:255',
            'translations.*.subtitle' => 'nullable|string',
            'translations.*.description' => 'nullable|string',
            'translations.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => ['nullable', CmsGalleryMedia::fileRule()],
        ]);

        $section = CmsSection::find($id);
        $section->update([
            'cms_page_id' => $validated['cms_page_id'],
            'name' => $validated['name'],
            'type' => $validated['type'],
            'template' => $validated['template'] ?? null,
            'section_layout' => CmsSection::normalizeLayout($validated['section_layout'] ?? null, $validated['type'] ?? null),
            'settings' => $validated['settings'] ?? null,
            'is_active' => $validated['is_active'] ?? false,
            'order' => $validated['order'] ?? 0,
        ]);

        // Update or create translations and handle language-specific images
        foreach ($validated['translations'] as $locale => $translationData) {
            $section->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title' => $translationData['title'] ?? null,
                    'subtitle' => $translationData['subtitle'] ?? null,
                    'description' => $translationData['description'] ?? null,
                ]
            );

            // Handle language-specific image uploads
            $translationFiles = $request->file("translations.{$locale}", []);
            if (isset($translationFiles['image']) && $translationFiles['image']->isValid()) {
                $section->clearMediaCollection("images_{$locale}");
                $section->addMedia($translationFiles['image'])->toMediaCollection("images_{$locale}");
            }
        }

        // Handle general image uploads (for backward compatibility)
        if ($request->hasFile('image')) {
            $section->clearMediaCollection('images');
            $section->addMediaFromRequest('image')->toMediaCollection('images');
        }

        // Handle gallery uploads
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                if ($file->isValid()) {
                    $section->addMedia($file)->toMediaCollection('gallery');
                }
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('Section updated successfully'),
            ]);
        }

        return redirect()->route('cms.sections.index', ['page_id' => $section->cms_page_id])
            ->with('success', __('Section updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $section = CmsSection::find($id);
        $section->delete();

        return response()->json([
            'success' => true,
            'message' => __('Section deleted successfully'),
        ]);
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus($id): JsonResponse
    {
        $section = CmsSection::find($id);
        $section->update(['is_active' => ! $section->is_active]);

        return response()->json([
            'success' => true,
            'message' => __('Status updated successfully'),
            'is_active' => $section->is_active,
        ]);
    }

    /**
     * Get the form partial for AJAX requests.
     */
    public function getForm(Request $request, $id = null)
    {
        $languages = CmsLanguage::active()->ordered()->get();
        $pages = CmsPage::orderBy('order')->get();
        $selectedPageId = $request->get('page_id');

        if ($id) {
            $section = CmsSection::find($id);
            $section->load('translations');
        }

        return view('cms.sections.ajax_form', compact('section', 'languages', 'pages', 'selectedPageId'));
    }
}
