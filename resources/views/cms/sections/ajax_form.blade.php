<form action="{{ isset($section) ? route('cms.sections.update', $section) : route('cms.sections.store') }}" method="POST" id="ajaxSectionForm" enctype="multipart/form-data">
    @csrf
    @if(isset($section))
        @method('PUT')
    @endif
    
    <div class="row">
        <div class="col-lg-8">
            @include('cms.sections.partials.fields_main')
        </div>
        <div class="col-lg-4">
            @include('cms.sections.partials.fields_sidebar')
        </div>
    </div>
</form>
