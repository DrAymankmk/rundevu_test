@extends('layout_new.mainlayout')

@push('styles')
<style>
.visit-event-timeline { display: flex; flex-direction: column; gap: 12px; }
.visit-event-item { display: flex; gap: 12px; align-items: flex-start; padding: 12px; border: 1px solid rgba(15, 23, 42, 0.08); border-radius: 12px; background: #fff; }
.visit-event-icon { width: 36px; height: 36px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; flex: 0 0 36px; color: #fff; font-size: 16px; }
.visit-event-item--primary .visit-event-icon { background: #3E66F3; }
.visit-event-item--success .visit-event-icon { background: #198754; }
.visit-event-item--info .visit-event-icon { background: #0dcaf0; color: #083344; }
.visit-event-item--warning .visit-event-icon { background: #ffc107; color: #3a2d00; }
.visit-event-item--secondary .visit-event-icon { background: #6c757d; }
.visit-event-body { min-width: 0; flex: 1; }
.visit-event-details { padding: 0; margin: 0; list-style: none; font-size: 12px; }
.visit-event-details li + li { margin-top: 2px; }
</style>
@endpush

@section('content')
<div class="page-wrapper" style="padding:20px">
	<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
		<div>
			<h4 class="page-title mb-1">{{ __('website_visit_logs.details') }} #{{ $log->id }}</h4>
			<p class="text-muted mb-0">{{ optional($log->visited_at)->format('Y-m-d H:i:s') }}</p>
		</div>
		<div class="d-flex gap-2">
			<a href="{{ route('website-visit-logs.index') }}" class="btn btn-light">{{ __('website_visit_logs.back_to_list') }}</a>
			<button type="button" class="btn btn-danger" id="delete-visit-btn">
				<i class="ti ti-trash"></i> {{ __('website_visit_logs.delete') }}
			</button>
		</div>
	</div>

	<div class="row g-3">
		<div class="col-lg-6">
			<div class="card h-100">
				<div class="card-body">
					<table class="table table-borderless mb-0">
						<tr>
							<th>{{ __('website_visit_logs.type') }}</th>
							<td>
								@if($log->is_bot)
									<span class="badge bg-warning text-dark">{{ __('website_visit_logs.bot') }}{{ $log->bot_name ? ': '.$log->bot_name : '' }}</span>
								@else
									<span class="badge bg-success">{{ __('website_visit_logs.human') }}</span>
								@endif
							</td>
						</tr>
						<tr>
							<th>{{ __('website_visit_logs.ip') }}</th>
							<td>{{ $log->ip ?: '—' }}</td>
						</tr>
						<tr>
							<th>{{ __('website_visit_logs.locale') }}</th>
							<td>{{ $log->locale ?: '—' }}</td>
						</tr>
						<tr>
							<th>{{ __('website_visit_logs.page') }}</th>
							<td>
								<div>{{ \App\Services\WebsiteVisitLogs\VisitUrlDisplay::decode($log->page_path) ?: $log->page_path }}</div>
								<small class="text-muted">{{ __('website_visit_logs.page_types.'.$log->page_type) }}</small>
							</td>
						</tr>
						<tr>
							<th>{{ __('website_visit_logs.page_url') }}</th>
							<td class="text-break">{{ \App\Services\WebsiteVisitLogs\VisitUrlDisplay::decode($log->page_url) ?: $log->page_url }}</td>
						</tr>
						<tr>
							<th>{{ __('website_visit_logs.page_title') }}</th>
							<td>{{ $log->page_title ?: '—' }}</td>
						</tr>
						<tr>
							<th>{{ __('website_visit_logs.entity') }}</th>
							<td>
								@if($log->entity_type)
									{{ __('website_visit_logs.entity_types.'.$log->entity_type) }}
									@if($log->entity_slug) : {{ \App\Services\WebsiteVisitLogs\VisitUrlDisplay::decode($log->entity_slug) ?: $log->entity_slug }} @endif
									@if($log->entity_id) (#{{ $log->entity_id }}) @endif
								@else
									—
								@endif
							</td>
						</tr>
						<tr>
							<th>{{ __('website_visit_logs.time_spent') }}</th>
							<td>{{ $log->formattedTimeSpent() }}</td>
						</tr>
						<tr>
							<th>{{ __('website_visit_logs.referer') }}</th>
							<td class="text-break">{{ \App\Services\WebsiteVisitLogs\VisitUrlDisplay::decode($log->referer) ?: ($log->referer ?: '—') }}</td>
						</tr>
						<tr>
							<th>{{ __('website_visit_logs.referer_host') }}</th>
							<td>{{ $log->referer_host ?: '—' }}</td>
						</tr>
						<tr>
							<th>{{ __('website_visit_logs.user_agent') }}</th>
							<td class="text-break small">{{ $log->user_agent ?: '—' }}</td>
						</tr>
						<tr>
							<th>{{ __('website_visit_logs.session_id') }}</th>
							<td class="small">{{ $log->session_id ?: '—' }}</td>
						</tr>
						<!-- <tr>
							<th>{{ __('website_visit_logs.visitor_token') }}</th>
							<td class="small">{{ $log->visitor_token ?: '—' }}</td>
						</tr> -->
					</table>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="card h-100">
				<div class="card-body">
					<h6 class="mb-3">{{ __('website_visit_logs.event_timeline') }} ({{ count($events) }})</h6>
					@if(empty($events))
						<p class="text-muted mb-0">{{ __('website_visit_logs.no_events') }}</p>
					@else
						<div class="visit-event-timeline">
							@foreach($events as $event)
								<div class="visit-event-item visit-event-item--{{ $event['tone'] ?? 'primary' }}">
									<div class="visit-event-icon"><i class="ti {{ $event['icon'] ?? 'ti-activity' }}"></i></div>
									<div class="visit-event-body">
										<div class="d-flex justify-content-between gap-2 flex-wrap">
											<strong>{{ $event['label'] ?? ($event['name'] ?? '—') }}</strong>
											<small class="text-muted">{{ $event['at_label'] ?? ($event['at'] ?? '—') }}</small>
										</div>
										@if(!empty($event['summary']))
											<div class="text-muted small mt-1">{{ $event['summary'] }}</div>
										@endif
										@if(!empty($event['details']))
											<ul class="visit-event-details mb-0 mt-1">
												@foreach($event['details'] as $detail)
													<li>
														<span class="text-muted">{{ $detail['label'] }}:</span>
														@if(!empty($detail['is_url']))
															<a href="{{ $detail['value'] }}" target="_blank" rel="noopener noreferrer" class="text-break">{{ $detail['value'] }}</a>
														@else
															<span class="text-break">{{ $detail['value'] }}</span>
														@endif
													</li>
												@endforeach
											</ul>
										@endif
									</div>
								</div>
							@endforeach
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script src="{{ URL::asset('build/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
$('#delete-visit-btn').on('click', function () {
	Swal.fire({
		title: '{{ __('website_visit_logs.are_you_sure') }}',
		text: '{{ __('website_visit_logs.delete_confirm') }}',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#d33',
		cancelButtonColor: '#3085d6',
		confirmButtonText: '{{ __('website_visit_logs.yes_delete') }}',
		cancelButtonText: '{{ __('website_visit_logs.cancel') }}'
	}).then(function (result) {
		if (!result.isConfirmed) return;
		$.ajax({
			url: "{{ route('website-visit-logs.destroy', $log->id) }}",
			type: 'DELETE',
			headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
			success: function () {
				window.location.href = "{{ route('website-visit-logs.index') }}";
			}
		});
	});
});
</script>
@endpush
