@extends('frontend.layout.app')

@section('content')
<div class="breadcumb-wrapper" data-bg-src="{{ asset('frontend/assets/img/bg/breadcumb-bg.jpg') }}">
	<div class="container">
		<div class="breadcumb-content">
			<h1 class="breadcumb-title">{{ __('main.blogs') }}</h1>
			<ul class="breadcumb-menu">
				<li><a href="{{ frontend_route('frontend.home') }}">{{ __('main.home') }}</a></li>
				<li>{{ __('main.blogs') }}</li>
			</ul>
		</div>
	</div>
</div>

<section class="th-blog-wrapper space-top space-extra-bottom">
	<div class="container">
		<div class="row">
			<div class="col-xxl-8 col-lg-7">
				<div class="row" id="blog-posts-grid">
					@if($posts->isNotEmpty())
						@include('frontend.pages.blog.partials.posts_grid', ['posts' => $posts])
					@else
					<div class="col-12" id="blog-empty-message">
						<div class="alert alert-info mb-0">{{ __('blog.no_posts') }}</div>
					</div>
					@endif
				</div>

				@if($posts->isNotEmpty() && $posts->hasMorePages())
				<div class="text-center mt-40" id="blog-load-more-wrap">
					<button type="button" class="th-btn" id="blog-load-more"
						data-url="{{ frontend_route('frontend.blog.load-more') }}"
						data-page="2"
						data-q="{{ $search }}"
						data-category="{{ $categorySlug }}"
						data-loading-text="{{ __('blog.loading') }}">
						{{ __('blog.load_more') }}
						<x-arrow-icon />
					</button>
				</div>
				@endif
			</div>

			<div class="col-xxl-4 col-lg-5">
				@include('frontend.pages.blog.partials.sidebar')
			</div>
		</div>
	</div>
</section>
@endsection

@push('scripts')
<script>
(function ($) {
	var $btn = $('#blog-load-more');
	if (!$btn.length) {
		return;
	}

	var defaultLabel = $btn.html();

	$btn.on('click', function () {
		var $el = $(this);
		if ($el.prop('disabled')) {
			return;
		}

		$el.prop('disabled', true).text($el.data('loading-text'));

		$.ajax({
			url: $el.data('url'),
			type: 'GET',
			data: {
				page: $el.data('page'),
				q: $el.data('q') || '',
				category: $el.data('category') || ''
			},
			success: function (response) {
				if (response.html) {
					$('#blog-posts-grid').append(response.html);
				}

				if (response.has_more) {
					$el.data('page', response.next_page);
					$el.prop('disabled', false).html(defaultLabel);
				} else {
					$('#blog-load-more-wrap').remove();
				}
			},
			error: function () {
				$el.prop('disabled', false).html(defaultLabel);
			}
		});
	});
})(jQuery);
</script>
@endpush
