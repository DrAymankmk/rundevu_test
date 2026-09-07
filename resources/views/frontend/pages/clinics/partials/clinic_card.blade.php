@php
	$locale = app()->getLocale();
	$cityLabel = '';
	if ($clinic->city) {
		$cityLabel = $locale === 'en'
			? ($clinic->city->name_en ?: $clinic->city->name_ar)
			: ($clinic->city->name_ar ?: $clinic->city->name_en);
	}
	$locationLabel = $clinic->address ?: $cityLabel;
	$mapUrl = null;
	if (!empty($clinic->lat) && !empty($clinic->lng) && (float) $clinic->lat != 0 && (float) $clinic->lng != 0) {
		$mapUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($clinic->lat . ',' . $clinic->lng);
	} elseif (filled($locationLabel) && $locationLabel !== '0.0') {
		$mapUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($locationLabel);
	}
	$rate = (float) ($clinic->rate ?? 0);
	$specialtyNames = $clinic->specialty_names ?? '';
	$columnClass = $columnClass ?? 'col-md-6 col-lg-4';
	$distance = isset($clinic->distance) ? round((float) $clinic->distance, 1) : null;
@endphp

<div class="{{ $columnClass }}">
	<article class="clinic-card-v2 h-100">
		<div class="clinic-card-v2__media">
			<a href="{{ route('frontend.clinics.show', $clinic->id) }}" class="clinic-card-v2__image-link">
				<img src="{{ $clinic->image }}" alt="{{ $clinic->name }}" loading="lazy">
			</a>

			@if($distance !== null)
			<span class="clinic-card-v2__badge clinic-card-v2__badge--distance">
				<i class="fa-solid fa-route"></i>
				{{ __('clinics.distance_km', ['km' => $distance]) }}
			</span>
			@endif

			<span class="clinic-card-v2__badge clinic-card-v2__badge--rate">
				<i class="fa-solid fa-star"></i>
				{{ number_format($rate, 1) }}
			</span>
		</div>

		<div class="clinic-card-v2__body">
			<h3 class="clinic-card-v2__title">
				<a href="{{ route('frontend.clinics.show', $clinic->id) }}">{{ $clinic->name }}</a>
			</h3>

			<p class="clinic-card-v2__specialties" title="{{ $specialtyNames }}">
				<i class="fa-solid fa-stethoscope"></i>
				<span>{{ $specialtyNames !== '' ? $specialtyNames : __('clinics.no_specialties') }}</span>
			</p>

			@if(filled($locationLabel) && $locationLabel !== '0.0')
			<p class="clinic-card-v2__location">
				@if($mapUrl)
				<a href="{{ $mapUrl }}" target="_blank" rel="noopener noreferrer" class="clinic-location-link">
					<i class="fa-solid fa-location-dot"></i>
					<span>{{ $locationLabel }}</span>
				</a>
				@else
				<span>
					<i class="fa-solid fa-location-dot"></i>
					<span>{{ $locationLabel }}</span>
				</span>
				@endif
			</p>
			@endif

			@if($cityLabel)
			<p class="clinic-card-v2__city">
				<i class="fa-solid fa-city"></i>
				<span>{{ $cityLabel }}</span>
			</p>
			@endif

			<div class="clinic-card-v2__footer">
				<div class="clinic-card-v2__stars" aria-label="{{ __('clinics.rating') }} {{ number_format($rate, 1) }}">
					@for($i = 1; $i <= 5; $i++)
						<i class="fa-{{ $rate >= $i ? 'solid' : 'regular' }} fa-star {{ $rate >= $i ? 'is-active' : '' }}"></i>
					@endfor
				</div>

				<a href="{{ route('frontend.clinics.show', $clinic->id) }}" class="clinic-card-v2__cta">
					{{ __('clinics.view_details') }}
					<x-arrow-icon />
				</a>
			</div>
		</div>
	</article>
</div>
