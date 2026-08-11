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
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i data-feather="home"> </i> @lang('admin.dashboard') </a></li>
                                <li class="breadcrumb-item"><a href="{{route('departments')}}"><i data-feather="home"> </i> @lang('admin.departments') </a></li>
                                <li class="breadcrumb-item active">{{ $data['app_type']->name }}
                                    (  {{ $data['employees']->total() }} )
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                            <div class="page-header-left">
                                <a class="btn btn-square btn-primary" href="{{route('create-department-employee', $data['app_type']->id)}}"
                                   title="@lang('admin.add')">
                                    @lang('admin.add') {{ $data['employee_job'] }}</a> &nbsp; &nbsp;
                            </div>



                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
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
                                            <th>@lang('admin.status')</th>
                                            <th>@lang('admin.action')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['employees'] as $index=>$employee)
                                            <tr>
                                                <td style="display: none">{{ $index + 1 }}</td>
                                                <td><img class="img-80 rounded-circle" src="{{ $employee->image }}"
                                                         alt="{{ $employee->name }}" style="width:80px;height:80px">
                                                </td>
                                                <td>{{ $employee->name }}</td>
                                                <td>{{ $employee->email }}</td>
                                                <td>{{ $employee->phone }}</td>
                                                <td>
                                                    <div class="media-body text-left icon-state">
                                                        <label class="switch">
                                                            <input type="checkbox"
                                                                   {{ $employee->status == 1 ? 'checked' : '' }}
                                                                   onchange="change_status_department_employees({{ $employee->id }},{{ $employee->status }})"><span
                                                                class="switch-state bg-primary"></span>

                                                        </label>
                                                    </div>

                                                </td>
                                                <td>
                                                    @if($data['app_type']->id == 3)
                                                    <form action="{{route('employee-shifts',$employee->id)}}"
                                                          method="get" style="display: inline-block">
                                                        <button class="btn btn-info btn-sm" type="submit" data-whatever="@test" title="@lang('admin.shifts_item')" aria-label="@lang('admin.shifts_item')">
                                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                                <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                                                                <path d="M16 2v4"></path>
                                                                <path d="M8 2v4"></path>
                                                                <path d="M3 10h18"></path>
                                                                <path d="M8 14h3"></path>
                                                                <path d="M13 18h3"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    @endif

                                                    <form action="{{route('department-employee-update',$employee->id)}}"
                                                          method="get" style="display: inline-block">
                                                        <button class="btn btn-info btn-sm" type="submit" data-whatever="@test" title="@lang('admin.edit')"><i class="fa fa-edit"></i></button>

                                                    </form>

                                                        <form action="{{ route('destroyDepartmentEmployee', $employee->id) }}"
                                                              method="post" style="display: inline-block">
                                                            {{ csrf_field() }}
                                                            {{ method_field('delete') }}
                                                            <button type="submit"
                                                                    class="btn btn-danger delete btn-sm"><i
                                                                    class="fa fa-trash"></i>
                                                            </button>
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

    <script>

        function change_status_department_employees(id, value) {
            if (value == 0) {
                value = 1;
            } else {
                value = 0;
            }
            axios.get('/admin/UpdateStatusDepartmentEmployees/' + id + '/' + value)
                .then(function (response) {
                    location.reload();
                })
                .catch(function (error) {
                    console.log(error);
                    alert(error);
                });
        };


    </script>

@endsection
