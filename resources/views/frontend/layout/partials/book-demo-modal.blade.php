@if(session('book_demo_submitted'))
<div class="alert alert-success position-fixed px-4 py-3 shadow"
	style="z-index: 1080; top: 1rem; left: 50%; transform: translateX(-50%); max-width: 90%;" role="alert"
	id="book-demo-flash">
	{{ session('book_demo_submitted') }}
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
	setTimeout(function() {
		var el = document.getElementById('book-demo-flash');
		if (el) el.remove();
	}, 5000);
});
</script>
@endif

<style>
	#bookDemoModal.book-demo-modal-top {
		z-index: 10000010 !important;
	}

	#bookDemoModal.book-demo-modal-top .modal-dialog {
		z-index: 10000011 !important;
	}

	body.book-demo-modal-open .modal-backdrop {
		z-index: 10000009 !important;
	}
</style>

<div class="modal fade book-demo-modal-top" id="bookDemoModal" tabindex="-1" aria-labelledby="bookDemoModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h5 class="modal-title" id="bookDemoModalLabel">{{ __('main.book_demo') }}</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"
					aria-label="{{ __('main.Close') }}"></button>
			</div>
			<form action="{{ route('frontend.book_demo') }}" method="POST">
				@csrf
				<div class="modal-body pt-2">
					<div class="mb-3">
						<label class="form-label"
							for="book-demo-clinic-name">{{ __('main.clinic_name') }}</label>
						<input type="text" class="form-control"
							id="book-demo-clinic-name" name="clinic_name"
							value="{{ old('clinic_name') }}" required
							maxlength="255"
							placeholder="{{ __('main.clinic_name') }}">
					</div>
					<div class="mb-3">
						<label class="form-label"
							for="book-demo-name">{{ __('main.name') }}</label>
						<input type="text" class="form-control" id="book-demo-name"
							name="name" value="{{ old('name') }}" required
							maxlength="255"
							placeholder="{{ __('main.name') }}">
					</div>
					<div class="mb-3">
						<label class="form-label"
							for="book-demo-email">{{ __('main.email') }}</label>
						<input type="email" class="form-control"
							id="book-demo-email" name="email"
							value="{{ old('email') }}" required
							maxlength="255"
							placeholder="{{ __('main.email') }}">
					</div>
					<div class="mb-0">
						<label class="form-label"
							for="book-demo-phone">{{ __('main.phone_number') }}</label>
						<input type="tel" class="form-control" id="book-demo-phone"
							name="phone" value="{{ old('phone') }}" required
							maxlength="50"
							placeholder="{{ __('main.phone_number') }}">
					</div>
					@if ($errors->getBag('bookDemo')->isNotEmpty())
					<div class="alert alert-danger mt-3 mb-0" role="alert">
						<ul class="mb-0 ps-3">
							@foreach ($errors->getBag('bookDemo')->all() as
							$err)
							<li>{{ $err }}</li>
							@endforeach
						</ul>
					</div>
					@endif
				</div>
				<div class="modal-footer border-0 pt-0">
					<button type="button"
						style="background:transparent!important;color:var(--theme-color,#3b82f6)!important;border-color:var(--theme-color,#3b82f6)!important;box-shadow:none!important;transform:none!important"
						class="th-btn th-border"
						data-bs-dismiss="modal">{{ __('main.Close') }}</button>
					<button type="submit"
						class="th-btn">{{ __('main.book_demo_submit') }}</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var modal = document.getElementById('bookDemoModal');
	if (!modal || typeof bootstrap === 'undefined' || !bootstrap.Modal) {
		return;
	}

	modal.addEventListener('show.bs.modal', function() {
		document.body.classList.add('book-demo-modal-open');
	});

	modal.addEventListener('hidden.bs.modal', function() {
		document.body.classList.remove('book-demo-modal-open');
	});

	@if ($errors->getBag('bookDemo')->isNotEmpty())
		new bootstrap.Modal(modal).show();
	@endif
});
</script>