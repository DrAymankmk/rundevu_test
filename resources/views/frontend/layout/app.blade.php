<!doctype html>
@php
$sessionLang = session('lang');
$effectiveLocale = $sessionLang !== null && $sessionLang !== ''
? (string) $sessionLang
: app()->getLocale();
$effectiveLocale = strtolower(trim(str_replace('_', '-', $effectiveLocale)));
if ($effectiveLocale === '') {
$effectiveLocale = strtolower((string) config('app.locale', 'en'));
}
$htmlLang = explode('-', $effectiveLocale)[0] ?: $effectiveLocale;
$rtlLangs = ['ar', 'fa', 'he', 'ur'];
$isRtl = in_array($htmlLang, $rtlLangs, true);
@endphp
<html class="no-js" lang="{{ $htmlLang }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">

<head>
	<meta charset="utf-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>Randevu</title>
	<meta name="author" content="Randevu">
	<meta name="description" content="Randevu">
	<meta name="keywords" content="Randevu">
	<meta name="robots" content="INDEX,FOLLOW">

	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Favicons - Place favicon.ico in the root directory -->
	<link rel="apple-touch-icon" sizes="57x57" href=" {{ asset('frontend/assets/img/logo.png') }}">
	<link rel="apple-touch-icon" sizes="60x60" href=" {{ asset('frontend/assets/img/logo.png') }}">
	<link rel="icon" type="image/png" sizes="192x192" href=" {{ asset('frontend/assets/img/logo.png') }}">
	<link rel="icon" type="image/png" sizes="32x32" href=" {{ asset('frontend/assets/img/logo.png') }}">
	<link rel="icon" type="image/png" sizes="16x16" href=" {{ asset('frontend/assets/img/logo.png') }}">
	<meta name="theme-color" content="#ffffff">

	<!--==============================
	  Google Fonts
	============================== -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Outfit:wght@100..900&family=Saira:ital,wght@0,100..900;1,100..900&display=swap"
		rel="stylesheet">

	<!--==============================
	    All CSS File
	============================== -->
	<!-- Bootstrap -->
	<link rel="stylesheet" href=" {{ asset('frontend/assets/css/bootstrap.min.css') }}">
	<!-- Fontawesome Icon -->
	<link rel="stylesheet" href=" {{ asset('frontend/assets/css/fontawesome.min.css') }}">
	<!-- Magnific Popup -->
	<link rel="stylesheet" href=" {{ asset('frontend/assets/css/magnific-popup.min.css') }}">
	<!-- Swiper Slider -->
	<link rel="stylesheet" href=" {{ asset('frontend/assets/css/swiper-bundle.min.css') }}">
	<!-- Theme Custom CSS -->

	@if($isRtl)
	<link rel="stylesheet" href="{{ asset('frontend/assets/css/rtl_style.css') }}">
	@else
	<link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}">
	@endif

	<link rel="stylesheet" href="{{ asset('frontend/assets/css/randevu-overrides.css') }}">

</head>

<body>


	<!--[if lte IE 9]>
    	<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
  <![endif]-->

	<!--********************************
   		Code Start From Here
	******************************** -->

	<div class="color-scheme-wrap active">
		<button class="switchIcon"><i class="fa-solid fa-palette"></i></button>
		<h4 class="color-scheme-wrap-title"><i class="far fa-palette me-2"></i>Style Swicher</h4>
		<div class="color-switch-btns">
			<button data-color="#3E66F3"><i class="fa-solid fa-droplet"></i></button>
			<button data-color="#684DF4"><i class="fa-solid fa-droplet"></i></button>
			<button data-color="#008080"><i class="fa-solid fa-droplet"></i></button>
			<button data-color="#323F7C"><i class="fa-solid fa-droplet"></i></button>
			<button data-color="#FC3737"><i class="fa-solid fa-droplet"></i></button>
			<button data-color="#8a2be2"><i class="fa-solid fa-droplet"></i></button>
		</div>
		<a href="https://themeforest.net/user/themeholy" class="th-btn text-center w-100"><i
				class="fa fa-shopping-cart me-2"></i> Purchase</a>
	</div>
	<!--==============================
     Preloader
  ==============================-->
	<div class="preloader ">
		<button class="th-btn preloaderCls">Cancel Preloader </button>
		<div class="preloader-inner">
			<img src=" {{ asset('frontend/assets/img/logo.png') }}" style="height:50px; width:100px;"
				alt="img">
			<div class="heart-rate">
				<img src=" {{ asset('frontend/assets/img/shape/preloader.svg') }}" alt="">

				<div class="fade-in"></div>

				<div class="fade-out"></div>
			</div>
		</div>
	</div>
	<!--==============================
    Sidemenu
============================== -->
	<div class="sidemenu-wrapper shopping-cart ">
		<div class="sidemenu-content">
			<button class="closeButton sideMenuCls"><i class="far fa-times"></i></button>
			<div class="widget woocommerce widget_shopping_cart">
				<h3 class="widget_title">Shopping cart</h3>
				<div class="widget_shopping_cart_content">
					<ul class="woocommerce-mini-cart cart_list product_list_widget ">
						<li class="woocommerce-mini-cart-item mini_cart_item">
							<a href="#"
								class="remove remove_from_cart_button"><i
									class="far fa-times"></i></a>
							<a href="#"><img src=" {{ asset('frontend/assets/img/product/product_thumb_1_1.jpg') }}"
									alt="Cart Image">Puregen Labs
								Meclizine</a>
							<span class="quantity">1 ×
								<span
									class="woocommerce-Price-amount amount">
									<span
										class="woocommerce-Price-currencySymbol">$</span>$55.00</span>
							</span>
						</li>
						<li class="woocommerce-mini-cart-item mini_cart_item">
							<a href="#"
								class="remove remove_from_cart_button"><i
									class="far fa-times"></i></a>
							<a href="#"><img src=" {{ asset('frontend/assets/img/product/product_thumb_1_2.jpg') }}"
									alt="Cart Image">Pranarom Pure
								Essential Oil</a>
							<span class="quantity">1 ×
								<span
									class="woocommerce-Price-amount amount">
									<span
										class="woocommerce-Price-currencySymbol">$</span>$15.00</span>
							</span>
						</li>
						<li class="woocommerce-mini-cart-item mini_cart_item">
							<a href="#"
								class="remove remove_from_cart_button"><i
									class="far fa-times"></i></a>
							<a href="#"><img src=" {{ asset('frontend/assets/img/product/product_thumb_1_3.jpg') }}"
									alt="Cart Image">Pediatric
								Stethoscope</a>
							<span class="quantity">1 ×
								<span
									class="woocommerce-Price-amount amount">
									<span
										class="woocommerce-Price-currencySymbol">$</span>$30.00</span>
							</span>
						</li>
						<li class="woocommerce-mini-cart-item mini_cart_item">
							<a href="#"
								class="remove remove_from_cart_button"><i
									class="far fa-times"></i></a>
							<a href="#"><img src=" {{ asset('frontend/assets/img/product/product_thumb_1_4.jpg') }}"
									alt="Cart Image">Puregen Labs
								Allergy Relief</a>
							<span class="quantity">1 ×
								<span
									class="woocommerce-Price-amount amount">
									<span
										class="woocommerce-Price-currencySymbol">$</span>$55.00</span>
							</span>
						</li>
						<li class="woocommerce-mini-cart-item mini_cart_item">
							<a href="#"
								class="remove remove_from_cart_button"><i
									class="far fa-times"></i></a>
							<a href="#"><img src=" {{ asset('frontend/assets/img/product/product_thumb_1_5.jpg') }}"
									alt="Cart Image">Digital
								Thermometer</a>
							<span class="quantity">1 ×
								<span
									class="woocommerce-Price-amount amount">
									<span
										class="woocommerce-Price-currencySymbol">$</span>$15.99</span>
							</span>
						</li>
					</ul>
					<p class="woocommerce-mini-cart__total total">
						<strong>Subtotal:</strong>
						<span class="woocommerce-Price-amount amount">
							<span
								class="woocommerce-Price-currencySymbol">$</span>170.99</span>
					</p>
					<p class="woocommerce-mini-cart__buttons buttons btn-wrap">
						<a href="cart.html" class="th-btn wc-forward">View cart</a>
						<a href="checkout.html"
							class="th-btn checkout wc-forward">Checkout</a>
					</p>
				</div>
			</div>
		</div>
	</div>
	<!--==============================
    Sidemenu
============================== -->
	<div class="sidemenu-wrapper ">
		<div class="sidemenu-content">
			<button class="closeButton sideMenuCls"><i class="far fa-times"></i></button>
			<div class="widget footer-widget mb-0">
				<div class="th-widget-about">
					<div class="about-logo">
						<a href="{{ route('frontend.home') }}"><img
								src=" {{ asset('frontend/assets/img/logo.png') }}"
								style="height:50px; width:100px;"
								alt="Randevu "></a>
					</div>
					<p class="about-text">Medova is a convenience services to the
						adaptability, Spacious modern villa living room
						with centrally placed swimming pool blending indooroutdoor
					</p>
				</div>
			</div>
			<div class="widget footer-widget">
				<h3 class="widget_title">Recent Posts</h3>
				<div class="recent-post-wrap">
					<div class="recent-post">
						<div class="media-img">
							<a href="blog-details.html"><img
									src="assets/img/blog/recent-post-1-1.jpg"
									alt="Blog Image"></a>
						</div>
						<div class="media-body">
							<div class="recent-post-meta">
								<a href="blog.html"><i
										class="fa-sharp fa-solid fa-calendar-days"></i>April
									02,
									2025</a>
							</div>
							<h4 class="post-title"><a class="text-inherit"
									href="blog-details.html">Cometes
									contabesco audacia
									aeneus tui canonicus</a></h4>
						</div>
					</div>
					<div class="recent-post">
						<div class="media-img">
							<a href="blog-details.html"><img
									src="assets/img/blog/recent-post-1-2.jpg"
									alt="Blog Image"></a>
						</div>
						<div class="media-body">
							<div class="recent-post-meta">
								<a href="blog.html"><i
										class="fa-sharp fa-solid fa-calendar-days"></i>April
									25,
									2025</a>
							</div>
							<h4 class="post-title"><a class="text-inherit"
									href="blog-details.html">Cometes
									contabesco audacia
									aeneus tui canonicus</a></h4>
						</div>
					</div>
					<div class="recent-post">
						<div class="media-img">
							<a href="blog-details.html"><img
									src="assets/img/blog/recent-post-1-3.jpg"
									alt="Blog Image"></a>
						</div>
						<div class="media-body">
							<div class="recent-post-meta">
								<a href="blog.html"><i
										class="fa-sharp fa-solid fa-calendar-days"></i>26
									April,
									2025</a>
							</div>
							<h4 class="post-title"><a class="text-inherit"
									href="blog-details.html">Cometes
									contabesco audacia
									aeneus tui canonicus</a></h4>

						</div>
					</div>
					<div class="recent-post">
						<div class="media-img">
							<a href="blog-details.html"><img
									src="assets/img/blog/recent-post-1-4.jpg"
									alt="Blog Image"></a>
						</div>
						<div class="media-body">
							<div class="recent-post-meta">
								<a href="blog.html"><i
										class="fa-sharp fa-solid fa-calendar-days"></i>27
									April,
									2025</a>
							</div>
							<h4 class="post-title"><a class="text-inherit"
									href="blog-details.html">Cometes
									contabesco audacia
									aeneus tui canonicus</a></h4>
						</div>
					</div>
				</div>
			</div>
			<div class="widget footer-widget">
				<h3 class="widget_title">Social Media:</h3>
				<div class="th-social">
					<a href="https://facebook.com"><i class="fab fa-facebook-f"></i></a>
					<a href="https://twitter.com"><i class="fab fa-twitter"></i></a>
					<a href="https://pinterest.com"><i class="fab fa-pinterest-p"></i></a>
					<a href="https://linkedin.com"><i class="fab fa-linkedin-in"></i></a>
					<a href="https://linkedin.com"><i class="fab fa-instagram"></i></a>
				</div>
			</div>
		</div>
	</div>
	<div class="popup-search-box d-none d-lg-block">
		<button class="searchClose"><i class="fal fa-times"></i></button>
		<form action="#">
			<input type="text" placeholder="What are you looking for?">
			<button type="submit"><i class="fal fa-search"></i></button>
		</form>
	</div>
	<!--==============================
    Mobile Menu
  ============================== -->
	<div class="th-menu-wrapper">
		<div class="th-menu-area text-center">
			<button class="th-menu-toggle"><i class="fal fa-times"></i></button>
			<div class="mobile-logo">
				<a href="{{ route('frontend.home') }}"><img
						src="{{ asset('frontend/assets/img/logo.png') }}"
						alt="Randevu "></a>
			</div>

			<div class="th-mobile-menu">
				<ul>
					<li><a href="{{ route('frontend.home') }}">Home</a></li>


					<li><a href="{{ route('frontend.about') }}">About Us</a></li>
					<li class="menu-item-has-children">
						<a href="#">Services</a>
						<ul class="sub-menu">
							<li><a href="service.html">Services</a></li>
							<li><a href="service-details.html">Service
									Details</a></li>
						</ul>
					</li>

					<li class="menu-item-has-children">
						<a href="#">Blog</a>
						<ul class="sub-menu">
							<li>
								<a href="#">Blog Layout</a>
								<ul class="sub-menu">
									<li><a href="blog.html">Blog</a>
									</li>
									<li><a href="blog-grid.html">Blog
											Grid</a>
									</li>
									<li><a href="blog-grid-sidebar.html">Blog
											Grid With
											Sidebar</a>
									</li>
									<li><a href="blog-list.html">Blog
											List</a>
									</li>
								</ul>
							</li>
							<li><a href="blog-details.html">Blog Details</a>
							</li>
						</ul>
					</li>

					<!-- multi language menu -->
					@include('frontend.layout.partials.multi-language-menu')
				</ul>
			</div>
		</div>
	</div>
	<!--==============================
	Header Area
==============================-->

	@if(Route::is('frontend.about') || Route::is('frontend.services') || Route::is('frontend.faq') ||
	Route::is('frontend.subscription') || Route::is('frontend.contact') )
	@include('frontend.layout.header_2')
	@else
	@include('frontend.layout.header_1')
	@endif

	@include('frontend.layout.partials.book-demo-modal')

	@yield('content')

	<!--==============================
        Footer Area
    ==============================-->
	@include('frontend.layout.footer')

	<!--********************************
			Code End  Here
	******************************** -->
	<!-- Scroll To Top -->
	<div class="scroll-top">
		<svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
			<path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"
				style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 307.919;">
			</path>
		</svg>
	</div>

	<!--==============================
    All Js File
============================== -->
	<!-- Jquery -->
	<script src=" {{ asset('frontend/assets/js/vendor/jquery-3.7.1.min.js') }}"></script>
	<!-- Swiper Slider -->
	<script src=" {{ asset('frontend/assets/js/swiper-bundle.min.js') }}"></script>
	<!-- Bootstrap -->
	<script src=" {{ asset('frontend/assets/js/bootstrap.min.js') }}"></script>
	<!-- Magnific Popup -->
	<script src=" {{ asset('frontend/assets/js/jquery.magnific-popup.min.js') }}"></script>
	<!-- Counter Up -->
	<script src=" {{ asset('frontend/assets/js/jquery.counterup.min.js') }}"></script>
	<!-- Circle Progress -->
	<script src=" {{ asset('frontend/assets/js/circle-progress.js') }}"></script>
	<!-- Range Slider -->
	<script src=" {{ asset('frontend/assets/js/jquery-ui.min.js') }}"></script>
	<!-- Imagesloadedr -->
	<script src=" {{ asset('frontend/assets/js/imagesloaded.pkgd.min.js') }}"></script>
	<!-- isotope -->
	<script src=" {{ asset('frontend/assets/js/isotope.pkgd.min.js') }}"></script>
	<!-- Nice-select -->
	<script src=" {{ asset('frontend/assets/js/nice-select.min.js') }}"></script>
	<!-- wow -->
	<script src=" {{ asset('frontend/assets/js/wow.min.js') }}"></script>

	<!-- 360 degree Js -->
	<script src=" {{ asset('frontend/assets/js/threesixty.min.js') }}"></script>
	<script src=" {{ asset('frontend/assets/js/panolens.min.js') }}"></script>

	<!-- gsap area start -->
	<script src=" {{ asset('frontend/assets/js/gsap.min.js') }}"></script>
	<script src=" {{ asset('frontend/assets/js/ScrollTrigger.min.js') }}"></script>
	<script src=" {{ asset('frontend/assets/js/SplitText.js') }}"></script>
	<!-- gsap area end -->

	<!-- Main Js File -->
	<script src=" {{ asset('frontend/assets/js/main.js') }}"></script>

</body>

</html>
