@extends('frontend.layout.app')

@section('content')

<div class="breadcumb-wrapper">
	<div class="container">
		<div class="breadcumb-content">
			<h1 class="breadcumb-title"> {{ __('main.contact') }}</h1>
			<ul class="breadcumb-menu">
				<li><a href="{{ route('frontend.home') }}">{{ __('main.home') }}</a></li>
				<li>{{ __('main.contact') }}</li>
			</ul>
		</div>
	</div>
</div>
@if(isset($cmsPageSections) && $cmsPageSections->isNotEmpty())
@foreach($cmsPageSections as $section)
@include('frontend.pages.contact.cms.render', ['section' => $section])
@endforeach
@else
@include('frontend.pages.contact.sections.contact_section')

@endif
@endsection
