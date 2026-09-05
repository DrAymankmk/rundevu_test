@extends('includes_admin.mainlayout')

@section('content')
<div class="page-body">
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col-sm-12">
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a
								href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a>
						</li>
						<li class="breadcrumb-item"><i
								class="feather-chevron-right"></i></li>
						<li class="breadcrumb-item active">
							@lang('clinic_reports.patients')</li>
					</ul>
					<h3 class="mt-2 mb-0">@lang('clinic_reports.patients')</h3>
				</div>
			</div>
		</div>

		<!-- @include('clinic_reports.partials.nav') -->
		@include('clinic_reports.partials.filters', [
		'showDoctor' => true,
		'showSpecialty' => true,
		'showPatient' => true,
		'showGender' => true,
		'searchPlaceholder' => __('clinic_reports.patient_name'),
		])

		@include('clinic_reports.partials.export_buttons', ['exportSection' => 'patients'])

		<div class="row mb-4">
			<div class="col-lg-6">
				<div class="card h-100">
					<div class="card-header">
						<h5 class="mb-0">@lang('clinic_reports.gender_distribution')
						</h5>
					</div>
					<div class="card-body">
						<div id="patients-gender-chart"></div>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="card h-100">
					<div class="card-header">
						<h5 class="mb-0">@lang('clinic_reports.age_distribution')
						</h5>
					</div>
					<div class="card-body">
						<div id="patients-age-chart"></div>
					</div>
				</div>
			</div>
		</div>

		<div class="card mb-4">
			<div class="card-header">
				<h5 class="mb-0">@lang('clinic_reports.most_frequent_patients')</h5>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-sm">
					<thead>
						<tr>
							<th>@lang('clinic_reports.patient_name')</th>
							<th>@lang('clinic_reports.total_reservations')
							</th>
						</tr>
					</thead>
					<tbody>
						@forelse($topPatients['frequent'] as $row)
						<tr>
							<td>{{ $row['patient_name'] }}</td>
							<td>{{ $row['total_reservations'] }}</td>
						</tr>
						@empty
						<tr>
							<td colspan="2" class="text-center">
								@lang('clinic_reports.no_data')</td>
						</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>

		<div class="card">
			<div class="card-body table-responsive">
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>@lang('clinic_reports.id')</th>
							<th>@lang('clinic_reports.patient_name')</th>
							<th>@lang('clinic_reports.phone')</th>
							<th>@lang('clinic_reports.total_reservations')
							</th>
						</tr>
					</thead>
					<tbody>
						@forelse($paginator as $row)
						<tr>
							<td>{{ $row->user_id }}</td>
							<td>{{ $row->patient_name }}</td>
							<td>{{ $row->phone }}</td>
							<td>{{ $row->total_reservations }}</td>
						</tr>
						@empty
						<tr>
							<td colspan="4" class="text-center">
								@lang('clinic_reports.no_data')</td>
						</tr>
						@endforelse
					</tbody>
				</table>
				{{ $paginator->links() }}
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
(function() {
	if (typeof ApexCharts === 'undefined') return;

	new ApexCharts(document.querySelector('#patients-gender-chart'), {
		chart: {
			type: 'bar',
			height: 320,
			toolbar: {
				show: false
			}
		},
		plotOptions: {
			bar: {
				columnWidth: '45%',
				borderRadius: 4
			}
		},
		series: [{
			name: @json(__(
				'clinic_reports.patients'
			)),
			data: [{
					{
						(
							int
						) $demographics
							[
								'gender'
							]
							[
								'male'
							]
					}
				},
				{
					{
						(
							int
						) $demographics
							[
								'gender'
							]
							[
								'female'
							]
					}
				},
				{
					{
						(
							int
						) $demographics
							[
								'gender'
							]
							[
								'other'
							]
					}
				},
				{
					{
						(
							int
						) $demographics
							[
								'gender'
							]
							[
								'unknown'
							]
					}
				}
			]
		}],
		xaxis: {
			categories: [
				@json(__('clinic_reports.male')),
				@json(__('clinic_reports.female')),
				@json(__('clinic_reports.other')),
				@json(__('clinic_reports.unknown'))
			]
		},
		colors: ['#2E7D32'],
		dataLabels: {
			enabled: true
		}
	}).render();

	new ApexCharts(document.querySelector('#patients-age-chart'), {
		chart: {
			type: 'bar',
			height: 320,
			toolbar: {
				show: false
			}
		},
		plotOptions: {
			bar: {
				columnWidth: '45%',
				borderRadius: 4
			}
		},
		series: [{
			name: @json(__(
				'clinic_reports.patients'
			)),
			data: [{
					{
						(
							int
						) $demographics
							[
								'ageGroups'
							]
							[
								'0_18'
							]
					}
				},
				{
					{
						(
							int
						) $demographics
							[
								'ageGroups'
							]
							[
								'19_30'
							]
					}
				},
				{
					{
						(
							int
						) $demographics
							[
								'ageGroups'
							]
							[
								'31_45'
							]
					}
				},
				{
					{
						(
							int
						) $demographics
							[
								'ageGroups'
							]
							[
								'46_60'
							]
					}
				},
				{
					{
						(
							int
						) $demographics
							[
								'ageGroups'
							]
							[
								'60_plus'
							]
					}
				},
				{
					{
						(
							int
						) $demographics
							[
								'ageGroups'
							]
							[
								'unknown'
							]
					}
				}
			]
		}],
		xaxis: {
			categories: [
				@json(__('clinic_reports.age_0_18')),
				@json(__('clinic_reports.age_19_30')),
				@json(__('clinic_reports.age_31_45')),
				@json(__('clinic_reports.age_46_60')),
				@json(__('clinic_reports.age_60_plus')),
				@json(__('clinic_reports.unknown'))
			]
		},
		colors: ['#1565C0'],
		dataLabels: {
			enabled: true
		}
	}).render();
})();
</script>
@endpush