@extends('includes_admin.mainlayout')
@section('content')

    <div class="page-wrapper">
        <div class="content">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard') </a></li>
                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                            <li class="breadcrumb-item active">@lang('admin.patient_file_clinic')</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card-box profile-header">
                <div class="row">
                    <div class="col-md-12">
                        <div class="profile-view">
                            <div class="profile-img-wrap">
                                <div class="profile-img">
                                    <a href="#"><img class="avatar" src="{{$reservation->clinic->image ?? null}}"
                                                     alt=""></a>
                                </div>
                            </div>
                            <div class="profile-basic">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="profile-info-left">
                                            <h3 class="user-name m-t-0 mb-0">{{$reservation->clinic->name ?? null}}</h3>
                                            <small class="text-muted">{{$reservation->clinic->phone ?? null}}</small>
                                            <div class="staff-id">@lang('admin.doctor_account')
                                                : {{$reservation->clinic->name ?? null}}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <ul class="personal-info">

                                            <li>
                                                <span class="title"><a href="#"><img class="avatar"
                                                                                     style="width: 90px;height: 90px"
                                                                                     src="{{$reservation->user->image ?? null}}"
                                                                                     alt=""></a></span>
                                                <span class="text"><a href="#"><img class="avatar"
                                                                                    style="width: 90px;height: 90px"
                                                                                    src="/media/reservations/{{$reservation->booking_number . '.png' ?? null}}"
                                                                                    alt=""></a></span>
                                            </li>

                                            <li>
                                                <span class="title">@lang('admin.name'):</span>
                                                <span class="text"><a
                                                        href="">{{$reservation->user->name ?? null}}</a></span>
                                            </li>

                                            <li>
                                                <span class="title">@lang('admin.gender'):</span>
                                                @if($reservation->user->gender == 1)
                                                    <span class="text"><a href="">@lang('admin.male')</a></span>
                                                @else
                                                    <span class="text"><a href="">@lang('admin.female')</a></span>
                                                @endif
                                            </li>

                                            <li>
                                                <span class="title">@lang('admin.phone'):</span>
                                                <span class="text"><a href="">{{$reservation->user->phone ?? null}}</a></span>
                                            </li>
                                            <li>
                                                <span class="title">@lang('admin.email'):</span>
                                                <span class="text"><a href="">{{$reservation->user->email ?? null}}</a></span>
                                            </li>
                                            <li>
                                                <span class="title">@lang('admin.ID_Number'):</span>
                                                <span class="text">{{$reservation->user->ID_Number ?? null}}</span>
                                            </li>

                                            <li>
                                                <span class="title">@lang('admin.dob'):</span>
                                                <span class="text">{{$reservation->user->dob ?? null}}</span>
                                            </li>
                                        </ul>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="settings-menu-links">
                <ul class="nav nav-tabs menu-tabs">

                    <li class="nav-item">
                        <a class="nav-link me-2"
                           href="{{route('previous-revelations',$reservation->id)}}">@lang('admin.previous_revelations')</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link me-2"
                           href="{{route('add-medicine',$reservation->id)}}">@lang('admin.new_revelation')</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link me-2"
                           href="{{route('previous-revelations',$reservation->id)}}">@lang('admin.report_previous_visit')</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link me-2"
                           href="{{route('patient-report', [$reservation->user_id,2])}}">@lang('admin.medical_reports')
                            &nbsp;&nbsp; <i class="fa fa-check-square"></i>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link me-2" href="{{ route('patient-sick-leave', $reservation->user_id) }}">@lang('admin.sick_leave')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-2" href="{{ route('companion-sick-leave', $reservation->user_id) }}">@lang('admin.leave_companion')</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link me-2" href="{{ route('patient-invoices',$reservation->user_id) }}">@lang('admin.Bills')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-2"
                           href="{{route('new-reservation',$reservation->id)}}">@lang('admin.appointment_booking')</a>
                    </li>

                    <!-- Your HTML structure -->
                    @if($reservation->follow_up == 0)
                    <form id="followUpForm" action="{{ route('followup_patient',$reservation->id) }}" method="post">
                        @csrf
                        <!-- Your form fields go here -->
                        <li class="nav-item">
                            <button type="button" id="followUpBtn" class="nav-link me-2 border-0 btn btn-danger btn-gradient-primary btn-rounded">@lang('admin.follow_up')</button>
                        </li>
                    </form>
                    @else
                        <li class="nav-item active">
                            <button type="button" id="followUpBtn" class="nav-link me-2 border-0 btn btn-danger btn-gradient-primary btn-rounded">@lang('admin.follow_up')
                              &nbsp;&nbsp;  <i class="fa fa-check-square"></i>
                            </button>
                        </li>
                    @endif

                </ul>
            </div>

            <div class="row">
                <div class="col-lg-4">
{{--                    <div class="doctor-personals-grp">--}}
{{--                        <div class="card">--}}
{{--                            <div class="card-body">--}}
{{--                                <div class="heading-detail ">--}}
{{--                                    <h4 class="mb-3">@lang('admin.new_revelation')</h4>--}}
{{--                                    <h6>@lang('admin.patient_nationality'):--}}
{{--                                        @if($lang == 'en')--}}
{{--                                            {{$reservation->user->nationality->name_en ?? null}}--}}
{{--                                        @else--}}
{{--                                            {{$reservation->user->nationality->name_ar ?? null}}--}}
{{--                                        @endif--}}
{{--                                    </h6>--}}
{{--                                </div>--}}

{{--                                <div class="form-group mb-0">--}}
{{--                                    <div class="settings-btns">--}}
{{--                                        <div class="settings-menu-links">--}}
{{--                                            <ul class="nav nav-tabs menu-tabs">--}}
{{--                                                <li class="nav-item active">--}}
{{--                                                    <a href="{{route('patient-report', [$reservation->user_id,2])}}"--}}
{{--                                                       class="nav-link me-2">@lang('admin.medical_reports')--}}
{{--                                                        &nbsp;&nbsp;--}}
{{--                                                        <i class="fa fa-check-square"></i></a>--}}
{{--                                                </li>--}}
{{--                                                <br>--}}
{{--                                                <li class="nav-item active">--}}
{{--                                                    <a href="{{route('add-medicine',$reservation->id)}}"--}}
{{--                                                       class="nav-link me-2">@lang('admin.new_revelation')--}}
{{--                                                        &nbsp;&nbsp;</a>--}}
{{--                                                </li>--}}
{{--                                                <br>--}}
{{--                                                <li class="nav-item active">--}}
{{--                                                    <button type="submit"--}}
{{--                                                            class="nav-link me-2 border-0 btn btn-danger btn-gradient-primary btn-rounded">@lang('admin.follow_up')</button>--}}
{{--                                                </li>--}}
{{--                                                <br>--}}
{{--                                            </ul>--}}
{{--                                        </div>--}}

{{--                                        --}}{{--                                        <a href="{{route('patient-report', [$reservation->user_id,2])}}"--}}
{{--                                        --}}{{--                                           class="border-0 btn btn-primary btn-gradient-primary btn-rounded">@lang('admin.medical_reports')--}}
{{--                                        --}}{{--                                            &nbsp;&nbsp;--}}
{{--                                        --}}{{--                                            <i class="fa fa-check-square"></i></a>--}}
{{--                                        --}}{{--                                        <br>   <br>--}}
{{--                                        --}}{{--                                        <a href="{{route('add-medicine',$reservation->id)}}"--}}
{{--                                        --}}{{--                                           class="border-0 btn btn-primary btn-gradient-primary btn-rounded">@lang('admin.new_revelation')</a>--}}
{{--                                        --}}{{--                                        <br>      <br>--}}
{{--                                        --}}{{--                                        <button type="submit"--}}
{{--                                        --}}{{--                                                class="border-0 btn btn-danger btn-gradient-primary btn-rounded">@lang('admin.follow_up')</button>--}}
{{--                                        --}}{{--                                        <br>   <br>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="doctor-personals-grp">
                        <div class="card">
                            <div class="card-body">
                                <div class="heading-detail">
                                    <h4>@lang('admin.patient_doctor'):</h4>
                                </div>
                                <div class="skill-blk">
                                    @foreach($data['visit_patient'] as $patient_doctor)
                                        <div class="skill-statistics">
                                            <div class="skills-head">
                                                <h5>{{$patient_doctor->doctor->name}}</h5>
                                            </div>
                                            {{$patient_doctor->doctor->specialization ?? null}}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="doctor-personals-grp">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">@lang('admin.visit_patient')</h4>
                            </div>
                            <div class="card-body p-0 table-dash">
                                <div class="table-responsive">
                                    <table class="table mb-0 border-0 datatable custom-table patient-profile-table">
                                        <thead>
                                        <tr>
                                            <th>@lang('admin.visit_type')</th>
                                            <th> @lang('admin.visit_number') </th>
                                            <th> @lang('admin.patient_type') </th>
                                            <th> @lang('admin.Day') </th>
                                            <th> @lang('admin.Date') </th>
                                            {{--                                            <th>@lang('admin.update_request') </th>--}}
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['visit_patient'] as $visit)
                                            <tr>
                                                <td>{{$visit->doctor->specialization ?? null}}</td>
                                                <td>1</td>
                                                <td>@lang('admin.cash')</td>
                                                <td>{{ $visit->day_name ?? null }}</td>
                                                <td>{{ $visit->date }}</td>
                                                {{--                                            <td class="text-end"></td>--}}
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">@lang('admin.patient_services')</h4>
                            </div>
                            <div class="card-body p-0 table-dash">
                                <div class="table-responsive">
                                    <table class="table mb-0 border-0 datatable custom-table patient-profile-table">
                                        <thead>
                                        <tr>
                                            <th>@lang('admin.service_name')</th>
                                            <th> @lang('admin.service_price') </th>
                                            <th> @lang('admin.service_status') </th>
{{--                                            <th> @lang('admin.service_cancel') </th>--}}
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['patient_services'] as $service)
                                            <tr>
                                                <td>{{$lang == 'en' ? $service->services->name_en : $service->services->name_ar}}</td>
                                                <td>{{ $service->price ?? null }}</td>
                                                <td>{{ $service->status }}</td>
                                                @if($service->status == 0)
                                                    <td style="color: #f0ad4e">@lang('admin.waiting')</td>
                                                @elseif($service->status == 1)
                                                    <td style="color: #0f9d58">@lang('admin.done')</td>
                                                @else
                                                    <td style="color: #ff0000">@lang('admin.not_implemented')</td>
                                                @endif

{{--                                                <td class="text-end">--}}
{{--                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"--}}
{{--                                                       data-bs-target="#delete_service{{$service->id}}"><i--}}
{{--                                                            class="fa fa-trash-alt m-r-5"></i> @lang('admin.delete')--}}
{{--                                                    </a>--}}

{{--                                                    <div id="delete_service{{$service->id}}"--}}
{{--                                                         class="modal fade delete-modal"--}}
{{--                                                         role="dialog">--}}
{{--                                                        <div class="modal-dialog modal-dialog-centered">--}}
{{--                                                            <div class="modal-content">--}}
{{--                                                                <form class="needs-validation" novalidate=""--}}
{{--                                                                      action="{{route('destroyPatientService',$service->id) }}"--}}
{{--                                                                      method="POST">--}}
{{--                                                                    {{ method_field('delete') }}--}}
{{--                                                                    {{ csrf_field() }}--}}
{{--                                                                    <div class="modal-body text-center">--}}
{{--                                                                        <img src="/assets/img/sent.png" alt=""--}}
{{--                                                                             width="50"--}}
{{--                                                                             height="46">--}}
{{--                                                                        <h3>@lang('admin.confirm_delete')</h3>--}}
{{--                                                                        <div class="m-t-20"><a href="#"--}}
{{--                                                                                               class="btn btn-white"--}}
{{--                                                                                               data-bs-dismiss="modal">@lang('admin.close')</a>--}}
{{--                                                                            <button type="submit"--}}
{{--                                                                                    class="btn btn-danger">@lang('admin.delete')</button>--}}
{{--                                                                        </div>--}}
{{--                                                                    </div>--}}
{{--                                                                </form>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </td>--}}
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">@lang('admin.patient_analysis')</h4>
                            </div>
                            <div class="card-body p-0 table-dash">
                                <div class="table-responsive">
                                    <table class="table mb-0 border-0 datatable custom-table patient-profile-table">
                                        <thead>
                                        <tr>
                                            <th>@lang('admin.service_name')</th>
                                            <th> @lang('admin.service_price') </th>
                                            <th> @lang('admin.service_status') </th>
{{--                                            <th> @lang('admin.service_cancel') </th>--}}
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['patient_analysis'] as $analysis)
                                            <tr>
                                                <td>{{$lang == 'en' ? $analysis->services->name_en : $analysis->services->name_ar}}</td>
                                                <td>{{ $analysis->price ?? null }}</td>
                                                <td>{{ $analysis->status }}</td>
                                                @if($analysis->status == 0)
                                                    <td style="color: #f0ad4e">@lang('admin.waiting')</td>
                                                @elseif($analysis->status == 1)
                                                    <td style="color: #0f9d58">@lang('admin.done')</td>
                                                @else
                                                    <td style="color: #ff0000">@lang('admin.not_implemented')</td>
                                                @endif

{{--                                                <td class="text-end">--}}
{{--                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"--}}
{{--                                                       data-bs-target="#delete_service{{$analysis->id}}"><i--}}
{{--                                                            class="fa fa-trash-alt m-r-5"></i> @lang('admin.delete')--}}
{{--                                                    </a>--}}

{{--                                                    <div id="delete_service{{$analysis->id}}"--}}
{{--                                                         class="modal fade delete-modal"--}}
{{--                                                         role="dialog">--}}
{{--                                                        <div class="modal-dialog modal-dialog-centered">--}}
{{--                                                            <div class="modal-content">--}}
{{--                                                                <form class="needs-validation" novalidate=""--}}
{{--                                                                      action="{{route('destroyPatientService',$analysis->id) }}"--}}
{{--                                                                      method="POST">--}}
{{--                                                                    {{ method_field('delete') }}--}}
{{--                                                                    {{ csrf_field() }}--}}
{{--                                                                    <div class="modal-body text-center">--}}
{{--                                                                        <img src="/assets/img/sent.png" alt=""--}}
{{--                                                                             width="50"--}}
{{--                                                                             height="46">--}}
{{--                                                                        <h3>@lang('admin.confirm_delete')</h3>--}}
{{--                                                                        <div class="m-t-20"><a href="#"--}}
{{--                                                                                               class="btn btn-white"--}}
{{--                                                                                               data-bs-dismiss="modal">@lang('admin.close')</a>--}}
{{--                                                                            <button type="submit"--}}
{{--                                                                                    class="btn btn-danger">@lang('admin.delete')</button>--}}
{{--                                                                        </div>--}}
{{--                                                                    </div>--}}
{{--                                                                </form>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </td>--}}
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">@lang('admin.patient_rays')</h4>
                            </div>
                            <div class="card-body p-0 table-dash">
                                <div class="table-responsive">
                                    <table class="table mb-0 border-0 datatable custom-table patient-profile-table">
                                        <thead>
                                        <tr>
                                            <th>@lang('admin.service_name')</th>
                                            <th> @lang('admin.service_price') </th>
                                            <th> @lang('admin.service_status') </th>
{{--                                            <th> @lang('admin.service_cancel') </th>--}}
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['patient_rays'] as $rays)
                                            <tr>
                                                <td>{{$lang == 'en' ? $rays->services->name_en : $rays->services->name_ar}}</td>
                                                <td>{{ $rays->price ?? null }}</td>
                                                <td>{{ $rays->status }}</td>
                                                @if($rays->status == 0)
                                                    <td style="color: #f0ad4e">@lang('admin.waiting')</td>
                                                @elseif($rays->status == 1)
                                                    <td style="color: #0f9d58">@lang('admin.done')</td>
                                                @else
                                                    <td style="color: #ff0000">@lang('admin.not_implemented')</td>
                                                @endif

{{--                                                <td class="text-end">--}}
{{--                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"--}}
{{--                                                       data-bs-target="#delete_service{{$rays->id}}"><i--}}
{{--                                                            class="fa fa-trash-alt m-r-5"></i> @lang('admin.delete')--}}
{{--                                                    </a>--}}

{{--                                                    <div id="delete_service{{$rays->id}}"--}}
{{--                                                         class="modal fade delete-modal"--}}
{{--                                                         role="dialog">--}}
{{--                                                        <div class="modal-dialog modal-dialog-centered">--}}
{{--                                                            <div class="modal-content">--}}
{{--                                                                <form class="needs-validation" novalidate=""--}}
{{--                                                                      action="{{route('destroyPatientService',$rays->id) }}"--}}
{{--                                                                      method="POST">--}}
{{--                                                                    {{ method_field('delete') }}--}}
{{--                                                                    {{ csrf_field() }}--}}
{{--                                                                    <div class="modal-body text-center">--}}
{{--                                                                        <img src="/assets/img/sent.png" alt=""--}}
{{--                                                                             width="50"--}}
{{--                                                                             height="46">--}}
{{--                                                                        <h3>@lang('admin.confirm_delete')</h3>--}}
{{--                                                                        <div class="m-t-20"><a href="#"--}}
{{--                                                                                               class="btn btn-white"--}}
{{--                                                                                               data-bs-dismiss="modal">@lang('admin.close')</a>--}}
{{--                                                                            <button type="submit"--}}
{{--                                                                                    class="btn btn-danger">@lang('admin.delete')</button>--}}
{{--                                                                        </div>--}}
{{--                                                                    </div>--}}
{{--                                                                </form>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </td>--}}
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">@lang('admin.patient_debts')</h4>
                            </div>
                            <div class="card-body p-0 table-dash">
                                <div class="table-responsive">
                                    <table class="table mb-0 border-0 datatable custom-table patient-profile-table">
                                        <thead>
                                        <tr>
                                            <th>@lang('admin.patient_name')</th>
                                            <th> @lang('admin.patient_type') </th>
                                            <th> @lang('admin.price') </th>
                                            <th> @lang('admin.debts') </th>
                                            <th> @lang('admin.Day') </th>
                                            <th> @lang('admin.Date') </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="{{asset('/admin/js/jquery-3.2.1.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            // Add click event listener to the button
            $('#followUpBtn').on('click', function() {
                // Trigger form submission
                $('#followUpForm').submit();
            });
        });
    </script>
@endsection
