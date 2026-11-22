@php
	$isEdit = isset($link) && $link;
	$isGroup = $isEdit && $link->isGroup();
@endphp

<div class="row">
	<div class="col-lg-8">
		<div class="card">
			<div class="card-header">
				<h5 class="card-title mb-0">{{ __('sitemap_links.settings') }}</h5>
			</div>
			<div class="card-body">
				<div class="mb-3">
					<label class="form-label">{{ __('sitemap_links.name') }} <span class="text-danger">*</span></label>
					<input type="text"
						class="form-control @error('title') is-invalid @enderror"
						name="title"
						value="{{ old('title', $isEdit ? $link->title : '') }}"
						required>
					@error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
				</div>

				@if($isGroup)
					<div class="mb-3">
						<label class="form-label">{{ __('sitemap_links.group_key') }}</label>
						<input type="text" class="form-control" value="{{ $link->group_key }}" disabled>
						<small class="text-muted">{{ __('sitemap_links.no_path') }}</small>
					</div>
				@else
					<div class="mb-3">
						<label class="form-label">{{ __('sitemap_links.path') }} <span class="text-danger">*</span></label>
						<input type="text"
							class="form-control @error('path') is-invalid @enderror"
							name="path"
							value="{{ old('path', $isEdit ? $link->path : '/') }}"
							placeholder="/about"
							required>
						<small class="text-muted">{{ __('sitemap_links.path_hint') }}</small>
						@error('path')<div class="invalid-feedback">{{ $message }}</div>@enderror
					</div>
				@endif

				<div class="row">
					<div class="col-md-6 mb-3">
						<label class="form-label">{{ __('sitemap_links.changefreq') }} <span class="text-danger">*</span></label>
						<select name="changefreq" class="form-select @error('changefreq') is-invalid @enderror" required>
							@foreach(\App\Models\SitemapLink::CHANGEFREQ_OPTIONS as $option)
								<option value="{{ $option }}"
									{{ old('changefreq', $isEdit ? $link->changefreq : 'weekly') === $option ? 'selected' : '' }}>
									{{ $option }}
								</option>
							@endforeach
						</select>
						@error('changefreq')<div class="invalid-feedback">{{ $message }}</div>@enderror
					</div>
					<div class="col-md-6 mb-3">
						<label class="form-label">{{ __('sitemap_links.priority') }} <span class="text-danger">*</span></label>
						<input type="number" step="0.1" min="0" max="1"
							class="form-control @error('priority') is-invalid @enderror"
							name="priority"
							value="{{ old('priority', $isEdit ? number_format((float) $link->priority, 1, '.', '') : '0.5') }}"
							required>
						<small class="text-muted">{{ __('sitemap_links.priority_hint') }}</small>
						@error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
					</div>
				</div>

				<div class="row">
					<div class="col-md-6 mb-3">
						<label class="form-label">{{ __('sitemap_links.lastmod') }}</label>
						<input type="datetime-local"
							class="form-control @error('lastmod') is-invalid @enderror"
							name="lastmod"
							value="{{ old('lastmod', $isEdit && $link->lastmod ? $link->lastmod->format('Y-m-d\TH:i') : '') }}">
						<small class="text-muted">{{ __('sitemap_links.lastmod_hint') }}</small>
						@error('lastmod')<div class="invalid-feedback">{{ $message }}</div>@enderror
					</div>
					<div class="col-md-6 mb-3">
						<label class="form-label">{{ __('sitemap_links.sort_order') }}</label>
						<input type="number" min="0"
							class="form-control @error('sort_order') is-invalid @enderror"
							name="sort_order"
							value="{{ old('sort_order', $isEdit ? $link->sort_order : 0) }}">
						@error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
					</div>
				</div>

				<div class="mb-3">
					<div class="form-check form-switch">
						<input type="checkbox" class="form-check-input" name="include_alternates" id="include_alternates" value="1"
							{{ old('include_alternates', $isEdit ? $link->include_alternates : true) ? 'checked' : '' }}>
						<label class="form-check-label" for="include_alternates">{{ __('sitemap_links.include_alternates') }}</label>
					</div>
					<small class="text-muted">{{ __('sitemap_links.include_alternates_hint') }}</small>
				</div>

				<div class="mb-3" id="x-default-locale-wrap">
					<label class="form-label">{{ __('sitemap_links.x_default_locale') }}</label>
					<select name="x_default_locale"
						class="form-select @error('x_default_locale') is-invalid @enderror">
						@foreach(\App\Models\SitemapLink::xDefaultOptions() as $value => $label)
							<option value="{{ $value }}"
								{{ old('x_default_locale', $isEdit ? ($link->x_default_locale ?: 'auto') : 'auto') === $value ? 'selected' : '' }}>
								{{ $label }}
							</option>
						@endforeach
					</select>
					<small class="text-muted">{{ __('sitemap_links.x_default_locale_hint') }}</small>
					@error('x_default_locale')<div class="invalid-feedback">{{ $message }}</div>@enderror
				</div>

				<div class="mb-3">
					<div class="form-check form-switch">
						<input type="checkbox" class="form-check-input" name="is_active" id="is_active" value="1"
							{{ old('is_active', $isEdit ? $link->is_active : true) ? 'checked' : '' }}>
						<label class="form-check-label" for="is_active">{{ __('sitemap_links.active') }}</label>
					</div>
				</div>

				<div class="d-grid">
					<button type="submit" class="btn btn-primary">
						<i class="mdi mdi-content-save"></i>
						{{ $isEdit ? __('sitemap_links.update') : __('sitemap_links.save') }}
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
