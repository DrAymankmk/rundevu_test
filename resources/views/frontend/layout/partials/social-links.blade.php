@php
	$socialPlatforms = collect(config('social.platforms', []))
		->filter(static fn ($platform) => filled($platform['url'] ?? null))
		->values();
@endphp

@if($socialPlatforms->isNotEmpty())
<div class="social-links">
	<span class="title">{{ __('main.social_media') }}</span>
	@foreach($socialPlatforms as $platform)
	<a href="{{ $platform['url'] }}"
		target="_blank"
		rel="noopener noreferrer"
		aria-label="{{ __('main.visit_social_profile', ['platform' => __('main.social_platform_'.($platform['key'] ?? 'social'))]) }}">
		<i class="{{ $platform['icon'] ?? 'fas fa-share-alt' }}"></i>
	</a>
	@endforeach
</div>
@endif
