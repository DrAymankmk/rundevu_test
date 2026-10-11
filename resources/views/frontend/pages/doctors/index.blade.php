@extends('frontend.layout.app')

@section('content')
@php
	$activeSpecialty = $activeSpecialty ?? null;
	$specialtyName = $specialtyName ?? '';
	$listTitle = $listTitle ?? __('doctors.page_title');
@endphp

<x-breadcrumb
	:title="$listTitle"
	:current="$specialtyName !== '' ? $specialtyName : $listTitle"
	:items="$activeSpecialty ? [['label' => __('doctors.page_title'), 'url' => frontend_route('frontend.doctors')]] : []"
	page="doctors"
/>

@include('frontend.pages.home.sections.specialties_section')

<section class="space-top space-extra-bottom doctors-listing-sec" style="padding: 40px 40px;">
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
					<a href="{{ frontend_route('frontend.doctors') }}"
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