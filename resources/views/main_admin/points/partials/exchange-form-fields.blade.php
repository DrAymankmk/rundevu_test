@php($exchange = $exchange ?? null)
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">@lang('admin.points') <span class="text-danger">*</span></label>
        <input type="number" name="points" class="form-control" min="1" required
               value="{{ old('points', $exchange->points ?? '') }}"
               placeholder="@lang('admin.points')">
    </div>
    <div class="col-md-6">
        <label class="form-label">@lang('admin.price') <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="number" step="0.01" name="price" class="form-control" min="0" required
                   value="{{ old('price', $exchange->price ?? '') }}"
                   placeholder="@lang('admin.price')">
            <span class="input-group-text">@lang('main.loyalty_currency_sar')</span>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-check form-switch">
            <input type="hidden" name="status" value="0">
            <input class="form-check-input" type="checkbox" name="status" value="1"
                   id="exchangeStatus{{ $exchange->id ?? 'new' }}"
                   {{ old('status', $exchange->status ?? 1) ? 'checked' : '' }}>
            <label class="form-check-label" for="exchangeStatus{{ $exchange->id ?? 'new' }}">@lang('admin.Active')</label>
        </div>
    </div>
</div>
