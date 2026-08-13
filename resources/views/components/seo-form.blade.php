@php
$languages = $languages ?? collect();
$seo = $seo ?? null;
$prefix = $prefix ?? 'seo';
$formAction = $formAction ?? null;
$submitLabel = $submitLabel ?? null;
@endphp

<div class="card mt-3">
	<div class="card-header d-flex align-items-center justify-content-between">
		<h5 class="card-title mb-0">{{ __('seo.settings') }}</h5>
		@if($formAction)
		<span class="text-muted small">{{ __('seo.save_seo') }}</span>
		@endif
	</div>
	<div class="card-body">
		@if($formAction)
		<form action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
			@csrf
			@endif
			@php
			$robotsValue = old($prefix . '.robots', $seo?->robots ?? 'index,follow');
			$ogTypeValue = old($prefix . '.og_type', $seo?->og_type ?? 'website');
			@endphp

			<div class="row mb-3">
				<div class="col-md-6">
					<label class="form-label">{{ __('seo.canonical_url') }}</label>
					<input type="url" class="form-control"
						name="{{ $prefix }}[canonical_url]"
						value="{{ old($prefix.'.canonical_url', $seo?->canonical_url) }}">
				</div>
				<div class="col-md-3">
					<label class="form-label">{{ __('seo.robots') }}</label>
					<select class="form-control" name="{{ $prefix }}[robots]">
						@foreach (['index,follow', 'noindex,nofollow',
						'index,nofollow', 'noindex,follow'] as $robots)
						<option value="{{ $robots }}"
							@selected($robotsValue===$robots)>
							{{ $robots }}
						</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-3">
					<label class="form-label">{{ __('seo.og_type') }}</label>
					<select class="form-control" name="{{ $prefix }}[og_type]">
						@foreach (['website', 'article', 'profile'] as $type)
						<option value="{{ $type }}" @selected($ogTypeValue===$type)>
							{{ ucfirst($type) }}
						</option>
						@endforeach
					</select>
				</div>
			</div>

			<div class="form-check form-switch mb-3">
				<input type="hidden" name="{{ $prefix }}[is_active]" value="0">
				<input type="checkbox" class="form-check-input" id="seo_is_active_{{ $prefix }}"
					name="{{ $prefix }}[is_active]" value="1"
					{{ old($prefix.'.is_active', $seo?->is_active ?? true) ? 'checked' : '' }}>
				<label class="form-check-label"
					for="seo_is_active_{{ $prefix }}">{{ __('seo.active') }}</label>
			</div>

			<div class="mb-3">
				<label class="form-label">{{ __('seo.og_image_file') }}</label>
				<input type="file" class="form-control" name="{{ $prefix }}[og_image_file]"
					accept="image/*">
				@if($seo && $seo->getFirstMediaUrl('og_image'))
				<div class="mt-2">
					<small
						class="text-muted d-block mb-1">{{ __('seo.current_og_image') }}</small>
					<img src="{{ $seo->getFirstMediaUrl('og_image') }}" alt="OG"
						class="img-thumbnail" style="max-height: 80px;">
				</div>
				@endif
			</div>

			<ul class="nav nav-tabs" role="tablist">
				@foreach($languages as $index => $lang)
				<li class="nav-item">
					<button class="nav-link {{ $index === 0 ? 'active' : '' }}"
						data-bs-toggle="tab"
						data-bs-target="#seo-{{ $prefix }}-{{ $lang->code }}"
						type="button">
						{{ $lang->flag ?? '' }} {{ $lang->name }}
					</button>
				</li>
				@endforeach
			</ul>

			<div class="tab-content pt-3">
				@foreach($languages as $index => $lang)
				@php
				$tr = $seo?->translations?->where('locale', $lang->code)->first();
				@endphp
				<div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
					id="seo-{{ $prefix }}-{{ $lang->code }}">
					<div class="mb-3">
						<label class="form-label">{{ __('seo.meta_title') }}
							({{ $lang->name }})</label>
						<input type="text" class="form-control"
							name="{{ $prefix }}[translations][{{ $lang->code }}][meta_title]"
							value="{{ old($prefix.'.translations.'.$lang->code.'.meta_title', $tr?->meta_title) }}"
							dir="{{ $lang->direction }}">
						<small
							class="text-muted">{{ __('seo.meta_title_hint') }}</small>
					</div>

					<div class="mb-3">
						<label class="form-label">{{ __('seo.meta_description') }}
							({{ $lang->name }})</label>
						<textarea class="form-control" rows="3"
							name="{{ $prefix }}[translations][{{ $lang->code }}][meta_description]"
							dir="{{ $lang->direction }}">{{ old($prefix.'.translations.'.$lang->code.'.meta_description', $tr?->meta_description) }}</textarea>
						<small
							class="text-muted">{{ __('seo.meta_description_hint') }}</small>
					</div>

					<div class="mb-3">
						<label class="form-label">{{ __('seo.meta_keywords') }}
							({{ $lang->name }})</label>
						<input type="text" class="form-control"
							name="{{ $prefix }}[translations][{{ $lang->code }}][meta_keywords]"
							value="{{ old($prefix.'.translations.'.$lang->code.'.meta_keywords', $tr?->meta_keywords) }}"
							dir="{{ $lang->direction }}">
					</div>

					<div class="row">
						<div class="col-md-6 mb-3">
							<label
								class="form-label">{{ __('seo.og_title') }}</label>
							<input type="text" class="form-control"
								name="{{ $prefix }}[translations][{{ $lang->code }}][og_title]"
								value="{{ old($prefix.'.translations.'.$lang->code.'.og_title', $tr?->og_title) }}">
						</div>
						<div class="col-md-6 mb-3">
							<label
								class="form-label">{{ __('seo.twitter_title') }}</label>
							<input type="text" class="form-control"
								name="{{ $prefix }}[translations][{{ $lang->code }}][twitter_title]"
								value="{{ old($prefix.'.translations.'.$lang->code.'.twitter_title', $tr?->twitter_title) }}">
						</div>
					</div>

					<div class="row">
						<div class="col-md-6 mb-3">
							<label
								class="form-label">{{ __('seo.og_description') }}</label>
							<textarea class="form-control" rows="2"
								name="{{ $prefix }}[translations][{{ $lang->code }}][og_description]">{{ old($prefix.'.translations.'.$lang->code.'.og_description', $tr?->og_description) }}</textarea>
						</div>
						<div class="col-md-6 mb-3">
							<label
								class="form-label">{{ __('seo.twitter_description') }}</label>
							<textarea class="form-control" rows="2"
								name="{{ $prefix }}[translations][{{ $lang->code }}][twitter_description]">{{ old($prefix.'.translations.'.$lang->code.'.twitter_description', $tr?->twitter_description) }}</textarea>
						</div>
					</div>

					<div class="mb-3">
						<label
							class="form-label">{{ __('seo.schema_json') }}</label>
						<textarea class="form-control font-monospace" rows="3"
							name="{{ $prefix }}[translations][{{ $lang->code }}][schema_json]"
							placeholder='{"@context":"https://schema.org","@type":"WebPage"}'>{{ old($prefix.'.translations.'.$lang->code.'.schema_json', $tr?->schema_json ? json_encode($tr->schema_json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : '') }}</textarea>
					</div>
				</div>
				@endforeach
			</div>

			@if($formAction)
			<div class="d-grid mt-3">
				<button type="submit" class="btn btn-primary">
					<i
						class="ti ti-device-floppy me-1"></i>{{ $submitLabel ?? __('seo.save_seo') }}
				</button>
			</div>
		</form>
		@endif
	</div>
</div>