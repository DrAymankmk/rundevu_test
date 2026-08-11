@php
    $navLanguages = \App\Models\Language::query()->where('status', 1)->orderBy('id')->get();
    if ($navLanguages->isEmpty() && \Illuminate\Support\Facades\Schema::hasTable('cms_languages')) {
        $navLanguages = \App\Models\CmsLanguage::query()->active()->ordered()->get();
    }
    if ($navLanguages->isEmpty()) {
        $navLanguages = collect([
            (object) ['code' => 'en', 'label' => 'English'],
            (object) ['code' => 'ar', 'label' => 'العربية'],
        ]);
    }
    $current = app()->getLocale();
@endphp
@if($navLanguages->isNotEmpty())
    <li class="menu-item-has-children">
        <a href="#">{{ strtoupper($current) }}</a>
        <ul class="sub-menu">
            @foreach($navLanguages as $lang)
                @php
                    $code = $lang->code ?? 'en';
                    $isActive = $current === $code;
                    if (isset($lang->native_name) || isset($lang->name)) {
                        $label = trim(($lang->flag ?? '') . ' ' . ($lang->native_name ?? $lang->name ?? $code));
                    } elseif (isset($lang->name_en)) {
                        $label = $current === 'ar' && ! empty($lang->name_ar) ? $lang->name_ar : $lang->name_en;
                    } else {
                        $label = $lang->label ?? strtoupper($code);
                    }
                @endphp
                <li>
                    <a href="{{ route('frontend.language.switch', ['lang' => $code]) }}"
                        rel="nofollow"
                        @if($isActive) class="active" aria-current="true" @endif>{{ $label }}</a>
                </li>
            @endforeach
        </ul>
    </li>
@endif
