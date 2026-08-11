	<footer class="footer-wrapper bg-title footer-layout1" data-bg-src="assets/img/bg/footer_bg_1.png">
		<div class="widget-area">
			<div class="container">
				<div class="row justify-content-between">
					<div class="col-md-6 col-xxl-3 col-xl-3">
						<div class="widget footer-widget mb-0">
							<div class="th-widget-about">
								<div class="about-logo">
									<a href="{{ route('frontend.home') }}"><img
											style="height:50px; width:100px;"
											src="{{ asset('frontend/assets/img/logo.png') }}"
											alt="Randevu "></a>
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
									<li><a href="{{ route('frontend.home') }}">{{ __('main.home') }}</a>
									</li>
									<li><a href="{{ route('frontend.about') }}">{{ __('main.about') }}</a>
									</li>
									<li><a href="{{ route('frontend.services') }}">{{ __('main.services') }}</a>
									</li>
									<li><a href="{{ route('frontend.subscription') }}">{{ __('main.subscription') }}</a>
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
									<li><a href="about.html">Dental
											Surgery</a>
									</li>
									<li><a href="contact.html">General
											Analysis</a>
									</li>
									<li><a href="course.html">Preventative
											Care</a>
									</li>
									<li><a href="course.html">Eye
											Care
											Solution</a>
									</li>
									<li><a href="contact.html">Population
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
									<li><a href="service.html">Primary
											Care</a>
									</li>
									<li><a href="service.html">Mental
											Care</a>
									</li>
									<li><a href="service.html">Speciality
											Care</a>
									</li>
									<li><a href="service.html">Dental
											Care</a>
									</li>
									<li><a href="service.html">Eye
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

									<li><a href="{{ route('frontend.faq') }}">{{ __('main.faqs') }}</a>
									</li>
									<li><a href="{{ route('frontend.contact') }}">{{ __('main.contact') }}</a>
									</li>
									<li><a href="{{ route('frontend.social') }}">{{ __('main.social_media') }}</a>
									</li>
								</ul>
							</div>
						</div>
					</div>

					<div class="col-md-6 col-xl-3">
						<h3 class="widget_title"> {{ __('main.download_app') }}</h3>

						<div class="btn-group download-btn mt-50 justify-content-center justify-content-xl-start wow fadeInUp"
							data-wow-delay=".2s">
							<a href="https://www.apple.com/app-store/"><img
									src="{{asset('frontend/assets/img/icon/apple.svg') }}"
									alt=""></a>
							<a href="https://play.google.com/store/"><img
									src="{{asset('frontend/assets/img/icon/google-play.svg') }}"
									alt=""></a>
						</div>
					</div>
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
											href="tel:+966580161257">00966580161257</a></span>
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
												alt="">
											{{ __('main.book_demo') }}</a>
										<!-- <a href="contact.html"
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
										href="{{ route('frontend.home') }}">{{ __('main.app_name') }}</a>.
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
			<div class="heart-rate2" data-bg-src=" {{ asset('frontend/assets/img/shape/preloader3.svg') }}">
			</div>
			<div class="heart-rate" data-bg-src=" {{ asset('frontend/assets/img/shape/preloader2.svg') }}">

				<div class="fade-in"></div>

				<div class="fade-out"></div>
			</div>
	</footer>
