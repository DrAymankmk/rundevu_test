@php
$isEdit = isset($link) && $link;
$selectedType = old('type', $isEdit ? $link->type : ($type ?? 'social'));
$selectedKey = old('key', $isEdit ? $link->key : '');
@endphp

<div class="row">
	<div class="col-lg-4">
		<div class="card">
			<div class="card-header">
				<h5 class="card-title mb-0">{{ __('website_links.translations') }}</h5>
			</div>
			<div class="card-body">
				<ul class="nav nav-tabs" id="languageTabs" role="tablist">
					@foreach($languages as $index => $lang)
					<li class="nav-item" role="presentation">
						<button class="nav-link {{ $index === 0 ? 'active' : '' }}"
							id="tab-{{ $lang->code }}" data-bs-toggle="tab"
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
					$translation = $isEdit ? $link->translations->firstWhere('locale',
					$lang->code) : null;
					@endphp
					<div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
						id="content-{{ $lang->code }}" role="tabpanel">
						<div class="mb-3">
							<label class="form-label">
								{{ __('website_links.name') }}
								({{ $lang->name }})
								<span class="text-danger">*</span>
							</label>
							<input type="text"
								class="form-control @error('translations.'.$lang->code.'.title') is-invalid @enderror"
								name="translations[{{ $lang->code }}][title]"
								value="{{ old('translations.'.$lang->code.'.title', $translation->title ?? '') }}"
								dir="{{ $lang->direction ?? 'ltr' }}"
								required>
							@error('translations.'.$lang->code.'.title')
							<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>

						<div class="mb-3">
							<label class="form-label">{{ __('website_links.description') }}
								({{ $lang->name }})</label>
							<textarea class="form-control @error('translations.'.$lang->code.'.description') is-invalid @enderror"
								name="translations[{{ $lang->code }}][description]"
								rows="3"
								dir="{{ $lang->direction ?? 'ltr' }}">{{ old('translations.'.$lang->code.'.description', $translation->description ?? '') }}</textarea>
							@error('translations.'.$lang->code.'.description')
							<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>
					</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-8">
		<div class="card">
			<div class="card-header">
				<h5 class="card-title mb-0">{{ __('website_links.settings') }}</h5>
			</div>
			<div class="card-body">
				<div class="mb-3">
					<label class="form-label">{{ __('website_links.preset') }}</label>
					<select id="preset-select" class="form-select">
						<option value="">{{ __('website_links.preset_custom') }}
						</option>
						@foreach($presets as $presetKey => $preset)
						<option value="{{ $presetKey }}"
							data-type="{{ $preset['type'] }}"
							data-icon="{{ $preset['icon'] }}"
							data-color="{{ $preset['brand_color'] }}"
							{{ $selectedKey === $presetKey ? 'selected' : '' }}>
							{{ ucwords(str_replace('_', ' ', $presetKey)) }}
						</option>
						@endforeach
					</select>

				</div>

				<div class="mb-3">
					<label class="form-label">{{ __('website_links.type') }} <span
							class="text-danger">*</span></label>
					<select name="type" id="type-select"
						class="form-select @error('type') is-invalid @enderror"
						required>
						<option value="social"
							{{ $selectedType === 'social' ? 'selected' : '' }}>
							{{ __('website_links.type_social') }}</option>
						<option value="store"
							{{ $selectedType === 'store' ? 'selected' : '' }}>
							{{ __('website_links.type_store') }}</option>
					</select>
					@error('type')<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="mb-3">
					<label class="form-label">{{ __('website_links.key') }} <span
							class="text-danger">*</span></label>
					<input type="text" id="key-input"
						class="form-control @error('key') is-invalid @enderror"
						name="key" value="{{ $selectedKey }}" required>
					@error('key')<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="mb-3">
					<label class="form-label">{{ __('website_links.url') }}</label>
					<input type="text"
						class="form-control @error('url') is-invalid @enderror"
						name="url"
						value="{{ old('url', $isEdit ? $link->url : '') }}"
						placeholder="https://">
					@error('url')<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="mb-3">
					<label class="form-label">{{ __('website_links.icon') }}</label>
					<div class="input-group">
						<span class="input-group-text" id="icon-preview">
							<i
								class="{{ old('icon', $isEdit ? $link->icon : 'fas fa-share-alt') }}"></i>
						</span>
						<input type="text" id="icon-input"
							class="form-control @error('icon') is-invalid @enderror"
							name="icon"
							value="{{ old('icon', $isEdit ? $link->icon : '') }}"
							placeholder="fab fa-instagram">
					</div>
					@error('icon')<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="mb-3">
					<label
						class="form-label">{{ __('website_links.brand_color') }}</label>
					<input type="color" id="color-input"
						class="form-control form-control-color @error('brand_color') is-invalid @enderror"
						name="brand_color"
						value="{{ old('brand_color', $isEdit ? ($link->brand_color ?: '#3E66F3') : '#3E66F3') }}">
					@error('brand_color')<div class="invalid-feedback">{{ $message }}
					</div>@enderror
				</div>

				<div class="mb-3">
					<label class="form-label">{{ __('website_links.sort_order') }}</label>
					<input type="number" min="0"
						class="form-control @error('sort_order') is-invalid @enderror"
						name="sort_order"
						value="{{ old('sort_order', $isEdit ? $link->sort_order : 0) }}">
					@error('sort_order')<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="mb-3">
					<div class="form-check form-switch">
						<input type="checkbox" class="form-check-input"
							name="is_active" id="is_active" value="1"
							{{ old('is_active', $isEdit ? $link->is_active : true) ? 'checked' : '' }}>
						<label class="form-check-label"
							for="is_active">{{ __('website_links.active') }}</label>
					</div>
				</div>

				<div class="d-grid">
					<button type="submit" class="btn btn-primary">
						<i class="mdi mdi-content-save"></i>
						{{ $isEdit ? __('website_links.update') : __('website_links.save') }}
					</button>
				</div>
			</div>
		</div>
	</div>
</div>