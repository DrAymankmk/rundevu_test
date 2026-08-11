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
                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i><a href="{{ route('drugs') }}">@lang('admin.doctor.Drug lists')</a></li>
                            <li class="breadcrumb-item active">@lang('admin.edit')</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">

                    <div class="card">
                        <div class="card-body">
                            <form class="needs-validation"
                                  action="{{route('edit-drug', $drug->id)}}"
                                  method="POST" enctype="multipart/form-data">
                                {{ method_field('POST') }}
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-heading">
                                            <h4>@lang('admin.edit') {{ $drug->title }}</h4>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="form-group local-forms">
                                            <label >@lang('admin.name_ar') <span class="login-danger">*</span></label>
                                            <input class="form-control" type="text" name="name_ar" value="{{$drug->name_ar}}"  required>
                                        </div>
                                        <span class="text-danger page-header-left"
                                              style="color: red;">{{$errors->first('name_ar')}}</span>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="form-group local-forms">
                                            <label >@lang('admin.name_en') <span class="login-danger">*</span></label>
                                            <input class="form-control" type="text" name="name_en" value="{{$drug->name_en}}"  required>
                                        </div>
                                        <span class="text-danger page-header-left"
                                              style="color: red;">{{$errors->first('name_en')}}</span>
                                    </div>


                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="form-group select-gender">
                                            <label class="gen-label">@lang('admin.status') <span class="login-danger">*</span></label>
                                            <div class="form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="radio" name="status" value="1" @if($drug->status == 1) checked @endif class="form-check-input" >@lang('admin.Active')
                                                </label>
                                            </div>

                                            <div class="form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="radio" name="status" value="0" @if($drug->status == 0) checked @endif class="form-check-input">@lang('admin.In Active')
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="doctor-submit text-end">
                                            <button type="submit" class="btn btn-primary submit-form me-2">@lang('admin.edit')</button>
                                            <button type="submit" class="btn btn-primary cancel-form">@lang('admin.cancel')</button>
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
