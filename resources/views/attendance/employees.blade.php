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
                                                                        ( {{ $data['employees']->total() }} )
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
                            <form class="needs-validation" novalidate="" action="{{route('attendance-departure')}}"
                                  method="get">
                                {{--                                {{csrf_field()}}--}}
                                <div class="form-row">
                                    <div class="col-md-3 mb-3">
                                        <label for="validationCustom05"
                                               class="page-header-left">@lang('admin.date_from')</label>
                                        <input class="form-control"
                                               name="date_from"
                                               type="date"
                                               value="{{ !empty(\Illuminate\Support\Facades\Session::get('date_from')) ? \Illuminate\Support\Facades\Session::get('date_from') : date('Y-m-d') }}"
                                               placeholder="@lang('admin.date_from')"
                                               autocomplete="true">
                                        <div class="invalid-feedback">{{ $errors->first('date_from') }}
                                        </div>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="validationCustom05"
                                               class="page-header-left">@lang('admin.date_to')</label>

                                        <input class="form-control"
                                               name="date_to"
                                               type="date"
                                               value="{{ !empty(\Illuminate\Support\Facades\Session::get('date_to')) ? \Illuminate\Support\Facades\Session::get('date_to') : date('Y-m-d') }}"
                                               placeholder="@lang('admin.date_to')"
                                               autocomplete="true">
                                        <div class="invalid-feedback">{{ $errors->first('date_to') }}
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="department_id"
                                               class="page-header-left">@lang('admin.administration')</label>

                                        <select id="department_id" name="type" class="form-control" required>
                                            <option value="">@lang('admin.administration')</option>
                                            @foreach($data['departments'] as $depart)
                                                <option
                                                    value="1"
                                                    @if(\Illuminate\Support\Facades\Session::get('department_id') == $depart->id)  selected @endif
                                                >{{ app()->getLocale() == 'en' ? $depart->name_en : $depart->name_ar }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="depart_id" class="page-header-left">@lang('admin.result')</label>
                                        <br>
                                        <button class="btn btn-primary" type="submit">@lang('admin.view_staff')</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        @if( count($data['employees']) > 0)
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="display dataTable" id="basic-1">
                                        <thead>
                                        <tr>
                                            <th style="display: none">#</th>
                                            <th>@lang('admin.image')</th>
                                            <th>@lang('admin.name')</th>
                                            <th>@lang('admin.email')</th>
                                            <th>@lang('admin.phone')</th>
                                            <th>@lang('admin.departments')</th>
                                            <th>@lang('admin.action')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['employees'] as $index=>$employee)
                                            <tr>
                                                <td style="display: none">{{ $index + 1 }}</td>
                                                <td><img class="img-80 rounded-circle" src="{{ $employee->employee->image }}"
                                                         alt="{{ $employee->employee->name }}" style="width:80px;height:80px">
                                                </td>
                                                <td>{{ $employee->employee->name }}</td>
                                                <td>{{ $employee->employee->email }}</td>
                                                <td>{{ $employee->employee->phone }}</td>
                                                <td>{{ \App\Models\Clinic::app_type_account($employee->employee->app_type) }}</td>
                                                <td>
                                                    <form action="{{route('employee-permissions',$employee->employee_id)}}"
                                                          method="get" style="display: inline-block">
                                                        <button class="btn btn-info btn-sm" type="submit" data-whatever="@test" title="@lang('admin.view_employee')"><i class="fa fa-eye"></i></button>

                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{ $data['employees']->links() }}
                        @else
                            <h4 class="text-center" style="color: #ff0000"> @lang('admin.no_data') </h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
