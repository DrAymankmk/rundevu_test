@extends('includes_admin.mainlayout')
@section('content')
    <style>
        .employee-form-card .card-body {
            padding: 30px;
        }

        .employee-form-grid {
            width: 100%;
        }

        .employee-form-grid .form-row {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px 24px;
            margin: 0 0 18px;
        }

        .employee-form-grid .form-row > [class*="col-"] {
            width: 100%;
            max-width: 100%;
            flex: none;
            padding: 0;
            margin-bottom: 0 !important;
        }

        .employee-form-grid .form-row.form-row-full {
            grid-template-columns: 1fr;
        }

        .employee-form-grid label {
            display: block;
            color: #344054;
            font-weight: 700;
            margin-bottom: 8px;
            text-align: inherit;
        }

        .employee-form-grid .form-control,
        .employee-form-grid .input-group-text,
        .employee-form-grid .select2-container .select2-selection--single,
        .employee-form-grid .select2-container .select2-selection--multiple {
            min-height: 46px;
            border-radius: 8px;
        }

        .employee-form-grid .select2-container {
            width: 100% !important;
        }

        .specialties-checkboxes {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 16px;
            background: #fafafa;
        }

        .specialties-checkboxes .form-check {
            padding: 8px 12px;
            border-radius: 6px;
            transition: background 0.15s;
            margin-bottom: 0;
        }

        .specialties-checkboxes .form-check:hover {
            background: #f0f0f0;
        }

        .specialties-checkboxes .form-check-input {
            width: 18px;
            height: 18px;
            margin-top: 2px;
        }

        .specialties-checkboxes .form-check-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 0;
            padding-right: 8px;
        }

        .employee-form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        [dir="ltr"] .employee-form-actions {
            justify-content: flex-start;
        }

        @media (max-width: 767.98px) {
            .employee-form-card .card-body {
                padding: 18px;
            }

            .employee-form-grid .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <h3>@lang('admin.edit') {{ $data['employee']->name ?? null }}</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i
                                            data-feather="home"> </i> @lang('admin.dashboard') </a></li>
                                <li class="breadcrumb-item"><a
                                        href="{{route('department-employees', $data['employee']->app_type)}}"><i
                                            data-feather="eye"> </i> {{ $data['employee']->name }} </a></li>

                            </ol>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card employee-form-card">
                        <div class="card-body">
                            <form class="needs-validation" novalidate=""
                                  action="{{route('update-department-employee', $data['employee']->id)}}"
                                  method="POST" enctype="multipart/form-data">
                                {{ method_field('POST') }}
                                {{ csrf_field() }}
                                <div class="modal-body employee-form-grid">
                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom01">@lang('admin.name')</label>
                                            <input class="form-control" id="validationCustom01" type="text"
                                                   placeholder="@lang('admin.name')"
                                                   value="{{ $data['employee']->name }}" name="name"
                                                   required="">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom01">@lang('admin.mobile_number')</label>
                                            <label for="validationCustom02"> </label>
                                            <input class="form-control" id="validationCustom02" type="number"
                                                   placeholder="@lang('admin.mobile_number')"
                                                   value="{{ $data['employee']->phone }}" name="phone"
                                                   required="">

                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustomUsername">@lang('admin.email')</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text"
                                                                                       id="inputGroupPrepend">@</span>
                                                </div>
                                                <input class="form-control" id="validationCustomUsername" type="email"
                                                       placeholder="@lang('admin.email')"
                                                       value="{{ $data['employee']->email }}"
                                                       name="email" aria-describedby="inputGroupPrepend"
                                                       required="">
                                                <div class="invalid-feedback">@lang('admin.enter') @lang('admin.email')
                                                    .
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="gender">@lang('admin.gender')</label>
                                            <select id="gender" class="form-control"
                                                    name="gender" required>
                                                <option value="1"
                                                        @if($data['employee']->gender == 1)  selected @endif>@lang('admin.male')</option>
                                                <option value="2"
                                                        @if($data['employee']->gender == 2)  selected @endif>@lang('admin.female')</option>
                                            </select>
                                            <div class="invalid-feedback">{{ $errors->first('gender') }}</div>
                                        </div>



                                        <div class="col-md-6 mb-3" style="display: none">
                                            <label for="validationCustom03">@lang('admin.password')</label>
                                            <input class="form-control" id="validationCustom03" type="password"
                                                   placeholder="@lang('admin.password')"
                                                   name="password">
                                            <div class="invalid-feedback">@lang('admin.enter') @lang('admin.password')
                                                .
                                            </div>
                                        </div>
                                    </div>


                                    @if($data['employee']->app_type == 3)
                                        <div class="form-row">
                                            <div class="col-md-6 mb-3">
                                                <label for="degree_id">@lang('admin.doctor_degrees')</label>
                                                <select id="degree_id" class="form-control"
                                                        name="degree_id" required>
                                                    <option value="">@lang('admin.select')</option>
                                                    @foreach( $data['degree'] as $degree_item)
                                                        <option value="{{$degree_item->id}}"
                                                                @if($data['employee']->degree_id == $degree_item->id)  selected @endif>{{$degree_item->name}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">{{ $errors->first('degree_id') }}</div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="specialist_id">@lang('admin.specialization')</label>
                                                <select id="specialist_id"
                                                        class="js-example-placeholder-multiple form-control"
                                                        name="specialist_id">
                                                    <option value="">@lang('admin.select')</option>
                                                    @foreach($data['specializations'] as $specialist)
                                                        <option value="{{ $specialist->id }}" {{ (!in_array($specialist->id,$data['specialty_ids'])) ? '' : 'selected'}}>{{ app()->getLocale() == 'en' ? $specialist->name_en : $specialist->name_ar }}</option>
                                                    @endforeach
                                                </select>
                                                <div
                                                    class="invalid-feedback">{{ $errors->first('specialist_ids') }}</div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label
                                                    for="sub_specialist_ids">@lang('admin.sub_specialization')</label>
                                                <select id="sub_specialist_ids"
                                                        class="js-example-placeholder-multiple form-control"
                                                        name="sub_specialist_ids[]" multiple="multiple" required>
                                                    @foreach($data['sub_specializations'] as $specialist)
                                                        <option
                                                            value="{{ $specialist->id }}" {{ (!in_array($specialist->id,$data['sub_specialty_ids'])) ? '' : 'selected'}}>{{ app()->getLocale() == 'en' ? $specialist->name_en : $specialist->name_ar }}</option>
                                                    @endforeach
{{--                                                    <option value="">@lang('admin.select_specialist')</option>--}}
                                                </select>
                                                <div
                                                    class="invalid-feedback">{{ $errors->first('sub_specialist_ids') }}</div>
                                            </div>


                                            <div class="col-md-6 mb-3">
                                                <label for="consultation_duration">@lang('admin.Consultation duration in minutes')</label>
                                                <input class="form-control" id="consultation_duration" type="number"
                                                       placeholder="@lang('admin.Consultation duration in minutes')" value="{{ $doctor_appointments->consultation_duration ?? 0 }}"
                                                       name="consultation_duration"
                                                       required="">
                                                <div class="invalid-feedback">@lang('admin.enter') @lang('admin.Consultation duration in minutes').
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3" style="display: none">
                                                <label for="appointments_online">@lang('admin.Percentage of online patient')</label>
                                                <input class="form-control" id="appointments_online" type="number"
                                                       placeholder="@lang('admin.Percentage of offline patient')" value="{{ $doctor_appointments->appointments_online ?? 0 }}"
                                                       name="appointments_online"
                                                >
                                                <div class="invalid-feedback">@lang('admin.enter') @lang('admin.Percentage of offline patient').
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3" style="display: none">
                                                <label for="appointments_reception">@lang('admin.Percentage of offline patient')</label>
                                                <input class="form-control" id="appointments_reception" type="number"
                                                       placeholder="@lang('admin.Percentage of offline patient')" value="{{ $doctor_appointments->appointments_reception ?? 0 }}"
                                                       name="appointments_reception"
                                                >
                                                <div class="invalid-feedback">@lang('admin.enter') @lang('admin.Percentage of offline patient').
                                                </div>
                                            </div>

                                        </div>
                                    @endif


{{--                                    @if($data['employee']->app_type != 3)--}}

{{--                                        <div class="form-row form-row-full">--}}
{{--                                            <div class="col-md-12 mb-3">--}}
{{--                                                <label>@lang('admin.specialization')</label>--}}
{{--                                                <div class="specialties-checkboxes">--}}
{{--                                                    <div class="row">--}}
{{--                                                        @php--}}
{{--                                                            $emp_specialty_ids = $data['employee']->specialties->pluck('specialty_id')->toArray();--}}
{{--                                                        @endphp--}}
{{--                                                        @foreach($data['specializations'] as $specialist)--}}
{{--                                                            <div class="col-md-4 col-lg-3 mb-2">--}}
{{--                                                                <div class="form-check">--}}
{{--                                                                    <input class="form-check-input" type="checkbox"--}}
{{--                                                                           name="specialist_ids[]"--}}
{{--                                                                           value="{{ $specialist->id }}"--}}
{{--                                                                           id="spec_{{ $specialist->id }}"--}}
{{--                                                                           {{ in_array($specialist->id, $emp_specialty_ids) ? 'checked' : '' }}>--}}
{{--                                                                    <label class="form-check-label" for="spec_{{ $specialist->id }}">--}}
{{--                                                                        {{ app()->getLocale() == 'en' ? $specialist->name_en : $specialist->name_ar }}--}}
{{--                                                                    </label>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                        @endforeach--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                <div--}}
{{--                                                    class="invalid-feedback">{{ $errors->first('specialist_ids') }}</div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    @endif--}}

                                    <div class="form-row">
                                        <div class="col-md-1 mb-3">
                                            <div class="form-group">
                                                <img src="{{$data['employee']->image }}"
                                                     class="img-thumbnail image-preview img-80 rounded-circle" alt=""
                                                     style="width:80px;height:80px">
                                            </div>
                                        </div>

                                        <div class="col-md-5 mb-3">
                                            <label for="validationCustom01">@lang('admin.image')</label>
                                            <div class="custom-file">
                                                <input class="custom-file-input" id="validatedCustomFile" type="file"
                                                       name="image">
                                                <label class="custom-file-label"
                                                       for="validatedCustomFile"> @lang('admin.select') @lang('admin.image')
                                                    ...</label>
                                                <div
                                                    class="invalid-feedback">@lang('admin.select') @lang('admin.image')</div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="employee-form-actions">
                                    <button class="btn btn-primary"
                                            type="submit">@lang('admin.edit')
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function () {

            $('#specialist_id').on('change', function () {
                var specialist_id = this.value;
                $('#sub_specialist_ids').html('').trigger('change');
                $.ajax({
                    url: '{{ route('getSubSpecialist') }}?specialist_id=' + specialist_id,
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        if (res.specialists_count > 0) { // all was ok
                            {{--$('#sub_specialist_ids').html('<option value="">@lang('admin.select')</option>');--}}
                            $.each(res.data, function (key, value) {
                                $('#sub_specialist_ids').append('<option value="' + value
                                    .id + '">' + value.name + '</option>');
                            });
                            $('#sub_specialist_ids').trigger('change');
                        } else {
                            $('#sub_specialist_ids').html('').trigger('change');
                        }
                    }
                });
            });
        });
    </script>

    <!-- END PAGE CONTENT WRAPPER -->
@endsection
