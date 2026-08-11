@extends('layout_new.mainlayout')

@section('content')
<div class="page-wrapper" style="padding:10px">
	<!-- Page Title -->
	<div class="row">
		<div class="col-12">
			<div class="page-title-box">
				<div class="page-title-right">
					<a href="{{ route('cms.sections.index') }}" class="btn btn-secondary">
						<i class="mdi mdi-arrow-left"></i> {{ __('cms.back') }}
					</a>
				</div>
				<h4 class="page-title">{{ __('cms.edit_section') }}: {{ $section->name }}</h4>
			</div>
		</div>
	</div>

	<form action="{{ route('cms.sections.update', $section) }}" method="POST" enctype="multipart/form-data">
		@csrf
		@method('PUT')

		<div class="row">
			<!-- Main Content -->
			<div class="col-lg-8">
				<div class="card">
					<div class="card-header">
						<h5 class="card-title mb-0">{{ __('cms.translations') }}
						</h5>
					</div>
					<div class="card-body">
						<!-- Language Tabs -->
						<ul class="nav nav-tabs" id="languageTabs" role="tablist">
							@foreach($languages as $index => $lang)
							<li class="nav-item" role="presentation">
								<button class="nav-link {{ $index === 0 ? 'active' : '' }}"
									id="tab-{{ $lang->code }}"
									data-bs-toggle="tab"
									data-bs-target="#content-{{ $lang->code }}"
									type="button" role="tab">
									{{ $lang->flag ?? '' }}
									{{ $lang->name }}
								</button>
							</li>
							@endforeach
						</ul>

						<div class="tab-content pt-3" id="languageTabsContent">
							@foreach($languages as $index => $lang)
							@php
							$translation =
							$section->translations->where('locale',
							$lang->code)->first();
							@endphp
							<div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
								id="content-{{ $lang->code }}"
								role="tabpanel">

								<div class="mb-3">
									<label class="form-label">{{ __('cms.title') }}
										({{ $lang->name }})</label>
									<input type="text"
										class="form-control"
										name="translations[{{ $lang->code }}][title]"
										value="{{ old('translations.'.$lang->code.'.title', $translation->title ?? '') }}"
										dir="{{ $lang->direction }}">
								</div>

								<div class="mb-3">
									<label class="form-label">{{ __('cms.subtitle') }}
										({{ $lang->name }})</label>
									<input type="text"
										class="form-control"
										name="translations[{{ $lang->code }}][subtitle]"
										value="{{ old('translations.'.$lang->code.'.subtitle', $translation->subtitle ?? '') }}"
										dir="{{ $lang->direction }}">
								</div>

								@include('components.rich-text-editor',
								[
								'inputId' => 'section_description_' .
								$lang->code,
								'inputName' => 'translations[' .
								$lang->code . '][description]',
								'label' => __('cms.description') . ' ('
								.
								$lang->name . ')',
								'value' =>
								old('translations.'.$lang->code.'.description',
								$translation->description ?? ''),
								'direction' => $lang->direction,
								'placeholder' => __(
								'cms.enter_description')
								])

								<!-- Image Upload Fields for this Language -->
								<hr class="my-3">
								<h6 class="mb-3">{{ __('cms.images') }}
									({{ $lang->name }})</h6>

								@include('components.image-upload',
								[
								'inputId' => 'section_image_' .
								$lang->code,
								'inputName' => 'translations[' .
								$lang->code . '][image]',
								'collection' => 'images_' . $lang->code,
								'label' => __('cms.main_image'),
								'existingImage' =>
								$section->getFirstMediaUrl('images_' .
								$lang->code)
								])
							</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>

			<!-- Sidebar -->
			<div class="col-lg-4">
				<div class="card">
					<div class="card-header">
						<h5 class="card-title mb-0">{{ __('cms.section_settings') }}
						</h5>
					</div>
					<div class="card-body">
						<div class="mb-3">
							<label class="form-label">{{ __('cms.page') }}
								<span
									class="text-danger">*</span></label>
							<select class="form-select @error('cms_page_id') is-invalid @enderror"
								name="cms_page_id" required>
								<option value="">
									{{ __('cms.select_page') }}
								</option>
								@foreach($pages as $page)
								<option value="{{ $page->id }}"
									{{ old('cms_page_id', $section->cms_page_id) == $page->id ? 'selected' : '' }}>
									{{ $page->name }}
								</option>
								@endforeach
							</select>
							@error('cms_page_id')
							<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>

						<div class="mb-3">
							<label class="form-label">{{ __('cms.internal_name') }}
								<span
									class="text-danger">*</span></label>
							<input type="text"
								class="form-control @error('name') is-invalid @enderror"
								name="name"
								value="{{ old('name', $section->name) }}"
								required>
							@error('name')
							<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>

						<div class="mb-3">
							<label class="form-label">{{ __('cms.type') }}
								<span
									class="text-danger">*</span></label>
							<select class="form-select @error('type') is-invalid @enderror"
								name="type" required>
								<option value="default"
									{{ old('type', $section->type) == 'default' ? 'selected' : '' }}>
									{{ __('cms.default') }}
								</option>
								<option value="hero"
									{{ old('type', $section->type) == 'hero' ? 'selected' : '' }}>
									{{ __('cms.hero') }}
								</option>
								<option value="gallery"
									{{ old('type', $section->type) == 'gallery' ? 'selected' : '' }}>
									{{ __('cms.gallery') }}
								</option>
								<option value="testimonial"
									{{ old('type', $section->type) == 'testimonial' ? 'selected' : '' }}>
									{{ __('cms.testimonial') }}
								</option>
								<option value="features"
									{{ old('type', $section->type) == 'features' ? 'selected' : '' }}>
									{{ __('cms.features') }}
								</option>
								<option value="cta"
									{{ old('type', $section->type) == 'cta' ? 'selected' : '' }}>
									{{ __('cms.call_to_action') }}
								</option>
								<option value="content"
									{{ old('type', $section->type) == 'content' ? 'selected' : '' }}>
									{{ __('cms.content') }}
								</option>
							</select>
							@error('type')
							<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>

						<div class="mb-3">
							<label
								class="form-label">{{ __('cms.template') }}</label>
							<input type="text" class="form-control"
								name="template"
								value="{{ old('template', $section->template) }}"
								placeholder="sections.hero">
							<small
								class="text-muted">{{ __('cms.blade_template_name') }}</small>
						</div>

						<div class="mb-3">
							<label
								class="form-label">{{ __('cms.order') }}</label>
							<input type="number" class="form-control"
								name="order"
								value="{{ old('order', $section->order) }}">
						</div>

						<div class="mb-3">
							<div class="form-check form-switch">
								<input type="checkbox"
									class="form-check-input"
									name="is_active"
									id="is_active" value="1"
									{{ old('is_active', $section->is_active) ? 'checked' : '' }}>
								<label class="form-check-label"
									for="is_active">{{ __('cms.active') }}</label>
							</div>
						</div>

						<div class="d-grid">
							<button type="submit" class="btn btn-primary">
								<i class="mdi mdi-content-save"></i>
								{{ __('cms.update_section') }}
							</button>
						</div>
					</div>
				</div>

				<!-- Section Info -->
				<div class="card mt-3">
					<div class="card-header">
						<h5 class="card-title mb-0">{{ __('cms.section_info') }}
						</h5>
					</div>
					<div class="card-body">
						<p class="mb-1"><strong>{{ __('cms.page') }}:</strong>
							{{ $section->page->name ?? '-' }}</p>
						<p class="mb-1"><strong>{{ __('cms.created') }}:</strong>
							{{ $section->created_at->format('Y-m-d H:i') }}
						</p>
						<p class="mb-1"><strong>{{ __('cms.updated') }}:</strong>
							{{ $section->updated_at->format('Y-m-d H:i') }}
						</p>
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


		<!-- Gallery Section -->
		<div class="col-lg-12 mt-3">
			<div class="card">
				<div class="card-header">
					<h5 class="card-title mb-0">{{ __('cms.gallery') }}</h5>
				</div>
				<div class="card-body">
					@include('components.gallery-upload', [
					'inputId' => 'section_gallery',
					'inputName' => 'gallery',
					'collection' => 'gallery',
					'label' => __('cms.gallery_images'),
					'existingImages' => $section->getMedia('gallery')
					])
				</div>
			</div>
		</div>

	</form>
</div>
@endsection
