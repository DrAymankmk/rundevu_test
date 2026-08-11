@extends('includes_admin.mainlayout')
@section('content')

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
                            <li class="breadcrumb-item active">{{ $data['title'] }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">

                    <div class="card card-table show-entire">
                        <div class="card-body">

                            <form method="POST" action="{{route('create-patient-service-file', $reservation_id)}}"
                                  class="invoices-form">
                                @csrf
                                <input type="hidden" name="lang" id="lang">
                                <input type="hidden" name="type" value="{{$data['type']}}">
                                <div class="invoice-add-table">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-nowrap  mb-0 no-footer add-table-items">
                                            <thead>
                                            <tr>
                                                <th>{{ $data['title'] }}</th>
                                                <th>@lang('admin.notes')</th>
                                                <th>@lang('admin.action')</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr class="add-row">
                                                <td>
                                                    <select class="form-control select" name="service_id[]" required>
                                                        @foreach($data['services'] as $service)
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
                                                    @if(count($data['services']) > 0)
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
                                            type="submit">@lang('admin.confirm')</button>
                                </div>
                                <br>
                            </form>

                        </div>

                    </div>
                </div>


                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table border-0 custom-table comman-table datatable mb-0">
                            <thead>
                            <tr>
                                <th>@lang('admin.service_name')</th>
                                <th>@lang('admin.patient_name')</th>
                                <th> @lang('admin.patient_type') </th>
                                <th> @lang('admin.service_color') </th>
                                <th> @lang('admin.insurance') </th>
                                <th> @lang('admin.price') </th>
                                <th> @lang('admin.cancel') </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data['patient_services'] as $service)
                                <tr>
                                    <td>{{$lang == 'en' ? $service->services->name_en : $service->services->name_ar}}</td>
                                    <td>{{$service->user->name}}</td>
                                    <td>@lang('admin.cash')</td>
                                    <td style="color: #f00">
                                        <div class="circle" style="width: 20px;
            height: 20px;
            background-color: red;
            border-radius: 50%; /* This creates the circular shape */"></div>
                                    </td>
                                    <td>---</td>
                                    <td>{{ $service->price }}</td>

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
                <hr>
                <br>

            </div>


        </div>
    </div>
    <script src="/assets/js/jquery-3.6.1.min.js"></script>
    <script>

        $(document).ready(function ($) {
            $(document).on("click", ".add-btn", function () {

                var experiencecontent = '<tr class="add-row">' +
                    '<td>' +
                    '<select class="form-control select" name="service_id[]" required>' +
                    @foreach($data['services'] as $service)
                        '<option value="{{ $service->id }}">{{ $service->name }}</option>' +
                    @endforeach
                        '</select>' +
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
        });
    </script>
@endsection
