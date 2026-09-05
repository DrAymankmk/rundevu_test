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
							@lang('clinic_reports.reviews')</li>
					</ul>
					<h3 class="mt-2 mb-0">@lang('clinic_reports.reviews')</h3>
				</div>
			</div>
		</div>

		<!-- @include('clinic_reports.partials.nav') -->
		@include('clinic_reports.partials.filters', [
		'showDoctor' => true,
		'showSpecialty' => true,
		'searchPlaceholder' => __('clinic_reports.doctor_name'),
		])

		@include('clinic_reports.partials.export_buttons', ['exportSection' => 'reviews'])

		<div class="row mb-3">
			<div class="col-md-4">
				<div class="card">
					<div class="card-body">
						<p class="text-muted mb-1">
							@lang('clinic_reports.clinic_avg_rating')</p>
						<h3>{{ number_format($ratings['clinic_avg_rating'], 2) }}
						</h3>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card">
					<div class="card-body">
						<p class="text-muted mb-1">
							@lang('clinic_reports.total_ratings')</p>
						<h3>{{ $ratings['total_reviews'] }}</h3>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card">
					<div class="card-body">
						<p class="text-muted mb-1">
							@lang('clinic_reports.negative_ratings')</p>
						<h3>{{ $ratings['negative_reviews_count'] }}</h3>
					</div>
				</div>
			</div>
		</div>

		<div class="card mb-4">
			<div class="card-header">
				<h5 class="mb-0">@lang('clinic_reports.ratings_breakdown')</h5>
			</div>
			<div class="card-body">
				<div class="row">
					@for($i = 5; $i >= 1; $i--)
					<div class="col">
						<p class="mb-1">{{ $i }} @lang('clinic_reports.stars')</p>
						<h4>{{ $ratings['breakdown'][$i] ?? 0 }}</h4>
					</div>
					@endfor
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-6 mb-4">
				<div class="card h-100">
					<div class="card-header">
						<h5 class="mb-0">@lang('clinic_reports.doctor_avg_rating')
						</h5>
					</div>
					<div class="card-body table-responsive">
						<table class="table table-sm">
							<thead>
								<tr>
									<th>@lang('clinic_reports.doctor_name')
									</th>
									<th>@lang('clinic_reports.avg_rating')
									</th>
									<th>@lang('clinic_reports.reviews_count')
									</th>
								</tr>
							</thead>
							<tbody>
								@forelse($ratings['by_doctor'] as $row)
								<tr>
									<td>{{ $row['doctor_name'] }}
									</td>
									<td>{{ $row['avg_rating'] }}
									</td>
									<td>{{ $row['reviews'] }}</td>
								</tr>
								@empty
								<tr>
									<td colspan="3"
										class="text-center">
										@lang('clinic_reports.no_data')
									</td>
								</tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-lg-6 mb-4">
				<div class="card h-100">
					<div class="card-header">
						<h5 class="mb-0">@lang('clinic_reports.ratings_over_period')
						</h5>
					</div>
					<div class="card-body table-responsive">
						<table class="table table-sm">
							<thead>
								<tr>
									<th>@lang('clinic_reports.period')
									</th>
									<th>@lang('clinic_reports.avg_rating')
									</th>
									<th>@lang('clinic_reports.reviews_count')
									</th>
								</tr>
							</thead>
							<tbody>
								@forelse($ratings['by_month'] as $row)
								<tr>
									<td>{{ $row->period }}</td>
									<td>{{ round((float) $row->avg_rating, 2) }}
									</td>
									<td>{{ $row->reviews }}</td>
								</tr>
								@empty
								<tr>
									<td colspan="3"
										class="text-center">
										@lang('clinic_reports.no_data')
									</td>
								</tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="card">
			<div class="card-header">
				<h5 class="mb-0">@lang('clinic_reports.negative_ratings') —
					@lang('clinic_reports.comment')</h5>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>@lang('clinic_reports.patient_name')</th>
							<th>@lang('clinic_reports.doctor_name')</th>
							<th>@lang('clinic_reports.rating')</th>
							<th>@lang('clinic_reports.comment')</th>
							<th>@lang('clinic_reports.date')</th>
						</tr>
					</thead>
					<tbody>
						@forelse($ratings['negative_reviews'] as $review)
						<tr>
							<td>{{ optional($review->users)->name }}</td>
							<td>{{ optional($review->doctors)->name }}</td>
							<td>{{ $review->rate_value }}</td>
							<td>{{ $review->comment }}</td>
							<td>{{ optional($review->created_at)->format('Y-m-d') }}
							</td>
						</tr>
						@empty
						<tr>
							<td colspan="5" class="text-center">
								@lang('clinic_reports.no_data')</td>
						</tr>
						@endforelse
					</tbody>
				</table>
				{{ $ratings['negative_reviews']->withQueryString()->links() }}
			</div>
		</div>
	</div>
</div>
@endsection