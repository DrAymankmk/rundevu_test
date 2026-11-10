@php
    $deal = $deal ?? null;
@endphp
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">@lang('deals.title_ar')</label>
        <input type="text" name="title_ar" class="form-control" value="{{ old('title_ar', $deal->title_ar ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">@lang('deals.title_en')</label>
        <input type="text" name="title_en" class="form-control" value="{{ old('title_en', $deal->title_en ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">@lang('deals.description_ar')</label>
        <textarea name="description_ar" class="form-control" rows="3">{{ old('description_ar', $deal->description_ar ?? '') }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">@lang('deals.description_en')</label>
        <textarea name="description_en" class="form-control" rows="3">{{ old('description_en', $deal->description_en ?? '') }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">@lang('deals.conditions_ar')</label>
        <textarea name="conditions_ar" class="form-control" rows="3">{{ old('conditions_ar', $deal->conditions_ar ?? '') }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">@lang('deals.conditions_en')</label>
        <textarea name="conditions_en" class="form-control" rows="3">{{ old('conditions_en', $deal->conditions_en ?? '') }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">@lang('deals.clinic')</label>
        <select name="clinic_id" id="deal_clinic_id" class="form-control" required>
            <option value="">@lang('deals.select_clinic')</option>
            @foreach($clinics as $clinic)
                <option value="{{ $clinic->id }}" {{ (string) old('clinic_id', $deal->clinic_id ?? '') === (string) $clinic->id ? 'selected' : '' }}>
                    {{ $clinic->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">@lang('deals.specialty')</label>
        <select name="specialty_id" id="deal_specialty_id" class="form-control">
            <option value="">@lang('deals.select_specialty')</option>
            @foreach(($specialties ?? []) as $specialty)
                <option value="{{ $specialty->id }}" {{ (string) old('specialty_id', $deal->specialty_id ?? '') === (string) $specialty->id ? 'selected' : '' }}>
                    {{ app()->getLocale() === 'en' ? ($specialty->name_en ?: $specialty->name_ar) : $specialty->name_ar }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">@lang('deals.price_before')</label>
        <input type="number" step="0.01" min="0" name="price_before" class="form-control" value="{{ old('price_before', $deal->price_before ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">@lang('deals.price_after')</label>
        <input type="number" step="0.01" min="0" name="price_after" class="form-control" value="{{ old('price_after', $deal->price_after ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">@lang('deals.status')</label>
        <select name="status" class="form-control" required>
            <option value="active" {{ old('status', $deal->status ?? 'not_active') === 'active' ? 'selected' : '' }}>@lang('deals.active')</option>
            <option value="not_active" {{ old('status', $deal->status ?? 'not_active') === 'not_active' ? 'selected' : '' }}>@lang('deals.not_active')</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">@lang('deals.maximum_number_of_usage')</label>
        <input type="number" min="1" name="maximum_number_of_usage" class="form-control" value="{{ old('maximum_number_of_usage', $deal->maximum_number_of_usage ?? '') }}" placeholder="@lang('deals.unlimited')">
    </div>
    <div class="col-md-4">
        <label class="form-label">@lang('deals.expiration_date')</label>
        <input type="date" name="expiration_date" class="form-control" value="{{ old('expiration_date', optional($deal->expiration_date ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">@lang('deals.image')</label>
        <input type="file" name="image" class="form-control" accept="image/*">
        @if(!empty($deal?->image))
            <img src="{{ $deal->image }}" alt="" class="mt-2" style="max-height:80px">
        @endif
    </div>
</div>

@push('scripts')
<script>
(function () {
    const clinicSelect = document.getElementById('deal_clinic_id');
    const specialtySelect = document.getElementById('deal_specialty_id');
    if (!clinicSelect || !specialtySelect) return;

    const selectedSpecialty = @json(old('specialty_id', $deal->specialty_id ?? null));

    function loadSpecialties(clinicId) {
        specialtySelect.innerHTML = '<option value="">@lang('deals.select_specialty')</option>';
        if (!clinicId) return;
        fetch(`{{ url('admin/deals/specialties') }}/${clinicId}`)
            .then(r => r.json())
            .then(items => {
                items.forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item.id;
                    opt.textContent = item.name_ar || item.name_en;
                    if (String(selectedSpecialty) === String(item.id)) opt.selected = true;
                    specialtySelect.appendChild(opt);
                });
            });
    }

    clinicSelect.addEventListener('change', function () {
        loadSpecialties(this.value);
    });

    if (clinicSelect.value && specialtySelect.options.length <= 1) {
        loadSpecialties(clinicSelect.value);
    }
})();
</script>
@endpush
