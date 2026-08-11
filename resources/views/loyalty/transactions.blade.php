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
							@lang('main.loyalty_transactions')</li>
					</ol>
				</div>
			</div>
		</div>
	</div>

	<div class="container-fluid">
		@include('loyalty.partials.nav')

		<div class="card">
			<div class="card-body table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th>@lang('main.users')</th>
							<th>@lang('main.type')</th>
							<th>@lang('main.all_points')</th>
							<th>@lang('admin.details_ar')</th>
							<th>@lang('admin.date')</th>
						</tr>
					</thead>
					<tbody>
						@foreach($data['transactions'] as $transaction)
						<tr>
							<td>{{ $transaction->id }}</td>
							<td>{{ optional($transaction->user)->name }}</td>
							<td>{{ $transaction->type }}</td>
							<td>{{ $transaction->points }}</td>
							<td>{{ app()->getLocale() === 'ar' ? $transaction->description_ar : $transaction->description_en }}
							</td>
							<td>{{ $transaction->created_at }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>

				{{ $data['transactions']->links() }}
			</div>
		</div>
	</div>
</div>
@endsection