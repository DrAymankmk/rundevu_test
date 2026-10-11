@php
$placeholderImage = asset('frontend/assets/img/blog/blog-grid-1.jpg');
$search = $search ?? request('q', '');
$categorySlug = $categorySlug ?? request('category', '');
@endphp
<aside class="sidebar-area style2">
	<div class="widget widget_search">
		<h3 class="widget_title">{{ __('blog.search') }}</h3>
		<form class="search-form" action="{{ frontend_route('frontend.blog') }}" method="GET">
			@if($categorySlug)
			<input type="hidden" name="category" value="{{ $categorySlug }}">
			@endif
			<input type="text" name="q" value="{{ $search }}" style="padding-inline-start: 60px;"
				placeholder="{{ __('blog.search_placeholder') }}">
			<button type="submit"><i class="far fa-search"></i></button>
		</form>
	</div>

	@if(isset($categories) && $categories->isNotEmpty())
	<div class="widget widget_categories">
		<h3 class="widget_title">{{ __('blog.categories') }}</h3>
		<ul>
			<li>
				<a href="{{ frontend_route('frontend.blog', array_filter(['q' => $search ?: null])) }}"
					class="{{ $categorySlug === '' ? 'active' : '' }}">
					{{ __('blog.all_categories') }}
				</a>
			</li>
			@foreach($categories as $category)
			@php
			$categoryTitle = $category->getTranslatedAttribute('title') ?: $category->name;
			@endphp
			<li>
				<a href="{{ frontend_route('frontend.blog', array_filter(['category' => $category->slug, 'q' => $search ?: null])) }}"
					class="{{ $categorySlug === $category->slug ? 'active' : '' }}">
					{{ $categoryTitle }}
					<span>({{ $category->posts_count }})</span>
				</a>
			</li>
			@endforeach
		</ul>
	</div>
	@endif

	<div class="widget">
		<h3 class="widget_title">{{ __('blog.recent_posts') }}</h3>
		<div class="recent-post-wrap">
			@forelse(($recentPosts ?? collect()) as $recent)
			@php
			$recentTitle = $recent->getTranslatedAttribute('title') ?: $recent->name;
			$recentImage = $recent->getImageUrl('thumb') ?: $placeholderImage;
			$recentDate = optional($recent->publish_date ?? $recent->created_at)->translatedFormat('F d, Y');
			@endphp
			<div class="recent-post">
				<div class="media-img">
					<a href="{{ frontend_route('frontend.blog.show', $recent->getRouteSlug()) }}">
						<img src="{{ $recentImage }}" alt="{{ $recentTitle }}" decoding="async" loading="lazy">
					</a>
				</div>
				<div class="media-body">
					<div class="recent-post-meta">
						<a href="{{ frontend_route('frontend.blog.show', $recent->getRouteSlug()) }}">
							<i class="fa-sharp fa-solid fa-calendar-days"></i>{{ $recentDate }}
						</a>
					</div>
					<h4 class="post-title">
						<a class="text-inherit"
							href="{{ frontend_route('frontend.blog.show', $recent->getRouteSlug()) }}">{{ $recentTitle }}</a>
					</h4>
				</div>
			</div>
			@empty
			<p class="mb-0">{{ __('blog.no_posts') }}</p>
			@endforelse
		</div>
	</div>
</aside>
