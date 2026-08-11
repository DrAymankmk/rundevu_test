<div class="card">
	<div class="card-header">
		<h5 class="card-title mb-0">{{ __('cms.section_settings') }}</h5>
	</div>
	<div class="card-body">
		<div class="mb-3">
			<label class="form-label">{{ __('cms.page') }} <span class="text-danger">*</span></label>
			<select class="form-select @error('cms_page_id') is-invalid @enderror" name="cms_page_id"
				required>
				<option value="">{{ __('cms.select_page') }}</option>
				@foreach($pages as $page)
				<option value="{{ $page->id }}"
					{{ old('cms_page_id', isset($section) ? $section->cms_page_id : ($selectedPageId ?? '')) == $page->id ? 'selected' : '' }}>
					{{ $page->name }}
				</option>
				@endforeach
			</select>
			@error('cms_page_id')
			<div class="invalid-feedback">{{ $message }}</div>
			@enderror
		</div>

		<div class="mb-3">
			<label class="form-label">{{ __('cms.internal_name') }} <span
					class="text-danger">*</span></label>
			<input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
				value="{{ old('name', $section->name ?? '') }}" required>
			@error('name')
			<div class="invalid-feedback">{{ $message }}</div>
			@enderror
		</div>

		<div class="mb-3">
			<label class="form-label">{{ __('cms.type') }} <span class="text-danger">*</span></label>
			<select class="form-select @error('type') is-invalid @enderror" name="type" required>
				<option value="default"
					{{ old('type', $section->type ?? '') == 'default' ? 'selected' : '' }}>
					{{ __('cms.default') }}</option>
				<option value="hero"
					{{ old('type', $section->type ?? '') == 'hero' ? 'selected' : '' }}>
					{{ __('cms.hero') }}</option>
				<option value="gallery"
					{{ old('type', $section->type ?? '') == 'gallery' ? 'selected' : '' }}>
					{{ __('cms.gallery') }}</option>
				<option value="testimonial"
					{{ old('type', $section->type ?? '') == 'testimonial' ? 'selected' : '' }}>
					{{ __('cms.testimonial') }}</option>
				<option value="features"
					{{ old('type', $section->type ?? '') == 'features' ? 'selected' : '' }}>
					{{ __('cms.features') }}</option>
				<option value="cta"
					{{ old('type', $section->type ?? '') == 'cta' ? 'selected' : '' }}>
					{{ __('cms.call_to_action') }}</option>
				<option value="content"
					{{ old('type', $section->type ?? '') == 'content' ? 'selected' : '' }}>
					{{ __('cms.content') }}</option>
			</select>
			@error('type')
			<div class="invalid-feedback">{{ $message }}</div>
			@enderror
		</div>

		<div class="mb-3">
			<label class="form-label">{{ __('cms.template') }}</label>
			<input type="text" class="form-control" name="template"
				value="{{ old('template', $section->template ?? '') }}"
				placeholder="sections.hero">
			<small class="text-muted">{{ __('cms.blade_template_name') }}</small>
		</div>

		<div class="mb-3">
			<label class="form-label">{{ __('cms.order') }}</label>
			<input type="number" class="form-control" name="order"
				value="{{ old('order', $section->order ?? 0) }}">
		</div>

		<div class="mb-3">
			<div class="form-check form-switch">
				<input type="checkbox" class="form-check-input" name="is_active" id="is_active"
					value="1"
					{{ old('is_active', $section->is_active ?? true) ? 'checked' : '' }}>
				<label class="form-check-label" for="is_active">{{ __('cms.active') }}</label>
			</div>
		</div>

		<div class="d-grid">
			<button type="submit" class="btn btn-primary">
				<i class="mdi mdi-content-save"></i>
				{{ isset($section) ? __('cms.update_section') : __('cms.save_section') }}
			</button>
		</div>
	</div>
</div>
