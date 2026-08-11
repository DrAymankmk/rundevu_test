@extends('frontend.layout.app')

@section('content')
    @if(isset($cmsPageSections) && $cmsPageSections->isNotEmpty())
        @foreach($cmsPageSections as $section)
            @include('frontend.pages.home.cms.render', ['section' => $section])
        @endforeach
    @else
        @include('frontend.pages.home.sections.hero_section')
        @include('frontend.pages.home.sections.features_section')
        @include('frontend.pages.home.sections.about_section')
        @include('frontend.pages.home.sections.services_section')
        @include('frontend.pages.home.sections.download_section')
    @endif
@endsection
