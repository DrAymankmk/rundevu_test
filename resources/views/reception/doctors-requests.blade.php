@extends('includes_admin.mainlayout')
@section('content')
<style>
    .success {
        background-color: #28C76F20;
    }

    .success i {
        color: #28C76F !important;
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
                            <li class="breadcrumb-item active">@lang('admin.reception.doctors_requests')</li>
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
                                            <h3>@lang('admin.reception.doctors_requests')</h3>
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

                            <!-- Filter -->
                            <div class="staff-search-table">
                                <form
                                    action="{{route('doctors-requests')}}"
                                    method="POST" enctype="multipart/form-data">
                                    {{ method_field('POST') }}
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="form-group local-forms cal-icon">
                                                <label>@lang('admin.From') </label>
                                                <input class="form-control datetimepicker" type="text" name="date_from" value="{{old('date_from')}}">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="form-group local-forms cal-icon">
                                                <label>@lang('admin.To') </label>
                                                <input class="form-control datetimepicker" type="text" name="date_to" value="{{old('date_to')}}"   >
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="form-group local-forms">
                                                <label for="doctor_id">@lang('admin.doctor_name') </label>
                                                <select class="form-control select" id="doctor_id" name="doctor_id">
                                                    <option selected disabled>@lang('admin.select')</option>
                                                    @foreach($data['doctors'] as $doctor)
                                                    <option value="{{ $doctor->id }}">{{$doctor->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-xl-4 ms-auto">
                                            <div class="doctor-submit">
                                                <button type="button" onclick="filter()" class="btn btn-primary submit-list-form me-2">@lang('admin.filter')</button>
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
                                            <th>@lang('admin.service_code')</th>
                                            <th>@lang('admin.service_name')</th>
                                            <th>@lang('admin.how_often')</th>
                                            <th>@lang('admin.price')</th>
                                            <th>@lang('admin.category_type')</th>
                                            <th>@lang('admin.doctor_name')</th>
                                            <th>@lang('admin.patient')</th>
                                            <th>@lang('admin.file_type')</th>
                                            <th>@lang('admin.file_number')</th>
                                            <th>@lang('admin.reception.options')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['patients_services'] as $index=>$service)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ app()->getLocale() == 'en' ? $service->services->name_en : $service->services->name_ar }}</td>
                                            <td>1</td>
                                            <td>{{ $service->price }} @lang('admin.SAR')</td>
                                            @if($service->type == 1)
                                            <td>@lang('admin.analysis')</td>
                                            @elseif($service->type == 2)
                                                <td>@lang('admin.rays')</td>
                                            @else
                                                <td>@lang('admin.service')</td>
                                            @endif
                                            <td>{{ $service->doctor->name ?? null }}</td>
                                            <td class="profile-image"><a href="{{ route('patient-file', $service->reservation_id) }}"><img width="28" height="28" src="{{ $service->user->image ?? null }}" class="rounded-circle m-r-5" alt=""> {{ $service->user->name ?? null }}</a></td>
                                            <td>@if($service->user->company_id != null)   @lang('admin.insurance') @else  @lang('admin.cash')@endif</td>
                                            <td><a href="{{ route('patient-file', $service->reservation_id) }}">{{ $service->user->file_number }}</a></td>
                                            <td class="text-end">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        @if($service->confirm == 0)
                                                        <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#confirm_service{{$service->id}}" data-service-id="{{$service->id}}" title="Confirm"><i class="feather-check-circle m-r-5"></i> @lang('admin.confirm')</a>
                                                        @endif
                                                            @if($service->status == 0)
                                                        <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal"   data-bs-target="#transfer_service{{$service->id}}"
                                                           data-service-id="{{$service->id}}" title="Transfer"><i class="fa fa-right-left m-r-5"></i>  @lang('admin.transfer')</a>
                                                            @endif
                                                            <a class="dropdown-item" href="{{ route('create-invoice', $service->reservation_id) }}"><i class="fa-regular fa-credit-card m-r-5"></i> @lang('admin.pay')</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <!-- Confirm Modal -->
                                            <div class="modal custom-modal modal-bg fade"
                                                 id="confirm_service{{$service->id}}" role="dialog">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <div class="form-header">
                                                                <h3>@lang('admin.confirm')</h3>
                                                                <p>@lang('admin.Are you sure you want to confirm?')</p>
                                                            </div>
                                                            <div class="modal-btn delete-action">
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <a href="javascript:void(0);"
                                                                           data-service-id="{{$service->id}}"
                                                                           class="btn btn-primary paid-continue-btn">@lang('admin.confirm')</a>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <a href="javascript:void(0);"
                                                                           data-bs-dismiss="modal"
                                                                           class="btn btn-primary paid-cancel-btn">@lang('admin.cancel')</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Confirm Modal End -->

                                            <!-- Transfer Modal -->
                                            <div class="modal custom-modal modal-bg fade" id="transfer_service{{$service->id}}"
                                                 role="dialog">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <div class="form-header">
                                                                <h3>@lang('admin.transfer')</h3>
                                                                <p>@lang('admin.Are you sure you want to transfer?')</p>
                                                            </div>
                                                            <div class="modal-btn delete-action">
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <a href="javascript:void(0);"
                                                                           data-service-id="{{$service->id}}"
                                                                           class="btn btn-primary transfer-continue-btn">@lang('admin.transfer')</a>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <a href="javascript:void(0);"
                                                                           data-bs-dismiss="modal"
                                                                           class="btn btn-primary paid-cancel-btn">@lang('admin.cancel')</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Transfer Modal End -->
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
        function filter() {
            document.getElementsByClassName('table-loader')[0].style.display = 'flex';
            setTimeout(function () {
                document.getElementsByClassName('table-loader')[0].style.display = 'none';
            }, 3000)
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
