@php
    $locale = app()->getLocale();
    $organization = $organization ?? null;
    $mode = $mode ?? 'create';
    $includeLoyalty = $includeLoyalty ?? ($mode === 'create');
    $includeMap = $includeMap ?? ($mode === 'create');
    $formKey = $formKey ?? ($organization->id ?? 'new');
    $fieldAppTypes = config('organization_fields.field_app_types', []);
    $visibleFor = function (string $field) use ($fieldAppTypes) {
        return implode(',', $fieldAppTypes[$field] ?? []);
    };
    $selectedSpecialtyIds = old('specialty_id', $organization ? $organization->specialties->pluck('specialty_id')->all() : []);
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">@lang('admin.app_type')</label>
        <select name="app_type" class="form-control organization-app-type" required
                @if($mode === 'edit') disabled @endif>
            @foreach($appTypes as $type)
                <option value="{{ $type->id }}" {{ (string) old('app_type', $organization->app_type ?? '1') === (string) $type->id ? 'selected' : '' }}>
                    {{ $locale === 'ar' ? $type->name_ar : $type->name_en }}
                </option>
            @endforeach
        </select>
        @if($mode === 'edit')
            <input type="hidden" name="app_type" value="{{ $organization->app_type }}">
        @endif
    </div>

    <div class="col-md-6 org-field" data-app-types="{{ $visibleFor('status') }}">
        <label class="form-label">@lang('admin.status')</label>
        <select name="status" class="form-control">
            <option value="1" {{ (string) old('status', $organization->status ?? '1') === '1' ? 'selected' : '' }}>@lang('main.enabled')</option>
            <option value="0" {{ (string) old('status', $organization->status ?? '1') === '0' ? 'selected' : '' }}>@lang('main.disabled')</option>
        </select>
    </div>

    <div class="col-12">
        <h6 class="text-muted mb-0">@lang('main.organization_account_section')</h6>
    </div>

    <div class="col-md-6 org-field" data-app-types="{{ $visibleFor('name') }}">
        <label class="form-label">@lang('admin.name')</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $organization->name ?? '') }}" required>
    </div>

    <div class="col-md-6 org-field" data-app-types="{{ $visibleFor('email') }}">
        <label class="form-label">@lang('admin.email')</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $organization->email ?? '') }}" required>
    </div>

    <div class="col-md-6 org-field" data-app-types="{{ $visibleFor('phone') }}">
        <label class="form-label">@lang('main.phone')</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone', $organization->phone ?? '') }}">
    </div>

    <div class="col-md-6 org-field" data-app-types="{{ $visibleFor('password') }}">
        <label class="form-label">@lang('main.password')</label>
        <input type="password" name="password" class="form-control" {{ $mode === 'create' ? 'required' : '' }}
               placeholder="{{ $mode === 'edit' ? __('main.leave_blank_to_keep_password') : '' }}">
    </div>

    <div class="col-md-6 org-field" data-app-types="{{ $visibleFor('parent_id') }}">
        <label class="form-label">@lang('main.parent_clinic')</label>
        <select name="parent_id" class="form-control">
            <option value="">@lang('main.Select')</option>
            @foreach($parentClinics as $clinic)
                @if(! $organization || (int) $clinic->id !== (int) $organization->id)
                    <option value="{{ $clinic->id }}" {{ (string) old('parent_id', $organization->parent_id ?? '') === (string) $clinic->id ? 'selected' : '' }}>
                        {{ $clinic->name }}
                    </option>
                @endif
            @endforeach
        </select>
    </div>

    <div class="col-md-6 org-field" data-app-types="{{ $visibleFor('city_id') }}">
        <label class="form-label">@lang('main.Cities')</label>
        <select name="city_id" class="form-control">
            <option value="">@lang('main.Select')</option>
            @foreach($cities as $city)
                <option value="{{ $city->id }}" {{ (string) old('city_id', $organization->city_id ?? '') === (string) $city->id ? 'selected' : '' }}>
                    {{ $locale === 'ar' ? $city->name_ar : $city->name_en }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-12 org-field" data-app-types="{{ $visibleFor('address') }}">
        <label class="form-label">@lang('main.Address')</label>
        <input type="text"
               name="address"
               id="organizationAddressAutocomplete_{{ $formKey }}"
               class="form-control organization-address"
               value="{{ old('address', $organization->address ?? '') }}"
               placeholder="@lang('main.Search for location')">
    </div>

    <div class="col-md-6 org-field">
        <label class="form-label">@lang('main.latitude')</label>
        <input type="text" name="lat" id="organizationLat_{{ $formKey }}" class="form-control organization-lat"
               value="{{ old('lat', $organization->lat ?? '') }}">
    </div>
    <div class="col-md-6 org-field">
        <label class="form-label">@lang('main.longitude')</label>
        <input type="text" name="lng" id="organizationLng_{{ $formKey }}" class="form-control organization-lng"
               value="{{ old('lng', $organization->lng ?? '') }}">
    </div>

    @if($includeMap)
        <div class="col-12 org-field org-map-field" data-app-types="1,4,5,7">
            <div id="organizationMap_{{ $formKey }}" class="organization-map" data-form-key="{{ $formKey }}"></div>
        </div>
    @endif

    <div class="col-12 org-field" data-app-types="{{ $visibleFor('info') }}">
        <label class="form-label">@lang('main.Clinic Description')</label>
        <textarea name="info" class="form-control" rows="2">{{ old('info', $organization->info ?? '') }}</textarea>
    </div>

    <div class="col-md-6 org-field" data-app-types="{{ $visibleFor('specialization') }}">
        <label class="form-label">@lang('main.specialization')</label>
        <input type="text" name="specialization" class="form-control" value="{{ old('specialization', $organization->specialization ?? '') }}">
    </div>

    <div class="col-md-6 org-field" data-app-types="{{ $visibleFor('license_number') }}">
        <label class="form-label">@lang('main.clinic_license_number')</label>
        <input type="text" name="license_number" class="form-control" value="{{ old('license_number', $organization->license_number ?? '') }}">
    </div>

    <div class="col-md-6 org-field" data-app-types="{{ $visibleFor('medical_commercial_license') }}">
        <label class="form-label">@lang('main.medical_commercial_license')</label>
        <input type="text" name="medical_commercial_license" class="form-control" value="{{ old('medical_commercial_license', $organization->medical_commercial_license ?? '') }}">
    </div>

    <div class="col-md-6 org-field" data-app-types="{{ $visibleFor('alternative_phone') }}">
        <label class="form-label">@lang('main.alternative_phone')</label>
        <input type="text" name="alternative_phone" class="form-control" value="{{ old('alternative_phone', $organization->alternative_phone ?? '') }}">
    </div>

    <div class="col-md-6 org-field" data-app-types="{{ $visibleFor('communication_officer') }}">
        <label class="form-label">@lang('main.communication_officer')</label>
        <input type="text" name="communication_officer" class="form-control" value="{{ old('communication_officer', $organization->communication_officer ?? '') }}">
    </div>

    <div class="col-md-6 org-field" data-app-types="{{ $visibleFor('communication_officer_phone') }}">
        <label class="form-label">@lang('main.communication_officer_phone')</label>
        <input type="text" name="communication_officer_phone" class="form-control" value="{{ old('communication_officer_phone', $organization->communication_officer_phone ?? '') }}">
    </div>

    @if($mode === 'create')
        <div class="col-md-6 org-field" data-app-types="{{ $visibleFor('package_id') }}">
            <label class="form-label">@lang('main.Choose Package')</label>
            <select name="package_id" class="form-control">
                <option value="">@lang('main.Select')</option>
                @foreach($packages as $package)
                    <option value="{{ $package->id }}" {{ (string) old('package_id') === (string) $package->id ? 'selected' : '' }}>
                        {{ $locale === 'ar' ? $package->name_ar : $package->name_en }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif

    <div class="col-md-6 org-field org-specialties-field">
        <label class="form-label">@lang('main.Clinic Specialties')</label>
        <select name="specialty_id[]" class="form-control" multiple>
            @foreach($specialties as $specialty)
                <option value="{{ $specialty->id }}" {{ in_array($specialty->id, (array) $selectedSpecialtyIds, true) ? 'selected' : '' }}>
                    {{ $locale === 'ar' ? $specialty->name_ar : $specialty->name_en }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6 org-field" data-app-types="{{ $visibleFor('image') }}">
        <label class="form-label">@lang('main.Clinic Image')</label>
        <input type="file" name="image" class="form-control" accept="image/*">
    </div>

    <div class="col-md-6 org-field" data-app-types="{{ $visibleFor('facebook_url') }}">
        <label class="form-label">Facebook</label>
        <input type="url" name="facebook_url" class="form-control" value="{{ old('facebook_url', $organization->facebook_url ?? '') }}">
    </div>

    <div class="col-md-6 org-field" data-app-types="{{ $visibleFor('instagram_url') }}">
        <label class="form-label">Instagram</label>
        <input type="url" name="instagram_url" class="form-control" value="{{ old('instagram_url', $organization->instagram_url ?? '') }}">
    </div>

    <div class="col-md-6 org-field" data-app-types="{{ $visibleFor('tiktok_url') }}">
        <label class="form-label">TikTok</label>
        <input type="url" name="tiktok_url" class="form-control" value="{{ old('tiktok_url', $organization->tiktok_url ?? '') }}">
    </div>

    <div class="col-md-6 org-field" data-app-types="{{ $visibleFor('snapchat_url') }}">
        <label class="form-label">Snapchat</label>
        <input type="url" name="snapchat_url" class="form-control" value="{{ old('snapchat_url', $organization->snapchat_url ?? '') }}">
    </div>

    <div class="col-md-6 org-field" data-app-types="{{ $visibleFor('youtube_url') }}">
        <label class="form-label">YouTube</label>
        <input type="url" name="youtube_url" class="form-control" value="{{ old('youtube_url', $organization->youtube_url ?? '') }}">
    </div>

    @if($includeLoyalty)
        <div class="col-12">
            <h6 class="text-muted mb-0 mt-2">@lang('main.loyalty_program')</h6>
        </div>

        <div class="col-md-6 org-field" data-app-types="{{ $visibleFor('points_enabled') }}">
            <label class="form-label">@lang('main.loyalty_points_status')</label>
            <select name="points_enabled" class="form-control points-enabled-select">
                <option value="1" {{ (string) old('points_enabled', $organization->points_enabled ?? '1') === '1' ? 'selected' : '' }}>@lang('main.enabled')</option>
                <option value="0" {{ (string) old('points_enabled', $organization->points_enabled ?? '1') === '0' ? 'selected' : '' }}>@lang('main.disabled')</option>
            </select>
        </div>

        <div class="col-md-6 org-field" data-app-types="{{ $visibleFor('points_category') }}">
            <label class="form-label">@lang('main.loyalty_category')</label>
            <input type="text" name="points_category" class="form-control"
                   value="{{ old('points_category', $organization->points_category ?? '') }}" placeholder="clinic / hospital / lab">
        </div>

        <div class="col-12 org-field" data-app-types="{{ $visibleFor('enabled_modules') }}">
            <label class="form-label">@lang('main.clinic_enabled_modules')</label>
            <div class="row g-2">
                @foreach($moduleDefinitions as $moduleKey => $definition)
                    <div class="col-md-6">
                        <div class="form-check border rounded px-3 py-2 mb-0">
                            <input class="form-check-input module-checkbox"
                                   type="checkbox"
                                   name="enabled_modules[]"
                                   value="{{ $moduleKey }}"
                                   id="module_{{ $formKey }}_{{ $moduleKey }}"
                                   data-module="{{ $moduleKey }}"
                                   {{ in_array($moduleKey, (array) old('enabled_modules', $organization ? $organization->selectedModuleKeys() : []), true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="module_{{ $formKey }}_{{ $moduleKey }}">
                                {{ $locale === 'ar' ? $definition['label_ar'] : $definition['label_en'] }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($errors->has('enabled_modules'))
                <div class="text-danger small mt-1">{{ $errors->first('enabled_modules') }}</div>
            @endif
        </div>
    @endif
</div>
