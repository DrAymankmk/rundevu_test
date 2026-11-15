@extends('frontend.layout.app')

@section('content')
<x-breadcrumb :title="__('main.blogs')" />

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
(function () {
	function initBlogLoadMore() {
		var btn = document.getElementById('blog-load-more');
		if (!btn) {
			return;
		}

		var defaultLabel = btn.innerHTML;
		var page = parseInt(btn.getAttribute('data-page'), 10) || 2;

		btn.addEventListener('click', function () {
			if (btn.disabled) {
				return;
			}

			var url = btn.getAttribute('data-url');
			if (!url) {
				return;
			}

			var params = new URLSearchParams({
				page: String(page),
				q: btn.getAttribute('data-q') || '',
				category: btn.getAttribute('data-category') || ''
			});

			btn.disabled = true;
			btn.textContent = btn.getAttribute('data-loading-text') || 'Loading...';

			fetch(url + '?' + params.toString(), {
				headers: {
					'Accept': 'application/json',
					'X-Requested-With': 'XMLHttpRequest'
				}
			})
				.then(function (res) {
					if (!res.ok) {
						throw new Error('Load more failed');
					}
					return res.json();
				})
				.then(function (response) {
					var grid = document.getElementById('blog-posts-grid');
					if (response.html && grid) {
						grid.insertAdjacentHTML('beforeend', response.html);
					}

					if (response.has_more) {
						page = response.next_page || (page + 1);
						btn.setAttribute('data-page', String(page));
						btn.disabled = false;
						btn.innerHTML = defaultLabel;
					} else {
						var wrap = document.getElementById('blog-load-more-wrap');
						if (wrap) {
							wrap.remove();
						}
					}
				})
				.catch(function () {
					btn.disabled = false;
					btn.innerHTML = defaultLabel;
				});
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initBlogLoadMore);
	} else {
		initBlogLoadMore();
	}
})();
</script>
@endpush
