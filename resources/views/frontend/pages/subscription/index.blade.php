@extends('frontend.layout.app')

@section('content')

<x-breadcrumb :title="__('main.subscription')" :bg="asset('assets/img/bg/breadcumb-bg.jpg')" />
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
