@php
    $st = $section->translation(app()->getLocale()) ?? $section->translation(config('app.fallback_locale', 'en'));
    $fb = config('app.fallback_locale', 'en');
    $locale = app()->getLocale();
    $items = $section->relationLoaded('items') ? $section->items->where('is_active', true)->sortBy('order')->values() : collect();
    $sliderId = 'projectSlide-' . $section->id;
    $slides = collect();
    foreach ($items as $item) {
        $it = $item->translation($locale) ?? $item->translation($fb);
        $img = $item->getMediaUrl('images', $locale, null, true);
        if ($img) {
            $slides->push(['img' => $img, 'title' => $it?->title, 'text' => $it?->content]);
        }
    }
    foreach ($section->getMedia('gallery') as $media) {
        $slides->push(['img' => $media->getUrl(), 'title' => null, 'text' => null]);
    }
    if ($slides->isEmpty()) {
        foreach (['project_1_1.jpg', 'project_1_2.jpg', 'project_1_3.jpg'] as $pj) {
            $slides->push([
                'img' => asset('frontend/assets/img/project/' . $pj),
                'title' => __('Our values'),
                'text' => __('We are equipped with best medical services and quality care.'),
            ]);
        }
    }
@endphp
<section class="project-area position-relative space-top overflow-hidden" id="section-{{ $section->id }}">
    <div class="container">
        <div class="row justify-content-lg-between justify-content-center align-items-center">
            <div class="col-lg-5">
                <div class="title-area text-center text-lg-start">
                    <span class="sub-title text-anime-style-2">{{ $st?->subtitle ?: __('Gallery') }}</span>
                    <h2 class="sec-title text-anime-style-3">{!! $st?->title ?: __('Our Core <span class="fw-normal">Values</span>') !!}</h2>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="ps-xl-5 ms-xl-4 text-center text-lg-start">
                    <p class="fs-18 wow fadeInUp">{!! $st?->description ?: e(__('We have world class lab assistants. We are equipped with best medical services & reagents. We ensure best quality findings.')) !!}</p>
                </div>
            </div>
        </div>
        <div class="slider-area">
            <div class="swiper th-slider projectSlide has-shadow" id="{{ $sliderId }}"
                data-slider-options='{"loop":true,"spaceBetween":56,"breakpoints":{"0":{"slidesPerView":1},"576":{"slidesPerView":"1"},"768":{"slidesPerView":"2"},"992":{"slidesPerView":"2"},"1200":{"slidesPerView":"3"}}}'>
                <div class="swiper-wrapper">
                    @foreach($slides as $slide)
                        <div class="swiper-slide">
                            <div class="project-card">
                                <div class="box-img global-img">
                                    <img src="{{ $slide['img'] }}" alt="">
                                </div>
                                <div class="box-content">
                                    <h3 class="box-title"><a href="{{ url('/contact') }}">{{ $slide['title'] ?: __('Project') }}</a></h3>
                                    <p class="box-text">{!! $slide['text'] ?: e(__('Quality care and trusted medical services.')) !!}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <button data-slider-prev="#{{ $sliderId }}" class="slider-arrow slider-prev"><i class="far fa-arrow-left"></i></button>
            <button data-slider-next="#{{ $sliderId }}" class="slider-arrow slider-next"><i class="far fa-arrow-right"></i></button>
        </div>
    </div>
</section>
