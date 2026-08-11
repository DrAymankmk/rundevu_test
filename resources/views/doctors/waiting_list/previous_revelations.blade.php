@extends('includes_admin.mainlayout')
@section('content')
    <style>
        .col-form-label {
            font-family: 'Tajawal', serif;
            font-weight: bold;
            background-color: #000;
            color: #fff;
        }

        .col-form {
            font-family: 'Tajawal', serif;
            font-weight: bold;
            background-color: #000;
            color: #000;
            border-color: #000;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .image-or-pdf {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        .image-or-pdf img,
        .image-or-pdf embed {
            max-width: 100%;
            height: auto;
        }

        .download-link {
            text-align: center;
            margin-top: 10px;
        }
    </style>
    <div class="page-wrapper">
        <div class="content">

            <div class="row">
                <div class="col-sm-7 col-6">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a
                                href="{{ route('admin.dashboard') }}">@lang('admin.dashboard') </a></li>
                        <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('previous-revelations',$user->id)}}">@lang('admin.previous_revelations') </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-box profile-header">
                <div class="row">
                    <div class="col-md-12">
                        <div class="profile-view">
                            <div class="profile-img-wrap">
                                <div class="profile-img">
                                    <a href="#"><img class="avatar" src="{{$user->image ?? null}}" alt=""></a>
                                </div>
                            </div>
                            <div class="profile-basic">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="profile-info-left">
                                            <h3 class="user-name m-t-0 mb-0">{{$user->name ?? null}}</h3>
                                            <small class="text-muted">@lang('admin.patient_nationality'):
                                                @if($lang == 'en')
                                                    {{$user->nationality->name_en ?? null}}
                                                @else
                                                    {{$user->nationality->name_ar ?? null}}
                                                @endif</small>
                                            <div class="staff-id">@lang('admin.ID_Number')
                                                : {{$user->ID_Number ?? null}}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <ul class="personal-info">
                                            <li>
                                                <span class="title">@lang('admin.phone'):</span>
                                                <span class="text"><a href="">{{$user->phone ?? null}}</a></span>
                                            </li>
                                            <li>
                                                <span class="title">@lang('admin.email'):</span>
                                                <span class="text"><a href="">{{$user->email ?? null}}</a></span>
                                            </li>

                                            <li>
                                                <span class="title">@lang('admin.dob'):</span>
                                                <span class="text">{{$user->dob ?? null}}</span>
                                            </li>

                                            <li>
                                                <span class="title">@lang('admin.address'):</span>
                                                <span class="text">{{$user->address ?? null}}</span>
                                            </li>

                                            <li>
                                                <span class="title">@lang('admin.gender'):</span>
                                                <span class="text">
                                                        @if($user->gender == 1)
                                                        @lang('admin.male')
                                                    @else
                                                        @lang('admin.female')
                                                    @endif
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="profile-tabs">
                <ul class="nav nav-tabs nav-tabs-bottom">
                    <li class="nav-item"><a class="nav-link active" href="#prescription"
                                            data-bs-toggle="tab">@lang('admin.doctor.Medical prescription')</a></li>
                    <li class="nav-item"><a class="nav-link" href="#analysis"
                                            data-bs-toggle="tab">@lang('admin.analysis')</a></li>
                    <li class="nav-item"><a class="nav-link" href="#rays"
                                            data-bs-toggle="tab">@lang('admin.rays')</a></li>
                    <li class="nav-item"><a class="nav-link" href="#service"
                                            data-bs-toggle="tab">@lang('admin.service')</a></li>
                    <li class="nav-item"><a class="nav-link" href="#sick_leave"
                                            data-bs-toggle="tab">@lang('admin.sick_leave')</a></li>
                    <li class="nav-item"><a class="nav-link" href="#leave_companion"
                                            data-bs-toggle="tab">@lang('admin.leave_companion')</a></li>
                    <li class="nav-item"><a class="nav-link" href="#Bills" data-bs-toggle="tab">@lang('admin.Bills')</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#attachment"
                                            data-bs-toggle="tab">@lang('admin.attachment')</a></li>
                    <li class="nav-item"><a class="nav-link" href="#insurance"
                                            data-bs-toggle="tab">@lang('admin.insurance')</a></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane show active" id="prescription">
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs nav-tabs-solid">
                                    @foreach($data['reservations'] as $index=>$reservation_item)
                                        <li class="nav-item"><a class="nav-link {{ $loop->first ? 'active' : '' }}"
                                                                href="#attachment{{$reservation_item->id}}"
                                                                data-bs-toggle="tab">@lang('admin.reservation_number')
                                                : {{ $reservation_item->booking_number }} ({{ $reservation_item->date }}
                                                )</a></li>
                                    @endforeach
                                </ul>
                                <div class="tab-content">
                                    @foreach($data['reservations'] as $index=>$reservation_item)
                                        <div class="tab-pane show {{ $loop->first ? 'active' : '' }}"
                                             id="attachment{{$reservation_item->id}}">
                                            <div class="row">

                                                <div class="col-sm-6">
                                                    <div class="card-box">
                                                        <h4 class="card-title">@lang('admin.patient_data')</h4>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.patient_name')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" class="form-control" readonly
                                                                       value="{{ $reservation_item->user->name ??null }}"/>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.patient_file')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" class="form-control" readonly
                                                                       value="{{ $reservation_item->user->file_number ??null }}">
                                                            </div>
                                                        </div>

                                                        <hr class="col-form">
                                                        <hr class="col-form">


                                                        <h4 class="card-title">@lang('admin.vital_signs')</h4>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.heat')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" class="form-control" readonly
                                                                       name="heat"
                                                                       value="{{ $reservation_item->vital_signs->heat ??null }}">
                                                            </div>

                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.weight')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" class="form-control" readonly
                                                                       name="weight"
                                                                       value="{{ $reservation_item->vital_signs->weight ??null }}">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.pulse')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" class="form-control" readonly
                                                                       name="pulse"
                                                                       value="{{ $reservation_item->vital_signs->pulse ??null }}">
                                                            </div>

                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.height')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" class="form-control" readonly
                                                                       name="height"
                                                                       value="{{ $reservation_item->vital_signs->height ??null }}">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.breathing')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" class="form-control" readonly
                                                                       name="breathing"
                                                                       value="{{ $reservation_item->vital_signs->breathing ??null }}">
                                                            </div>


                                                            <label class="col-form-label col-md-2">
                                                                @lang('admin.pregnant')
                                                                <input type="checkbox" id="pregnant" readonly
                                                                       name="pregnant" value="1"
                                                                       @if($reservation_item->vital_signs->pregnant ?? 0 == 1 ) checked @endif >
                                                            </label>
                                                        </div>


                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.blood_pressure')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" class="form-control" readonly
                                                                       name="blood_pressure" required
                                                                       value="{{ $reservation_item->vital_signs->blood_pressure ??null }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.sports_habits')</label>
                                                            <div class="col-md-10">
                                                                <input type="text" class="form-control" readonly
                                                                       name="sports_habits" required
                                                                       value="{{ $reservation_item->vital_signs->sports_habits ??null }}">
                                                            </div>
                                                        </div>
                                                        <hr class="col-form">
                                                        <hr class="col-form">

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.symptoms')</label>
                                                            <div class="col-md-10">
                                    <textarea class="form-control" readonly
                                              name="symptoms"> {{ $reservation_item->symptoms ??null }}</textarea>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.Diagnosis')</label>
                                                            <div class="col-md-10">
                                    <textarea class="form-control" readonly
                                              name="diagnosis"> {{ $reservation_item->diagnosis ??null }}</textarea>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.clinical_examination')</label>
                                                            <div class="col-md-10">
                                    <textarea class="form-control" readonly
                                              name="clinical_examination"> {{ $reservation_item->clinical_examination ??null }}</textarea>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.recommendations')</label>
                                                            <div class="col-md-10">
                                    <textarea class="form-control" readonly
                                              name="recommendations"> {{ $reservation_item->recommendations ??null }}</textarea>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.notes')</label>
                                                            <div class="col-md-10">
                                    <textarea class="form-control" readonly
                                              name="notes"> {{ $reservation_item->notes ??null }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-sm-6">
                                                    <div class="card-box">
                                                        <h4 class="card-title">@lang('admin.schedule_consultation')</h4>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.date')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" class="form-control"
                                                                       name="schedule_consultation_date"
                                                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                                                       value="{{$reservation_item->schedule_consultation_date ?? null}}"
                                                                       id="datepicker">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.time')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" class="form-control" readonly
                                                                       name="schedule_consultation_date"
                                                                       value="{{$reservation_item->schedule_consultation_time ?? null}}"
                                                                       id="datepicker">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <h4 class="card-title text-center">@lang('admin.doctor.Drug lists')</h4>
                                                        @if(count($reservation_item->reservation_drugs) > 0)
                                                            @foreach($reservation_item->reservation_drugs as $section)
                                                                <div class="col-sm-6 col-lg-6">
                                                                    <div class="card invoices-grid-card w-100">
                                                                        <div class="card-middle">
                                                                            <h2 class="card-middle-avatar">
                                                                                <a><img class="avatar avatar-sm me-2 avatar-img rounded-circle"
                                                                                        src="/media/icons/Medical_prescription_menu.png"
                                                                                        alt="Doctor Image">
                                                                                    @if($lang == 'en')
                                                                                        {{ $section->drugs->name_en }}
                                                                                    @else
                                                                                        {{ $section->drugs->name_ar }}
                                                                                    @endif
                                                                                    &nbsp; &nbsp;
                                                                                    @lang('admin.medicine_type.'.$section->drugs->medicine_type)
                                                                                </a>
                                                                            </h2>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="row align-items-center">
                                                                                <div class="col">
                                                                                    <h6 class="mb-2">@lang('admin.Dosage')</h6>
                                                                                    <h6 class="mb-2">@lang('admin.repetition')</h6>
                                                                                    <h6 class="mb-2">@lang('admin.nums_days')</h6>
                                                                                    <h6 class="mb-3">@lang('admin.notes')</h6>
                                                                                </div>
                                                                                <div class="col-auto">
                                                                                    <h6 class="mb-2">{{ $section->drugs->concentration_ratio ?? null }} @lang('admin.doctor.'.$section->drugs->concentration_type)</h6>
                                                                                    <h6 class="mb-2">{{ $section->repetition ?? null }} @lang('admin.time_per_day')</h6>
                                                                                    <h6 class="mb-2">{{$section->nums_days ?? null}} @lang('admin.day')</h6>
                                                                                    <h6 class="mb-3">{{$section->notes ?? null}}</h6>
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
                                                                    <table
                                                                        class="table border-0 custom-table comman-table datatable mb-0">
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
                                                            <table
                                                                class="table border-0 custom-table comman-table  mb-0">
                                                                <thead>
                                                                <tr>
                                                                    <th>@lang('admin.service_name')</th>
                                                                    <th>@lang('admin.patient_name')</th>
                                                                    <th> @lang('admin.patient_type') </th>
                                                                    <th> @lang('admin.insurance') </th>
                                                                    <th> @lang('admin.price')  </th>
                                                                    <th> @lang('admin.service_status') </th>
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
                                                                        @if($analysis->status == 0)
                                                                            <td style="color: #f0ad4e">@lang('admin.waiting')</td>
                                                                        @elseif($analysis->status == 1)
                                                                            <td style="color: #0f9d58">@lang('admin.done')</td>
                                                                        @else
                                                                            <td style="color: #ff0000">@lang('admin.not_implemented')</td>
                                                                        @endif
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
                                                            <table
                                                                class="table border-0 custom-table comman-table  mb-0">
                                                                <thead>
                                                                <tr>
                                                                    <th>@lang('admin.service_name')</th>
                                                                    <th>@lang('admin.patient_name')</th>
                                                                    <th> @lang('admin.patient_type') </th>
                                                                    <th> @lang('admin.insurance') </th>
                                                                    <th> @lang('admin.price') </th>
                                                                    <th> @lang('admin.service_status') </th>
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

                                                                        @if($rays->status == 0)
                                                                            <td style="color: #f0ad4e">@lang('admin.waiting')</td>
                                                                        @elseif($rays->status == 1)
                                                                            <td style="color: #0f9d58">@lang('admin.done')</td>
                                                                        @else
                                                                            <td style="color: #ff0000">@lang('admin.not_implemented')</td>
                                                                        @endif
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
                                                            <table
                                                                class="table border-0 custom-table comman-table  mb-0">
                                                                <thead>
                                                                <tr>
                                                                    <th>@lang('admin.service_name')</th>
                                                                    <th>@lang('admin.patient_name')</th>
                                                                    <th> @lang('admin.patient_type') </th>
                                                                    <th> @lang('admin.insurance') </th>
                                                                    <th> @lang('admin.price') </th>
                                                                    <th> @lang('admin.discount') </th>
                                                                    <th> @lang('admin.service_status') </th>
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

                                                                        @if($service->status == 0)
                                                                            <td style="color: #f0ad4e">@lang('admin.waiting')</td>
                                                                        @elseif($service->status == 1)
                                                                            <td style="color: #0f9d58">@lang('admin.done')</td>
                                                                        @else
                                                                            <td style="color: #ff0000">@lang('admin.not_implemented')</td>
                                                                        @endif
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
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="analysis">
                        <div class="row">
                            <div class="col-md-12">
                                @foreach($data['patient_analysis'] as $reservation)
                                    @php $analysis_reservation = \App\Models\PatientService::where('type',1)->where(['user_id'=>$user->id,'type'=>1,'reservation_id'=>$reservation->id])->orderBy('id','desc')->get(); @endphp
                                    @if(count($analysis_reservation) > 0)
                                    <div class="card-box">
                                        <div class="card-header">
                                            <h4 class="card-title">@lang('admin.patient_analysis') @lang('admin.date')
                                                ( {{ $reservation->reservations->date }})</h4>
                                        </div>
                                        <div class="card-body p-0 table-dash">
                                            <div class="table-responsive">
                                                <table
                                                    class="table mb-0 border-0 datatable custom-table patient-profile-table">
                                                    <thead>
                                                    <tr>
                                                        <th>@lang('admin.service_name')</th>
                                                        <th> @lang('admin.service_price') </th>
                                                        <th> @lang('admin.service_status') </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($analysis_reservation as $analysis)
                                                        <tr>
                                                            <td>{{$lang == 'en' ? $analysis->services->name_en : $analysis->services->name_ar}}</td>
                                                            <td>{{ $analysis->price ?? null }}</td>
                                                            @if($analysis->status == 0)
                                                                <td style="color: #f0ad4e">@lang('admin.waiting')</td>
                                                            @elseif($analysis->status == 1)
                                                                <td style="color: #0f9d58">@lang('admin.done')</td>
                                                            @else
                                                                <td style="color: #ff0000">@lang('admin.not_implemented')</td>
                                                            @endif

                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="rays">
                        <div class="row">
                            <div class="col-md-12">
                                @foreach($data['patient_rays'] as $reservation_rays)
                                    @php $rays_reservation = \App\Models\PatientService::where('type', 2)->where(['user_id'=>$user->id,'type'=>2,'reservation_id'=>$reservation_rays->id])->orderBy('id','desc')->get(); @endphp
                                    @if(count($rays_reservation) > 0)
                                    <div class="card-box">
                                        <div class="card-header">
                                            <h4 class="card-title">@lang('admin.patient_rays') @lang('admin.date')
                                                ( {{ $reservation_rays->reservations->date }})</h4>
                                        </div>
                                        <div class="card-body p-0 table-dash">
                                            <div class="table-responsive">
                                                <table
                                                    class="table mb-0 border-0 datatable custom-table patient-profile-table">
                                                    <thead>
                                                    <tr>
                                                        <th>@lang('admin.service_name')</th>
                                                        <th> @lang('admin.service_price') </th>
                                                        <th> @lang('admin.service_status') </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($rays_reservation as $rays)
                                                        <tr>
                                                            <td>{{$lang == 'en' ? $rays->services->name_en : $rays->services->name_ar}}</td>
                                                            <td>{{ $rays->price ?? null }}</td>
                                                            @if($rays->status == 0)
                                                                <td style="color: #f0ad4e">@lang('admin.waiting')</td>
                                                            @elseif($rays->status == 1)
                                                                <td style="color: #0f9d58">@lang('admin.done')</td>
                                                            @else
                                                                <td style="color: #ff0000">@lang('admin.not_implemented')</td>
                                                            @endif

                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="service">
                        <div class="row">
                            <div class="col-md-12">
                                @foreach($data['patient_services'] as $reservation_service)
                                    @php $service_reservation = \App\Models\PatientService::where('type', 3)->where(['user_id'=>$user->id,'type'=>3,'reservation_id'=>$reservation_service->id])->orderBy('id','desc')->get(); @endphp
                                    @if(count($service_reservation) > 0)
                                    <div class="card-box">
                                        <div class="card-header">
                                            <h4 class="card-title">@lang('admin.patient_services') @lang('admin.date')
                                                ( {{ $reservation_service->reservations->date ?? null }})</h4>
                                        </div>
                                        <div class="card-body p-0 table-dash">
                                            <div class="table-responsive">
                                                <table
                                                    class="table mb-0 border-0 datatable custom-table patient-profile-table">
                                                    <thead>
                                                    <tr>
                                                        <th>@lang('admin.service_name')</th>
                                                        <th> @lang('admin.service_price') </th>
                                                        <th> @lang('admin.service_status') </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($service_reservation as $service)
                                                        <tr>
                                                            <td>{{$lang == 'en' ? $service->services->name_en : $service->services->name_ar}}</td>
                                                            <td>{{ $service->price ?? null }}</td>
                                                            @if($service->status == 0)
                                                                <td style="color: #f0ad4e">@lang('admin.waiting')</td>
                                                            @elseif($service->status == 1)
                                                                <td style="color: #0f9d58">@lang('admin.done')</td>
                                                            @else
                                                                <td style="color: #ff0000">@lang('admin.not_implemented')</td>
                                                            @endif

                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="sick_leave">
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs nav-tabs-solid">
                                    @foreach($data['sick_leaves'] as $index=>$sick_leave)
                                        <li class="nav-item"><a class="nav-link {{ $loop->first ? 'active' : '' }}"
                                                                href="#leave{{$sick_leave->id}}"
                                                                data-bs-toggle="tab">@lang('admin.reservation_number')
                                                : {{ $sick_leave->reservation->booking_number }}
                                                ({{ $sick_leave->reservation->date }})</a></li>
                                    @endforeach
                                    {{--                                    <li class="nav-item"><a class="nav-link" href="#solid-tab2" data-bs-toggle="tab">Profile</a>--}}
                                    {{--                                    </li>--}}
                                    {{--                                    <li class="nav-item"><a class="nav-link" href="#solid-tab3" data-bs-toggle="tab">Messages</a>--}}
                                    {{--                                    </li>--}}
                                </ul>
                                <div class="tab-content">
                                    @foreach($data['sick_leaves'] as $index=>$sick_leave)
                                        <div class="tab-pane show {{ $loop->first ? 'active' : '' }}"
                                             id="leave{{$sick_leave->id}}">
                                            <div class="row">

                                                <div class="col-md-12">
                                                    <div class="card-box">
                                                        <h4 class="card-title text-center">@lang('admin.sick_leave_report')
                                                            ({{ $sick_leave->user->name }})</h4>
                                                        {{--                                                    <form method="POST" action="{{route('create-sick-leave', $sick_leave->reservation->id)}}" class="invoices-form">--}}
                                                        {{--                                                        @csrf--}}
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.request_number')</label>
                                                            <div class="col-md-4">
                                                                <input type="number"
                                                                       value="{{$sick_leave->reservation->booking_number}}"
                                                                       readonly
                                                                       class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.report_date')</label>
                                                            <div class="col-md-4">
                                                                <input type="date"
                                                                       value="{{$sick_leave->reservation->date}}"
                                                                       readonly class="form-control">
                                                            </div>
                                                        </div>

                                                        <hr class="col-form">
                                                        <hr class="col-form">

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.patient_name')</label>
                                                            <div class="col-md-10">
                                                                <input type="text" readonly
                                                                       value="{{$sick_leave->user->name}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.patient_file')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" readonly
                                                                       value="{{$sick_leave->user->file_number}}"
                                                                       class="form-control">
                                                            </div>

                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.patient_ID')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" readonly
                                                                       value="{{$sick_leave->user->ID_Number}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.patient_nationality')</label>
                                                            <div class="col-md-4">
                                                                @if($sick_leave->user->nationality)
                                                                    <input type="text" readonly
                                                                           value="{{ $lang == 'en' ? $sick_leave->user->nationality->name_en : $sick_leave->user->nationality->name_ar }}"
                                                                           class="form-control">
                                                                @else
                                                                    <input type="text" readonly class="form-control">
                                                                @endif
                                                            </div>

                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.patient_gender')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" readonly
                                                                       value="{{$sick_leave->user->gender == 1 ? trans('admin.male') : trans('admin.female')}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.patient_visit_date')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" readonly
                                                                       value="{{$sick_leave->reservation->date}}"
                                                                       class="form-control">
                                                            </div>

                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.patient_dob')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" readonly
                                                                       value="{{$sick_leave->user->dob}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>


                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.works')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" name="works" readonly required
                                                                       value="{{$sick_leave->works ?? old('works')}}"
                                                                       class="form-control">
                                                            </div>

                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.Workplace')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" name="Workplace" readonly required
                                                                       value="{{$sick_leave->Workplace ?? old('Workplace')}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>

                                                        <hr class="col-form">
                                                        <hr class="col-form">

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.doctor_name')</label>
                                                            <div class="col-md-10">
                                                                <input type="text" readonly
                                                                       value="{{$sick_leave->reservation->doctor->name}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.doctor_specialist')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" readonly
                                                                       value="{{$sick_leave->reservation->doctor->specialization ?? null}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>

                                                        <hr class="col-form">
                                                        <hr class="col-form">
                                                        <h4 class="card-title text-center">@lang('admin.medical_recommend')</h4>

                                                        <div class="form-group row">

                                                            <label class="col-form-label col-md-2">
                                                                <input type="checkbox" readonly id="address-line1">
                                                                @lang('admin.sick_leave')
                                                            </label>

                                                            <div class="col-md-1">
                                                                <input type="number" readonly required
                                                                       value="{{$sick_leave->sick_days ?? old('sick_days')}}"
                                                                       class="form-control">
                                                            </div>
                                                            <label
                                                                class="col-form-label col-md-1">@lang('admin.days')</label>

                                                            <label
                                                                class="col-form-label col-md-1">@lang('admin.start_from')</label>
                                                            <div class="col-md-3">
                                                                <input type="date" readonly name="from_date" required
                                                                       value="{{$sick_leave->from_date ?? old('from_date')}}"
                                                                       class="form-control">
                                                            </div>

                                                            <label
                                                                class="col-form-label col-md-1">@lang('admin.end_to')</label>
                                                            <div class="col-md-3">
                                                                <input type="date" readonly name="to_date" required
                                                                       value="{{$sick_leave->to_date ?? old('to_date')}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>


                                                        <div class="form-group row">

                                                            <label class="col-form-label col-md-12">
                                                                <input type="checkbox" readonly name="medical_company"
                                                                       @if ($sick_leave->medical_company ?? null == 1) checked
                                                                       @endif
                                                                       value="1">
                                                                @lang('admin.medical_company')
                                                            </label>

                                                            <label class="col-form-label col-md-12">
                                                                <input type="checkbox" readonly name="impossible_treat"
                                                                       @if ($sick_leave->impossible_treat ?? null == 1) checked
                                                                       @endif
                                                                       value="1">
                                                                @lang('admin.impossible_treat')
                                                            </label>
                                                            <label class="col-form-label col-md-12">
                                                                <input type="checkbox" readonly name="physician_leave"
                                                                       @if ($sick_leave->physician_leave ?? null == 1) checked
                                                                       @endif
                                                                       value="1">
                                                                @lang('admin.physician_leave')
                                                            </label>

                                                        </div>


                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.Diagnosis')</label>
                                                            <div class="col-md-10">
                                                                <input type="text" readonly name="Diagnosis" required
                                                                       value="{{$sick_leave->Diagnosis ?? old('Diagnosis')}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.notes')</label>
                                                            <div class="col-md-10">
                                                                <input type="text" readonly name="notes" required
                                                                       value="{{$sick_leave->notes ?? old('notes')}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                        <hr class="col-form">
                                                        <hr class="col-form">

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.directed_to')</label>
                                                            <div class="col-md-10">
                                                                <input type="text" readonly required name="directed_to"
                                                                       value="{{$sick_leave->directed_to ?? old('directed_to')}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.letter_no')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" readonly required name="letter_no"
                                                                       value="{{$sick_leave->letter_no ?? old('letter_no')}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.letter_date')</label>
                                                            <div class="col-md-4">
                                                                <input type="date" readonly name="letter_date" required
                                                                       value="{{$sick_leave->letter_date ?? old('letter_date')}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="user_id"
                                                               value="{{ $sick_leave->user_id }}">
                                                        <div class="text-center buttons">
                                                            {{--                                                            <button type="submit" class="btn btn-primary">@lang('admin.save')</button>--}}
                                                            {{--                                                            <button type="submit" class="btn btn-primary">@lang('admin.send_reception')</button>--}}
                                                            <button onclick="printPage()" class="btn btn-primary"
                                                                    style="background-color: #000;color#fff">@lang('admin.print')</button>
                                                        </div>
                                                        {{--                                                    </form>--}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="leave_companion">
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs nav-tabs-solid">
                                    @foreach($data['leave_companion'] as $index=>$leave_companion)
                                        <li class="nav-item"><a class="nav-link {{ $loop->first ? 'active' : '' }}"
                                                                href="#companion{{$leave_companion->id}}"
                                                                data-bs-toggle="tab">@lang('admin.reservation_number')
                                                : {{ $leave_companion->reservation->booking_number }}
                                                ({{ $leave_companion->reservation->date }})</a></li>
                                    @endforeach
                                </ul>
                                <div class="tab-content">
                                    @foreach($data['leave_companion'] as $index=>$leave_companion)
                                        <div class="tab-pane show {{ $loop->first ? 'active' : '' }}"
                                             id="companion{{$leave_companion->id}}">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card-box">
                                                        <h4 class="card-title text-center">@lang('admin.companion_sick_leave_report')
                                                            ({{ $leave_companion->user->name }} )</h4>
                                                        {{--                                                    <form method="POST" action="{{route('create-sick-leave', $leave_companion->reservation->id)}}"--}}
                                                        {{--                                                          class="invoices-form">--}}
                                                        {{--                                                        @csrf--}}

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.request_number')</label>
                                                            <div class="col-md-4">
                                                                <input type="number"
                                                                       value="{{$leave_companion->reservation->booking_number}}"
                                                                       readonly
                                                                       class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.report_date')</label>
                                                            <div class="col-md-4">
                                                                <input type="date"
                                                                       value="{{$leave_companion->reservation->date}}"
                                                                       readonly class="form-control">
                                                            </div>
                                                        </div>

                                                        <hr class="col-form">
                                                        <hr class="col-form">

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.patient_name')</label>
                                                            <div class="col-md-10">
                                                                <input type="text" readonly
                                                                       value="{{$leave_companion->user->name}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.patient_file')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" readonly
                                                                       value="{{$leave_companion->user->file_number}}"
                                                                       class="form-control">
                                                            </div>

                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.patient_ID')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" readonly
                                                                       value="{{$leave_companion->user->ID_Number}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.patient_nationality')</label>
                                                            <div class="col-md-4">
                                                                @if($leave_companion->user->nationality)
                                                                    <input type="text" readonly
                                                                           value="{{ $lang == 'en' ? $leave_companion->user->nationality->name_en : $leave_companion->user->nationality->name_ar }}"
                                                                           class="form-control">
                                                                @else
                                                                    <input type="text" readonly class="form-control">
                                                                @endif
                                                            </div>

                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.patient_gender')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" readonly
                                                                       value="{{$leave_companion->user->gender == 1 ? trans('admin.male') : trans('admin.female')}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.patient_visit_date')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" readonly
                                                                       value="{{$leave_companion->reservation->date}}"
                                                                       class="form-control">
                                                            </div>

                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.patient_dob')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" readonly
                                                                       value="{{$leave_companion->user->dob}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>


                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.works')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" name="works" readonly required
                                                                       value="{{$leave_companion->works ?? old('works')}}"
                                                                       class="form-control">
                                                            </div>

                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.Workplace')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" name="Workplace" readonly required
                                                                       value="{{$leave_companion->Workplace ?? old('Workplace')}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>

                                                        <hr class="col-form">
                                                        <hr class="col-form">

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.doctor_name')</label>
                                                            <div class="col-md-10">
                                                                <input type="text" readonly
                                                                       value="{{$leave_companion->reservation->doctor->name}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.doctor_specialist')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" readonly
                                                                       value="{{$leave_companion->reservation->doctor->specialization ?? null}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>

                                                        <hr class="col-form">
                                                        <hr class="col-form">
                                                        <h4 class="card-title text-center">@lang('admin.medical_recommend')</h4>

                                                        <div class="form-group row">

                                                            <label class="col-form-label col-md-2">
                                                                <input type="checkbox" readonly id="address-line1">
                                                                @lang('admin.sick_leave')
                                                            </label>

                                                            <div class="col-md-1">
                                                                <input type="number" readonly required
                                                                       value="{{$leave_companion->sick_days ?? old('sick_days')}}"
                                                                       class="form-control">
                                                            </div>
                                                            <label
                                                                class="col-form-label col-md-1">@lang('admin.days')</label>

                                                            <label
                                                                class="col-form-label col-md-1">@lang('admin.start_from')</label>
                                                            <div class="col-md-3">
                                                                <input type="date" name="from_date" readonly required
                                                                       value="{{$leave_companion->from_date ?? old('from_date')}}"
                                                                       class="form-control">
                                                            </div>

                                                            <label
                                                                class="col-form-label col-md-1">@lang('admin.end_to')</label>
                                                            <div class="col-md-3">
                                                                <input type="date" name="to_date" readonly required
                                                                       value="{{$leave_companion->to_date ?? old('to_date')}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>


                                                        <div class="form-group row">

                                                            <label class="col-form-label col-md-12">
                                                                <input type="checkbox" name="medical_company" readonly
                                                                       @if ($leave_companion->medical_company ?? null == 1) checked
                                                                       @endif
                                                                       value="1">
                                                                @lang('admin.medical_company')
                                                            </label>

                                                            <label class="col-form-label col-md-12">
                                                                <input type="checkbox" name="impossible_treat" readonly
                                                                       @if ($leave_companion->impossible_treat ?? null == 1) checked
                                                                       @endif
                                                                       value="1">
                                                                @lang('admin.impossible_treat')
                                                            </label>
                                                            <label class="col-form-label col-md-12">
                                                                <input type="checkbox" name="physician_leave" readonly
                                                                       @if ($leave_companion->physician_leave ?? null == 1) checked
                                                                       @endif
                                                                       value="1">
                                                                @lang('admin.physician_leave')
                                                            </label>

                                                        </div>


                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.Diagnosis')</label>
                                                            <div class="col-md-10">
                                                                <input type="text" name="Diagnosis" readonly required
                                                                       value="{{$leave_companion->Diagnosis ?? old('Diagnosis')}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.notes')</label>
                                                            <div class="col-md-10">
                                                                <input type="text" name="notes" readonly required
                                                                       value="{{$leave_companion->notes ?? old('notes')}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                        <hr class="col-form">
                                                        <hr class="col-form">

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.directed_to')</label>
                                                            <div class="col-md-10">
                                                                <input type="text" required name="directed_to" readonly
                                                                       value="{{$leave_companion->directed_to ?? old('directed_to')}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.letter_no')</label>
                                                            <div class="col-md-4">
                                                                <input type="text" required name="letter_no" readonly
                                                                       value="{{$leave_companion->letter_no ?? old('letter_no')}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.letter_date')</label>
                                                            <div class="col-md-4">
                                                                <input type="date" name="letter_date" readonly required
                                                                       value="{{$leave_companion->letter_date ?? old('letter_date')}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="user_id"
                                                               value="{{ $leave_companion->user_id }}">
                                                        <input type="hidden" name="type" value="2">
                                                        <hr class="col-form">
                                                        <hr class="col-form">

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.companion_name')</label>
                                                            <div class="col-md-10">
                                                                <input type="text" name="companion_name" readonly
                                                                       required
                                                                       value="{{$leave_companion->companion_name ?? old('companion_name')}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.relation_patient')</label>
                                                            <div class="col-md-10">
                                                                <input type="text" name="relation_patient" readonly
                                                                       required
                                                                       value="{{$leave_companion->relation_patient ?? old('relation_patient')}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.occupation')</label>
                                                            <div class="col-md-10">
                                                                <input type="text" name="occupation" readonly required
                                                                       value="{{$leave_companion->occupation ?? old('occupation')}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.Workplaces')</label>
                                                            <div class="col-md-10">
                                                                <input type="text" name="Workplaces" readonly required
                                                                       value="{{$leave_companion->Workplaces ?? old('Workplaces')}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.sick_days')</label>
                                                            <div class="col-md-4">
                                                                <input type="number"
                                                                       name="companion_sick_days"
                                                                       required readonly
                                                                       value="{{$leave_companion->companion_sick_days ?? old('companion_sick_days')}}"
                                                                       class="form-control">
                                                            </div>
                                                            <label
                                                                class="col-form-label col-md-1">@lang('admin.days')</label>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.from_date')</label>
                                                            <div class="col-md-4">
                                                                <input type="date"
                                                                       name="companion_from_date"
                                                                       required readonly
                                                                       value="{{$leave_companion->companion_from_date ?? old('companion_from_date')}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-form-label col-md-2">@lang('admin.to_date')</label>
                                                            <div class="col-md-4">
                                                                <input type="date"
                                                                       name="companion_to_date"
                                                                       required readonly
                                                                       value="{{$leave_companion->companion_to_date ?? old('companion_to_date')}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="text-center buttons">
                                                            {{--                                                            <button type="submit" class="btn btn-primary">@lang('admin.save')</button>--}}
                                                            {{--                                                            <button type="submit" class="btn btn-primary">@lang('admin.send_reception')</button>--}}
                                                            <button onclick="printPage()"
                                                                    class="btn btn-primary">@lang('admin.print')</button>

                                                        </div>

                                                        {{--                                                    </form>--}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="Bills">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-box">
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
                    <div class="tab-pane" id="attachment">
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs nav-tabs-solid">
                                    @foreach($data['attachment'] as $index=>$attachment)
                                        <li class="nav-item"><a class="nav-link {{ $loop->first ? 'active' : '' }}"
                                                                href="#attachment{{$attachment->id}}"
                                                                data-bs-toggle="tab">@lang('admin.reservation_number')
                                                : {{ $attachment->reservation->booking_number }}
                                                ({{ $attachment->reservation->date }})</a></li>
                                    @endforeach
                                </ul>
                                <div class="tab-content">
                                    @foreach($data['attachment'] as $index=>$attachment)
                                        <div class="tab-pane show {{ $loop->first ? 'active' : '' }}"
                                             id="attachment{{$attachment->id}}">
                                            <div class="row">
                                                @php
                                                    $extension = pathinfo($attachment->image, PATHINFO_EXTENSION);
                                                @endphp
                                                <div class="image-or-pdf">
                                                    @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                                        <img src="{{$attachment->image}}" alt="@lang('admin.no_image')">
                                                    @else
                                                        <!-- Use 'embed' for PDF files: -->
                                                        {{--                                <iframe src="{{$attachment->image}}" style="width: 100%; height: 600px;"></iframe>--}}
                                                    @endif
                                                </div>

                                                <div class="download-link">
                                                    <!-- Replace 'your-image.jpg' or 'your-file.pdf' with the actual file path -->
                                                    @lang('admin.notes') : <p>{{$attachment->notes ?? null}}</p>
                                                    <a href="{{$attachment->image}}" download>@lang('admin.Download')
                                                        ({{ $index + 1 }})</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function printPage() {
            // Apply the print styles
            var style = document.createElement('style');
            style.innerHTML = '@media print { .header, .sidebar, .sidebar-overlay,.page-header,.buttons { display: none !important; } }';
            document.head.appendChild(style);

            // Trigger the print dialog
            window.print();
            // Remove the print styles after printing
            style.remove();
        }
    </script>
@endsection
