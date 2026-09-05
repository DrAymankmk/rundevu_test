<?php

namespace App\Http\Controllers\AdminPanel\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\CmsLanguage;
use App\Support\Cms\CmsGalleryMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    public function index()
    {
        return view('blog.categories.index');
    }

    public function data(Request $request): JsonResponse
    {
        $query = BlogCategory::with('translations');

        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhereHas('translations', function ($q2) use ($search) {
                        $q2->where('title', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%");
                    });
            });
        }

        $totalRecords = BlogCategory::count();
        $filteredRecords = $query->count();

        if ($request->has('order')) {
            $columnIndex = $request->order[0]['column'];
            $columnName = $request->columns[$columnIndex]['data'];
            $columnDirection = $request->order[0]['dir'];

            $sortableColumns = ['id', 'name', 'slug', 'is_active', 'created_at'];
            if (in_array($columnName, $sortableColumns)) {
                $query->orderBy($columnName, $columnDirection);
            }
        } else {
            $query->orderByDesc('id');
        }

        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $categories = $query->skip($start)->take($length)->get();

        $data = $categories->map(function (BlogCategory $category) {
            $translation = $category->translation(app()->getLocale()) ?? $category->translations->first();

            return [
                'id' => $category->id,
                'name' => $category->name,
                'title' => $translation ? $translation->title : '-',
                'slug' => $category->slug,
                'image' => $category->getImageUrl('thumb'),
                'is_active' => $category->is_active,
                'posts_count' => $category->posts()->count(),
                'created_at' => optional($category->created_at)->format('Y-m-d'),
            ];
        });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ]);
    }

    public function create()
    {
        $languages = CmsLanguage::active()->ordered()->get();

        return view('blog.categories.create', compact('languages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_categories,slug',
            'is_active' => 'nullable|boolean',
            'translations' => 'required|array',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,gif,webp|max:5120',
            'image_alt' => 'nullable|string|max:255',
        ]);

        $category = BlogCategory::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?: Str::slug($validated['name']),
            'is_active' => $request->boolean('is_active'),
        ]);

        foreach ($validated['translations'] as $locale => $translationData) {
            $category->translations()->create([
                'locale' => $locale,
                'title' => $translationData['title'],
                'description' => $translationData['description'] ?? null,
            ]);
        }

        $this->syncImage($category, $request);

        return redirect()->route('blog.categories.index')
            ->with('success', __('blog.category_created'));
    }

    public function edit($id)
    {
        $category = BlogCategory::with('translations')->findOrFail($id);
        $languages = CmsLanguage::active()->ordered()->get();

        return view('blog.categories.edit', compact('category', 'languages'));
    }

    public function update(Request $request, $id)
    {
        $category = BlogCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_categories,slug,' . $category->id,
            'is_active' => 'nullable|boolean',
            'translations' => 'required|array',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,gif,webp|max:5120',
            'image_alt' => 'nullable|string|max:255',
            'remove_image' => 'nullable|boolean',
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?: Str::slug($validated['name']),
            'is_active' => $request->boolean('is_active'),
        ]);

        foreach ($validated['translations'] as $locale => $translationData) {
            $category->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title' => $translationData['title'],
                    'description' => $translationData['description'] ?? null,
                ]
            );
        }

        if ($request->boolean('remove_image')) {
            $category->clearMediaCollection('image');
        }

        $this->syncImage($category, $request);

        return redirect()->route('blog.categories.index')
            ->with('success', __('blog.category_updated'));
    }

    public function destroy($id): JsonResponse
    {
        $category = BlogCategory::findOrFail($id);
        $category->clearMediaCollection('image');
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => __('blog.category_deleted'),
        ]);
    }

    public function toggleStatus($id): JsonResponse
    {
        $category = BlogCategory::findOrFail($id);
        $category->update(['is_active' => !$category->is_active]);

        return response()->json([
            'success' => true,
            'message' => __('blog.status_updated'),
            'is_active' => $category->is_active,
        ]);
    }

    private function syncImage(BlogCategory $category, Request $request): void
    {
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $category->clearMediaCollection('image');
            CmsGalleryMedia::addFileWithAlt(
                $category,
                $request->file('image'),
                'image',
                $request->input('image_alt')
            );
            return;
        }

        if ($request->filled('image_alt')) {
            CmsGalleryMedia::persistCollectionAlt($category, 'image', $request->input('image_alt'));
        }
    }
}
