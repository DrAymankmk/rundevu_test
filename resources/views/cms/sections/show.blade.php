@extends('layout_new.mainlayout')

@section('content')
<div class="page-wrapper" style="padding:10px">
	<!-- Page Title -->
	<div class="row">
		<div class="col-12">
			<div class="page-title-box">
				<div class="page-title-right">
					<a href="{{ route('cms.sections.index', ['page_id' => $section->cms_page_id]) }}"
						class="btn btn-secondary">
						<i class="mdi mdi-arrow-left"></i> {{ __('cms.back') }}
					</a>
					<a href="{{ route('cms.sections.edit', $section) }}" cms.e
						class="btn btn-primary">
						<i class="mdi mdi-pencil"></i> {{ __('cms.edit') }}
					</a>
				</div>
				<h4 class="page-title">{{ __('cms.section_details') }}: {{ $section->name }}
				</h4>
			</div>
		</div>
	</div>

	<div class="row">
		<!-- Main Content -->
		<div class="col-lg-8">
			<!-- Translations -->
			<div class="card">
				<div class="card-header">
					<h5 class="card-title mb-0">{{ __('Translations') }}</h5>
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
						$translation = $section->translations->where('locale',
						$lang->code)->first();
						$imageUrl =
						$section->getFirstMediaUrl("images_{$lang->code}") ?:
						$section->getFirstMediaUrl('images');
						@endphp
						<div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
							id="content-{{ $lang->code }}" role="tabpanel">

							@if($translation && $translation->title)
							<div class="mb-3">
								<label
									class="form-label fw-bold">{{ __('cms.title') }}</label>
								<p class="mb-0"
									dir="{{ $lang->direction }}">
									{{ $translation->title }}
								</p>
							</div>
							@endif

							@if($translation && $translation->subtitle)
							<div class="mb-3">
								<label
									class="form-label fw-bold">{{ __('cms.subtitle') }}</label>
								<p class="mb-0"
									dir="{{ $lang->direction }}">
									{{ $translation->subtitle }}
								</p>
							</div>
							@endif

							@if($translation && $translation->description)
							<div class="mb-3">
								<label
									class="form-label fw-bold">{{ __('cms.description') }}</label>
								<div class="border p-3 rounded"
									dir="{{ $lang->direction }}">
									{!! $translation->description
									!!}
								</div>
							</div>
							@endif

							@if($imageUrl)
							<div class="mb-3">
								<label
									class="form-label fw-bold">{{ __('cms.image') }}</label>
								<div>
									<img src="{{ $imageUrl }}"
										alt="{{ $translation->title ?? $section->name }}"
										class="img-fluid rounded"
										style="max-height: 400px;">
								</div>
							</div>
							@endif
						</div>
						@endforeach
					</div>
				</div>
			</div>

			<!-- Gallery -->
			@php
			$gallery = $section->getMedia('gallery');
			@endphp
			@if($gallery && $gallery->count() > 0)
			<div class="card mt-3">
				<div class="card-header">
					<h5 class="card-title mb-0">{{ __('cms.gallery') }}
						({{ $gallery->count() }})</h5>
				</div>
				<div class="card-body">
					<div class="row">
						@foreach($gallery as $media)
						<div class="col-md-3 mb-3">
							<img src="{{ \App\Support\Cms\CmsGalleryMedia::previewUrl($media) }}"
								alt="{{ $media->name }}"
								class="img-thumbnail w-100"
								style="height: 200px; object-fit: cover;">
						</div>
						@endforeach
					</div>
				</div>
			</div>
			@endif

			<!-- Items -->
			@if($section->items && $section->items->count() > 0)
			<div class="card mt-3">
				<div class="card-header">
					<h5 class="card-title mb-0">{{ __('cms.items') }}
						({{ $section->items->count() }})</h5>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>{{ __('cms.title') }}</th>
									<th>{{ __('cms.status') }}
									</th>
									<th>{{ __('cms.order') }}</th>
									<th>{{ __('cms.actions') }}
									</th>
								</tr>
							</thead>
							<tbody>
								@foreach($section->items as $item)
								@php
								$itemTranslation =
								$item->translation(app()->getLocale())
								?? $item->translations->first();
								@endphp
								<tr>
									<td>{{ $itemTranslation->title ?? '-' }}
									</td>
									<td>
										@if($item->is_active)
										<span
											class="badge bg-success">{{ __('cms.active') }}</span>
										@else
										<span
											class="badge bg-danger">{{ __('cms.inactive') }}</span>
										@endif
									</td>
									<td>{{ $item->order }}</td>
									<td>
										<a href="{{ route('cms.items.show', $item) }}"
											class="btn btn-sm btn-info">
											<i
												class="mdi mdi-eye"></i>
										</a>
										<a href="{{ route('cms.items.edit', $item) }}"
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
			<!-- Section Settings -->
			<div class="card">
				<div class="card-header">
					<h5 class="card-title mb-0">{{ __('cms.section_settings') }}</h5>
				</div>
				<div class="card-body">
					<div class="mb-3">
						<label
							class="form-label fw-bold">{{ __('cms.name') }}</label>
						<p class="mb-0">{{ $section->name }}</p>
					</div>

					<div class="mb-3">
						<label
							class="form-label fw-bold">{{ __('cms.page') }}</label>
						<p class="mb-0">
							<a
								href="{{ route('cms.pages.show', $section->page) }}">
								{{ $section->page->name ?? '-' }}
							</a>
						</p>
					</div>

					<div class="mb-3">
						<label
							class="form-label fw-bold">{{ __('cms.type') }}</label>
						<p class="mb-0"><span
								class="badge bg-secondary">{{ $section->type }}</span>
						</p>
					</div>

					@if($section->template)
					<div class="mb-3">
						<label
							class="form-label fw-bold">{{ __('cms.template') }}</label>
						<p class="mb-0"><code>{{ $section->template }}</code></p>
					</div>
					@endif

					<div class="mb-3">
						<label
							class="form-label fw-bold">{{ __('cms.order') }}</label>
						<p class="mb-0">{{ $section->order }}</p>
					</div>

					<div class="mb-3">
						<label
							class="form-label fw-bold">{{ __('cms.status') }}</label>
						<p class="mb-0">
							@if($section->is_active)
							<span
								class="badge bg-success">{{ __('cms.active') }}</span>
							@else
							<span
								class="badge bg-danger">{{ __('cms.inactive') }}</span>
							@endif
						</p>
					</div>

					@if($section->settings)
					<div class="mb-3">
						<label
							class="form-label fw-bold">{{ __('cms.settings') }}</label>
						<pre class="bg-light p-2 rounded"
							style="font-size: 0.85rem;">{{ json_encode($section->settings, JSON_PRETTY_PRINT) }}</pre>
					</div>
					@endif
				</div>
			</div>

			<!-- Section Info -->
			<div class="card mt-3">
				<div class="card-header">
					<h5 class="card-title mb-0">{{ __('cms.section_info') }}</h5>
				</div>
				<div class="card-body">
					<p class="mb-1"><strong>{{ __('cms.created') }}:</strong>
						{{ $section->created_at->format('Y-m-d H:i') }}</p>
					<p class="mb-1"><strong>{{ __('cms.updated') }}:</strong>
						{{ $section->updated_at->format('Y-m-d H:i') }}</p>
					@if($section->deleted_at)
					<p class="mb-1"><strong>{{ __('cms.deleted') }}:</strong>
						{{ $section->deleted_at->format('Y-m-d H:i') }}</p>
					@endif
					<p class="mb-0"><strong>{{ __('cms.items') }}:</strong>
						<a
							href="{{ route('cms.items.index', ['section_id' => $section->id]) }}">
							{{ $section->items->count() }}
							{{ __('cms.items_count') }}
						</a>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
