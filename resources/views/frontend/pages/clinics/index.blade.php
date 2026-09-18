@extends('frontend.layout.app')

@section('content')
<x-breadcrumb :title="__('main.clinics')" />

<section class="space-top space-extra-bottom clinics-listing-sec" style="padding: 40px;">
	<div class="container">
		@include('frontend.pages.clinics.partials.filters')

		<div class="row gy-4">
			@forelse($clinics as $clinic)
			@include('frontend.pages.clinics.partials.clinic_card', ['clinic' => $clinic])
			@empty
			<div class="col-12">
				<div class="alert alert-info mb-0">
					{{ $nearMe ? __('clinics.no_nearby_clinics') : __('clinics.no_clinics') }}
				</div>
			</div>
			@endforelse
		</div>

		@if($clinics->hasPages())
		<div class="th-pagination text-center mt-40">
			{{ $clinics->links() }}
		</div>
		@endif
	</div>
</section>
@endsection

@push('scripts')
<script>
(function() {
	var form = document.getElementById('clinics-filter-form');
	var nearBtn = document.getElementById('clinics-near-me');
	var latInput = document.getElementById('clinics-lat');
	var lngInput = document.getElementById('clinics-lng');
	var errorEl = document.getElementById('clinics-near-error');

	if (!form || !nearBtn || !latInput || !lngInput) {
		return;
	}

	function showError(message) {
		if (!errorEl) {
			return;
		}
		errorEl.textContent = message || '';
		errorEl.classList.toggle('d-none', !message);
	}

	nearBtn.addEventListener('click', function() {
		showError('');

		if (!navigator.geolocation) {
			showError(nearBtn.getAttribute('data-error-unsupported'));
			return;
		}

		var label = nearBtn.querySelector('span');
		var original = label ? label.textContent : '';
		nearBtn.disabled = true;
		if (label) {
			label.textContent = nearBtn.getAttribute(
				'data-loading-text');
		}

		navigator.geolocation.getCurrentPosition(
			function(position) {
				latInput.value = position.coords
					.latitude.toFixed(7);
				lngInput.value = position.coords
					.longitude.toFixed(7);
				form.submit();
			},
			function(error) {
				nearBtn.disabled = false;
				if (label) {
					label.textContent = original;
				}

				if (error && error.code === 1) {
					showError(nearBtn.getAttribute(
						'data-error-denied'
					));
				} else {
					showError(nearBtn.getAttribute(
						'data-error-unavailable'
					));
				}
			}, {
				enableHighAccuracy: true,
				timeout: 12000,
				maximumAge: 60000
			}
		);
	});
})();
</script>
@endpush