@extends('includes_admin.mainlayout')
@section('content')
    <style>
        .circle {
            text-align: center;
            width: 25px;
            height: 21px;
            border-radius: 50%; /* Make it a circle with 50% border-radius */
        }
    </style>
    <div class="page-wrapper">
        <div class="content">
            <!-- Page Header -->
{{--            <div class="page-header">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-sm-12">--}}
{{--                        <ul class="breadcrumb">--}}
{{--                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard') </a></li>--}}
{{--                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>--}}
{{--                            <li class="breadcrumb-item active">@lang('admin.doctor.waiting_list')</li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table show-entire">
                        <div class="card-body">

                            <!-- Table Header -->
                            <div class="page-table-header mb-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="doctor-table-blk">
                                            <h3>@lang('admin.doctor.waiting_list')</h3>
                                        </div>
                                    </div>
{{--                                    <div class="col-auto text-end float-end ms-auto download-grp">--}}
{{--                                        <a href="javascript:;" class=" me-2"><img--}}
{{--                                                src="/assets/img/icons/pdf-icon-01.svg" alt=""></a>--}}
{{--                                        <a href="javascript:;" class=" me-2"><img--}}
{{--                                                src="/assets/img/icons/pdf-icon-02.svg" alt=""></a>--}}
{{--                                        <a href="javascript:;" class=" me-2"><img--}}
{{--                                                src="/assets/img/icons/pdf-icon-03.svg" alt=""></a>--}}
{{--                                        <a href="javascript:;"><img src="/assets/img/icons/pdf-icon-04.svg" alt=""></a>--}}
{{--                                    </div>--}}
                                </div>
                            </div>
                            <!-- /Table Header -->
                            <div class="staff-search-table">
                                <form action="{{ route('patients-waiting') }}" method="get">
                                    <div class="row">
                                        <div class="col-12 col-md-4 col-xl-3">
                                            <div class="form-group local-forms cal-icon">
                                                <label>@lang('admin.From') </label>
                                                <input class="form-control datetimepicker" type="text" name="date_from">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4 col-xl-3">
                                            <div class="form-group local-forms cal-icon">
                                                <label>@lang('admin.To') </label>
                                                <input class="form-control datetimepicker" type="text" name="date_to">
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-4 col-xl-2">
                                            <div class="doctor-submit">
                                                <button type="submit"
                                                        class="btn btn-primary submit-list-form me-2">@lang('admin.Search')</button>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-4 col-xl-2">
                                            <label></label>
                                            <h5>@lang('admin.day') / @lang('admin.days_list.' . $dayName)  </h5>
                                        </div>
                                        <div class="col-12 col-md-4 col-xl-2">
                                            <label> </label>
                                            <h5>@lang('admin.date') / {{  $date_from }}</h5>
                                        </div>

                                    </div>
                                </form>

                                <div class="row">
                                    <div class="col-12 col-md-4 col-xl-4">
                                        <button class="btn btn-danger delete-btn"
                                                style="display: none">@lang('admin.cancel_patient')</button>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table border-0 custom-table comman-table datatable mb-0">
                                        <thead>
                                        <tr>
                                            <th>
                                                <div class="form-check check-tables">
                                                    <input class="form-check-input" type="checkbox" id="checkAll" value="something">
                                                </div>
                                            </th>
                                            <th>@lang('admin.waiting_number')</th>
                                            <th>@lang('admin.patient_name')</th>
                                            <th>@lang('admin.ID_Number')</th>
                                            <th>@lang('admin.file_number')</th>
                                            <th>@lang('admin.file_type')</th>
                                            <th>@lang('admin.waiting_time')</th>
                                            <th>@lang('admin.status')</th>
                                            <th>@lang('admin.waiting_list_action')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['patients_waiting'] as $index=>$waiting)
                                            <tr>
                                                <td>
                                                    <div class="form-check check-tables">
                                                        <input class="form-check-input" type="checkbox" value="{{$waiting->id}}" />
                                                    </div>
                                                </td>
                                                <td>
                                                @if($waiting->isCurrentTurn == true)
                                                    <div class="circle" style="background-color: #24FE18;text-align: center">{{ $index + 1 }}</div>
                                                @else
                                                    <div class="circle" style="background-color: #FA8902;text-align: center">{{ $index + 1 }}</div>
                                                @endif
                                                    @if($waiting->waiting_list == 1 && $waiting->status_id != 6)
                                                        <div class="circle" style="background-color: #24FE18;text-align: center">{{ $index + 1 }}</div>
                                                    @else
                                                        <div class="circle" style="background-color: #FA8902;text-align: center">{{ $index + 1 }}</div>
                                                    @endif
                                                </td>
                                                <td class="profile-image"><a
                                                        href="{{ route('patient-file', $waiting->id) }}"><img
                                                            width="28" height="28"
                                                            src="{{$waiting->user->image ?? null}}"
                                                            class="rounded-circle m-r-5"
                                                            alt="">{{$waiting->user->name ?? null}}
                                                    </a></td>
                                                <td>{{ $waiting->user->ID_Number ?? null }}</td>
                                                <td> --- </td>
                                                <td>@lang('admin.cash')</td>
                                                <td>{{ $waiting->waiting_time }}</td>
                                                <td>
                                                    {{  app()->getLocale() == 'en' ? $waiting->reservation_status->name_en : $waiting->reservation_status->name_ar }}
{{--                                                    @if( ($waiting->isCurrentTurn == 1 || $waiting->status_id == 2) && ($waiting->isCurrentTurn == true))--}}
{{--                                                        <button class="custom-badge status-green">--}}
{{--                                                            {{  app()->getLocale() == 'en' ? $waiting->reservation_status->name_en : $waiting->reservation_status->name_ar }}</button>--}}
{{--                                                    @elseif($waiting->status_id == 3 ||  $waiting->status_id == 4 || $waiting->status_id == 5 || $waiting->status_id == 6)--}}
{{--                                                        <button class="custom-badge status-pink">--}}
{{--                                                            {{  app()->getLocale() == 'en' ? $waiting->reservation_status->name_en : $waiting->reservation_status->name_ar }}</button>--}}
{{--                                                    @else--}}
{{--                                                        <button class="custom-badge status-pink">--}}
{{--                                                            {{  app()->getLocale() == 'en' ? $waiting->reservation_status->name_en : $waiting->reservation_status->name_ar }}</button>--}}
{{--                                                    @endif--}}

                                                </td>


                                                <td class="text-end">
                                                    <a href="{{ route('patient-file', $waiting->id) }}" class="btn btn-sm btn-white text-success me-2"><i class="far fa-eye me-1"></i> @lang('admin.file')</a>
                                                    <a href="#" class="btn btn-sm btn-white text-primary me-2" data-bs-toggle="modal" data-bs-target="#add_event{{$waiting->id}}"><i class="fa-solid fa-pen-to-square m-r-5"></i> @lang('admin.replace')</a>
                                                    <a class="btn btn-sm btn-white text-success" href="#" data-bs-toggle="modal" data-bs-target="#delete_drug{{$waiting->id}}"><i class="far fa-check-circle me-1"></i> @lang('admin.Finish')
                                                    </a>
{{--                                                    <div class="dropdown dropdown-action">--}}
{{--                                                        <a href="#" class="action-icon dropdown-toggle"--}}
{{--                                                           data-bs-toggle="dropdown" aria-expanded="false"><i--}}
{{--                                                                class="fa fa-ellipsis-v"></i></a>--}}
{{--                                                        <div class="dropdown-menu dropdown-menu-end">--}}
{{--                                                            <a class="dropdown-item"--}}
{{--                                                               href="{{ route('patient-file', $waiting->id) }}"><i--}}
{{--                                                                    class="feather-eye m-r-5"></i> @lang('admin.file')--}}
{{--                                                            </a>--}}
{{--                                                            <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#add_event{{$waiting->id}}"><i class="fa-solid fa-pen-to-square m-r-5"></i> @lang('admin.replace')</a>--}}

{{--                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_drug{{$waiting->id}}"><i class="fa fa-trash-alt m-r-5"></i> @lang('admin.Finish')--}}
{{--                                                            </a>--}}

{{--                                                        </div>--}}
{{--                                                    </div>--}}

                                                    <div id="delete_drug{{$waiting->id}}"
                                                         class="modal fade delete-modal"
                                                         role="dialog">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <form class="needs-validation" novalidate=""
                                                                      action="{{route('reservation-finished',$waiting->id) }}"
                                                                      method="POST">
                                                                    {{ method_field('delete') }}
                                                                    {{ csrf_field() }}
                                                                    <div class="modal-body text-center">
                                                                        <img src="/assets/img/sent.png" alt=""
                                                                             width="50"
                                                                             height="46">
                                                                        <h3>@lang('admin.confirm_finished')</h3>
                                                                        <div class="m-t-20"><a href="#" class="btn btn-white" data-bs-dismiss="modal">@lang('admin.close')</a>
                                                                            <button type="submit"
                                                                                    class="btn btn-danger">@lang('admin.finished')</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="add_event{{$waiting->id}}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content modal-md">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">@lang('admin.replace_patient')</h4>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form class="needs-validation" novalidate=""
                                                                          action="{{route('replace-reservation',$waiting->id)}}"
                                                                          method="POST" enctype="multipart/form-data">
                                                                        {{ method_field('POST') }}
                                                                        {{ csrf_field() }}
                                                                        <div class="form-group">
                                                                            <label>@lang('admin.patient') <span class="text-danger">*</span></label>
                                                                          <select class="form-control" name="reservation_id">
                                                                              <option>@lang('admin.select')</option>
                                                                              @foreach($data['patients_waiting'] as $reservation)
                                                                                  <option value="{{$reservation->id}}">{{$reservation->user->name}}</option>
                                                                              @endforeach

                                                                          </select>
                                                                        </div>
                                                                        <div class="m-t-20 text-center">
                                                                            <button class="btn btn-primary submit-btn">@lang('admin.replace')</button>
                                                                        </div>
                                                                    </form>
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
{{--                            {{ $data['patients_waiting']->links() }}--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script>
            // Get a reference to the "Check All" checkbox
            var checkAllCheckbox = document.getElementById('checkAll');

            // Get all individual checkboxes in the table body
            var checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');

            // Get all delete buttons
            var deleteButtons = document.querySelectorAll('.delete-btn');

            // Add a click event listener to the "Check All" checkbox
            checkAllCheckbox.addEventListener('click', function () {
                // Iterate through all individual checkboxes
                checkboxes.forEach(function (checkbox) {
                    checkbox.checked = checkAllCheckbox.checked;
                });

                // Toggle the visibility of delete buttons based on the "Check All" status
                deleteButtons.forEach(function (button) {
                    if (checkAllCheckbox.checked) {
                        button.style.display = 'block';
                    } else {
                        button.style.display = 'none';
                    }
                });
            });

            // Add click event listeners to individual checkboxes
            checkboxes.forEach(function (checkbox, index) {
                checkbox.addEventListener('click', function () {
                    // Toggle the visibility of the corresponding delete button
                    deleteButtons[index].style.display = checkbox.checked ? 'block' : 'none';
                });
            });

            // Add click event listeners to delete buttons
            deleteButtons.forEach(function (button, index) {
                button.addEventListener('click', function () {
                    // Perform the delete action for the corresponding item
                    // You can implement your item deletion logic here
                    alert('Item ' + (index + 1) + ' will be deleted.');
                });
            });


        </script>
@endsection
