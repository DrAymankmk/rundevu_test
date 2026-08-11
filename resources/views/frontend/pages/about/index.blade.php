@extends('frontend.layout.app')

@section('content')
<div class="breadcumb-wrapper " data-bg-src="assets/img/bg/breadcumb-bg.jpg">
	<div class="container">
		<div class="breadcumb-content">
			<h1 class="breadcumb-title"> {{ __('main.about_us') }}</h1>
			<ul class="breadcumb-menu">
				<li><a href="{{ route('frontend.home') }}">{{ __('main.home') }}</a></li>
				<li>{{ __('main.about_us') }}</li>
			</ul>
		</div>
	</div>
</div>
@if(isset($cmsPageSections) && $cmsPageSections->isNotEmpty())
@foreach($cmsPageSections as $section)
@include('frontend.pages.about.cms.render', ['section' => $section])
@endforeach
@else
@include('frontend.pages.about.sections.about_section')
@include('frontend.pages.about.sections.features_section')
@include('frontend.pages.about.sections.values_section')
@include('frontend.pages.about.sections.testimonial_section')
@include('frontend.pages.about.sections.announcement_section')
@include('frontend.pages.about.sections.team_section')
@endif
@endsection
