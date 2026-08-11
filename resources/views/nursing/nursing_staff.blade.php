@extends('includes_admin.mainlayout')

@section('content')
    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->

            <div class="row">
                <div class="col-sm-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('nursing-requests.index') }}">@lang('admin.Nursing')</a></li>
                        <li class="breadcrumb-item px-2"><i id="breadcrumbArrow"></i></li>
                        <li class="breadcrumb-item active">@lang('admin.employees') </li>
                    </ul>
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
                                            <h3>@lang('admin.List_of_employees')</h3>
                                            <div class="doctor-search-blk">
                                                <div class="top-nav-search table-search-blk">
                                                    <input type="text" class="form-control" id="searchInput"
                                                        placeholder="@lang('admin.Search by nurse name')">
                                                    <button class="btn" onclick="search()"><img
                                                            src="/assets/img/icons/search-normal.svg"
                                                            alt=""></button>
                                                </div>
                                                <div class="add-group">
                                                    <a data-bs-toggle="modal" data-bs-target="#add_employee"
                                                        class="btn btn-primary add-pluss"><img
                                                            src="/assets/img/icons/plus.svg" alt=""></a>
                                                    <a href="javascript:;" class="btn btn-primary doctor-refresh"
                                                        onclick="refreshPage()"><img src="/assets/img/icons/re-fresh.svg"
                                                            alt=""></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Table Header -->

                            <!-- Filter -->
                            <div class="staff-search-table">
                                <form>
                                    <div class="row">
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="form-group local-forms cal-icon">
                                                <label>@lang('admin.from') </label>
                                                <input class="form-control datetimepicker" type="text" id="fromDate">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="form-group local-forms cal-icon">
                                                <label>@lang('admin.To') </label>
                                                <input class="form-control datetimepicker" type="text" id="toDate">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="doctor-submit">
                                                <button type="button" onclick="filter()"
                                                    class="btn btn-primary submit-list-form me-2">@lang('admin.filter')</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- Filter End -->

                            <div class="position-relative">
                                <div class="table-loader" id="loader">
                                    <div class="spinner"></div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table border-0 custom-table comman-table datatable mb-0" id="nursesTable">
                                        <thead>
                                            <tr>
                                                <th>@lang('admin.nurse name')</th>
                                                <th>@lang('admin.mobile_number')</th>
                                                <th>@lang('admin.Number services')</th>
                                                <th>@lang('admin.point')</th>
                                                <th>@lang('admin.reception.options')</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($nurses as $nurse)
                                                <tr>
                                                    <td>{{ $nurse->name }}</td>
                                                    <td>{{ $nurse->phone }}</td>
                                                    <td>{{ $nurse->patientService()->count() }}</td>
                                                    <td>{{ app()->getLocale() == 'en' ? $nurse->clinicPointNursing->name_en : $nurse->clinicPointNursing->name_ar }}
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="dropdown dropdown-action">
                                                            <a href="#" class="action-icon dropdown-toggle"
                                                                data-bs-toggle="dropdown" aria-expanded="false"><i
                                                                    class="fa fa-ellipsis-v"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a href="{{ route('nursing-services.nursingServices', ['id' => $nurse->id]) }}"
                                                                    class="dropdown-item" type="button">
                                                                    <i class="fa-solid fa-eye m-r-5"></i> @lang('admin.show')
                                                                </a>
                                                                <a class="dropdown-item " id ="edit-nurse"
                                                                    data-bs-toggle="modal"
                                                                    data-nurse-id="{{ $nurse->id }}"
                                                                    data-bs-target="#edit_employee" type="button"><i
                                                                        class="fa-solid fa-pen-to-square m-r-5"></i>
                                                                    @lang('admin.update')</a>

                                                                <a class="dropdown-item delete-nurse"
                                                                    data-nurse-id="{{ $nurse->id }}"
                                                                    data-bs-toggle="modal" data-bs-target="#delete_nurse"
                                                                    type="button">
                                                                    <i class="fa-solid fa-trash-can m-r-5"></i>
                                                                    @lang('admin.delete')
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
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Delete Department Modal -->
    <div id="delete_nurse" class="modal fade delete-modal" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <img src="/assets/img/sent.png" alt="" width="50" height="46">
                    <h3>@lang('admin.Are you sure you want to delete this?')</h3>
                    <div class="m-t-20">
                        <a href="#" class="btn btn-white" data-bs-dismiss="modal">@lang('admin.cancel')</a>
                        <button id="confirmDelete" class="btn btn-danger">@lang('admin.delete')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Department Modal End -->

    <!-- Add Employee Modal -->
    <div class="modal custom-modal modal-bg fade bank-details" id="add_employee" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header py-2 px-3">
                    <div class="form-header text-start mb-0">
                        <h4 class="mb-0">@lang('admin.add nurse')</h4>
                    </div>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-start py-4 px-3">
                    <div class="bank-inner-details">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group local-forms modal-select">
                                    <label> @lang('admin.nurse name') <span class="login-danger">*</span></label>
                                    {{-- <input list="employees" id="employee" name="employee" class="form-control" type="text" placeholder="إسم المريض" >
                                <datalist id="employees">
                                    @foreach ($Nurses as $nurse)
                                    <option value="{{$nurse->name}}">{{$nurse->name}}</option>
                                    @endforeach
                                </datalist> --}}
                                    <span class="text-danger error-message"></span>
                                    <select class="form-control" name="nurse_id">
                                        <option selected="true" disabled="disabled" >@lang('admin.Select nurse')
                                        </option>
                                        @foreach ($all_nurses as $nurse)
                                            <option value="{{ $nurse->id }}">{{ $nurse->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms modal-select">
                                    <label>@lang('admin.point') <span class="login-danger">*</span></label>
                                    <span class="text-danger error-message"></span>
                                    <select class="form-control" name="point_id">

                                        <option selected="true" disabled="disabled" >@lang('admin.Select point')
                                        </option>
                                        @foreach ($points as $point)
                                            <option value="{{ $point->id }}">
                                                {{ app()->getLocale() == 'en' ? $point->name_en : $point->name_ar }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms mb-0">
                                    <label>@lang('admin.notes') <span class="login-danger">*</span></label>
                                    <span class="text-danger error-message"></span>
                                    <textarea class="form-control" style="min-height: 87px;" placeholder="@lang('admin.enter_text_here')" rows="3"
                                        cols="30" name="notes"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-3">
                    <div class="bank-details-btn">
                        <a href="javascript:void(0);" data-bs-dismiss="modal"
                            class="btn bank-cancel-btn me-2">@lang('admin.cancel')</a>
                        <a href="javascript:void(0);" class="btn bank-save-btn"
                            onclick="addNurse()">@lang('admin.Add')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Employee Modal End -->

    <!-- Edit Employee Modal -->
    <div class="modal custom-modal modal-bg fade bank-details" id="edit_employee" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header py-2 px-3">
                    <div class="form-header text-start mb-0">
                        <h4 class="mb-0">@lang('admin.edit nurse')</h4>
                    </div>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-start py-4 px-3">
                    <div class="bank-inner-details">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group local-forms modal-select">
                                    
                                    <label> @lang('admin.nurse name') <span class="login-danger">*</span></label>
                                    {{-- <input list="employees" id="employee" name="employee" class="form-control" type="text" placeholder="إسم المريض" >
                                <datalist id="employees">
                                    @foreach ($Nurses as $nurse)
                                    <option value="{{$nurse->name}}">{{$nurse->name}}</option>
                                    @endforeach
                                </datalist> --}}
                                <span class="text-danger error-message"></span>
                                    <select class="form-control" id="nurseNameSelect" name="nurse_id">

                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms modal-select">
                                    <label>@lang('admin.point') <span class="login-danger">*</span></label>
                                    <span class="text-danger error-message"></span>
                                    <select class="form-control" id="pointSelect" name="point_id">

                                        {{-- <option selected="true" disabled="disabled">@lang('admin.Select point')</option>
                                        @foreach ($points as $point)
                                            <option value="{{ $point->id }}">
                                                {{ app()->getLocale() == 'en' ? $point->name_en : $point->name_ar }}
                                            </option>
                                        @endforeach --}}

                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms mb-0">
                                    <label>@lang('admin.notes') <span class="login-danger">*</span></label>
                                    <span class="text-danger error-message"></span>
                                    <textarea class="form-control" id="notes" style="min-height: 87px;" placeholder="@lang('admin.enter_text_here')"
                                        rows="3" cols="30" name="notes"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-3">
                    <div class="bank-details-btn">
                        <a href="javascript:void(0);" data-bs-dismiss="modal"
                            class="btn bank-cancel-btn me-2">@lang('admin.cancel')</a>
                        <a href="javascript:void(0);" class="btn bank-save-btn"
                            onclick="editNurse()">@lang('admin.edit')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        function addNurse() {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Get the selected values from the modal
            var nurseId = $('.modal-body .modal-select:eq(0) select').val();
            var pointId = $('.modal-body .modal-select:eq(1) select').val();
            var notes = $('.modal-body textarea[name="notes"]').val();

            // Perform AJAX request
            $.ajax({
                type: 'POST',
                url: '{{ route('nursing-staff.store') }}', // Replace with your actual API endpoint
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    nurse_id: nurseId,
                    point_id: pointId,
                    notes: notes
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        // Validation failed, handle validation errors
                        var errors = xhr.responseJSON.errors;
                        // Display validation errors to the user
                        $('.error-message').text('');
                        $.each(errors, function(field, messages) {
                            if (field === 'nurse_id') {
                                // Update the error message for nurse selection field
                                $('.modal-select select[name="nurse_id"]').siblings('.error-message')
                                    .text(messages[0]);
                            }
                            if (field === 'point_id') {
                                // Update the error message for nurse selection field
                                $('.modal-select select[name="point_id"]').siblings('.error-message')
                                    .text(messages[0]);
                            }
                            if (field === 'notes') {
                                // Update the error message for nurse selection field
                                $('.modal-body textarea[name="notes"]').siblings('.error-message')
                                    .text(messages[0]);
                            }
                            
                        });

                    } else {
                        // Non-validation error, handle accordingly
                        console.log(xhr);
                    }
                }
            });
        }
    </script>
    <script>
        // Assuming you have a button or link to open the modal with a data-nurse-id attribute
        $(document).on('click', '#edit-nurse', function(event) {

            event.preventDefault();

            var nurseId = $(this).data('nurse-id');
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var lang = '{{ app()->getLocale() == 'en' }}';

            $('#nurseNameSelect').empty();
            $('#notes').val('');
            $('#pointSelect').empty();
            // Use AJAX to fetch data from the controller
            $.ajax({
                url: '{{ route('nursing-staff.edit', ['id' => 'nurseId']) }}'.replace('nurseId', nurseId),

                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                dataType: 'json',
                success: function(data) {

                    // Populate the modal with fetched data

                    $('#nurseNameSelect').append('<option value="' + data.nurse.id +
                        '" selected>' + data.nurse.name + '</option>');
                    $('#notes').val(data.nurse.notes);
                    $('#pointSelect').val(data.nursing_point_id).change();
                    $.each(data.points, function(index, point) {
                        $('#pointSelect').append('<option value="' + point.id + '" ' + (point
                                .id == data.nurse.nursing_point_id ? 'selected' : '') +
                            '>' +
                            (lang == 1 ? point.name_en : point.name_ar) +
                            '</option>');
                    });
                    // Show the modal
                    $('#edit_employee').modal('show');
                },
                error: function(error) {
                    console.error('Error fetching nurse data: ', error);
                }
            });
        });

        function editNurse() {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Get the selected values from the modal
            var nurseId = $('#nurseNameSelect').val();
            var pointId = $('#pointSelect').val();
            var notes = $('#notes').val();
            // Perform AJAX request
            $.ajax({
                type: 'PUT', // Use PUT for update
                url: '{{ route('nursing-staff.update', ['id' => ':nurseId']) }}'.replace(
                    ':nurseId', nurseId),
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    nurse_id: nurseId,
                    point_id: pointId,
                    notes: notes
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        // Validation failed, handle validation errors
                        var errors = xhr.responseJSON.errors;
                        // Display validation errors to the user
                        $('.error-message').text('');
                        $.each(errors, function(field, messages) {
                            if (field === 'nurse_id') {
                                // Update the error message for nurse selection field
                                $('.modal-select select[name="nurse_id"]').siblings('.error-message')
                                    .text(messages[0]);
                            }
                            if (field === 'point_id') {
                                // Update the error message for nurse selection field
                                $('.modal-select select[name="point_id"]').siblings('.error-message')
                                    .text(messages[0]);
                            }
                            if (field === 'notes') {
                                // Update the error message for nurse selection field
                                $('.modal-body textarea[name="notes"]').siblings('.error-message')
                                    .text(messages[0]);
                            }
                            
                        });

                    } else {
                        // Non-validation error, handle accordingly
                        console.log(xhr);
                    }
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            // Store nurse ID when delete button is clicked
            $('.delete-nurse').on('click', function() {
                var nurseId = $(this).data('nurse-id');
                $('#confirmDelete').data('nurse-id', nurseId);
            });

            // Handle delete confirmation with AJAX
            $('#confirmDelete').on('click', function() {
                // Get nurse ID from the button
                var nurseId = $(this).data('nurse-id');
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                // Make an AJAX request to delete the nurse
                $.ajax({
                    url: '{{ route('nursing-staff.delete', ['id' => ':nurseId']) }}'.replace(
                        ':nurseId', nurseId),
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        // Handle success, update UI, etc.
                        console.log(response);

                        // For simplicity, reload the page after successful deletion
                        location.reload();
                    },
                    error: function(error) {
                        // Handle error, show an alert, etc.
                        console.error(error);
                    }
                });

                // After initiating the AJAX request, close the modal
                $('#delete_nurse').modal('hide');
            });
        });
    </script>
    <script>
        function search() {
            var searchInput = $('#searchInput').val();
            var lang = '{{ app()->getLocale() == 'en' }}';
            // Make an AJAX request using Laravel's built-in CSRF protection
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Make the AJAX request
            $.ajax({
                type: 'GET',
                url: '{{ route('nursing-staff.search') }}',
                data: {
                    query: searchInput
                },
                success: function(data) {

                    var tableBody = $('#nursesTable tbody');
                    tableBody.empty();
                    $.each(data.nurses, function(index, nurse) {
                        var nurseId = nurse.id;
                        var routeUrl = '{{ route('nursing-services.nursingServices', ':id') }}';
                       
                        var newRow = '<tr>' +
                            '<td>' + nurse.name + '</td>' +
                            '<td>' + nurse.phone + '</td>' +
                            '<td>3</td>' +
                            '<td>' + nurse.point_name +
                            '</td>' +
                            '<td class="text-end">' +
                            '<div class="dropdown dropdown-action">' +
                            '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>' +
                            '<div class="dropdown-menu dropdown-menu-end">' +
                                '<a href="' + routeUrl.replace(':id', nurseId) + '" class="dropdown-item" type="button"><i class="fa-solid fa-eye m-r-5"></i> @lang('admin.show')</a>' +
                            '<a class="dropdown-item" id ="edit-nurse" data-bs-toggle="modal" data-nurse-id="' +
                            nurse.id +
                            '" data-bs-target="#edit_employee" type="button"><i class="fa-solid fa-pen-to-square m-r-5"></i> @lang('admin.update')</a>' +
                            '<a class="dropdown-item delete-nurse" data-nurse-id="' + nurse.id +
                            '" data-bs-toggle="modal" data-bs-target="#delete_nurse" type="button"><i class="fa-solid fa-trash-can m-r-5"></i> @lang('admin.delete')</a>' +
                            '</div>' +
                            '</div>' +
                            '</td>' +
                            '</tr>';

                        tableBody.append(newRow);
                    });
                },
                error: function(error) {
                    // Handle the error
                    console.error('Error:', error);
                }
            });

        }

        function filter() {
            var fromDate = $('#fromDate').val();
            var toDate = $('#toDate').val();
            var lang = '{{ app()->getLocale() == 'en' }}';
            $.ajax({
                url: '{{ route('nursing-staff.filter') }}',
                type: 'get',
                data: {
                    fromDate: fromDate,
                    toDate: toDate
                },
                success: function(response) {
                    var tableBody = $('#nursesTable tbody');
                    tableBody.empty();
                    $.each(response.nurses, function(index, nurse) {

                        var newRow = '<tr>' +
                            '<td>' + nurse.name + '</td>' +
                            '<td>' + nurse.phone + '</td>' +
                            '<td>3</td>' +
                            '<td>' + nurse.point_name +
                            '</td>' +
                            '<td class="text-end">' +
                            '<div class="dropdown dropdown-action">' +
                            '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>' +
                            '<div class="dropdown-menu dropdown-menu-end">' +
                            '<a href="nurse-services.html" class="dropdown-item" type="button"><i class="fa-solid fa-eye m-r-5"></i> عرض</a>' +
                            '<a class="dropdown-item" id ="edit-nurse" data-bs-toggle="modal" data-nurse-id="' +
                            nurse.id +
                            '" data-bs-target="#edit_employee" type="button"><i class="fa-solid fa-pen-to-square m-r-5"></i> تعديل</a>' +
                            '<a class="dropdown-item delete-nurse" data-nurse-id="' + nurse.id +
                            '" data-bs-toggle="modal" data-bs-target="#delete_nurse" type="button"><i class="fa-solid fa-trash-can m-r-5"></i> حذف</a>' +
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
    </script>
    <script>
        // JavaScript function to refresh the page
        function refreshPage() {
            // Show the loader
            document.getElementById('loader').style.display = 'block';

            // Reload the page after a short delay (you can adjust the delay as needed)
            setTimeout(function() {
                location.reload(true); // Reload the page
            }, 1000); // 1000 milliseconds (1 second) delay in this example
        }
    </script>
@endsection
