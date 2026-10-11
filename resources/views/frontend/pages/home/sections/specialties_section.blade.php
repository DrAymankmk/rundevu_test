@php
    $specialties = collect($specialties ?? []);
    $iconFor = static function ($specialty): string {
        $slug = strtolower((string) ($specialty->frontend_slug ?? ''));
        $name = strtolower((string) ($specialty->name_en ?? ''));
        $haystack = $slug.' '.$name;

        $icons = [
            'tooth' => ['dentist', 'dental'],
            'bone' => ['ortho', 'bone'],
            'brain' => ['neuro', 'nerve', 'psych'],
            'eye' => ['ophthal', 'eye'],
            'ear-listen' => ['ent', 'ear'],
            'baby' => ['obstet', 'gynec', 'pedia', 'neonat'],
            'heart-pulse' => ['cardio', 'internal'],
            'user-doctor' => ['surg'],
            'hand-dots' => ['derma', 'skin'],
            'apple-whole' => ['nutri'],
            'ribbon' => ['oncol'],
        ];

        foreach ($icons as $icon => $needles) {
            foreach ($needles as $needle) {
                if (str_contains($haystack, $needle)) {
                    return $icon;
                }
            }
        }

        return 'stethoscope';
    };
    $needsMdi = $specialties->contains(static function ($specialty) {
        return str_starts_with((string) ($specialty->icon ?? ''), 'mdi ');
    });
@endphp

@if($needsMdi)
@push('styles')
	<link rel="stylesheet" href="{{ asset('build/plugins/material/materialdesignicons.css') }}">
@endpush
@endif

@if($specialties->isNotEmpty())
<section class="space-top space-extra-bottom specialty-scroll-sec" id="specialties-sec">
	<div class="container">
		<div class="title-area text-center">
			<span class="sub-title">{{ __('doctors.specialties_subtitle') }}</span>
			<h2 class="sec-title">{{ __('doctors.specialties_title') }}</h2>
			<p class="fs-18 mb-0">{{ __('doctors.specialties_description') }}</p>
		</div>

		<div class="specialty-scroll-wrap">
			<button type="button" class="specialty-scroll-btn specialty-scroll-btn--prev" data-specialty-prev aria-label="{{ __('doctors.specialties_prev') }}">
				<i class="fa-solid fa-chevron-left"></i>
			</button>

			<div class="specialty-scroll" data-specialty-scroller tabindex="0">
				@foreach($specialties as $specialty)
					@php
						$specialtyImage = $specialty->imageUrl();
						$specialtyIcon = trim((string) ($specialty->icon ?? ''));
						$isActive = isset($activeSpecialty) && (int) ($activeSpecialty->id ?? 0) === (int) $specialty->id;
					@endphp
					<a class="specialty-scroll-card {{ $isActive ? 'is-active' : '' }}"
						href="{{ frontend_route('frontend.doctors.show', $specialty->frontend_slug) }}"
						@if($isActive) aria-current="page" @endif>
						<span class="specialty-scroll-card__icon {{ $specialtyImage ? 'specialty-scroll-card__icon--image' : '' }}">
							@if($specialtyImage)
								<img src="{{ $specialtyImage }}" alt="{{ $specialty->localizedName() }}" width="64" height="64" loading="lazy" decoding="async">
							@elseif($specialtyIcon !== '')
								<i class="{{ $specialtyIcon }}"></i>
							@else
								<i class="fa-solid fa-{{ $iconFor($specialty) }}"></i>
							@endif
						</span>
						<span class="specialty-scroll-card__name">{{ $specialty->localizedName() }}</span>
					</a>
				@endforeach
			</div>

			<button type="button" class="specialty-scroll-btn specialty-scroll-btn--next" data-specialty-next aria-label="{{ __('doctors.specialties_next') }}">
				<i class="fa-solid fa-chevron-right"></i>
			</button>
		</div>
	</div>
</section>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		var scroller = document.querySelector('[data-specialty-scroller]');
		if (!scroller) {
			return;
		}

		var prev = document.querySelector('[data-specialty-prev]');
		var next = document.querySelector('[data-specialty-next]');
		var rtl = document.documentElement.getAttribute('dir') === 'rtl';
		var step = function () {
			return Math.max(240, Math.round(scroller.clientWidth * 0.75));
		};

		if (prev) {
			prev.addEventListener('click', function () {
				scroller.scrollBy({ left: rtl ? step() : -step(), behavior: 'smooth' });
			});
		}

		if (next) {
			next.addEventListener('click', function () {
				scroller.scrollBy({ left: rtl ? -step() : step(), behavior: 'smooth' });
			});
		}
	});
</script>
@endif
