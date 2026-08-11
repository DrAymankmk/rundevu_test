@php
    $demo = $demo_request ?? null;
@endphp
<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; line-height: 1.5; color: #333;">
    <h2>{{ config('app.name') }} — {{ __('main.notification_demo_requested_subject') }}</h2>
    <p>{{ __('main.notification_demo_requested_intro') }}</p>
    <table cellpadding="6" cellspacing="0">
        <tr><td><strong>{{ __('main.name') }}</strong></td><td>{{ $demo->name ?? '—' }}</td></tr>
        <tr><td><strong>{{ __('main.clinic_name') }}</strong></td><td>{{ $demo->clinic_name ?? '—' }}</td></tr>
        <tr><td><strong>{{ __('main.email') }}</strong></td><td>{{ $demo->email ?? '—' }}</td></tr>
        <tr><td><strong>{{ __('main.phone_number') }}</strong></td><td>{{ $demo->phone ?? '—' }}</td></tr>
        <tr><td><strong>{{ __('main.created_date') }}</strong></td><td>{{ $demo->created_at?->format('d M Y H:i') ?? now()->format('d M Y H:i') }}</td></tr>
    </table>
    <p style="font-size: 12px; color: #888;">{{ __('main.notification_email_footer') }}</p>
</body>
</html>
