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
                            <li class="breadcrumb-item active">@lang('accounting.account_dashboard')</li>
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
                        <a href="{{ route('accounts',8) }}">
                        <div class="doctor-widget border-right-bg">
                            <div class="doctor-box-icon flex-shrink-0" style="background-color: #fff">
                                <img src="/assets/img/icons/menu-icon-01.svg" alt="">
                            </div>
                            <div class="doctor-content dash-count flex-grow-1">
                                <h4><span class="status-green"></span></h4>
                                <h5>@lang('accounting.accounting')</h5>
                            </div>
                        </div>
                        </a>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('restrictions') }}">
                            <div class="doctor-widget border-right-bg">
                                <div class="doctor-box-icon flex-shrink-0" style="background-color: #fff">
                                    <img src="/assets/img/icons/menu-icon-01.svg" alt="">
                                </div>
                                <div class="doctor-content dash-count flex-grow-1">
                                    <h4><span class="status-pink"></span></h4>
                                    <h5>@lang('accounting.restrictions')</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('financial-bonds') }}">
                        <div class="doctor-widget border-right-bg">
                            <div class="doctor-box-icon flex-shrink-0" style="background-color: #fff">
                                <img src="/assets/img/icons/menu-icon-01.svg" alt="">
                            </div>
                            <div class="doctor-content dash-count flex-grow-1">
                                <h4><span class="status-pink"></span></h4>
                                <h5>@lang('accounting.financial_bonds')</h5>
                            </div>
                        </div>
                        </a>
                    </div>

                <div class="col-xl-3 col-md-6">
                    <a href="{{ route('costCenter') }}">
                        <div class="doctor-widget">
                            <div class="doctor-box-icon flex-shrink-0" style="background-color: #fff">
                                <img src="/assets/img/icons/menu-icon-01.svg" alt="">
                            </div>
                            <div class="doctor-content dash-count flex-grow-1">
                                <h4><span class="status-pink"></span></h4>
                                <h5>@lang('accounting.cost_center')</h5>
                            </div>
                        </div>
                    </a>
                    </div>
                    <br>
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('account-settings') }}">
                            <div class="doctor-widget">
                                <div class="doctor-box-icon flex-shrink-0" style="background-color: #fff">
                                    <img src="/assets/img/icons/menu-icon-01.svg" alt="">
                                </div>
                                <div class="doctor-content dash-count flex-grow-1">
                                    <h4><span class="status-pink"></span></h4>
                                    <h5>@lang('accounting.settings')</h5>
                                </div>
                            </div>
                        </a>
                    </div>


                </div>
            </div>



        </div>

    </div>


@endsection
