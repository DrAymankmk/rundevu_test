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
                            <li class="breadcrumb-item active">@lang('admin.doctor.doctor_appointments')</li>
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
                                            <h3>@lang('admin.doctor.doctor_appointments')</h3>
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
                                <form action="{{ route('doctor-appointment') }}" method="get">
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


                            <div class="table-responsive">
                                <table class="table border-0 custom-table comman-table datatable mb-0">
                                    <thead>
                                    <tr>
                                        <th style="display: none">#</th>
                                        <th>@lang('admin.shifts.dateA')</th>
                                        <th>@lang('admin.shifts.name')</th>
                                        <th>@lang('admin.shifts.time_from')</th>
                                        <th>@lang('admin.shifts.time_to')</th>
                                        <th>@lang('admin.status')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data['appointments'] as $index=>$appointment)
                                        <tr>
                                            <td style="display: none">{{ $index + 1 }}</td>
                                            <td>{{ $appointment->dateA ?? null }}</td>
                                            <td>{{ $appointment->shift->name ?? null }}</td>
                                            <td>{{ $appointment->shift->time_from ?? null }}</td>
                                            <td>{{ $appointment->shift->time_to ?? null }}</td>

                                            @if ($appointment->status == 0)
                                                <td>@lang('messages.attendance.official_vacation')</td>
                                            @elseif ($appointment->status == 2)
                                                <td>@lang('messages.attendance.absence')</td>
                                            @elseif (empty($appointment->check_in) && empty($appointment->check_out))
                                                <td>-</td>
                                            @else
                                                <td>
                                                    @lang('admin.audience'):: {{ !empty($appointment->check_in) ? date('h:i a', strtotime($appointment->check_in)) : "" }}
                                                    <br>
                                                    @lang('admin.leave'):: {{ !empty($appointment->check_out) ? date('h:i a', strtotime($appointment->check_out)) : "" }}
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-content">
                                <div class="tab-pane show active" id="about-cont">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-body pt-0">
                                                    <form class="needs-validation"
                                                          action="{{route('add-doctor-condition')}}"
                                                          method="POST">
                                                        @csrf
                                                        <div class="settings-form">

                                                            <div class="form-group">
                                                               &nbsp;&nbsp <label
                                                                    for="appointments_online">@lang('admin.appointments_online')
                                                                    ( % ) </label>
                                                                &nbsp;&nbsp   <input type="number" class="form-control"
                                                                       name="appointments_online"
                                                                       id="appointments_online"
                                                                                     required
                                                                                     value="{{$data['condition']->appointments_online ?? null}}"
                                                                       placeholder="@lang('admin.appointments_online')">
                                                            </div>

                                                            <div class="form-group">
                                                                &nbsp;&nbsp   <label
                                                                    for="appointments_online">@lang('admin.appointments_reception')
                                                                    ( % ) </label>
                                                                &nbsp;&nbsp  <input type="number" class="form-control"
                                                                       name="appointments_reception"
                                                                                    required
                                                                       id="appointments_reception" value="{{$data['condition']->appointments_reception ?? null}}" placeholder="@lang('admin.appointments_reception')">
                                                            </div>

                                                            <div class="form-group">
                                                                &nbsp;&nbsp   <label for="appointments_online">@lang('admin.number_patients')</label>
                                                                &nbsp;&nbsp  <input type="number" class="form-control"
                                                                                    name="number_patients"
                                                                                    required
                                                                                    id="number_patients" value="{{$data['condition']->number_patients ?? null}}" placeholder="@lang('admin.appointments_reception')">
                                                            </div>

                                                            <div class="form-group">
                                                                &nbsp;&nbsp    <label
                                                                    for="condition">@lang('admin.doctor_condition')</label>
                                                                &nbsp;&nbsp   <textarea class="form-control" name="condition"
                                                                          id="condition"
                                                                                        required
                                                                          placeholder="@lang('admin.doctor_condition')">{{$data['condition']->condition ?? null}}</textarea>
                                                            </div>

                                                            <div class="form-group mb-0 text-center">
                                                                    <button type="submit"
                                                                            class="border-0 btn btn-primary btn-gradient-primary btn-rounded">@lang('admin.save')</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
