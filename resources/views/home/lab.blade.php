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
{{--                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>--}}
                            <li class="breadcrumb-item px-2"><i id="breadcrumbArrow"></i></li>
                            <li class="breadcrumb-item active">

                                @if (auth()->user()->app_type == 25)
                                    @lang('admin.lab.dashboard')
                                @else
                                    @lang('admin.lab.dashboard_rays')
                                @endif

                            </li>
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
                                <h4><span class="counter-up">{{$data['total_number_invoices']}}</span><span></span><span
                                        class="status-green"></span></h4>
                                <h5>@lang('admin.lab.total_number_invoices')</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="doctor-widget border-right-bg">
                            <div class="doctor-box-icon flex-shrink-0">
                                <img src="/assets/img/icons/doctor-dash-02.svg" alt="">
                            </div>
                            <div class="doctor-content dash-count flex-grow-1">
                                <h4><span
                                        class="counter-up">{{$data['total_number_invoices_unfinished']}}</span><span>/{{$data['total_number_invoices']}}</span><span
                                        class="status-pink"></span></h4>
                                <h5>@lang('admin.lab.total_number_invoices_unfinished')</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="doctor-widget border-right-bg">
                            <div class="doctor-box-icon flex-shrink-0">
                                <img src="/assets/img/icons/doctor-dash-03.svg" alt="">
                            </div>
                            <div class="doctor-content dash-count flex-grow-1">
                                <h4><span
                                        class="counter-up">{{$data['total_number_invoices_finished']}}</span><span>/{{$data['total_number_invoices']}}</span><span
                                        class="status-green"></span></h4>
                                <h5>@lang('admin.lab.total_number_invoices_finished')</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="doctor-widget">
                            <div class="doctor-box-icon flex-shrink-0">
                                <img src="/assets/img/icons/doctor-dash-04.svg" alt="">
                            </div>
                            <div class="doctor-content dash-count flex-grow-1">

                                <h4><span
                                        class="counter-up">{{$data['total_number_invoices_today']}}</span><span></span><span
                                        class="status-green"></span></h4>
                                <h5>@lang('admin.lab.total_number_invoices_today')</h5>
                            </div>
                        </div>
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
                                            @if (auth()->user()->app_type == 25)
                                                <h3>@lang('admin.lab.analysis')</h3>
                                            @else
                                                <h3>@lang('admin.lab.rays')</h3>
                                            @endif
{{--                                            <div class="doctor-search-blk">--}}
{{--                                                <div class="top-nav-search table-search-blk">--}}
{{--                                                    <form>--}}
{{--                                                        <input type="text" class="form-control"--}}
{{--                                                               placeholder="@lang('admin.search_here')">--}}
{{--                                                        <a class="btn"><img src="/assets/img/icons/search-normal.svg"--}}
{{--                                                                            alt=""></a>--}}
{{--                                                    </form>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
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
                                <form action="{{ route('admin.dashboard') }}" method="get">
                                    <div class="row">
                                        <div class="col-12 col-md-6 col-xl-3">
                                            <div class="form-group local-forms cal-icon">
                                                <label>@lang('admin.From') </label>
                                                <input class="form-control datetimepicker" type="text" name="date_from">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-xl-3">
                                            <div class="form-group local-forms cal-icon">
                                                <label>@lang('admin.To') </label>
                                                <input class="form-control datetimepicker" type="text" name="date_to">
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6 col-xl-3">
                                            <div class="form-group local-forms file-action">
                                                <label>@lang('admin.patient_name_file_number') </label>
                                                <input class="form-control" type="text" name="name">
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6 col-xl-3 ms-auto">
                                            <div class="doctor-submit">
                                                <button type="submit"
                                                        class="btn btn-primary submit-list-form me-2">@lang('admin.Search')</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <div class="row">

                                    <div class="col-12 col-md-4 col-xl-4">
                                        <h4>@lang('admin.date') / {{  date('Y-m-d') }}</h4>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table border-0 custom-table comman-table datatable mb-0">
                                        <thead>
                                        <tr>
                                            <th>@lang('admin.patient_name')</th>
                                            <th>@lang('admin.age')</th>
                                            <th>@lang('admin.doctor_name')</th>
                                            <th>@lang('admin.file_type')</th>
                                            <th>@lang('admin.date')</th>
                                            <th>@lang('admin.action')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['analysis'] as $index=>$analysis_item)
                                            <tr>
                                                <td class="profile-image"><a
                                                        href="{{ route('patient-analysis', $analysis_item->user_id) }}"><img
                                                            width="28" height="28"
                                                            src="{{$analysis_item->user->image ?? null}}"
                                                            class="rounded-circle m-r-5"
                                                            alt="">{{$analysis_item->user->name ?? null}}
                                                    </a></td>
                                                <td>{{ \Carbon\Carbon::parse($analysis_item->user->dob)->diff(\Carbon\Carbon::now())->y  }} @lang('admin.year')</td>

                                                <td>{{ $analysis_item->doctor->name }}</td>
                                                <td>@lang('admin.cash')</td>

                                                <td>{{ date('Y-m-d',strtotime($analysis_item->created_at)) }}</td>
                                                <td class="text-end">
                                                    <div class="dropdown dropdown-action">
                                                        <a href="#" class="action-icon dropdown-toggle"
                                                           data-bs-toggle="dropdown" aria-expanded="false"><i
                                                                class="fa fa-ellipsis-v"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item"
                                                               href="{{ route('patient-analysis', $analysis_item->user_id) }}"><i
                                                                    class="feather-eye m-r-5"></i>
                                                                @if (auth()->user()->app_type == 25)
                                                                    @lang('admin.lab.analysis')
                                                                @else
                                                                    @lang('admin.lab.rays')
                                                                @endif
                                                            </a>

                                                        </div>
                                                    </div>

                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{--                            {{ $data['patients_waiting']->links() }}--}}
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
