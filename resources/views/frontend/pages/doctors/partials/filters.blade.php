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
	$hasActiveFilters = filled($search) || (int) $cityId > 0 || (int) $specialtyId > 0 || (int) $clinicId > 0;
@endphp

<div class="doctors-filters mb-30">
	<form action="{{ route('frontend.doctors') }}" method="GET" class="doctors-filters__panel">
		<div class="row gy-3 gx-3 align-items-end">
			<div class="col-md-6 col-xl-3">
				<label class="form-label fw-semibold mb-2">{{ __('main.search') }}</label>
				<div class="doctors-filters__input">
					<i class="fa-solid fa-magnifying-glass"></i>
					<input type="text"
						name="q"
						value="{{ $search }}"
						class="form-control"
						placeholder="{{ __('doctors.search_placeholder') }}">
				</div>
			</div>

			<div class="col-md-6 col-xl-2">
				<label class="form-label fw-semibold mb-2">{{ __('main.city') }}</label>
				<select name="city_id" class="form-select">
					<option value="">{{ __('doctors.all_cities') }}</option>
					@foreach($cities as $city)
						<option value="{{ $city->id }}" {{ (int) $cityId === (int) $city->id ? 'selected' : '' }}>
							{{ $cityName($city) }}
						</option>
					@endforeach
				</select>
			</div>

			<div class="col-md-6 col-xl-2">
				<label class="form-label fw-semibold mb-2">{{ __('doctors.specialty') }}</label>
				<select name="specialty_id" class="form-select">
					<option value="">{{ __('doctors.all_specialties') }}</option>
					@foreach($specialties as $specialty)
						<option value="{{ $specialty->id }}" {{ (int) $specialtyId === (int) $specialty->id ? 'selected' : '' }}>
							{{ $specialtyName($specialty) }}
						</option>
					@endforeach
				</select>
			</div>

			<div class="col-md-6 col-xl-3">
				<label class="form-label fw-semibold mb-2">{{ __('doctors.clinic') }}</label>
				<select name="clinic_id" class="form-select">
					<option value="">{{ __('doctors.all_clinics') }}</option>
					@foreach($clinics as $clinicOption)
						<option value="{{ $clinicOption->id }}" {{ (int) $clinicId === (int) $clinicOption->id ? 'selected' : '' }}>
							{{ $clinicOption->name }}
						</option>
					@endforeach
				</select>
			</div>

			<div class="col-md-12 col-xl-2">
				<label class="form-label fw-semibold mb-2 d-none d-xl-block">&nbsp;</label>
				<div class="doctors-filters__actions">
					<button type="submit" class="th-btn style2">{{ __('doctors.filter') }}</button>
					<a href="{{ route('frontend.doctors') }}" class="th-btn black-border">{{ __('doctors.reset') }}</a>
				</div>
			</div>
		</div>

		@if($hasActiveFilters)
		<div class="doctors-filters__active mt-3">
			<span><i class="fa-solid fa-filter"></i> {{ __('doctors.active_filters') }}</span>
			<a href="{{ route('frontend.doctors') }}">{{ __('doctors.clear_filters') }}</a>
		</div>
		@endif
	</form>
</div>
