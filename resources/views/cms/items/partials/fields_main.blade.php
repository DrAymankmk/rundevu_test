<div class="card">
	<div class="card-header">
		<h5 class="card-title mb-0">{{ __('cms.translations') }}</h5>
	</div>
	<div class="card-body">
		<!-- Language Tabs -->
		<ul class="nav nav-tabs" id="languageTabs" role="tablist">
			@foreach($languages as $index => $lang)
			<li class="nav-item" role="presentation">
				<button class="nav-link {{ $index === 0 ? 'active' : '' }}"
					id="tab-{{ $lang->code }}" data-bs-toggle="tab"
					data-bs-target="#content-{{ $lang->code }}" type="button" role="tab">
					{{ $lang->flag ?? '' }} {{ $lang->name }}
				</button>
			</li>
			@endforeach
		</ul>

		<div class="tab-content pt-3" id="languageTabsContent">
			@foreach($languages as $index => $lang)
			@php
			$translation = isset($item) ? $item->translations->where('locale', $lang->code)->first() :
			null;
			@endphp
			<div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
				id="content-{{ $lang->code }}" role="tabpanel">

				<div class="mb-3">
					<label class="form-label">
						{{ __('cms.title') }} ({{ $lang->name }})
						<span class="text-danger">*</span>
					</label>
					<input type="text"
						class="form-control @error('translations.'.$lang->code.'.title') is-invalid @enderror"
						name="translations[{{ $lang->code }}][title]"
						value="{{ old('translations.'.$lang->code.'.title', $translation->title ?? '') }}"
						dir="{{ $lang->direction }}" required>
					@error('translations.'.$lang->code.'.title')
					<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="mb-3">
					<label class="form-label">{{ __('cms.sub_title') }}
						({{ $lang->name }})</label>
					<input type="text" class="form-control"
						name="translations[{{ $lang->code }}][sub_title]"
						value="{{ old('translations.'.$lang->code.'.sub_title', $translation->sub_title ?? '') }}"
						dir="{{ $lang->direction }}">
				</div>

				@include('backend.components.rich-text-editor', [
				'inputId' => 'item_content_' . $lang->code,
				'inputName' => 'translations[' . $lang->code . '][content]',
				'label' => __('cms.content') . ' (' . $lang->name . ')',
				'value' => old('translations.'.$lang->code.'.content', $translation->content ??
				''),
				'direction' => $lang->direction,
				'placeholder' => __('cms.enter_content')
				])

				<div class="mb-3">
					<label class="form-label">{{ __('cms.icon') }}
						({{ $lang->name }})</label>
					@include('backend.components.icon-picker', [
					'inputId' => 'icon_' . $lang->code,
					'inputName' => 'translations[' . $lang->code . '][icon]',
					'value' => old('translations.' . $lang->code . '.icon',
					$translation->icon ?? '')
					])
				</div>

				<!-- Image Upload Fields for this Language -->
				<hr class="my-3">
				<h6 class="mb-3">{{ __('cms.images') }} ({{ $lang->name }})</h6>

				@include('backend.components.image-upload', [
				'inputId' => 'item_image_' . $lang->code,
				'inputName' => 'translations[' . $lang->code . '][image]',
				'collection' => 'images_' . $lang->code,
				'label' => __('cms.main_image'),
				'existingImage' => isset($item) ? $item->getFirstMediaUrl('images_' .
				$lang->code) : null
				])

				@include('backend.components.image-upload', [
				'inputId' => 'item_icon_' . $lang->code,
				'inputName' => 'translations[' . $lang->code . '][icon_image]',
				'collection' => 'icons_' . $lang->code,
				'label' => __('cms.icon_image'),
				'existingImage' => isset($item) ? $item->getFirstMediaUrl('icons_' .
				$lang->code) : null
				])
			</div>
			@endforeach
		</div>
	</div>
</div>

<!-- Gallery Section -->
<div class="card mt-3">
	<div class="card-header">
		<h5 class="card-title mb-0">{{ __('cms.gallery') }}</h5>
	</div>
	<div class="card-body">
		@include('backend.components.gallery-upload', [
		'inputId' => 'item_gallery',
		'inputName' => 'gallery',
		'collection' => 'gallery',
		'label' => __('cms.gallery_images'),
		'existingImages' => isset($item) ? $item->getMedia('gallery') : collect([])
		])
	</div>
</div>
