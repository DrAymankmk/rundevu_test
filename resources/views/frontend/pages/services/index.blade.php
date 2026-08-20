@extends('frontend.layout.app')

@section('content')

<x-breadcrumb :title="__('main.all_services')"/>

@if(isset($cmsPageSections) && $cmsPageSections->isNotEmpty())
@foreach($cmsPageSections as $section)
@include('frontend.pages.services.cms.render', ['section' => $section])
@endforeach
@else
@include('frontend.pages.services.sections.services_section')

@endif
@endsection
