<?php $page = 'edit-user'; ?>
@extends('layout_new.mainlayout')
@section('content')
    <!-- ========================
        Start Page Content
    ========================= -->

    <div class="page-wrapper">

        <!-- Start Content -->
        <div class="content">

            <!-- row start -->
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- page header start -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-0 d-flex align-items-center"> <a href="{{route('users')}}" class="text-dark"> <i class="ti ti-chevron-left me-1"></i>{{ trans('main.Patients') }}</a></h6>
                    </div>
                    <!-- page header end -->

                    <!-- card start -->
                    <form method="post" id="add_patient_form" action="{{ route('update-user', $user->id) }}"
                          enctype="multipart/form-data">
                        <input type="hidden" name="patient_id" value="{{$user->id ?? null}}">
                        @csrf
                    <div class="card">
                        <div class="card-body pb-0">

                            <div class="form">
                                <h6 class="fw-bold mb-3">{{ trans('main.Patient Information') }}</h6>
                                <div class="row">

                                    <div class="col-lg-12">
                                        <div class="mb-3 d-flex align-items-center">
                                            <label class="form-label mb-0">{{ trans('main.Profile Image') }}</label>
                                            <div class="drag-upload-btn avatar avatar-xxl rounded-circle bg-light text-muted position-relative overflow-hidden z-1 mb-2 ms-4 p-0">
                                                <img src="{{ $user->image }}" alt="img" class="position-relative z-n1">
                                                <input type="file" name="image" class="form-control image-sign" >
                                                <div class="position-absolute bottom-0 end-0 star-0 w-100 h-25 bg-dark d-flex align-items-center justify-content-center z-n1">
                                                    <a href="javascript:void(0);" class="text-white d-flex align-items-center justify-content-center">
                                                        <i class="ti ti-photo fs-14"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">@lang('admin.reception.name')<span class="text-danger ms-1">*</span></label>
                                            <input type="text" class="form-control" placeholder="@lang('admin.reception.name')" name="name" value="{{$nameParts[0] ?? null}}">
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">@lang('admin.reception.Father Name')<span class="text-danger ms-1">*</span></label>
                                            <input type="text" class="form-control" placeholder="@lang('admin.reception.Father Name')" name="father_name" value="{{$nameParts[1] ?? null}}">
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">@lang('admin.reception.Grandfather name')<span class="text-danger ms-1">*</span></label>
                                            <input type="text" class="form-control" placeholder="@lang('admin.reception.Grandfather name')" name="Grandfather_name" value="{{$nameParts[2] ?? null}}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">@lang('admin.reception.Family name')<span class="text-danger ms-1">*</span></label>
                                            <input type="text" class="form-control" placeholder="@lang('admin.reception.Family name')" name="family_name" value="{{$nameParts[3] ?? null}}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">{{ trans('main.Phone Number') }}<span class="text-danger ms-1">*</span></label>
                                            <input type="tel" class="form-control" id="phone" name="phone" value="{{ $user->phone }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">{{ trans('main.Email Address') }}<span class="text-danger ms-1">*</span></label>
                                            <input type="email" class="form-control" name="email" value="{{ $user->email }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">{{ trans('main.cities') }}<span class="text-danger ms-1">*</span></label>
                                            <select class="select" name="city_id" required>
                                                @foreach($cities as $city)
                                                <option value="{{$city->id}}" @if($city->id == $user->city_id) selected @endif>{{ app()->getLocale() == 'en' ? $city->name_en : $city->name_ar }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">{{ trans('main.DOB') }}<span class="text-danger ms-1">*</span></label>
                                            <div class="input-icon-end position-relative">
                                                <input type="text" class="form-control datetimepicker" name="dob" placeholder="dd/mm/yyyy" value="{{ $user->dob }}">
                                                <span class="input-icon-addon">
                                                    <i class="ti ti-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">{{ trans('main.doctor_details.gender') }}<span class="text-danger ms-1">*</span></label>
                                            <select class="select" name="gender" required>
                                                <option value="1">{{ trans('admin.male') }}</option>
                                                <option value="2">{{ trans('admin.female') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">{{ trans('admin.file_number') }}<span class="text-danger ms-1">*</span></label>
                                            <input type="text" class="form-control" name="file_number" value="{{ $user->file_number }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">{{ trans('admin.status') }}<span class="text-danger ms-1">*</span></label>
                                            <select class="select" name="status">
                                                <option value="1" @if($user->status == 1) selected @endif>@lang('admin.Active')</option>
                                                <option value="0" @if($user->status == 0) selected @endif>@lang('admin.Inactive')</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>
                    <!-- card end -->

                    <div class="d-flex align-items-center justify-content-end">
                        <a href="javascript:void(0);" class="btn btn-light me-2">{{ trans('admin.Cancel') }}</a>
                        <button type="submit" id="add_patient" class="btn btn-primary submit-form me-2">{{ trans('admin.Save Changes') }}</button>
                    </div>
                    </form>
                </div>
            </div>
            <!-- row end -->

        </div>
        <!-- End Content -->

        @component('components.footer')
        @endcomponent

    </div>

    <!-- ========================
        End Page Content
    ========================= -->

@endsection
