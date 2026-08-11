@php
$fb = config('app.fallback_locale', 'en');
$locale = app()->getLocale();
$st = $section->translation($locale) ?? $section->translation($fb);
$secTitle = $st?->title ?: __('Services');
$secSubtitle = $st?->subtitle ?: __('Our Services');
$secDescription = $st?->description ?: __('Services description');
$items = $section->relationLoaded('items') ? $section->items->where('is_active', true)->sortBy('order')->values() :
collect();
$defaultIcons = ['service_1_1.svg', 'service_1_2.svg', 'service_1_3.svg'];

$serviceButtons = collect();
if ($section->relationLoaded('links')) {
foreach ($section->links->where('is_active', true)->filter(static fn ($l) => filled(trim((string) ($l->link ??
''))))->sortBy('order')->values() as $idx => $link) {
$href = $resolveHref($link->link);
$tr = null;
if ($link->relationLoaded('translations')) {
$tr = $link->translations->firstWhere('locale', $locale)
?? $link->translations->firstWhere('locale', $fb)
?? $link->translations->first();
}
$label = $tr?->name ?? $link->name ?? __('Link');
$target = in_array($link->target, ['_blank', '_self'], true) ? $link->target : '_self';
$rel = $target === '_blank' ? 'noopener noreferrer' : null;
$icon = trim((string) ($link->icon ?? ''));
$type = strtolower((string) ($link->type ?? ''));
if (in_array($type, ['secondary', 'outline', 'border'], true)) {
$btnClass = 'th-btn th-border';
} elseif (in_array($type, ['primary', 'solid', 'main'], true)) {
$btnClass = 'th-btn style2';
} else {
$btnClass = $idx === 0 ? 'th-btn style2' : 'th-btn th-border';
}
$serviceButtons->push(compact('href', 'label', 'target', 'rel', 'icon', 'btnClass'));
}
}

@endphp

<section class="position-relative overflow-hidden space overflow-hidden" id="service-sec">
	<div class="container">
		<div class="row gy-4 mb-4 justify-content-center">
			<div class="col-lg-8 text-center">
				<span class="sub-title">{{ $secSubtitle }}</span>
				<h2 class="sec-title">{!! $secTitle !!}</h2>
				<div class="fs-18 mt-2">{!! $secDescription !!}</div>
			</div>
		</div>
		<div class="row gy-4">
			@forelse($items as $idx => $item)
			@php
			$it = $item->translation($locale) ?? $item->translation($fb);
			$itemTitle = $it?->title ?: __('Service');
			$itemDesc = $it?->content ?: __('Service description');
			$defaultIcon = asset('frontend/assets/img/icon/' . ($defaultIcons[$idx % 3] ??
			'service_1_1.svg'));
			$icon = $item->getMediaUrl('icons', $locale, $defaultIcon, true);
			$itemImg = $item->getMediaUrl('images', $locale, null, true);
			if (! filled($itemImg)) {
			$g = $item->getMedia('gallery')->first();
			$itemImg = $g ? $g->getUrl() : null;
			}
			$hasItemImage = filled($itemImg);
			@endphp
			<div class="col-md-6 col-lg-4 col-xxl-3">
				<div class="service-card style2">
					<div class="box-content">
						@if($hasItemImage)
						<div class="service-card-thumb global-img mb-3">
							<img src="{{ $itemImg }}"
								alt="{{ strip_tags($itemTitle) }}"
								class="w-100 rounded"
								style="max-height: 200px; object-fit: cover;">
						</div>
						@endif
						<div class="box-icon">
							<img src="{{ $icon }}" alt="">
						</div>
						<h3 class="box-title"><a
								href="{{ url('/contact') }}">{{ $itemTitle }}</a>
						</h3>
						<p class="box-text">{!! $itemDesc !!}</p>
						@if($serviceButtons->isNotEmpty())
						<a href="{{ $serviceButtons->first()?->href ?? '#' }}"
							class="th-btn black-border">{{ __('Read More') }}
							<i
								class="fa-light fa-arrow-right-long ms-2"></i></a>
						@endif
					</div>
				</div>
			</div>
			@empty
			<div class="col-12 text-center text-muted">{{ __('No services added yet.') }}</div>
			@endforelse
		</div>
	</div>
</section>
