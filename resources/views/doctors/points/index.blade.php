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
                            <li class="breadcrumb-item active">@lang('admin.My Points') ( {{ $data['points']->total() }} )</li>
                        </ul>
                    </div>
                </div>
            </div>


            <div class="row">

                <div class="col-12 col-md-6 col-xl-7 d-flex text-center" >
                    <div class="card wallet-widget general-health">
                        <div class="circle-bar circle-bar2">
                            <div class="circle-graph2" data-percent="{{$data['points']->sum('point')}}">
                                <b>{{$data['points']->sum('point')}}</b>
                            </div>
                        </div>
                        <div class="main-limit">
                            <p style="font-weight: bold">@lang('admin.total_points')  </p>
                               <span>@lang('admin.from')</span><h4 style="color: #f00"> {{$data['total_points']}}</h4>

                        </div>
                    </div>
                </div>

                <div class="col-sm-12">

                    <div class="card card-table show-entire">
                        <div class="card-body">

                            <!-- Table Header -->
                            <div class="page-table-header mb-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="doctor-table-blk">
                                            <h3>@lang('admin.My Points')</h3>
                                            <div class="doctor-search-blk">
                                                <div class="top-nav-search table-search-blk">
                                                    <form>
                                                        <input type="text" class="form-control" placeholder="@lang('admin.search_here')">
                                                        <a class="btn"><img src="assets/img/icons/search-normal.svg" alt=""></a>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="javascript:;" class=" me-2"><img src="assets/img/icons/pdf-icon-01.svg" alt=""></a>
                                        <a href="javascript:;" class=" me-2"><img src="assets/img/icons/pdf-icon-02.svg" alt=""></a>
                                        <a href="javascript:;" class=" me-2"><img src="assets/img/icons/pdf-icon-03.svg" alt=""></a>
                                        <a href="javascript:;" ><img src="assets/img/icons/pdf-icon-04.svg" alt=""></a>

                                    </div>
                                </div>
                            </div>
                            <!-- /Table Header -->

                            <div class="table-responsive">
                                <table class="table border-0 custom-table comman-table datatable mb-0">
                                    <thead>
                                    <tr>
                                        <th style="display: none">#</th>
                                        <th>@lang('admin.My Points')</th>
                                        <th>@lang('admin.content')</th>
                                        <th>@lang('admin.created_at')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data['points'] as $index=>$point)
                                        <tr>
                                            <td style="display: none">{{ $index + 1 }}</td>
                                            <td>{{ $point->point }}</td>
                                            <td>{{ $point->content }}</td>
                                            <td>{{ $point->created_at->format('F jS') }}</td>
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
