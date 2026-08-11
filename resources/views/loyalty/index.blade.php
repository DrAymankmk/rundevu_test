@extends('includes_admin.mainlayout')
@section('content')
<div class="page-body">
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a
								href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a>
						</li>
						<li class="breadcrumb-item active">
							@lang('main.loyalty_program')</li>
					</ol>
				</div>
			</div>
		</div>
	</div>

	<div class="container-fluid">
		@include('loyalty.partials.nav')

		@include('loyalty.partials.process-guide')

		<div class="row">
			<div class="col-md-3">
				<div class="card">
					<div class="card-body">
						<h6>@lang('main.loyalty_active_coupons')</h6>
						<h3>{{ $data['active_coupons_count'] }}</h3>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="card">
					<div class="card-body">
						<h6>@lang('main.loyalty_pending_redemptions')</h6>
						<h3>{{ $data['pending_redemptions_count'] }}</h3>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="card">
					<div class="card-body">
						<h6>@lang('main.loyalty_points_earned')</h6>
						<h3>{{ $data['points_earned'] }}</h3>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="card">
					<div class="card-body">
						<h6>@lang('main.loyalty_points_spent')</h6>
						<h3>{{ $data['points_spent'] }}</h3>
					</div>
				</div>
			</div>
		</div>

		<div class="row mt-3">
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">
						<h5>@lang('main.loyalty_earning_rules')</h5>
					</div>
					<div class="card-body table-responsive">
						<table class="table table-sm">
							<thead>
								<tr>
									<th>@lang('admin.name')</th>
									<th>@lang('main.all_points')
									</th>
								</tr>
							</thead>
							<tbody>
								@foreach($data['rules'] as $rule)
								<tr>
									<td>{{ app()->getLocale() === 'ar' ? $rule->name_ar : $rule->name_en }}
									</td>
									<td>{{ $rule->points }}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="card">
					<div class="card-header">
						<h5>@lang('main.loyalty_recent_redemptions')</h5>
					</div>
					<div class="card-body table-responsive">
						<table class="table table-sm">
							<thead>
								<tr>
									<th>@lang('main.users')</th>
									<th>@lang('admin.status')</th>
									<th>@lang('main.all_points')
									</th>
								</tr>
							</thead>
							<tbody>
								@forelse($data['recent_redemptions'] as
								$redemption)
								<tr>
									<td>{{ optional($redemption->user)->name }}
									</td>
									<td>{{ $redemption->status }}
									</td>
									<td>{{ $redemption->points_spent }}
									</td>
								</tr>
								@empty
								<tr>
									<td colspan="3"
										class="text-muted">
										@lang('main.no_data')
									</td>
								</tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="row mt-3">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h5>@lang('main.loyalty_recent_transactions')</h5>
					</div>
					<div class="card-body table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th>@lang('main.users')</th>
									<th>@lang('main.type')</th>
									<th>@lang('main.all_points')
									</th>
									<th>@lang('admin.date')</th>
								</tr>
							</thead>
							<tbody>
								@forelse($data['recent_transactions'] as
								$transaction)
								<tr>
									<td>{{ optional($transaction->user)->name }}
									</td>
									<td>{{ $transaction->type }}
									</td>
									<td>{{ $transaction->points }}
									</td>
									<td>{{ $transaction->created_at }}
									</td>
								</tr>
								@empty
								<tr>
									<td colspan="4"
										class="text-muted">
										@lang('main.no_data')
									</td>
								</tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection