@php
    $modalKey = $modalKey ?? 'add';
    $isEdit = ($modalKey !== 'add') && !empty($package);
    $package = $isEdit ? $package : null;
    $statusValue = $isEdit ? old('status', $package?->status) : old('status');
@endphp

<ul class="nav nav-tabs mb-3" role="tablist">
	<li class="nav-item" role="presentation">
		<button class="nav-link active" id="pkg-tab-ar-{{ $modalKey }}" data-bs-toggle="tab"
			data-bs-target="#pkg-pane-ar-{{ $modalKey }}" type="button" role="tab">
			@lang('admin.name_ar')
		</button>
	</li>
	<li class="nav-item" role="presentation">
		<button class="nav-link" id="pkg-tab-en-{{ $modalKey }}" data-bs-toggle="tab"
			data-bs-target="#pkg-pane-en-{{ $modalKey }}" type="button" role="tab">
			@lang('admin.name_en')
		</button>
	</li>
</ul>

<div class="tab-content mb-3">
	<div class="tab-pane fade show active" id="pkg-pane-ar-{{ $modalKey }}" role="tabpanel">
		<div class="form-group local-forms">
			<label>@lang('admin.name_ar') <span class="login-danger">*</span></label>
			<input class="form-control" placeholder="@lang('admin.name_ar')" name="name_ar" dir="rtl"
				value="{{ old('name_ar', $package?->name_ar) }}" required>
		</div>
		<div class="form-group local-forms">
			<label>@lang('main.features') (@lang('admin.name_ar'))</label>
			<textarea class="form-control" name="features_ar" rows="4" dir="rtl"
				placeholder="@lang('main.features')">{{ old('features_ar', $package?->features_ar) }}</textarea>
		</div>
	</div>

	<div class="tab-pane fade" id="pkg-pane-en-{{ $modalKey }}" role="tabpanel">
		<div class="form-group local-forms">
			<label>@lang('admin.name_en') <span class="login-danger">*</span></label>
			<input class="form-control" placeholder="@lang('admin.name_en')" name="name_en"
				value="{{ old('name_en', $package?->name_en) }}" required>
		</div>
		<div class="form-group local-forms">
			<label>@lang('main.features') (@lang('admin.name_en'))</label>
			<textarea class="form-control" name="features_en" rows="4"
				placeholder="@lang('main.features')">{{ old('features_en', $package?->features_en) }}</textarea>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="form-group local-forms">
			<label>@lang('main.duration') <span class="login-danger">*</span></label>
			<input type="number" class="form-control" placeholder="@lang('main.duration')"
				name="duration" value="{{ old('duration', $package?->duration) }}" min="1"
				required>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group local-forms">
			<label>@lang('main.price') <span class="login-danger">*</span></label>
			<input type="number" step="0.01" class="form-control" placeholder="@lang('main.price')"
				name="price" value="{{ old('price', $package?->price) }}" min="0" required>
		</div>
	</div>

	<div class="col-md-6">
		<div class="form-group local-forms">
			<label>@lang('admin.discount')</label>
			<input type="number" step="0.01" class="form-control"
				placeholder="@lang('admin.discount')" name="discount"
				value="{{ old('discount', $package?->discount) }}" min="0">
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group local-forms">
			<label>@lang('main.price_after_discount')</label>
			<input type="text" class="form-control" placeholder="@lang('main.price_after_discount')"
				name="price_after_discount"
				value="{{ old('price_after_discount', $package?->price_after_discount) }}">
		</div>
	</div>

	<div class="col-md-6">
		<div class="form-group local-forms">
			<label>@lang('main.free_months')</label>
			<input type="number" class="form-control" placeholder="@lang('main.free_months')"
				name="free_months" value="{{ old('free_months', $package?->free_months ?? 0) }}"
				min="0">
		</div>
	</div>
	<div class="col-12">
		<div class="form-group ">
			<label>@lang('admin.status') <span class="login-danger">*</span></label>
			<select class="form-control" name="status" required>
				<option value="" disabled @if(!filled($statusValue) && $statusValue !==0 &&
					$statusValue !=='0' ) selected @endif>
					@lang('admin.select') @lang('admin.status')
				</option>
				<option value="1" @selected((string) $statusValue==='1' )>@lang('admin.Active')
				</option>
				<option value="0" @selected((string) $statusValue==='0' )>
					@lang('admin.InActive')</option>
			</select>
		</div>
	</div>
</div>