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
                            <li class="breadcrumb-item active">@lang('admin.doctor.View patient appointments')</li>
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
                                            <h3>@lang('admin.doctor.View patient appointments')</h3>
                                            <div class="doctor-search-blk">
                                                <div class="top-nav-search table-search-blk">
                                                    <form>
                                                        <input type="text" class="form-control"
                                                               placeholder="@lang('admin.search_here')">
                                                        <a class="btn"><img src="assets/img/icons/search-normal.svg"
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
                                <form action="{{ route('patient-appointment') }}" method="get">
                                    <div class="row">
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="form-group local-forms cal-icon">
                                                <label>@lang('admin.date') </label>
                                                <input class="form-control datetimepicker" type="text" name="date_from">
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
                                        <th>@lang('admin.name')</th>
                                        <th>@lang('admin.file_number')</th>
                                        <th>@lang('admin.age')</th>
                                        <th>@lang('admin.date')</th>
                                        <th>@lang('admin.status')</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data['appointments'] as $index=>$appointment)
                                        <tr>
                                            <td style="display: none">{{ $index + 1 }}</td>
                                            <td class="profile-image"><a
                                                    href="{{ route('medical-prescription', $appointment->id) }}"><img
                                                        width="28" height="28"
                                                        src="{{$appointment->user->image ?? null}}"
                                                        class="rounded-circle m-r-5"
                                                        alt="">{{$appointment->user->name ?? null}}
                                                </a></td>
                                            <td>{{ $appointment->user->ID_Number ?? null }}</td>
                                            <td>{{ \Carbon\Carbon::parse($appointment->user->dob)->diff( \Carbon\Carbon::now())->y  }} @lang('admin.years')</td>
                                            <td>{{ $appointment->date }} , {{ $appointment->appointment }}</td>
                                            <td>
                                                <button
                                                    class="custom-badge {{ $appointment->status_id == 6 ? 'status-green' : 'status-pink' }} ">
                                                    {{  app()->getLocale() == 'en' ? $appointment->reservation_status->name_en : $appointment->reservation_status->name_ar }}</button>
                                            </td>


                                            <td class="text-end">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle"
                                                       data-bs-toggle="dropdown" aria-expanded="false"><i
                                                            class="fa fa-ellipsis-v"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-end">


                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                           data-bs-target="#communication_{{$appointment->id}}"><i
                                                                class="feather-eye m-r-5"></i> @lang('admin.communication')
                                                        </a>
                                                        @if (\Carbon\Carbon::parse($appointment->date)->gte(now()))
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                           data-bs-target="#cancel_{{$appointment->id}}"><i
                                                                class="fa fa-trash-alt m-r-5"></i> @lang('admin.cancel')
                                                        </a>
                                                        @endif

                                                    </div>
                                                </div>

                                                <div id="communication_{{$appointment->id}}"
                                                     class="modal fade delete-modal"
                                                     role="dialog">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <form class="needs-validation"
                                                                  action="{{route('send-contact-reservation',$appointment->id) }}"
                                                                  method="POST">
                                                                @csrf
                                                                <div class="modal-body text-center">
                                                                    <h3>@lang('admin.send_message') {{ $appointment->user->name ?? null }}</h3>
                                                                    <br>
                                                                    <div class="col-12 col-sm-12">
                                                                        <div class="form-group local-forms">
                                                                            <label>@lang('admin.communication') <span
                                                                                    class="login-danger">*</span></label>
                                                                            <textarea class="form-control" rows="3"
                                                                                      cols="30" name="message"
                                                                                      required></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="m-t-20"><a href="#"
                                                                                           class="btn btn-white"
                                                                                           data-bs-dismiss="modal">@lang('admin.close')</a>
                                                                        <button type="submit"
                                                                                class="btn btn-primary submit-form me-2" style="color: #fff">@lang('admin.send')</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="cancel_{{$appointment->id}}" class="modal fade delete-modal"
                                                     role="dialog">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <form class="needs-validation" novalidate=""
                                                                  action="{{route('cancel-reservation',$appointment->id) }}"
                                                                  method="POST">
                                                                {{ method_field('delete') }}
                                                                {{ csrf_field() }}
                                                                <div class="modal-body text-center">
                                                                    <img src="/assets/img/sent.png" alt="" width="50"
                                                                         height="46">
                                                                    <h3>@lang('admin.confirm_cancel')</h3>
                                                                    <div class="m-t-20"><a href="#"
                                                                                           class="btn btn-white"
                                                                                           data-bs-dismiss="modal">@lang('admin.close')</a>
                                                                        <button type="submit"
                                                                                class="btn btn-danger">@lang('admin.cancel')</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                            </td>

                                        </tr>
                                    @endforeach
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
