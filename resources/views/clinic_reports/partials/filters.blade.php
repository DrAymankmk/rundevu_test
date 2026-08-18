@php
$f = $filters;
$showStatus = $showStatus ?? false;
$showDoctor = $showDoctor ?? false;
$showSpecialty = $showSpecialty ?? false;
$showPatient = $showPatient ?? false;
$showGender = $showGender ?? false;
$searchPlaceholder = $searchPlaceholder ?? __('clinic_reports.search_placeholder');
@endphp
<style>
	.clinic-reports-filters-toggle {
		background: transparent;
		border: 0;
		width: 100%;
		padding: 0;
		color: inherit;
		text-align: inherit;
	}
	.clinic-reports-filters-toggle .clinic-reports-filters-chevron {
		transition: transform .2s ease;
	}
	.clinic-reports-filters-toggle.collapsed .clinic-reports-filters-chevron {
		transform: rotate(-90deg);
	}
	[dir="rtl"] .clinic-reports-filters-toggle.collapsed .clinic-reports-filters-chevron {
		transform: rotate(90deg);
	}
</style>
<div class="card mb-4">
	<div class="card-header">
		<button type="button"
			class="clinic-reports-filters-toggle d-flex align-items-center justify-content-between"
			data-bs-toggle="collapse"
			data-toggle="collapse"
			data-bs-target="#clinic-reports-filters"
			data-target="#clinic-reports-filters"
			aria-expanded="true"
			aria-controls="clinic-reports-filters">
			<h5 class="mb-0">@lang('clinic_reports.filters')</h5>
			<i class="fa fa-chevron-down clinic-reports-filters-chevron" aria-hidden="true"></i>
		</button>
	</div>
	<div id="clinic-reports-filters" class="collapse show">
	<div class="card-body">
		<form method="get" action="{{ url()->current() }}">
			<div class="row g-3">
				<div class="col-md-3">
					<label class="form-label">@lang('clinic_reports.date_filter')</label>
					<select name="date_preset" class="form-control"
						onchange="toggleClinicReportDates(this.value)">
						<option value="daily"
							{{ in_array($f['preset'], ['daily', 'today'], true) ? 'selected' : '' }}>
							@lang('clinic_reports.daily')</option>
						<option value="weekly"
							{{ in_array($f['preset'], ['weekly', 'this_week'], true) ? 'selected' : '' }}>
							@lang('clinic_reports.weekly')</option>
						<option value="monthly"
							{{ in_array($f['preset'], ['monthly', 'this_month'], true) ? 'selected' : '' }}>
							@lang('clinic_reports.monthly')</option>
						<option value="custom"
							{{ $f['preset'] === 'custom' ? 'selected' : '' }}>
							@lang('clinic_reports.custom')</option>
					</select>
				</div>
				<div class="col-md-2 clinic-report-custom-dates"
					style="{{ $f['preset'] === 'custom' ? '' : 'display:none' }}">
					<label class="form-label">@lang('clinic_reports.date_from')</label>
					<input type="date" name="date_from" class="form-control"
						value="{{ request('date_from', $f['start']->toDateString()) }}">
				</div>
				<div class="col-md-2 clinic-report-custom-dates"
					style="{{ $f['preset'] === 'custom' ? '' : 'display:none' }}">
					<label class="form-label">@lang('clinic_reports.date_to')</label>
					<input type="date" name="date_to" class="form-control"
						value="{{ request('date_to', $f['end']->toDateString()) }}">
				</div>

				@if($showStatus)
				<div class="col-md-3">
					<label class="form-label">@lang('clinic_reports.status')</label>
					<select name="status_id" class="form-control">
						<option value="">@lang('clinic_reports.all_statuses')
						</option>
						@foreach($options['statuses'] as $status)
						<option value="{{ $status->id }}"
							{{ (string)($f['status_id'] ?? '') === (string)$status->id ? 'selected' : '' }}>
							{{ $status->{'name_' . app()->getLocale()} }}
						</option>
						@endforeach
					</select>
				</div>
				@endif

				@if($showDoctor)
				<div class="col-md-3">
					<label class="form-label">@lang('clinic_reports.doctor_name')</label>
					<select name="doctor_id" class="form-control">
						<option value="">@lang('clinic_reports.all_doctors')
						</option>
						@foreach($options['doctors'] as $doctor)
						<option value="{{ $doctor->id }}"
							{{ (string)($f['doctor_id'] ?? '') === (string)$doctor->id ? 'selected' : '' }}>
							{{ $doctor->name }}
						</option>
						@endforeach
					</select>
				</div>
				@endif

				@if($showSpecialty)
				<div class="col-md-3">
					<label class="form-label">@lang('clinic_reports.specialty')</label>
					<select name="specialty_id" class="form-control">
						<option value="">@lang('clinic_reports.all_specialties')
						</option>
						@foreach($options['specialties'] as $specialty)
						<option value="{{ $specialty->id }}"
							{{ (string)($f['specialty_id'] ?? '') === (string)$specialty->id ? 'selected' : '' }}>
							{{ $specialty->{'name_' . app()->getLocale()} }}
						</option>
						@endforeach
					</select>
				</div>
				@endif

				@if($showPatient)
				<div class="col-md-3">
					<label class="form-label">@lang('clinic_reports.patient_name')</label>
					<select name="patient_id" class="form-control">
						<option value="">@lang('clinic_reports.all_patients')
						</option>
						@foreach($options['patients'] as $patient)
						<option value="{{ $patient->id }}"
							{{ (string)($f['patient_id'] ?? '') === (string)$patient->id ? 'selected' : '' }}>
							{{ $patient->name }}
						</option>
						@endforeach
					</select>
				</div>
				@endif

				@if($showGender)
				<div class="col-md-2">
					<label
						class="form-label">@lang('clinic_reports.gender_distribution')</label>
					<select name="gender" class="form-control">
						<option value="">@lang('clinic_reports.all_genders')
						</option>
						<option value="1"
							{{ (string)($f['gender'] ?? '') === '1' ? 'selected' : '' }}>
							@lang('clinic_reports.male')</option>
						<option value="2"
							{{ (string)($f['gender'] ?? '') === '2' ? 'selected' : '' }}>
							@lang('clinic_reports.female')</option>
						<!-- <option value="3"
							{{ (string)($f['gender'] ?? '') === '3' ? 'selected' : '' }}>
							@lang('clinic_reports.other')</option> -->
					</select>
				</div>
				@endif

				<div class="col-md-3">
					<label class="form-label">@lang('clinic_reports.search')</label>
					<input type="text" name="search" class="form-control"
						placeholder="{{ $searchPlaceholder }}"
						value="{{ $f['search'] }}">
				</div>

				<div class="col-md-12">
					<button type="submit"
						class="btn btn-primary">@lang('clinic_reports.apply')</button>
					<a href="{{ url()->current() }}"
						class="btn btn-light">@lang('clinic_reports.reset')</a>
				</div>
			</div>
		</form>
	</div>
	</div>
</div>
<script>
function toggleClinicReportDates(value) {
	document.querySelectorAll('.clinic-report-custom-dates').forEach(function(el) {
		el.style.display = value === 'custom' ? '' : 'none';
	});
}
</script>
