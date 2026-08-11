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
                            <li class="breadcrumb-item active">@lang('admin.doctor_dashboard')</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            @php
                $currentTime = \Carbon\Carbon::now();
                $currentHour = $currentTime->hour;
                $greeting = '';
                if ($currentHour >= 0 && $currentHour < 12) {
                    $greeting = trans('admin.Good Morning');
                } elseif ($currentHour >= 12 && $currentHour < 18) {
                      $greeting = trans('admin.Good Afternoon');
                } elseif ($currentHour >= 18 && $currentHour < 24) {
                      $greeting = trans('admin.Good Evening');
                }
            @endphp

            <div class="good-morning-blk">
                <div class="row">
                    <div class="col-md-6">
                        <div class="morning-user">
                            <h2>{{ $greeting }}, <span>{{ auth()->user()->name ?? null }}</span></h2>
                            <p>@lang('admin.nice_day')</p>
                        </div>
                    </div>
                    <div class="col-md-6 position-blk">
                        <div class="morning-img">
                            <img src="/assets/img/morning-img-02.png" alt="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="doctor-list-blk">
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="doctor-widget border-right-bg">
                            <div class="doctor-box-icon flex-shrink-0">
                                <img src="/assets/img/icons/doctor-dash-01.svg" alt="">
                            </div>
                            <div class="doctor-content dash-count flex-grow-1">
                                <h4><span class="counter-up" >{{$data['appointments_count']}}</span><span></span><span class="status-green"></span></h4>
                                <h5>@lang('admin.Appointments')</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="doctor-widget border-right-bg">
                            <div class="doctor-box-icon flex-shrink-0">
                                <img src="/assets/img/icons/doctor-dash-02.svg" alt="">
                            </div>
                            <div class="doctor-content dash-count flex-grow-1">
                                <h4><span class="counter-up" >{{count($data['today_appointment'])}}</span><span></span><span class="status-pink"></span></h4>
                                <h5>@lang('admin.today_appointment_count')</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="doctor-widget border-right-bg">
                            <div class="doctor-box-icon flex-shrink-0">
                                <img src="/assets/img/icons/doctor-dash-03.svg" alt="">
                            </div>
                            <div class="doctor-content dash-count flex-grow-1">
                                <h4><span class="counter-up" >{{$data['next_appointment_count']}}</span><span></span><span class="status-pink"></span></h4>
                                <h5>@lang('admin.next_appointment')</h5>
                            </div>
                        </div>
                    </div><div class="col-xl-3 col-md-6">
                        <div class="doctor-widget">
                            <div class="doctor-box-icon flex-shrink-0">
                                <img src="/assets/img/icons/doctor-dash-04.svg" alt="">
                            </div>
                            <div class="doctor-content dash-count flex-grow-1">
                                <h4><span class="counter-up" >{{$data['tomorrow_appointment']}}</span><span></span><span class="status-pink"></span></h4>
                                <h5>@lang('admin.tomorrow_appointment')</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-12 col-md-12 col-lg-6 col-xl-9">
                    <div class="card">
                        <div class="card-body">
                            <div class="chart-title patient-visit">
                                <h4>@lang('admin.patient_visit_7days')</h4>
                            </div>
                            <div id="patient-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-6 col-xl-3 d-flex">
                    <div class="card">
                        <div class="card-body">
                            <div class="chart-title">
                                <h4>@lang('admin.clinics_doctor')</h4>
                            </div>
                            <div class="activity-top">
                                <div class="activity-boxs comman-flex-center">
                                    <img src="{{ Auth::user()->clinic_doctor->image ?? null }}" alt="" style="width: 24px;height: 26px">
                                </div>
                                <div class="departments-list">
                                    <h4>{{ Auth::user()->clinic_doctor->name ?? null }}</h4>
                                    <p>{{Auth::user()->clinic_doctor->created_date ?? null }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-12  col-xl-7">
                    <div class="card">
                        <div class="card-body">
                            <div class="chart-title patient-visit">
                                <h4>@lang('admin.patient_visit_next_7days')</h4>
                            </div>
                            <div id="apexcharts-area"></div>
                        </div>
                    </div>
                    @if(count($data['today_appointment']) > 0)
                    <div class="row">
                        <div class="col-12 col-md-12  col-xl-9">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title d-inline-block">@lang('admin.today_appointment')</h4> <a href="{{ route('patient-appointment') }}" class="patient-views float-end">@lang('admin.show_all')</a>
                                </div>
                                <div class="card-body p-0 table-dash">
                                    <div class="table-responsive">
                                        <table class="table mb-0 border-0 custom-table">
                                            <tbody>
                                            @foreach($data['today_appointment'] as $appointment)
                                            <tr>
                                                <td class="table-image appoint-doctor">
                                                    <img width="28" height="28" class="rounded-circle" src="{{$appointment->user->image ?? null}}" alt="">
                                                    <h2>{{$appointment->user->name ?? null}}</h2>
                                                </td>
                                                <td class="appoint-time text-center">
                                                    <h6>@lang('admin.Today') {{$appointment->appointment ?? null}}</h6>
                                                    <span>{{$appointment->booking_number ?? null}}</span>
                                                </td>
                                                <td>
                                                    @if($appointment->status_id == 3 || $appointment->status_id == 4 || $appointment->status_id == 5)
                                                        <button class="check-point status-pink "><i class="feather-x"></i>&nbsp;{{  app()->getLocale() == 'en' ? $appointment->reservation_status->name_en : $appointment->reservation_status->name_ar }}</button>
                                                    @else
                                                        <button class="check-point status-green me-1"><i class="feather-check"></i>&nbsp;{{  app()->getLocale() == 'en' ? $appointment->reservation_status->name_en : $appointment->reservation_status->name_ar }}</button>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-12  col-xl-3 d-flex">
                            <div class="card wallet-widget">
                                <div class="circle-bar circle-bar2">
                                    <div class="circle-graph2" data-percent="{{count($data['today_appointment'])}}">
                                        <b><img src="/assets/img/icons/timer.svg" alt=""></b>
                                    </div>
                                </div>
                                <div class="main-limit">
                                    <p>@lang('admin.Next Appointment in')</p>
                                    <h4>{{$data['next_appointment']->appointment ?? null}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @if(count($data['new_appointment']) > 0)
                <div class="col-12 col-lg-12 col-xl-5 d-flex">
                    <div class="card flex-fill comman-shadow">
                        <div class="card-header">
                            <h4 class="card-title d-inline-block">@lang('admin.new_appointment')</h4> <a href="{{ route('patient-appointment') }}" class="patient-views float-end">@lang('admin.show_all')</a>
                        </div>
                        <div class="card-body">
                            <div class="teaching-card">
                                <ul class="activity-feed3">

                                    @foreach($data['new_appointment'] as $appointment)
                                    <li class="feed-item d-flex align-items-center">
                                        <div class="dolor-activity">
                                            <ul class="doctor-date-list mb-2">
                                                <li><i class="fas fa-circle me-2"></i>{{$appointment->appointment ?? null}} <span>{{$appointment->user->name ?? null}}</span></li>

                                                <li class=" dropdown ongoing-blk ">
                                                    <a href="#" class="dropdown-toggle  active-doctor" data-bs-toggle="dropdown">
                                                        <i class="fas fa-circle me-2 active-circles"></i>{{$appointment->date ?? null}} <span>{{$appointment->user->name ?? null}}</span><span class="ongoing-drapt">Ongoing <i class="feather-chevron-down ms-2"></i></span>
                                                    </a>
                                                    <ul class="doctor-sub-list dropdown-menu">
                                                        <li class="patient-new-list dropdown-item">@lang('admin.patient')<span>{{$appointment->user->name ?? null}}</span><a href="javascript:;" class="new-dot status-green"><i class="fas fa-circle me-1 fa-2xs"></i> {{  app()->getLocale() == 'en' ? $appointment->reservation_status->name_en : $appointment->reservation_status->name_ar }}</a></li>
                                                        <li class="dropdown-item">@lang('admin.time')<span>{{$appointment->appointment ?? null}} </span></li>

                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
{{--        <div class="notification-box">--}}
{{--            <div class="msg-sidebar notifications msg-noti">--}}
{{--                <div class="topnav-dropdown-header">--}}
{{--                    <span>Messages</span>--}}
{{--                </div>--}}
{{--                <div class="drop-scroll msg-list-scroll" id="msg_list">--}}
{{--                    <ul class="list-box">--}}
{{--                        <li>--}}
{{--                            <a href="chat.html">--}}
{{--                                <div class="list-item">--}}
{{--                                    <div class="list-left">--}}
{{--                                        <span class="avatar">R</span>--}}
{{--                                    </div>--}}
{{--                                    <div class="list-body">--}}
{{--                                        <span class="message-author">Richard Miles </span>--}}
{{--                                        <span class="message-time">12:28 AM</span>--}}
{{--                                        <div class="clearfix"></div>--}}
{{--                                        <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="chat.html">--}}
{{--                                <div class="list-item new-message">--}}
{{--                                    <div class="list-left">--}}
{{--                                        <span class="avatar">J</span>--}}
{{--                                    </div>--}}
{{--                                    <div class="list-body">--}}
{{--                                        <span class="message-author">John Doe</span>--}}
{{--                                        <span class="message-time">1 Aug</span>--}}
{{--                                        <div class="clearfix"></div>--}}
{{--                                        <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="chat.html">--}}
{{--                                <div class="list-item">--}}
{{--                                    <div class="list-left">--}}
{{--                                        <span class="avatar">T</span>--}}
{{--                                    </div>--}}
{{--                                    <div class="list-body">--}}
{{--                                        <span class="message-author"> Tarah Shropshire </span>--}}
{{--                                        <span class="message-time">12:28 AM</span>--}}
{{--                                        <div class="clearfix"></div>--}}
{{--                                        <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="chat.html">--}}
{{--                                <div class="list-item">--}}
{{--                                    <div class="list-left">--}}
{{--                                        <span class="avatar">M</span>--}}
{{--                                    </div>--}}
{{--                                    <div class="list-body">--}}
{{--                                        <span class="message-author">Mike Litorus</span>--}}
{{--                                        <span class="message-time">12:28 AM</span>--}}
{{--                                        <div class="clearfix"></div>--}}
{{--                                        <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="chat.html">--}}
{{--                                <div class="list-item">--}}
{{--                                    <div class="list-left">--}}
{{--                                        <span class="avatar">C</span>--}}
{{--                                    </div>--}}
{{--                                    <div class="list-body">--}}
{{--                                        <span class="message-author"> Catherine Manseau </span>--}}
{{--                                        <span class="message-time">12:28 AM</span>--}}
{{--                                        <div class="clearfix"></div>--}}
{{--                                        <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="chat.html">--}}
{{--                                <div class="list-item">--}}
{{--                                    <div class="list-left">--}}
{{--                                        <span class="avatar">D</span>--}}
{{--                                    </div>--}}
{{--                                    <div class="list-body">--}}
{{--                                        <span class="message-author"> Domenic Houston </span>--}}
{{--                                        <span class="message-time">12:28 AM</span>--}}
{{--                                        <div class="clearfix"></div>--}}
{{--                                        <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="chat.html">--}}
{{--                                <div class="list-item">--}}
{{--                                    <div class="list-left">--}}
{{--                                        <span class="avatar">B</span>--}}
{{--                                    </div>--}}
{{--                                    <div class="list-body">--}}
{{--                                        <span class="message-author"> Buster Wigton </span>--}}
{{--                                        <span class="message-time">12:28 AM</span>--}}
{{--                                        <div class="clearfix"></div>--}}
{{--                                        <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="chat.html">--}}
{{--                                <div class="list-item">--}}
{{--                                    <div class="list-left">--}}
{{--                                        <span class="avatar">R</span>--}}
{{--                                    </div>--}}
{{--                                    <div class="list-body">--}}
{{--                                        <span class="message-author"> Rolland Webber </span>--}}
{{--                                        <span class="message-time">12:28 AM</span>--}}
{{--                                        <div class="clearfix"></div>--}}
{{--                                        <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="chat.html">--}}
{{--                                <div class="list-item">--}}
{{--                                    <div class="list-left">--}}
{{--                                        <span class="avatar">C</span>--}}
{{--                                    </div>--}}
{{--                                    <div class="list-body">--}}
{{--                                        <span class="message-author"> Claire Mapes </span>--}}
{{--                                        <span class="message-time">12:28 AM</span>--}}
{{--                                        <div class="clearfix"></div>--}}
{{--                                        <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="chat.html">--}}
{{--                                <div class="list-item">--}}
{{--                                    <div class="list-left">--}}
{{--                                        <span class="avatar">M</span>--}}
{{--                                    </div>--}}
{{--                                    <div class="list-body">--}}
{{--                                        <span class="message-author">Melita Faucher</span>--}}
{{--                                        <span class="message-time">12:28 AM</span>--}}
{{--                                        <div class="clearfix"></div>--}}
{{--                                        <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="chat.html">--}}
{{--                                <div class="list-item">--}}
{{--                                    <div class="list-left">--}}
{{--                                        <span class="avatar">J</span>--}}
{{--                                    </div>--}}
{{--                                    <div class="list-body">--}}
{{--                                        <span class="message-author">Jeffery Lalor</span>--}}
{{--                                        <span class="message-time">12:28 AM</span>--}}
{{--                                        <div class="clearfix"></div>--}}
{{--                                        <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="chat.html">--}}
{{--                                <div class="list-item">--}}
{{--                                    <div class="list-left">--}}
{{--                                        <span class="avatar">L</span>--}}
{{--                                    </div>--}}
{{--                                    <div class="list-body">--}}
{{--                                        <span class="message-author">Loren Gatlin</span>--}}
{{--                                        <span class="message-time">12:28 AM</span>--}}
{{--                                        <div class="clearfix"></div>--}}
{{--                                        <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="chat.html">--}}
{{--                                <div class="list-item">--}}
{{--                                    <div class="list-left">--}}
{{--                                        <span class="avatar">T</span>--}}
{{--                                    </div>--}}
{{--                                    <div class="list-body">--}}
{{--                                        <span class="message-author">Tarah Shropshire</span>--}}
{{--                                        <span class="message-time">12:28 AM</span>--}}
{{--                                        <div class="clearfix"></div>--}}
{{--                                        <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--                <div class="topnav-dropdown-footer">--}}
{{--                    <a href="chat.html">See all messages</a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>


    <script>
        var chartData = @json($data['chartData']);

        var daysOfWeek = @json($data['daysOfWeek']);

        var next_chartData = @json($data['next_7days_chartData']);

        var next_daysOfWeek = @json($data['next_7days_daysOfWeek']);

    </script>
@endsection
