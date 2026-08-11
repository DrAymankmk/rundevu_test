@extends('includes_admin.mainlayout')
@section('content')

    <style>
        .col-form-label {
            font-family: 'Tajawal', serif;
            font-weight: bold;
            background-color: #2E40AE;
            color: #fff;
        }

        .col-form {
            font-family: 'Tajawal', serif;
            font-weight: bold;
            background-color: #000;
            color: #000;
            border-color: #000;
        }
    </style>

    <div class="page-wrapper">
        <div class="content">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.dashboard') }}">@lang('admin.dashboard') </a></li>
                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                            <li class="breadcrumb-item active">@lang('admin.new_revelation')</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-sm-12">
                    <div class="card-box">
                        <div class="settings-menu-links">
                            <ul class="nav nav-tabs menu-tabs">
                                <li class="nav-item ">
                                    <a class="nav-link me-2"
                                       href="{{ route('patient-services',[$reservation->id, 3]) }}">@lang('admin.treatment_plan')</a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link me-2"
                                       href="{{ route('patient-services',[$reservation->id, 1]) }}">@lang('admin.patient_consent')</a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link me-2"
                                       href="{{route('patient-report', [$reservation->user_id,2])}}">@lang('admin.medical_reports')</a>
                                </li>
{{--                                <li class="nav-item active">--}}
{{--                                    <a class="nav-link me-2"--}}
{{--                                       href="{{ route('patient-services',[$reservation->id, 1]) }}">@lang('admin.status_conversion')</a>--}}
{{--                                </li>--}}
                                <li class="nav-item ">
                                    <a class="nav-link me-2"
                                       href="{{ route('patient-services',[$reservation->id, 2]) }}">@lang('admin.consult_doctor')</a>
                                </li>

                                <li class="nav-item ">
                                    <a class="nav-link me-2"
                                       href="{{ route('patient-services',[$reservation->id, 4]) }}">@lang('admin.attachment')</a>
                                </li>

                                <li class="nav-item ">
                                    <a class="nav-link me-2"
                                       href="{{ route('patient-service-file',[$reservation->id, 1]) }}">@lang('admin.analysis')</a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link me-2"
                                       href="{{ route('patient-service-file',[$reservation->id, 2]) }}">@lang('admin.rays')</a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link me-2"
                                       href="{{ route('patient-service-file',[$reservation->id, 3]) }}">@lang('admin.service')</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link me-2" href="{{ route('patient-sick-leave', $reservation->id) }}">@lang('admin.sick_leave')</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link me-2" href="{{ route('companion-sick-leave', $reservation->id) }}">@lang('admin.leave_companion')</a>
                                </li>

                                <li class="nav-item ">
                                    <a class="nav-link me-2"
                                       href="{{ route('visit-page-reservation',$reservation->id) }}">@lang('admin.visit_page')</a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="card-box">
                        <h4 class="card-title">@lang('admin.patient_data')</h4>
                        <form action="{{ route('create-vital-signs', $reservation->id) }}" method="post">
                            @csrf

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.patient_name')</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly
                                           value="{{ $reservation->user->name ??null }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.patient_file')</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly
                                           value="{{ $reservation->user->file_number ??null }}">
                                </div>
                            </div>

                            <hr class="col-form">
                            <hr class="col-form">


                            <h4 class="card-title">@lang('admin.vital_signs')</h4>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.heat')</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="heat"
                                           value="{{ $reservation->vital_signs->heat ??null }}">
                                </div>

                                <label class="col-form-label col-md-2">@lang('admin.weight')</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="weight"
                                           value="{{ $reservation->vital_signs->weight ??null }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.pulse')</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="pulse"
                                           value="{{ $reservation->vital_signs->pulse ??null }}">
                                </div>

                                <label class="col-form-label col-md-2">@lang('admin.height')</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="height"
                                           value="{{ $reservation->vital_signs->height ??null }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.breathing')</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="breathing"
                                           value="{{ $reservation->vital_signs->breathing ??null }}">
                                </div>


                                <label class="col-form-label col-md-2">
                                    @lang('admin.pregnant')
                                    <input type="checkbox" id="pregnant" name="pregnant" value="1"
                                           @if($reservation->vital_signs->pregnant ?? 0 == 1 ) checked @endif >
                                </label>
                            </div>


                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.blood_pressure')</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="blood_pressure" required
                                           value="{{ $reservation->vital_signs->blood_pressure ??null }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.sports_habits')</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="sports_habits" required
                                           value="{{ $reservation->vital_signs->sports_habits ??null }}">
                                </div>
                            </div>
                            <hr class="col-form">
                            <hr class="col-form">

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.symptoms')</label>
                                <div class="col-md-10">
                                    <textarea class="form-control"
                                              name="symptoms"> {{ $reservation->symptoms ??null }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.Diagnosis')</label>
                                <div class="col-md-10">
                                    <textarea class="form-control"
                                              name="diagnosis"> {{ $reservation->diagnosis ??null }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.clinical_examination')</label>
                                <div class="col-md-10">
                                    <textarea class="form-control"
                                              name="clinical_examination"> {{ $reservation->clinical_examination ??null }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.recommendations')</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="recommendations"> {{ $reservation->recommendations ??null }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.notes')</label>
                                <div class="col-md-10">
                                    <textarea class="form-control"
                                              name="notes"> {{ $reservation->notes ??null }}</textarea>
                                </div>
                            </div>

                            <div class="form-group text-center">
                                <button class="btn btn-primary" type="submit">@lang('admin.save')</button>
                            </div>
                        </form>
                    </div>
                </div>


                <div class="col-sm-6">
                    <div class="card-box">
                        <h4 class="card-title">@lang('admin.schedule_consultation')</h4>
                        <form action="{{ route('create_schedule_consultation', $reservation->id) }}" method="post">
                            @csrf
                            <input type="hidden" name="doctor_id" value="{{$reservation->doctor_id}}">
                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.date')</label>
                                <div class="col-md-4">
                                    <input type="date" class="form-control" name="schedule_consultation_date" min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ old('date') }}" id="datepicker">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div id="availableTimes"></div>
                            </div>

                            <div class="form-group text-center" id="schedule_consultation" style="display: none">
                                <button class="btn btn-primary"
                                        type="submit">@lang('admin.schedule_consultation')</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{--                <div class="col-sm-6">--}}
                {{--                    <div class="card-box">--}}
                {{--                        <div class="settings-menu-links">--}}
                {{--                            <ul class="nav nav-tabs menu-tabs">--}}
                {{--                                <li class="nav-item active">--}}
                {{--                                    <a class="nav-link me-2"--}}
                {{--                                       href="{{ route('patient-service-file',[$reservation->user_id, 1]) }}">@lang('admin.analysis')</a>--}}
                {{--                                </li>--}}
                {{--                                <li class="nav-item active">--}}
                {{--                                    <a class="nav-link me-2"--}}
                {{--                                       href="{{ route('patient-service-file',[$reservation->user_id, 2]) }}">@lang('admin.rays')</a>--}}
                {{--                                </li>--}}
                {{--                                <li class="nav-item active">--}}
                {{--                                    <a class="nav-link me-2"--}}
                {{--                                       href="{{ route('patient-service-file',[$reservation->user_id, 3]) }}">@lang('admin.service')</a>--}}
                {{--                                </li>--}}
                {{--                                <li class="nav-item active">--}}
                {{--                                    <a class="nav-link me-2"--}}
                {{--                                       href="{{ route('companion-sick-leave', $reservation->user_id) }}">@lang('admin.leave_companion')</a>--}}
                {{--                                </li>--}}
                {{--                                <li class="nav-item active">--}}
                {{--                                    <a class="nav-link me-2" href="#">@lang('admin.insurance')</a>--}}
                {{--                                </li>--}}
                {{--                                <li class="nav-item active">--}}
                {{--                                    <a class="nav-link me-2"--}}
                {{--                                       href="{{ route('patient-invoices',$reservation->user_id) }}">@lang('admin.Bills')</a>--}}
                {{--                                </li>--}}
                {{--                                <li class="nav-item active">--}}
                {{--                                    <a class="nav-link me-2"--}}
                {{--                                       href="{{ route('patient-sick-leave', $reservation->user_id) }}">@lang('admin.sick_leave')</a>--}}
                {{--                                </li>--}}
                {{--                                <li class="nav-item active">--}}
                {{--                                    <a class="nav-link me-2"--}}
                {{--                                       href="{{ route('patient-services',[$reservation->user_id, 4]) }}">@lang('admin.attachment')</a>--}}
                {{--                                </li>--}}
                {{--                            </ul>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}

            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table show-entire">
                        <div class="card-body">
                            <!-- Table Header -->
                            <div class="page-table-header mb-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="doctor-table-blk">
                                            <h3>@lang('admin.add_medicine')</h3>
                                            <div class="doctor-search-blk">
                                                <div class="top-nav-search table-search-blk">
                                                    <form>
                                                        <input type="text" class="form-control"
                                                               placeholder="@lang('admin.search_here')">
                                                        <a class="btn"><img src="/assets/img/icons/search-normal.svg"
                                                                            alt=""></a>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="javascript:;" class=" me-2"><img
                                                src="/assets/img/icons/pdf-icon-01.svg" alt=""></a>
                                        <a href="javascript:;" class=" me-2"><img
                                                src="/assets/img/icons/pdf-icon-02.svg" alt=""></a>
                                        <a href="javascript:;" class=" me-2"><img
                                                src="/assets/img/icons/pdf-icon-03.svg" alt=""></a>
                                        <a href="javascript:;"><img src="/assets/img/icons/pdf-icon-04.svg" alt=""></a>
                                    </div>
                                </div>
                            </div>
                            <!-- /Table Header -->
                            <form method="POST" action="{{route('create-medicine', $reservation->id)}}"
                                  class="invoices-form">
                                @csrf
                                <input type="hidden" name="lang" id="lang">
                                <div class="invoice-add-table">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-nowrap  mb-0 no-footer add-table-items">
                                            <thead>
                                            <tr>
                                                <th>@lang('admin.medicine_name')</th>
                                                <th>@lang('admin.repetition')</th>
                                                <th>@lang('admin.nums_days')</th>
                                                <th>@lang('admin.notes')</th>
                                                <th>@lang('admin.action')</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr class="add-row">
                                                <td>
                                                    <select class="select" name="drug_id[]" required>
                                                        @foreach($data['drugs'] as $drug)
                                                            @if($lang == 'en')
                                                                <option
                                                                    value="{{ $drug->id }}">{{ $drug->name_en }} {{ $drug->concentration_ratio }} @lang('admin.doctor.'.$drug->concentration_type)   @lang('admin.medicine_type.'.$drug->medicine_type)</option>
                                                            @else
                                                                <option
                                                                    value="{{ $drug->id }}">{{ $drug->name_ar }} {{ $drug->concentration_ratio }} @lang('admin.doctor.'.$drug->concentration_type)   @lang('admin.medicine_type.'.$drug->medicine_type)</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" name="repetition[]" class="form-control"
                                                           placeholder="@lang('admin.time_per_day')" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="nums_days[]" class="form-control"
                                                           placeholder="@lang('admin.day')" required>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="notes[]"
                                                           placeholder="@lang('admin.notes')">
                                                </td>

                                                <td class="add-remove text-end">
                                                    @if(count($data['drugs']) > 0)
                                                        <a href="javascript:void(0);" class="add-btn me-2"><i
                                                                class="fas fa-plus-circle"></i></a>
                                                    @endif
                                                    {{--                                                <a href="javascript:void(0);" class="remove-btn"><i--}}
                                                    {{--                                                        class="fa fa-trash-alt"></i></a>--}}
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="form-group text-center mb-0">
                                    <button class="btn btn-primary"
                                            type="submit">@lang('admin.dosing_confirmation')</button>
                                </div>
                                <br>
                            </form>

                        </div>

                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-12">

                    <div class="card card-table show-entire">
                        <div class="card-body">

                            <!-- Table Header -->
                            <div class="page-table-header mb-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="doctor-table-blk">
                                            <h3>@lang('admin.analysis')</h3>
                                            <div class="doctor-search-blk">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Table Header -->
                            <form method="POST" action="{{route('create-patient-service-file', $reservation->id)}}"
                                  class="invoices-form">
                                @csrf
                                <input type="hidden" name="lang" id="lang">
                                <input type="hidden" name="type" value="1">
                                <div class="invoice-add-table">
                                    <div class="table-responsive">
                                        <table
                                            class="table table-striped table-nowrap  mb-0 no-footer add-table-items-analysis">
                                            <thead>
                                            <tr>
                                                <th>@lang('admin.analysis')</th>
                                                <th>@lang('admin.notes')</th>
                                                <th>@lang('admin.action')</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr class="add-row-analysis">
                                                <td>
                                                    <select class="form-control select" name="service_id[]" required>
                                                        <option value="">@lang('admin.select')</option>
                                                        @foreach($data['analysis'] as $service)
                                                            <option
                                                                value="{{ $service->id }}">{{ $service->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="notes[]"
                                                           placeholder="@lang('admin.notes')">
                                                </td>

                                                <td class="add-remove text-end">
                                                    @if(count($data['analysis']) > 0)
                                                        <a href="javascript:void(0);" class="add-btn-analysis me-2"><i
                                                                class="fas fa-plus-circle"></i></a>
                                                    @endif
                                                    {{--                                                <a href="javascript:void(0);" class="remove-btn"><i--}}
                                                    {{--                                                        class="fa fa-trash-alt"></i></a>--}}
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="form-group text-center mb-0">
                                    <button class="btn btn-primary"
                                            type="submit">@lang('admin.send')</button>
                                </div>
                                <br>
                            </form>

                        </div>

                    </div>
                </div>


                {{--                <div class="col-md-12">--}}
                {{--                    <div class="table-responsive">--}}
                {{--                        <table class="table border-0 custom-table comman-table datatable mb-0">--}}
                {{--                            <thead>--}}
                {{--                            <tr>--}}
                {{--                                <th>@lang('admin.service_name')</th>--}}
                {{--                                <th>@lang('admin.patient_name')</th>--}}
                {{--                                <th> @lang('admin.patient_type') </th>--}}
                {{--                                <th> @lang('admin.insurance') </th>--}}
                {{--                                <th> @lang('admin.price') </th>--}}
                {{--                                <th> @lang('admin.cancel') </th>--}}
                {{--                            </tr>--}}
                {{--                            </thead>--}}
                {{--                            <tbody>--}}
                {{--                            @foreach($data['patient_analysis'] as $analysis)--}}
                {{--                                <tr>--}}
                {{--                                    <td>{{$lang == 'en' ? $analysis->services->name_en : $analysis->services->name_ar}}</td>--}}
                {{--                                    <td>{{$analysis->user->name}}</td>--}}
                {{--                                    <td>@lang('admin.cash')</td>--}}
                {{--                                    <td>---</td>--}}
                {{--                                    <td>{{ $analysis->price }}</td>--}}

                {{--                                    <td class="text-end">--}}
                {{--                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"--}}
                {{--                                           data-bs-target="#delete_service{{$analysis->id}}"><i--}}
                {{--                                                class="fa fa-trash-alt m-r-5"></i> @lang('admin.delete')--}}
                {{--                                        </a>--}}

                {{--                                        <div id="delete_service{{$analysis->id}}" class="modal fade delete-modal"--}}
                {{--                                             role="dialog">--}}
                {{--                                            <div class="modal-dialog modal-dialog-centered">--}}
                {{--                                                <div class="modal-content">--}}
                {{--                                                    <form class="needs-validation" novalidate=""--}}
                {{--                                                          action="{{route('destroyPatientService',$analysis->id) }}"--}}
                {{--                                                          method="POST">--}}
                {{--                                                        {{ method_field('delete') }}--}}
                {{--                                                        {{ csrf_field() }}--}}
                {{--                                                        <div class="modal-body text-center">--}}
                {{--                                                            <img src="/assets/img/sent.png" alt="" width="50"--}}
                {{--                                                                 height="46">--}}
                {{--                                                            <h3>@lang('admin.confirm_delete')</h3>--}}
                {{--                                                            <div class="m-t-20"><a href="#" class="btn btn-white" data-bs-dismiss="modal">@lang('admin.close')</a>--}}
                {{--                                                                <button type="submit"--}}
                {{--                                                                        class="btn btn-danger">@lang('admin.delete')</button>--}}
                {{--                                                            </div>--}}
                {{--                                                        </div>--}}
                {{--                                                    </form>--}}
                {{--                                                </div>--}}
                {{--                                            </div>--}}
                {{--                                        </div>--}}
                {{--                                    </td>--}}
                {{--                                </tr>--}}
                {{--                            @endforeach--}}

                {{--                            </tbody>--}}
                {{--                        </table>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                {{--                <hr>--}}
                {{--                <br>--}}

            </div>

            <div class="row">
                <div class="col-sm-12">

                    <div class="card card-table show-entire">
                        <div class="card-body">

                            <!-- Table Header -->
                            <div class="page-table-header mb-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="doctor-table-blk">
                                            <h3>@lang('admin.rays')</h3>
                                            <div class="doctor-search-blk">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Table Header -->
                            <form method="POST" action="{{route('create-patient-service-file', $reservation->id)}}"
                                  class="invoices-form">
                                @csrf
                                <input type="hidden" name="lang" id="lang">
                                <input type="hidden" name="type" value="2">
                                <div class="invoice-add-table">
                                    <div class="table-responsive">
                                        <table
                                            class="table table-striped table-nowrap  mb-0 no-footer add-table-items-rays">
                                            <thead>
                                            <tr>
                                                <th>@lang('admin.rays')</th>
                                                <th>@lang('admin.notes')</th>
                                                <th>@lang('admin.action')</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr class="add-row-analysis">
                                                <td>
                                                    <select class="form-control select" name="service_id[]" required>
                                                        <option value="">@lang('admin.select')</option>
                                                        @foreach($data['rays'] as $service)
                                                            <option
                                                                value="{{ $service->id }}">{{ $service->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="notes[]"
                                                           placeholder="@lang('admin.notes')">
                                                </td>

                                                <td class="add-remove text-end">
                                                    @if(count($data['rays']) > 0)
                                                        <a href="javascript:void(0);" class="add-btn-rays me-2"><i
                                                                class="fas fa-plus-circle"></i></a>
                                                    @endif
                                                    {{--                                                <a href="javascript:void(0);" class="remove-btn"><i--}}
                                                    {{--                                                        class="fa fa-trash-alt"></i></a>--}}
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="form-group text-center mb-0">
                                    <button class="btn btn-primary"
                                            type="submit">@lang('admin.send')</button>
                                </div>
                                <br>
                            </form>

                        </div>

                    </div>
                </div>


                {{--                <div class="col-md-12">--}}
                {{--                    <div class="table-responsive">--}}
                {{--                        <table class="table border-0 custom-table comman-table datatable mb-0">--}}
                {{--                            <thead>--}}
                {{--                            <tr>--}}
                {{--                                <th>@lang('admin.service_name')</th>--}}
                {{--                                <th>@lang('admin.patient_name')</th>--}}
                {{--                                <th> @lang('admin.patient_type') </th>--}}
                {{--                                <th> @lang('admin.insurance') </th>--}}
                {{--                                <th> @lang('admin.price') </th>--}}
                {{--                                <th> @lang('admin.cancel') </th>--}}
                {{--                            </tr>--}}
                {{--                            </thead>--}}
                {{--                            <tbody>--}}
                {{--                            @foreach($data['patient_rays'] as $rays)--}}
                {{--                                <tr>--}}
                {{--                                    <td>{{$lang == 'en' ? $rays->services->name_en : $rays->services->name_ar}}</td>--}}
                {{--                                    <td>{{$rays->user->name}}</td>--}}
                {{--                                    <td>@lang('admin.cash')</td>--}}
                {{--                                    <td>---</td>--}}
                {{--                                    <td>{{ $rays->price }}</td>--}}

                {{--                                    <td class="text-end">--}}
                {{--                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"--}}
                {{--                                           data-bs-target="#delete_service{{$rays->id}}"><i--}}
                {{--                                                class="fa fa-trash-alt m-r-5"></i> @lang('admin.delete')--}}
                {{--                                        </a>--}}

                {{--                                        <div id="delete_service{{$rays->id}}" class="modal fade delete-modal"--}}
                {{--                                             role="dialog">--}}
                {{--                                            <div class="modal-dialog modal-dialog-centered">--}}
                {{--                                                <div class="modal-content">--}}
                {{--                                                    <form class="needs-validation" novalidate=""--}}
                {{--                                                          action="{{route('destroyPatientService',$rays->id) }}"--}}
                {{--                                                          method="POST">--}}
                {{--                                                        {{ method_field('delete') }}--}}
                {{--                                                        {{ csrf_field() }}--}}
                {{--                                                        <div class="modal-body text-center">--}}
                {{--                                                            <img src="/assets/img/sent.png" alt="" width="50"--}}
                {{--                                                                 height="46">--}}
                {{--                                                            <h3>@lang('admin.confirm_delete')</h3>--}}
                {{--                                                            <div class="m-t-20"><a href="#" class="btn btn-white" data-bs-dismiss="modal">@lang('admin.close')</a>--}}
                {{--                                                                <button type="submit"--}}
                {{--                                                                        class="btn btn-danger">@lang('admin.delete')</button>--}}
                {{--                                                            </div>--}}
                {{--                                                        </div>--}}
                {{--                                                    </form>--}}
                {{--                                                </div>--}}
                {{--                                            </div>--}}
                {{--                                        </div>--}}
                {{--                                    </td>--}}
                {{--                                </tr>--}}
                {{--                            @endforeach--}}

                {{--                            </tbody>--}}
                {{--                        </table>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                {{--                <hr>--}}
                {{--                <br>--}}

            </div>


            <div class="row">
                <div class="col-sm-12">

                    <div class="card card-table show-entire">
                        <div class="card-body">

                            <!-- Table Header -->
                            <div class="page-table-header mb-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="doctor-table-blk">
                                            <h3>@lang('admin.service')</h3>
                                            <div class="doctor-search-blk">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Table Header -->
                            <form method="POST" action="{{route('create-patient-service-file', $reservation->id)}}"
                                  class="invoices-form">
                                @csrf
                                <input type="hidden" name="lang" id="lang">
                                <input type="hidden" name="type" value="3">
                                <div class="invoice-add-table">
                                    <div class="table-responsive">
                                        <table
                                            class="table table-striped table-nowrap  mb-0 no-footer add-table-items-services">
                                            <thead>
                                            <tr>
                                                <th>@lang('admin.service')</th>
                                                <th>@lang('admin.price')</th>
                                                <th>@lang('admin.discount')</th>
                                                <th>@lang('admin.notes')</th>
                                                <th>@lang('admin.action')</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr class="add-row-services">
                                                <td>
                                                    <select class="form-control select" name="service_id[]" required>
                                                        <option value="">@lang('admin.select')</option>
                                                        @foreach($data['services'] as $service)
                                                            <option
                                                                value="{{ $service->id }}">{{ $service->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>{{ $service->price }} @lang('admin.r.s')</td>
                                                <td>
                                                    <input type="number" class="form-control" name="discount[]"
                                                           placeholder="@lang('admin.discount')">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="notes[]"
                                                           placeholder="@lang('admin.notes')">
                                                </td>

                                                <td class="add-remove text-end">
                                                    @if(count($data['services']) > 0)
                                                        <a href="javascript:void(0);" class="add-btn-services me-2"><i
                                                                class="fas fa-plus-circle"></i></a>
                                                    @endif
                                                    {{--                                                <a href="javascript:void(0);" class="remove-btn"><i--}}
                                                    {{--                                                        class="fa fa-trash-alt"></i></a>--}}
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="form-group text-center mb-0">
                                    <button class="btn btn-primary"
                                            type="submit">@lang('admin.send')</button>
                                </div>
                                <br>
                            </form>

                        </div>

                    </div>
                </div>


                {{--                <div class="col-md-12">--}}
                {{--                    <div class="table-responsive">--}}
                {{--                        <table class="table border-0 custom-table comman-table datatable mb-0">--}}
                {{--                            <thead>--}}
                {{--                            <tr>--}}
                {{--                                <th>@lang('admin.service_name')</th>--}}
                {{--                                <th>@lang('admin.patient_name')</th>--}}
                {{--                                <th> @lang('admin.patient_type') </th>--}}
                {{--                                <th> @lang('admin.insurance') </th>--}}
                {{--                                <th> @lang('admin.price') </th>--}}
                {{--                                <th> @lang('admin.cancel') </th>--}}
                {{--                            </tr>--}}
                {{--                            </thead>--}}
                {{--                            <tbody>--}}
                {{--                            @foreach($data['patient_rays'] as $rays)--}}
                {{--                                <tr>--}}
                {{--                                    <td>{{$lang == 'en' ? $rays->services->name_en : $rays->services->name_ar}}</td>--}}
                {{--                                    <td>{{$rays->user->name}}</td>--}}
                {{--                                    <td>@lang('admin.cash')</td>--}}
                {{--                                    <td>---</td>--}}
                {{--                                    <td>{{ $rays->price }}</td>--}}

                {{--                                    <td class="text-end">--}}
                {{--                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"--}}
                {{--                                           data-bs-target="#delete_service{{$rays->id}}"><i--}}
                {{--                                                class="fa fa-trash-alt m-r-5"></i> @lang('admin.delete')--}}
                {{--                                        </a>--}}

                {{--                                        <div id="delete_service{{$rays->id}}" class="modal fade delete-modal"--}}
                {{--                                             role="dialog">--}}
                {{--                                            <div class="modal-dialog modal-dialog-centered">--}}
                {{--                                                <div class="modal-content">--}}
                {{--                                                    <form class="needs-validation" novalidate=""--}}
                {{--                                                          action="{{route('destroyPatientService',$rays->id) }}"--}}
                {{--                                                          method="POST">--}}
                {{--                                                        {{ method_field('delete') }}--}}
                {{--                                                        {{ csrf_field() }}--}}
                {{--                                                        <div class="modal-body text-center">--}}
                {{--                                                            <img src="/assets/img/sent.png" alt="" width="50"--}}
                {{--                                                                 height="46">--}}
                {{--                                                            <h3>@lang('admin.confirm_delete')</h3>--}}
                {{--                                                            <div class="m-t-20"><a href="#" class="btn btn-white" data-bs-dismiss="modal">@lang('admin.close')</a>--}}
                {{--                                                                <button type="submit"--}}
                {{--                                                                        class="btn btn-danger">@lang('admin.delete')</button>--}}
                {{--                                                            </div>--}}
                {{--                                                        </div>--}}
                {{--                                                    </form>--}}
                {{--                                                </div>--}}
                {{--                                            </div>--}}
                {{--                                        </div>--}}
                {{--                                    </td>--}}
                {{--                                </tr>--}}
                {{--                            @endforeach--}}

                {{--                            </tbody>--}}
                {{--                        </table>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                {{--                <hr>--}}
                {{--                <br>--}}

            </div>

        </div>
    </div>
    <script src="/assets/js/jquery-3.6.1.min.js"></script>
    <script>

        $(document).ready(function ($) {
            $(document).on("click", ".add-btn", function () {

                var experiencecontent = '<tr class="add-row">' +
                    '<td>' +
                    '<select class="form-control select" name="drug_id[]" required>' +
                    @foreach($data['drugs'] as $drug)
                        @if($lang == 'en')
                        '<option value="{{ $drug->id }}">{{ $drug->name_en }} {{ $drug->concentration_ratio }} @lang('admin.doctor.'.$drug->concentration_type)   @lang('admin.medicine_type.'.$drug->medicine_type)</option>' +
                    @else
                        '<option value="{{ $drug->id }}">{{ $drug->name_ar }} {{ $drug->concentration_ratio }} @lang('admin.doctor.'.$drug->concentration_type)   @lang('admin.medicine_type.'.$drug->medicine_type)</option>' +
                    @endif
                        @endforeach
                        '</select>' +
                    '</td>' +
                    '<td>' +
                    '<input type="number" class="form-control" name="repetition[]" placeholder="{{ trans('admin.time_per_day') }}" required>' +
                    '</td>' +
                    '<td>' +
                    '<input type="number" class="form-control" name="nums_days[]" placeholder="{{ trans('admin.day') }}" required>' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" class="form-control" name="notes[]" placeholder="{{ trans('admin.notes') }}">' +
                    '</td>' +
                    '<td class="add-remove text-end">' +
                    ' <a href="javascript:void(0);" class="add-btn me-2"><i class="fas fa-plus-circle"></i></a> ' +
                    '<a href="javascript:void(0);" class="remove-btn"><i class="fa fa-trash-alt"></i></a>' +
                    '</td>' +
                    '</tr>';

                $(".add-table-items").append(experiencecontent);
                return false;
            });


            // add patient analysis

            $(document).on("click", ".add-btn-analysis", function () {
                var experiencecontent = '<tr class="add-row-analysis">' +
                    '<td>' +
                    '<select class="form-control select" name="service_id[]" required>' +
                    '<option value="">{{ trans('admin.select') }}</option>' +
                    @foreach($data['analysis'] as $analysis)
                        '<option value="{{ $analysis->id }}">{{ $analysis->name }}</option>' +
                    @endforeach
                        '</select>' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" class="form-control" name="notes[]" placeholder="{{ trans('admin.notes') }}">' +
                    '</td>' +
                    '<td class="add-remove text-end">' +
                    ' <a href="javascript:void(0);" class="add-btn-analysis me-2"><i class="fas fa-plus-circle"></i></a> ' +
                    '<a href="javascript:void(0);" class="remove-btn"><i class="fa fa-trash-alt"></i></a>' +
                    '</td>' +
                    '</tr>';
                $(".add-table-items-analysis").append(experiencecontent);
                return false;
            });


            $(document).on("click", ".add-btn-rays", function () {
                var experiencecontent = '<tr class="add-row-rays">' +
                    '<td>' +
                    '<select class="form-control select" name="service_id[]" required>' +
                    '<option value="">{{ trans('admin.select') }}</option>' +
                    @foreach($data['rays'] as $ray)
                        '<option value="{{ $ray->id }}">{{ $ray->name }}</option>' +
                    @endforeach
                        '</select>' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" class="form-control" name="notes[]" placeholder="{{ trans('admin.notes') }}">' +
                    '</td>' +
                    '<td class="add-remove text-end">' +
                    ' <a href="javascript:void(0);" class="add-btn-rays me-2"><i class="fas fa-plus-circle"></i></a> ' +
                    '<a href="javascript:void(0);" class="remove-btn"><i class="fa fa-trash-alt"></i></a>' +
                    '</td>' +
                    '</tr>';
                $(".add-table-items-rays").append(experiencecontent);
                return false;
            });


            $(document).on("click", ".add-btn-services", function () {
                var experiencecontent = '<tr class="add-row-services">' +
                    '<td>' +
                    '<select class="form-control select" name="service_id[]" required>' +
                    '<option value="">{{ trans('admin.select') }}</option>' +
                    @foreach($data['rays'] as $ray)
                        '<option value="{{ $ray->id }}">{{ $ray->name }}</option>' +
                    @endforeach
                        '</select>' +
                    '</td>' +
                    '<td>' +
                    '{{$service->price}} {{ trans('admin.r.s') }}' +
                    '</td>' +
                    '<td>' +
                    '<input type="number" class="form-control" name="discount[]" placeholder="{{ trans('admin.discount') }}">' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" class="form-control" name="notes[]" placeholder="{{ trans('admin.notes') }}">' +
                    '</td>' +
                    '<td class="add-remove text-end">' +
                    ' <a href="javascript:void(0);" class="add-btn-services me-2"><i class="fas fa-plus-circle"></i></a> ' +
                    '<a href="javascript:void(0);" class="remove-btn"><i class="fa fa-trash-alt"></i></a>' +
                    '</td>' +
                    '</tr>';
                $(".add-table-items-services").append(experiencecontent);
                return false;
            });
        });


        $('#datepicker').change(function () {
            var selectedDate = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route('get.available.times') }}',
                data: {
                    date: selectedDate
                },
                success: function (data) {
                    // Handle the returned data (available times)

                    if (data === 0) {
                        $('#schedule_consultation').hide();
                        $('#availableTimes').html('<span style="color:#f00;text-align:center;font-weight:bold">{{ trans("admin.no_available_appointment") }}</span>');
                        return true;
                    }

                    // Update your UI with the available times
                    var availableTimesHtml = '';
                    $.each(data, function (index, time) {
                        var available = 'checked';
                        var color = '#06BA99';
                        if (time.status == 1) {
                            available = 'checked';
                            color = '#06BA99';
                        } else {
                            available = 'disabled';
                            color = '#f00';
                        }
                        availableTimesHtml += '<div class="form-check form-check-inline col-md-4" style="background-color: ' + color + ';margin-bottom: 1.125rem ">' +
                            '<input class="form-check-input" type="radio" name="schedule_consultation_time" id="time_' + index + '" value="' + time.time + '" ' + available + '>' +
                            '<label class="form-check-label" for="time_' + index + '">' +
                            time.time +
                            '</label>' +
                            '</div>';
                    });
                    $('#availableTimes').html(availableTimesHtml);
                    $('#schedule_consultation').show();
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });
    </script>
@endsection
