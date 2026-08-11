<?php $page = 'clinics'; ?>
@extends('layout_new.mainlayout')
@section('content')

<!-- ========================
        Start Page Content
    ========================= -->

<div class="page-wrapper">

	<!-- Start Content -->
	<div class="content">

		<!-- Start Page Header -->
		<div
			class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 pb-3 mb-3 border-1 border-bottom">
			<div class="flex-grow-1">
				<h4 class="fw-bold mb-0">{{ trans('main.clinics') }} <span
						class="badge badge-soft-primary fw-medium border py-1 px-2 border-primary fs-13 ms-1">{{ trans('main.Total Clinics') }}
						: {{ $clinics->total() }}</span></h4>
			</div>
			<div class="text-end d-flex">
				<!-- dropdown-->
				{{--                    <div class="dropdown me-1">--}}
				{{--                        <a href="javascript:void(0);" class="btn btn-md fs-14 fw-normal border bg-white rounded text-dark d-inline-flex align-items-center"  data-bs-toggle="dropdown">--}}
				{{--                            Export<i class="ti ti-chevron-down ms-2"></i>--}}
				{{--                        </a>--}}
				{{--                        <ul class="dropdown-menu p-2">--}}
				{{--                            <li>--}}
				{{--                                <a class="dropdown-item" href="#">Download as PDF</a>--}}
				{{--                            </li>--}}
				{{--                            <li>--}}
				{{--                                <a class="dropdown-item" href="#">Download as Excel</a>--}}
				{{--                            </li>--}}
				{{--                        </ul>--}}
				{{--                    </div>--}}
				<!-- <div
					class="bg-white border shadow-sm rounded px-1 pb-0 text-center d-flex align-items-center justify-content-center">
					<a href="{{route('clinics')}}"
						class="bg-light rounded p-1 d-flex align-items-center justify-content-center">
						<i class="ti ti-list fs-14 text-dark"></i></a>
				</div> -->

				<a href="{{route('add-clinic')}}" class="btn btn-primary ms-2 fs-13 btn-md"><i
						class="ti ti-plus me-1"></i>{{ trans('main.add_clinic') }}</a>
			</div>
		</div>
		<!-- End Page Header -->

		<!--  Start Filter -->
		<div class=" d-flex align-items-center justify-content-between flex-wrap">
			<div>
				<div class="search-set mb-3">
					<div class="d-flex align-items-center flex-wrap gap-2">
						<div class="table-search d-flex align-items-center mb-0">
							<div class="search-input">
								<a href="javascript:void(0);"
									class="btn-searchset"></a>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div
				class="d-flex table-dropdown mb-3 right-content align-items-center flex-wrap row-gap-3">
				<div class="dropdown me-2">
					<a href="javascript:void(0);"
						class="bg-white border rounded btn btn-md text-dark fs-14 py-1 align-items-center d-flex fw-normal"
						data-bs-toggle="dropdown" data-bs-auto-close="outside">
						<i
							class="ti ti-filter text-gray-5 me-1"></i>{{ trans('admin.Filters') }}
					</a>
					<div class="dropdown-menu dropdown-lg dropdown-menu-end filter-dropdown p-0"
						id="filter-dropdown">
						<div
							class="d-flex align-items-center justify-content-between border-bottom filter-header">
							<h4 class="mb-0 fw-bold">
								{{ trans('admin.Filter') }}</h4>
							<div class="d-flex align-items-center">
								<a href="javascript:void(0);"
									class="link-danger text-decoration-underline">{{ trans('admin.Clear All') }}</a>
							</div>
						</div>
						<form action="#">
							<div class="filter-body pb-0">
								<div class="mb-3">
									<label
										class="form-label mb-1 text-dark fs-14 fw-medium">Date<span
											class="text-danger">*</span></label>
									<div
										class="input-icon-end position-relative">
										<input type="text"
											class="form-control bookingrange"
											placeholder="dd/mm/yyyy">
										<span
											class="input-icon-addon">
											<i
												class="ti ti-calendar"></i>
										</span>
									</div>
								</div>
								<div class="mb-3">
									<div
										class="d-flex align-items-center justify-content-between">
										<label
											class="form-label">Status</label>
										<a href="javascript:void(0);"
											class="link-primary mb-1">Reset</a>
									</div>
									<div class="dropdown">
										<a href="javascript:void(0);"
											class="dropdown-toggle btn bg-white  d-flex align-items-center justify-content-start fs-13 p-2 fw-normal border"
											data-bs-toggle="dropdown"
											data-bs-auto-close="outside"
											aria-expanded="true">
											Select <i
												class="ti ti-chevron-down ms-auto"></i>
										</a>
										<div
											class="dropdown-menu shadow-lg w-100 dropdown-info p-3">
											<ul
												class="mb-3">
												<li
													class="mb-1">
													<label
														class="dropdown-item px-2 d-flex align-items-center text-dark">
														<input class="form-check-input m-0 me-2"
															type="checkbox">
														Available
													</label>
												</li>
												<li
													class="mb-0">
													<label
														class="dropdown-item px-2 d-flex align-items-center text-dark">
														<input class="form-check-input m-0 me-2"
															type="checkbox">
														Unavailable
													</label>
												</li>
											</ul>
											<div
												class="row g-2">
												<div
													class="col-6">
													<a href="javascript:void(0);"
														class="btn btn-outline-white w-100 close-filter">Cancel</a>
												</div>
												<div
													class="col-6">
													<a href="javascript:void(0);"
														class="btn btn-primary w-100">Select</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div
								class="filter-footer d-flex align-items-center justify-content-end border-top">
								<a href="javascript:void(0);"
									class="btn btn-light btn-md me-2 fw-medium"
									id="close-filter">{{ trans('main.Close') }}</a>
								<button type="submit"
									class="btn btn-primary btn-md fw-medium">{{ trans('admin.Filter') }}</button>
							</div>
						</form>
					</div>
				</div>
				<div class="dropdown">
					<a href="javascript:void(0);"
						class="dropdown-toggle btn bg-white btn-md d-inline-flex align-items-center fw-normal rounded border text-dark px-2 py-1 fs-14"
						data-bs-toggle="dropdown">
						<span class="me-1"> {{ trans('admin.Sort By') }} : </span>
						{{ trans('admin.Recent') }}
					</a>
					<ul class="dropdown-menu  dropdown-menu-end p-2">
						<li>
							<a href="javascript:void(0);"
								class="dropdown-item rounded-1">{{ trans('admin.Recent') }}</a>
						</li>
						<li>
							<a href="javascript:void(0);"
								class="dropdown-item rounded-1">{{ trans('admin.Oldest') }}</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<!--  End Filter -->

		<!--  Start Table -->
		<div class="table-responsive">
			<table class="table datatable table-nowrap">
				<thead class="">
					<tr>
						<th>@lang('admin.image')</th>
						<th>@lang('main.package')</th>
						<th>@lang('main.contract_booking_system')</th>
						<th>@lang('main.expired_date')</th>
						<th>@lang('main.no.branches')</th>
						<th>{{ trans('admin.Created Date') }}</th>
						<th>@lang('admin.status')</th>
						<th>@lang('admin.action')</th>
					</tr>
				</thead>
				<tbody>
					@foreach($clinics as $clinic)
					<tr id="row-{{ $clinic->id }}">
						<td>
							<div class="d-flex align-items-center">
								<a href="{{route('clinic-details', $clinic->id)}}"
									class="avatar avatar-md me-2">
									<img src="{{ $clinic->image }}"
										alt="{{ $clinic->name }}"
										class="rounded-circle">
								</a>
								<a href="{{route('clinic-details',$clinic->id)}}"
									class="text-dark fw-semibold">{{ $clinic->name ?? null }}
									<span
										class="text-body fs-13 fw-normal d-block">
										{{  app()->getLocale() == 'en' ? $clinic->city->name_en ?? null : $clinic->city->name_ar ?? null}}
									</span> </a>
							</div>
						</td>
						<td>{{ $clinic->currentPackage?->name_ar ?? null }}</td>
						<td>
							@php $contractModel = optional($clinic->contract)->contract_model ?? 'cash_commission'; @endphp
							<span class="badge badge-soft-primary border border-primary px-2 py-1 fs-13 fw-medium">
								@if($contractModel === 'annual_subscription')
									@lang('main.contract_annual_subscription_short')
								@elseif($contractModel === 'online_payment_commission')
									@lang('main.contract_online_payment_commission_short')
								@else
									@lang('main.contract_cash_commission_short')
								@endif
							</span>
						</td>
						<td>{{ $clinic->package_end_date }}</td>
						<td>{{  0 }}</td>
						<td>{{ $clinic->created_at->format('d M Y') }}</td>
						<td>
							<div class="d-inline-block me-2">
								<div
									class="form-check form-switch ps-0 mb-0">
									<input type="checkbox"
										id="clinic{{$clinic->id}}"
										class="form-check-input m-0"
										{{ $clinic->status == 1 ? 'checked' : '' }}
										onchange="change_status_clinic({{ $clinic->id }},{{ $clinic->status }})">

									{{--                                        <input class="form-check-input m-0" type="checkbox" {{ $clinic->status == 1 ? 'checked' : '' }}>--}}
								</div>
							</div>
							{{--                                <span class="badge--}}
							{{--        {{ $clinic->status == 1 ? 'badge-soft-success border border-success' : 'badge-soft-danger border border-danger' }}--}}
							{{--        d-inline-block px-2 py-1 fs-13 fw-medium">--}}
							{{--        {{ $clinic->status == 1 ? __('admin.Active') : __('admin.Inactive') }}--}}
							{{--    </span>--}}
						</td>
						<td>
							<a href="{{ route('clinic-details', $clinic->id) }}"
								class="link-reset fs-18 p-1"> <i
									class="ti ti-eye"></i></a>
							@if(($clinic->doctors_count ?? 0) == 0)
							<a href="javascript:void(0);"
								class="link-reset fs-18 p-1 delete-btn"
								data-route="{{ route('destroy-clinic', $clinic->id) }}"
								data-id="row-{{ $clinic->id }}"
								data-bs-toggle="modal"
								data-bs-target="#genericDeleteModal">
								<i class="ti ti-trash"></i>
							</a>
							@endif
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		<!--  End Table -->
	</div>
	<!-- End Content -->
	{{ $clinics->links() }}

	@component('components.footer')
	@endcomponent

	@include('layout_new.partials.delete_modal')

</div>

<!-- ========================
        End Page Content
    ========================= -->


<script>
function change_status_clinic(id, value) {
	if (value == 0) {
		value = 1;
	} else {
		value = 0;
	}
	$.ajax({
		url: '/admin/update-status-clinic/' + id + '/' + value,
		type: 'get',
		dataType: 'json',
		success: function(data) {
			showToast(data.message); // مثلاً: "تم الحذف بنجاح"
			window.location.replace(data.route);
		}
	});
}
</script>

@endsection
