@extends('includes_admin.mainlayout')
@section('content')

    <div class="page-wrapper">
        <div class="content">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('patients') }}">@lang('admin.reception.reception') </a></li>
                            <li class="breadcrumb-item px-2"><i id="breadcrumbArrow"></i></li>
                            <li class="breadcrumb-item active p-0">@lang('admin.reception.edit_file')</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" id="add_patient_form" action="{{ route('create-patient') }}"
                                  enctype="multipart/form-data" class="" class="was-validated needs-validation">
                                <input type="hidden" name="patient_id" value="{{$patient->id ?? null}}">
                                @csrf
                                <div class="d-flex flex-wrap justify-content-between m-b-30">
                                    <div class="form-heading">
                                        <h4 class="mb-0">@lang('admin.reception.Patient data')</h4>
                                    </div>
                                    <div>
                                        <input class="d-none" type="checkbox" checked="checked" id="vaildation" name="valid">
                                        <label class="validate-lable btn btn-primary" for="vaildation">
                                            <span>@lang('admin.reception.Account verification') <i class="fa fa-user-shield font-18 m-l-5"></i></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="profile-user-box">
                                            <div class="profile-user-img top-0 m-0 m-b-30">
                                                <img style="width: 185px; height: 185px; border: 2px solid black;" id="avatar" src="{{ $patient->image }}" alt="Profile">
                                                <div class="form-group doctor-up-files profile-edit-icon mb-0">
                                                    <div class="uplod d-flex">
                                                        <label for="image" class="file-upload profile-upbtn mb-0">
                                                            <img src="/assets/img/icons/camera-icon.svg" alt="Profile"></i><input type="file" accept="image/*" name="image" id="image" onchange="uploadImage(event)">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-3">
                                        <div class="form-group local-forms">
                                            <label>@lang('admin.reception.name') <span class="login-danger">*</span></label>
                                            <input class="form-control" type="text" placeholder="@lang('admin.reception.name')" name="name" value="{{$nameParts[0] ?? null}}" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-3">
                                        <div class="form-group local-forms">
                                            <label> @lang('admin.reception.Father Name') <span class="login-danger">*</span></label>
                                            <input class="form-control" type="text" placeholder="@lang('admin.reception.Father Name')" name="father_name" value="{{$nameParts[1] ?? null}}" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-3">
                                        <div class="form-group local-forms">
                                            <label> @lang('admin.reception.Grandfather name') <span class="login-danger">*</span></label>
                                            <input class="form-control" type="text" placeholder="@lang('admin.reception.Grandfather name')" name="Grandfather_name"  value="{{$nameParts[2] ?? null}}" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-3">
                                        <div class="form-group local-forms">
                                            <label>@lang('admin.reception.Family name')  <span class="login-danger">*</span></label>
                                            <input class="form-control" type="text" placeholder="@lang('admin.reception.Family name')" name="family_name" value="{{$nameParts[3] ?? null}}" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="form-group local-forms">
                                            <label> @lang('admin.email')</label>
                                            <input class="form-control" type="email" placeholder="@lang('admin.email')" name="email" value="{{$patient->email}}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="form-group local-forms">
                                            <label for="nationality_id"> @lang('admin.patient_nationality') <span class="login-danger">*</span></label>
                                            <select class="form-control select" id="nationality_id" name="nationality_id">
                                                <option selected="true"  disabled="disabled">@lang('admin.select') </option>
                                                @foreach($nationality as $national)
                                                    <option value="{{$national->id}}" @if($national->id == $patient->nationality_id) selected @endif>{{$national->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="form-group local-forms">
                                            <label>@lang('admin.address')<span class="login-danger">*</span></label>
                                            <input class="form-control" type="text" name="address_1" placeholder="@lang('admin.address')"  value="{{$patient->address_1}}" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="form-group local-forms">
                                            <label>@lang('admin.reception.Additional_information_address')</label>
                                            <input class="form-control" type="text" name="address_2" placeholder="@lang('admin.reception.Additional_information_address')" value="{{$patient->address_2 ?? null}}" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-3">
                                        <div class="form-group local-forms">
                                            <label for="city_id"> @lang('admin.reception.region')<span class="login-danger">*</span></label>
                                            <select class="form-control select" id="city_id" name="city_id" required>
                                                <option selected="true" disabled="disabled">@lang('admin.reception.select_city') </option>
                                                @foreach($cities as $city)
                                                    <option value="{{$city->id}}" @if($city->id == $patient->city_id) selected @endif>{{$city->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-3">
                                        <div class="form-group local-forms">
                                            <label for="region_id"> @lang('admin.reception.city')<span class="login-danger">*</span></label>
                                            <select class="form-control select" name="region_id" id="region_id" required>
                                                <option  disabled="disabled">@lang('admin.reception.select_region')</option>
                                                <option value="{{$patient->region_id ?? null}}" selected >{{app()->getLocale() == 'en' ? $patient->region->name_en ?? null : $patient->region->name_ar ?? null }}</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-3">
                                        <div class="form-group local-forms">
                                            <label> @lang('admin.reception.postal_code')</label>
                                            <input class="form-control" type="text" name="postal_code" placeholder="@lang('admin.reception.postal_code')" value="{{$patient->postal_code ?? null}}">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-xl-3">
                                        <div class="form-group local-forms">
                                            <label> @lang('admin.dob') <span class="login-danger">*</span></label>
                                            <input class="form-control datetimepicker" type="date" name="dob" id="dob" max="{{ date('Y-m-d') }}"   data-date-format="YYYY-MM-DD"  placeholder="@lang('admin.dob')" value="{{$patient->dob ?? null}}" required>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-xl-4">
                                        <div class="form-group local-forms">
                                            <label> @lang('admin.phone')<span class="login-danger">*</span></label>
                                            <input class="form-control" type="text" name="phone" placeholder="@lang('admin.phone')" value="{{$patient->phone ?? null}}" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-4">
                                        <div class="form-group local-forms">
                                            <label> @lang('admin.mobile_number')<span class="login-danger">*</span></label>
                                            <input class="form-control" type="text" name="mobile_number" placeholder="@lang('admin.mobile_number')" value="{{$patient->mobile_number ?? null}}" required>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-xl-4">
                                        <div class="form-group local-forms">
                                            <label for="gender"> @lang('admin.gender')<span class="login-danger">*</span></label>
                                            <select class="form-control select" name="gender" id="gender">
                                                <option selected="true" disabled="disabled">@lang('admin.select') @lang('admin.gender')</option>
                                                <option value="1" @if($patient->gender == 1) selected @endif>@lang('admin.male')</option>
                                                <option value="2" @if($patient->gender == 2) selected @endif>@lang('admin.female')</option>
                                            </select>
                                        </div>
                                    </div>

                                    <input type="hidden" value="{{ Session()->get('lang') }}" name="lang" id="lang">
                                    <div class="col-12">
                                        <div class="doctor-submit text-end">
                                            <button type="submit" id="add_patient" class="btn btn-primary submit-form me-2">@lang('admin.save')</button>
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

    <script src="{{asset('/admin/js/jquery-3.2.1.min.js')}}"></script>

    <script>
        function uploadImage(e) {
            document.getElementById('avatar').src = URL.createObjectURL(e.target.files[0]);
        }
        $(document).ready(function () {

            $('#city_id').on('change', function () {
                var city_id = this.value;
                var lang = $('#lang').val();
                $('#region_id').html('');
                $.ajax({
                    url: '{{ route('getRegions') }}?city_id=' + city_id,
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        if (res.length > 0) { // all was ok
                            var region_text = "{{ trans('admin.reception.select_region') }}"
                            $('#region_id').html('<option value="" disabled> '+ region_text +' </option>');
                            $.each(res, function (key, value) {
                                $('#region_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        } else {
                            $('#region_id').html('<option value="" disabled selected>{{ trans('admin.no_data') }} </option>');
                        }
                    }
                });
            });

            $('#company_id').on('change', function () {
                var company_id = this.value;
                var lang = $('#lang').val();
                $('#class_id').html('');
                $.ajax({
                    url: '{{ route('getInsuranceClasses') }}?company_id=' + company_id,
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        if (res.length > 0) { // all was ok
                            var insurance_classes_text = "{{ trans('admin.reception.Choose Insurance Classes') }}"

                            $('#class_id').html('<option value="" disabled> '+ insurance_classes_text +' </option>');
                            $.each(res, function (key, value) {
                                $('#class_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        } else {
                            $('#class_id').html('<option value="" disabled selected>{{ trans('admin.no_data') }} </option>');
                        }
                    }
                });
            });


            $(document).on('submit', '#add_patient_form', function (event) {
                event.preventDefault();
                var formData = new FormData(this);
                formData.append('_method', 'POST');
                $.ajax({
                    url: "{{ route('create-patient') }}",
                    method: "POST",
                    processData: false, // important
                    contentType: false, // important
                    data: formData,
                    dataType: 'JSON',
                    beforeSend: function () {
                        $(".overlay").toggleClass('d-none');
                    },
                    success: function (data) {
                        swalNormal(data);
                        $(".overlay").toggleClass('d-none');
                        window.location.replace(data.route);
                    },
                    error: function (data) {
                        sweetAlertErrorResponse(data);
                    }
                })
            });


            function swalNormal(data) {
                new Noty({
                    type: data.status === 1 ? 'success' : 'error',
                    layout: 'topRight',
                    text: data.message,
                    timeout: 2000,
                    killer: true
                }).show();
            }

            function sweetAlertErrorResponse(data) {
                var span = document.createElement("span");
                span.innerHTML = "";
                $.each(data.responseJSON.errors, function (index, value) {
                    span.innerHTML += value + "<br>";
                });
                new Noty({
                    type: 'error',
                    layout: 'topRight',
                    text: span,
                    timeout: 2000,
                    killer: true
                }).show();
            }

        });
    </script>
@endsection
