@extends('includes_admin.mainlayout')
@php $locale = app()->getLocale(); @endphp

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
							@lang('clinic_reports.appointments')</li>
					</ul>
					<h3 class="mt-2 mb-0">@lang('clinic_reports.appointments')</h3>
				</div>
			</div>
		</div>

		<!-- @include('clinic_reports.partials.nav') -->
		@include('clinic_reports.partials.filters', [
		'showStatus' => true,
		'showDoctor' => true,
		'showSpecialty' => true,
		'showPatient' => true,
		'searchPlaceholder' => __('clinic_reports.search_placeholder'),
		])

		@include('clinic_reports.partials.export_buttons', ['exportSection' => 'appointments'])

		<div class="card">
			<div class="card-body table-responsive">
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>@lang('clinic_reports.id')</th>
							<th>@lang('clinic_reports.patient_name')</th>
							<th>@lang('clinic_reports.doctor_name')</th>
							<th>@lang('clinic_reports.specialty')</th>
							<th>@lang('clinic_reports.date')</th>
							<th>@lang('clinic_reports.time')</th>
							<th>@lang('clinic_reports.status')</th>
						</tr>
					</thead>
					<tbody>
						@forelse($reservations as $item)
						<tr>
							<td>{{ $item->id }}</td>
							<td>{{ optional($item->user)->name }}</td>
							<td>{{ optional($item->doctor)->name }}</td>
							<td>{{ optional($item->specialty)->{'name_' . $locale} }}
							</td>
							<td>{{ $item->date }}</td>
							<td>{{ $item->appointment }}</td>
							<td>{{ optional($item->reservation_status)->{'name_' . $locale} }}
							</td>
						</tr>
						@empty
						<tr>
							<td colspan="7" class="text-center">
								@lang('clinic_reports.no_data')</td>
						</tr>
						@endforelse
					</tbody>
				</table>
				{{ $reservations->links() }}
			</div>
		</div>
	</div>
</div>
@endsection