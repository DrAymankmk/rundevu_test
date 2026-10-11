@php
$st = $section->translation(app()->getLocale()) ?? $section->translation(config('app.fallback_locale', 'en'));
$fb = config('app.fallback_locale', 'en');
$locale = app()->getLocale();
$items = $section->relationLoaded('items') ? $section->items->where('is_active', true)->sortBy('order')->values() :
collect();
$galleryImage = $section->getGalleryMedia($locale)->first(
	fn ($media) => \App\Support\Cms\CmsGalleryMedia::isImage($media)
);
$phoneImg = $galleryImage
	? (\App\Support\Cms\CmsGalleryMedia::accessibleUrl($galleryImage) ?? $galleryImage->getUrl())
	: $section->getMediaUrl('images', $locale, asset('frontend/assets/img/normal/download-1-1.png'), true);
$phoneAlt = $galleryImage
	? \App\Support\Cms\CmsGalleryMedia::alt($galleryImage)
	: $section->getMediaAlt('images', $locale, true);
$apple = $items->first(fn ($i) => str_contains(strtolower($i->slug ?? ''), 'apple')) ?? $items->get(0);
$google = $items->first(fn ($i) => str_contains(strtolower($i->slug ?? ''), 'google') ||
str_contains(strtolower($i->slug ?? ''), 'play')) ?? $items->get(1);
$appleIt = $apple ? ($apple->translation($locale) ?? $apple->translation($fb)) : null;
$googleIt = $google ? ($google->translation($locale) ?? $google->translation($fb)) : null;
$cmsAppleUrl = $appleIt?->content ? strip_tags($appleIt->content) : '';
$cmsGoogleUrl = $googleIt?->content ? strip_tags($googleIt->content) : '';
$appleUrl = website_store_url('apple', $cmsAppleUrl);
$googleUrl = website_store_url('google_play', $cmsGoogleUrl);
@endphp
<section class="download-area space overflow-hidden background-image" style="background-image: url('{{ asset('frontend/assets/img/download_bg.jpeg') }}');"
	id="section-{{ $section->id }}">
	<div class="container">
		<div class="row gy-5 align-items-center">
			<div class="col-xl-6">
				<div class="download-img">
					<div class="img1">
						<img src="{{ $phoneImg }}" alt="{{ $phoneAlt }}" loading="lazy" decoding="async">
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
					@if($appleUrl !== '' || $googleUrl !== '')
					<div class="btn-group download-btn mt-50 justify-content-center justify-content-xl-start wow fadeInUp"
						data-wow-delay=".2s">
						@if($appleUrl !== '')
						<a href="{{ $appleUrl }}" target="_blank"
							rel="noopener noreferrer"><img
								src="{{ asset('frontend/assets/img/icon/apple.svg') }}"
								width="168" height="50" loading="lazy" decoding="async"
								alt="{{ __('main.app_store') }}"></a>
						@endif
						@if($googleUrl !== '')
						<a href="{{ $googleUrl }}" target="_blank"
							rel="noopener noreferrer"><img
								src="{{ asset('frontend/assets/img/icon/google-play.svg') }}"
								width="168" height="50" loading="lazy" decoding="async"
								alt="{{ __('main.google_play') }}"></a>
						@endif
					</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</section>