@php($rule = $rule ?? null)
<div class="row g-3">
	<div class="col-md-6">
		<label class="form-label">@lang('main.loyalty_rule_key')</label>
		<input type="text" name="key" class="form-control" required maxlength="100" pattern="[A-Za-z0-9_-]+"
			value="{{ old('key', $rule->key ?? '') }}" placeholder="completed_visit" @if($rule)
			readonly @endif>
		<!-- <small class="text-muted">@lang('main.loyalty_rule_key_hint')</small> -->
	</div>
	<div class="col-md-6">
		<label class="form-label">@lang('main.all_points')</label>
		<input type="number" name="points" class="form-control" required min="0"
			value="{{ old('points', $rule->points ?? 0) }}">
	</div>
	<div class="col-md-6">
		<label class="form-label">@lang('admin.name_ar')</label>
		<input type="text" name="name_ar" class="form-control" required maxlength="255"
			value="{{ old('name_ar', $rule->name_ar ?? '') }}">
	</div>
	<div class="col-md-6">
		<label class="form-label">@lang('admin.name_en')</label>
		<input type="text" name="name_en" class="form-control" required maxlength="255"
			value="{{ old('name_en', $rule->name_en ?? '') }}">
	</div>
	<div class="col-md-4">
		<label class="form-label">@lang('main.loyalty_max_per_day')</label>
		<input type="number" name="max_per_day" class="form-control" min="1"
			value="{{ old('max_per_day', $rule->max_per_day ?? '') }}"
			placeholder="@lang('main.unlimited')">
	</div>
	<div class="col-md-4">
		<label class="form-label">@lang('main.loyalty_min_words')</label>
		<input type="number" name="min_words" class="form-control" min="1"
			value="{{ old('min_words', $rule->min_words ?? '') }}"
			placeholder="@lang('main.not_applicable')">
	</div>
	<div class="col-md-4">
		<label class="form-label">@lang('main.loyalty_expires_after_months')</label>
		<input type="number" name="expires_after_months" class="form-control" required min="1" max="120"
			value="{{ old('expires_after_months', $rule->expires_after_months ?? 12) }}">
	</div>
	<div class="col-md-12">
		<div class="form-check form-switch">
			<input type="hidden" name="status" value="0">
			<input class="form-check-input" type="checkbox" name="status" value="1"
				id="ruleStatus{{ $rule->id ?? 'new' }}"
				{{ old('status', $rule->status ?? 1) ? 'checked' : '' }}>
			<label class="form-check-label"
				for="ruleStatus{{ $rule->id ?? 'new' }}">@lang('admin.Active')</label>
		</div>
	</div>
</div>