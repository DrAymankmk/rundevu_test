@php
$locale = $locale ?? app()->getLocale();
$search = $search ?? request('search', '');
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
	<div>
		<h6 class="mb-1">
			{{ $locale === 'ar' ? $coupon->service_name_ar : ($coupon->service_name_en ?: $coupon->service_name_ar) }}
		</h6>
		<small class="text-muted">
			@lang('main.loyalty_coupon_patients_cities'):
			{{ empty($cityNames) ? '—' : implode(', ', $cityNames) }}
		</small>
	</div>
	<button type="button" class="btn btn-primary btn-send-all-coupon-notifications"
		data-coupon-id="{{ $coupon->id }}"
		{{ empty($cityIds) || ($patients instanceof \Illuminate\Contracts\Pagination\Paginator ? $patients->total() === 0 : $patients->isEmpty()) ? 'disabled' : '' }}>
		@lang('main.loyalty_coupon_send_all')
	</button>
</div>

@if(empty($cityIds))
<div class="alert alert-warning mb-0">@lang('main.loyalty_coupon_no_cities')</div>
@else
<form class="coupon-patients-search-form row g-2 align-items-end mb-3"
	data-coupon-id="{{ $coupon->id }}"
	action="{{ url('admin/loyalty-coupons/' . $coupon->id . '/patients') }}"
	method="GET">
	<div class="col-md-8">
		<label class="form-label mb-1">@lang('main.loyalty_coupon_patients_search')</label>
		<input type="text"
			name="search"
			value="{{ $search }}"
			class="form-control"
			placeholder="@lang('main.loyalty_coupon_patients_search_placeholder')"
			autocomplete="off">
	</div>
	<div class="col-md-4 d-flex gap-2">
		<button type="submit" class="btn btn-primary flex-grow-1">@lang('main.search')</button>
		@if($search !== '')
			<button type="button" class="btn btn-light btn-clear-coupon-patients-search"
				data-coupon-id="{{ $coupon->id }}">
				@lang('admin.clear')
			</button>
		@endif
	</div>
</form>

<div class="table-responsive">
	<table class="table table-sm table-hover align-middle">
		<thead>
			<tr>
				<th>#</th>
				<th>@lang('main.users')</th>
				<th>@lang('main.phone')</th>
				<th>@lang('main.city')</th>
				<th>@lang('main.loyalty_coupon_patient_notifications')</th>
				<th>@lang('admin.Actions')</th>
			</tr>
		</thead>
		<tbody>
			@forelse($patients as $patient)
			@php
			$cityName = $locale === 'ar'
			? (optional($patient->city)->name_ar ?: optional($patient->city)->name_en)
			: (optional($patient->city)->name_en ?: optional($patient->city)->name_ar);
			$userNotifications = $notificationsByUser->get($patient->id, collect());
			@endphp
			<tr data-user-id="{{ $patient->id }}">
				<td>{{ $patient->id }}</td>
				<td>{{ $patient->name }}</td>
				<td>{{ $patient->phone }}</td>
				<td>{{ $cityName ?: '—' }}</td>
				<td class="patient-notifications-cell" style="min-width: 220px;">
					@if($userNotifications->isEmpty())
					<span class="text-muted small">@lang('main.no_data')</span>
					@else
					<ul class="list-unstyled mb-0 small">
						@foreach($userNotifications->take(5) as $notification)
						<li class="mb-1 border-bottom pb-1">
							<strong>{{ $locale === 'ar' ? $notification->title_ar : $notification->title_en }}</strong>
							<div class="text-muted">
								{{ $locale === 'ar' ? $notification->message_ar : $notification->message_en }}
							</div>
							<div class="text-muted">
								{{ optional($notification->created_at)->format('Y-m-d H:i') }}
							</div>
						</li>
						@endforeach
					</ul>
					@if($userNotifications->count() > 5)
					<small
						class="text-muted">+{{ $userNotifications->count() - 5 }}</small>
					@endif
					@endif
				</td>
				<td>
					<button type="button"
						class="btn btn-sm btn-outline-primary btn-send-coupon-notification"
						data-coupon-id="{{ $coupon->id }}"
						data-user-id="{{ $patient->id }}">
						@lang('main.loyalty_coupon_send_notification')
					</button>
				</td>
			</tr>
			@empty
			<tr>
				<td colspan="6" class="text-center text-muted">
					@lang('main.loyalty_coupon_no_patients')</td>
			</tr>
			@endforelse
		</tbody>
	</table>
</div>

@if($patients instanceof \Illuminate\Contracts\Pagination\Paginator && $patients->hasPages())
<div class="mt-2 coupon-patients-pagination">
	{{ $patients->links() }}
</div>
@endif
@endif
