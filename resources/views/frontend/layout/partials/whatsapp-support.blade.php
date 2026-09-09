@php
	$clinicWaUrl = 'https://wa.me/message/UZCY4BQEEJYYE1';
	$patientWaUrl = 'https://wa.me/message/6OFM5AU3AHNIN1';
	$clinicQr = 'https://api.qrserver.com/v1/create-qr-code/?size=280x280&ecc=H&margin=10&data=' . urlencode($clinicWaUrl);
	$patientQr = 'https://api.qrserver.com/v1/create-qr-code/?size=280x280&ecc=H&margin=10&data=' . urlencode($patientWaUrl);
@endphp

<div class="wa-support" id="waSupportDock" hidden>
	<button type="button" class="wa-support__btn wa-support__btn--clinic" data-wa-open="clinic"
		aria-haspopup="dialog" aria-controls="waModalClinic"
		aria-label="{{ __('main.wa_clinic_support') }}">
		<span class="wa-support__pulse" aria-hidden="true"></span>
		<span class="wa-support__icon" aria-hidden="true">
			<i class="fa-solid fa-user-headset"></i>
			<i class="fab fa-whatsapp wa-support__badge"></i>
		</span>
		<span class="wa-support__copy">
			<span class="wa-support__title">{{ __('main.wa_clinic_support') }}</span>
			<span class="wa-support__hint">{{ __('main.wa_clinic_support_hint') }}</span>
		</span>
	</button>
	<button type="button" class="wa-support__btn wa-support__btn--patient" data-wa-open="patient"
		aria-haspopup="dialog" aria-controls="waModalPatient"
		aria-label="{{ __('main.wa_patient_support') }}">
		<span class="wa-support__pulse" aria-hidden="true"></span>
		<span class="wa-support__icon" aria-hidden="true">
			<i class="fa-solid fa-heart-pulse"></i>
			<i class="fab fa-whatsapp wa-support__badge"></i>
		</span>
		<span class="wa-support__copy">
			<span class="wa-support__title">{{ __('main.wa_patient_support') }}</span>
			<span class="wa-support__hint">{{ __('main.wa_patient_support_hint') }}</span>
		</span>
	</button>
</div>

<div class="wa-modal" id="waModalClinic" role="dialog" aria-modal="true" aria-labelledby="waModalClinicTitle" hidden>
	<div class="wa-modal__backdrop" data-wa-close></div>
	<div class="wa-modal__card wa-modal__card--clinic" role="document">
		<button type="button" class="wa-modal__close" data-wa-close aria-label="{{ __('main.wa_close') }}">
			<i class="far fa-times"></i>
		</button>
		<div class="wa-modal__glow" aria-hidden="true"></div>
		<div class="wa-modal__head">
			<span class="wa-modal__chip"><i class="fab fa-whatsapp"></i> {{ __('main.social_platform_whatsapp') }}</span>
			<h3 class="wa-modal__title" id="waModalClinicTitle">{{ __('main.wa_clinic_modal_title') }}</h3>
			<p class="wa-modal__text">{{ __('main.wa_clinic_modal_text') }}</p>
		</div>
		<div class="wa-modal__stage">
			<div class="wa-modal__frame">
				<span class="wa-modal__corner wa-modal__corner--tl"></span>
				<span class="wa-modal__corner wa-modal__corner--tr"></span>
				<span class="wa-modal__corner wa-modal__corner--bl"></span>
				<span class="wa-modal__corner wa-modal__corner--br"></span>
				<span class="wa-modal__scan" aria-hidden="true"></span>
				<img class="wa-modal__qr" src="{{ $clinicQr }}" width="280" height="280"
					alt="{{ __('main.wa_clinic_qr_alt') }}">
			</div>
			<p class="wa-modal__scan-hint"><i class="fas fa-camera"></i> {{ __('main.wa_scan_hint') }}</p>
		</div>
		<a class="wa-modal__cta" href="{{ $clinicWaUrl }}" target="_blank" rel="noopener noreferrer">
			<i class="fab fa-whatsapp"></i>
			{{ __('main.wa_open_whatsapp') }}
		</a>
	</div>
</div>

<div class="wa-modal" id="waModalPatient" role="dialog" aria-modal="true" aria-labelledby="waModalPatientTitle" hidden>
	<div class="wa-modal__backdrop" data-wa-close></div>
	<div class="wa-modal__card wa-modal__card--patient" role="document">
		<button type="button" class="wa-modal__close" data-wa-close aria-label="{{ __('main.wa_close') }}">
			<i class="far fa-times"></i>
		</button>
		<div class="wa-modal__glow" aria-hidden="true"></div>
		<div class="wa-modal__head">
			<span class="wa-modal__chip"><i class="fab fa-whatsapp"></i> {{ __('main.social_platform_whatsapp') }}</span>
			<h3 class="wa-modal__title" id="waModalPatientTitle">{{ __('main.wa_patient_modal_title') }}</h3>
			<p class="wa-modal__text">{{ __('main.wa_patient_modal_text') }}</p>
		</div>
		<div class="wa-modal__stage">
			<div class="wa-modal__frame">
				<span class="wa-modal__corner wa-modal__corner--tl"></span>
				<span class="wa-modal__corner wa-modal__corner--tr"></span>
				<span class="wa-modal__corner wa-modal__corner--bl"></span>
				<span class="wa-modal__corner wa-modal__corner--br"></span>
				<span class="wa-modal__scan" aria-hidden="true"></span>
				<img class="wa-modal__qr" src="{{ $patientQr }}" width="280" height="280"
					alt="{{ __('main.wa_patient_qr_alt') }}">
			</div>
			<p class="wa-modal__scan-hint"><i class="fas fa-camera"></i> {{ __('main.wa_scan_hint') }}</p>
		</div>
		<a class="wa-modal__cta" href="{{ $patientWaUrl }}" target="_blank" rel="noopener noreferrer">
			<i class="fab fa-whatsapp"></i>
			{{ __('main.wa_open_whatsapp') }}
		</a>
	</div>
</div>

@push('scripts')
<script>
(function () {
	var dock = document.getElementById('waSupportDock');
	if (!dock) {
		return;
	}

	var lastY = window.scrollY || 0;
	var ticking = false;
	var openModal = null;
	var lastFocus = null;

	function setDockState() {
		var y = window.scrollY || window.pageYOffset || 0;
		var visible = y > 90;
		dock.hidden = false;
		dock.classList.toggle('is-visible', visible);
		if (visible) {
			dock.classList.toggle('is-down', y > lastY + 2);
			dock.classList.toggle('is-up', y < lastY - 2);
		}
		lastY = y;
		ticking = false;
	}

	window.addEventListener('scroll', function () {
		if (!ticking) {
			window.requestAnimationFrame(setDockState);
			ticking = true;
		}
	}, { passive: true });

	setDockState();

	function closeWaModal() {
		if (!openModal) {
			return;
		}
		var modal = openModal;
		modal.classList.remove('is-open');
		document.body.classList.remove('wa-modal-open');
		openModal = null;
		window.setTimeout(function () {
			if (!modal.classList.contains('is-open')) {
				modal.setAttribute('hidden', '');
			}
		}, 320);
		if (lastFocus && typeof lastFocus.focus === 'function') {
			lastFocus.focus();
		}
	}

	function showWaModal(id) {
		var modal = document.getElementById(id);
		if (!modal) {
			return;
		}
		if (openModal && openModal !== modal) {
			openModal.classList.remove('is-open');
			openModal.setAttribute('hidden', '');
		}
		lastFocus = document.activeElement;
		modal.hidden = false;
		modal.classList.add('is-open');
		document.body.classList.add('wa-modal-open');
		openModal = modal;
		var closeBtn = modal.querySelector('.wa-modal__close');
		if (closeBtn) {
			closeBtn.focus();
		}
	}

	document.querySelectorAll('[data-wa-open]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var key = btn.getAttribute('data-wa-open');
			showWaModal(key === 'patient' ? 'waModalPatient' : 'waModalClinic');
		});
	});

	document.querySelectorAll('[data-wa-close]').forEach(function (el) {
		el.addEventListener('click', closeWaModal);
	});

	document.addEventListener('keydown', function (event) {
		if (event.key === 'Escape') {
			closeWaModal();
		}
	});
})();
</script>
@endpush
