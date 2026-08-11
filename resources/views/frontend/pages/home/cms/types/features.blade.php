@php
    $fb = config('app.fallback_locale', 'en');
    $locale = app()->getLocale();
    $st = $section->translation($locale) ?? $section->translation($fb);
    $items = $section->relationLoaded('items') ? $section->items->where('is_active', true)->sortBy('order')->values() : collect();
    $defaults = [
        ['t' => __('Need Immediate Care?'), 'c' => __('Medova clinic is equipped with best world class machinery & reagents.')],
        ['t' => __('Quality & Patient Safety'), 'c' => __('We are equipped with best medical services & reagents.')],
        ['t' => __('Unlock Your Wellness Journey'), 'c' => __('Make an appointment and take the first step toward better health.')],
    ];

    $resolveHref = static function (?string $raw): string {
        $raw = trim((string) $raw);
        if ($raw === '') {
            return '#';
        }
        if (preg_match('#^(https?:)?//#i', $raw) || str_starts_with($raw, 'mailto:') || str_starts_with($raw, 'tel:')) {
            return $raw;
        }

        return str_starts_with($raw, '/') ? url($raw) : url('/' . ltrim($raw, '/'));
    };

    $getItemLink = static function ($item) use ($locale, $fb, $resolveHref) {
        if (! $item || ! $item->relationLoaded('links')) {
            return null;
        }

        $link = $item->links
            ->where('is_active', true)
            ->filter(static fn ($l) => filled(trim((string) ($l->link ?? ''))))
            ->sortBy('order')
            ->first();

        if (! $link) {
            return null;
        }

        $tr = null;
        if ($link->relationLoaded('translations')) {
            $tr = $link->translations->firstWhere('locale', $locale)
                ?? $link->translations->firstWhere('locale', $fb)
                ?? $link->translations->first();
        }

        $target = in_array($link->target, ['_blank', '_self'], true) ? $link->target : '_self';

        return [
            'href' => $resolveHref($link->link),
            'label' => $tr?->name ?? $link->name ?? __('Learn more'),
            'target' => $target,
            'rel' => $target === '_blank' ? 'noopener noreferrer' : null,
            'icon' => trim((string) ($link->icon ?? '')),
        ];
    };
@endphp
<div class="position-relative space" id="section-{{ $section->id }}">
    <div class="container">
        @if($st?->title || $st?->subtitle)
            <div class="title-area text-center mb-40">
                @if($st?->subtitle)
                    <span class="sub-title text-anime-style-2">{{ $st->subtitle }}</span>
                @endif
                @if($st?->title)
                    <h2 class="sec-title text-anime-style-3">{!! $st->title !!}</h2>
                @endif
                @if($st?->description)
                    <div class="fs-18 mt-2">{!! $st->description !!}</div>
                @endif
            </div>
        @endif
        <div class="row gy-4 justify-content-center">
            @forelse($items as $idx => $item)
                @php
                    $it = $item->translation($locale) ?? $item->translation($fb);
                    $t = $it?->title ?: ($defaults[$idx]['t'] ?? __('Feature'));
                    $c = $it?->content ?: ($defaults[$idx]['c'] ?? '');
                    $delay = ['.2s', '.4s', '.6s'][$idx % 3];
                    $itemLink = $getItemLink($item);
                @endphp
                <div class="col-md-6 col-xl-4 feature-card_wrapp wow fadeInUp" data-wow-delay="{{ $delay }}">
                    <div class="feature-card th-ani">
                        <h3 class="box-title">{{ $t }}</h3>
                        <div class="box-text">{!! $c !!}</div>
                        @if($itemLink)
                            <a href="{{ $itemLink['href'] }}" class="th-btn black-border mt-2"
                                @if(($itemLink['target'] ?? '_self') === '_blank') target="_blank" @endif
                                @if(! empty($itemLink['rel'])) rel="{{ $itemLink['rel'] }}" @endif>
                                {{ $itemLink['label'] }}
                                @if(! empty($itemLink['icon']))
                                    <i class="{{ $itemLink['icon'] }}"></i>
                                @else
                                    <i class="fa-regular fa-arrow-right ms-2"></i>
                                @endif
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                @foreach($defaults as $idx => $def)
                    <div class="col-md-6 col-xl-4 feature-card_wrapp wow fadeInUp" data-wow-delay="{{ ['.2s', '.4s', '.6s'][$idx] }}">
                        <div class="feature-card th-ani">
                            <h3 class="box-title text-anime-style-2">{{ $def['t'] }}</h3>
                            <p class="box-text">{{ $def['c'] }}</p>
                            <!-- <a href="{{ url('/contact') }}" class="th-btn black-border">{{ __('Emergency Call') }} <i class="fa-regular fa-arrow-right ms-2"></i></a> -->
                        </div>
                    </div>
                @endforeach
            @endforelse
        </div>
    </div>
</div>
