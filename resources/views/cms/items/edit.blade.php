@extends('layout_new.mainlayout')

@section('content')
<div class="page-wrapper" style="padding:10px">
	<!-- Page Title -->
	<div class="row">
		<div class="col-12">
			<div class="page-title-box">
				<div class="page-title-right">
					<a href="{{ route('cms.items.index') }}" class="btn btn-secondary">
						<i class="mdi mdi-arrow-left"></i> {{ __('cms.back') }}
					</a>
				</div>
				<h4 class="page-title">{{ __('cms.edit_item') }}</h4>
			</div>
		</div>
	</div>

	<form action="{{ route('cms.items.update', $item) }}" method="POST" enctype="multipart/form-data">
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
							$item->translations->where('locale',
							$lang->code)->first();
							@endphp
							<div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
								id="content-{{ $lang->code }}"
								role="tabpanel">

								<div class="mb-3">
									<label class="form-label">
										{{ __('cms.title') }}
										({{ $lang->name }})
										<span
											class="text-danger">*</span>
									</label>
									<input type="text"
										class="form-control @error('translations.'.$lang->code.'.title') is-invalid @enderror"
										name="translations[{{ $lang->code }}][title]"
										value="{{ old('translations.'.$lang->code.'.title', $translation->title ?? '') }}"
										dir="{{ $lang->direction }}">
									@error('translations.'.$lang->code.'.title')
									<div class="invalid-feedback">
										{{ $message }}</div>
									@enderror
								</div>

								<div class="mb-3">
									<label class="form-label">{{ __('cms.sub_title') }}
										({{ $lang->name }})</label>
									<input type="text"
										class="form-control"
										name="translations[{{ $lang->code }}][sub_title]"
										value="{{ old('translations.'.$lang->code.'.sub_title', $translation->sub_title ?? '') }}"
										dir="{{ $lang->direction }}">
								</div>

								@include('components.rich-text-editor',
								[
								'inputId' => 'item_content_' .
								$lang->code,
								'inputName' => 'translations[' .
								$lang->code . '][content]',
								'label' => __('cms.content') . ' (' .
								$lang->name . ')',
								'value' =>
								old('translations.'.$lang->code.'.content',
								$translation->content ?? ''),
								'direction' => $lang->direction,
								'placeholder' => __('cms.enter_content')
								])

								<div class="mb-3">
									<label class="form-label">{{ __('Icon') }}
										({{ $lang->name }})</label>
									@include('components.icon-picker',
									[
									'inputId' => 'icon_' .
									$lang->code,
									'inputName' => 'translations['
									. $lang->code . '][icon]',
									'value' => old('translations.'
									. $lang->code . '.icon',
									$translation->icon ?? '')
									])
								</div>

								<!-- Image Upload Fields for this Language -->
								<hr class="my-3">
								<h6 class="mb-3">{{ __('cms.images') }}
									({{ $lang->name }})</h6>

								@include('components.image-upload',
								[
								'inputId' => 'item_image_' .
								$lang->code,
								'inputName' => 'translations[' .
								$lang->code . '][image]',
								'collection' => 'images_' . $lang->code,
								'label' => __('cms.main_image'),
								'existingImage' =>
								$item->getFirstMediaUrl('images_' .
								$lang->code)
								])

								@include('components.image-upload',
								[
								'inputId' => 'item_icon_' . $lang->code,
								'inputName' => 'translations[' .
								$lang->code . '][icon_image]',
								'collection' => 'icons_' . $lang->code,
								'label' => __('cms.icon_image'),
								'existingImage' =>
								$item->getFirstMediaUrl('icons_' .
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
						<h5 class="card-title mb-0">{{ __('cms.item_settings') }}
						</h5>
					</div>
					<div class="card-body">
						<div class="mb-3">
							<label
								class="form-label">{{ __('cms.slug') }}</label>
							<input type="text"
								class="form-control @error('slug') is-invalid @enderror"
								name="slug"
								value="{{ old('slug', $item->slug) }}">
							@error('slug')
							<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>

						<div class="mb-3">
							<label class="form-label">{{ __('cms.section') }}
							</label>
							<select class="form-select @error('cms_section_id') is-invalid @enderror"
								name="cms_section_id">
								<option value="">
									{{ __('cms.select_section') }}
								</option>
								@foreach($sections as $section)
								<option value="{{ $section->id }}"
									{{ old('cms_section_id', $item->cms_section_id) == $section->id ? 'selected' : '' }}>
									{{ $section->page->name ?? '' }}
									- {{ $section->name }}
								</option>
								@endforeach
							</select>
							@error('cms_section_id')
							<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>

						<div class="mb-3">
							<label
								class="form-label">{{ __('cms.order') }}</label>
							<input type="number" class="form-control"
								name="order"
								value="{{ old('order', $item->order) }}">
						</div>

						<div class="mb-3">
							<div class="form-check form-switch">
								<input type="checkbox"
									class="form-check-input"
									name="is_active"
									id="is_active" value="1"
									{{ old('is_active', $item->is_active) ? 'checked' : '' }}>
								<label class="form-check-label"
									for="is_active">{{ __('cms.active') }}</label>
							</div>
						</div>

						<div class="d-grid">
							<button type="submit" class="btn btn-primary">
								<i class="mdi mdi-content-save"></i>
								{{ __('cms.update_item') }}
							</button>
						</div>
					</div>
				</div>

				<!-- Item Info -->
				<div class="card mt-3">
					<div class="card-header">
						<h5 class="card-title mb-0">{{ __('cms.item_info') }}</h5>
					</div>
					<div class="card-body">
						<p class="mb-1"><strong>{{ __('cms.section') }}:</strong>
							{{ $item->section->name ?? '-' }}</p>
						<p class="mb-1"><strong>{{ __('cms.created') }}:</strong>
							{{ $item->created_at->format('Y-m-d H:i') }}</p>
						<p class="mb-0"><strong>{{ __('cms.updated') }}:</strong>
							{{ $item->updated_at->format('Y-m-d H:i') }}</p>
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
					'inputId' => 'item_gallery',
					'inputName' => 'gallery',
					'collection' => 'gallery',
					'label' => __('cms.gallery_images'),
					'existingImages' => $item->getMedia('gallery')
					])
				</div>
			</div>
		</div>

	</form>
</div>
@endsection