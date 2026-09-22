@extends('frontend.layout.app')

@section('content')
<style>
.social-page-hero {
	background: linear-gradient(135deg, rgba(62, 102, 243, 0.08) 0%, rgba(255, 255, 255, 0) 55%);
}

.social-page-intro {
	max-width: 720px;
	margin-inline: auto;
}

.social-platform-grid {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
	gap: 1.5rem;
}

.social-platform-grid + .social-platform-grid {
	margin-top: 1.5rem;
}

.social-platform-card {
	position: relative;
	display: flex;
	flex-direction: column;
	align-items: center;
	text-align: center;
	padding: 2.25rem 1.5rem 1.75rem;
	border-radius: 1.25rem;
	background: #fff;
	border: 1px solid rgba(15, 23, 42, 0.08);
	box-shadow: 0 12px 40px rgba(15, 23, 42, 0.06);
	transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
	height: 100%;
	overflow: hidden;
}

.social-platform-card::before {
	content: '';
	position: absolute;
	inset: 0 auto auto 0;
	width: 100%;
	height: 4px;
	background: var(--platform-color, var(--theme-color, #3E66F3));
}

.social-platform-card:hover {
	transform: translateY(-6px);
	box-shadow: 0 20px 48px rgba(15, 23, 42, 0.12);
	border-color: rgba(62, 102, 243, 0.18);
}

.social-platform-icon {
	width: 96px;
	height: 96px;
	border-radius: 50%;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	margin-bottom: 1.25rem;
	background: var(--platform-color, var(--theme-color, #3E66F3));
	color: #fff;
	font-size: 2.5rem;
	box-shadow: 0 14px 30px rgba(15, 23, 42, 0.14);
}

.social-platform-card.is-light-icon .social-platform-icon {
	color: #111827;
}

.social-platform-name {
	font-size: 1.35rem;
	font-weight: 700;
	margin-bottom: 0.5rem;
	color: var(--title-color, #0f172a);
}

.social-platform-text {
	color: var(--body-color, #64748b);
	margin-bottom: 1.5rem;
	min-height: 3rem;
}

.social-platform-btn {
	margin-top: auto;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 0.5rem;
	padding: 0.8rem 1.4rem;
	border-radius: 999px;
	font-weight: 600;
	text-decoration: none;
	color: #fff;
	background: var(--platform-color, var(--theme-color, #3E66F3));
	transition: opacity 0.2s ease, transform 0.2s ease;
}

.social-platform-btn:hover {
	color: #fff;
	opacity: 0.92;
	transform: translateY(-1px);
}

.social-page-cta {
	border-radius: 1.5rem;
	padding: 2.5rem 2rem;
	background: linear-gradient(135deg, var(--theme-color, #3E66F3) 0%, #684DF4 100%);
	color: #fff;
	text-align: center;
}

.social-page-cta .th-btn {
	background: #fff;
	color: var(--theme-color, #3E66F3);
	border-color: #fff;
}

.social-page-cta .th-btn:hover {
	background: rgba(255, 255, 255, 0.92);
	color: var(--theme-color, #3E66F3);
}

@media (max-width: 575.98px) {
	.social-platform-icon {
		width: 84px;
		height: 84px;
		font-size: 2.1rem;
	}
}
</style>

<x-breadcrumb :title="__('main.social_media')" />

<section class="space social-page-hero">
	<div class="container">
		<div class="social-page-intro text-center mb-50">
			<span class="sub-title">{{ __('main.follow_us') }}</span>
			<h2 class="sec-title mb-20">{{ __('main.social_media_page_title') }}</h2>
			<p class="mb-0">{{ __('main.social_media_page_subtitle') }}</p>
		</div>

		@if($storePlatforms->isNotEmpty())
		<div class="social-platform-grid">
			@foreach($storePlatforms as $platform)
			@php
			$platformKey = $platform['key'] ?? 'store';
			$brandColor = $platform['brand_color'] ?? '#3E66F3';
			$platformTitle = $platform['title'] ?? __('main.download_app');
			$isLightIcon = $platform['is_light_icon'] ?? false;
			$ctaLabel = __('main.download_now');
			@endphp
			<article class="social-platform-card{{ $isLightIcon ? ' is-light-icon' : '' }}"
				style="--platform-color: {{ $brandColor }};">
				<div class="social-platform-icon" aria-hidden="true">
					<i class="{{ $platform['icon'] ?? 'fas fa-mobile-screen' }}"></i>
				</div>
				<h3 class="social-platform-name">
					{{ $platformTitle }}
				</h3>
				<p class="social-platform-text">
					{{ $platform['description'] ?? '' }}
				</p>
				<a href="{{ $platform['url'] }}" class="social-platform-btn" target="_blank"
					rel="noopener noreferrer"
					aria-label="{{ $ctaLabel }} - {{ $platformTitle }}">
					<span>{{ $ctaLabel }}</span>
					<i class="fa-regular fa-arrow-up-right-from-square"></i>
				</a>
			</article>
			@endforeach
		</div>
		@endif

		@if($platforms->isEmpty())
		@if($storePlatforms->isEmpty())
		<div class="alert alert-info text-center mb-0" role="alert">
			{{ __('main.social_media_coming_soon') }}
		</div>
		@endif
		@else
		<div class="social-platform-grid">
			@foreach($platforms as $platform)
			@php
			$platformKey = $platform['key'] ?? 'social';
			$brandColor = $platform['brand_color'] ?? '#3E66F3';
			$platformTitle = $platform['title'] ?? __('main.social_platform_'.$platformKey);
			$isLightIcon = $platform['is_light_icon'] ?? in_array($platformKey, ['snapchat'], true);
			@endphp
			<article class="social-platform-card{{ $isLightIcon ? ' is-light-icon' : '' }}"
				style="--platform-color: {{ $brandColor }};">
				<div class="social-platform-icon" aria-hidden="true">
					<i class="{{ $platform['icon'] ?? 'fas fa-share-alt' }}"></i>
				</div>
				<h3 class="social-platform-name">
					{{ $platformTitle }}
				</h3>
				<p class="social-platform-text">
					{{ $platform['description'] ?? __('main.social_platform_'.$platformKey.'_desc') }}
				</p>
				<a href="{{ $platform['url'] }}" class="social-platform-btn" target="_blank"
					rel="noopener noreferrer"
					aria-label="{{ __('main.visit_social_profile', ['platform' => $platformTitle]) }}">
					<span>{{ __('main.visit_profile') }}</span>
					<i class="fa-regular fa-arrow-up-right-from-square"></i>
				</a>
			</article>
			@endforeach
		</div>
		@endif
	</div>
</section>
@endsection
