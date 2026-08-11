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
                                <li class="breadcrumb-item active">@lang('admin.Attendance and Departure')
                                   ( {{$data['employee']->name}} )
                                    {{--                                    ( {{ $data['employees']->total() }} )--}}
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
                        <div class="card-body">
                            <h5>@lang('admin.view_employee_permissions') ({{ $data['employee']->name }})</h5>

                            <div class="table-responsive">
                                <table class="display dataTable" id="basic-2">
                                    <thead>
                                    <tr>
                                        <th>@lang('admin.image')</th>
                                        <th>@lang('admin.name')</th>
                                        <th>@lang('admin.email')</th>
                                        <th>@lang('admin.phone')</th>
                                        <th>@lang('admin.action')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><img class="img-80 rounded-circle" src="{{ $data['employee']->image }}"
                                                     alt="{{ $data['employee']->name }}" style="width:80px;height:80px">
                                            </td>
                                            <td>{{ $data['employee']->name }}</td>
                                            <td>{{ $data['employee']->email }}</td>
                                            <td>{{ $data['employee']->phone }}</td>
                                            <td>
                                                <form action="{{route('employee-permissions',$data['employee']->id)}}"
                                                      method="get" style="display: inline-block">
                                                    <button class="btn btn-info btn-sm" type="submit" data-whatever="@test" title="@lang('admin.view_employee_permissions')"><i class="fa fa-eye"></i></button>
                                                </form>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>


                        @if( count($data['attendance_departure']) > 0)
                            <div class="card-body">
                                <h5>@lang('admin.Attendance and Departure') ({{ $data['employee']->name }})</h5>
                                <div class="table-responsive">
                                    <table class="display dataTable" id="basic-1">
                                        <thead>
                                        <tr>
                                            <th style="display: none">#</th>
                                            <th>@lang('admin.date')</th>
                                            <th>@lang('admin.day')</th>
                                            <th>@lang('admin.audience')</th>
                                            <th>@lang('admin.leave')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['attendance_departure'] as $index=>$attendance)
                                            <tr>
                                                <td style="display: none">{{ $index + 1 }}</td>
                                                <td>{{ $attendance->dateA }}</td>
                                                <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $attendance->dateA)->dayName }}</td>
                                                <td>{{ $attendance->audience }}</td>
                                                <td>{{ $attendance->leave }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{ $data['attendance_departure']->links() }}

                            <div class="card-body">
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
                        @else

                            <h4 class="text-center" style="color: #ff0000"> @lang('admin.no_data') </h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
