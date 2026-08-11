@php
    use App\Models\Package;

    $fb = config('app.fallback_locale', 'en');
    $locale = app()->getLocale();
    $st = $section->translation($locale) ?? $section->translation($fb);

    $packages = Package::query()
        ->where('status', 1)
        ->orderBy('price')
        ->get();

    $parseFeatures = static function (?string $text): array {
        if (blank($text)) {
            return [];
        }
        if (preg_match('/<li[^>]*>/i', $text)) {
            preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $text, $matches);

            return collect($matches[1] ?? [])
                ->map(static fn ($line) => trim(strip_tags($line)))
                ->filter()
                ->values()
                ->all();
        }

        return collect(preg_split('/\r\n|\r|\n/', strip_tags($text)))
            ->map(static fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    };

    $formatPrice = static function ($value): string {
        if ($value === null || $value === '') {
            return '';
        }
        if (is_numeric($value)) {
            return rtrim(rtrim(number_format((float) $value, 2, '.', ''), '0'), '.');
        }

        return (string) $value;
    };

    $wowClasses = ['fadeInLeft', 'fadeInUp', 'fadeInRight'];
    $wowDelays = ['.2s', '.4s', '.6s'];
    $popularIndex = $packages->count() >= 3 ? (int) floor(($packages->count() - 1) / 2) : -1;
@endphp

<section class="space-top overflow-hidden" id="section-{{ $section->id }}">
    <div class="container">
        <div class="title-area text-center">
            <span class="sub-title">{{ $st?->subtitle ?: __('main.pricing_plan') }}</span>
            <h2 class="sec-title">{{ $st?->title ?: __('main.pricing_plan') }}</h2>
            @if($st?->description)
                <div class="fs-18 mt-2">{!! $st->description !!}</div>
            @endif
        </div>
        <div class="row gy-4 justify-content-center">
            @forelse($packages as $index => $package)
                @php
                    $name = $locale === 'ar'
                        ? ($package->name_ar ?: $package->name_en)
                        : ($package->name_en ?: $package->name_ar);
                    $featuresRaw = $locale === 'ar'
                        ? ($package->features_ar ?: $package->features_en)
                        : ($package->features_en ?: $package->features_ar);
                    $features = $parseFeatures($featuresRaw);
                    $price = $formatPrice($package->price_after_discount ?: $package->price);
                    $originalPrice = filled($package->price_after_discount)
                        && (string) $package->price_after_discount !== (string) $package->price
                        ? $formatPrice($package->price)
                        : null;
                    $isPopular = $index === $popularIndex;
                    $offerTag = $isPopular
                        ? __('main.popular_plan')
                        : ($package->free_months > 0
                            ? __('main.package_free_months', ['count' => (int) $package->free_months])
                            : '');
                    $durationLabel = $package->duration
                        ? __('main.package_duration_days', ['count' => (int) $package->duration])
                        : __('main.month');
                @endphp
                <div class="col-xl-4 col-md-6">
                    <div class="price-box th-ani {{ $isPopular ? 'active' : '' }} wow {{ $wowClasses[$index % 3] }}"
                        data-wow-delay="{{ $wowDelays[$index % 3] }}">
                        @if(filled($offerTag))
                            <span class="offer-tag">{{ $offerTag }}</span>
                        @else
                            <span class="offer-tag"></span>
                        @endif
                        <h3 class="box-title">{{ $name }}</h3>
                        @if(filled($package->discount))
                            <p class="box-text">{{ __('main.discount_percent_off', ['discount' => $package->discount]) }}</p>
                        @endif
                        <h4 class="box-price">
                            @if($price !== '')
                                {{ $price }}
                            @endif
                            @if($originalPrice)
                                <small class="text-decoration-line-through opacity-75 ms-1">{{ $originalPrice }}</small>
                            @endif
                            <span class="duration">/{{ $durationLabel }}</span>
                        </h4>
                        @if($featuresRaw && empty($features))
                            <p class="box-text2">{!! nl2br(e(strip_tags($featuresRaw))) !!}</p>
                        @endif
                        @if(!empty($features))
                            <div class="box-content">
                                <div class="available-list">
                                    <ul>
                                        @foreach($features as $feature)
                                            <li>{{ $feature }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                        <a href="{{ route('frontend.subscription', ['package' => $package->id]) }}"
                            class="th-btn black-border fw-btn">
                            {{ __('main.choose_plan') }}
                            <i class="fa-regular fa-arrow-left ms-2"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center mb-0">{{ __('main.no_packages_available_at_the_moment') }}</p>
                </div>
            @endforelse
        </div>
    </div>
    <div class="shape-mockup d-none d-xxl-block" data-top="0%" data-right="0%">
        <img src="{{ asset('frontend/assets/img/shape/element-1.png') }}" alt="">
    </div>
</section>
