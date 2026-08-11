@extends('layout_new.mainlayout')

@section('content')
<div class="page-wrapper" style="padding:10px">
	<!-- Page Title -->
	<div class="row">
		<div class="col-12">
			<div class="page-title-box">
				<div class="page-title-right">
					<a href="{{ route('cms.items.index', ['section_id' => $item->cms_section_id]) }}"
						class="btn btn-secondary">
						<i class="mdi mdi-arrow-left"></i> {{ __('cms.back') }}
					</a>
					<a href="{{ route('cms.items.edit', $item) }}"
						class="btn btn-primary">
						<i class="mdi mdi-pencil"></i> {{ __('cms.edit') }}
					</a>
				</div>
				<h4 class="page-title">{{ __('cms.item_details') }}</h4>
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
						$translation = $item->translations->where('locale',
						$lang->code)->first();
						$imageUrl = $item->getFirstMediaUrl("images_{$lang->code}")
						?: $item->getFirstMediaUrl('images');
						$iconUrl = $item->getFirstMediaUrl("icons_{$lang->code}") ?:
						$item->getFirstMediaUrl('icons');
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

							@if($translation && $translation->sub_title)
							<div class="mb-3">
								<label
									class="form-label fw-bold">{{ __('cms.sub_title') }}</label>
								<p class="mb-0"
									dir="{{ $lang->direction }}">
									{{ $translation->sub_title }}
								</p>
							</div>
							@endif

							@if($translation && $translation->content)
							<div class="mb-3">
								<label
									class="form-label fw-bold">{{ __('cms.content') }}</label>
								<div class="border p-3 rounded"
									dir="{{ $lang->direction }}">
									{!! $translation->content !!}
								</div>
							</div>
							@endif

							@if($translation && $translation->icon)
							<div class="mb-3">
								<label
									class="form-label fw-bold">{{ __('cms.icon') }}</label>
								<p class="mb-0">
									<i class="{{ $translation->icon }}"
										style="font-size: 2rem;"></i>
									<code
										class="ms-2">{{ $translation->icon }}</code>
								</p>
							</div>
							@endif

							@if($imageUrl)
							<div class="mb-3">
								<label
									class="form-label fw-bold">{{ __('cms.image') }}</label>
								<div>
									<img src="{{ $imageUrl }}"
										alt="{{ $translation->title ?? 'Item Image' }}"
										class="img-fluid rounded"
										style="max-height: 400px;">
								</div>
							</div>
							@endif

							@if($iconUrl)
							<div class="mb-3">
								<label
									class="form-label fw-bold">{{ __('cms.icon_image') }}</label>
								<div>
									<img src="{{ $iconUrl }}"
										alt="{{ $translation->title ?? 'Item Icon' }}"
										class="img-fluid rounded"
										style="max-height: 200px;">
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
			$gallery = $item->getMedia('gallery');
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
		</div>

		<!-- Sidebar -->
		<div class="col-lg-4">
			<!-- Item Settings -->
			<div class="card">
				<div class="card-header">
					<h5 class="card-title mb-0">{{ __('cms.item_settings') }}</h5>
				</div>
				<div class="card-body">
					<div class="mb-3">
						<label
							class="form-label fw-bold">{{ __('cms.slug') }}</label>
						<p class="mb-0"><code>{{ $item->slug }}</code></p>
					</div>

					<div class="mb-3">
						<label
							class="form-label fw-bold">{{ __('cms.section') }}</label>
						<p class="mb-0">
							<a
								href="{{ route('cms.sections.show', $item->section) }}">
								{{ $item->section->name ?? '-' }}
							</a>
						</p>
					</div>

					@if($item->section && $item->section->page)
					<div class="mb-3">
						<label
							class="form-label fw-bold">{{ __('cms.page') }}</label>
						<p class="mb-0">
							<a
								href="{{ route('cms.pages.show', $item->section->page) }}">
								{{ $item->section->page->name ?? '-' }}
							</a>
						</p>
					</div>
					@endif

					<div class="mb-3">
						<label
							class="form-label fw-bold">{{ __('cms.order') }}</label>
						<p class="mb-0">{{ $item->order }}</p>
					</div>

					<div class="mb-3">
						<label
							class="form-label fw-bold">{{ __('cms.status') }}</label>
						<p class="mb-0">
							@if($item->is_active)
							<span
								class="badge bg-success">{{ __('cms.active') }}</span>
							@else
							<span
								class="badge bg-danger">{{ __('cms.inactive') }}</span>
							@endif
						</p>
					</div>

					@if($item->settings)
					<div class="mb-3">
						<label
							class="form-label fw-bold">{{ __('cms.settings') }}</label>
						<pre class="bg-light p-2 rounded"
							style="font-size: 0.85rem;">{{ json_encode($item->settings, JSON_PRETTY_PRINT) }}</pre>
					</div>
					@endif
				</div>
			</div>

			<!-- Item Info -->
			<div class="card mt-3">
				<div class="card-header">
					<h5 class="card-title mb-0">{{ __('cms.item_info') }}</h5>
				</div>
				<div class="card-body">
					<p class="mb-1"><strong>{{ __('cms.created') }}:</strong>
						{{ $item->created_at->format('Y-m-d H:i') }}</p>
					<p class="mb-1"><strong>{{ __('cms.updated') }}:</strong>
						{{ $item->updated_at->format('Y-m-d H:i') }}</p>
					@if($item->deleted_at)
					<p class="mb-1"><strong>{{ __('cms.deleted') }}:</strong>
						{{ $item->deleted_at->format('Y-m-d H:i') }}</p>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
