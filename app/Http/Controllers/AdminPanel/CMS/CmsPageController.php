<?php

namespace App\Http\Controllers\AdminPanel\CMS;

use App\Models\CmsPage;
use App\Models\CmsLanguage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class CmsPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('cms.pages.index');
    }

    /**
     * Get pages data for DataTable.
     */
    public function data(Request $request): JsonResponse
    {
        $query = CmsPage::with('translations');

        // Search
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhereHas('translations', function ($q2) use ($search) {
                      $q2->where('title', 'like', "%{$search}%");
                  });
            });
        }

        $totalRecords = CmsPage::count();
        $filteredRecords = $query->count();

        // Ordering
        if ($request->has('order')) {
            $columnIndex = $request->order[0]['column'];
            $columnName = $request->columns[$columnIndex]['data'];
            $columnDirection = $request->order[0]['dir'];

            $sortableColumns = ['id', 'name', 'slug', 'is_active', 'order'];
            if (in_array($columnName, $sortableColumns)) {
                $query->orderBy($columnName, $columnDirection);
            }
        } else {
            $query->orderBy('order');
        }

        // Pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $pages = $query->skip($start)->take($length)->get();

        $data = $pages->map(function ($page) {
            $translation = $page->translation(app()->getLocale()) ?? $page->translations->first();
            return [
                'id' => $page->id,
                'name' => $page->name,
                'slug' => $page->slug,
                'title' => $translation ? $translation->title : '-',
                'is_active' => $page->is_active,
                'order' => $page->order,
                'sections_count' => $page->sections()->count(),
                'created_at' => $page->created_at->format('Y-m-d'),
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
    public function create()
    {
        $languages = CmsLanguage::active()->ordered()->get();
        return view('cms.pages.create', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'required|string|max:255|unique:cms_pages,slug',
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
            'order' => 'integer',
            'translations' => 'required|array',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.meta_description' => 'nullable|string',
            'translations.*.meta_keywords' => 'nullable|string|max:255',
        ]);

        $page = CmsPage::create([
            'slug' => $validated['slug'],
            'name' => $validated['name'],
            'is_active' => $validated['is_active'] ?? false,
            'order' => $validated['order'] ?? 0,
        ]);

        // Create translations
        foreach ($validated['translations'] as $locale => $translationData) {
            $page->translations()->create([
                'locale' => $locale,
                'title' => $translationData['title'],
                'meta_description' => $translationData['meta_description'] ?? null,
                'meta_keywords' => $translationData['meta_keywords'] ?? null,
            ]);
        }

        return redirect()->route('cms.pages.index')
            ->with('success', __('Page created successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $page = CmsPage::findOrFail($id);
        $page->load('translations', 'sections.translations');
        return view('cms.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $page = CmsPage::findOrFail($id);
        $languages = CmsLanguage::active()->ordered()->get();
        $page->load(['translations', 'sections' => function($query) {
            $query->orderBy('order');
        }, 'sections.translations']);
        return view('cms.pages.edit', compact('page', 'languages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $page = CmsPage::findOrFail($id);
        $validated = $request->validate([
            'slug' => 'required|string|max:255|unique:cms_pages,slug,' . $page->id,
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
            'order' => 'integer',
            'translations' => 'required|array',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.meta_description' => 'nullable|string',
            'translations.*.meta_keywords' => 'nullable|string|max:255',
        ]);

        $page->update([
            'slug' => $validated['slug'],
            'name' => $validated['name'],
            'is_active' => $validated['is_active'] ?? false,
            'order' => $validated['order'] ?? 0,
        ]);

        // Update or create translations
        foreach ($validated['translations'] as $locale => $translationData) {
            $page->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title' => $translationData['title'],
                    'meta_description' => $translationData['meta_description'] ?? null,
                    'meta_keywords' => $translationData['meta_keywords'] ?? null,
                ]
            );
        }

        return redirect()->route('cms.pages.index')
            ->with('success', __('Page updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CmsPage $page): JsonResponse
    {
        $page->delete();

        return response()->json([
            'success' => true,
            'message' => __('Page deleted successfully'),
        ]);
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus(CmsPage $page): JsonResponse
    {
        $page->update(['is_active' => !$page->is_active]);

        return response()->json([
            'success' => true,
            'message' => __('Status updated successfully'),
            'is_active' => $page->is_active,
        ]);
    }
}