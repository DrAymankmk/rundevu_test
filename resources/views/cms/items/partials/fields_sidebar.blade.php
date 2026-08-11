<div class="card">
	<div class="card-header">
		<h5 class="card-title mb-0">{{ __('cms.item_settings') }}</h5>
	</div>
	<div class="card-body">
		<div class="mb-3">
			<label class="form-label">{{ __('cms.section') }} <span
					class="text-danger">*</span></label>
			<select class="form-select @error('cms_section_id') is-invalid @enderror"
				name="cms_section_id" required>
				<option value="">{{ __('cms.select_section') }}</option>
				@foreach($sections as $section)
				<option value="{{ $section->id }}"
					{{ old('cms_section_id', isset($item) ? $item->cms_section_id : ($selectedSectionId ?? '')) == $section->id ? 'selected' : '' }}>
					{{ $section->page->name ?? '' }} - {{ $section->name }}
				</option>
				@endforeach
			</select>
			@error('cms_section_id')
			<div class="invalid-feedback">{{ $message }}</div>
			@enderror
		</div>

		<div class="mb-3">
			<label class="form-label">{{ __('cms.order') }}</label>
			<input type="number" class="form-control" name="order"
				value="{{ old('order', $item->order ?? 0) }}">
		</div>

		<div class="mb-3">
			<div class="form-check form-switch">
				<input type="checkbox" class="form-check-input" name="is_active" id="is_active"
					value="1"
					{{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
				<label class="form-check-label" for="is_active">{{ __('cms.active') }}</label>
			</div>
		</div>

		<div class="d-grid">
			<button type="submit" class="btn btn-primary">
				<i class="mdi mdi-content-save"></i>
				{{ isset($item) ? __('cms.update_item') : __('cms.save_item') }}
			</button>
		</div>
	</div>
</div>
