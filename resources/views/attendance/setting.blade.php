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
                                            data-feather="home"> </i> {{ trans('admin.dashboard') }} </a></li>
                                <li class="breadcrumb-item active">@lang('admin.attendance_setting')</li>
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
                                <div class="table-responsive">
                                    <table class="display dataTable" id="basic-1">
                                        <thead>
                                        <tr>
                                            <th>{{ trans('admin.attendance_period') }}</th>
                                            <th>{{ trans('admin.leaving_period') }}</th>
                                            <th>{{ trans('admin.extra_time') }}</th>
                                            <th> {{ trans('admin.action') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>

                                                <td>{{ $setting->attendance_period ?? 0 }}</td>
                                                <td>{{ $setting->leaving_period ?? 0 }}</td>
                                                <td>{{ $setting->extra_time ?? 0 }}</td>
                                                <td>
                                                    {{--                                                    @if (auth()->user()->hasPermissionTo('رد على اتصل بنا'))--}}
                                                    <button class="btn btn-primary" type="button" data-toggle="modal"
                                                            data-target="#{{ $setting->id ?? 0 }}"
                                                            data-whatever="@socialMedia"><i class="fa fa-edit"></i>
                                                    </button>

                                                    <div class="modal fade" id="{{ $setting->id ?? 0 }}" tabindex="-1"
                                                         role="dialog"
                                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"> @lang('admin.attendance_setting')</h5>
                                                                    <button class="close" type="button"
                                                                            data-dismiss="modal"
                                                                            aria-label="Close">
                                                                        <span aria-hidden="true">×</span></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form class="needs-validation" novalidate=""
                                                                          action="{{route('add-attendance-setting',$setting->id ?? 0)}}"
                                                                          method="POST" enctype="multipart/form-data">
                                                                        {{ method_field('POST') }}
                                                                        {{ csrf_field() }}

                                                                        <div class="form-group">
                                                                            <label for="validationCustom05"
                                                                                   class="col-form-label page-header-left">{{ trans('admin.attendance_period') }}  </label>
                                                                            <input type="number" class="form-control"
                                                                                      name="attendance_period"
                                                                                      placeholder="{{ trans('admin.enter') }} {{ trans('admin.attendance_period') }}"
                                                                                      required value="{{ $setting->attendance_period ?? 0 }}">
                                                                            <div
                                                                                class=" invalid-feedback">{{ $errors->first('attendance_period') }}</div>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="validationCustom05"
                                                                                   class="col-form-label page-header-left">{{ trans('admin.leaving_period') }}  </label>
                                                                            <input type="number" class="form-control"
                                                                                   name="leaving_period"
                                                                                   placeholder="{{ trans('admin.enter') }} {{ trans('admin.leaving_period') }}"
                                                                                   required value="{{ $setting->leaving_period ?? 0 }}">
                                                                            <div
                                                                                class=" invalid-feedback">{{ $errors->first('leaving_period') }}</div>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="validationCustom05"
                                                                                   class="col-form-label page-header-left">{{ trans('admin.extra_time') }}  </label>
                                                                            <input type="number" class="form-control"
                                                                                   name="extra_time"
                                                                                   placeholder="{{ trans('admin.enter') }} {{ trans('admin.extra_time') }}"
                                                                                   required value="{{ $setting->extra_time ?? 0 }}">
                                                                            <div
                                                                                class=" invalid-feedback">{{ $errors->first('extra_time') }}</div>
                                                                        </div>
                                                                        <input type="hidden" name="clinic_id" value="{{$auth_app}}">

                                                                        <div class="modal-footer">
                                                                            <button class="btn btn-primary"
                                                                                    type="submit">
                                                                                @lang('admin.edit')
                                                                            </button>
                                                                            <button class="btn btn-secondary"
                                                                                    type="button"
                                                                                    data-dismiss="modal"> {{ trans('admin.close') }}
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>


                                                </td>
                                            </tr>

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
