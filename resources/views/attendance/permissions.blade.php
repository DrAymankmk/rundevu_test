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
                                <li class="breadcrumb-item active">@lang('admin.view_employee_permissions')
                                    ( {{ $data['permissions']->total() }} )
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">

                <div class="col-sm-12">
                    <div class="card">
                        @if( count($data['permissions']) > 0)
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="display dataTable" id="basic-1">
                                        <thead>
                                        <tr>
                                            <th style="display: none">#</th>
                                            <th>@lang('admin.name')</th>
                                            <th>@lang('main.app_type')</th>
                                            <th>@lang('admin.date')</th>
                                            <th>@lang('admin.permission_type')</th>
                                            <th>@lang('admin.reason')</th>
                                            <th>@lang('admin.permission_status')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['permissions'] as $index=>$permission)
                                            <tr>
                                                <td style="display: none">{{ $index + 1 }}</td>
                                                <td>{{ $permission->account->name ?? null }}</td>
                                                <td>{{ \App\Models\Clinic::app_type_account($permission->account->app_type) }}</td>
                                                <td>{{ $permission->dateA }}</td>
                                                <td>{{ app()->getLocale() == 'en' ? $permission->permissions_type->name_en : $permission->permissions_type->name_ar }}</td>
                                                <td>{{ $permission->reason }}</td>
                                                @if($permission->status == 0)
                                                <td>
                                                    <form action="{{route('update-status-permission',[$permission->id,1])}}"
                                                          method="get" style="display: inline-block">
                                                        <button class="btn btn-success btn-sm" type="submit" data-whatever="@test" title="@lang('admin.acceptable')"><i class="fa fa-check"></i></button>
                                                    </form>

                                                    <form action="{{route('update-status-permission',[$permission->id,2])}}"
                                                          method="get" style="display: inline-block">
                                                        <button class="btn btn-danger btn-sm" type="submit" data-whatever="@test" title="@lang('admin.unacceptable')"><i class="fa fa-window-close"></i></button>
                                                    </form>

                                                </td>
                                                @elseif($permission->status == 1)
                                                    <td style="color: #00c292">@lang('admin.acceptable')</td>
                                                @else
                                                    <td style="color: #ff0000"> @lang('admin.unacceptable') </td>
                                                @endif

                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{ $data['permissions']->links() }}

                        @else
                            <h4 class="text-center" style="color: #ff0000"> @lang('admin.no_data') </h4>
                        @endif
                    </div>
                    

                    <div class="card-body" style="float: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }}">
                        <div class="table-responsive">
                            <table class="display dataTable" id="basic-1">
                                <tbody>
                                <tr>
                                    <div style="font-weight: bold">@lang('admin.total_early_departure'):</div><br>
                                    <div style="font-weight: bold">@lang('admin.total_delay_minute'):</div><br>
                                    <div style="font-weight: bold">@lang('admin.total_extra_minute'):</div><br>
                                    <div style="font-weight: bold">@lang('admin.total_absence_days'):</div><br>
                                    <div style="font-weight: bold">@lang('admin.total_permission'):</div><br>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>

@endsection
