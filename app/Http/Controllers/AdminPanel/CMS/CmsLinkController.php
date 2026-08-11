<?php

namespace App\Http\Controllers\AdminPanel\CMS;

use App\Models\CmsLink;
use App\Models\CmsPage;
use App\Models\CmsSection;
use App\Models\CmsItem;
use App\Models\CmsLanguage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class CmsLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pages = CmsPage::orderBy('order')->get();
        $sections = CmsSection::with('page')->orderBy('order')->get();
        $items = CmsItem::with('section.page')->orderBy('order')->get();
        $languages = CmsLanguage::active()->ordered()->get();
        
        return view('cms.links.index', compact('pages', 'sections', 'items', 'languages'));
    }

    /**
     * Get links data for DataTable.
     */
    public function data(Request $request): JsonResponse
    {
        $query = CmsLink::with(['translations', 'linkable']);

        // Filter by linkable type
        if ($request->has('linkable_type') && $request->linkable_type) {
            $query->where('linkable_type', $request->linkable_type);
        }

        // Search
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('link', 'like', "%{$search}%")
                  ->orWhereHas('translations', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $totalRecords = CmsLink::count();
        $filteredRecords = $query->count();

        // Ordering
        if ($request->has('order')) {
            $columnIndex = $request->order[0]['column'];
            $columnName = $request->columns[$columnIndex]['data'];
            $columnDirection = $request->order[0]['dir'];
            
            $sortableColumns = ['id', 'name', 'link', 'type', 'is_active', 'order'];
            if (in_array($columnName, $sortableColumns)) {
                $query->orderBy($columnName, $columnDirection);
            }
        } else {
            $query->orderBy('order');
        }

        // Pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $links = $query->skip($start)->take($length)->get();

        $data = $links->map(function ($link) {
            $translation = $link->translation(app()->getLocale()) ?? $link->translations->first();
            
            // Determine linkable name
            $linkableName = '-';
            if ($link->linkable) {
                $linkableName = $link->linkable->name ?? ($link->linkable->title ?? '-');
            }
            
            return [
                'id' => $link->id,
                'name' => $link->name,
                'translated_name' => $translation ? $translation->name : $link->name,
                'link' => $link->link,
                'icon' => $link->icon,
                'target' => $link->target,
                'type' => $link->type,
                'linkable_type' => class_basename($link->linkable_type),
                'linkable_name' => $linkableName,
                'is_active' => $link->is_active,
                'order' => $link->order,
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'linkable_type' => 'required|in:page,section,item',
            'linkable_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'link' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'target' => 'required|in:_self,_blank',
            'type' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'order' => 'integer',
            'translations' => 'nullable|array',
            'translations.*.name' => 'nullable|string|max:255',
        ]);

        // Determine the linkable model class
        $linkableClass = match($validated['linkable_type']) {
            'page' => CmsPage::class,
            'section' => CmsSection::class,
            'item' => CmsItem::class,
        };

        $link = CmsLink::create([
            'linkable_type' => $linkableClass,
            'linkable_id' => $validated['linkable_id'],
            'name' => $validated['name'],
            'link' => $validated['link'] ?? null,
            'icon' => $validated['icon'] ?? null,
            'target' => $validated['target'],
            'type' => $validated['type'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'order' => $validated['order'] ?? 0,
        ]);

        // Create translations
        if (isset($validated['translations'])) {
            foreach ($validated['translations'] as $locale => $translationData) {
                if (!empty($translationData['name'])) {
                    $link->translations()->create([
                        'locale' => $locale,
                        'name' => $translationData['name'],
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => __('Link created successfully'),
            'data' => $link,
        ]);
    }

    /**
     * Show the specified resource.
     */
    public function show($id): JsonResponse
    {
        $link = CmsLink::find($id);
        $link->load('translations');
        
        // Prepare translations as key-value
        $translations = [];
        foreach ($link->translations as $translation) {
            $translations[$translation->locale] = [
                'name' => $translation->name,
            ];
        }
        
        // Determine linkable_type for form
        $linkableType = match($link->linkable_type) {
            CmsPage::class => 'page',
            CmsSection::class => 'section',
            CmsItem::class => 'item',
            default => 'page',
        };
        
        return response()->json([
            'success' => true,
            'data' => array_merge($link->toArray(), [
                'linkable_type_short' => $linkableType,
                'translations_keyed' => $translations,
            ]),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $link = CmsLink::find($id);
        $validated = $request->validate([
            'linkable_type' => 'required|in:page,section,item',
            'linkable_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'link' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'target' => 'required|in:_self,_blank',
            'type' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'order' => 'integer',
            'translations' => 'nullable|array',
            'translations.*.name' => 'nullable|string|max:255',
        ]);

        // Determine the linkable model class
        $linkableClass = match($validated['linkable_type']) {
            'page' => CmsPage::class,
            'section' => CmsSection::class,
            'item' => CmsItem::class,
        };

        $link->update([
            'linkable_type' => $linkableClass,
            'linkable_id' => $validated['linkable_id'],
            'name' => $validated['name'],
            'link' => $validated['link'] ?? null,
            'icon' => $validated['icon'] ?? null,
            'target' => $validated['target'],
            'type' => $validated['type'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'order' => $validated['order'] ?? 0,
        ]);

        // Update or create translations
        if (isset($validated['translations'])) {
            foreach ($validated['translations'] as $locale => $translationData) {
                if (!empty($translationData['name'])) {
                    $link->translations()->updateOrCreate(
                        ['locale' => $locale],
                        ['name' => $translationData['name']]
                    );
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => __('Link updated successfully'),
            'data' => $link,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $link = CmsLink::find($id);
        $link->delete();

        return response()->json([
            'success' => true,
            'message' => __('Link deleted successfully'),
        ]);
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus($id): JsonResponse
    {
        $link = CmsLink::find($id);
        $link->update(['is_active' => !$link->is_active]);

        return response()->json([
            'success' => true,
            'message' => __('Status updated successfully'),
            'is_active' => $link->is_active,
        ]);
    }
}
