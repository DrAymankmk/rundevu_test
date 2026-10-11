@php
    $placeholderImage = asset('frontend/assets/img/blog/blog-grid-1.jpg');
    $showTags = $showTags ?? false;
@endphp
@foreach($posts as $post)
@php
    $title = $post->getTranslatedAttribute('title') ?: $post->name;
    $summary = $post->getTranslatedAttribute('summary');
    $image = $post->getImageUrl('preview') ?: ($post->getImageUrl() ?: $placeholderImage);
    $date = optional($post->publish_date ?? $post->created_at)->translatedFormat('F d, Y');
    $categoryNames = $post->categories->map(function ($category) {
        return $category->getTranslatedAttribute('title') ?: $category->name;
    })->filter()->values();
    $postTags = $showTags
        ? collect($post->getTranslatedAttribute('tags') ?? [])
            ->map(fn ($tag) => trim((string) $tag))
            ->filter()
            ->values()
        : collect();
@endphp
<div class="col-xl-6">
    <div class="th-blog blog-single has-post-thumbnail single-grid">
        <div class="blog-img global-img">
            <a href="{{ frontend_route('frontend.blog.show', $post->getRouteSlug()) }}">
                <img src="{{ $image }}" alt="{{ $title }}" loading="lazy" decoding="async">
            </a>
        </div>
        <div class="blog-content">
            <div class="blog-meta">
                @if($date)
                <a href="{{ frontend_route('frontend.blog.show', $post->getRouteSlug()) }}">
                    <i class="fa-solid fa-calendar-days"></i>{{ $date }}
                </a>
                @endif
                @if($categoryNames->isNotEmpty())
                <a href="{{ frontend_route('frontend.blog', ['category' => $post->categories->first()->slug]) }}">
                    <i class="fa-solid fa-folder"></i>{{ $categoryNames->first() }}
                </a>
                @endif
            </div>
            <h2 class="box-title">
                <a href="{{ frontend_route('frontend.blog.show', $post->getRouteSlug()) }}">{{ $title }}</a>
            </h2>
            @if($summary)
            <p class="blog-text">
                {{ \Illuminate\Support\Str::limit(strip_tags($summary), 120) }}
            </p>
            @endif
            @if($postTags->isNotEmpty())
            <div class="blog-meta mb-2">
                @foreach($postTags as $tag)
                <a href="{{ frontend_route('frontend.blog', ['q' => $tag]) }}">
                    <i class="fa-solid fa-tags"></i>{{ $tag }}
                </a>
                @endforeach
            </div>
            @endif
            <a href="{{ frontend_route('frontend.blog.show', $post->getRouteSlug()) }}" class="line-btn">
                {{ __('blog.read_more') }}
                <x-arrow-icon />
            </a>
        </div>
    </div>
</div>
@endforeach
