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
                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i><a href="{{route('drug-sections',$drug->id)}}">@lang('admin.doctor.drug_sections')</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">

                    <div class="card">
                        <div class="card-body">
                            <form class="needs-validation"
                                  action="{{route('add-drug-section', $drug->id)}}"
                                  method="POST" enctype="multipart/form-data">
                                {{ method_field('POST') }}
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-heading">
                                            <h4>@lang('admin.doctor.Add a new list')</h4>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="form-group local-forms">
                                            <label >@lang('admin.name_ar') <span class="login-danger">*</span></label>
                                            <input class="form-control" type="text" name="name_ar"  required>
                                        </div>
                                        <span class="text-danger page-header-left"
                                              style="color: red;">{{$errors->first('name_ar')}}</span>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="form-group local-forms">
                                            <label >@lang('admin.name_en') <span class="login-danger">*</span></label>
                                            <input class="form-control" type="text" name="name_en"  required>
                                        </div>
                                        <span class="text-danger page-header-left"
                                              style="color: red;">{{$errors->first('name_en')}}</span>
                                    </div>

                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="form-group local-forms">
                                            <label >@lang('admin.doctor.medicine_type') <span class="login-danger">*</span></label>
                                            <select name="medicine_type" class="form-control" required>
                                                <option value=""> {{trans('admin.select')}}</option>
                                                <option value="cAP">@lang('admin.medicine_type.cAP')</option>
                                                <option value="tab">@lang('admin.medicine_type.tab')</option>
                                                <option value="cap">@lang('admin.medicine_type.cap')</option>
                                                <option value="syr">@lang('admin.medicine_type.syr')</option>
                                                <option value="sus">@lang('admin.medicine_type.sus')</option>
                                                <option value="eye_drops">@lang('admin.medicine_type.eye_drops')</option>
                                                <option value="eye_lotion">@lang('admin.medicine_type.eye_lotion')</option>
                                                <option value="ear_drops">@lang('admin.medicine_type.ear_drops')</option>
                                                <option value="ointment">@lang('admin.medicine_type.ointment')</option>
                                                <option value="inhalation">@lang('admin.medicine_type.inhalation')</option>
                                            </select>
                                        </div>
                                        <span class="text-danger page-header-left"
                                              style="color: red;">{{$errors->first('medicine_type')}}</span>
                                    </div>


                                    <div class="col-12 col-md-4 col-xl-4">
                                        <div class="form-group local-forms">
                                            <label >@lang('admin.doctor.concentration_ratio') <span class="login-danger">*</span></label>
                                            <input class="form-control" name="concentration_ratio"
                                                   type="number" value="{{ old('concentration_ratio') }}"
                                                   required="">
                                        </div>
                                        <span class="text-danger page-header-left"
                                              style="color: red;">{{$errors->first('concentration_ratio')}}</span>
                                    </div>

                                    <div class="col-12 col-md-2 col-xl-2">
                                        <div class="form-group local-forms">
                                            <label >@lang('admin.qty') <span class="login-danger">*</span></label>
                                            <select id="concentration_type" class="form-control"
                                                    name="concentration_type" required>
                                                <option value="Mg">@lang('admin.doctor.Mg')</option>
                                                <option value="g">@lang('admin.doctor.g')</option>
                                            </select>
                                        </div>
                                        <span class="text-danger page-header-left"
                                              style="color: red;">{{$errors->first('concentration_type')}}</span>
                                    </div>


                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="form-group select-gender">
                                            <label class="gen-label">@lang('admin.status') <span class="login-danger">*</span></label>
                                            <div class="form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="radio" name="status" value="1" class="form-check-input" checked>@lang('admin.Active')
                                                </label>
                                            </div>

                                            <div class="form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="radio" name="status" value="0" class="form-check-input">@lang('admin.In Active')
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="doctor-submit text-end">
                                            <button type="submit" class="btn btn-primary submit-form me-2">@lang('admin.add')</button>
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
