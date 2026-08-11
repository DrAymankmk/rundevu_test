@extends('layout_new.mainlayout')
{{--@extends('includes_admin.mainlayout')--}}
@section('content')
<div class="page-wrapper">
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">
							<h5 class="card-title mb-4">
								@lang('main.demo-request')
								({{ $demoRequests->count() }})</h5>

							<table id="demo-requests-table"
								class="table border-0 custom-table comman-table datatable mb-0">
								<thead>
									<tr>
										<th>#</th>
										<th>@lang('main.name')
										</th>
										<th>@lang('main.clinic_name')
										</th>
										<th>@lang('main.email')
										</th>
										<th>@lang('main.phone')
										</th>
										<th>@lang('main.created_date')
										</th>
									</tr>
								</thead>
								<tbody>
									@forelse($demoRequests as
									$index => $demoRequest)
									<tr>
										<td>{{ $index + 1 }}
										</td>
										<td>{{ $demoRequest->name }}
										</td>
										<td>{{ $demoRequest->clinic_name }}
										</td>
										<td>{{ $demoRequest->email }}
										</td>
										<td>{{ $demoRequest->phone }}
										</td>
										<td>{{ $demoRequest->created_at?->format('d M Y H:i') }}
										</td>
									</tr>
									@empty
									<tr>
										<td colspan="6"
											class="text-center">
											@lang('main.no_demo_requests_yet')
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
</div>
@endsection
@push('scripts')
<script>
$(document).ready(function() {
	var table = $('#demo-requests-table').DataTable();
});
</script>
@endpush
