@php
	$locale = app()->getLocale();
	$cityName = function ($city) use ($locale) {
		if (!$city) {
			return '';
		}
		return $locale === 'en'
			? ($city->name_en ?: $city->name_ar)
			: ($city->name_ar ?: $city->name_en);
	};
	$specialtyName = function ($specialty) use ($locale) {
		return $locale === 'en'
			? ($specialty->name_en ?: $specialty->name_ar)
			: ($specialty->name_ar ?: $specialty->name_en);
	};
	$nearMe = $nearMe ?? false;
	$lat = $lat ?? null;
	$lng = $lng ?? null;
@endphp

<div class="clinics-filters mb-40">
	<form id="clinics-filter-form" action="{{ frontend_route('frontend.clinics') }}" method="GET" class="row gy-3 gx-3 align-items-end">
		<input type="hidden" name="lat" id="clinics-lat" value="{{ $nearMe ? $lat : '' }}">
		<input type="hidden" name="lng" id="clinics-lng" value="{{ $nearMe ? $lng : '' }}">

		<div class="col-md-6 col-xl-3">
			<label class="form-label fw-semibold mb-2">{{ __('main.search') }}</label>
			<input type="text"
				name="q"
				value="{{ $search }}"
				class="form-control"
				placeholder="{{ __('clinics.search_placeholder') }}">
		</div>

		<div class="col-md-6 col-xl-2">
			<label class="form-label fw-semibold mb-2">{{ __('main.city') }}</label>
			<select name="city_id" class="form-select">
				<option value="">{{ __('clinics.all_cities') }}</option>
				@foreach($cities as $city)
					<option value="{{ $city->id }}" {{ (int) $cityId === (int) $city->id ? 'selected' : '' }}>
						{{ $cityName($city) }}
					</option>
				@endforeach
			</select>
		</div>

		<div class="col-md-6 col-xl-2">
			<label class="form-label fw-semibold mb-2">{{ __('clinics.specialty') }}</label>
			<select name="specialty_id" class="form-select">
				<option value="">{{ __('clinics.all_specialties') }}</option>
				@foreach($specialties as $specialty)
					<option value="{{ $specialty->id }}" {{ (int) $specialtyId === (int) $specialty->id ? 'selected' : '' }}>
						{{ $specialtyName($specialty) }}
					</option>
				@endforeach
			</select>
		</div>

		<div class="col-md-6 col-xl-5">
			<label class="form-label fw-semibold mb-2 d-none d-xl-block">&nbsp;</label>
			<div class="clinics-filters__actions">
				<button type="submit" class="th-btn style2">{{ __('clinics.filter') }}</button>

				<button type="button"
					class="th-btn clinics-near-btn {{ $nearMe ? 'is-active' : '' }}"
					id="clinics-near-me"
					data-loading-text="{{ __('clinics.locating') }}"
					data-error-unsupported="{{ __('clinics.location_unsupported') }}"
					data-error-denied="{{ __('clinics.location_denied') }}"
					data-error-unavailable="{{ __('clinics.location_unavailable') }}">
					<i class="fa-solid fa-location-crosshairs"></i>
					<span>{{ $nearMe ? __('clinics.near_me_active') : __('clinics.near_me') }}</span>
				</button>

				<a href="{{ frontend_route('frontend.clinics') }}" class="th-btn black-border">{{ __('clinics.reset') }}</a>
			</div>
		</div>
	</form>

	@if($nearMe)
	<div class="clinics-near-banner mt-3">
		<i class="fa-solid fa-circle-check"></i>
		<span>{{ __('clinics.near_me_banner') }}</span>
		<a href="{{ frontend_route('frontend.clinics', array_filter(['q' => $search ?: null, 'city_id' => $cityId ?: null, 'specialty_id' => $specialtyId ?: null])) }}">
			{{ __('clinics.clear_near_me') }}
		</a>
	</div>
	@endif

	<p class="clinics-near-error text-danger mt-2 mb-0 d-none" id="clinics-near-error" role="alert"></p>
</div>
