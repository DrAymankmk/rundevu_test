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
    <style type="text/css" media="print">
        .header, .sidebar, .sidebar-overlay,.page-header,.buttons {
            display: none !important;
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
                                    href="{{ route('patient-file',$user->id) }}">@lang('admin.file') </a></li>

                            <li class="breadcrumb-item active">@lang('admin.companion_sick_leave_report')</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card-box">
                        <h4 class="card-title text-center">@lang('admin.companion_sick_leave_report')  ({{ $user->name }} )</h4>
                        <form method="POST" action="{{route('create-sick-leave', $reservation->id)}}"
                              class="invoices-form">
                            @csrf

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.request_number')</label>
                                <div class="col-md-4">
                                    <input type="number" value="{{$reservation->booking_number}}" readonly
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.report_date')</label>
                                <div class="col-md-4">
                                    <input type="date" value="{{$reservation->date}}" readonly class="form-control">
                                </div>
                            </div>

                            <hr class="col-form">
                            <hr class="col-form">

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.patient_name')</label>
                                <div class="col-md-10">
                                    <input type="text" readonly value="{{$user->name}}" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.patient_file')</label>
                                <div class="col-md-4">
                                    <input type="text" readonly value="{{$user->file_number}}" class="form-control">
                                </div>

                                <label class="col-form-label col-md-2">@lang('admin.patient_ID')</label>
                                <div class="col-md-4">
                                    <input type="text" readonly value="{{$user->ID_Number}}" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.patient_nationality')</label>
                                <div class="col-md-4">
                                    @if($user->nationality)
                                        <input type="text" readonly
                                               value="{{ $lang == 'en' ? $user->nationality->name_en : $user->nationality->name_ar }}"
                                               class="form-control">
                                    @else
                                        <input type="text" readonly class="form-control">
                                    @endif
                                </div>

                                <label class="col-form-label col-md-2">@lang('admin.patient_gender')</label>
                                <div class="col-md-4">
                                    <input type="text" readonly
                                           value="{{$user->gender == 1 ? trans('admin.male') : trans('admin.female')}}"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.patient_visit_date')</label>
                                <div class="col-md-4">
                                    <input type="text" readonly value="{{$reservation->date}}" class="form-control">
                                </div>

                                <label class="col-form-label col-md-2">@lang('admin.patient_dob')</label>
                                <div class="col-md-4">
                                    <input type="text" readonly value="{{$user->dob}}"
                                           class="form-control">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.works')</label>
                                <div class="col-md-4">
                                    <input type="text" name="works" required
                                           value="{{$data['sick_leave_reservation']->works ?? old('works')}}"
                                           class="form-control">
                                </div>

                                <label class="col-form-label col-md-2">@lang('admin.Workplace')</label>
                                <div class="col-md-4">
                                    <input type="text" name="Workplace" required
                                           value="{{$data['sick_leave_reservation']->Workplace ?? old('Workplace')}}"
                                           class="form-control">
                                </div>
                            </div>

                            <hr class="col-form">
                            <hr class="col-form">

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.doctor_name')</label>
                                <div class="col-md-10">
                                    <input type="text" readonly value="{{$reservation->doctor->name}}"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.doctor_specialist')</label>
                                <div class="col-md-4">
                                    <input type="text" readonly value="{{$reservation->doctor->specialization ?? null}}"
                                           class="form-control">
                                </div>
                            </div>

                            <hr class="col-form">
                            <hr class="col-form">
                            <h4 class="card-title text-center">@lang('admin.medical_recommend')</h4>

                            <div class="form-group row">

                                <label class="col-form-label col-md-2">
                                    <input type="checkbox" id="address-line1">
                                    @lang('admin.sick_leave')
                                </label>

                                <div class="col-md-1">
                                    <input type="number" required
                                           value="{{$data['sick_leave_reservation']->sick_days ?? old('sick_days')}}"
                                           class="form-control">
                                </div>
                                <label class="col-form-label col-md-1">@lang('admin.days')</label>

                                <label class="col-form-label col-md-1">@lang('admin.start_from')</label>
                                <div class="col-md-3">
                                    <input type="date" name="from_date" required
                                           value="{{$data['sick_leave_reservation']->from_date ?? old('from_date')}}"
                                           class="form-control">
                                </div>

                                <label class="col-form-label col-md-1">@lang('admin.end_to')</label>
                                <div class="col-md-3">
                                    <input type="date" name="to_date" required
                                           value="{{$data['sick_leave_reservation']->to_date ?? old('to_date')}}"
                                           class="form-control">
                                </div>
                            </div>


                            <div class="form-group row">

                                <label class="col-form-label col-md-12">
                                    <input type="checkbox" name="medical_company"
                                           @if ($data['sick_leave_reservation']->medical_company ?? null == 1) checked
                                           @endif
                                           value="1">
                                    @lang('admin.medical_company')
                                </label>

                                <label class="col-form-label col-md-12">
                                    <input type="checkbox" name="impossible_treat"
                                           @if ($data['sick_leave_reservation']->impossible_treat ?? null == 1) checked
                                           @endif
                                           value="1">
                                    @lang('admin.impossible_treat')
                                </label>
                                <label class="col-form-label col-md-12">
                                    <input type="checkbox" name="physician_leave"
                                           @if ($data['sick_leave_reservation']->physician_leave ?? null == 1) checked
                                           @endif
                                           value="1">
                                    @lang('admin.physician_leave')
                                </label>

                            </div>


                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.Diagnosis')</label>
                                <div class="col-md-10">
                                    <input type="text" name="Diagnosis" required
                                           value="{{$data['sick_leave_reservation']->Diagnosis ?? old('Diagnosis')}}"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.notes')</label>
                                <div class="col-md-10">
                                    <input type="text" name="notes" required
                                           value="{{$data['sick_leave_reservation']->notes ?? old('notes')}}"
                                           class="form-control">
                                </div>
                            </div>
                            <hr class="col-form">
                            <hr class="col-form">

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.directed_to')</label>
                                <div class="col-md-10">
                                    <input type="text" required name="directed_to"
                                           value="{{$data['sick_leave_reservation']->directed_to ?? old('directed_to')}}"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.letter_no')</label>
                                <div class="col-md-4">
                                    <input type="text" required name="letter_no"
                                           value="{{$data['sick_leave_reservation']->letter_no ?? old('letter_no')}}"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.letter_date')</label>
                                <div class="col-md-4">
                                    <input type="date" name="letter_date" required
                                           value="{{$data['sick_leave_reservation']->letter_date ?? old('letter_date')}}"
                                           class="form-control">
                                </div>
                            </div>
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <input type="hidden" name="type" value="2">
                            <hr class="col-form">
                            <hr class="col-form">

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.companion_name')</label>
                                <div class="col-md-10">
                                    <input type="text" name="companion_name" required
                                           value="{{$data['sick_leave_reservation']->companion_name ?? old('companion_name')}}"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.relation_patient')</label>
                                <div class="col-md-10">
                                    <input type="text" name="relation_patient" required
                                           value="{{$data['sick_leave_reservation']->relation_patient ?? old('relation_patient')}}"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.occupation')</label>
                                <div class="col-md-10">
                                    <input type="text" name="occupation" required
                                           value="{{$data['sick_leave_reservation']->occupation ?? old('occupation')}}"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.Workplaces')</label>
                                <div class="col-md-10">
                                    <input type="text" name="Workplaces" required
                                           value="{{$data['sick_leave_reservation']->Workplaces ?? old('Workplaces')}}"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.sick_days')</label>
                                <div class="col-md-4">
                                    <input type="number"
                                           name="companion_sick_days"
                                           required
                                           value="{{$data['sick_leave_reservation']->companion_sick_days ?? old('companion_sick_days')}}"
                                           class="form-control">
                                </div>
                                <label class="col-form-label col-md-1">@lang('admin.days')</label>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.from_date')</label>
                                <div class="col-md-4">
                                    <input type="date"
                                           name="companion_from_date"
                                           required
                                           value="{{$data['sick_leave_reservation']->companion_from_date ?? old('companion_from_date')}}"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.to_date')</label>
                                <div class="col-md-4">
                                    <input type="date"
                                           name="companion_to_date"
                                           required
                                           value="{{$data['sick_leave_reservation']->companion_to_date ?? old('companion_to_date')}}"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="text-center buttons">
                                <button type="submit" class="btn btn-primary">@lang('admin.save')</button>
                                <button type="submit" class="btn btn-primary">@lang('admin.send_reception')</button>
                                <button  onclick="printPage()" class="btn btn-primary">@lang('admin.print')</button>

                            </div>

                        </form>
                    </div>
                </div>
            </div>


            <div class="row buttons">
                <div class="col-sm-12">

                    <div class="card card-table show-entire">
                        <div class="card-body">

                            <!-- Table Header -->
                            <div class="page-table-header mb-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="doctor-table-blk">
                                            <h3>@lang('admin.companion_sick_leave_report')</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Table Header -->

                            <div class="table-responsive">
                                <table class="table border-0 custom-table comman-table datatable mb-0">
                                    <thead>
                                    <tr>
                                        <th>@lang('admin.Doctor')</th>
                                        <th> @lang('admin.doctor_specialist') </th>
                                        <th> @lang('admin.date') </th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $data['sick_leaves'] as $sick_leave)
                                        <tr>
                                            <td class="profile-image"><a href="{{ route('patient-sick-leave',$sick_leave->reservation_id) }}">
                                                    <img width="28" height="28" src="{{$sick_leave->reservation->doctor->image ?? null}}" class="rounded-circle m-r-5"
                                                         alt="">{{$sick_leave->reservation->doctor->name ?? null}}
                                                </a></td>
                                            <td>{{$sick_leave->reservation->doctor->specialization ?? null}}</td>
                                            <td>{{ $sick_leave->created_at->format('F jS') }}</td>

                                            <td class="text-end">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle"
                                                       data-bs-toggle="dropdown" aria-expanded="false"><i
                                                            class="fa fa-ellipsis-v"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="{{ route('patient-sick-leave',$sick_leave->reservation_id) }}"><i
                                                                class="feather-eye m-r-5"></i> @lang('admin.sick_leave')
                                                        </a>
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{ $data['sick_leaves']->links() }}
                    </div>
                </div>
            </div>


        </div>
    </div>



    <!-- Add this script at the end of your body or in the <head> section -->
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
