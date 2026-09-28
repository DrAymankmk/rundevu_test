@php
    $inputId = $inputId ?? 'specialty_icon';
    $iconValue = $iconValue ?? '';
    $imageUrl = $imageUrl ?? null;
    $showRemove = $showRemove ?? false;
@endphp

<div class="mb-3">
    <label class="form-label">{{ trans('admin.icon') }}</label>
    @include('components.icon-picker', [
        'inputId' => $inputId,
        'inputName' => 'icon',
        'value' => $iconValue,
        'sharedModalId' => 'specialtyIconPicker',
    ])
    <small class="text-muted d-block mt-1">{{ trans('admin.specialty_icon_hint') }}</small>
</div>

<div class="mb-3">
    <label class="form-label">{{ trans('admin.image') }}</label>
    @if($imageUrl)
        <div class="mb-2">
            <img src="{{ $imageUrl }}" alt="" width="56" height="56" class="rounded border" style="object-fit: cover;">
        </div>
    @endif
    <input type="file" class="form-control" name="image" accept="image/*">
    @if($showRemove && $imageUrl)
        <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="remove_image_{{ $inputId }}">
            <label class="form-check-label" for="remove_image_{{ $inputId }}">{{ trans('admin.remove_image') }}</label>
        </div>
    @endif
    <small class="text-muted d-block mt-1">{{ trans('admin.specialty_image_hint') }}</small>
</div>
