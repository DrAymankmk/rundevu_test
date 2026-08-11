@extends('includes_admin.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard') </a></li>
                            {{--                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>--}}
                            <li class="breadcrumb-item px-2"><i id="breadcrumbArrow"></i></li>
                            <li class="breadcrumb-item active">
                                @lang('insurance.insurance_dashboard')
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            @php
                $currentTime = \Carbon\Carbon::now();
                $currentHour = $currentTime->hour;
                $greeting = '';
                if ($currentHour >= 0 && $currentHour < 12) {
                    $greeting = trans('admin.Good Morning');
                } elseif ($currentHour >= 12 && $currentHour < 18) {
                      $greeting = trans('admin.Good Afternoon');
                } elseif ($currentHour >= 18 && $currentHour < 24) {
                      $greeting = trans('admin.Good Evening');
                }
            @endphp

            <div class="good-morning-blk">
                <div class="row">
                    <div class="col-md-6">
                        <div class="morning-user">
                            <h2>{{ $greeting }}, <span>{{ auth()->user()->name ?? null }}</span></h2>
                            <p>@lang('admin.nice_day')</p>
                        </div>
                    </div>
                    <div class="col-md-6 position-blk">
                        <div class="morning-img">
                            <img src="/assets/img/morning-img-01.png" alt="">
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
                                <div class="row align-items-center gap-2 d-md-flex d-block">
                                    <div class="col">
                                        <div class="doctor-table-blk">
                                            <h3>@lang('insurance.insurance_approvals')</h3>
                                            <div class="doctor-search-blk">
                                                <div class="top-nav-search table-search-blk">
                                                    <!-- <form> -->
                                                    <input onkeyup="search(event)" type="text" class="form-control" placeholder="ابحث هنا">
                                                    <a class="btn"><img src="/assets/img/icons/search-normal.svg" alt=""></a>
                                                    <!-- </form> -->
                                                </div>
                                                <div class="add-group">
                                                    <!-- <a href="add-appointment.html" class="btn btn-primary add-pluss"><img src="assets/img/icons/plus.svg" alt=""></a> -->
                                                    <a href="javascript:;" class="btn btn-primary doctor-refresh"><img src="/assets/img/icons/re-fresh.svg" alt=""></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Table Header -->
                            <div class="staff-search-table">
                                <form
                                    action="{{route('admin.dashboard')}}"
                                    method="POST" enctype="multipart/form-data">
                                    {{ method_field('POST') }}
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="form-group local-forms cal-icon">
                                                <label>@lang('admin.From') </label>
                                                <input class="form-control datetimepicker" id="date_from" type="text" name="date_from" value="{{old('date_from')}}">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="form-group local-forms cal-icon">
                                                <label>@lang('admin.To') </label>
                                                <input class="form-control datetimepicker" id="date_to" type="text" name="date_to" value="{{old('date_to')}}" required  >
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6 col-xl-4 ms-auto">
                                            <div class="doctor-submit">
                                                <button type="button" onclick="filter()" class="btn btn-primary submit-list-form me-2">@lang('admin.filter')</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>


                                <div class="position-relative">
                                    <div class="table-loader">
                                        <div class="spinner"></div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table border-0 custom-table comman-table datatable mb-0">
                                            <thead>
                                            <tr>
                                                <th>@lang('admin.patient')</th>
                                                <th>@lang('insurance.attachment')</th>
                                                <th>@lang('insurance.financial_statement')</th>
                                                <th>@lang('admin.date')</th>
                                                <th>@lang('insurance.clinic')</th>
                                                <th>@lang('admin.doctor_account')</th>
                                                <th>@lang('admin.status')</th>
                                                <th>@lang('admin.options')</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($data['insurance_approvals'] as $index=>$patient)
                                                <tr>
                                                    <td>{{$patient->user->name ?? null}}</td>
                                                    <td><a href="#">@lang('insurance.attachment')</a></td>
                                                    <td><a href="{{ route('insurance-approvals.show',$patient->id) }}">@lang('insurance.financial_statement')</a></td>
                                                    <td>{{ date('Y-m-d',strtotime($patient->created_at)) }}</td>
                                                    <td>{{ $patient->clinic->name ?? null }}</td>
                                                    <td>{{ $patient->doctor->name ?? null }}</td>
                                                    <td><i class="fa fa-circle-check text-info font-18"></i></td>
                                                    <td class="text-end">
                                                        <div class="dropdown dropdown-action">
                                                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a href="{{ route('insurance-approvals.show',$patient->id) }}" class="dropdown-item" type="button"><i class="fa-solid fa-eye m-r-5"></i> @lang('admin.show')</a>
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
                            {{--                            {{ $data['patients_waiting']->links() }}--}}
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <script src="{{asset('/admin/js/jquery-3.2.1.min.js')}}"></script>

    <script>
        function search(e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                document.getElementsByClassName('table-loader')[0].style.display = 'flex';
                setTimeout(function () {
                    document.getElementsByClassName('table-loader')[0].style.display = 'none';
                }, 3000)
            }
        }
        // function filter() {
        //     document.getElementsByClassName('table-loader')[0].style.display = 'flex';
        //     setTimeout(function () {
        //         document.getElementsByClassName('table-loader')[0].style.display = 'none';
        //     }, 3000)
        // }

        function filter() {
            var fromDate = $('#date_from').val();
            var toDate = $('#date_to').val();
            var doctorId = $('#doctor_id').val();
            var lang = '{{ app()->getLocale() == 'en' }}';
            $.ajax({
                url: '{{ route('doctors-requests.filter') }}',
                type: 'get',
                data: {
                    date_from: fromDate,
                    date_to: toDate,
                    doctor_id: doctorId,
                },
                success: function(response) {
                    document.getElementsByClassName('table-loader')[0].style.display = 'flex';
                    setTimeout(function () {
                        document.getElementsByClassName('table-loader')[0].style.display = 'none';
                    }, 3000)
                    var tableBody = $('#doctorRequestsTable tbody');
                    tableBody.empty();
                    $.each(response.patients_services, function(index, service) {

                        var create_invoice_route = '{{ route('create-invoice', ':id') }}';
                        var create_invoice_url = '{{ route('create-invoice', '') }}';
                        {{--var create_invoice_url = '{{ route('create-invoice', '') }}/' + service.reservation_id;--}}


                        var newRow = '<tr>' +
                            '<td>' + (index + 1) + '</td>' +
                            '<td>' + (lang ? service.services.name_en : service.services.name_ar) + '</td>' +
                            '<td>1</td>' +
                            '<td>' + service.price + ' @lang('admin.SAR')</td>' +
                            '<td>' + (service.type == 1 ? '@lang('admin.analysis')' : (service.type == 2 ? '@lang('admin.rays')' : '@lang('admin.service')')) + '</td>' +
                            '<td>' + (service.doctor ? service.doctor.name : null) + '</td>' +
                            '<td class="profile-image"><a href="' + service.user.file_number + '">' +
                            '<img width="28" height="28" src="' + (service.user.image ? service.user.image : null) + '" class="rounded-circle m-r-5" alt=""> ' +
                            (service.user.name ? service.user.name : null) + '</a></td>' +
                            '<td>' + (service.user.company_id != null ? '@lang('admin.insurance')' : '@lang('admin.cash')') + '</td>' +
                            '<td><a href="' + service.user.file_number + '">' + service.user.file_number + '</a></td>' +
                            '<td class="text-end">' +
                            '<div class="dropdown dropdown-action">' +
                            '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>' +
                            '<div class="dropdown-menu dropdown-menu-end">' +
                            (service.confirm == 0 ? '<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#confirm_service' + service.id + '" data-service-id="' + service.id + '" title="Confirm"><i class="feather-check-circle m-r-5"></i> @lang('admin.confirm')</a>' : '') +
                            (service.status == 0 ? '<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#transfer_service' + service.id + '" data-service-id="' + service.id + '" title="Transfer"><i class="fa fa-right-left m-r-5"></i> @lang('admin.transfer')</a>' : '') +
                            '<a class="dropdown-item" href="' + create_invoice_url + '"><i class="fa-regular fa-credit-card m-r-5"></i> @lang('admin.pay')</a>' +
                            '</div>' +
                            '</div>' +
                            '</td>' +
                            '</tr>';
                        tableBody.append(newRow);
                    });
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        }

        $(document).ready(function () {

            $('.paid-continue-btn').click(function (e) {
                e.preventDefault();

                var serviceId = $(this).data('service-id'); // Get the service ID from the data attribute
                // Retrieve CSRF token from meta tag
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                // AJAX request
                $.ajax({
                    url: '/admin/confirm-service/' + serviceId, // Properly format the URL
                    type: 'POST', // Specify the type of request (GET or POST)
                    dataType: 'json', // Specify the data type of the response
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

                        $('#confirm_service' + serviceId).modal('hide');
                        // Hide the anchor element upon successful confirmation
                        $('.add-table-invoice.success[data-service-id="' + serviceId + '"]').hide();


                        // Handle the success response here
                        console.log(response);
                        // Optionally, you can reload the page or update the UI based on the response
                    },
                    error: function (xhr, status, error) {
                        // Handle errors here
                        console.error(xhr.responseText);
                    }
                });
            });
            $('.transfer-continue-btn').click(function (e) {
                e.preventDefault();

                var serviceId = $(this).data('service-id'); // Get the service ID from the data attribute
                // Retrieve CSRF token from meta tag
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                // AJAX request
                $.ajax({
                    url: '/admin/transfer-service/' + serviceId, // Properly format the URL
                    type: 'POST', // Specify the type of request (GET or POST)
                    dataType: 'json', // Specify the data type of the response
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

                        $('#transfer_service' + serviceId).modal('hide');
                        // Hide the anchor element upon successful confirmation
                        $('.transfer_service.success[data-service-id="' + serviceId + '"]').hide();

                        // Handle the success response here
                        console.log(response);
                        // Optionally, you can reload the page or update the UI based on the response
                    },
                    error: function (xhr, status, error) {
                        // Handle errors here
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection
