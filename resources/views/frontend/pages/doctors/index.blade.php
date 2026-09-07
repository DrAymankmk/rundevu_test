@extends('frontend.layout.app')

@section('content')
<div class="breadcumb-wrapper" data-bg-src="{{ asset('frontend/assets/img/bg/breadcumb-bg.jpg') }}">
	<div class="container">
		<div class="breadcumb-content">
			<h1 class="breadcumb-title">{{ __('doctors.page_title') }}</h1>
			<ul class="breadcumb-menu">
				<li><a href="{{ route('frontend.home') }}">{{ __('main.home') }}</a></li>
				<li>{{ __('doctors.page_title') }}</li>
			</ul>
		</div>
	</div>
</div>

<section class="space-top space-extra-bottom doctors-listing-sec" style="padding: 40px 100px;">
	<div class="container">
		@include('frontend.pages.doctors.partials.filters')

		<div class="doctors-results-bar">
			<p>
				{{ __('doctors.results_count', ['count' => $doctors->total()]) }}
			</p>
		</div>

		<div class="row gy-4">
			@forelse($doctors as $doctor)
			@include('frontend.pages.doctors.partials.doctor_card', ['doctor' => $doctor])
			@empty
			<div class="col-12">
				<div class="doctors-empty-state">
					<i class="fa-solid fa-user-doctor"></i>
					<h3>{{ __('doctors.no_doctors') }}</h3>
					<p>{{ __('doctors.no_doctors_hint') }}</p>
					<a href="{{ route('frontend.doctors') }}"
						class="th-btn style2">{{ __('doctors.reset') }}</a>
				</div>
			</div>
			@endforelse
		</div>

		@if($doctors->hasPages())
		<div class="doctors-pagination-wrap">
			{{ $doctors->links('frontend.partials.pagination') }}
		</div>
		@endif
	</div>
</section>
@endsection
