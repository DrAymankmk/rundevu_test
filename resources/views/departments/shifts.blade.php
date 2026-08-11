@extends('includes_admin.mainlayout')
@section('content')
    <!-- Right sidebar Ends-->
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                                <h3>@lang('admin.shifts_item')</h3>

                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i
                                            data-feather="home"> </i> @lang('admin.dashboard') </a></li>
                                    <li class="breadcrumb-item active">@lang('admin.shifts_item') {{ $department->name_en }}{{ count($department->shifts) }}</li>

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
                        <div class="card-body btn-showcase">
                            <!-- Simple demo-->
                            <button class="btn btn-primary" type="button" data-toggle="modal"
                                    data-target="#sub_category" data-whatever="@test">@lang('admin.shifts.add')
                            </button>


                            <div class="modal fade" id="sub_category" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">@lang('admin.shifts.add')</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <form class="needs-validation" novalidate=""
                                                  action="{{route('add-department-shift', $department->id)}}"
                                                  method="POST">
                                                {{ method_field('POST') }}
                                                {{ csrf_field() }}

                                                <div class="form-group">
                                                    <label for="validationCustom05"
                                                           class="col-form-label page-header-left">@lang('admin.shifts.name')</label>
                                                    <input class="form-control" name="name"
                                                           type="text" value="{{ old('name') }}"
                                                           placeholder="@lang('admin.shifts.name')"
                                                           required="">
                                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                                </div>
                                                <span class="text-danger page-header-left"
                                                      style="color: red;">{{$errors->first('name')}}</span>

                                                <div class="form-group">
                                                    <label for="time_from"
                                                           class="col-form-label page-header-left">@lang('admin.shifts.time_from')</label>
                                                    <input class="form-control" name="time_from"
                                                           id="time_from"
                                                           type="time" value="{{ old('time_from') }}"
                                                           placeholder="@lang('admin.shifts.time_from')"
                                                           required="">
                                                    <div
                                                        class="invalid-feedback">{{ $errors->first('time_from') }}</div>
                                                </div>
                                                <span class="text-danger page-header-left"
                                                      style="color: red;">{{$errors->first('time_from')}}</span>

                                                <div class="form-group">
                                                    <label for="time_to"
                                                           class="col-form-label page-header-left">@lang('admin.shifts.time_to')</label>
                                                    <input class="form-control" name="time_to"
                                                           id="time_to"
                                                           type="time" value="{{ old('time_to') }}"
                                                           placeholder="@lang('admin.shifts.time_to')"
                                                           required="">
                                                    <div class="invalid-feedback">{{ $errors->first('time_to') }}</div>
                                                </div>
                                                <span class="text-danger page-header-left"
                                                      style="color: red;">{{$errors->first('time_to')}}</span>


                                                <div class="form-group" style="display: none">
                                                    <label for="minute_allow_delay"
                                                           class="col-form-label page-header-left">@lang('admin.shifts.minute_allow_delay')</label>
                                                    <input class="form-control" name="minute_allow_delay"
                                                           type="number" value=5
                                                           id="minute_allow_delay"
                                                           placeholder="@lang('admin.shifts.minute_allow_delay')"
                                                           >
                                                    <div
                                                        class="invalid-feedback">{{ $errors->first('minute_allow_delay') }}</div>
                                                </div>
                                                <span class="text-danger page-header-left"
                                                      style="color: red;">{{$errors->first('minute_allow_delay')}}</span>

                                                <div class="modal-footer">
                                                    <button class="btn btn-primary"
                                                            type="submit">@lang('admin.add')</button>
                                                    <button class="btn btn-secondary" type="button"
                                                            data-dismiss="modal">@lang('admin.close')
                                                    </button>
                                                </div>
                                            </form>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>


                        @if( count($department->shifts) > 0)
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="display dataTable" id="basic-1">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('admin.shifts.name')</th>
                                            <th>@lang('admin.shifts.time_from')</th>
                                            <th>@lang('admin.shifts.time_to')</th>
{{--                                            <th>@lang('admin.shifts.minute_allow_delay')</th>--}}
                                            <th>@lang('admin.status')</th>
                                            <th>@lang('admin.action')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($department->shifts as $index=>$shift)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $shift->name }}</td>
                                                <td>{{ $shift->time_from }}</td>
                                                <td>{{ $shift->time_to }}</td>
{{--                                                <td>{{ $shift->minute_allow_delay }}</td>--}}
                                                <td>
                                                    <div class="media-body text-left icon-state">
                                                        <label class="switch">
                                                            <input type="checkbox"
                                                                   {{ $shift->status == 1 ? 'checked' : '' }}
                                                                   onchange="change_status_shift({{ $shift->id }},{{ $shift->status }})"><span
                                                                class="switch-state bg-primary"></span>

                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    {{--                                                    <a class="btn btn-primary btn-group-sm"--}}
                                                    {{--                                                       href="{{ route('categories-cities',$sub_category->id) }}"--}}
                                                    {{--                                                       data-whatever="@test" title="{{ trans('admin.all_cities') }}"><i--}}
                                                    {{--                                                            class="fa fa-eye"></i></a>--}}
                                                    <button class="btn btn-primary" type="button"
                                                            data-toggle="modal"
                                                            title="@lang('admin.edit')"
                                                            data-target="#{{ $shift->id }}"
                                                            data-whatever="@test"><i class="fa fa-edit"></i>
                                                    </button>

                                                    <form
                                                        action="{{ route('destroy-department-shift', $shift->id) }}"
                                                        method="post" style="display: inline-block">
                                                        {{ csrf_field() }}
                                                        {{ method_field('delete') }}
                                                        <button type="submit" class="btn btn-danger delete btn-sm">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>


                                                    <div class="modal fade" id="{{ $shift->id }}" tabindex="-1"
                                                         role="dialog"
                                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">
                                                                      @lang('admin.edit_data')  {{ $shift->name }} </h5>

                                                                    <button class="close" type="button"
                                                                            data-dismiss="modal"
                                                                            aria-label="Close">
                                                                        <span aria-hidden="true">×</span></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form class="needs-validation" novalidate=""
                                                                          action="{{route('edit-department-shift',$shift->id)}}"
                                                                          method="POST">
                                                                        {{ method_field('POST') }}
                                                                        {{ csrf_field() }}



                                                                        <div class="form-group">
                                                                            <label for="validationCustom05"
                                                                                   class="col-form-label page-header-left">@lang('admin.shifts.name')</label>
                                                                            <input class="form-control" name="name"
                                                                                   type="text" value="{{ $shift->name }}"
                                                                                   placeholder="@lang('admin.shifts.name')"
                                                                                   required="">
                                                                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                                                        </div>
                                                                        <span class="text-danger page-header-left"
                                                                              style="color: red;">{{$errors->first('name')}}</span>

                                                                        <div class="form-group">
                                                                            <label for="time_from"
                                                                                   class="col-form-label page-header-left">@lang('admin.shifts.time_from')</label>
                                                                            <input class="form-control" name="time_from"
                                                                                   id="time_from"
                                                                                   type="time" value="{{ $shift->time_from }}"
                                                                                   placeholder="@lang('admin.shifts.time_from')"
                                                                                   required="">
                                                                            <div
                                                                                class="invalid-feedback">{{ $errors->first('time_from') }}</div>
                                                                        </div>
                                                                        <span class="text-danger page-header-left"
                                                                              style="color: red;">{{$errors->first('time_from')}}</span>

                                                                        <div class="form-group">
                                                                            <label for="time_to"
                                                                                   class="col-form-label page-header-left">@lang('admin.shifts.time_to')</label>
                                                                            <input class="form-control" name="time_to"
                                                                                   id="time_to"
                                                                                   type="time" value="{{ $shift->time_to }}"
                                                                                   placeholder="@lang('admin.shifts.time_to')"
                                                                                   required="">
                                                                            <div class="invalid-feedback">{{ $errors->first('time_to') }}</div>
                                                                        </div>
                                                                        <span class="text-danger page-header-left"
                                                                              style="color: red;">{{$errors->first('time_to')}}</span>


                                                                        <div class="form-group" style="display: none">
                                                                            <label for="minute_allow_delay"
                                                                                   class="col-form-label page-header-left">@lang('admin.shifts.minute_allow_delay')</label>
                                                                            <input class="form-control" name="minute_allow_delay"
                                                                                   type="number" value="{{ $shift->minute_allow_delay }}"
                                                                                   id="minute_allow_delay"
                                                                                   placeholder="@lang('admin.shifts.minute_allow_delay')"
                                                                            >
                                                                            <div
                                                                                class="invalid-feedback">{{ $errors->first('minute_allow_delay') }}</div>
                                                                        </div>
                                                                        <span class="text-danger page-header-left"
                                                                              style="color: red;">{{$errors->first('minute_allow_delay')}}</span>



                                                                        <div class="modal-footer">
                                                                            <button class="btn btn-primary"
                                                                                    type="submit">
                                                                                @lang('admin.edit')
                                                                            </button>
                                                                            <button class="btn btn-secondary"
                                                                                    type="button"
                                                                                    data-dismiss="modal">@lang('admin.close')
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>

                                                </td>

                                            </tr>
                                        @endforeach
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


    <script>

        function change_status_shift(id, value) {
            if (value == 0) {
                value = 1;
            } else {
                value = 0;
            }
            axios.get('/admin/update-status-department-shift/' + id + '/' + value)
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
