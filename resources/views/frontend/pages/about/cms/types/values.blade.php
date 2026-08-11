@php
$fb = config('app.fallback_locale', 'en');
$locale = app()->getLocale();
$st = $section->translation($locale) ?? $section->translation($fb);
$title = $st?->title ?: __('Values');
$desc = $st?->description ?: '';
$items = $section->relationLoaded('items') ? $section->items->where('is_active', true)->sortBy('order')->values() :
collect();
@endphp

<section class="project-area position-relative space overflow-hidden" id="project-sec">
	<div class="container">
		<div class="row justify-content-lg-between justify-content-center align-items-center">
			<div class="col-lg-5">
				<div class="title-area">
					<span class="sub-title">
						{{ $st?->subtitle ?: __('medova values') }}</span>
					<h2 class="sec-title">{{ $st?->title?: __('Our Core') }}
					</h2>
				</div>
			</div>
			<div class="col-lg-5">
				<div class="ps-xl-5 ms-xl-4">
					<p class="fs-18 wow fadeInUp">{!! $st?->description ?: __('Values
						description') !!}</p>
				</div>
			</div>
		</div> <!-- / Title row -->
		<div class="slider-area">
			<div class="swiper th-slider projectSlide has-shadow" id="projectSlide"
				data-slider-options='{"loop":true,"spaceBetween":56,"breakpoints":{"0":{"slidesPerView":1},"576":{"slidesPerView":"1"},"768":{"slidesPerView":"2"},"992":{"slidesPerView":"2"},"1200":{"slidesPerView":"3"}}}'>
				<div class="swiper-wrapper">

					@forelse ($items as $item)
					@php
					$it = $item->translation($locale) ?? $item->translation($fb);
					$title = $it?->title ?: __('Value');
					$content = $it?->content ?: __('Value description');
					@endphp
					<div class="swiper-slide">
						<div class="project-card">
							<div class="box-img global-img">
								<img src="{{ $item->getMediaUrl('images', $locale, null, true) }}"
									alt="{{ $item->title }}">
							</div>
							<div class="box-content">
								<h3 class="box-title"><a
										href="case-studies-details.html">{{ $title }}</a>
								</h3>
								<p class="box-text">{!! $content !!}</p>
							</div>
						</div>
					</div>
					@empty
					<div class="swiper-slide">
						<div class="project-card">
							<div class="box-img global-img">
								<img src="{{ asset('frontend/assets/img/project/project_1_1.jpg') }}"
									alt="project image">
							</div>
						</div>
					</div>
					@endforelse


				</div>
			</div>
			<button data-slider-prev="#projectSlide" class="slider-arrow slider-prev"><i
					class="far fa-arrow-left"></i></button>
			<button data-slider-next="#projectSlide" class="slider-arrow slider-next"><i
					class="far fa-arrow-right"></i></button>
		</div>
	</div>
</section>
