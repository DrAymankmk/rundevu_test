@php
/** @var \Illuminate\Support\Collection<int, \App\Models\CmsLanguage> $languages */
	$link = $link ?? null;
	@endphp
	<div class="row g-2 mb-2 align-items-end border rounded p-2 bg-light link-block" data-role="link-row">
		@if($link)
		<input type="hidden" name="{{ $namePrefix }}[id]" value="{{ $link->id }}">
		@endif
		<input type="hidden" name="{{ $namePrefix }}[is_active]" value="0">
		<div class="col-md-2">
			<label class="form-label small mb-0">{{ __('cms.label') }}</label>
			<input type="text" class="form-control form-control-sm" name="{{ $namePrefix }}[name]"
				value="{{ old($namePrefix.'.name', $link?->name ?? '') }}">
		</div>
		<div class="col-md-2">
			<label class="form-label small mb-0">{{ __('cms.url') }}</label>
			<input type="text" class="form-control form-control-sm" name="{{ $namePrefix }}[link]"
				value="{{ old($namePrefix.'.link', $link?->link ?? '') }}"
				placeholder="https://">
		</div>
		<div class="col-md-2">
			<label class="form-label small mb-0">{{ __('cms.icon') }}</label>
			<input type="text" class="form-control form-control-sm" name="{{ $namePrefix }}[icon]"
				value="{{ old($namePrefix.'.icon', $link?->icon ?? '') }}"
				placeholder="mdi mdi-link">
		</div>
		<div class="col-md-2">
			<label class="form-label small mb-0">{{ __('cms.target') }}</label>
			<select class="form-select form-select-sm" name="{{ $namePrefix }}[target]">
				@php $t = old($namePrefix.'.target', $link?->target ?? '_self'); @endphp
				<option value="_self" @selected($t==='_self' )>_self</option>
				<option value="_blank" @selected($t==='_blank' )>_blank</option>
			</select>
		</div>
		<div class="col-md-1">
			<label class="form-label small mb-0">{{ __('cms.order') }}</label>
			<input type="number" class="form-control form-control-sm" name="{{ $namePrefix }}[order]"
				value="{{ old($namePrefix.'.order', $link?->order ?? 0) }}">
		</div>
		<div class="col-md-1">
			@php $linkActiveId = str_replace(['[', ']'], ['_', ''], $namePrefix) . '_is_active';
			@endphp
			<div class="d-flex align-items-center justify-content-between gap-2 mt-3">
				<label class="form-label small mb-0"
					for="{{ $linkActiveId }}">{{ __('cms.active') }}</label>
				<div class="form-check form-switch m-0">
					<input type="checkbox" class="form-check-input" role="switch"
						id="{{ $linkActiveId }}" name="{{ $namePrefix }}[is_active]"
						value="1"
						{{ old($namePrefix.'.is_active', $link?->is_active ?? true) ? 'checked' : '' }}>
				</div>
			</div>
		</div>
		<div class="col-md-2 text-end">
			<button type="button" class="btn btn-sm btn-outline-danger mt-3" data-role="remove-link"
				title="{{ __('cms.remove') }}">&times;</button>
		</div>
		@foreach($languages as $lang)
		@php
		$tr = $link ? $link->translations->where('locale', $lang->code)->first() : null;
		@endphp
		<div class="col-12 col-md-4">
			<label class="form-label small mb-0">{{ __('cms.link_label') }}
				({{ $lang->code }})</label>
			<input type="text" class="form-control form-control-sm"
				name="{{ $namePrefix }}[translations][{{ $lang->code }}][name]"
				value="{{ old($namePrefix.'.translations.'.$lang->code.'.name', $tr->name ?? '') }}">
		</div>
		@endforeach
	</div>
