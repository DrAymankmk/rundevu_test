@php
$fb = config('app.fallback_locale', 'en');
$locale = app()->getLocale();
$st = $section->translation($locale) ?? $section->translation($fb);
$secTitle = $st?->title ?: __('Contact');
$secSubtitle = $st?->subtitle ?: __('Contact');
$secDescription = $st?->description ?: __('Contact description');
$items = $section->relationLoaded('items') ? $section->items->where('is_active', true)->sortBy('order')->values() :
collect();

$defaultContactIcons = [
'location' => 'fa-solid fa-location-dot',
'phone' => 'fa-light fa-phone',
'email' => 'fa-solid fa-envelope',
'default' => 'fa-solid fa-circle-info',
];

$resolveContactIcon = static function ($item, $translation) use ($defaultContactIcons) {
if (! empty($translation?->icon)) {
return $translation->icon;
}
$slug = strtolower((string) ($item->slug ?? ''));
if (str_contains($slug, 'location') || str_contains($slug, 'address') || $slug === 'contact-1') {
return $defaultContactIcons['location'];
}
if (str_contains($slug, 'phone') || str_contains($slug, 'tel') || $slug === 'contact-2') {
return $defaultContactIcons['phone'];
}
if (str_contains($slug, 'email') || str_contains($slug, 'mail') || $slug === 'contact-3') {
return $defaultContactIcons['email'];
}

return $defaultContactIcons['default'];
};

$resolveContactKind = static function ($item) {
$slug = strtolower((string) ($item->slug ?? ''));
if (str_contains($slug, 'location') || str_contains($slug, 'address') || $slug === 'contact-1') {
return 'location';
}
if (str_contains($slug, 'phone') || str_contains($slug, 'tel') || $slug === 'contact-2') {
return 'phone';
}
if (str_contains($slug, 'email') || str_contains($slug, 'mail') || $slug === 'contact-3') {
return 'email';
}

return 'text';
};

$parseContactLines = static function (?string $content) {
if (blank($content)) {
return collect();
}

$normalized = preg_replace('#<br\s* /?>#i', "\n", (string) $content);
	return collect(preg_split('/\R+/', $normalized))
	->map(static fn ($line) => trim(strip_tags($line)))
	->filter()
	->values();
	};
	$contactHref = static function (string $line, string $kind) {
	if ($kind === 'phone') {
	$digits = preg_replace('/[^\d+]/', '', $line);

	return $digits !== '' ? 'tel:' . $digits : null;
	}
	if ($kind === 'email' && filter_var($line, FILTER_VALIDATE_EMAIL)) {
	return 'mailto:' . $line;
	}

	return null;
	};

	@endphp


	<style>
	html[lang="ar"] .contact-form .form-group>i {
		left: 35px;
		right: auto
	}
	html[dir="rtl"] .contact-form .form-field-icons {
    left: 20px;
    right: auto;
}
	html[dir="rtl"] .contact-form .form-group .form-control, html[dir="rtl"] .contact-form .form-group .form-select {
    padding-left: 4.25rem;
    padding-right: 15px;
    text-align: right;
    direction: rtl;
}


	</style>

	<div class="space overflow-hidden" id="contact-sec">
		<div class="container">
			<div class="row gy-4">
				<div class="col-xl-4">
					<div class="contact-media-wrap">
						@forelse($items as $item)
						@php
						$it = $item->translation($locale) ??
						$item->translation($fb);
						$itemTitle = $it?->title ?: __('main.contact');
						$itemIcon = $resolveContactIcon($item, $it);
						$kind = $resolveContactKind($item);
						$lines = $parseContactLines($it?->content);
						$itemLinks = $item->relationLoaded('links')
						? $item->links->where('is_active',
						true)->sortBy('order')->values()
						: collect();
						@endphp
						<div class="contact-media">
							<div class="icon-btn">
								<i class="{{ $itemIcon }}"></i>
							</div>
							<div class="media-body">
								<h5 class="box-title">{{ $itemTitle }}
								</h5>
								@if($itemLinks->isNotEmpty())
								<p class="box-text">
									@foreach($itemLinks as $link)
									@php
									$linkTr =
									$link->translation($locale) ??
									$link->translation($fb);
									$linkLabel = $linkTr?->name ??
									$link->name ?? $link->link;
									$linkHref =
									filled($link->link) ?
									$link->link : null;
									if ($linkHref && $kind ===
									'phone' && !
									str_starts_with($linkHref,
									'tel:')) {
									$digits =
									preg_replace('/[^\d+]/', '',
									$linkHref);
									$linkHref = $digits !== '' ?
									'tel:' . $digits : $linkHref;
									}
									if ($linkHref && $kind ===
									'email' && !
									str_starts_with($linkHref,
									'mailto:')) {
									$linkHref = 'mailto:' .
									$linkHref;
									}
									@endphp
									@if($linkHref)
									<a href="{{ $linkHref }}"
										@if(($link->target
										?? '_self') ===
										'_blank')
										target="_blank"
										rel="noopener
										noreferrer"
										@endif>{{ $linkLabel }}</a>
									@else
									<span>{{ $linkLabel }}</span>
									@endif
									@endforeach
								</p>
								@elseif($lines->isNotEmpty())
								@if($kind === 'location' || $kind ===
								'text')
								<p class="box-text">
									{{ $lines->implode(', ') }}
								</p>
								@else
								<p class="box-text">
									@foreach($lines as $line)
									@php $href =
									$contactHref($line, $kind);
									@endphp
									@if($href)
									<a
										href="{{ $href }}">{{ $line }}</a>
									@else
									<span>{{ $line }}</span>
									@endif
									@endforeach
								</p>
								@endif
								@endif
							</div>
						</div>
						@empty
						<p class="text-muted mb-0">{{ __('main.no_contact_info') }}
						</p>
						@endforelse
					</div>
				</div>
				<div class="col-xl-8">
					<form action="{{ route('frontend.contact.submit') }}" method="POST"
						class="contact-form">
						@csrf
						<h3 class="h4 mb-30 mt-n3">
							{{ __('main.you_have_questions') }}

						</h3>

						@if(session('contact_us_submitted'))
							<div class="alert alert-success">{{ session('contact_us_submitted') }}</div>
						@endif

						@if($errors->contactForm->any())
							<div class="alert alert-danger">
								<ul class="mb-0 ps-3">
									@foreach($errors->contactForm->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif

						<div class="row">
							<div class="form-group col-md-6">
								<input type="text" class="form-control @error('name', 'contactForm') is-invalid @enderror"
									name="name" id="name" value="{{ old('name') }}"
									placeholder=" {{ __('main.name') }}" required>
								<i class="fal fa-user"></i>
							</div>
							<div class="form-group col-md-6">
								<input type="tel" class="form-control @error('number', 'contactForm') is-invalid @enderror"
									name="number" id="number" value="{{ old('number') }}"
									placeholder=" {{ __('main.phone_number') }}" required>
								<i class="fal fa-phone"></i>
							</div>
							<div class="form-group col-12">
								<input type="email" class="form-control @error('email', 'contactForm') is-invalid @enderror"
									name="email" id="email" value="{{ old('email') }}"
									placeholder=" {{ __('main.email_address') }}" required>
								<i class="fal fa-envelope"></i>
							</div>


							<div class="form-group col-12">
								<textarea name="message" id="message"
									cols="30" rows="3"
									class="form-control @error('message', 'contactForm') is-invalid @enderror"
									placeholder=" {{ __('main.your_message') }}" required>{{ old('message') }}</textarea>
								<i class="fal fa-comment"></i>
							</div>
							<div class="form-btn mt-20 col-12">
								<button class="th-btn">
									{{ __('main.send_message') }}</button>
							</div>
						</div>
						<p class="form-messages mb-0 mt-3"></p>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="">
		<div class="contact-map">
			<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3644.7310056272386!2d89.2286059153658!3d24.00527418490799!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39fe9b97badc6151%3A0x30b048c9fb2129bc!2sAngfuztheme!5e0!3m2!1sen!2sbd!4v1651028958211!5m2!1sen!2sbd"
				allowfullscreen="" loading="lazy"></iframe>
			<div class="contact-icon">
				<img src="assets/img/icon/location-dot.svg" alt="">
			</div>
		</div>
	</div>