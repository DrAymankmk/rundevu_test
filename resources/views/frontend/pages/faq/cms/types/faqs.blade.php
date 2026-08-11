@php
$fb = config('app.fallback_locale', 'en');
$locale = app()->getLocale();
$st = $section->translation($locale) ?? $section->translation($fb);
$secTitle = $st?->title ?: __('FAQ');
$secSubtitle = $st?->subtitle ?: __('FAQ');
$secDescription = $st?->description ?: __('FAQ description');
$items = $section->relationLoaded('items') ? $section->items->where('is_active', true)->sortBy('order')->values() : collect();
$accordionId = 'faqAccordion-' . $section->id;
@endphp

<div class="faq-area overflow-hidden" id="faq-sec" style="padding:20px 40px">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-xl-8" style="margin:auto">
				<div class="faq-area2 ms-xxl-5 ps-xxl-5">
					<div class="title-area mb-40">
						<span class="sub-title">{!! $secSubtitle !!}</span>
						<h2 class="sec-title">{!! $secTitle !!}</h2>
					</div>
					<div class="accordion-area accordion" id="{{ $accordionId }}">
						@forelse($items as $item)
							@php
								$it = $item->translation($locale) ?? $item->translation($fb);
								$headingId = 'faq-heading-' . $item->id;
								$collapseId = 'faq-collapse-' . $item->id;
							@endphp
							<div class="accordion-card wow fadeInUp" data-wow-delay=".{{ min($loop->iteration, 5) }}s">
								<div class="accordion-header" id="{{ $headingId }}">
									<button class="accordion-button collapsed" type="button"
										data-bs-toggle="collapse"
										data-bs-target="#{{ $collapseId }}"
										aria-expanded="false"
										aria-controls="{{ $collapseId }}">
										{!! $it?->title ?: __('Item') !!}
									</button>
								</div>
								<div id="{{ $collapseId }}" class="accordion-collapse collapse"
									aria-labelledby="{{ $headingId }}"
									data-bs-parent="#{{ $accordionId }}">
									<div class="accordion-body">
										<p class="faq-text">{!! $it?->content ?: __('Item content') !!}</p>
									</div>
								</div>
							</div>
						@empty
						@endforelse
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="shape-mockup movingX d-none d-xxl-block" data-bottom="0%" data-right="0%">
		<img src="{{ asset('frontend/assets/img/shape/element-9.png') }}" alt="">
	</div>
</div>
