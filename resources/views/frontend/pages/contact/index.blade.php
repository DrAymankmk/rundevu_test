@extends('frontend.layout.app')

@section('content')

<x-breadcrumb :title="__('main.contact')" />
@if(isset($cmsPageSections) && $cmsPageSections->isNotEmpty())
@foreach($cmsPageSections as $section)
@include('frontend.pages.contact.cms.render', ['section' => $section])
@endforeach
@else
@include('frontend.pages.contact.sections.contact_section')

@endif
@endsection
