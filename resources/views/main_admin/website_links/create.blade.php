@extends('layout_new.mainlayout')

@section('content')
<div class="page-wrapper" style="padding:10px">
	<div class="row">
		<div class="col-12">
			<div class="page-title-box p-3 d-flex justify-content-between align-items-center">
				<h4 class="page-title">{{ __('website_links.add') }}</h4>
				<div class="page-title-start">
					<a href="{{ route('website-links.index') }}"
						class="btn btn-secondary">
						<i class="mdi mdi-arrow-left"></i>
						{{ __('website_links.back') }}
					</a>
				</div>
			</div>
		</div>
	</div>

	@if($errors->any())
	<div class="alert alert-danger">
		<ul class="mb-0">
			@foreach($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif

	<form action="{{ route('website-links.store') }}" method="POST">
		@csrf
		@include('main_admin.website_links.partials.form-fields')
	</form>
</div>
@endsection

@push('scripts')
@include('main_admin.website_links.partials.form-scripts')
@endpush
