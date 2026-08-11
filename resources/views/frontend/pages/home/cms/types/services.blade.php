@php
    $fb = config('app.fallback_locale', 'en');
    $locale = app()->getLocale();
    $st = $section->translation($locale) ?? $section->translation($fb);
    $items = $section->relationLoaded('items') ? $section->items->where('is_active', true)->sortBy('order')->values() : collect();
    $bg = asset('frontend/assets/img/bg/service_bg_1.jpg');
    $defaultIcons = ['service_1_1.svg', 'service_1_2.svg', 'service_1_3.svg'];
    $defaultImg = ['service_2_1.jpg', 'service_2_2.jpg', 'service_2_3.jpg'];

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
            'label' => $tr?->name ?? $link->name ?? __('Read More'),
            'target' => $target,
            'rel' => $target === '_blank' ? 'noopener noreferrer' : null,
            'icon' => trim((string) ($link->icon ?? '')),
        ];
    };
@endphp
<section class="position-relative overflow-hidden space" data-bg-src="{{ $bg }}" id="section-{{ $section->id }}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5">
                <div class="title-area text-center">
                    <span class="sub-title">{{ $st?->subtitle ?: __('Our Services') }}</span>
                    <h2 class="sec-title">{!! $st?->title ?: __('Our Amazing <span class="fw-normal">Services</span>') !!}</h2>
                    <div class="fs-18 wow fadeInUp cms-services-intro">{!! $st?->description ?: e(__('We are equipped with best medical services and quality findings.')) !!}</div>
                </div>
            </div>
        </div>
        <div class="service-list-area">
            <div class="row gy-3">
                @forelse($items as $idx => $item)
                    @php
                        $it = $item->translation($locale) ?? $item->translation($fb);
                        $icon = $item->getMediaUrl('icons', $locale, asset('frontend/assets/img/icon/' . ($defaultIcons[$idx % 3] ?? 'service_1_1.svg')), true);
                        $simg = $item->getMediaUrl('images', $locale, asset('frontend/assets/img/service/' . ($defaultImg[$idx % 3] ?? 'service_2_1.jpg')), true);
                        $itemLink = $getItemLink($item);
                    @endphp
                    <div class="col-12">
                        <div class="service-grid2 wow fadeInUp" data-wow-delay="{{ '.' . (2 + $idx) }}s">
                            <div class="service-content">
                                <div class="box-wrapp">
                                    <div class="box-icon box-icon--fit">
                                        <img src="{{ $icon }}" alt="{{ $it?->title ?: __('Service') }}" loading="lazy" decoding="async">
                                    </div>
                                    <h3 class="box-title">
                                        @if($itemLink)
                                            <a href="{{ $itemLink['href'] }}"
                                                @if(($itemLink['target'] ?? '_self') === '_blank') target="_blank" @endif
                                                @if(! empty($itemLink['rel'])) rel="{{ $itemLink['rel'] }}" @endif>{{ $it?->title ?: __('Service') }}</a>
                                        @else
                                            {{ $it?->title ?: __('Service') }}
                                        @endif
                                    </h3>
                                    <div class="box-text">{!! $it?->content ?: e(__('Quality medical care tailored to your needs.')) !!}</div>
                                </div>
                                <div class="box-right-wrapp">
                                    <div class="box-img global-img">
                                        <img src="{{ $simg }}" alt="">
                                    </div>
                                    @if($itemLink)
                                        <div class="service-btn">
                                            <a href="{{ $itemLink['href'] }}" class="th-btn black-border"
                                                @if(($itemLink['target'] ?? '_self') === '_blank') target="_blank" @endif
                                                @if(! empty($itemLink['rel'])) rel="{{ $itemLink['rel'] }}" @endif>
                                                {{ $itemLink['label'] }}
                                                @if(! empty($itemLink['icon']))
                                                    <i class="{{ $itemLink['icon'] }}"></i>
                                                @else
                                                    <i class="fa-regular fa-arrow-right ms-2"></i>
                                                @endif
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted">{{ __('No services added yet.') }}</div>
                @endforelse
		 <div class="text-center mt-60">
                    <a href="{{route('frontend.services')}}" class="th-btn style2">{{__('main.browse_all')}} 

		<i class="fa-light fa-arrow-right-long ms-2"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .service-grid2 .box-icon--fit {
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
        flex-shrink: 0;
    }

    .service-grid2 .box-icon--fit img {
        max-width: 56px;
        max-height: 56px;
        width: auto;
        height: auto;
        object-fit: contain;
        object-position: center;
    }
</style>
