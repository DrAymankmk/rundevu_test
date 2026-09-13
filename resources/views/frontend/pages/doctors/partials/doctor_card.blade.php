@php
$locale = app()->getLocale();
$parentClinic = $doctor->owner ?? null;
$city = $doctor->city ?: ($parentClinic->city ?? null);
$cityLabel = '';
if ($city) {
$cityLabel = $locale === 'en'
? ($city->name_en ?: $city->name_ar)
: ($city->name_ar ?: $city->name_en);
}
$locationLabel = ($parentClinic->address ?? null) ?: $cityLabel;
$rate = (float) ($doctor->rate ?? 0);
$specialtyNames = $doctor->specialty_names ?? '';
$degreeName = $doctor->degree_name ?? '';
$columnClass = $columnClass ?? 'col-md-6 col-lg-3';
@endphp

<div class="{{ $columnClass }}">
	<article class="doctor-card-v2 h-100">
		<div class="doctor-card-v2__media">
			<a href="{{ frontend_route('frontend.doctors.show', $doctor) }}"
				class="doctor-card-v2__image-link">
				<img src="{{ $doctor->image }}" alt="{{ $doctor->name }}" loading="lazy">
			</a>

			<span class="doctor-card-v2__badge doctor-card-v2__badge--rate">
				<i class="fa-solid fa-star"></i>
				{{ number_format($rate, 1) }}
			</span>

			@if($degreeName !== '')
			<span class="doctor-card-v2__badge doctor-card-v2__badge--degree">
				{{ $degreeName }}
			</span>
			@endif
		</div>

		<div class="doctor-card-v2__body">
			<h3 class="doctor-card-v2__title">
				<a
					href="{{ frontend_route('frontend.doctors.show', $doctor) }}">{{ $doctor->name }}</a>
			</h3>

			<p class="doctor-card-v2__specialties" title="{{ $specialtyNames }}">
				<i class="fa-solid fa-stethoscope"></i>
				<span>{{ $specialtyNames !== '' ? $specialtyNames : __('doctors.no_specialties') }}</span>
			</p>

			@if($parentClinic)
			<p class="doctor-card-v2__clinic">
				<a href="{{ frontend_route('frontend.clinics.show', $parentClinic) }}"
					class="clinic-location-link">
					<i class="fa-solid fa-hospital"></i>
					<span>{{ $parentClinic->name }}</span>
				</a>
			</p>
			@endif

			@if(filled($locationLabel) && $locationLabel !== '0.0')
			<p class="doctor-card-v2__location">
				<i class="fa-solid fa-location-dot"></i>
				<span>{{ $locationLabel }}</span>
			</p>
			@elseif($cityLabel)
			<p class="doctor-card-v2__location">
				<i class="fa-solid fa-city"></i>
				<span>{{ $cityLabel }}</span>
			</p>
			@endif

			<div class="doctor-card-v2__footer">
				<div class="clinic-card-v2__stars"
					aria-label="{{ __('doctors.rating') }} {{ number_format($rate, 1) }}">
					@for($i = 1; $i <= 5; $i++) <i
						class="fa-{{ $rate >= $i ? 'solid' : 'regular' }} fa-star {{ $rate >= $i ? 'is-active' : '' }}">
						</i>
						@endfor
				</div>

				<a href="{{ frontend_route('frontend.doctors.show', $doctor) }}"
					class="doctor-card-v2__cta">
					{{ __('doctors.view_details') }}
					<x-arrow-icon />
				</a>
			</div>
		</div>
	</article>
</div>