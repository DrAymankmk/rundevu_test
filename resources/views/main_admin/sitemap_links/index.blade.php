@extends('layout_new.mainlayout')

@section('content')
<div class="page-wrapper" style="padding:20px">
	<div class="row">
		<div class="col-12 pb-4">
			<div
				class="page-title-box d-flex justify-content-between align-items-center flex-wrap gap-2">
				<div>
					<h4 class="page-title mb-1">{{ __('sitemap_links.title') }}</h4>
					<p class="text-muted mb-0">{{ __('sitemap_links.subtitle') }}</p>
				</div>
				<div class="page-title-right d-flex gap-2 flex-wrap">
					<a href="{{ route('sitemap') }}" target="_blank"
						rel="noopener noreferrer" class="btn btn-outline-secondary">
						<i class="mdi mdi-sitemap"></i>
						{{ __('sitemap_links.preview_index') }}
					</a>
					<a href="{{ route('sitemap.locale', ['locale' => 'en']) }}"
						target="_blank" rel="noopener noreferrer"
						class="btn btn-outline-secondary">
						{{ __('sitemap_links.preview_en') }}
						<span
							class="badge bg-primary ms-1">{{ number_format($sitemapCounts['en'] ?? 0) }}</span>
					</a>
					<a href="{{ route('sitemap.locale', ['locale' => 'ar']) }}"
						target="_blank" rel="noopener noreferrer"
						class="btn btn-outline-secondary">
						{{ __('sitemap_links.preview_ar') }}
						<span
							class="badge bg-primary ms-1">{{ number_format($sitemapCounts['ar'] ?? 0) }}</span>
					</a>
					<a href="{{ route('sitemap-links.create') }}" class="btn btn-primary">
						<i class="mdi mdi-plus"></i>
						{{ __('sitemap_links.add') }}
					</a>
				</div>
			</div>
		</div>
	</div>

	<div class="row mb-3">
		<div class="col-md-6 col-xl-3 mb-2">
			<div class="card mb-0">
				<div class="card-body py-3">
					<div class="text-muted small mb-1">
						{{ __('sitemap_links.preview_en') }}</div>
					<div class="fw-semibold mb-0">
						{{ number_format($sitemapCounts['en'] ?? 0) }}
						<span
							class="text-muted fw-normal">{{ __('sitemap_links.links_count_label') }}</span>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-xl-3 mb-2">
			<div class="card mb-0">
				<div class="card-body py-3">
					<div class="text-muted small mb-1">
						{{ __('sitemap_links.preview_ar') }}</div>
					<div class="fw-semibold mb-0">
						{{ number_format($sitemapCounts['ar'] ?? 0) }}
						<span
							class="text-muted fw-normal">{{ __('sitemap_links.links_count_label') }}</span>
					</div>
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

	@if(session('error'))
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		{{ session('error') }}
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
	@endif

	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h5 class="card-title mb-1">{{ __('sitemap_links.pages') }}</h5>
					<p class="text-muted mb-0">{{ __('sitemap_links.pages_hint') }}</p>
				</div>
				<div class="card-body">
					@include('main_admin.sitemap_links.partials.links_table', [
					'links' => $pages,
					'emptyText' => __('sitemap_links.empty_pages'),
					])
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h5 class="card-title mb-1">{{ __('sitemap_links.groups') }}</h5>
					<p class="text-muted mb-0">{{ __('sitemap_links.groups_hint') }}</p>
				</div>
				<div class="card-body">
					@include('main_admin.sitemap_links.partials.links_table', [
					'links' => $groups,
					'emptyText' => __('sitemap_links.empty_groups'),
					])
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
