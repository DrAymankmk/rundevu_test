	<footer class="background-image footer-wrapper bg-title footer-layout1"
		style="background-image: url('{{ asset('frontend/assets/img/bg/footer_bg_1.png') }}');">
		<div class="widget-area">
			<div class="container">
				<div class="row justify-content-between">
					<div class="col-md-6 col-xxl-3 col-xl-3">
						<div class="widget footer-widget mb-0">
							<div class="th-widget-about">
								<div class="about-logo">
									<a
										href="{{ frontend_route('frontend.home') }}"><img
											style="height:50px; width:100px;"
											width="100"
											height="50"
											src="{{ asset('frontend/assets/img/logo.png') }}"
											decoding="async"
											alt="{{ config('app.name', 'Randevu') }}"
											loading="lazy"></a>
								</div>
								<p class="about-text">
									{{ __('main.footer_description') }}
								</p>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-xl-3">
						<div class="widget widget_nav_menu style2 footer-widget">
							<h3 class="widget_title"> {{ __('main.quick_links') }}</h3>
							<div class="menu-all-pages-container">
								<ul class="menu">
									<li><a href="{{ frontend_route('frontend.home') }}">{{ __('main.home') }}</a>
									</li>
									<li><a href="{{ frontend_route('frontend.about') }}">{{ __('main.about') }}</a>
									</li>
									<li><a href="{{ frontend_route('frontend.services') }}">{{ __('main.services') }}</a>
									</li>
									<li><a href="{{ frontend_route('frontend.clinics') }}">{{ __('main.clinics') }}</a>
									</li>
									<li><a href="{{ frontend_route('frontend.doctors') }}">{{ __('doctors.page_title') }}</a>
									</li>
									<li><a href="{{ frontend_route('frontend.subscription') }}">{{ __('main.subscription') }}</a>
									</li>

								</ul>
							</div>
						</div>
					</div>
					<!-- <div class="col-md-6 col-xl-auto">
						<div class="widget widget_nav_menu footer-widget">
							<h3 class="widget_title">Departments</h3>
							<div class="menu-all-pages-container">
								<ul class="menu">
									<li><a href="{{ frontend_route('frontend.about') }}">Dental
											Surgery</a>
									</li>
									<li><a href="{{ frontend_route('frontend.contact') }}">General
											Analysis</a>
									</li>
									<li><a href="{{ frontend_route('frontend.services') }}">Preventative
											Care</a>
									</li>
									<li><a href="{{ frontend_route('frontend.services') }}">Eye
											Care
											Solution</a>
									</li>
									<li><a href="{{ frontend_route('frontend.contact') }}">Population
											Health</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-xl-auto">
						<div class="widget widget_nav_menu footer-widget">
							<h3 class="widget_title">Services</h3>
							<div class="menu-all-pages-container">
								<ul class="menu">
									<li><a href="{{ frontend_route('frontend.services') }}">Primary
											Care</a>
									</li>
									<li><a href="{{ frontend_route('frontend.services') }}">Mental
											Care</a>
									</li>
									<li><a href="{{ frontend_route('frontend.services') }}">Speciality
											Care</a>
									</li>
									<li><a href="{{ frontend_route('frontend.services') }}">Dental
											Care</a>
									</li>
									<li><a href="{{ frontend_route('frontend.services') }}">Eye
											Care</a>
									</li>
								</ul>
							</div>
						</div>
					</div> -->
					<div class="col-md-6 col-xl-3">
						<div class="widget widget_nav_menu footer-widget">
							<h3 class="widget_title"> {{ __('main.support') }}</h3>
							<div class="menu-all-pages-container">
								<ul class="menu">

									<li><a href="{{ frontend_route('frontend.faq') }}">{{ __('main.faqs') }}</a>
									</li>
									<li><a href="{{ frontend_route('frontend.blog') }}">{{ __('main.blogs') }}</a>
									</li>
									<li><a href="{{ frontend_route('frontend.contact') }}">{{ __('main.contact') }}</a>
									</li>
									<li><a href="{{ frontend_route('frontend.social') }}">{{ __('main.social_media') }}</a>
									</li>
								</ul>
							</div>
						</div>
					</div>

					@php
					$appleUrl = website_store_url('apple');
					$googleUrl = website_store_url('google_play');
					@endphp
					@if($appleUrl !== '' || $googleUrl !== '')
					<div class="col-md-6 col-xl-3">
						<h3 class="widget_title"> {{ __('main.download_app') }}</h3>

						<div class="btn-group download-btn mt-50 justify-content-center justify-content-xl-start wow fadeInUp"
							data-wow-delay=".2s">
							@if($appleUrl !== '')
							<a href="{{ $appleUrl }}" rel="noopener noreferrer"
								target="_blank"><img
									src="{{asset('frontend/assets/img/icon/apple.svg') }}"
									width="168" height="50" loading="lazy"
									decoding="async"
									alt="{{ __('main.app_store') }}"></a>
							@endif
							@if($googleUrl !== '')
							<a href="{{ $googleUrl }}" rel="noopener noreferrer"
								target="_blank"><img
									src="{{asset('frontend/assets/img/icon/google-play.svg') }}"
									width="168" height="50" loading="lazy"
									decoding="async"
									alt="{{ __('main.google_play') }}"></a>
							@endif
						</div>
					</div>
					@endif
				</div>
			</div>
			<div class="container">
				<div class="row justify-content-end align-items-end">
					<div class="col-xl-4">
						<div class="footer-widget-about">
							<div class="th-widget-about">
								<p class="footer-info"><i
										class="fa-sharp fa-solid fa-phone"></i>
									<span><a class="text-inherit"
											href="tel:+966530377588">00966530377588</a></span>
								</p>
								<p class="footer-info"><i
										class="fa-sharp fa-solid fa-envelope"></i><span>
										<a class="text-inherit"
											href="mailto:support@rundevo.net">
											support@rundevo.net</a></span>
								</p>
								<p class="footer-info"><i
										class="fas fa-map-marker-alt"></i>
									{{ __('main.address_text') }}
								</p>
							</div>
						</div>
					</div>
					<div class="col-xl-8">
						<div class="row gy-4 align-items-center">
							<!-- <div class="col-lg-6">
								<div
									class="title-area mb-0 text-center text-lg-start">
									<h4 class="sec-title m-0">
										Explore Our
										Comprehensive
										Healthcare
										Solutions
									</h4>
								</div>
							</div> -->
							<div class="col-lg-6">
								<div class="footer-top-btn">
									<div
										class="btn-group justify-content-center justify-content-lg-end">
										<a href="#" class="th-btn"
											data-bs-toggle="modal"
											data-bs-target="#bookDemoModal"><img
												src="{{ asset('frontend/assets/img/icon/alarm.svg') }}"
												width="18"
												height="18"
												alt=""
												decoding="async"
												loading="lazy">
											{{ __('main.book_demo') }}</a>
										<!-- <a href="{{ frontend_route('frontend.contact') }}"
											class="th-btn style2">Our
											Specialists<i
												class="fa-light fa-arrow-right-long ms-2"></i></a>
									</div> -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="copyright-wrap">
					<div class="container">
						<div class="row gy-2 align-items-center">
							<div class="col-lg-5">
								<p class="copyright-text">
									{{ __('main.copyright') }} <i
										class="fal fa-copyright"></i>
									{{ date('Y') }} <a
										href="{{ frontend_route('frontend.home') }}">{{ __('main.app_name') }}</a>.
									{{ __('main.all_rights_reserved') }}
								</p>
							</div>
							<div class="col-lg-7 text-center text-lg-end">
								@include('frontend.layout.partials.social-links')
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="background-image heart-rate2"
				style="background-image: url('{{ asset('frontend/assets/img/shape/preloader3.svg') }}');">
			</div>
			<!-- <div class="background-image heart-rate" style="background-image: url('{{ asset('frontend/assets/img/shape/preloader2.svg') }}');">

				<div class="fade-in"></div>

				<div class="fade-out"></div>
			</div> -->
	</footer>
