@php
    $st = $section->translation(app()->getLocale()) ?? $section->translation(config('app.fallback_locale', 'en'));
    $fb = config('app.fallback_locale', 'en');
    $locale = app()->getLocale();
    $items = $section->relationLoaded('items') ? $section->items->where('is_active', true)->sortBy('order')->values() : collect();
    $sliderId = 'testiSlide-' . $section->id;
    $patternBg = asset('frontend/assets/img/bg/pattern.png');
    $defaults = [
        ['text' => __('The professionalism and efficiency of the clinic made my visit smooth and stress-free. I highly recommend Medova to anyone in need of top-quality medical care.'), 'name' => 'Monika Zuli', 'role' => __('Heart Patient'), 'img' => asset('frontend/assets/img/testimonial/testi_1_1.png')],
        ['text' => __('I was impressed by the attentive staff and the state-of-the-art facilities at the clinic. The expertise and compassion truly set the experience apart.'), 'name' => 'Sofia Kim', 'role' => __('Diet Patient'), 'img' => asset('frontend/assets/img/testimonial/testi_1_2.png')],
        ['text' => __('The level of care and personalized treatment I received exceeded my expectations. The dedication and friendly atmosphere made the difference.'), 'name' => 'Lucas Fletcher', 'role' => __('Diet Patient'), 'img' => asset('frontend/assets/img/testimonial/testi_1_3.png')],
    ];
    $slides = collect();
    foreach ($items as $idx => $item) {
        $it = $item->translation($locale) ?? $item->translation($fb);
        $avatar = $item->getMediaUrl('images', $locale, $defaults[$idx % 3]['img'], true);
        $slides->push([
            'text' => $it?->content ?: $defaults[$idx % 3]['text'],
            'name' => $it?->title ?: $defaults[$idx % 3]['name'],
            'role' => $it?->sub_title ?: $defaults[$idx % 3]['role'],
            'img' => $avatar,
        ]);
    }
    if ($slides->isEmpty()) {
        foreach ($defaults as $d) {
            $slides->push($d);
        }
    }
@endphp
<section class="overflow-hidden space overflow-hidden" id="testi-sec-{{ $section->id }}">
    <div class="container">
        <div class="row gy-24 justify-content-center">
            <div class="col-xl-8">
                <div class="title-area text-center">
                    <span class="sub-title text-anime-style-2">{{ $st?->subtitle ?: __('Testimonials') }}</span>
                    <h2 class="sec-title text-anime-style-3">{!! $st?->title ?: __('<span class="fw-normal">What Our Patient</span> Think About Awesome Services') !!}</h2>
                    @if($st?->description)
                        <div class="fs-18 mt-2">{!! $st->description !!}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row gy-24 mb-24">
            <div class="col-xl-12">
                <div class="slider-area wow fadeInLeft" data-wow-delay=".2s">
                    <div class="swiper th-slider testiSlide1 has-shadow" id="{{ $sliderId }}"
                        data-slider-options='{"effect":"slide","loop":true}'>
                        <div class="swiper-wrapper">
                            @foreach($slides as $slide)
                                <div class="swiper-slide">
                                    <div class="testi-card" data-bg-src="{{ $patternBg }}">
                                        <div class="box-quote">
                                            <img src="{{ asset('frontend/assets/img/icon/quote.svg') }}" alt="">
                                        </div>
                                        <p class="box-text">{!! $slide['text'] !!}</p>
                                        <div class="box-wrapp">
                                            <div class="box-profile">
                                                <div class="box-author">
                                                    <img src="{{ $slide['img'] }}" alt="">
                                                </div>
                                                <div class="box-info">
                                                    <h3 class="box-title">{{ $slide['name'] }}</h3>
                                                    <span class="box-desig">{{ $slide['role'] }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="icon-box">
                            <button data-slider-prev="#{{ $sliderId }}" class="slider-arrow default slider-prev"><i class="far fa-arrow-left"></i></button>
                            <button data-slider-next="#{{ $sliderId }}" class="slider-arrow default slider-next"><i class="far fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
