@php
    $googleTagManagerId = preg_replace('/[^A-Za-z0-9-]/', '', (string) config('services.google.tag_manager_id'));
@endphp

@if($googleTagManagerId !== '')
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $googleTagManagerId }}"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
@endif
