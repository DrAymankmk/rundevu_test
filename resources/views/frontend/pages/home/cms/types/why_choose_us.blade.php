@php
    $st = $section->translation(app()->getLocale()) ?? $section->translation(config('app.fallback_locale', 'en'));
    $fb = config('app.fallback_locale', 'en');
    $locale = app()->getLocale();
    $items = $section->relationLoaded('items') ? $section->items->where('is_active', true)->sortBy('order')->values() : collect();
    $defaultIcons = ['choose_1_1.svg', 'choose_1_2.svg', 'choose_1_3.svg', 'choose_1_4.svg'];
    $defaultTitles = [__('25+ Experience'), __('24/7 Service Available'), __('Fast Process, Best Result'), __('Professional Medical Team')];
    $defaultText = __('We have world class lab assistants. We are equipped with best medical services & reagents.');
    $imgDefaults = ['choose-img-1.jpg', 'choose-img-2.jpg', 'choose-img-3.jpg'];
@endphp
<div class="choose-area position-relative overflow-hidden space" id="section-{{ $section->id }}">
    <div class="container">
        <div class="row gy-5">
            <div class="col-xxl-5">
                <div class="title-area pe-xl-5">
                    <span class="sub-title sub-title2">{{ $st?->subtitle ?: __('Why Choose Us') }}</span>
                    <h2 class="sec-title text-anime-style-3">{!! $st?->title ?: __('<span class="fw-normal d-block">Exceptional Care</span> For Every Patient') !!}</h2>
                    <p class="fs-18 pe-xl-5 wow fadeInLeft">{!! $st?->description ?: e(__('We have world class lab assistants. We are equipped with best medical services & reagents. We ensure best quality findings.')) !!}</p>
                </div>
                <div class="choose-item-wrap">
                    @if($items->isNotEmpty())
                        @foreach($items as $idx => $item)
                            @php
                                $it = $item->translation($locale) ?? $item->translation($fb);
                                $iconUrl = $item->getMediaUrl('icons', $locale, null, true) ;
                                if (! $iconUrl) {
                                    $iconUrl = asset('frontend/assets/img/icon/' . ($defaultIcons[$idx % 4] ?? 'choose_1_1.svg'));
                                }
                                $delay = ['.3s', '.5s', '.7s', '.9s'][$idx % 4];
                            @endphp
                            <div class="choose-item wow fadeInUp" data-wow-delay="{{ $delay }}">
                                <div class="box-icon">
                                    <img src="{{ $iconUrl }}" alt="">
                                </div>
                                <div class="media-body">
                                    <h3 class="box-title">{{ $it?->title ?: ($defaultTitles[$idx] ?? __('Why us')) }}</h3>
                                    <p class="box-text">{!! $it?->content ?: e($defaultText) !!}</p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        @foreach($defaultTitles as $idx => $defTitle)
                            <div class="choose-item wow fadeInUp" data-wow-delay="{{ ['.3s', '.5s', '.7s', '.9s'][$idx] }}">
                                <div class="box-icon">
                                    <img src="{{ asset('frontend/assets/img/icon/' . $defaultIcons[$idx]) }}" alt="">
                                </div>
                                <div class="media-body">
                                    <h3 class="box-title">{{ $defTitle }}</h3>
                                    <p class="box-text">{{ $defaultText }}</p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="col-xxl-7">
                <div class="choose-img-box">
                    @php
                        $gallery = $section->getMedia('gallery');
                    @endphp
                    @if($gallery->isNotEmpty())
                        @foreach($gallery->take(3) as $gidx => $media)
                            @php
                                $anim = ['fadeInLeft', 'fadeInRight', 'fadeInUp'][$gidx % 3];
                                $d = ['.3s', '.6s', '.9s'][$gidx % 3];
                                $cls = ['img1', 'img2', 'img3'][$gidx % 3];
                            @endphp
                            <div class="{{ $cls }} wow {{ $anim }} global-img" data-wow-delay="{{ $d }}">
                                <img src="{{ $media->getUrl() }}" alt="">
                            </div>
                        @endforeach
                    @else
                        <div class="img1 wow fadeInLeft global-img" data-wow-delay=".3s">
                            <img src="{{ asset('frontend/assets/img/normal/' . $imgDefaults[0]) }}" alt="">
                        </div>
                        <div class="img2 wow fadeInRight global-img" data-wow-delay=".6s">
                            <img src="{{ asset('frontend/assets/img/normal/' . $imgDefaults[1]) }}" alt="">
                        </div>
                        <div class="img3 wow fadeInUp global-img" data-wow-delay=".9s">
                            <img src="{{ asset('frontend/assets/img/normal/' . $imgDefaults[2]) }}" alt="">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="shape-mockup" data-bottom="10%" data-right="25%"><img src="{{ asset('frontend/assets/img/shape/shape-1.png') }}" alt=""></div>
    <div class="shape-mockup jump" data-bottom="20%" data-right="0%"><img src="{{ asset('frontend/assets/img/shape/element-2.png') }}" alt=""></div>
</div>
