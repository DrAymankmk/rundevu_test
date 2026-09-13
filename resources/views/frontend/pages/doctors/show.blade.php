@extends('frontend.layout.app')

@section('content')
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
	$locationLabel = ($parentClinic->address ?? null) ?: ($doctor->address ?: $cityLabel);
	$mapUrl = null;
	$lat = $parentClinic->lat ?? $doctor->lat;
	$lng = $parentClinic->lng ?? $doctor->lng;
	if (!empty($lat) && !empty($lng) && (float) $lat != 0 && (float) $lng != 0) {
		$mapUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($lat . ',' . $lng);
	} elseif (filled($locationLabel) && $locationLabel !== '0.0') {
		$mapUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($locationLabel);
	}
	$rate = (float) ($doctor->rate ?? 0);
	$ratesCount = (int) ($doctor->rates_count ?? 0);
	$specialtyList = collect(explode(',', (string) ($doctor->specialty_names ?? '')))
		->map(fn ($item) => trim($item))
		->filter()
		->values();
@endphp

<div class="breadcumb-wrapper" data-bg-src="{{ asset('frontend/assets/img/bg/breadcumb-doctor-details.jpg') }}">
	<div class="container">
		<div class="breadcumb-content">
			<h1 class="breadcumb-title">{{ $doctor->name }}</h1>
			<ul class="breadcumb-menu">
				<li><a href="{{ frontend_route('frontend.home') }}">{{ __('main.home') }}</a></li>
				<li><a href="{{ frontend_route('frontend.doctors') }}">{{ __('doctors.page_title') }}</a></li>
				<li>{{ \Illuminate\Support\Str::limit($doctor->name, 40) }}</li>
			</ul>
		</div>
	</div>
</div>

<section class="space-top space-extra-bottom doctor-details-page">
	<div class="container">
		<div class="doctor-details-hero">
			<div class="doctor-details-hero__media">
				<img src="{{ $doctor->image }}" alt="{{ $doctor->name }}">
			</div>

			<div class="doctor-details-hero__content">
				<div class="doctor-details-hero__top">
					<span class="doctor-details-chip doctor-details-chip--soft">
						<i class="fa-solid fa-user-doctor"></i>
						{{ __('main.doctor') }}
					</span>

					<div class="doctor-details-hero__rating">
						<div class="clinic-card-v2__stars" aria-label="{{ __('doctors.rating') }} {{ number_format($rate, 1) }}">
							@for($i = 1; $i <= 5; $i++)
								<i class="fa-{{ $rate >= $i ? 'solid' : 'regular' }} fa-star {{ $rate >= $i ? 'is-active' : '' }}"></i>
							@endfor
						</div>
						<strong>{{ number_format($rate, 1) }}</strong>
						@if($ratesCount > 0)
							<span>{{ __('doctors.reviews_count', ['count' => $ratesCount]) }}</span>
						@endif
					</div>
				</div>

				<h2 class="doctor-details-hero__title">{{ $doctor->name }}</h2>

				@if(!empty($doctor->degree_name))
				<p class="doctor-details-hero__degree">
					<i class="fa-solid fa-graduation-cap"></i>
					{{ $doctor->degree_name }}
				</p>
				@endif

				@if($specialtyList->isNotEmpty())
				<div class="doctor-details-tags">
					@foreach($specialtyList as $specialty)
						<span class="doctor-details-chip">{{ $specialty }}</span>
					@endforeach
				</div>
				@endif

				<div class="doctor-details-meta">
					@if($parentClinic)
					<span>
						<i class="fa-solid fa-hospital"></i>
						{{ $parentClinic->name }}
					</span>
					@endif

					@if($cityLabel)
					<span>
						<i class="fa-solid fa-city"></i>
						{{ $cityLabel }}
					</span>
					@endif

					@if(filled($locationLabel) && $locationLabel !== '0.0')
					<span>
						<i class="fa-solid fa-location-dot"></i>
						{{ \Illuminate\Support\Str::limit($locationLabel, 55) }}
					</span>
					@endif
				</div>

				<div class="doctor-details-hero__actions">
					@if($parentClinic)
					<a href="{{ frontend_route('frontend.clinics.show', $parentClinic) }}" class="th-btn style2">
						<i class="fa-solid fa-hospital"></i>
						{{ __('doctors.view_clinic') }}
					</a>
					@endif

					@if(filled($doctor->phone))
					<a href="tel:{{ $doctor->phone }}" class="th-btn black-border">
						<i class="fa-solid fa-phone"></i>
						{{ __('doctors.call_doctor') }}
					</a>
					@endif

					@if($mapUrl)
					<a href="{{ $mapUrl }}" target="_blank" rel="noopener noreferrer" class="th-btn black-border">
						<i class="fa-solid fa-map-location-dot"></i>
						{{ __('doctors.open_map') }}
					</a>
					@endif

					<a href="{{ frontend_route('frontend.doctors') }}" class="doctor-details-back">
						{{ __('doctors.back_to_doctors') }}
						<x-arrow-icon />
					</a>
				</div>
			</div>
		</div>

		<div class="row gy-4 mt-1">
			<div class="col-lg-8">
				@if(filled($info))
				<div class="doctor-details-panel">
					<div class="doctor-details-panel__head">
						<span class="doctor-details-panel__icon"><i class="fa-solid fa-circle-info"></i></span>
						<h3>{{ __('doctors.about') }}</h3>
					</div>
					<div class="doctor-details-panel__body">
						{!! nl2br(e($info)) !!}
					</div>
				</div>
				@endif

				@if($parentClinic)
				<div class="doctor-details-panel">
					<div class="doctor-details-panel__head">
						<span class="doctor-details-panel__icon"><i class="fa-solid fa-hospital"></i></span>
						<div>
							<h3>{{ __('doctors.clinic') }}</h3>
							<p>{{ __('doctors.clinic_affiliation') }}</p>
						</div>
					</div>

					<a href="{{ frontend_route('frontend.clinics.show', $parentClinic) }}" class="doctor-clinic-card">
						<img src="{{ $parentClinic->image }}" alt="{{ $parentClinic->name }}">
						<div class="doctor-clinic-card__content">
							<h4>{{ $parentClinic->name }}</h4>
							@if($cityLabel)
							<span><i class="fa-solid fa-city"></i> {{ $cityLabel }}</span>
							@endif
							@if(filled($locationLabel) && $locationLabel !== '0.0')
							<span><i class="fa-solid fa-location-dot"></i> {{ \Illuminate\Support\Str::limit($locationLabel, 70) }}</span>
							@endif
						</div>
						<span class="doctor-clinic-card__cta">
							{{ __('doctors.view_clinic') }}
							<x-arrow-icon />
						</span>
					</a>
				</div>
				@endif

				<div class="doctor-details-panel">
					<div class="doctor-details-panel__head">
						<span class="doctor-details-panel__icon"><i class="fa-solid fa-comments"></i></span>
						<div>
							<h3>{{ __('doctors.reviews') }}</h3>
							@if($ratesCount > 0)
							<p>{{ __('doctors.reviews_count', ['count' => $ratesCount]) }}</p>
							@endif
						</div>
					</div>

					@forelse($visitorRatings as $review)
					<div class="doctor-review-card">
						<div class="doctor-review-card__head">
							<img src="{{ $review->users->image ?? asset('media/logo/logo.png') }}"
								alt="{{ $review->users->name ?? '' }}">
							<div>
								<strong>{{ $review->users->name ?? '' }}</strong>
								<div class="clinic-card-v2__stars">
									@for($i = 1; $i <= 5; $i++)
										<i class="fa-{{ (float) $review->rate_value >= $i ? 'solid' : 'regular' }} fa-star {{ (float) $review->rate_value >= $i ? 'is-active' : '' }}"></i>
									@endfor
								</div>
							</div>
						</div>
						<p>{{ $review->comment }}</p>
					</div>
					@empty
					<div class="doctor-details-empty">
						<i class="fa-regular fa-comment-dots"></i>
						<p>{{ __('doctors.no_reviews') }}</p>
					</div>
					@endforelse
				</div>
			</div>

			<div class="col-lg-4">
				<aside class="doctor-details-sidebar">
					<div class="doctor-details-side-card">
						<div class="doctor-details-side-card__head">
							<h3>{{ __('doctors.view_details') }}</h3>
							<div class="doctor-details-side-card__score">
								<strong>{{ number_format($rate, 1) }}</strong>
								<span>{{ __('doctors.rating') }}</span>
							</div>
						</div>

						<ul class="doctor-details-info-list">
							@if(!empty($doctor->degree_name))
							<li>
								<span class="doctor-details-info-list__icon"><i class="fa-solid fa-graduation-cap"></i></span>
								<div>
									<small>{{ __('doctors.degree') }}</small>
									<span>{{ $doctor->degree_name }}</span>
								</div>
							</li>
							@endif

							@if($parentClinic)
							<li>
								<span class="doctor-details-info-list__icon"><i class="fa-solid fa-hospital"></i></span>
								<div>
									<small>{{ __('doctors.clinic') }}</small>
									<a href="{{ frontend_route('frontend.clinics.show', $parentClinic) }}">{{ $parentClinic->name }}</a>
								</div>
							</li>
							@endif

							@if(filled($locationLabel) && $locationLabel !== '0.0')
							<li>
								<span class="doctor-details-info-list__icon"><i class="fa-solid fa-location-dot"></i></span>
								<div>
									<small>{{ __('doctors.address') }}</small>
									@if($mapUrl)
									<a href="{{ $mapUrl }}" target="_blank" rel="noopener noreferrer">{{ $locationLabel }}</a>
									@else
									<span>{{ $locationLabel }}</span>
									@endif
								</div>
							</li>
							@endif

							@if($cityLabel)
							<li>
								<span class="doctor-details-info-list__icon"><i class="fa-solid fa-city"></i></span>
								<div>
									<small>{{ __('main.city') }}</small>
									<span>{{ $cityLabel }}</span>
								</div>
							</li>
							@endif

							@if(filled($doctor->phone))
							<li>
								<span class="doctor-details-info-list__icon"><i class="fa-solid fa-phone"></i></span>
								<div>
									<small>{{ __('doctors.phone') }}</small>
									<a href="tel:{{ $doctor->phone }}">{{ $doctor->phone }}</a>
								</div>
							</li>
							@endif

							@if(filled($doctor->email))
							<li>
								<span class="doctor-details-info-list__icon"><i class="fa-solid fa-envelope"></i></span>
								<div>
									<small>{{ __('doctors.email') }}</small>
									<a href="mailto:{{ $doctor->email }}">{{ $doctor->email }}</a>
								</div>
							</li>
							@endif
						</ul>

						<div class="doctor-details-side-card__actions">
							@if($parentClinic)
							<a href="{{ frontend_route('frontend.clinics.show', $parentClinic) }}" class="th-btn style2 w-100 text-center">
								{{ __('doctors.view_clinic') }}
							</a>
							@endif
							@if(filled($doctor->phone))
							<a href="tel:{{ $doctor->phone }}" class="th-btn black-border w-100 text-center">
								{{ __('doctors.call_doctor') }}
							</a>
							@endif
							@if($mapUrl)
							<a href="{{ $mapUrl }}" target="_blank" rel="noopener noreferrer" class="th-btn black-border w-100 text-center">
								{{ __('doctors.open_map') }}
							</a>
							@endif
						</div>
					</div>
				</aside>
			</div>
		</div>

		@if($relatedDoctors->isNotEmpty())
		<div class="doctor-details-related">
			<div class="title-area text-center mb-40">
				<span class="sub-title">{{ __('doctors.page_title') }}</span>
				<h2 class="sec-title">{{ __('doctors.related_doctors') }}</h2>
			</div>
			<div class="row gy-4">
				@foreach($relatedDoctors as $related)
					@include('frontend.pages.doctors.partials.doctor_card', [
						'doctor' => $related,
						'columnClass' => 'col-sm-6 col-lg-3',
					])
				@endforeach
			</div>
		</div>
		@endif
	</div>
</section>
@endsection
