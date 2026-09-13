<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Services\Seo\SeoResolver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    private const PER_PAGE = 4;

    public function __construct(private SeoResolver $seoResolver)
    {
    }

    public function index(Request $request)
    {
        $locale = app()->getLocale();
        $search = trim((string) $request->get('q', ''));
        $categorySlug = trim((string) $request->get('category', ''));

        $posts = $this->filteredPostsQuery($search, $categorySlug)
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        $recentPosts = BlogPost::query()
            ->published()
            ->with(['translations', 'media'])
            ->orderByDesc('publish_date')
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        $categories = BlogCategory::query()
            ->active()
            ->with('translations')
            ->withCount(['posts' => function ($q) {
                $q->published();
            }])
            ->orderBy('name')
            ->get();

        $seo = $this->seoResolver->resolve(null, $locale, [
            'title' => __('main.blogs'),
            'description' => __('blog.frontend_list_description'),
            'canonical' => frontend_route('frontend.blog'),
        ]);

        return view('frontend.pages.blog.index', compact(
            'posts',
            'recentPosts',
            'categories',
            'search',
            'categorySlug',
            'seo'
        ));
    }

    public function loadMore(Request $request): JsonResponse
    {
        $search = trim((string) $request->get('q', ''));
        $categorySlug = trim((string) $request->get('category', ''));
        $page = max(2, (int) $request->get('page', 2));

        $posts = $this->filteredPostsQuery($search, $categorySlug)
            ->paginate(self::PER_PAGE, ['*'], 'page', $page);

        $html = view('frontend.pages.blog.partials.posts_grid', [
            'posts' => $posts,
        ])->render();

        return response()->json([
            'html' => $html,
            'has_more' => $posts->hasMorePages(),
            'next_page' => $posts->currentPage() + 1,
        ]);
    }

    public function show(string $slug)
    {
        $locale = app()->getLocale();

        $post = BlogPost::query()
            ->published()
            ->whereSlug($slug)
            ->with(['translations', 'categories.translations', 'media', 'seoMeta.translations'])
            ->firstOrFail();

        $canonicalSlug = $post->getRouteSlug();

        if ($canonicalSlug !== '' && $canonicalSlug !== $slug) {
            return redirect()->to(frontend_route('frontend.blog.show', $canonicalSlug), 301);
        }

        $translation = $post->translation($locale) ?? $post->translations->first();
        $title = $translation?->title ?? $post->name;
        $summary = $translation?->summary ?? '';
        $image = $post->getImageUrl('preview', $locale) ?: $post->getImageUrl('', $locale);

        $relatedPosts = $this->relatedPosts($post, $translation, 4);

        $recentPosts = BlogPost::query()
            ->published()
            ->where('id', '!=', $post->id)
            ->with(['translations', 'media'])
            ->orderByDesc('publish_date')
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        $categories = BlogCategory::query()
            ->active()
            ->with('translations')
            ->withCount(['posts' => function ($q) {
                $q->published();
            }])
            ->orderBy('name')
            ->get();

        $seo = $this->seoResolver->resolve($post, $locale, [
            'title' => $title,
            'description' => $summary,
            'image' => $image,
            'canonical' => frontend_route('frontend.blog.show', $canonicalSlug !== '' ? $canonicalSlug : $slug),
        ]);

        return view('frontend.pages.blog.blog_details', compact(
            'post',
            'translation',
            'relatedPosts',
            'recentPosts',
            'categories',
            'seo'
        ));
    }

    private function relatedPosts(BlogPost $post, $translation, int $limit = 4)
    {
        $categoryIds = $post->categories->pluck('id')->filter()->values()->all();
        $tags = collect($translation?->tags ?? [])
            ->merge(
                $post->translations->flatMap(fn ($row) => is_array($row->tags) ? $row->tags : [])
            )
            ->map(fn ($tag) => trim((string) $tag))
            ->filter()
            ->unique()
            ->values()
            ->all();

        if ($categoryIds === [] && $tags === []) {
            return collect();
        }

        return BlogPost::query()
            ->published()
            ->where('id', '!=', $post->id)
            ->with(['translations', 'categories.translations', 'media'])
            ->where(function ($query) use ($categoryIds, $tags) {
                if ($categoryIds !== []) {
                    $query->whereHas('categories', function ($q) use ($categoryIds) {
                        $q->whereIn('blog_categories.id', $categoryIds);
                    });
                }

                if ($tags !== []) {
                    $method = $categoryIds !== [] ? 'orWhereHas' : 'whereHas';
                    $query->{$method}('translations', function ($q) use ($tags) {
                        $q->where(function ($tagQuery) use ($tags) {
                            foreach ($tags as $tag) {
                                $tagQuery->orWhereJsonContains('tags', $tag);
                            }
                        });
                    });
                }
            })
            ->orderByDesc('publish_date')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    private function filteredPostsQuery(string $search, string $categorySlug): Builder
    {
        $query = BlogPost::query()
            ->published()
            ->with(['translations', 'categories.translations', 'media'])
            ->orderByDesc('publish_date')
            ->orderByDesc('id');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhereHas('translations', function ($q2) use ($search) {
                        $q2->where('title', 'like', "%{$search}%")
                            ->orWhere('slug', 'like', "%{$search}%")
                            ->orWhere('summary', 'like', "%{$search}%")
                            ->orWhere('content', 'like', "%{$search}%");
                    });
            });
        }

        if ($categorySlug !== '') {
            $query->whereHas('categories', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug)->where('is_active', true);
            });
        }

        return $query;
    }
}
