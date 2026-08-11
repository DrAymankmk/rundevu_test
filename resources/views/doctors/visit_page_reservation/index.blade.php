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
                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i><a
                                    href="{{ route('add-medicine',$reservation->id) }}">@lang('admin.new_revelation') </a>
                            </li>
                            <li class="breadcrumb-item active">@lang('admin.visit_page')</li>
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
                                       href="{{ route('patient-services',[$reservation->user_id, 2]) }}">@lang('admin.consult_doctor')</a>
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
                                    <input type="text" class="form-control" readonly name="heat"
                                           value="{{ $reservation->vital_signs->heat ??null }}">
                                </div>

                                <label class="col-form-label col-md-2">@lang('admin.weight')</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly name="weight"
                                           value="{{ $reservation->vital_signs->weight ??null }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.pulse')</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly name="pulse"
                                           value="{{ $reservation->vital_signs->pulse ??null }}">
                                </div>

                                <label class="col-form-label col-md-2">@lang('admin.height')</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly name="height"
                                           value="{{ $reservation->vital_signs->height ??null }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.breathing')</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly name="breathing"
                                           value="{{ $reservation->vital_signs->breathing ??null }}">
                                </div>


                                <label class="col-form-label col-md-2">
                                    @lang('admin.pregnant')
                                    <input type="checkbox" id="pregnant" readonly name="pregnant" value="1"
                                           @if($reservation->vital_signs->pregnant ?? 0 == 1 ) checked @endif >
                                </label>
                            </div>


                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.blood_pressure')</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly name="blood_pressure" required
                                           value="{{ $reservation->vital_signs->blood_pressure ??null }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.sports_habits')</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" readonly name="sports_habits" required
                                           value="{{ $reservation->vital_signs->sports_habits ??null }}">
                                </div>
                            </div>
                            <hr class="col-form">
                            <hr class="col-form">

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.symptoms')</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" readonly
                                              name="symptoms"> {{ $reservation->symptoms ??null }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.Diagnosis')</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" readonly
                                              name="diagnosis"> {{ $reservation->diagnosis ??null }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.clinical_examination')</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" readonly
                                              name="clinical_examination"> {{ $reservation->clinical_examination ??null }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.recommendations')</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" readonly
                                              name="recommendations"> {{ $reservation->recommendations ??null }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.notes')</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" readonly
                                              name="notes"> {{ $reservation->notes ??null }}</textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


                <div class="col-sm-6">
                    <div class="card-box">
                        <h4 class="card-title">@lang('admin.schedule_consultation')</h4>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">@lang('admin.date')</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="schedule_consultation_date"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       value="{{$reservation->schedule_consultation_date ?? null}}" id="datepicker">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">@lang('admin.time')</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" readonly name="schedule_consultation_date"
                                       value="{{$reservation->schedule_consultation_time ?? null}}" id="datepicker">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <h4 class="card-title text-center">@lang('admin.doctor.Drug lists')</h4>
                        @if(count($reservation->reservation_drugs) > 0)
                            @foreach($reservation->reservation_drugs as $section)
                                <div class="col-sm-6 col-lg-6">
                                    <div class="card invoices-grid-card w-100">
                                        <div class="card-middle">
                                            <h2 class="card-middle-avatar">
                                                <a><img class="avatar avatar-sm me-2 avatar-img rounded-circle"
                                                        src="/media/icons/Medical_prescription_menu.png" alt="Doctor Image">
                                                    @if($lang == 'en')
                                                        {{ $section->drugs->name_en }}
                                                    @else
                                                        {{ $section->drugs->name_ar }}
                                                    @endif
                                                    &nbsp;       &nbsp;
                                                   @lang('admin.medicine_type.'.$section->drugs->medicine_type)
                                                </a>
                                            </h2>
                                        </div>
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <h6 class="mb-3">@lang('admin.Dosage')</h6>
                                                    <h6 class="mb-3">@lang('admin.repetition')</h6>
                                                    <h6 class="mb-3">@lang('admin.nums_days')</h6>
                                                    <h6 class="mb-3">@lang('admin.notes')</h6>
                                                </div>
                                                <div class="col-auto">
                                                    <h6 class="mb-3">{{ $section->drugs->concentration_ratio }} @lang('admin.doctor.'.$section->drugs->concentration_type)</h6>
                                                    <h6 class="mb-3">{{ $section->repetition }} @lang('admin.time_per_day')</h6>
                                                    <h6 class="mb-3">{{$section->nums_days}} @lang('admin.day')</h6>
                                                    <h6 class="mb-3">{{$section->notes}}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="row">

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">@lang('admin.patient_debts')</h4>
                            </div>
                            <div class="card-body p-0 table-dash">
                                <div class="table-responsive">
                                    <table class="table border-0 custom-table comman-table datatable mb-0">
                                        <thead>
                                        <tr>
                                            <th>@lang('admin.patient_name')</th>
                                            <th> @lang('admin.patient_type') </th>
                                            <th> @lang('admin.price') </th>
                                            <th>@lang('admin.debts')</th>
                                            <th> @lang('admin.Day') </th>
                                            <th> @lang('admin.Date') </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['patient_invoices'] as $index=>$invoice)
                                            <tr>
                                                <td>{{$invoice->user->name}}</td>
                                                <td>@lang('admin.cash')</td>
                                                <td>{{ $invoice->price }}</td>
                                                <td>---</td>
                                                <td>{{ \Carbon\Carbon::parse($invoice->created_at)->format('l') ?? null }}</td>
                                                <td>{{ date('Y-m-d',strtotime($invoice->created_at)) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>


            @if(count($data['patient_analysis']) > 0)
            <div class="row">
                <div class="col-md-12">
                    <strong>@lang('admin.analysis')</strong>
                    <div class="table-responsive">
                        <table class="table border-0 custom-table comman-table  mb-0">
                            <thead>
                            <tr>
                                <th>@lang('admin.service_name')</th>
                                <th>@lang('admin.patient_name')</th>
                                <th> @lang('admin.patient_type') </th>
                                <th> @lang('admin.insurance') </th>
                                <th> @lang('admin.price')  </th>
                                <th> @lang('admin.cancel') </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data['patient_analysis'] as $analysis)
                                <tr>
                                    <td>{{$lang == 'en' ? $analysis->services->name_en : $analysis->services->name_ar}}</td>
                                    <td>{{$analysis->user->name}}</td>
                                    <td>@lang('admin.cash')</td>
                                    <td>---</td>
                                    <td>{{ $analysis->price }} @lang('admin.r.s')</td>

                                    <td class="text-end">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                           data-bs-target="#delete_service{{$analysis->id}}"><i
                                                class="fa fa-trash-alt m-r-5"></i> @lang('admin.delete')
                                        </a>

                                        <div id="delete_service{{$analysis->id}}" class="modal fade delete-modal"
                                             role="dialog">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form class="needs-validation" novalidate=""
                                                          action="{{route('destroyPatientService',$analysis->id) }}"
                                                          method="POST">
                                                        {{ method_field('delete') }}
                                                        {{ csrf_field() }}
                                                        <div class="modal-body text-center">
                                                            <img src="/assets/img/sent.png" alt="" width="50"
                                                                 height="46">
                                                            <h3>@lang('admin.confirm_delete')</h3>
                                                            <div class="m-t-20"><a href="#" class="btn btn-white"
                                                                                   data-bs-dismiss="modal">@lang('admin.close')</a>
                                                                <button type="submit"
                                                                        class="btn btn-danger">@lang('admin.delete')</button>
                                                            </div>
                                                        </div>
                                                    </form>
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
                <br>

            </div>
            @endif
            @if(count($data['patient_rays']) > 0)
            <div class="row">
                <div class="col-md-12">
                    <strong>@lang('admin.rays')</strong>
                    <div class="table-responsive">
                        <table class="table border-0 custom-table comman-table  mb-0">
                            <thead>
                            <tr>
                                <th>@lang('admin.service_name')</th>
                                <th>@lang('admin.patient_name')</th>
                                <th> @lang('admin.patient_type') </th>
                                <th> @lang('admin.insurance') </th>
                                <th> @lang('admin.price') </th>
                                <th> @lang('admin.cancel') </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data['patient_rays'] as $rays)
                                <tr>
                                    <td>{{$lang == 'en' ? $rays->services->name_en : $rays->services->name_ar}}</td>
                                    <td>{{$rays->user->name}}</td>
                                    <td>@lang('admin.cash')</td>
                                    <td>---</td>
                                    <td>{{ $rays->price }} @lang('admin.r.s')</td>

                                    <td class="text-end">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                           data-bs-target="#delete_service{{$rays->id}}"><i
                                                class="fa fa-trash-alt m-r-5"></i> @lang('admin.delete')
                                        </a>

                                        <div id="delete_service{{$rays->id}}" class="modal fade delete-modal"
                                             role="dialog">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form class="needs-validation" novalidate=""
                                                          action="{{route('destroyPatientService',$rays->id) }}"
                                                          method="POST">
                                                        {{ method_field('delete') }}
                                                        {{ csrf_field() }}
                                                        <div class="modal-body text-center">
                                                            <img src="/assets/img/sent.png" alt="" width="50"
                                                                 height="46">
                                                            <h3>@lang('admin.confirm_delete')</h3>
                                                            <div class="m-t-20"><a href="#" class="btn btn-white"
                                                                                   data-bs-dismiss="modal">@lang('admin.close')</a>
                                                                <button type="submit"
                                                                        class="btn btn-danger">@lang('admin.delete')</button>
                                                            </div>
                                                        </div>
                                                    </form>
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
                <br>

            </div>
            @endif

            @if(count($data['patient_services']) > 0)
            <div class="row">

                <div class="col-md-12">
                    <strong>@lang('admin.service')</strong>
                    <div class="table-responsive">
                        <table class="table border-0 custom-table comman-table  mb-0">
                            <thead>
                            <tr>
                                <th>@lang('admin.service_name')</th>
                                <th>@lang('admin.patient_name')</th>
                                <th> @lang('admin.patient_type') </th>
                                <th> @lang('admin.insurance') </th>
                                <th> @lang('admin.price') </th>
                                <th> @lang('admin.discount') </th>
                                <th> @lang('admin.cancel') </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data['patient_services'] as $service)
                                <tr>
                                    <td>{{$lang == 'en' ? $service->services->name_en : $service->services->name_ar}}</td>
                                    <td>{{$service->user->name}}</td>
                                    <td>@lang('admin.cash')</td>
                                    <td>---</td>
                                    <td>{{ $service->price }} @lang('admin.r.s')</td>
                                    <td>{{ $service->discount ?? 0 }}</td>

                                    <td class="text-end">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                           data-bs-target="#delete_service{{$service->id}}"><i
                                                class="fa fa-trash-alt m-r-5"></i> @lang('admin.delete')
                                        </a>

                                        <div id="delete_service{{$service->id}}" class="modal fade delete-modal"
                                             role="dialog">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form class="needs-validation" novalidate=""
                                                          action="{{route('destroyPatientService',$service->id) }}"
                                                          method="POST">
                                                        {{ method_field('delete') }}
                                                        {{ csrf_field() }}
                                                        <div class="modal-body text-center">
                                                            <img src="/assets/img/sent.png" alt="" width="50"
                                                                 height="46">
                                                            <h3>@lang('admin.confirm_delete')</h3>
                                                            <div class="m-t-20"><a href="#" class="btn btn-white"
                                                                                   data-bs-dismiss="modal">@lang('admin.close')</a>
                                                                <button type="submit"
                                                                        class="btn btn-danger">@lang('admin.delete')</button>
                                                            </div>
                                                        </div>
                                                    </form>
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
                <br>

            </div>
            @endif
        </div>
    </div>
@endsection
