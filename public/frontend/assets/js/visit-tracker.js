(function () {
	'use strict';

	var cfg = window.__VISIT_LOG__;
	if (!cfg || !cfg.id || !cfg.endpoint || !cfg.token) {
		return;
	}

	var start = Date.now();
	var lastSentSeconds = 0;
	var queuedEvents = [];
	var scrollMarks = { 25: false, 50: false, 75: false, 100: false };
	var onceEvents = {};
	var leaveSent = false;
	var flushing = false;
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
		send(payload(extraEvents), !!useBeacon);
	}

	function sendLeaveOnce(reason) {
		if (leaveSent) {
			// Still refresh time-on-page without another leave event.
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

	function checkScroll() {
		var doc = document.documentElement;
		var scrollTop = window.pageYOffset || doc.scrollTop || 0;
		var height = Math.max(doc.scrollHeight - window.innerHeight, 1);
		var pct = Math.min(100, Math.round((scrollTop / height) * 100));
		[25, 50, 75, 100].forEach(function (mark) {
			if (!scrollMarks[mark] && pct >= mark) {
				scrollMarks[mark] = true;
				pushEvent('scroll_' + mark, { percent: mark }, true);
			}
		});
	}

	window.addEventListener('scroll', checkScroll, { passive: true });
	checkScroll();

	setInterval(function () {
		flush(false);
	}, heartbeatMs);

	// Tab switch / minimize: update time only — do NOT log leave (was causing many leave rows).
	document.addEventListener('visibilitychange', function () {
		if (document.visibilityState === 'hidden') {
			flush(true);
		}
	});

	// Real page unload / navigation: one leave event only.
	window.addEventListener('pagehide', function () {
		sendLeaveOnce('pagehide');
	});
})();
