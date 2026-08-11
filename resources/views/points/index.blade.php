@extends('includes_admin.mainlayout')
@section('content')
    <!-- Right sidebar Ends-->
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i
                                            data-feather="home"> </i> @lang('admin.dashboard') </a></li>
                                <li class="breadcrumb-item active">@lang('admin.My Points')
                                    ( {{ $data['points']->total() }} )
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">

                <div class="col-xl-12 xl-50 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="number-widgets">
                                <div class="media">
                                    <div class="media-body text-center">
                                        <h6 class="mb-0">@lang('admin.total_points') @lang('admin.from') <span style="color: #ff0000">{{$data['total_points']}}</span></h6>
                                    </div>
                                    <div class="radial-bar radial-bar-{{$data['points']->sum('point')}} radial-bar-primary" data-label="{{ $data['points']->sum('point') }}"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="card">
                        @if( count($data['points']) > 0)
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="display dataTable" id="basic-1">
                                        <thead>
                                        <tr>
                                            <th style="display: none">#</th>
                                            <th>@lang('admin.name')</th>
                                            <th>@lang('main.app_type')</th>
                                            <th>@lang('admin.My Points')</th>
                                            <th>@lang('admin.content')</th>
                                            <th>@lang('admin.created_at')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['points'] as $index=>$point)
                                            <tr>
                                                <td style="display: none">{{ $index + 1 }}</td>
                                                <td>{{ $point->account->name }}</td>
                                                <td>{{ \App\Models\Clinic::app_type_account($point->account->app_type) }}</td>
                                                <td>{{ $point->content }}</td>
                                                <td>{{ $point->point }}</td>
                                                <td>{{ $point->created_at->format('F jS') }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{ $data['points']->links() }}
                        @else
                            <h4 class="text-center" style="color: #ff0000"> @lang('admin.no_data') </h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
