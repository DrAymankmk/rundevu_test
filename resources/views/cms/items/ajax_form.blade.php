<form action="{{ isset($item) ? route('cms.items.update', $item) : route('cms.items.store') }}" method="POST" id="ajaxItemForm" enctype="multipart/form-data">
    @csrf
    @if(isset($item))
        @method('PUT')
    @endif
    
    <div class="row">
        <div class="col-lg-8">
            @include('cms.items.partials.fields_main')
        </div>
        <div class="col-lg-4">
            @include('cms.items.partials.fields_sidebar')
        </div>
    </div>
</form>
