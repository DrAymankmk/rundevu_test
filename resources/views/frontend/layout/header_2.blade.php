 <header class="th-header header-layout2">
 	<div class="header-top">
 		<div class="container">
 			<div
 				class="row gy-2 justify-content-center justify-content-lg-between align-items-center">
 				<div class="col-auto">
 					<div class="header-info-wrap">
							<div class="header-info">
								<div class="header-info_icon">
									<i
										class="fa-solid fa-envelope"></i>
								</div>
								<div class="media-body">
									<span
										class="header-info_label">{{ __('main.mail') }}</span>
									<p class="header-info_link">
										<a
											href="mailto:support@rundevo.net">support@rundevo.net</a>
									</p>
								</div>
							</div>
							<div class="divided"></div>
							<div class="header-info">
								<div class="header-info_icon">
									<i
										class="fa-solid fa-location-dot"></i>
								</div>
								<div class="media-body">
									<span
										class="header-info_label">{{ __('main.address') }}</span>
									<p class="header-info_link">
										{{ __('main.address_text') }}

									</p>
								</div>
							</div>
							<div class="divided"></div>
							<div class="header-info">
								<div class="header-info_icon">
									<i
										class="fa-solid fa-clock"></i>
								</div>
								<div class="media-body">
									<span
										class="header-info_label">{{ __('main.openning_hour') }}</span>
									<p class="header-info_link">
										{{ __('main.openning_hours_text') }}
									</p>
								</div>
							</div>
						</div>
 				</div>
 				<div class="col-auto">
 					<div class="header-button">
						<a href="#" class="th-btn" data-bs-toggle="modal" data-bs-target="#bookDemoModal"><img
								src="{{ asset('frontend/assets/img/icon/alarm.svg') }}"
								alt=""> {{ __('main.book_demo') }}</a>
 						<!-- <form class="search-form">
 							<input type="text"
 								placeholder="{{ __('main.search') }}">
 							<button type="submit"><i
 									class="fa-light fa-magnifying-glass"></i></button>
 						</form> -->

 					</div>
 				</div>
 			</div>
 		</div>
 	</div>
 	<div class="sticky-wrapper">
 		<!-- Main Menu Area -->
 		<div class="container">
 			<div class="menu-area">
 				<div class="row align-items-center justify-content-between">
 					<div class="col-auto">
 						<div class="header-logo">
 							<a href="{{ route('frontend.home') }}"><img
 									src="{{ asset('frontend/assets/img/logo.png') }}"
 									style="height:50px; width:100px;"
 									alt="Randevu "></a>
 						</div>
 					</div>
 					<div class="col-auto">
 						<nav class="main-menu style2 d-none d-lg-inline-block">
 							<ul>
 								<li><a href="{{ route('frontend.home') }}">
 										{{ __('main.home') }}</a>
 								</li>
 								<li><a href="{{ route('frontend.about') }}">
 										{{ __('main.about') }}</a>
 								</li>
 								<li><a href="{{ route('frontend.services') }}">
 										{{ __('main.services') }}</a>
 								</li>
 								<li><a href="{{ route('frontend.faq') }}">
 										{{ __('main.faq') }}</a>
 								</li>

 								<li><a href="{{ route('frontend.subscription') }}">
 										{{ __('main.subscription') }}</a>
 								</li>

								<li><a href="{{ route('frontend.contact') }}">
										{{ __('main.contact') }}</a>
								</li>
								<li><a href="{{ route('frontend.social') }}">
										{{ __('main.social_media') }}</a>
								</li>
 								<!-- multi language menu -->
 								@include('frontend.layout.partials.multi-language-menu')

 							</ul>
 						</nav>
 						<div class="header-button">
 							<button type="button"
 								class="th-menu-toggle d-inline-block d-lg-none"><i
 									class="far fa-bars"></i></button>
 						</div>
 					</div>
 					<div class="col-auto d-none d-xl-block">
 						<div class="header-button">
							<a href="#"
								class="th-btn style2" data-bs-toggle="modal" data-bs-target="#bookDemoModal">{{ __('main.book_demo') }}</a>
 							<!-- <a href="#"
 								class="icon-btn sideMenuToggler d-none d-lg-block"><img
 									src="{{ asset('frontend/assets/img/icon/grid.svg') }}"
 									alt=""></a> -->

 						</div>
 					</div>
 				</div>
 			</div>
 		</div>
 	</div>
 </header>
