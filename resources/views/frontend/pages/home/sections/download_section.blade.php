<section class="background-image download-area space overflow-hidden" style="background-image: url('{{ asset('frontend/assets/img/bg/download-bg-1.png') }}');">
	<div class="container">
		<div class="row gy-5 align-items-center">
			<div class="col-xl-6">
				<div class="download-img">
					<div class="img1">
						<img src="{{ asset('frontend/assets/img/normal/download-1-1.png') }}" alt="img" decoding="async" loading="lazy">
					</div>
				</div>
			</div>
			<div class="col-xl-6">
				<div class="ps-xl-4">
					<div
						class="title-area mb-30 text-center text-xl-start pe-xl-5 me-xl-5">
						<span class="sub-title text-anime-style-2">download
							app</span>
						<h2 class="sec-title text-anime-style-3">Medova
							<span class="fw-normal">Medical
								Apps</span> that make Personal
							Health Easier
						</h2>
						<p class="fs-18 pe-xl-5  wow fadeInUp">If you're
							looking to develop a healthcare app,
							you'll first need to determine the type
							of app that will serve your purpose. By
							taking some time to
							consider what features are the most
							important for your specific patients</p>
					</div>

					@php
						$appleUrl = website_store_url('apple');
						$googleUrl = website_store_url('google_play');
					@endphp
					@if($appleUrl !== '' || $googleUrl !== '')
					<div class="btn-group download-btn mt-50 justify-content-center justify-content-xl-start wow fadeInUp"
						data-wow-delay=".2s">
						@if($appleUrl !== '')
						<a href="{{ $appleUrl }}" target="_blank" rel="noopener noreferrer"><img
								src="{{ asset('frontend/assets/img/icon/apple.svg') }}"
								alt="{{ __('main.app_store') }}" decoding="async" loading="lazy"></a>
						@endif
						@if($googleUrl !== '')
						<a href="{{ $googleUrl }}" target="_blank" rel="noopener noreferrer"><img
								src="{{ asset('frontend/assets/img/icon/google-play.svg') }}"
								alt="{{ __('main.google_play') }}" decoding="async" loading="lazy"></a>
						@endif
					</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</section>