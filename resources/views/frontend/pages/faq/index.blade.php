@extends('frontend.layout.app')

@section('content')

<x-breadcrumb :title="__('main.faqs')"/>
@if(isset($cmsPageSections) && $cmsPageSections->isNotEmpty())
@foreach($cmsPageSections as $section)
@include('frontend.pages.faq.cms.render', ['section' => $section])
@endforeach
@else
@include('frontend.pages.faq.sections.faq_section')

@endif
@endsection