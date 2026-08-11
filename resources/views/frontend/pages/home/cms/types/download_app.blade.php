@php
$st = $section->translation(app()->getLocale()) ?? $section->translation(config('app.fallback_locale', 'en'));
$fb = config('app.fallback_locale', 'en');
$locale = app()->getLocale();
$items = $section->relationLoaded('items') ? $section->items->where('is_active', true)->sortBy('order')->values() :
collect();
$phoneImg = $section->getMediaUrl('images', $locale, asset('frontend/assets/img/normal/download-1-1.png'), true);
$apple = $items->first(fn ($i) => str_contains(strtolower($i->slug ?? ''), 'apple')) ?? $items->get(0);
$google = $items->first(fn ($i) => str_contains(strtolower($i->slug ?? ''), 'google') ||
str_contains(strtolower($i->slug ?? ''), 'play')) ?? $items->get(1);
$appleIt = $apple ? ($apple->translation($locale) ?? $apple->translation($fb)) : null;
$googleIt = $google ? ($google->translation($locale) ?? $google->translation($fb)) : null;
$appleUrl = $appleIt?->content ? strip_tags($appleIt->content) :
'https://apps.apple.com/us/app/randevu-%D8%B1%D8%A7%D9%86%D8%AF%D9%8A%D9%81%D9%88/id6761128352';
$googleUrl = $googleIt?->content ? strip_tags($googleIt->content) :
'https://play.google.com/store/apps/details?id=com.takaful.rendezvous';
@endphp
<section class="download-area space overflow-hidden"
	data-bg-src="{{ asset('frontend/assets/img/bg/download-bg-1.png') }}" id="section-{{ $section->id }}">
	<div class="container">
		<div class="row gy-5 align-items-center">
			<div class="col-xl-6">
				<div class="download-img">
					<div class="img1">
						<img src="{{ $phoneImg }}" alt="">
					</div>
				</div>
			</div>
			<div class="col-xl-6">
				<div class="ps-xl-4">
					<div class="title-area mb-30 pe-xl-5 me-xl-5">
						<span
							class="sub-title">{{ $st?->subtitle ?: __('download app') }}</span>
						<h2 class="sec-title">{!! $st?->title ?: __('Medova <span
								class="fw-normal">Medical Apps</span>
							that make Personal Health Easier') !!}</h2>
						<p class="fs-18 pe-xl-5 wow fadeInUp">{!! $st?->description
							?: e(__("If you're looking to develop a healthcare
							app, you'll first need to determine the type of
							app that will serve your purpose. Consider what
							features are the most important for your specific
							patients.")) !!}</p>
					</div>
					<div class="btn-group download-btn mt-50 justify-content-center justify-content-xl-start wow fadeInUp"
						data-wow-delay=".2s">
						<a href="{{ $appleUrl }}" target="_blank"
							rel="noopener noreferrer"><img
								src="{{ asset('frontend/assets/img/icon/apple.svg') }}"
								alt="App Store"></a>
						<a href="{{ $googleUrl }}" target="_blank"
							rel="noopener noreferrer"><img
								src="{{ asset('frontend/assets/img/icon/google-play.svg') }}"
								alt="Google Play"></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>