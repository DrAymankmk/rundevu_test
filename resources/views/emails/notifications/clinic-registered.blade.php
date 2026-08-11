@php
    $clinic = $clinic ?? null;
    $packageName = $package_name ?? ($package->name_en ?? '');
    $meta = $meta ?? [];
@endphp
<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; line-height: 1.5; color: #333;">
    <h2>{{ config('app.name') }} — {{ __('main.notification_clinic_registered_subject') }}</h2>
    <p>{{ __('main.notification_clinic_registered_intro') }}</p>
    <table cellpadding="6" cellspacing="0">
        <tr><td><strong>{{ __('main.clinic_name') }}</strong></td><td>{{ $clinic->name ?? '—' }}</td></tr>
        <tr><td><strong>{{ __('main.email_address') }}</strong></td><td>{{ $clinic->email ?? '—' }}</td></tr>
        <tr><td><strong>{{ __('main.phone_number') }}</strong></td><td>{{ $clinic->phone ?? '—' }}</td></tr>
        @if(!empty($clinic->alternative_phone))
        <tr><td><strong>{{ __('main.alternative_number') }}</strong></td><td>{{ $clinic->alternative_phone }}</td></tr>
        @endif
        <tr><td><strong>{{ __('main.address') }}</strong></td><td>{{ $clinic->address ?? '—' }}</td></tr>
        <tr><td><strong>{{ __('main.clinic_license_number') }}</strong></td><td>{{ $clinic->license_number ?? '—' }}</td></tr>
        <tr><td><strong>{{ __('main.package') }}</strong></td><td>{{ $packageName }}</td></tr>
        @if(!empty($meta['admin_name']))
        <tr><td><strong>{{ __('main.name') }}</strong></td><td>{{ $meta['admin_name'] }}</td></tr>
        @endif
        @if(!empty($meta['admin_email']))
        <tr><td><strong>{{ __('main.email') }}</strong></td><td>{{ $meta['admin_email'] }}</td></tr>
        @endif
        @if(!empty($meta['admin_phone']))
        <tr><td><strong>{{ __('main.phone_number') }}</strong></td><td>{{ $meta['admin_phone'] }}</td></tr>
        @endif
        <tr><td><strong>{{ __('main.created_date') }}</strong></td><td>{{ now()->format('d M Y H:i') }}</td></tr>
    </table>
    <p style="font-size: 12px; color: #888;">{{ __('main.notification_email_footer') }}</p>
</body>
</html>
