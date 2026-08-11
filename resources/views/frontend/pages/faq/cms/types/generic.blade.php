@php
    $st = $section->translation(app()->getLocale()) ?? $section->translation(config('app.fallback_locale', 'en'));
    $title = $st?->title ?: __('Section');
    $subtitle = $st?->subtitle ?: '';
    $description = $st?->description ?: '';
    $items = $section->relationLoaded('items') ? $section->items->where('is_active', true)->sortBy('order')->values() : collect();
@endphp
<section class="position-relative space border-bottom" id="section-{{ $section->id }}">
    <div class="container">
        <div class="title-area text-center mb-40">
            @if($subtitle)
                <span class="sub-title text-anime-style-2">{{ $subtitle }}</span>
            @endif
            <h2 class="sec-title text-anime-style-3">{{ $title }}</h2>
            @if($description)
                <div class="fs-18 mt-3 cms-section-desc">{!! $description !!}</div>
            @endif
        </div>
        @if($items->isNotEmpty())
            <div class="row gy-4 justify-content-center">
                @foreach($items as $item)
                    @php
                        $it = $item->translation(app()->getLocale()) ?? $item->translation(config('app.fallback_locale', 'en'));
                        $img = $item->getMediaUrl('images', app()->getLocale(), null, true);
                    @endphp
                    <div class="col-md-6 col-xl-4">
                        <div class="feature-card th-ani">
                            @if($img)
                                <div class="mb-3"><img src="{{ $img }}" alt="" class="img-fluid rounded"></div>
                            @endif
                            <h3 class="box-title text-anime-style-2">{{ $it?->title ?: __('Item') }}</h3>
                            @if($it?->sub_title)
                                <p class="text-theme small mb-2">{{ $it->sub_title }}</p>
                            @endif
                            <div class="box-text">{!! $it?->content ?: '' !!}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
