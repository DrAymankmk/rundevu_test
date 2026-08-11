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
                            <li class="breadcrumb-item active">@lang('admin.pharmacy.pharmacy_dashboard')</li>
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
                            <img src="/assets/img/morning-img-01.png" alt="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
                    <div class="dash-widget">
                        <div class="dash-boxs comman-flex-center">
                            <img  src="/assets/img/icons/empty-wallet.svg" alt="">

                        </div>
                        <div class="dash-content dash-count">
                            <h4>@lang('admin.my_points')</h4>
                            <h2><span class="counter-up" >{{ $data['points'] }}</span></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
                    <div class="dash-widget">
                        <div class="dash-boxs comman-flex-center">
                            <img src="/media/icons/Medical_prescription_menu.png" style="background-color: #888" alt="">
                        </div>
                        <div class="dash-content dash-count">
                            <h4>@lang('admin.pharmacy.New prescription')</h4>
                            <h2><span class="counter-up" >{{ $data['diagnosis_count'] }}</span></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
                    <div class="dash-widget">
                        <div class="dash-boxs comman-flex-center">
                            <img src="/media/icons/prescription record.png" style="background-color: #888" alt="">
                        </div>
                        <div class="dash-content dash-count">
                            <h4>@lang('admin.doctor.prescription record')</h4>
                            <h2><span class="counter-up" >{{ $data['medicines_dispensed_count'] }}</span></h2>
                        </div>
                    </div>
                </div>
{{--                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">--}}
{{--                    <div class="dash-widget">--}}
{{--                        <div class="dash-boxs comman-flex-center">--}}
{{--                            <img src="/assets/img/icons/empty-wallet.svg" alt="">--}}
{{--                        </div>--}}
{{--                        <div class="dash-content dash-count">--}}
{{--                            <h4>Earnings</h4>--}}
{{--                            <h2>$<span class="counter-up" > 20,250</span></h2>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>

            <div class="row">
                <div class="col-12 col-md-12  col-xl-4">
                    <div class="card top-departments">
                        <div class="card-header">
                            <h4 class="card-title mb-0">@lang('admin.pharmacy.Treatment requests')</h4>
                        </div>
                        <div class="card-body">
                            @foreach($data['treatment_requests'] as $treatment)
                            <div class="activity-top">
                                <div class="activity-boxs comman-flex-center">
                                    <img src="/media/icons/Medical_prescription_menu.png" style="background-color: #888" alt="">
                                </div>
                                <div class="departments-list">
                                    <h4>{{$treatment->date}}</h4>
                                    <p>{{ $treatment->count }}</p>
                                </div>
                            </div>
                            @endforeach

                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12  col-xl-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title d-inline-block">@lang('admin.pharmacy.Treatment requests')</h4> <a href="{{route('new-prescription')}}" class="patient-views float-end">Show all</a>
                        </div>
                        <div class="card-body p-0 table-dash">
                            <div class="table-responsive">
                                <table class="table mb-0 border-0 datatable custom-table">
                                    <thead>
                                    <tr>
                                        <th>@lang('admin.Patient name')</th>
                                        <th>@lang('admin.Doctor')</th>
                                        <th>@lang('admin.created_at')</th>
                                        <th>@lang('admin.drug')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data['diagnosis_display'] as $section)
                                    <tr>
                                        <td>{{$section->users->name ?? null}}</td>
                                        <td>{{$section->doctor->name ?? null}}</td>
                                        <td>{{$section->created_at ?? null}}</td>

                                        @if($data['lang'] == 'en')
                                            <td>{{ $section->drugs->name_en }} {{ $section->drugs->concentration_ratio }} @lang('admin.doctor.'.$section->drugs->concentration_type)   @lang('admin.medicine_type.'.$section->drugs->medicine_type)
                                                {{$section->repetition}} @lang('admin.time_per_day') - {{$section->nums_days}} @lang('admin.day')
                                            </td>
                                        @else
                                            <td>{{ $section->drugs->name_ar }} {{ $section->drugs->concentration_ratio }} @lang('admin.doctor.'.$section->drugs->concentration_type)   @lang('admin.medicine_type.'.$section->drugs->medicine_type)
                                                {{$section->repetition}} @lang('admin.time_per_day') - {{$section->nums_days}} @lang('admin.day')
                                            </td>
                                        @endif

                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-xl-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h4 class="card-title d-inline-block">@lang('admin.pharmacy.medicines_dispensed') </h4> <a href="{{route('prescription-record')}}" class="float-end patient-views">@lang('admin.show all')</a>
                        </div>
                        <div class="card-block table-dash">
                            <div class="table-responsive">
                                <table class="table mb-0 border-0 datatable custom-table">
                                    <thead>
                                    <tr>
                                        <th>@lang('admin.Patient name')</th>
                                        <th>@lang('admin.Doctor')</th>
                                        <th>@lang('admin.created_at')</th>
                                        <th>@lang('admin.drug')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data['medicines_dispensed'] as $section)
                                        <tr>
                                            <td>{{$section->users->name ?? null}}</td>
                                            <td>{{$section->doctor->name ?? null}}</td>
                                            <td>{{$section->created_at ?? null}}</td>

                                            @if($data['lang'] == 'en')
                                                <td>{{ $section->drugs->name_en }} {{ $section->drugs->concentration_ratio }} @lang('admin.doctor.'.$section->drugs->concentration_type)   @lang('admin.medicine_type.'.$section->drugs->medicine_type)
                                                    {{$section->repetition}} @lang('admin.time_per_day') - {{$section->nums_days}} @lang('admin.day')
                                                </td>
                                            @else
                                                <td>{{ $section->drugs->name_ar }} {{ $section->drugs->concentration_ratio }} @lang('admin.doctor.'.$section->drugs->concentration_type)   @lang('admin.medicine_type.'.$section->drugs->medicine_type)
                                                    {{$section->repetition}} @lang('admin.time_per_day') - {{$section->nums_days}} @lang('admin.day')
                                                </td>
                                            @endif

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
        <div class="notification-box">
            <div class="msg-sidebar notifications msg-noti">
                <div class="topnav-dropdown-header">
                    <span>Messages</span>
                </div>
                <div class="drop-scroll msg-list-scroll" id="msg_list">
                    <ul class="list-box">
                        <li>
                            <a href="chat.html">
                                <div class="list-item">
                                    <div class="list-left">
                                        <span class="avatar">R</span>
                                    </div>
                                    <div class="list-body">
                                        <span class="message-author">Richard Miles </span>
                                        <span class="message-time">12:28 AM</span>
                                        <div class="clearfix"></div>
                                        <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="chat.html">
                                <div class="list-item new-message">
                                    <div class="list-left">
                                        <span class="avatar">J</span>
                                    </div>
                                    <div class="list-body">
                                        <span class="message-author">John Doe</span>
                                        <span class="message-time">1 Aug</span>
                                        <div class="clearfix"></div>
                                        <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="chat.html">
                                <div class="list-item">
                                    <div class="list-left">
                                        <span class="avatar">T</span>
                                    </div>
                                    <div class="list-body">
                                        <span class="message-author"> Tarah Shropshire </span>
                                        <span class="message-time">12:28 AM</span>
                                        <div class="clearfix"></div>
                                        <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="chat.html">
                                <div class="list-item">
                                    <div class="list-left">
                                        <span class="avatar">M</span>
                                    </div>
                                    <div class="list-body">
                                        <span class="message-author">Mike Litorus</span>
                                        <span class="message-time">12:28 AM</span>
                                        <div class="clearfix"></div>
                                        <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="chat.html">
                                <div class="list-item">
                                    <div class="list-left">
                                        <span class="avatar">C</span>
                                    </div>
                                    <div class="list-body">
                                        <span class="message-author"> Catherine Manseau </span>
                                        <span class="message-time">12:28 AM</span>
                                        <div class="clearfix"></div>
                                        <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="chat.html">
                                <div class="list-item">
                                    <div class="list-left">
                                        <span class="avatar">D</span>
                                    </div>
                                    <div class="list-body">
                                        <span class="message-author"> Domenic Houston </span>
                                        <span class="message-time">12:28 AM</span>
                                        <div class="clearfix"></div>
                                        <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="chat.html">
                                <div class="list-item">
                                    <div class="list-left">
                                        <span class="avatar">B</span>
                                    </div>
                                    <div class="list-body">
                                        <span class="message-author"> Buster Wigton </span>
                                        <span class="message-time">12:28 AM</span>
                                        <div class="clearfix"></div>
                                        <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="chat.html">
                                <div class="list-item">
                                    <div class="list-left">
                                        <span class="avatar">R</span>
                                    </div>
                                    <div class="list-body">
                                        <span class="message-author"> Rolland Webber </span>
                                        <span class="message-time">12:28 AM</span>
                                        <div class="clearfix"></div>
                                        <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="chat.html">
                                <div class="list-item">
                                    <div class="list-left">
                                        <span class="avatar">C</span>
                                    </div>
                                    <div class="list-body">
                                        <span class="message-author"> Claire Mapes </span>
                                        <span class="message-time">12:28 AM</span>
                                        <div class="clearfix"></div>
                                        <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="chat.html">
                                <div class="list-item">
                                    <div class="list-left">
                                        <span class="avatar">M</span>
                                    </div>
                                    <div class="list-body">
                                        <span class="message-author">Melita Faucher</span>
                                        <span class="message-time">12:28 AM</span>
                                        <div class="clearfix"></div>
                                        <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="chat.html">
                                <div class="list-item">
                                    <div class="list-left">
                                        <span class="avatar">J</span>
                                    </div>
                                    <div class="list-body">
                                        <span class="message-author">Jeffery Lalor</span>
                                        <span class="message-time">12:28 AM</span>
                                        <div class="clearfix"></div>
                                        <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="chat.html">
                                <div class="list-item">
                                    <div class="list-left">
                                        <span class="avatar">L</span>
                                    </div>
                                    <div class="list-body">
                                        <span class="message-author">Loren Gatlin</span>
                                        <span class="message-time">12:28 AM</span>
                                        <div class="clearfix"></div>
                                        <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="chat.html">
                                <div class="list-item">
                                    <div class="list-left">
                                        <span class="avatar">T</span>
                                    </div>
                                    <div class="list-body">
                                        <span class="message-author">Tarah Shropshire</span>
                                        <span class="message-time">12:28 AM</span>
                                        <div class="clearfix"></div>
                                        <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="topnav-dropdown-footer">
                    <a href="chat.html">See all messages</a>
                </div>
            </div>
        </div>
    </div>
@endsection
