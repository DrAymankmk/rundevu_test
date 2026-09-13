@extends('frontend.layout.app')

@section('content')
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
	$ratesCount = (int) ($clinic->rates_count ?? 0);
	$specialtyList = collect(explode(',', (string) ($clinic->specialty_names ?? '')))
		->map(fn ($item) => trim($item))
		->filter()
		->values();
	$hasSocial =
		filled($clinic->facebook_url) ||
		filled($clinic->instagram_url) ||
		filled($clinic->tiktok_url) ||
		filled($clinic->snapchat_url) ||
		filled($clinic->youtube_url);
@endphp

<div class="breadcumb-wrapper" data-bg-src="{{ asset('frontend/assets/img/bg/breadcumb-clinic-details.jpg') }}">
	<div class="container">
		<div class="breadcumb-content">
			<h1 class="breadcumb-title">{{ $clinic->name }}</h1>
			<ul class="breadcumb-menu">
				<li><a href="{{ frontend_route('frontend.home') }}">{{ __('main.home') }}</a></li>
				<li><a href="{{ frontend_route('frontend.clinics') }}">{{ __('main.clinics') }}</a></li>
				<li>{{ \Illuminate\Support\Str::limit($clinic->name, 40) }}</li>
			</ul>
		</div>
	</div>
</div>

<section class="space-top space-extra-bottom clinic-details-page">
	<div class="container">
		<div class="clinic-details-hero">
			<div class="clinic-details-hero__media">
				<img src="{{ $clinic->image }}" alt="{{ $clinic->name }}">
			</div>

			<div class="clinic-details-hero__content">
				<div class="clinic-details-hero__top">
					<span class="clinic-details-chip clinic-details-chip--soft">
						<i class="fa-solid fa-hospital"></i>
						{{ __('main.clinic') }}
					</span>

					<div class="clinic-details-hero__rating">
						<div class="clinic-card-v2__stars" aria-label="{{ __('clinics.rating') }} {{ number_format($rate, 1) }}">
							@for($i = 1; $i <= 5; $i++)
								<i class="fa-{{ $rate >= $i ? 'solid' : 'regular' }} fa-star {{ $rate >= $i ? 'is-active' : '' }}"></i>
							@endfor
						</div>
						<strong>{{ number_format($rate, 1) }}</strong>
						@if($ratesCount > 0)
							<span>{{ __('clinics.reviews_count', ['count' => $ratesCount]) }}</span>
						@endif
					</div>
				</div>

				<h2 class="clinic-details-hero__title">{{ $clinic->name }}</h2>

				@if($specialtyList->isNotEmpty())
				<div class="clinic-details-tags">
					@foreach($specialtyList as $specialty)
						<span class="clinic-details-chip">{{ $specialty }}</span>
					@endforeach
				</div>
				@endif

				<div class="clinic-details-meta">
					@if($cityLabel)
					<span>
						<i class="fa-solid fa-city"></i>
						{{ $cityLabel }}
					</span>
					@endif

					@if(filled($locationLabel) && $locationLabel !== '0.0')
					<span>
						<i class="fa-solid fa-location-dot"></i>
						{{ \Illuminate\Support\Str::limit($locationLabel, 60) }}
					</span>
					@endif

					@if($clinic->medical_staff->isNotEmpty())
					<span>
						<i class="fa-solid fa-user-doctor"></i>
						{{ __('clinics.doctors_count', ['count' => $clinic->medical_staff->count()]) }}
					</span>
					@endif
				</div>

				<div class="clinic-details-hero__actions">
					@if($mapUrl)
					<a href="{{ $mapUrl }}" target="_blank" rel="noopener noreferrer" class="th-btn style2">
						<i class="fa-solid fa-map-location-dot"></i>
						{{ __('clinics.open_map') }}
					</a>
					@endif

					@if(filled($clinic->phone))
					<a href="tel:{{ $clinic->phone }}" class="th-btn black-border">
						<i class="fa-solid fa-phone"></i>
						{{ __('clinics.call_clinic') }}
					</a>
					@endif

					<a href="{{ frontend_route('frontend.clinics') }}" class="clinic-details-back">
						{{ __('clinics.back_to_clinics') }}
						<x-arrow-icon />
					</a>
				</div>
			</div>
		</div>

		<div class="row gy-4 mt-1">
			<div class="col-lg-8">
				@if(filled($info))
				<div class="clinic-details-panel">
					<div class="clinic-details-panel__head">
						<span class="clinic-details-panel__icon"><i class="fa-solid fa-circle-info"></i></span>
						<h3>{{ __('clinics.about') }}</h3>
					</div>
					<div class="clinic-details-panel__body">
						{!! nl2br(e($info)) !!}
					</div>
				</div>
				@endif

				@if($clinic->medical_staff->isNotEmpty())
				<div class="clinic-details-panel">
					<div class="clinic-details-panel__head">
						<span class="clinic-details-panel__icon"><i class="fa-solid fa-user-doctor"></i></span>
						<div>
							<h3>{{ __('clinics.doctors') }}</h3>
							<p>{{ __('clinics.doctors_count', ['count' => $clinic->medical_staff->count()]) }}</p>
						</div>
					</div>

					<div class="row gy-3">
						@foreach($clinic->medical_staff as $doctor)
						<div class="col-sm-6 col-md-4">
							<a href="{{ frontend_route('frontend.doctors.show', $doctor) }}" class="clinic-doctor-card">
								<img src="{{ $doctor->image }}" alt="{{ $doctor->name }}">
								<div>
									<h4>{{ $doctor->name }}</h4>
									<span>{{ __('clinics.view_doctor') }}</span>
								</div>
							</a>
						</div>
						@endforeach
					</div>
				</div>
				@endif

				<div class="clinic-details-panel">
					<div class="clinic-details-panel__head">
						<span class="clinic-details-panel__icon"><i class="fa-solid fa-comments"></i></span>
						<div>
							<h3>{{ __('clinics.reviews') }}</h3>
							@if($ratesCount > 0)
							<p>{{ __('clinics.reviews_count', ['count' => $ratesCount]) }}</p>
							@endif
						</div>
					</div>

					@forelse($visitorRatings as $review)
					<div class="clinic-review-card">
						<div class="clinic-review-card__head">
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
					<div class="clinic-details-empty">
						<i class="fa-regular fa-comment-dots"></i>
						<p>{{ __('clinics.no_reviews') }}</p>
					</div>
					@endforelse
				</div>
			</div>

			<div class="col-lg-4">
				<aside class="clinic-details-sidebar">
					<div class="clinic-details-side-card">
						<div class="clinic-details-side-card__head">
							<h3>{{ __('main.clinic_details') }}</h3>
							<div class="clinic-details-side-card__score">
								<strong>{{ number_format($rate, 1) }}</strong>
								<span>{{ __('clinics.rating') }}</span>
							</div>
						</div>

						<ul class="clinic-details-info-list">
							@if(filled($locationLabel) && $locationLabel !== '0.0')
							<li>
								<span class="clinic-details-info-list__icon"><i class="fa-solid fa-location-dot"></i></span>
								<div>
									<small>{{ __('clinics.address') }}</small>
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
								<span class="clinic-details-info-list__icon"><i class="fa-solid fa-city"></i></span>
								<div>
									<small>{{ __('main.city') }}</small>
									<span>{{ $cityLabel }}</span>
								</div>
							</li>
							@endif

							@if(filled($clinic->phone))
							<li>
								<span class="clinic-details-info-list__icon"><i class="fa-solid fa-phone"></i></span>
								<div>
									<small>{{ __('clinics.phone') }}</small>
									<a href="tel:{{ $clinic->phone }}">{{ $clinic->phone }}</a>
								</div>
							</li>
							@endif

							@if(filled($clinic->email))
							<li>
								<span class="clinic-details-info-list__icon"><i class="fa-solid fa-envelope"></i></span>
								<div>
									<small>{{ __('clinics.email') }}</small>
									<a href="mailto:{{ $clinic->email }}">{{ $clinic->email }}</a>
								</div>
							</li>
							@endif
						</ul>

						@if($mapUrl || filled($clinic->phone))
						<div class="clinic-details-side-card__actions">
							@if($mapUrl)
							<a href="{{ $mapUrl }}" target="_blank" rel="noopener noreferrer" class="th-btn style2 w-100 text-center">
								{{ __('clinics.open_map') }}
							</a>
							@endif
							@if(filled($clinic->phone))
							<a href="tel:{{ $clinic->phone }}" class="th-btn black-border w-100 text-center">
								{{ __('clinics.call_clinic') }}
							</a>
							@endif
						</div>
						@endif
					</div>

					@if($hasSocial)
					<div class="clinic-details-side-card">
						<h3 class="mb-3">{{ __('main.social_media') }}</h3>
						<div class="clinic-details-social">
							@if(filled($clinic->facebook_url))
							<a target="_blank" rel="noopener" href="{{ $clinic->facebook_url }}" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
							@endif
							@if(filled($clinic->instagram_url))
							<a target="_blank" rel="noopener" href="{{ $clinic->instagram_url }}" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
							@endif
							@if(filled($clinic->tiktok_url))
							<a target="_blank" rel="noopener" href="{{ $clinic->tiktok_url }}" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
							@endif
							@if(filled($clinic->snapchat_url))
							<a target="_blank" rel="noopener" href="{{ $clinic->snapchat_url }}" aria-label="Snapchat"><i class="fab fa-snapchat"></i></a>
							@endif
							@if(filled($clinic->youtube_url))
							<a target="_blank" rel="noopener" href="{{ $clinic->youtube_url }}" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
							@endif
						</div>
					</div>
					@endif
				</aside>
			</div>
		</div>

		@if($relatedClinics->isNotEmpty())
		<div class="clinic-details-related">
			<div class="title-area text-center mb-40">
				<span class="sub-title">{{ __('main.clinics') }}</span>
				<h2 class="sec-title">{{ __('clinics.related_clinics') }}</h2>
			</div>
			<div class="row gy-4">
				@foreach($relatedClinics as $related)
					@include('frontend.pages.clinics.partials.clinic_card', [
						'clinic' => $related,
						'columnClass' => 'col-sm-6 col-lg-3',
					])
				@endforeach
			</div>
		</div>
		@endif
	</div>
</section>
@endsection
