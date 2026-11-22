@php
    $googleSiteVerification = preg_replace('/[^A-Za-z0-9_-]/', '', (string) config('services.google.site_verification'));
    $googleAnalyticsId = preg_replace('/[^A-Za-z0-9-]/', '', (string) config('services.google.analytics_id'));
    $googleTagManagerId = preg_replace('/[^A-Za-z0-9-]/', '', (string) config('services.google.tag_manager_id'));
    // If GTM is present, GA4 should be configured inside the container to avoid loading gtag twice.
    $loadStandaloneGtag = $googleAnalyticsId !== '' && $googleTagManagerId === '';
@endphp

@if($googleSiteVerification !== '')
<meta name="google-site-verification" content="{{ $googleSiteVerification }}">
@endif

@if($googleTagManagerId !== '' || $loadStandaloneGtag)
<script>
// Analytics tags (GTM, gtag, Clarity via GTM) cost ~300 ms of main-thread work on phones.
// Start them on the visitor's first interaction, or 8 s after load at the latest, so they
// never compete with the first render.
window.rundevoLoadTagsLater = function (fn) {
	var done = false;
	var events = ['pointerdown', 'keydown', 'touchstart', 'mousemove', 'scroll', 'wheel'];
	function run() {
		if (done) return;
		done = true;
		events.forEach(function (e) { window.removeEventListener(e, run, { passive: true }); });
		fn();
	}
	events.forEach(function (e) { window.addEventListener(e, run, { passive: true, once: true }); });
	function fallback() { setTimeout(run, 8000); }
	if (document.readyState === 'complete') {
		fallback();
	} else {
		window.addEventListener('load', fallback, { once: true });
	}
};
</script>
@endif

@if($googleTagManagerId !== '')
<script>
window.dataLayer = window.dataLayer || [];
(function () {
	var gtmId = @json($googleTagManagerId);
	function injectGtm() {
		(function (w, d, s, l, i) {
			w[l] = w[l] || [];
			w[l].push({ 'gtm.start': new Date().getTime(), event: 'gtm.js' });
			var f = d.getElementsByTagName(s)[0],
				j = d.createElement(s),
				dl = l != 'dataLayer' ? '&l=' + l : '';
			j.async = true;
			j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
			f.parentNode.insertBefore(j, f);
		})(window, document, 'script', 'dataLayer', gtmId);
	}
	window.rundevoLoadTagsLater(injectGtm);
})();
</script>
@endif

@if($loadStandaloneGtag)
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
(function () {
	var gaId = @json($googleAnalyticsId);
	function injectGtag() {
		var s = document.createElement('script');
		s.async = true;
		s.src = 'https://www.googletagmanager.com/gtag/js?id=' + gaId;
		s.onload = function () {
			gtag('js', new Date());
			gtag('config', gaId);
		};
		document.head.appendChild(s);
	}
	window.rundevoLoadTagsLater(injectGtag);
})();
</script>
@endif
