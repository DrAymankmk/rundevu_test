@php
    $recipient = $recipient ?? null;
    $selectedEvents = old('events', $recipient ? $recipient->events->pluck('id')->all() : []);
    $modalKey = $modalKey ?? 'default';
@endphp

<div class="mb-3">
    <label class="form-label">@lang('main.notification_recipient_email') <span class="text-danger">*</span></label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $recipient->email ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">@lang('main.notification_recipient_label')</label>
    <input type="text" name="label" class="form-control" value="{{ old('label', $recipient->label ?? '') }}" placeholder="@lang('main.notification_recipient_label_hint')">
</div>

<div class="mb-3">
    <label class="form-label d-block">@lang('main.notification_subscribed_events') <span class="text-danger">*</span></label>
    @foreach($events as $event)
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="events[]" value="{{ $event->id }}" id="event-{{ $modalKey }}-{{ $event->id }}"
                {{ in_array($event->id, $selectedEvents, true) ? 'checked' : '' }}>
            <label class="form-check-label" for="event-{{ $modalKey }}-{{ $event->id }}">
                {{ $event->localizedName() }}
                <small class="text-muted">({{ $event->key }})</small>
            </label>
        </div>
    @endforeach
</div>

<div class="form-check form-switch mb-0">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is-active-{{ $modalKey }}"
        {{ old('is_active', $recipient->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is-active-{{ $modalKey }}">@lang('admin.Active')</label>
</div>
