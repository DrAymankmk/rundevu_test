@php
    $couponRecord = $coupon ?? null;
    $locale = app()->getLocale();
    $clinics = $clinics ?? collect();
    $selectedClinicId = (int) old('clinic_id', $couponRecord->clinic_id ?? 0);
    $selectedBranchIds = collect(old('branch_ids', $couponRecord->branch_ids ?? []))->map(fn ($id) => (int) $id)->all();
    $showOnMobileHome = old('show_on_mobile_home', optional($couponRecord)->show_on_mobile_home ?? true);
    $modalKey = $modalKey ?? (optional($couponRecord)->id ?? 'new');
    $isEdit = (bool) $couponRecord;
@endphp
<div class="row g-3 coupon-admin-form" data-modal-key="{{ $modalKey }}">
    <div class="col-md-6">
        <label class="form-label">@lang('main.clinics')</label>
        <select name="clinic_id" class="form-control coupon-clinic-select" {{ $isEdit ? '' : 'required' }} {{ $isEdit ? 'disabled' : '' }}>
            <option value="">@lang('main.clinics')</option>
            @foreach($clinics as $clinic)
                <option value="{{ $clinic->id }}" {{ $selectedClinicId === (int) $clinic->id ? 'selected' : '' }}>
                    {{ $clinic->name }}
                    @if(! $clinic->points_enabled)
                        ({{ __('main.disabled') }})
                    @endif
                </option>
            @endforeach
        </select>
        @if($isEdit)
            <input type="hidden" name="clinic_id" value="{{ $selectedClinicId }}">
        @endif
    </div>
    <div class="col-md-6">
        <label class="form-label">@lang('main.loyalty_coupon_branches')</label>
        <select name="branch_ids[]" class="form-control coupon-branches-select" multiple
                data-selected='@json($selectedBranchIds)'>
        </select>
        <small class="text-muted">@lang('main.loyalty_coupon_branches_hint')</small>
        <div class="coupon-branches-empty text-muted small mt-1 d-none">@lang('main.loyalty_coupon_branches_empty')</div>
    </div>

    <div class="col-md-6">
        <label class="form-label">@lang('main.loyalty_service_name_ar')</label>
        <input type="text" name="service_name_ar" class="form-control" required
               value="{{ old('service_name_ar', $couponRecord->service_name_ar ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">@lang('main.loyalty_service_name_en')</label>
        <input type="text" name="service_name_en" class="form-control"
               value="{{ old('service_name_en', $couponRecord->service_name_en ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">@lang('main.loyalty_details_ar')</label>
        <textarea name="details_ar" class="form-control" rows="2">{{ old('details_ar', $couponRecord->details_ar ?? '') }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">@lang('main.loyalty_details_en')</label>
        <textarea name="details_en" class="form-control" rows="2">{{ old('details_en', $couponRecord->details_en ?? '') }}</textarea>
    </div>

    <div class="col-md-4">
        <label class="form-label">@lang('main.loyalty_price_before_discount')</label>
        <div class="input-group">
            <input type="number" step="0.01" min="0" name="price_before_discount"
                   class="form-control price-before-discount coupon-price-input" required
                   value="{{ old('price_before_discount', $couponRecord->price_before_discount ?? '') }}">
            <span class="input-group-text">@lang('main.loyalty_currency_sar')</span>
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label">@lang('main.loyalty_discount_type')</label>
        <select name="discount_type" class="form-control discount-type-select coupon-price-input" required>
            <option value="percentage" {{ old('discount_type', $couponRecord->discount_type ?? 'percentage') === 'percentage' ? 'selected' : '' }}>
                @lang('main.loyalty_discount_percentage')
            </option>
            <option value="fixed" {{ old('discount_type', $couponRecord->discount_type ?? '') === 'fixed' ? 'selected' : '' }}>
                @lang('main.loyalty_discount_fixed')
            </option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">@lang('main.loyalty_discount_value')</label>
        <div class="input-group">
            <input type="number" step="0.01" min="0" name="discount_value"
                   class="form-control discount-value-input coupon-price-input" required
                   value="{{ old('discount_value', $couponRecord->discount_value ?? '') }}">
            <span class="input-group-text discount-value-suffix">
                {{ old('discount_type', $couponRecord->discount_type ?? 'percentage') === 'fixed' ? __('main.loyalty_currency_sar') : '%' }}
            </span>
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-label">@lang('main.loyalty_price_after_discount')</label>
        <div class="input-group">
            <input type="text" class="form-control price-after-discount bg-light" readonly
                   value="{{ old('price_after_discount', $couponRecord->price_after_discount ?? '0.00') }}">
            <span class="input-group-text">@lang('main.loyalty_currency_sar')</span>
        </div>
        <small class="text-muted">@lang('main.loyalty_price_after_discount_hint')</small>
    </div>
    <div class="col-md-4">
        <label class="form-label">@lang('main.loyalty_usage_limit')</label>
        <input type="number" name="usage_limit" class="form-control" min="0"
               value="{{ old('usage_limit', $couponRecord->usage_limit ?? 0) }}">
        <small class="text-muted">@lang('main.loyalty_usage_limit_hint')</small>
    </div>
    <div class="col-md-4">
        <label class="form-label">@lang('main.loyalty_expires_at')</label>
        <input type="date" name="expires_at" class="form-control" required
               value="{{ old('expires_at', optional($couponRecord)->expires_at?->format('Y-m-d')) }}">
    </div>

    <div class="col-md-12">
        <label class="form-label">@lang('main.loyalty_show_on_mobile_home')</label>
        <div class="form-check mt-1">
            <input type="hidden" name="show_on_mobile_home" value="0">
            <input type="checkbox" class="form-check-input" name="show_on_mobile_home" value="1"
                   id="showOnMobileHome{{ $modalKey }}"
                   {{ $showOnMobileHome ? 'checked' : '' }}>
            <label class="form-check-label" for="showOnMobileHome{{ $modalKey }}">
                @lang('main.loyalty_show_on_mobile_home_label')
            </label>
        </div>
        <small class="text-muted">@lang('main.loyalty_show_on_mobile_home_hint')</small>
    </div>
</div>
