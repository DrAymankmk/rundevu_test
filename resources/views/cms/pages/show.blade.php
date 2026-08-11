@extends('layout_new.mainlayout')

@section('content')
<div class="page-wrapper" style="padding:10px">
	<!-- Page Title -->
	<div class="row">
		<div class="col-12">
			<div class="page-title-box"
				style="display: flex; justify-content: space-between; align-items: center;">
				<h4 class="page-title">{{ __('cms.page_details') }}: {{ $page->name }}</h4>
				<div class="page-title-right">
					<a href="{{ route('cms.pages.index') }}" class="btn btn-secondary">
						<i class="mdi mdi-arrow-left"></i> {{ __('cms.back') }}
					</a>
					<a href="{{ route('cms.pages.edit', $page->id) }}"
						class="btn btn-primary">
						<i class="mdi mdi-pencil"></i> {{ __('cms.edit') }}
					</a>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<!-- Main Content -->
		<div class="col-lg-8">
			<!-- Translations -->
			<div class="card">
				<div class="card-header">
					<h5 class="card-title mb-0">{{ __('cms.translations') }}</h5>
				</div>
				<div class="card-body">
					<!-- Language Tabs -->
					<ul class="nav nav-tabs" id="languageTabs" role="tablist">
						@php
						$languages =
						\App\Models\CmsLanguage::active()->ordered()->get();
						@endphp
						@foreach($languages as $index => $lang)
						<li class="nav-item" role="presentation">
							<button class="nav-link {{ $index === 0 ? 'active' : '' }}"
								id="tab-{{ $lang->code }}"
								data-bs-toggle="tab"
								data-bs-target="#content-{{ $lang->code }}"
								type="button" role="tab">
								{{ $lang->flag ?? '' }}
								{{ $lang->name }}
								@if($lang->is_default)
								<span
									class="badge bg-success ms-1">{{ __('cms.default') }}</span>
								@endif
							</button>
						</li>
						@endforeach
					</ul>

					<div class="tab-content pt-3" id="languageTabsContent">
						@foreach($languages as $index => $lang)
						@php
						$translation = $page->translations->where('locale',
						$lang->code)->first();
						@endphp
						<div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
							id="content-{{ $lang->code }}" role="tabpanel">

							<div class="mb-3">
								<label
									class="form-label fw-bold">{{ __('cms.title') }}</label>
								<p class="mb-0"
									dir="{{ $lang->direction }}">
									{{ $translation->title ?? '-' }}
								</p>
							</div>

							@if($translation &&
							$translation->meta_description)
							<div class="mb-3">
								<label
									class="form-label fw-bold">{{ __('cms.meta_description') }}</label>
								<p class="mb-0"
									dir="{{ $lang->direction }}">
									{{ $translation->meta_description }}
								</p>
							</div>
							@endif

							@if($translation && $translation->meta_keywords)
							<div class="mb-3">
								<label
									class="form-label fw-bold">{{ __('cms.meta_keywords') }}</label>
								<p class="mb-0"
									dir="{{ $lang->direction }}">
									{{ $translation->meta_keywords }}
								</p>
							</div>
							@endif
						</div>
						@endforeach
					</div>
				</div>
			</div>

			<!-- Sections -->
			@if($page->sections && $page->sections->count() > 0)
			<div class="card mt-3">
				<div class="card-header">
					<h5 class="card-title mb-0">{{ __('cms.sections') }}
						({{ $page->sections->count() }})</h5>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>{{ __('cms.name') }}</th>
									<th>{{ __('cms.type') }}</th>
									<th>{{ __('cms.items') }}</th>
									<th>{{ __('cms.status') }}
									</th>
									<th>{{ __('cms.actions') }}
									</th>
								</tr>
							</thead>
							<tbody>
								@foreach($page->sections as $section)
								<tr>
									<td>{{ $section->name }}</td>
									<td><span class="badge bg-secondary">{{ $section->type }}</span>
									</td>
									<td><span class="badge bg-info">{{ $section->items->count() }}</span>
									</td>
									<td>
										@if($section->is_active)
										<span
											class="badge bg-success">{{ __('cms.active') }}</span>
										@else
										<span
											class="badge bg-danger">{{ __('cms.inactive') }}</span>
										@endif
									</td>
									<td>
										<a href="{{ route('cms.sections.show', $section->id) }}"
											class="btn btn-sm btn-info">
											<i
												class="mdi mdi-eye"></i>
										</a>
										<a href="{{ route('cms.sections.edit', $section->id) }}"
											class="btn btn-sm btn-primary">
											<i
												class="mdi mdi-pencil"></i>
										</a>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
			@endif
		</div>

		<!-- Sidebar -->
		<div class="col-lg-4">
			<!-- Page Settings -->
			<div class="card">
				<div class="card-header">
					<h5 class="card-title mb-0">{{ __('cms.page_settings') }}</h5>
				</div>
				<div class="card-body">
					<div class="mb-3">
						<label
							class="form-label fw-bold">{{ __('cms.internal_name') }}</label>
						<p class="mb-0">{{ $page->name }}</p>
					</div>

					<div class="mb-3">
						<label
							class="form-label fw-bold">{{ __('cms.slug') }}</label>
						<p class="mb-0"><code>{{ $page->slug }}</code></p>
					</div>

					<div class="mb-3">
						<label
							class="form-label fw-bold">{{ __('cms.order') }}</label>
						<p class="mb-0">{{ $page->order }}</p>
					</div>

					<div class="mb-3">
						<label
							class="form-label fw-bold">{{ __('cms.status') }}</label>
						<p class="mb-0">
							@if($page->is_active)
							<span
								class="badge bg-success">{{ __('cms.active') }}</span>
							@else
							<span
								class="badge bg-danger">{{ __('cms.inactive') }}</span>
							@endif
						</p>
					</div>
				</div>
			</div>

			<!-- Page Info -->
			<div class="card mt-3">
				<div class="card-header">
					<h5 class="card-title mb-0">{{ __('cms.page_info') }}</h5>
				</div>
				<div class="card-body">
					<p class="mb-1"><strong>{{ __('cms.created') }}:</strong>
						{{ $page->created_at->format('Y-m-d H:i') }}</p>
					<p class="mb-1"><strong>{{ __('cms.updated') }}:</strong>
						{{ $page->updated_at->format('Y-m-d H:i') }}</p>
					@if($page->deleted_at)
					<p class="mb-1"><strong>{{ __('cms.deleted') }}:</strong>
						{{ $page->deleted_at->format('Y-m-d H:i') }}</p>
					@endif
					<p class="mb-0"><strong>{{ __('cms.sections') }}:</strong>
						<a
							href="{{ route('cms.sections.index', ['page_id' => $page->id]) }}">
							{{ $page->sections->count() }}
							{{ __('cms.sections_count') }}
						</a>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection