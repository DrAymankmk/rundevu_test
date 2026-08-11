<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">@lang('admin.name')</label>
        <input type="text" class="form-control" name="name" value="{{ $supervisor->name ?? '' }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">@lang('main.email')</label>
        <input type="email" class="form-control" name="email" value="{{ $supervisor->email ?? '' }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">@lang('main.phone')</label>
        <input type="text" class="form-control" name="phone" value="{{ $supervisor->phone ?? '' }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">@lang('main.city')</label>
        <select name="city_id" class="form-select" required>
            <option value="">@lang('admin.select_city')</option>
            @foreach($cities as $city)
                <option value="{{ $city->id }}" {{ (isset($supervisor) && $supervisor->city_id == $city->id) ? 'selected' : '' }}>
                    {{ $city->name_ar }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">@lang('admin.password')</label>
        <input type="password" class="form-control" name="password" {{ isset($supervisor) ? '' : 'required' }}>
    </div>

    <div class="col-md-6">
        <label class="form-label">@lang('admin.image')</label>
        <input type="file" class="form-control" name="image">
        @if(isset($supervisor) && $supervisor->image)
            <img src="{{ $supervisor->image }}" class="mt-2 rounded-circle" width="60" height="60">
        @endif
    </div>
</div>
