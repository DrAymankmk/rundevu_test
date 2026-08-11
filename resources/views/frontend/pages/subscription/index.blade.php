@extends('frontend.layout.app')

@section('content')

<div class="breadcumb-wrapper " data-bg-src="assets/img/bg/breadcumb-bg.jpg">
	<div class="container">
		<div class="breadcumb-content">
			<h1 class="breadcumb-title"> {{ __('main.subscription') }}</h1>
			<ul class="breadcumb-menu">
				<li><a href="{{ route('frontend.home') }}"> {{ __('main.home') }}</a></li>
				<li> {{ __('main.subscription') }}</li>
			</ul>
		</div>
	</div>
</div>
@if(isset($cmsPageSections) && $cmsPageSections->isNotEmpty())
@foreach($cmsPageSections as $section)
@include('frontend.pages.subscription.cms.render', [
'section' => $section,
'packages' => $packages ?? collect(),
'selectedPackage' => $selectedPackage ?? null,
'selectedPackageId' => $selectedPackageId ?? null,
])
@endforeach
@else
@include('frontend.pages.subscription.sections.checkout_section')

@endif
@endsection
