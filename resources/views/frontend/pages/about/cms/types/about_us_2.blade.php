@php(extract(\App\Support\Cms\AboutUsSectionPresenter::data($section)))

<div class="about-area overflow-hidden space-bottom" id="about-sec-{{ $section->id }}">
	<div class="container">
		<div class="row gy-4 flex align-items-center">
			<div class="col-xxl-8 mb-30 mb-xl-0">
				<div class="title-area">
					<span class="sub-title">{{ $sub }}</span>
					<h2 class="sec-title">{!! $title !!}</h2>
				</div>
				<div class="img-box1">
					<div class="about-wrapper">
						<div class="img1">
							<img src="{{ $primaryImg }}"
								alt="{{ $primaryAlt !== '' ? $primaryAlt : $aboutAlt }}" decoding="async" loading="lazy">
						</div>
						<div class="">
							<div
								class="cms-about-desc{{ $descHasListItems ? ' checklist mb-50' : '' }}">
								{!! $desc !!}
							</div>
							@if($aboutButtons->isNotEmpty() || $callLink)
							<div class="btn-group mt-40 wow fadeInUp"
								data-wow-delay=".4s">
								@foreach($aboutButtons as $btn)
								<a href="{{ $btn['href'] }}"
									class="{{ $btn['btnClass'] }}"
									@if(($btn['target'] ?? '_self'
									)==='_blank' ) target="_blank"
									@endif @if(!
									empty($btn['rel']))
									rel="{{ $btn['rel'] }}"
									@endif>
									{{ $btn['label'] }}
									@if(! empty($btn['icon']))
									<i
										class="{{ $btn['icon'] }}"></i>
									@endif
								</a>
								@endforeach
								@if($callLink)
								<div class="call-info">
									<div class="call-icon">
										<a
											href="{{ $callLink['href'] }}">
											<i
												class="fa-solid fa-phone-volume"></i>
										</a>
									</div>
									<div class="media-body">
										<span
											class="call-label">{{ $callLink['caption'] }}</span>
										<p
											class="call-link">
											<a
												href="{{ $callLink['href'] }}">{{ $callLink['label'] }}</a>
										</p>
									</div>
								</div>
								@endif
							</div>
							@endif
						</div>
					</div>
				</div>
			</div>
			<div class="col-xxl-4">
				<div class="img-box2">
					<div class="img-box-wrapp">
						<div class="img1 reveal">
							<img src="{{ $secondaryImg }}"
								alt="{{ $secondaryAlt !== '' ? $secondaryAlt : $aboutAlt }}" decoding="async" loading="lazy">
						</div>
						<div class="img2 reveal">
							<img src="{{ $videoImg }}"
								alt="{{ $videoAlt !== '' ? $videoAlt : $aboutAlt }}" decoding="async" loading="lazy">
						</div>
					</div>
					<div class="about-wrapp">
						<div class="discount-wrapp">
							<div class="logo">
								<img src="{{ asset('frontend/assets/img/logo.png') }}"
									alt="{{ __('main.app_name') }}"
									style="width: 80px; height: 50px;" decoding="async" loading="lazy">
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
		</div>
	</div>
	<div class="shape-mockup" data-bottom="7%" data-left="0%">
		<img src="{{ asset('frontend/assets/img/shape/element-1.png') }}" alt="" decoding="async" loading="lazy">
	</div>
</div>