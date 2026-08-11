@php(extract(\App\Support\Cms\AboutUsSectionPresenter::data($section)))

<div class="about-area overflow-hidden space" id="about-sec">
	<div class="container">
		<div class="row gy-5">
			<div class="col-xxl-4">
				<div class="img-box4 me-xl-3">
					<div class="img1 reveal">
						<img src="{{ $primaryImg }}" alt="{{ $aboutAlt }}">
					</div>
					<div class="about-wrapp" style="right: 0px;">
						<div class="discount-wrapp">
							<div class="logo">
								<img src="{{ asset('frontend/assets/img/logo.png') }}"
									alt="{{ __('main.app_name') }}"
									style="width: 80px; height: 50px;">
							</div>
							<div class="discount-tag">
								<span @if(str_contains($discountAnimeClass, 'discount-anime-plain'
									)) dir="rtl" @endif
									style="padding: 10px;">{{ $discountLabel }}</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xxl-8 mb-30 mb-xl-0">
				<div class="title-area">
					<span class="sub-title">{{ $sub }}</span>
					<h2 class="sec-title">{!! $title !!}</h2>
				</div>
				<div class="about-wrapper style2" style="gap:45px">
					<div style="width: 60%;">
						<div
							class="cms-about-desc{{ $descHasListItems ? ' checklist mb-50' : ' mb-30' }}">
							{!! $desc !!}
						</div>
						@if($aboutButtons->isNotEmpty() || $callLink)
						<div class="btn-group mt-40 wow fadeInUp"
							data-wow-delay=".5s">
							@foreach($aboutButtons as $btn)
							<a href="{{ $btn['href'] }}"
								class="{{ $btn['btnClass'] }}"
								@if(($btn['target'] ?? '_self'
								)==='_blank' ) target="_blank" @endif
								@if(! empty($btn['rel']))
								rel="{{ $btn['rel'] }}" @endif>
								{{ $btn['label'] }}
								@if(! empty($btn['icon']))
								<i class="{{ $btn['icon'] }}"></i>
								@endif
							</a>
							@endforeach
							@if($callLink)
							<div class="call-info">
								<div class="call-icon">
									<a
										href="{{ $callLink['href'] }}"><i
											class="fa-solid fa-phone-volume"></i></a>
								</div>
								<div class="media-body">
									<span
										class="call-label">{{ $callLink['caption'] }}</span>
									<p class="call-link">
										<a
											href="{{ $callLink['href'] }}">{{ $callLink['label'] }}</a>
									</p>
								</div>
							</div>
							@endif
						</div>
						@endif
					</div>
					<div class="video-img2 wow fadeInUp" style="width: 320px;">
						@if($videoIsLocal && filled($videoUrl))
						<video class="about-video-thumb" muted playsinline
							preload="metadata" aria-label="{{ $aboutAlt }}">
							<source src="{{ $videoUrl }}"
								type="{{ $videoMimeType }}">
						</video>
						@else
						<img src="{{ $videoImg }}" alt="{{ $aboutAlt }}">
						@endif
						@if(filled($videoUrl))
						@if($videoIsLocal)
						<a href="#about-inline-video-{{ $section->id }}"
							class="video-play-btn popup-content"
							aria-label="{{ __('main.play_video') }}">
							<i class="fa-solid fa-play"></i>
						</a>
						<div id="about-inline-video-{{ $section->id }}"
							style="display: flex; justify-content: center;"
							class="mfp-hide">
							<video controls playsinline
								style="max-width:100%;width:720px;">
								<source src="{{ $videoUrl }}"
									type="{{ $videoMimeType }}">
							</video>
						</div>
						@else
						<a href="{{ $videoUrl }}" class="video-play-btn popup-video"
							aria-label="{{ __('main.play_video') }}">
							<i class="fa-solid fa-play"></i>
						</a>
						@endif
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
