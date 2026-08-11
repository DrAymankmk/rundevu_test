@extends('includes_admin.mainlayout')
@section('content')

    <style>
        .delete {
            background-color: #ea545520;
        }

        .delete i {
            color: #ea5455 !important;
        }

        .complete-btn {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
        }

        .complete-btn i {
            color: white !important;
        }
        .select2-search__field {
            width: 100% !important;
        }


        .delete {
            background-color: #ea545520;
        }

        .delete i {
            color: #ea5455 !important;
        }

        .complete-btn {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
        }

        .complete-btn i {
            color: white !important;
        }

        .select2-container .select2-selection--single {
            height: 45px !important;
            padding: 8px !important;
        }
        .select2-selection__rendered {
            line-height: 28px !important;
        }
        .select2-search__field {
            width: 100% !important;
            padding: 8px !important;
        }
        .selected-row {
            background-color: #f8f9fa !important;
            box-shadow: 0 0 0 1px #dee2e6;
        }

    </style>

    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('patients') }}">@lang('admin.reception.reception') </a></li>
                            <li class="breadcrumb-item px-2"><i id="breadcrumbArrow"></i></li>
                            <li class="breadcrumb-item active">@lang('admin.reception.appointments_list')</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table show-entire">
                        <div class="card-body">
                            <!-- Table Header -->
                            <div class="page-table-header mb-2">
                                <div class="row align-items-center gap-2 d-md-flex d-block">
                                    <div class="col">
                                        <div class="doctor-table-blk">
                                            <h3>@lang('admin.reception.appointments_list')</h3>
                                            <div class="doctor-search-blk">
                                                <div class="top-nav-search table-search-blk">
                                                    <form id="searchForm" method="GET" action="{{ route('appointments') }}">
                                                        <input onkeyup="search(event)" type="text" class="form-control"
                                                               placeholder="@lang('admin.search_with_phone')"
                                                               name="search" value="{{ request('search') }}">
                                                        <a class="btn" onclick="document.getElementById('searchForm').submit()">
                                                            <img src="/assets/img/icons/search-normal.svg" alt="">
                                                        </a>
                                                    </form>
                                                </div>
                                                <div class="add-group">
                                                    <a href="{{ route('add-appointment') }}"
                                                       class="btn btn-primary add-pluss"><img
                                                            src="/assets/img/icons/plus.svg" alt=""></a>
                                                    <a href="javascript:;" class="btn btn-primary doctor-refresh"><img
                                                            src="/assets/img/icons/re-fresh.svg" alt=""></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Table Header -->

                            <!-- Filter -->
                            <!-- Filter -->
                            <div class="staff-search-table">
                                <form id="filterForm" method="GET" action="{{ route('appointments') }}"> <!-- أضف id هنا -->
                                    <div class="row">
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="form-group local-forms">
                                                <label for="patient">@lang('admin.patient_name') <span class="login-danger">*</span></label>
                                                <select class="form-control my-select2" name="patient_id" id="patientSelect">
                                                    <option value="">@lang('admin.select_patient')</option>
                                                    @foreach($data['patients'] as $patient)
                                                        <option value="{{ $patient->id }}"
                                                            {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                                                            {{ $patient->name }} @if($patient->phone) - {{ $patient->phone }} @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="form-group local-forms">
                                                <label>@lang('admin.doctor_name')</label>
                                                <select class="form-control my-select2" name="doctor_id" id="doctorSelect">
                                                    <option value="">@lang('admin.select_doctor')</option>
                                                    @foreach($data['doctors'] as $doctor)
                                                        <option value="{{ $doctor->id }}"
                                                            {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                                            {{ $doctor->name }}
                                                            @if($doctor->specialties && $doctor->specialties->first())
                                                                ({{ app()->getLocale() == 'en' ?
                                    $doctor->specialties->first()->specialties->name_en ?? '' :
                                    $doctor->specialties->first()->specialties->name_ar ?? '' }})
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="form-group local-forms cal-icon">
                                                <label>@lang('admin.From')</label>
                                                <input class="form-control datetimepicker" type="text" name="date_from"
                                                       value="{{ request('date_from') }}">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="form-group local-forms cal-icon">
                                                <label>@lang('admin.To') </label>
                                                <input class="form-control datetimepicker" type="text" name="date_to"
                                                       value="{{ request('date_to') }}">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="doctor-submit">
                                                <button type="button" onclick="applyFilter()"
                                                        class="btn btn-primary submit-list-form me-2">@lang('admin.filter')</button>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="doctor-submit">
                                                @if(request()->anyFilled(['patient_id', 'doctor_id', 'date_from', 'date_to', 'search']))
                                                    <a href="{{ route('appointments') }}" class="btn btn-secondary">
                                                        @lang('admin.clear')
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- Filter End -->

                            <div class="position-relative">
                                <div class="table-loader">
                                    <div class="spinner"></div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table border-0 custom-table comman-table datatable mb-0">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('admin.reservation_number')</th>
                                            <th>@lang('admin.date')</th>
                                            <th>@lang('admin.time')</th>
                                            <th>@lang('admin.specialize')</th>
                                            <th>@lang('admin.doctor_name')</th>
                                            <th>@lang('admin.patient')</th>
                                            <th>@lang('admin.file_type')</th>
                                            <th>@lang('admin.status')</th>
                                            <th>@lang('admin.reception.options')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['reservations'] as $index=>$reservation)
                                            <tr>
                                                <td>{{ $index  + 1 }}</td>
                                                <td>{{ $reservation->booking_number }}</td>
                                                <td>{{ $reservation->date }}</td>
                                                <td>{{ $reservation->appointment }}</td>
                                                <td>
                                                    {{ $reservation->doctor && $reservation->doctor->specialties
                                                        ? $reservation->doctor->specialties->map(function ($special) {
                                                            return app()->getLocale() == 'en'
                                                                ? ($special->specialties->name_en ?? null)
                                                                : ($special->specialties->name_ar ?? null);
                                                        })->filter()->join(', ')
                                                        : '' }}
                                                </td>
                                                <td>{{ $reservation->doctor->name }}</td>
                                                <td class="profile-image"><a
                                                        href="{{ route('appointments',$reservation->user_id) }}"><img
                                                            width="28" height="28"
                                                            src="{{ $reservation->user->image ?? null }}"
                                                            class="rounded-circle m-r-5"
                                                            alt="">{{ $reservation->user->name ?? null }}</a></td>
                                                <td>@if($reservation->user->company_id != null)
                                                        @lang('admin.insurance')
                                                    @else
                                                        @lang('admin.cash')
                                                    @endif</td>

                                                <td>{{ app()->getLocale() == 'en' ? $reservation->reservation_status->name_en ?? null : $reservation->reservation_status->name_ar ?? null }}</td>

                                                <td class="text-end">
                                                    <div class="dropdown dropdown-action">
                                                        <a href="#" class="action-icon dropdown-toggle"
                                                           data-bs-toggle="dropdown" aria-expanded="false"><i
                                                                class="fa fa-ellipsis-v"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" type="button" href="{{ route('booking.chat', $reservation->id) }}"><i class="fa-solid fa-message m-r-5"></i> @lang('admin.chat')</a>
                                                            @if($reservation->status_id == 1)
                                                                <a  href="javascript:void(0)" onclick="selectRow(event)"
                                                                    class="dropdown-item" data-bs-toggle="modal"
                                                                    data-bs-target="#cancel_appointment_{{$reservation->id}}"
                                                                    type="button"><i class="fa-solid fa-cancel m-r-5"></i> @lang('admin.cancel')
                                                                </a>
                                                            @endif
                                                            @if($reservation->status_id == 2)
                                                                <a  href="javascript:void(0)" onclick="selectRow(event)"
                                                                    class="dropdown-item" data-bs-toggle="modal"
                                                                    data-bs-target="#waitingList_appointment_{{$reservation->id}}"
                                                                    type="button"><i class="fa-solid fa-list-ul m-r-5"></i> @lang('admin.doctor.waiting_list')
                                                                </a>
                                                            @endif
{{--                                                            @if($reservation->status_id == 1)--}}
{{--                                                                <a class="dropdown-item" href="{{ route('create-invoice-reservation', $reservation->id) }}"><i class="fa-regular fa-credit-card m-r-5"></i> @lang('admin.pay')</a>--}}
{{--                                                            @endif--}}

                                                            <!-- زر إنهاء الحجز -->
                                                            @if(in_array($reservation->status_id, [1, 2, 3]))
                                                                <a href="javascript:void(0)" onclick="selectRow(event)"
                                                                   class="dropdown-item" data-bs-toggle="modal"
                                                                   data-bs-target="#complete_appointment_{{$reservation->id}}"
                                                                   type="button"><i class="fa-solid fa-check-circle m-r-5 text-success"></i> @lang('admin.complete_reservation')
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Modal إلغاء الحجز -->
                                                    <div id="cancel_appointment_{{$reservation->id}}"
                                                         class="modal fade delete-modal" role="dialog">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-body text-center">
                                                                    <img src="/assets/img/sent.png" alt="" width="50"
                                                                         height="46">
                                                                    <h3>@lang('admin.cancel_reservation_from_list')</h3>
                                                                    <div class="m-t-20"><a href="#"
                                                                                           class="btn btn-white"
                                                                                           data-bs-dismiss="modal">@lang('admin.close')</a>
                                                                        <button
                                                                            onclick="cancelAppointment({{ $reservation->id ?? null }})"
                                                                            type="submit"
                                                                            class="btn btn-danger">@lang('admin.Yes')</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Modal قائمة الانتظار -->
                                                    <div id="waitingList_appointment_{{$reservation->id}}"
                                                         class="modal fade delete-modal" role="dialog">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-body text-center">
                                                                    <img src="/assets/img/sent.png" alt="" width="50"
                                                                         height="46">
                                                                    <h3>@lang('admin.waitingList_reservation')</h3>
                                                                    <div class="m-t-20"><a href="#"
                                                                                           class="btn btn-white"
                                                                                           data-bs-dismiss="modal">@lang('admin.close')</a>
                                                                        <button type="submit" onclick="WaitingListAppointment({{ $reservation->id ?? null }})"
                                                                                class="btn btn-danger">@lang('admin.Yes')</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Modal إنهاء الحجز -->
                                                    <div id="complete_appointment_{{$reservation->id}}"
                                                         class="modal fade delete-modal" role="dialog">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-body text-center">
                                                                    <img src="/assets/img/sent.png" alt="" width="50"
                                                                         height="46">
                                                                    <h3>@lang('admin.complete_reservation_confirm')</h3>
                                                                    <div class="m-t-20">
                                                                        <a href="#" class="btn btn-white"
                                                                           data-bs-dismiss="modal">@lang('admin.close')</a>
                                                                        <button onclick="completeAppointment({{ $reservation->id ?? null }})"
                                                                                type="submit"
                                                                                class="btn btn-success">@lang('admin.Yes')</button>
                                                                    </div>
                                                                </div>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- إضافة Select2 CSS و JS -->
    <link href="/admin/css/select2.css" rel="stylesheet" />
    <script src="/reception/assets/js/jquery.js"></script>
    <script src="/reception/assets/js/select2.js"></script>

    <script>

        $(document).ready(function() {
            // تهيئة select2 مع بحث للمرضى
            $('#patientSelect').select2({
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

            $('#doctorSelect').select2({
                placeholder: "@lang('admin.search_doctor_name')",
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

            $('#patientSelect, #doctorSelect').select2({
                allowClear: true,
                width: '100%'
            });

            // فلترة تلقائية عند تغيير Select2
            $('#patientSelect, #doctorSelect').on('change', function() {
                applyFilter();
            });


            // تهيئة datetimepicker
            if ($('.datetimepicker').length) {
                $('.datetimepicker').datetimepicker({
                    format: 'YYYY-MM-DD',
                    locale: '{{ app()->getLocale() }}',
                    useCurrent: false
                });
            }

            // إخفاء loader عند اكتمال تحميل الصفحة
            setTimeout(function() {
                $('.table-loader').hide();
            }, 500);
        });

        // دالة البحث التلقائي
        let searchTimeout;

        function search(e) {
            $('.table-loader').show();
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function () {
                document.getElementById('searchForm').submit();
            }, 800);
        }

        function applyFilter() {
            $('.table-loader').show();
            setTimeout(function () {
                document.getElementById('filterForm').submit();
            }, 300);
        }
        // function search(e) {
        //     if (e.key === 'Enter' || e.keyCode === 13) {
        //         document.getElementsByClassName('table-loader')[0].style.display = 'flex';
        //         setTimeout(function () {
        //             document.getElementsByClassName('table-loader')[0].style.display = 'none';
        //         }, 3000)
        //     }
        // }
        //
        // function filter() {
        //     document.getElementsByClassName('table-loader')[0].style.display = 'flex';
        //     setTimeout(function () {
        //         document.getElementsByClassName('table-loader')[0].style.display = 'none';
        //     }, 3000)
        // }

        function cancelAppointment(reservationId) {
            $.ajax({
                url: '{{ url('/admin/cancelReservation', ['id' => '']) }}/' + reservationId,
                type: 'DELETE',
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function (response) {
                    new Noty({
                        type: 'success',
                        layout: 'topRight',
                        text: response,
                        timeout: 3000,
                        killer: true
                    }).show();

                    document.getElementsByClassName('table-loader')[0].style.display = 'flex';
                    setTimeout(function () {
                        document.getElementsByClassName('table-loader')[0].style.display = 'none';
                    }, 3000)
                    $('#cancel_appointment_' + reservationId).modal('hide');
                    window.location.reload();
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        function WaitingListAppointment(reservationId) {
            $.ajax({
                url: '{{ url('/admin/WaitingListReservation', ['id' => '']) }}/' + reservationId,
                type: 'DELETE',
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function (response) {
                    new Noty({
                        type: 'success',
                        layout: 'topRight',
                        text: response,
                        timeout: 3000,
                        killer: true
                    }).show();

                    document.getElementsByClassName('table-loader')[0].style.display = 'flex';
                    setTimeout(function () {
                        document.getElementsByClassName('table-loader')[0].style.display = 'none';
                    }, 3000)
                    $('#waitingList_appointment_' + reservationId).modal('hide');
                    location.reload();
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        // دالة إنهاء الحجز
        function completeAppointment(reservationId) {
            const url = '/admin/completeReservation/' + reservationId;

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": "PUT" // إذا كان Route::post لكنك تريد PUT
                },
                success: function (response) {
                    new Noty({
                        type: 'success',
                        layout: 'topRight',
                        text: response.message || 'تم إنهاء الحجز بنجاح',
                        timeout: 3000,
                        killer: true
                    }).show();

                    $('#complete_appointment_' + reservationId).modal('hide');

                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', xhr.responseText);

                    let errorMessage = 'حدث خطأ في الاتصال';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.status === 404) {
                        errorMessage = 'الرابط غير موجود (404)';
                    }

                    new Noty({
                        type: 'error',
                        layout: 'topRight',
                        text: errorMessage,
                        timeout: 3000,
                        killer: true
                    }).show();
                }
            });
        }

        // دالة لتحديد الصف عند النقر على زر
        function selectRow(event) {
            var row = event.target.closest('tr');
            $('tr').removeClass('selected-row');
            $(row).addClass('selected-row');
        }
    </script>

    <style>
        .selected-row {
            background-color: #f8f9fa !important;
            box-shadow: 0 0 0 1px #dee2e6;
        }

        .text-success {
            color: #28a745 !important;
        }
    </style>

@endsection
