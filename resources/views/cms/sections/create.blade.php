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
				<h4 class="page-title">{{ __('cms.create_section') }}</h4>
			</div>
		</div>
	</div>

	<form action="{{ route('cms.sections.store') }}" method="POST" enctype="multipart/form-data">
		@csrf

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
							<div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
								id="content-{{ $lang->code }}"
								role="tabpanel">

								<div class="mb-3">
									<label class="form-label">{{ __('cms.title') }}
										({{ $lang->name }})</label>
									<input type="text"
										class="form-control"
										name="translations[{{ $lang->code }}][title]"
										value="{{ old('translations.'.$lang->code.'.title') }}"
										dir="{{ $lang->direction }}">
								</div>

								<div class="mb-3">
									<label class="form-label">{{ __('cms.subtitle') }}
										({{ $lang->name }})</label>
									<input type="text"
										class="form-control"
										name="translations[{{ $lang->code }}][subtitle]"
										value="{{ old('translations.'.$lang->code.'.subtitle') }}"
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
								old('translations.'.$lang->code.'.description'),
								'direction' => $lang->direction,
								'placeholder' =>
								__('cms.enter_description')
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
								'existingImage' => null
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
									{{ old('cms_page_id', $selectedPageId) == $page->id ? 'selected' : '' }}>
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
								name="name" value="{{ old('name') }}"
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
									{{ old('type') == 'default' ? 'selected' : '' }}>
									{{ __('cms.default') }}
								</option>
								<option value="hero"
									{{ old('type') == 'hero' ? 'selected' : '' }}>
									{{ __('cms.hero') }}</option>
								<option value="gallery"
									{{ old('type') == 'gallery' ? 'selected' : '' }}>
									{{ __('cms.gallery') }}
								</option>
								<option value="testimonial"
									{{ old('type') == 'testimonial' ? 'selected' : '' }}>
									{{ __('cms.testimonial') }}
								</option>
								<option value="features"
									{{ old('type') == 'features' ? 'selected' : '' }}>
									{{ __('cms.features') }}
								</option>
								<option value="cta"
									{{ old('type') == 'cta' ? 'selected' : '' }}>
									{{ __('cms.call_to_action') }}
								</option>
								<option value="content"
									{{ old('type') == 'content' ? 'selected' : '' }}>
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
								value="{{ old('template') }}"
								placeholder="sections.hero">
							<small
								class="text-muted">{{ __('cms.blade_template_name') }}</small>
						</div>

						<div class="mb-3">
							<label
								class="form-label">{{ __('cms.order') }}</label>
							<input type="number" class="form-control"
								name="order"
								value="{{ old('order', 0) }}">
						</div>

						<div class="mb-3">
							<div class="form-check form-switch">
								<input type="checkbox"
									class="form-check-input"
									name="is_active"
									id="is_active" value="1"
									{{ old('is_active', true) ? 'checked' : '' }}>
								<label class="form-check-label"
									for="is_active">{{ __('cms.active') }}</label>
							</div>
						</div>

						<div class="d-grid">
							<button type="submit" class="btn btn-primary">
								<i class="mdi mdi-content-save"></i>
								{{ __('cms.save_section') }}
							</button>
						</div>
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
					'existingImages' => collect([])
					])
				</div>
			</div>
		</div>
	</form>
</div>
@endsection
