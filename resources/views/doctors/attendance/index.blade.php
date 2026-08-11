@extends('includes_admin.mainlayout')
@section('content')

    <div class="page-wrapper">
        <div class="content">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.dashboard') }}">@lang('admin.dashboard') </a></li>
                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                            <li class="breadcrumb-item active">@lang('admin.doctor.Attendance and Departure')</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">

                    <div class="card card-table show-entire">
                        <div class="card-body">

                            <!-- Table Header -->
                            <div class="page-table-header mb-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="doctor-table-blk">
                                            <h3>@lang('admin.Attendance and Departure') ({{ $data['employee']->name }}
                                                )</h3>
                                            <div class="doctor-search-blk">
                                                <div class="top-nav-search table-search-blk">
                                                    <form>
                                                        <input type="text" class="form-control"
                                                               placeholder="@lang('admin.search_here')">
                                                        <a class="btn"><img src="/assets/img/icons/search-normal.svg"
                                                                            alt=""></a>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="javascript:;" class=" me-2"><img
                                                src="/assets/img/icons/pdf-icon-01.svg" alt=""></a>
                                        <a href="javascript:;" class=" me-2"><img
                                                src="/assets/img/icons/pdf-icon-02.svg" alt=""></a>
                                        <a href="javascript:;" class=" me-2"><img
                                                src="/assets/img/icons/pdf-icon-03.svg" alt=""></a>
                                        <a href="javascript:;"><img src="/assets/img/icons/pdf-icon-04.svg" alt=""></a>
                                    </div>
                                </div>
                            </div>
                            <!-- /Table Header -->
                            <div class="staff-search-table">
                                <form action="{{ route('doctor-attendance-departure') }}" method="get">
                                    <div class="row">
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="form-group local-forms cal-icon">
                                                <label>@lang('admin.From') </label>
                                                <input class="form-control datetimepicker" type="text" name="date_from">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="form-group local-forms cal-icon">
                                                <label>@lang('admin.To') </label>
                                                <input class="form-control datetimepicker" type="text" name="date_to">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-xl-4 ms-auto">
                                            <div class="doctor-submit">
                                                <button type="submit"
                                                        class="btn btn-primary submit-list-form me-2">@lang('admin.Search')</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>


                            <div class="card invoices-tabs-card">
                                <div class="card-body card-body pt-0 pb-0">
                                    <div class="invoices-main-tabs">
                                        <div class="row align-items-center">

                                            <div class="col-lg-4 col-md-4">
                                                <div class="invoices-settings-btn">
                                                    <a class="invoices-settings-icon">
                                                        <img src="{{ auth()->user()->image ?? null }}"
                                                             style="width: 20px;height: 20px"/>
                                                    </a>
                                                    <a href="{{route('employee-clinic-permissions',$data['employee']->id)}}"
                                                       class="btn">
                                                        <i data-feather="eye"></i> @lang('admin.view_employee_permissions')
                                                        ({{auth()->user()->name}})
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="table-responsive">
                                <table class="table border-0 custom-table comman-table datatable mb-0">
                                    <thead>
                                    <tr>
                                        <th style="display: none">#</th>
                                        <th>@lang('admin.date')</th>
                                        <th>@lang('admin.day')</th>
                                        <th>@lang('admin.audience')</th>
                                        <th>@lang('admin.leave')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data['attendance_departure'] as $index=>$attendance)
                                        <tr>
                                            <td style="display: none">{{ $index + 1 }}</td>
                                            <td>{{ $attendance->dateA }}</td>
                                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $attendance->dateA)->dayName }}</td>
                                            <td>{{ $attendance->audience }}</td>
                                            <td>{{ $attendance->leave }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{ $data['attendance_departure']->links() }}
                    </div>
                </div>
            </div>

            <div class="col-xl-5 col-md-6 col-sm-12 col-12">
                <h4 class="invoice-details-title">@lang('admin.Attendance and Departure')</h4>
                <div class="invoice-details-box">
                    <div class="invoice-inner-head">
                        <span>@lang('admin.total_early_departure') : <a >.</a></span>
                    </div>

                    <div class="invoice-inner-head">
                        <span>@lang('admin.total_delay_minute') : <a >.</a></span>
                    </div>

                    <div class="invoice-inner-head">
                        <span>@lang('admin.total_extra_minute') : <a >.</a></span>
                    </div>

                    <div class="invoice-inner-head">
                        <span>@lang('admin.total_absence_days') : <a >.</a></span>
                    </div>

                    <div class="invoice-inner-head">
                        <span>@lang('admin.total_permission') : <a >.</a></span>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
