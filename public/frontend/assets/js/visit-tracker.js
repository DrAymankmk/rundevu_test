(function () {
	'use strict';

	var cfg = window.__VISIT_LOG__;
	if (!cfg || !cfg.endpoint) {
		return;
	}

	var start = Date.now();
	var lastSentSeconds = 0;
	var queuedEvents = [];
	var scrollMarks = { 25: false, 50: false, 75: false, 100: false };
	var onceEvents = {};
	var leaveSent = false;
	var flushing = false;
	var bootstrapped = false;
	var bootstrapping = false;
	var heartbeatMs = Math.max(5, parseInt(cfg.heartbeat, 10) || 15) * 1000;

	function nowIso() {
		try {
			return new Date().toISOString();
		} catch (e) {
			return '';
		}
	}

	function timeSpent() {
		return Math.max(0, Math.floor((Date.now() - start) / 1000));
	}

	function pushEvent(name, meta, once) {
		if (!name || queuedEvents.length >= 40) {
			return;
		}
		name = String(name).slice(0, 80);
		if (once || name === 'leave' || name.indexOf('scroll_') === 0 || name.indexOf('_form_start') !== -1) {
			if (onceEvents[name]) {
				return;
			}
			onceEvents[name] = true;
		}
		queuedEvents.push({
			name: name,
			at: nowIso(),
			meta: meta || {}
		});
	}

	function payload(extraEvents) {
		var events = queuedEvents.splice(0, queuedEvents.length);
		if (extraEvents && extraEvents.length) {
			events = events.concat(extraEvents);
		}
		return {
			visit_id: cfg.id,
			visitor_token: cfg.token,
			time_spent_seconds: timeSpent(),
			page_title: (document.title || '').slice(0, 255),
			events: events
		};
	}

	function send(body, useBeacon) {
		if (!cfg.id || !cfg.token) {
			return;
		}
		var seconds = body.time_spent_seconds || 0;
		if (seconds < lastSentSeconds && (!body.events || !body.events.length)) {
			return;
		}
		lastSentSeconds = Math.max(lastSentSeconds, seconds);

		var json = JSON.stringify(body);

		if (useBeacon && navigator.sendBeacon) {
			try {
				var blob = new Blob([json], { type: 'application/json' });
				navigator.sendBeacon(cfg.endpoint, blob);
				return;
			} catch (e) {
				// fall through
			}
		}

		if (flushing && !useBeacon) {
			return;
		}
		flushing = true;

		var headers = {
			'Content-Type': 'application/json',
			'Accept': 'application/json',
			'X-Requested-With': 'XMLHttpRequest'
		};
		if (cfg.csrf) {
			headers['X-CSRF-TOKEN'] = cfg.csrf;
		}

		fetch(cfg.endpoint, {
			method: 'POST',
			headers: headers,
			body: json,
			credentials: 'same-origin',
			keepalive: !!useBeacon
		}).catch(function () {
			// ignore network errors
		}).finally(function () {
			flushing = false;
		});
	}

	function flush(useBeacon, extraEvents) {
		ensureBootstrap().then(function (ok) {
			if (!ok) {
				return;
			}
			send(payload(extraEvents), !!useBeacon);
		});
	}

	function ensureBootstrap() {
		if (cfg.id && cfg.token) {
			bootstrapped = true;
			return Promise.resolve(true);
		}
		if (!cfg.deferred || !cfg.bootstrap) {
			return Promise.resolve(false);
		}
		if (bootstrapping) {
			return new Promise(function (resolve) {
				var t = setInterval(function () {
					if (bootstrapped || (!bootstrapping && !(cfg.id && cfg.token))) {
						clearInterval(t);
						resolve(!!(cfg.id && cfg.token));
					}
				}, 50);
			});
		}
		bootstrapping = true;
		return fetch(cfg.bootstrap, {
			method: 'POST',
			credentials: 'same-origin',
			headers: {
				'Content-Type': 'application/json',
				'Accept': 'application/json',
				'X-Requested-With': 'XMLHttpRequest'
			},
			body: JSON.stringify({
				page_url: window.location.href,
				page_path: window.location.pathname + window.location.search,
				page_title: (document.title || '').slice(0, 255),
				referer: document.referrer || ''
			})
		}).then(function (res) {
			return res.json();
		}).then(function (data) {
			bootstrapping = false;
			if (data && data.ok && data.visit_id && data.visitor_token) {
				cfg.id = data.visit_id;
				cfg.token = data.visitor_token;
				bootstrapped = true;
				return true;
			}
			return false;
		}).catch(function () {
			bootstrapping = false;
			return false;
		});
	}

	function sendLeaveOnce(reason) {
		if (leaveSent) {
			flush(true);
			return;
		}
		leaveSent = true;
		onceEvents.leave = true;
		flush(true, [{ name: 'leave', at: nowIso(), meta: { reason: reason || 'pagehide' } }]);
	}

	function linkMeta(el) {
		var href = el.getAttribute('href') || '';
		var text = (el.textContent || '').trim().replace(/\s+/g, ' ').slice(0, 80);
		var meta = { href: href.slice(0, 255), text: text };
		var linkId = el.getAttribute('data-website-link-id');
		if (linkId) {
			meta.link_id = linkId;
		}
		var visitEvent = el.getAttribute('data-visit-event');
		if (visitEvent) {
			meta.visit_event = visitEvent;
		}
		return meta;
	}

	document.addEventListener('click', function (e) {
		var el = e.target;
		if (!el) {
			return;
		}
		if (el.closest) {
			var cta = el.closest('[data-visit-event], .book-demo, .btn-book-demo, [href*="whatsapp"], .whatsapp');
			if (cta) {
				var ctaName = cta.getAttribute('data-visit-event') || 'click_cta';
				if (cta.getAttribute('data-website-link-id')) {
					ctaName = 'click_website_link';
				}
				pushEvent(ctaName, linkMeta(cta));
				return;
			}
			var anchor = el.closest('a[href]');
			if (anchor) {
				var name = anchor.getAttribute('data-website-link-id') ? 'click_website_link' : 'click_link';
				pushEvent(name, linkMeta(anchor));
			}
		}
	}, true);

	document.addEventListener('focusin', function (e) {
		var el = e.target;
		if (!el || !el.closest) {
			return;
		}
		if (el.closest('form#contactForm, form[action*="contact"], .contact-form')) {
			pushEvent('contact_form_start', {}, true);
		}
		if (el.closest('form[action*="subscription"], .subscription-form')) {
			pushEvent('subscription_form_start', {}, true);
		}
	}, true);

	var scrollRange = 1;
	var scrollRaf = 0;

	function measureScrollRange() {
		var doc = document.documentElement;
		scrollRange = Math.max((doc.scrollHeight || 0) - (window.innerHeight || 0), 1);
	}

	function checkScroll() {
		var doc = document.documentElement;
		var scrollTop = window.pageYOffset || doc.scrollTop || 0;
		var pct = Math.min(100, Math.round((scrollTop / scrollRange) * 100));
		[25, 50, 75, 100].forEach(function (mark) {
			if (!scrollMarks[mark] && pct >= mark) {
				scrollMarks[mark] = true;
				pushEvent('scroll_' + mark, { percent: mark }, true);
			}
		});
	}

	function onScroll() {
		if (scrollRaf) {
			return;
		}
		scrollRaf = window.requestAnimationFrame(function () {
			scrollRaf = 0;
			checkScroll();
		});
	}

	window.addEventListener('scroll', onScroll, { passive: true });
	window.addEventListener('resize', function () {
		measureScrollRange();
		onScroll();
	}, { passive: true });
	// Defer first geometry read so parse/eval does not force an early reflow.
	function scheduleFirstScrollCheck() {
		measureScrollRange();
		checkScroll();
	}
	if ('requestIdleCallback' in window) {
		requestIdleCallback(scheduleFirstScrollCheck, { timeout: 2000 });
	} else {
		setTimeout(scheduleFirstScrollCheck, 0);
	}

	setInterval(function () {
		flush(false);
	}, heartbeatMs);

	document.addEventListener('visibilitychange', function () {
		if (document.visibilityState === 'hidden') {
			flush(true);
		}
	});

	window.addEventListener('pagehide', function () {
		sendLeaveOnce('pagehide');
	});

	// Create the visit after first paint so HTML itself stays cacheable.
	function scheduleBootstrap() {
		if ('requestIdleCallback' in window) {
			requestIdleCallback(function () { ensureBootstrap(); }, { timeout: 3000 });
		} else {
			setTimeout(function () { ensureBootstrap(); }, 800);
		}
	}
	if (document.readyState === 'complete') {
		scheduleBootstrap();
	} else {
		window.addEventListener('load', scheduleBootstrap, { once: true });
	}
})();
