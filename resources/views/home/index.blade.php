@extends('includes_admin.mainlayout')
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            {{--                            <h3>{{ trans('admin.dashboard') }}</h3>--}}
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href=" {{route('admin.dashboard')}}"><i data-feather="home"> </i> {{ trans('admin.dashboard') }}  </a></li>
                            </ol>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        {{--    @if (auth()->user()->hasPermissionTo('عرض الصفحة الرئيسية'))--}}
        <!-- Container-fluid starts-->
        <div class="container-fluid">

                <div class="row">
                    <div class="col-sm-6 col-xl-3 col-lg-4">
                        <div class="dash-widget dashboard-link-widget" data-href="{{ route('department-employees',3) }}">
                            <div class="dash-boxs comman-flex-center">
                                <i class="fa-solid fa-user-check"></i>
                            </div>
                            <div class="dash-content dash-count">
                                <h4>@lang('admin.active_doctors')</h4>
                                <h2><span class="counter-up">{{ $data['active_doctors_count'] ?? 0 }}</span></h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3 col-lg-4">
                        <div class="dash-widget dashboard-link-widget" data-href="{{ route('department-employees',2) }}">
                            <div class="dash-boxs comman-flex-center">
                                <i class="fa-solid fa-headset"></i>
                            </div>
                            <div class="dash-content dash-count">
                                <h4><a href="{{ route('department-employees',2) }}">@lang('admin.receptionists')</a></h4>
                                <h2><span class="counter-up">{{ $data['receptionists'] ?? 0 }}</span></h2>
                            </div>
                        </div>
                    </div>



                    <div class="col-sm-6 col-xl-3 col-lg-4">
                        <div class="dash-widget dashboard-link-widget" data-href="{{ route('specialties') }}">
                            <div class="dash-boxs comman-flex-center">
                                <i class="fa-solid fa-stethoscope"></i>
                            </div>
                            <div class="dash-content dash-count">
                                <h4><a href="{{ route('specialties') }}">@lang('admin.Manage available specialties')</a></h4>
                                <h2><span class="counter-up">{{ $data['specialties_count'] ?? 0 }}</span></h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3 col-lg-4">
                        <div class="dash-widget dashboard-link-widget" data-href="{{ route('offers') }}">
                            <div class="dash-boxs comman-flex-center">
                                <i class="fa-solid fa-tags"></i>
                            </div>
                            <div class="dash-content dash-count">
                                <h4><a href="{{ route('offers') }}">@lang('admin.Manage Offers')</a></h4>
                                <h2><span class="counter-up">{{ $data['offers_count'] ?? 0 }}</span></h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3 col-lg-4">
                        <div class="dash-widget dashboard-link-widget" data-href="{{ route('branches') }}">
                            <div class="dash-boxs comman-flex-center">
                                <i class="fa-solid fa-code-branch"></i>
                            </div>
                            <div class="dash-content dash-count">
                                <h4><a href="{{ route('branches') }}">@lang('admin.Manage Branch')</a></h4>
                                <h2><span class="counter-up">{{ $data['branches'] ?? 0 }}</span></h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3 col-lg-4">
                        <div class="dash-widget dashboard-link-widget" data-href="{{ route('contactUs') }}">
                            <div class="dash-boxs comman-flex-center">
                                <i class="fa-solid fa-message"></i>
                            </div>
                            <div class="dash-content dash-count">
                                <h4><a href="{{ route('contactUs') }}">@lang('admin.Complaints Box')</a></h4>
                                <h2><span class="counter-up">{{ $data['complaints_count'] ?? 0 }}</span></h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3 col-lg-4">
                        <div class="dash-widget dashboard-link-widget" data-href="{{ route('financial-reports.index') }}">
                            <div class="dash-boxs comman-flex-center">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                            </div>
                            <div class="dash-content dash-count">
                                <h4><a href="{{ route('financial-reports.index') }}">@lang('financial_reports.clinic_dashboard_title')</a></h4>
                                <p>@lang('financial_reports.clinic_dashboard_hint')</p>
                            </div>
                        </div>
                    </div>



                </div>
                <div class="row">
                    <div class="col-lg-6 col-sm-6 col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ trans('admin.last_seven_days_complains') }} <span class="digits"></span></h4>
                            </div>
                            @if(count($data['complains_charts']) > 0)
                                <div class="card-body chart-block ">
                                    <div class="chart-overflow" id="complains_charts"></div>
                                </div>
                            @else
                                <h6 style="text-align: center;color: #f00">{{ trans('admin.no_data') }}</h6>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-6 col-sm-6 col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ trans('admin.last_seven_days_Reservations') }} <span class="digits"></span></h4>
                            </div>
                            @if(count($data['reservations_charts']) > 0)
                                <div class="card-body chart-block ">
                                    <div class="chart-overflow" id="reservations_charts"></div>
                                </div>
                            @else
                                <h6 style="text-align: center;color: #f00">{{ trans('admin.no_data') }}</h6>
                            @endif
                        </div>
                    </div>
                </div>
        </div>
        <!-- Container-fluid Ends-->

    </div>




    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.dashboard-link-widget').forEach(function (widget) {
                widget.style.cursor = 'pointer';
                widget.addEventListener('click', function (event) {
                    if (event.target.closest('a, button, input, select, textarea')) {
                        return;
                    }
                    window.location.href = widget.dataset.href;
                });
            });
        });
    </script>
@endsection
