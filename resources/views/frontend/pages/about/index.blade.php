@extends('frontend.layout.app')

@section('content')
<x-breadcrumb :title="__('main.about_us')"/>
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
