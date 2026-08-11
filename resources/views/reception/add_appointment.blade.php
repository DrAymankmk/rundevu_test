@extends('includes_admin.mainlayout')
@section('content')

    <style>
        .booking-card {
            border-radius: 16px;
            border: 1px solid #eef1f7;
            box-shadow: 0 6px 22px rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }

        .booking-card .card-body {
            padding: 28px;
        }

        .form-heading h4 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 18px;
            color: #1f2937;
        }

        .booking-section-title {
            margin-top: 10px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #e5e7eb;
        }

        .local-forms label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #374151;
        }

        .form-control,
        .select2-container .select2-selection--single {
            min-height: 46px;
            border-radius: 12px !important;
            border: 1px solid #dbe3ef !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 44px !important;
            padding-left: 14px;
            padding-right: 14px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px !important;
            right: 8px;
        }

        .field-hint {
            font-size: 12px;
            color: #6b7280;
            margin-top: 6px;
            display: block;
        }

        .ajax-status-box {
            display: none;
            align-items: center;
            gap: 10px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            color: #475569;
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 18px;
            font-size: 14px;
            font-weight: 500;
        }

        .ajax-status-box.show {
            display: flex;
        }

        .ajax-spinner {
            width: 18px;
            height: 18px;
            border: 2px solid #dbeafe;
            border-top: 2px solid #2563eb;
            border-radius: 50%;
            animation: spin .8s linear infinite;
            flex-shrink: 0;
        }

        @keyframes spin {
            100% { transform: rotate(360deg); }
        }

        .time-legend {
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
            margin-top: 8px;
            margin-bottom: 8px;
        }

        .time-legend .item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #6b7280;
            font-size: 13px;
        }

        .time-legend .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .dot.available { background: #22c55e; }
        .dot.booked { background: #ef4444; }
        .dot.pending { background: #f59e0b; }

        .doctor-submit .btn {
            min-width: 130px;
            border-radius: 12px;
            padding: 10px 18px;
            font-weight: 600;
        }

        .slot-note {
            margin-top: 10px;
            font-size: 12px;
            color: #64748b;
        }

        @media (max-width: 767px) {
            .booking-card .card-body {
                padding: 18px;
            }
        }
    </style>

    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-7 col-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('appointments') }}">@lang('admin.reception.appointments_list')</a>
                            </li>
                            <li class="breadcrumb-item px-2"><i id="breadcrumbArrow"></i></li>
                            <li class="breadcrumb-item active">@lang('admin.reserve_appointment')</li>
                        </ul>
                    </div>

                    <div class="col-sm-5 col-12 text-end">
                        <a href="{{ route('add-patient') }}" class="btn btn-primary btn-rounded">
                            <i class="fa fa-plus m-r-5"></i> @lang('admin.new_patient')
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card booking-card">
                        <div class="card-body">
                            <form method="post" action="{{ route('create-appointment') }}" enctype="multipart/form-data" class="needs-validation">
                                @csrf

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-heading booking-section-title">
                                            <h4>@lang('admin.reserve_appointment')</h4>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div id="ajax-global-status" class="ajax-status-box">
                                            <span class="ajax-spinner"></span>
                                            <span class="status-text">Loading...</span>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group local-forms">
                                            <label for="patient">
                                                @lang('admin.patient_name')
                                                <span class="login-danger">*</span>
                                            </label>
                                            <select id="patient" name="patient_id" class="form-control my-select2" required>
                                                <option value="">@lang('admin.select')</option>
                                                @foreach($data['patients'] as $patient)
                                                    <option value="{{ $patient->id }}">
                                                        {{ $patient->name }}
                                                        @if($patient->phone) - {{ $patient->phone }} @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="field-hint">@lang('admin.select') @lang('admin.patient_name')</span>
                                        </div>
                                    </div>

{{--                                    <div class="col-12">--}}
{{--                                        <div id="patient-confirmation" class="ajax-status-box">--}}
{{--                                            <span class="status-text"></span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

                                    <div class="col-12">
                                        <div class="form-heading booking-section-title">
                                            <h4>@lang('admin.appointment_details')</h4>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-12 col-xl-12">
                                        <div class="form-group local-forms">
                                            <label for="specialist_id">@lang('admin.specialization')</label>
                                            <select class="form-control select" id="specialist_id" required>
                                                <option value="">@lang('admin.select')</option>
                                                @foreach($data['specializations'] as $specialist)
                                                    <option value="{{ $specialist->specialty_id }}">
                                                        {{ app()->getLocale() == 'en' ? $specialist->specialties->name_en : $specialist->specialties->name_ar }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="field-hint">اختر التخصص أولًا لعرض الأطباء.</span>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-12 col-xl-12">
                                        <div class="form-group local-forms">
                                            <label for="doctor_id">@lang('admin.Doctor')</label>
                                            <select class="form-control select" id="doctor_id" name="doctor_id" required disabled>
                                                <option value="">@lang('admin.select')</option>
                                            </select>
                                            <span class="field-hint" id="doctor_hint">سيتم تحميل الأطباء بعد اختيار التخصص.</span>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-12 col-xl-12">
                                        <div class="form-group local-forms">
                                            <label for="datepicker">
                                                @lang('admin.appointment_date')
                                                <span class="login-danger">*</span>
                                            </label>
                                            <input
                                                type="date"
                                                class="form-control"
                                                name="date"
                                                min="{{ date('Y-m-d') }}"
                                                value="{{ old('date') }}"
                                                id="datepicker"
                                                required
                                            >
                                            <span class="field-hint">اختر التاريخ لعرض المواعيد المتاحة.</span>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-12 col-xl-12">
                                        <div class="form-group local-forms">
                                            <label for="appointment">@lang('admin.appointment_time')</label>
                                            <select class="form-control select" id="appointment" name="appointment_time" required disabled>
                                                <option value="">@lang('admin.select')</option>
                                            </select>

                                            <div class="time-legend">
                                                <div class="item"><span class="dot available"></span> متاح</div>
                                                <div class="item"><span class="dot booked"></span> محجوز</div>
                                            </div>

                                            <div class="slot-note" id="appointment_hint">
                                                اختر الطبيب والتاريخ أولًا لتحميل الأوقات.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-12">
                                        <div class="form-group local-forms">
                                            <label>@lang('admin.notes') <span class="login-danger">*</span></label>
                                            <textarea class="form-control" rows="3" cols="30" name="notes"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="doctor-submit text-end">
                                            <button type="submit" class="btn btn-primary submit-form me-2">
                                                @lang('admin.save')
                                            </button>
                                            <button type="reset" class="btn btn-outline-secondary cancel-form">
                                                @lang('admin.cancel')
                                            </button>
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

    <script src="/reception/assets/js/jquery.js"></script>
    <script src="/reception/assets/js/select2.js"></script>
    <script src="/reception/assets/js/qr-code.js"></script>

    <script>
        $(document).ready(function () {

            $('#patient').select2({
                placeholder: "@lang('admin.search_patient_name_or_phone')",
                allowClear: true,
                width: '100%',
                language: {
                    noResults: function () {
                        return "@lang('admin.no_results_found')";
                    },
                    searching: function () {
                        return "@lang('admin.searching')...";
                    }
                }
            });

            $('#specialist_id').select2({ width: '100%' });
            $('#doctor_id').select2({ width: '100%' });
            $('#appointment').select2({ width: '100%' });

            function showGlobalStatus(text = 'Loading...') {
                $('#ajax-global-status .status-text').text(text);
                $('#ajax-global-status').addClass('show');
            }

            function hideGlobalStatus() {
                $('#ajax-global-status').removeClass('show');
            }

            function resetDoctors(message = '{{ __("admin.select") }}') {
                $('#doctor_id')
                    .prop('disabled', true)
                    .html('<option value="">' + message + '</option>')
                    .trigger('change');

                $('#doctor_hint').text('سيتم تحميل الأطباء بعد اختيار التخصص.');
            }

            function resetAppointments(message = '{{ __("admin.select") }}') {
                $('#appointment')
                    .prop('disabled', true)
                    .html('<option value="">' + message + '</option>')
                    .trigger('change');

                $('#appointment_hint').text('اختر الطبيب والتاريخ أولًا لتحميل الأوقات.');
            }

            $('#patient').on('change', function () {
                let patientId = $(this).val();

                $('#patient-confirmation').removeClass('show');
                if (!patientId) {
                    return;
                }

                $.ajax({
                    url: '{{ route("getQrCodeUser") }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        patient_id: patientId
                    },
                    success: function (res) {
                        if (!res || !res.patient) {
                            return;
                        }

                        let patient = res.patient;
                        let status = res.status === 'valid'
                            ? '{{ app()->getLocale() == 'ar' ? 'ملف المريض صالح' : 'Patient file is valid' }}'
                            : '{{ app()->getLocale() == 'ar' ? 'ملف المريض غير صالح' : 'Patient file is invalid' }}';
                        let phone = patient.phone ? ' - ' + patient.phone : '';
                        let fileNumber = patient.file_number ? ' - ' + patient.file_number : '';

                        $('#patient-confirmation .status-text').text(status + ': ' + patient.name + phone + fileNumber);
                        $('#patient-confirmation').addClass('show');
                    }
                });
            });

            $('#specialist_id').on('change', function () {
                let specialist_id = $(this).val();

                resetDoctors();
                resetAppointments();

                if (!specialist_id) {
                    return;
                }

                showGlobalStatus('جاري تحميل الأطباء...');

                $.ajax({
                    url: '{{ route("getDoctorsFromSpecialists") }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        specialist_id: specialist_id
                    },
                    success: function (res) {
                        let options = '<option value="">{{ __("admin.select") }}</option>';

                        if (res && res.data && res.data.length > 0) {
                            $.each(res.data, function (key, value) {
                                options += `<option value="${value.id}">${value.name}</option>`;
                            });

                            $('#doctor_id')
                                .html(options)
                                .prop('disabled', false)
                                .trigger('change');

                            $('#doctor_hint').text('تم تحميل الأطباء بنجاح. اختر الطبيب.');
                        } else {
                            $('#doctor_id')
                                .html('<option value="">{{ __("admin.no_data") }}</option>')
                                .prop('disabled', true)
                                .trigger('change');

                            $('#doctor_hint').text('لا يوجد أطباء لهذا التخصص.');
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                        resetDoctors('{{ __("admin.no_data") }}');
                        $('#doctor_hint').text('حدث خطأ أثناء تحميل الأطباء.');
                    },
                    complete: function () {
                        hideGlobalStatus();
                    }
                });
            });

            $('#doctor_id, #datepicker').on('change', function () {
                let doctor_id = $('#doctor_id').val();
                let selectedDate = $('#datepicker').val();

                resetAppointments();

                if (!doctor_id || !selectedDate) {
                    return;
                }

                showGlobalStatus('جاري تحميل المواعيد المتاحة...');

                $.ajax({
                    type: 'GET',
                    url: '{{ route("get.available.times") }}',
                    dataType: 'json',
                    data: {
                        date: selectedDate,
                        doctor_id: doctor_id
                    },
                    success: function (res) {
                        let options = '<option value="">{{ __("admin.select") }}</option>';

                        if (res && res.length > 0) {
                            $.each(res, function (key, value) {
                                let label = value.time;
                                let disabled = '';

                                if (parseInt(value.status) !== 1) {
                                    label += ' - محجوز';
                                    disabled = 'disabled';
                                }

                                options += `<option value="${value.time}" ${disabled}>${label}</option>`;
                            });

                            $('#appointment')
                                .html(options)
                                .prop('disabled', false)
                                .trigger('change');

                            $('#appointment_hint').text('تم تحميل المواعيد. المواعيد المحجوزة ظاهرة كغير قابلة للاختيار.');
                        } else {
                            $('#appointment')
                                .html('<option value="">{{ __("admin.no_data") }}</option>')
                                .prop('disabled', true)
                                .trigger('change');

                            $('#appointment_hint').text('لا توجد مواعيد متاحة في هذا التاريخ.');
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                        resetAppointments('{{ __("admin.no_data") }}');
                        $('#appointment_hint').text('حدث خطأ أثناء تحميل المواعيد.');
                    },
                    complete: function () {
                        hideGlobalStatus();
                    }
                });
            });

        });
    </script>
@endsection
