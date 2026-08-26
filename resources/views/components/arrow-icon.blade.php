@php
    $locale = strtolower(trim(str_replace('_', '-', (string) (session('lang') ?: app()->getLocale()))));
    $lang = explode('-', $locale)[0] ?: $locale;
    $rtlLangs = ['ar', 'fa', 'he', 'ur'];
    $isRtl = in_array($lang, $rtlLangs, true);

    $weight = $weight ?? 'fa-light';
    $sizeClass = $class ?? 'ms-2';
    $icon = $isRtl ? 'fa-arrow-left-long' : 'fa-arrow-right-long';
@endphp
<i class="{{ $weight }} {{ $icon }} {{ $sizeClass }}"></i>
