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
                            <li class="breadcrumb-item active">@lang('admin.change_password')</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <form class="needs-validation"
                                  action="{{ route('add-password') }}"
                                  method="POST" enctype="multipart/form-data">
                                {{ method_field('POST') }}
                                {{ csrf_field() }}
                                <div class="row">
                                    <h4 class="page-title">{{ trans('admin.change_password') }}</h4>
                                    <div class="col-12 col-md-6 col-xl-12">
                                        <div class="form-group local-forms">
                                            <label>{{ trans('admin.old_password') }} <span class="login-danger">*</span></label>
                                            <input class="form-control" type="password" name="old_password" id="old_password"
                                                   required
                                                   placeholder="{{ trans('admin.enter') }} {{ trans('admin.new_password') }}">
                                            <div class="invalid-feedback">{{ $errors->first('old_password') }}.</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="form-group local-forms">
                                            <label>{{ trans('admin.new_password') }} <span class="login-danger">*</span></label>
                                            <input class="form-control" type="password" name="new_password"
                                                   id="new_password"
                                                   required
                                                   placeholder="{{ trans('admin.enter') }} {{ trans('admin.new_password') }}">
                                            <div class="invalid-feedback">{{ $errors->first('new_password') }}.</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="form-group local-forms">
                                            <label>{{ trans('admin.confirm_password') }} <span class="login-danger">*</span></label>
                                            <input class="form-control" type="password" name="confirm_password"
                                                   id="confirm_password"
                                                   required
                                                   placeholder="{{ trans('admin.enter') }} {{ trans('admin.confirm_password') }}">
                                            <div class="invalid-feedback">{{ $errors->first('confirm_password') }}.
                                            </div>
                                            <span id='message'></span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="lang" id="lang" value="{{app()->getLocale()}}"/>

                                    <div class="col-12">
                                        <div class="doctor-submit text-end">
                                            <button type="submit" class="btn btn-primary submit-form me-2">{{ trans('admin.change_password') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
