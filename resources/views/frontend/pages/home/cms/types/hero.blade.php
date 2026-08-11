@php
$fb = config('app.fallback_locale', 'en');
$locale = app()->getLocale();
$st = $section->translation($locale) ?? $section->translation($fb);
$items = $section->relationLoaded('items') ? $section->items->where('is_active', true)->sortBy('order')->values() :
collect();
$defaultBg = asset('frontend/assets/img/hero/hero_bg_1_1.jpg');
$bg = $section->getMediaUrl('images', $locale, $defaultBg, true);
$defaultSlides = [
['title' => __('Your <span class="text-theme">Health</span>, Our Priority, Care for Every Patient'), 'img' =>
asset('frontend/assets/img/hero/hero-image-1.png')],
['title' => __('In Trusted Hands Guiding Your <span class="text-theme">Health</span> Journey'), 'img' =>
asset('frontend/assets/img/hero/hero-image-2.png')],
['title' => __('Caring for Every <span class="text-theme">Patient</span>, Every Step of the Way'), 'img' =>
asset('frontend/assets/img/hero/hero-image-3.png')],
];

$resolveHref = static function (?string $raw): string {
$raw = trim((string) $raw);
if ($raw === '') {
return '#';
}
if (preg_match('#^(https?:)?//#i', $raw) || str_starts_with($raw, 'mailto:') || str_starts_with($raw, 'tel:')) {
return $raw;
}

return str_starts_with($raw, '/') ? url($raw) : url('/' . ltrim($raw, '/'));
};

$mapModelLinksToButtons = static function ($model) use ($locale, $fb, $resolveHref): \Illuminate\Support\Collection {
if (! $model || ! $model->relationLoaded('links')) {
return collect();
}

return $model->links
->where('is_active', true)
->filter(static fn ($l) => filled(trim((string) ($l->link ?? ''))))
->sortBy('order')
->values()
->map(static function ($link, $idx) use ($locale, $fb, $resolveHref) {
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
$btnClass = 'th-btn style1';
} else {
$btnClass = $idx === 0 ? 'th-btn style1' : 'th-btn th-border';
}

return [
'href' => $href,
'label' => $label,
'target' => $target,
'rel' => $rel,
'icon' => $icon,
'btnClass' => $btnClass,
];
});
};

$fallbackButtons = $mapModelLinksToButtons($section);
if ($fallbackButtons->isEmpty()) {
$fallbackButtons = collect([
[
'href' => url('/contact'),
'label' => __('Book an Appointment'),
'target' => '_self',
'rel' => null,
'icon' => 'fa-solid fa-calendar-days ms-2',
'btnClass' => 'th-btn style1',
],
[
'href' => url('/services'),
'label' => __('Our Departments'),
'target' => '_self',
'rel' => null,
'icon' => 'fa-light fa-arrow-right-long ms-2',
'btnClass' => 'th-btn th-border',
],
]);
}

$slides = collect();
if ($items->isNotEmpty()) {
foreach ($items as $idx => $item) {
$it = $item->translation($locale) ?? $item->translation($fb);
$heroImg = $item->getMediaUrl('images', $locale, $defaultSlides[$idx % 3]['img'], true);
$title = $it?->title;
if (! $title && $it?->content) {
$title = strip_tags($it->content);
}
if (! $title) {
$title = $st?->title ?: $defaultSlides[$idx % 3]['title'];
}
$sub = $it?->sub_title ?? ($idx === 0 ? ($st?->subtitle ?: __('welcome to randevu')) : __('welcome to randevu'));
$desc = $it?->content && $idx === 0 ? $it->content : '';
$buttons = $mapModelLinksToButtons($item);
// if ($buttons->isEmpty()) {
// $buttons = $fallbackButtons;
// }
$slides->push(['title' => $title, 'subtitle' => $sub, 'desc' => $desc, 'img' => $heroImg, 'buttons' => $buttons]);
}
} else {
foreach ($defaultSlides as $idx => $def) {
$slides->push([
'title' => $st?->title && $idx === 0 ? $st->title : $def['title'],
'subtitle' => $st?->subtitle ?: __('welcome to randevu '),
'desc' => $st?->description && $idx === 0 ? $st->description : '',
'img' => $def['img'],
// 'buttons' => $fallbackButtons,
]);
}
}
@endphp
<div class="th-hero-wrapper hero-1" id="hero-{{ $section->id }}">
	<div class="swiper th-slider" data-slider-options='{"effect":"fade"}'>
		<div class="swiper-wrapper">
			@foreach($slides as $slide)
			<div class="swiper-slide">
				<div class="hero-inner">
					<div class="th-hero-bg" data-bg-src="{{ $bg }}"></div>
					<div class="container">
						<div class="row align-items-end">
							<div class="col-xl-7">
								<div class="hero-style1">
									<span class="sub-title"
										data-ani="slideinup"
										data-ani-delay="0.2s">{{ $slide['subtitle'] }}</span>
									<h1 class="hero-title"
										data-ani="slideinup"
										data-ani-delay="0.4s">
										{!! $slide['title']
										!!}</h1>
									@if(! empty($slide['desc']))
									<div
										class="text-white mt-3 mb-4 opacity-90 hero-desc">
										{!! $slide['desc']
										!!}</div>
									@endif
									<div class="btn-group justify-content-xl-start justify-content-center"
										data-ani="slideinup"
										data-ani-delay="0.8s">
										@foreach($slide['buttons'] as $btn)
										<a href="{{ $btn['href'] }}"
											class="{{ $btn['btnClass'] }}"
											@if(($btn['target']
											?? '_self'
											)==='_blank'
											)
											target="_blank"
											@endif
											@if(!
											empty($btn['rel']))
											rel="{{ $btn['rel'] }}"
											@endif>
											{{ $btn['label'] }}
											@if(!
											empty($btn['icon']))
											<i
												class="{{ $btn['icon'] }}"></i>
											@endif
										</a>
										@endforeach
									</div>
								</div>
							</div>
							<div class="col-xl-5">
								<div class="hero-image"
									data-ani="slideinup"
									data-ani-delay="0.4s">
									<img src="{{ $slide['img'] }}"
										style="width: 596px; height: 750px;"
										alt="">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@endforeach
		</div>
		<div class="slider-controller">
			<div class="slider-pagination"></div>
		</div>
	</div>
	<div class="scroll-down">
		<a href="#about-sec" class="hero-scroll-wrap"><span></span></a>
		<span class="title">{{ __('Scroll') }}</span>
	</div>
	<div class="social-links">
		<a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer"><i
				class="fab fa-instagram"></i></a>
		<a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer"><i
				class="fab fa-facebook-f"></i></a>
		<a href="https://www.twitter.com/" target="_blank" rel="noopener noreferrer"><i
				class="fab fa-twitter"></i></a>
		<a href="https://www.linkedin.com/" target="_blank" rel="noopener noreferrer"><i
				class="fab fa-linkedin-in"></i></a>
	</div>
</div>