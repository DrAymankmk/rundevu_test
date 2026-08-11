@extends('frontend.layout.app')

@section('content')

<div class="breadcumb-wrapper " data-bg-src="{{ asset('assets/img/bg/breadcumb-bg.jpg') }}">
	<div class="container">
		<div class="breadcumb-content">
			<h1 class="breadcumb-title"> {{ __('main.faqs') }}</h1>
			<ul class="breadcumb-menu">
				<li><a href="{{ route('frontend.home') }}"> {{ __('main.home') }}</a></li>
				<li> {{ __('main.faqs') }}</li>
			</ul>
		</div>
	</div>
</div>
@if(isset($cmsPageSections) && $cmsPageSections->isNotEmpty())
@foreach($cmsPageSections as $section)
@include('frontend.pages.faq.cms.render', ['section' => $section])
@endforeach
@else
@include('frontend.pages.faq.sections.faq_section')

@endif
@endsection