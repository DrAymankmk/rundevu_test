@extends('layout_new.mainlayout')

@section('content')
<div class="page-wrapper" style="padding:20px">
	<div class="row">
		<div class="col-12 pb-4">
			<div class="page-title-box d-flex justify-content-between align-items-center">
				<h4 class="page-title mb-0">{{ __('website_links.title') }}</h4>
				<div class="page-title-right d-flex gap-2">
					<a href="{{ route('website-links.create', ['type' => 'store']) }}"
						class="btn btn-outline-primary">
						<i class="mdi mdi-apple"></i>
						{{ __('website_links.add_store') }}
					</a>
					<a href="{{ route('website-links.create', ['type' => 'social']) }}"
						class="btn btn-primary">
						<i class="mdi mdi-plus"></i>
						{{ __('website_links.add_social') }}
					</a>
				</div>
			</div>
		</div>
	</div>

	@if(session('success'))
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		{{ session('success') }}
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
	@endif

	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h5 class="card-title mb-1">{{ __('website_links.store_links') }}</h5>
					<p class="text-muted mb-0">{{ __('website_links.store_hint') }}</p>
				</div>
				<div class="card-body">
					@include('main_admin.website_links.partials.links_table', ['links' =>
					$storeLinks, 'emptyText' => __('website_links.empty_store')])
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h5 class="card-title mb-1">{{ __('website_links.social_links') }}
					</h5>
					<p class="text-muted mb-0">{{ __('website_links.social_hint') }}</p>
				</div>
				<div class="card-body">
					@include('main_admin.website_links.partials.links_table', ['links' =>
					$socialLinks, 'emptyText' => __('website_links.empty_social')])
				</div>
			</div>
		</div>
	</div>
</div>

@foreach($storeLinks->concat($socialLinks) as $link)
<div class="modal fade" id="delete_link_{{ $link->id }}" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<form action="{{ route('website-links.destroy', $link->id) }}" method="POST">
				@csrf
				@method('DELETE')
				<div class="modal-body text-center">
					<h4>{{ __('website_links.delete_confirm') }}</h4>
					<p class="text-muted mb-0">{{ $link->displayTitle() }}</p>
					<div class="mt-4 d-flex justify-content-center gap-2">
						<button type="button" class="btn btn-white"
							data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
						<button type="submit"
							class="btn btn-danger">{{ __('website_links.delete') }}</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endforeach
@endsection
