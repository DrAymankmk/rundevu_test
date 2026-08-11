@php($couponRecord = $coupon ?? null)
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>@lang('main.loyalty_service_name_ar')</label>
            <input type="text" name="service_name_ar" class="form-control" required
                   value="{{ old('service_name_ar', $couponRecord->service_name_ar ?? '') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>@lang('main.loyalty_service_name_en')</label>
            <input type="text" name="service_name_en" class="form-control"
                   value="{{ old('service_name_en', $couponRecord->service_name_en ?? '') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>@lang('main.loyalty_details_ar')</label>
            <textarea name="details_ar" class="form-control" rows="2">{{ old('details_ar', $couponRecord->details_ar ?? '') }}</textarea>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>@lang('main.loyalty_details_en')</label>
            <textarea name="details_en" class="form-control" rows="2">{{ old('details_en', $couponRecord->details_en ?? '') }}</textarea>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>@lang('main.loyalty_price_before_discount')</label>
            <div class="input-group">
                <input type="number" step="0.01" min="0" name="price_before_discount"
                       class="form-control price-before-discount coupon-price-input" required
                       value="{{ old('price_before_discount', $couponRecord->price_before_discount ?? '') }}">
                <div class="input-group-append">
                    <span class="input-group-text">@lang('main.loyalty_currency_sar')</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>@lang('main.loyalty_discount_type')</label>
            <select name="discount_type" class="form-control discount-type-select coupon-price-input" required>
                <option value="percentage" {{ old('discount_type', $couponRecord->discount_type ?? 'percentage') === 'percentage' ? 'selected' : '' }}>
                    @lang('main.loyalty_discount_percentage')
                </option>
                <option value="fixed" {{ old('discount_type', $couponRecord->discount_type ?? '') === 'fixed' ? 'selected' : '' }}>
                    @lang('main.loyalty_discount_fixed')
                </option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>@lang('main.loyalty_discount_value')</label>
            <div class="input-group">
                <input type="number" step="0.01" min="0" name="discount_value"
                       class="form-control discount-value-input coupon-price-input" required
                       value="{{ old('discount_value', $couponRecord->discount_value ?? '') }}">
                <div class="input-group-append">
                    <span class="input-group-text discount-value-suffix">
                        {{ old('discount_type', $couponRecord->discount_type ?? 'percentage') === 'fixed' ? __('main.loyalty_currency_sar') : '%' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>@lang('main.loyalty_price_after_discount')</label>
            <div class="input-group">
                <input type="text" class="form-control price-after-discount bg-light" readonly
                       value="{{ old('price_after_discount', $couponRecord->price_after_discount ?? '0.00') }}">
                <div class="input-group-append">
                    <span class="input-group-text">@lang('main.loyalty_currency_sar')</span>
                </div>
            </div>
            <small class="text-muted">@lang('main.loyalty_price_after_discount_hint')</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>@lang('main.loyalty_usage_limit')</label>
            <input type="number" name="usage_limit" class="form-control" min="0"
                   value="{{ old('usage_limit', $couponRecord->usage_limit ?? 0) }}">
            <small class="text-muted">@lang('main.loyalty_usage_limit_hint')</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>@lang('main.loyalty_expires_at')</label>
            <input type="date" name="expires_at" class="form-control" required
                   value="{{ old('expires_at', optional($couponRecord)->expires_at?->format('Y-m-d')) }}">
        </div>
    </div>
</div>
