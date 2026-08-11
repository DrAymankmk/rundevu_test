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
                            <li class="breadcrumb-item active p-0">@lang('admin.reception.file_room')</li>
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
                                            <h3>@lang('admin.reception.file_room')</h3>
                                            <div class="doctor-search-blk">
                                                <div class="top-nav-search table-search-blk">
                                                    <!-- <form> -->
                                                    <input onkeyup="search(event)" type="text" class="form-control" placeholder="@lang('admin.search_here')" id="searchInput">
                                                    <a class="btn" onclick="performSearch()"><img src="/assets/img/icons/search-normal.svg" alt=""></a>
                                                    <!-- </form> -->
                                                </div>
                                                <div class="add-group">
                                                    <a href="{{ route('add-patient') }}" class="btn btn-primary add-pluss"><img src="/assets/img/icons/plus.svg" alt=""></a>
                                                    <a href="javascript:;" class="btn btn-primary doctor-refresh"><img src="/assets/img/icons/re-fresh.svg" alt=""></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end py-2 ms-auto download-grp">
                                        <a href="javascript:;"><img src="/assets/img/icons/pdf-icon-01.svg" alt=""></a>
                                        <a href="javascript:;"><img src="/assets/img/icons/pdf-icon-02.svg" alt=""></a>
                                        <a href="javascript:;"><img src="/assets/img/icons/pdf-icon-03.svg" alt=""></a>
                                        <a href="javascript:;"><img src="/assets/img/icons/pdf-icon-04.svg" alt=""></a>
                                    </div>
                                </div>
                            </div>
                            <!-- /Table Header -->

                            <div class="position-relative">
                                <div class="table-loader">
                                    <div class="spinner"></div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table border-0 custom-table comman-table datatable mb-0">
                                        <thead>
                                        <tr>
                                            <th>@lang('admin.reception.ID_number') </th>
                                            <th>@lang('admin.reception.patient') </th>
                                            <th>@lang('admin.age') </th>
                                            <th>@lang('admin.phone') </th>
                                            <th>@lang('admin.patient_nationality') </th>
                                            <th>@lang('admin.reception.city') </th>
                                            <th>@lang('admin.gender') </th>
                                            <th>@lang('admin.reception.options') </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($patients as $key=>$patient)
                                        <tr>
                                            <td>{{ $patient->ID_Number }}  </td>
                                            <td class="profile-image"><a href="{{ route('edit-patient', $patient->id) }}"><img width="28" height="28" src="{{$patient->image}}" class="rounded-circle m-r-5" alt="">
                                                {{ $patient->name }}</a></td>
                                            <td>{{ \Carbon\Carbon::parse($patient->dob)->diff(\Carbon\Carbon::now())->y  }}</td>
                                            <td href="tel:{{ $patient->phone }}">{{ $patient->phone  }}</td>
                                           <td>{{ app()->getLocale() == 'en' ?  $patient->nationality->name_en : $patient->nationality->name_ar  }}</td>
                                           <td>{{ app()->getLocale() == 'en' ?  $patient->city->name_en : $patient->city->name_ar  }}</td>
                                           <td>{{  $patient->gender == 1 ? trans('admin.male') : trans('admin.female')  }}</td>
                                            <td class="text-end">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="{{ route('edit-patient', $patient->id) }}"><i class="fa fa-pen-to-square m-r-5"></i> @lang('admin.edit')</a>
{{--                                                        <a class="dropdown-item" href="{{ route('attachments',$patient->id) }}"><i class="fa fa-file m-r-5"></i> @lang('admin.reception.attachments_list')</a>--}}
{{--                                                        <a class="dropdown-item" href="{{route('patient-report', [$patient->id,2])}}"><i class="fa fa-newspaper m-r-5"></i> التقرير الطبي</a>--}}
{{--                                                        <a class="dropdown-item" href="{{ route('invoices', $patient->id) }}"><i class="fa fa-file-invoice m-r-5"></i> @lang('admin.reception.invoices')</a>--}}
{{--                                                        <a class="dropdown-item" href="{{route('bonds', $patient->id)}}"><i class="fa fa-file-invoice-dollar m-r-5"></i> @lang('admin.reception.bonds')</a>--}}
                                                        <a class="dropdown-item" href="{{ route('appointments', $patient->id) }}"><i class="fa fa-calendar-days m-r-5"></i> @lang('admin.reception.appointments_list')</a>
{{--                                                        <a class="dropdown-item" onclick="generateQrCode('{{$patient->patient_card}}', '{{ $patient->ID_Number ?? null }}')" href="#" data-bs-toggle="modal" data-bs-target="#patient_card_{{$patient->id}}"><i class="fa fa-id-card m-r-5"></i> @lang('admin.patient_card')</a>--}}
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#send_message"><i class="fa fa-message m-r-5"></i> @lang('admin.send_notification')</a>
                                                        <a class="dropdown-item" href="{{ route('services', $patient->id) }}"><i class="fa fa-clipboard-check m-r-5"></i> @lang('admin.services')</a>
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_patient"><i class="fa fa-trash-alt m-r-5"></i> @lang('admin.delete')</a>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Patient Card Modal -->
                                            <div class="modal custom-modal modal-bg fade bank-details" id="patient_card_{{$patient->id}}" role="dialog"> <!-- invoices-preview -->
                                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                                    <div class="modal-content">
                                                        <div class="modal-header py-2 px-3">
                                                            <div class="form-header text-start mb-0">
                                                                <h4 class="mb-0">@lang('admin.patient_card')</h4>
                                                            </div>
                                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body pt-1 px-0 border-0">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="card invoice-info-card">
                                                                        <div class="card-body pb-0">
                                                                            <!-- Card Info -->
                                                                            <div class="card-box profile-header text-start">
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <div class="profile-view">
                                                                                            <div class="profile-img-wrap">
                                                                                                <div class="profile-img">
                                                                                                    <a href="#"><img class="avatar" src="{{ $patient->image ?? null }}" alt=""></a>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="profile-basic">
                                                                                                <div class="row">
                                                                                                    <div class="col-lg-5">
                                                                                                        <div class="profile-info-left">
                                                                                                            <h3 class="user-name m-t-0 mb-0">{{ $patient->name ?? null }}</h3>
                                                                                                            <!-- <small class="text-muted">Gynecologist</small> -->
                                                                                                            <div class="staff-id">@lang('admin.patient_number'):
                                                                                                                {{ $patient->ID_Number ?? null }}</div>
                                                                                                            <!-- <div class="staff-msg"><a href="chat.html" class="btn btn-primary">Send Message</a></div> -->
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col-lg-7">
                                                                                                        <ul class="personal-info">
                                                                                                            <li class="d-xl-flex d-block">
                                                                                                                <span class="title">@lang('admin.phone'):</span>
                                                                                                                <span class="text"><a href=""> {{ $patient->phone ?? null }}</a></span>
                                                                                                            </li>
                                                                                                            <li class="d-xl-flex d-block">
                                                                                                                <span class="title">@lang('admin.email'):</span>
                                                                                                                <span class="text"><a href=""> {{ $patient->email ?? null }}</a></span>
                                                                                                            </li>
                                                                                                            <li class="d-xl-flex d-block">
                                                                                                                <span class="title">@lang('admin.dob'):</span>
                                                                                                                <span class="text"> {{ $patient->dob ?? null }}</span>
                                                                                                            </li>
                                                                                                            <li class="d-xl-flex d-block">
                                                                                                                <span class="title">@lang('admin.address'):</span>
                                                                                                                <span class="text"> {{ $patient->address ?? null }}</span>
                                                                                                            </li>
                                                                                                            <li class="d-xl-flex d-block">
                                                                                                                <span class="title">@lang('admin.gender'):</span>
                                                                                                                <span class="text"> {{ app()->getLocale() == 'en' ? trans('admin.male') : trans('admin.female') }}</span>
                                                                                                            </li>
                                                                                                            <li class="d-xl-flex d-block">
                                                                                                                <span class="title">@lang('admin.subscription_end'):</span>
                                                                                                                <span class="text"> {{ $patient->expired_date ?? null }}</span>
                                                                                                            </li>
                                                                                                        </ul>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!-- Card Info End -->

                                                                            <!-- Card QRcode -->
                                                                            <div class="card-box profile-header text-start m-t-20">
                                                                                <div class="row">
                                                                                    <div id="qrCodeContainer" class="col-10 col-lg-5 m-auto p-3 d-flex justify-content-center rounded-3">
                                                                                        <div class="rounded-1" style="border: 2px solid #fff; padding: 6px;" id="qrcode"></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!-- Card QRcode End -->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Patient Card Modal End -->
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



    <!-- Send Message Modal -->
    <div class="modal custom-modal modal-bg fade bank-details" id="send_message" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header py-2 px-3">
                    <div class="form-header text-start mb-0">
                        <h4 class="mb-0">أرسال رسالة/إشعار</h4>
                    </div>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-start py-4 px-3">
                    <div class="bank-inner-details">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label>العنوان <span class="login-danger">*</span></label>
                                    <input class="form-control" placeholder="العنوان"></input>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms mb-0">
                                    <label>النص <span class="login-danger">*</span></label>
                                    <textarea class="form-control" style="min-height: 150px;" placeholder="أدخل النص هنا" rows="6" cols="30"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-3">
                    <div class="bank-details-btn">
                        <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn bank-cancel-btn me-2">إلغاء</a>
                        <a href="javascript:void(0);" class="btn bank-save-btn">أرسال</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Send Message Modal End -->

    <!-- Delete Patient Modal -->
    <div id="delete_patient" class="modal fade delete-modal" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <img src="assets/img/sent.png" alt="" width="50" height="46">
                    <h3>هل أنت متأكد أنك تريد حذف هذا؟</h3>
                    <div class="m-t-20"> <a href="#" class="btn btn-white" data-bs-dismiss="modal">أغلق</a>
                        <button type="submit" class="btn btn-danger">حذف</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Patient Modal End -->

    <script>

        function performSearch() {
            const searchValue = document.getElementById('searchInput').value;
            window.location.href = '{{ route("patients") }}?search=' + encodeURIComponent(searchValue);
        }

        // للبحث التلقائي أثناء الكتابة (Auto-search)
        let searchTimer;
        function search(e) {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function() {
                performSearch();
            }, 1000); // البحث بعد ثانية من التوقف عن الكتابة
        }

        // function search(e) {
        //     if (e.key === 'Enter' || e.keyCode === 13) {
        //         document.getElementsByClassName('table-loader')[0].style.display = 'flex';
        //         setTimeout(function () {
        //             document.getElementsByClassName('table-loader')[0].style.display = 'none';
        //         }, 3000)
        //     }
        // }

        function generateQrCode(type, value) {
            document.getElementById("qrcode").innerHTML = ''
            if (type === 'valid') {
                document.getElementById('qrCodeContainer').style.backgroundColor = '#28C76F'
                new QRCode(document.getElementById("qrcode"), {
                    text: value,
                    width: 120,
                    height: 120,
                    colorDark : "#fff",
                    colorLight : "#28C76F",
                    correctLevel : QRCode.CorrectLevel.H
                });
            } else {
                document.getElementById('qrCodeContainer').style.backgroundColor = '#EA5455'
                new QRCode(document.getElementById("qrcode"), {
                    text: value,
                    width: 120,
                    height: 120,
                    colorDark : "#fff",
                    colorLight : "#EA5455",
                    correctLevel : QRCode.CorrectLevel.H
                });
            }
        }
    </script>
@endsection
