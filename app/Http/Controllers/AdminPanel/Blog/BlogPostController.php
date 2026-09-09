<?php

namespace App\Http\Controllers\AdminPanel\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\CmsLanguage;
use App\Services\Seo\SeoSyncService;
use App\Support\Cms\CmsGalleryMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BlogPostController extends Controller
{
    public function index()
    {
        return view('blog.posts.index');
    }

    public function data(Request $request): JsonResponse
    {
        $query = BlogPost::with(['categories.translations', 'translations', 'media']);

        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhereHas('translations', function ($q2) use ($search) {
                        $q2->where('title', 'like', "%{$search}%")
                            ->orWhere('slug', 'like', "%{$search}%")
                            ->orWhere('summary', 'like', "%{$search}%");
                    });
            });
        }

        $totalRecords = BlogPost::count();
        $filteredRecords = $query->count();

        if ($request->has('order')) {
            $columnIndex = $request->order[0]['column'];
            $columnName = $request->columns[$columnIndex]['data'];
            $columnDirection = $request->order[0]['dir'];

            $sortableColumns = ['id', 'name', 'slug', 'is_active', 'publish_date', 'created_at'];
            if (in_array($columnName, $sortableColumns)) {
                $query->orderBy($columnName, $columnDirection);
            }
        } else {
            $query->orderByDesc('id');
        }

        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $posts = $query->skip($start)->take($length)->get();
        $locale = app()->getLocale();

        $data = $posts->map(function (BlogPost $post) use ($locale) {
            $translation = $post->translation($locale) ?? $post->translations->first();

            return [
                'id' => $post->id,
                'name' => $post->name,
                'title' => $translation ? $translation->title : '-',
                'slug' => $post->getSlug($locale),
                'image' => $post->getImageUrl('thumb', $locale),
                'categories' => $post->categories->map(function (BlogCategory $category) use ($locale) {
                    return $category->getTranslatedAttribute('title', $locale) ?: $category->name;
                })->implode(', '),
                'is_active' => $post->is_active,
                'publish_date' => optional($post->publish_date)->format('Y-m-d H:i'),
                'created_at' => optional($post->created_at)->format('Y-m-d'),
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
        $categories = BlogCategory::with('translations')->orderBy('name')->get();
        $languages = CmsLanguage::active()->ordered()->get();

        return view('blog.posts.create', compact('categories', 'languages'));
    }

    public function store(Request $request, SeoSyncService $seoSync)
    {
        $validated = $request->validate(array_merge($this->postRules(), SeoSyncService::validationRules()));
        $resolvedSlugs = $this->resolveTranslationSlugs($validated['translations']);

        $post = BlogPost::create([
            'name' => $validated['name'],
            'slug' => $this->parentSlug($resolvedSlugs),
            'is_active' => $request->boolean('is_active'),
            'publish_date' => $validated['publish_date'] ?? null,
        ]);

        foreach ($validated['translations'] as $locale => $translationData) {
            $post->translations()->create([
                'locale' => $locale,
                'slug' => $resolvedSlugs[$locale],
                'title' => $translationData['title'],
                'summary' => $translationData['summary'],
                'content' => $translationData['content'],
                'tags' => $this->parseTags($translationData['tags'] ?? null),
            ]);

            $this->syncTranslationImage($post, $request, $locale);
        }

        $post->categories()->sync($validated['category_ids'] ?? []);
        $seoSync->sync($post, $request);

        return redirect()->route('blog.posts.index')
            ->with('success', __('blog.post_created'));
    }

    public function edit($id)
    {
        $post = BlogPost::with(['categories', 'translations', 'seoMeta.translations', 'media'])->findOrFail($id);
        $categories = BlogCategory::with('translations')->orderBy('name')->get();
        $languages = CmsLanguage::active()->ordered()->get();

        return view('blog.posts.edit', compact('post', 'categories', 'languages'));
    }

    public function update(Request $request, $id, SeoSyncService $seoSync)
    {
        $post = BlogPost::findOrFail($id);

        $validated = $request->validate(array_merge(
            $this->postRules($post->id),
            ['translations.*.remove_image' => 'nullable|boolean'],
            SeoSyncService::validationRules()
        ));
        $resolvedSlugs = $this->resolveTranslationSlugs($validated['translations'], $post->id);

        $post->update([
            'name' => $validated['name'],
            'slug' => $this->parentSlug($resolvedSlugs, $post->id),
            'is_active' => $request->boolean('is_active'),
            'publish_date' => $validated['publish_date'] ?? null,
        ]);

        foreach ($validated['translations'] as $locale => $translationData) {
            $post->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'slug' => $resolvedSlugs[$locale],
                    'title' => $translationData['title'],
                    'summary' => $translationData['summary'],
                    'content' => $translationData['content'],
                    'tags' => $this->parseTags($translationData['tags'] ?? null),
                ]
            );

            $this->syncTranslationImage($post, $request, $locale);
        }

        $post->categories()->sync($validated['category_ids'] ?? []);
        $seoSync->sync($post, $request);

        return redirect()->route('blog.posts.index')
            ->with('success', __('blog.post_updated'));
    }

    public function destroy($id): JsonResponse
    {
        $post = BlogPost::findOrFail($id);
        $post->clearAllMedia();
        $post->categories()->detach();
        $post->delete();

        return response()->json([
            'success' => true,
            'message' => __('blog.post_deleted'),
        ]);
    }

    public function toggleStatus($id): JsonResponse
    {
        $post = BlogPost::findOrFail($id);
        $post->update(['is_active' => !$post->is_active]);

        return response()->json([
            'success' => true,
            'message' => __('blog.status_updated'),
            'is_active' => $post->is_active,
        ]);
    }

    private function postRules(?int $ignorePostId = null): array
    {
        $slugRule = Rule::unique('blog_post_translations', 'slug');
        $parentSlugRule = Rule::unique('blog_posts', 'slug');
        if ($ignorePostId) {
            $slugRule->where(fn ($query) => $query->where('blog_post_id', '!=', $ignorePostId));
            $parentSlugRule->ignore($ignorePostId);
        }

        return [
            'name' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
            'publish_date' => 'nullable|date',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'integer|exists:blog_categories,id',
            'translations' => 'required|array',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.slug' => ['nullable', 'string', 'max:255', $slugRule, $parentSlugRule],
            'translations.*.summary' => 'required|string',
            'translations.*.content' => 'required|string',
            'translations.*.tags' => 'nullable|string',
            'translations.*.image' => 'nullable|image|mimes:jpeg,png,gif,webp|max:5120',
            'translations.*.image_alt' => 'nullable|string|max:255',
        ];
    }

    /**
     * @param  array<string, array<string, mixed>>  $translations
     * @return array<string, string>
     */
    private function resolveTranslationSlugs(array $translations, ?int $ignorePostId = null): array
    {
        $resolved = [];

        foreach ($translations as $locale => $translationData) {
            $slug = BlogPost::makeSlug((string) ($translationData['slug'] ?? ''));
            if ($slug === '') {
                $slug = BlogPost::makeSlug((string) ($translationData['title'] ?? ''));
            }

            $slug = BlogPost::uniqueSlug($slug, $ignorePostId);

            if (in_array($slug, $resolved, true)) {
                $slug = BlogPost::uniqueSlug($slug . '-' . $locale, $ignorePostId);
            }

            $resolved[$locale] = $slug;
        }

        return $resolved;
    }

    /**
     * @param  array<string, string>  $resolvedSlugs
     */
    private function parentSlug(array $resolvedSlugs, ?int $ignorePostId = null): string
    {
        $defaultLocale = CmsLanguage::getDefault()?->code;
        $slug = ($defaultLocale && isset($resolvedSlugs[$defaultLocale]))
            ? $resolvedSlugs[$defaultLocale]
            : (reset($resolvedSlugs) ?: 'post');

        return BlogPost::uniqueSlug($slug, $ignorePostId);
    }

    private function parseTags(?string $tags): array
    {
        if ($tags === null || trim($tags) === '') {
            return [];
        }

        return collect(explode(',', $tags))
            ->map(fn ($tag) => trim($tag))
            ->filter()
            ->values()
            ->all();
    }

    private function syncTranslationImage(BlogPost $post, Request $request, string $locale): void
    {
        $collection = BlogPost::imageCollection($locale);
        $isDefault = CmsLanguage::getDefault()?->code === $locale;
        $file = $request->file("translations.{$locale}.image");

        if ($file && $file->isValid()) {
            $post->clearMediaCollection($collection);
            if ($isDefault) {
                $post->clearMediaCollection('image');
            }
            CmsGalleryMedia::addFileWithAlt(
                $post,
                $file,
                $collection,
                $request->input("translations.{$locale}.image_alt")
            );
            return;
        }

        if ($request->boolean("translations.{$locale}.remove_image")) {
            $post->clearMediaCollection($collection);
            if ($isDefault) {
                $post->clearMediaCollection('image');
            }
            return;
        }

        if ($request->exists("translations.{$locale}.image_alt")) {
            $altCollection = $post->getFirstMedia($collection)
                ? $collection
                : ($isDefault ? 'image' : $collection);
            CmsGalleryMedia::persistCollectionAlt(
                $post,
                $altCollection,
                $request->input("translations.{$locale}.image_alt")
            );
        }
    }
}
