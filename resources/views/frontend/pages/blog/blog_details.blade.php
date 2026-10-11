@extends('frontend.layout.app')

@section('content')
@php
    $placeholderImage = asset('frontend/assets/img/blog/blog-grid-1.jpg');
    $title = $translation?->title ?? $post->name;
    $summary = $translation?->summary ?? '';
    $content = $translation?->content ?? '';
    $tags = is_array($translation?->tags ?? null) ? $translation->tags : [];
    $image = $post->getImageUrl('preview') ?: ($post->getImageUrl() ?: $placeholderImage);
    $date = optional($post->publish_date ?? $post->created_at)->translatedFormat('F d, Y');
    $categoryNames = $post->categories->map(function ($category) {
        return $category->getTranslatedAttribute('title') ?: $category->name;
    })->filter()->values();
    $shareUrl = urlencode(frontend_route('frontend.blog.show', $post->getRouteSlug()));
    $shareTitle = urlencode($title);
@endphp

<x-breadcrumb
    :title="$title"
    :items="[['label' => __('main.blogs'), 'url' => frontend_route('frontend.blog')]]"
    :current="\Illuminate\Support\Str::limit($title, 40)"
/>

<section class="th-blog-wrapper blog-details space-top space-extra-bottom">
    <div class="container">
        <div class="row">
            <div class="col-xxl-8 col-lg-7">
                <div class="th-blog blog-single">
                    <div class="blog-img global-img">
                        <img src="{{ $image }}" alt="{{ $title }}" decoding="async" fetchpriority="high" width="800" height="500">
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">
                            @if($date)
                            <a href="{{ frontend_route('frontend.blog') }}">
                                <i class="fa-solid fa-calendar-days"></i>{{ $date }}
                            </a>
                            @endif
                            @if($categoryNames->isNotEmpty())
                            <a href="{{ frontend_route('frontend.blog', ['category' => $post->categories->first()->slug]) }}">
                                <i class="fa-solid fa-tags"></i>{{ $categoryNames->implode(', ') }}
                            </a>
                            @endif
                        </div>

                        <h2 class="blog-title">{{ $title }}</h2>

                        @if($summary)
                        <p class="blog-desc text-body">{{ $summary }}</p>
                        @endif

                        <div class="blog-desc text-body">
                            {!! $content !!}
                        </div>

                        <div class="share-links clearfix">
                            <div class="row justify-content-between">
                                <div class="col-sm-auto">
                                    @if(count($tags))
                                    <span class="share-links-title">{{ __('blog.tags') }}:</span>
                                    <div class="tagcloud">
                                        @foreach($tags as $tag)
                                        <a href="{{ frontend_route('frontend.blog', ['q' => $tag]) }}">{{ $tag }}</a>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                <div class="col-sm-auto text-xl-end">
                                    <span class="share-links-title">{{ __('blog.share') }}:</span>
                                    <ul class="social-links">
                                        <li>
                                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener">
                                                <i class="fab fa-facebook-f"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" rel="noopener">
                                                <i class="fab fa-twitter"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" rel="noopener">
                                                <i class="fab fa-linkedin-in"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="related-blogs mt-50">
                    <h3 class="blog-inner-title h4 mb-30">{{ __('blog.related_posts') }}</h3>
                    @if(($relatedPosts ?? collect())->isNotEmpty())
                    <div class="row">
                        @include('frontend.pages.blog.partials.posts_grid', [
                            'posts' => $relatedPosts,
                            'showTags' => true,
                        ])
                    </div>
                    @else
                    <p class="mb-0">{{ __('blog.no_related_posts') }}</p>
                    @endif
                </div>
            </div>

            <div class="col-xxl-4 col-lg-5">
                @include('frontend.pages.blog.partials.sidebar')
            </div>
        </div>
    </div>
</section>
@endsection
