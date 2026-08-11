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
                            <li class="breadcrumb-item active">@lang('admin.doctor.request a permission')</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <form class="needs-validation"
                                  action="{{ route('send-request-permission') }}"
                                  method="POST" enctype="multipart/form-data">
                                {{ method_field('POST') }}
                                {{ csrf_field() }}
                                <h4 class="page-title">@lang('admin.doctor.request a permission')</h4>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">@lang('admin.permission_type') <span class="star-red">*</span></label>
                                    <div class="col-lg-7">
                                        <select class="select form-control" name="permission_id" required>
                                            @foreach($data['permissions_types'] as $type)
                                            <option selected="selected" value="{{ $type->id }}">{{$type->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">@lang('admin.reason_permission') <span class="star-red">*</span></label>
                                    <div class="col-lg-7">
                                        <textarea class="form-control" name="reason" required></textarea>
                                    </div>

                                </div>

                                <div class="form-group theme-set row">
                                    <label class="col-lg-3 col-form-label">@lang('admin.image') </label>
                                    <div class="col-lg-7">
                                        <input class="form-control" type="file" name="image">
                                    </div>
                                </div>

                                <div class="m-t-20 text-center">
                                    <button class="btn btn-primary submit-btn">@lang('admin.save')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
