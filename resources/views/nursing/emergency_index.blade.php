@extends('includes_admin.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('nursing-requests.index') }}">@lang('admin.Nursing') </a>
                            </li>
                            <li class="breadcrumb-item px-2"><i id="breadcrumbArrow"></i></li>
                            <li class="breadcrumb-item active">@lang('admin.Emergency') </li>
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
                                            <h3>@lang('admin.List of Emergency')</h3>
                                            <div class="doctor-search-blk">
                                                <div class="top-nav-search table-search-blk">
                                                    <input id="searchInput" type="text" class="form-control"
                                                        placeholder="@lang('admin.search_here')">
                                                        <button class="btn" onclick="search()"><img src="/assets/img/icons/search-normal.svg" alt=""></button>
                                                </div>
                                                <div class="add-group">
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
                                    <table id="emergencyTable" class="table border-0 custom-table comman-table datatable mb-0">
                                        <thead>
                                            <tr>
                                                <th>@lang('admin.patient_name')</th>
                                                <th>@lang('admin.ID_Number')</th>
                                                <th>@lang('admin.patient_file')</th>
                                                <th>@lang('admin.file_type')</th>
                                                <th>@lang('admin.Date of entry')</th>
                                                <th>@lang('admin.Date of Exit')</th>
                                                <th>@lang('admin.type of Exit')</th>
                                                <th>@lang('admin.Doctor')</th>
                                                <th>@lang('admin.reception.options')</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($emergency as $item)
                                                <tr>
                                                    <td><a href="{{route('emergency.edit',$item->id)}}">{{ $item->user->name }}</a></td>
                                                    <td>{{ $item->user->ID_Number }}</td>
                                                    <td>{{ $item->user->file_number }}</td>
                                                    <td>

                                                        @if ($item->user->company_id != null)
                                                            @lang('admin.insurance')
                                                        @else
                                                            @lang('admin.cash')
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->enter_date }}</td>
                                                    <td>{{ $item->exit_date !== '0000-00-00' ? $item->exit_date : '' }}</td>
                                                    <td>
                                                        @if ($item->type == 1)
                                                            <i class="fa-solid fa-door-open m-r-5"></i>
                                                        @elseif($item->type == 2)
                                                            <i class="fa-solid fa-truck-medical m-r-5"></i>({{$item->nurse->name}})
                                                        @else
                                                            --
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @foreach ($item->doctors as $key => $doctor)
                                                            {{ $doctor->name }}
                                                            @if ($key < count($item->doctors) - 1)
                                                                , <!-- Add a comma if it's not the last doctor -->
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="dropdown dropdown-action">
                                                            <a href="#" class="action-icon dropdown-toggle"
                                                                data-bs-toggle="dropdown" aria-expanded="false"><i
                                                                    class="fa fa-ellipsis-v"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a href="edit-patient.html" class="dropdown-item"
                                                                    type="button"><i class="fa-solid fa-file m-r-5"></i>
                                                                    @lang('admin.patient_file_clinic')</a>
                                                                <a class="dropdown-item" data-bs-toggle="modal"
                                                                    data-bs-target="#note_modal"
                                                                    data-bs-id="{{ $item->id }}"
                                                                    data-bs-note="{{ $item->notes }}" type="button"><i
                                                                        class="fa-solid fa-notes-medical m-r-5"></i>@lang('admin.note')</a>
                                                                @if ($item->type == null)
                                                                    <a class="dropdown-item" data-bs-toggle="modal"
                                                                        data-bs-target="#confirm_exit"
                                                                        data-bs-id="{{ $item->id }}" type="button"><i
                                                                            class="fa-solid fa-door-open m-r-5"></i>
                                                                        @lang('admin.exit')</a>
                                                                    <a class="dropdown-item" data-bs-toggle="modal"
                                                                        data-bs-target="#exit_ambulance_modal"
                                                                        data-bs-id="{{ $item->id }}" type="button"><i
                                                                            class="fa-solid fa-truck-medical m-r-5"></i>@lang('admin.Exit by ambulance')</a>
                                                                @endif
                                                                <a href="{{route('emergency.edit',$item->id)}}" class="dropdown-item"
                                                                    type="button"><i
                                                                        class="fa-solid fa-pen-to-square m-r-5"></i>
                                                                    @lang('admin.edit')</a>
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

    <!-- Confirm Modal -->
    <div class="modal custom-modal modal-bg fade" id="confirm_exit" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>@lang('admin.confirm')</h3>
                        <p>@lang('admin.Are you sure you want to exit the patient?')</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <input type="hidden" name="id" id="exit_id">
                        <div class="row">
                            <div class="col-6">
                                <a href="javascript:void(0);" data-bs-dismiss="modal"
                                    class="btn btn-primary paid-continue-btn" id="confimed_exit">@lang('admin.confirm')</a>
                            </div>
                            <div class="col-6">
                                <a href="javascript:void(0);" data-bs-dismiss="modal"
                                    class="btn btn-primary paid-cancel-btn">@lang('admin.cancel')</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Confirm Modal End -->

    <!-- Note Modal -->
    <div class="modal custom-modal modal-bg fade bank-details" id="note_modal" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header py-2 px-3">
                    <div class="form-header text-start mb-0">
                        <h4 class="mb-0">@lang('admin.notes')</h4>
                    </div>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-start py-4 px-3">
                    <div class="bank-inner-details">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group local-forms mb-0">
                                    <label>@lang('admin.notes') <span class="login-danger">*</span></label>
                                    <textarea id="note_content" class="form-control" name="notes" style="min-height: 150px;"
                                        placeholder="@lang('admin.enter_text_here')" rows="6" cols="30" required></textarea>
                                    <input type="hidden" name="id" id="note_id">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-3">
                    <div class="bank-details-btn">
                        <a href="javascript:void(0);" data-bs-dismiss="modal"
                            class="btn bank-cancel-btn me-2">@lang('admin.cancel')</a>
                        <a href="javascript:void(0);" class="btn bank-save-btn" id="btn_save_note">@lang('admin.save')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Note Modal End -->

    <!-- Exit with Ambulance -->
    <div class="modal custom-modal modal-bg fade bank-details" id="exit_ambulance_modal" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header py-2 px-3">
                    <div class="form-header text-start mb-0">
                        <h4 class="mb-0">@lang('admin.Exit by ambulance')</h4>
                    </div>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <input type="hidden" name="id" id="exit_ambulance_id">
                <div class="modal-body text-start py-4 px-3">
                    <div class="bank-inner-details">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group local-forms mb-0">
                                    <label for="nurse">@lang('admin.accompanying nurse') <span class="login-danger">*</span></label>
                                    <select id="nurse" class="form-control" style="width: 100%;" name="nurse_id">
                                        @foreach ($all_nurses as $nurse)
                                            <option value="{{ $nurse->id }}">{{ $nurse->name }}</option>
                                        @endforeach
                                    </select>
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
                            id="confimed_ambulance_exit">@lang('admin.confirm')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Exit with Ambulance End -->
    </div>
    <div class="sidebar-overlay" data-reff=""></div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#note_modal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var noteContent = button.data('bs-note'); // Extract info from data-bs-note attribute
                var note_id = button.data('bs-id'); // Extract info from data-bs-note attribute
                // Update modal content with the note content
                $('#note_content').text(noteContent);
                $('#note_id').val(note_id);
            });
        });

        $(document).ready(function() {
            // Event listener for the "Save" button
            $("#btn_save_note").click(function() {
                // Get the note content from the textarea
                var noteContent = $("#note_content").val();
                var note_id = $("#note_id").val();

                // Make sure the note content is not empty
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                // Make an AJAX request to save the note
                $.ajax({
                    type: "POST", // You can change the HTTP method as needed
                    url: '{{ route('save-note.saveNote') }}', // Replace with the actual endpoint to save the note
                    data: {
                        note_content: noteContent,
                        note_id: note_id
                    },
                    success: function(response) {

                        $("#note_modal").modal("hide");
                        location.reload();
                    },
                    error: function(error) {
                        // Handle the error response here
                        console.error("Error saving note:", error);
                    }
                });

            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#exit_ambulance_modal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var id = button.data('bs-id');
                $('#exit_ambulance_id').val(id);
            });
        });

        $(document).ready(function() {
            var selectedNurseId; // Variable to store the selected nurse_id

            // Event listener for nurse dropdown change event
            $("#nurse").change(function() {
                selectedNurseId = $(this).val();
            });
            $("#confimed_ambulance_exit").click(function() {
                var id = $("#exit_ambulance_id").val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                // Make an AJAX request to save the note
                $.ajax({
                    type: "POST", // You can change the HTTP method as needed
                    url: '{{ route('ambulance-exit') }}', // Replace with the actual endpoint to save the note
                    data: {
                        id: id,
                        selectedNurseId: selectedNurseId
                    },
                    success: function(response) {

                        $("#note_modal").modal("hide");
                        location.reload();
                    },
                    error: function(error) {
                        // Handle the error response here
                        console.error("Error saving note:", error);
                    }
                });

            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#confirm_exit').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var id = button.data('bs-id');
                $('#exit_id').val(id);
            });
        });

        $(document).ready(function() {
            // Event listener for the "Save" button
            $("#confimed_exit").click(function() {
                var id = $("#exit_id").val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                // Make an AJAX request to save the note
                $.ajax({
                    type: "POST", // You can change the HTTP method as needed
                    url: '{{ route('normal-exit') }}', // Replace with the actual endpoint to save the note
                    data: {
                        id: id
                    },
                    success: function(response) {

                        $("#note_modal").modal("hide");
                        location.reload();
                    },
                    error: function(error) {
                        // Handle the error response here
                        console.error("Error saving note:", error);
                    }
                });

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
                url: '{{ route('emergency.search') }}',
                data: {
                    query: searchInput
                },
                success: function(response) {

                    var tableBody = $('#emergencyTable tbody');
                    tableBody.empty();
                    $.each(response.emergency, function(index, item) {

                        var newRow = '<tr>' +
                            '<td><a href="vital-signs.html">' + item.user.name + '</a></td>' +
                            '<td>' + item.user.ID_Number + '</td>' +
                            '<td>' + item.user.file_number + '</td>' +
                            '<td>' + (item.user.company_id !== null ? ' @lang('admin.insurance')' :
                                ' @lang('admin.cash')') +
                            '</td>' +
                            '<td>' + item.enter_date + '</td>' +
                            '<td>' + (item.exit_date !== '0000-00-00' ? item.exit_date : '') + '</td>' +

                            '<td>' +
                            (item.type === 1 ? '<i class="fa-solid fa-door-open m-r-5"></i>' :
                                (item.type === 2 ? '<i class="fa-solid fa-truck-medical m-r-5"></i>' :
                                    '--')) +
                            '</td>' +

                            '<td>' + item.doctors.map(doctor => doctor.name).join(', ') +
                            '</td>' +
                            '<td class="text-end">' +
                            '<div class="dropdown dropdown-action">' +
                            '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>' +
                            '<div class="dropdown-menu dropdown-menu-end">' +
                            '<a href="edit-patient.html" class="dropdown-item" type="button"><i class="fa-solid fa-file m-r-5"></i> @lang('admin.patient_file_clinic')</a>' +
                            '<a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#note_modal" data-bs-note="' +
                            item.notes +
                            '" type="button"><i class="fa-solid fa-notes-medical m-r-5"></i>@lang('admin.note')</a>' +
                            (item.type === null ?
                                '<a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#confirm_exit" data-bs-id="' +
                                item.id +
                                '" type="button"><i class="fa-solid fa-door-open m-r-5"></i>@lang('admin.exit')</a>' +
                                '<a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exit_ambulance_modal" data-bs-id="' +
                                item.id +
                                '" type="button"><i class="fa-solid fa-truck-medical m-r-5"></i>@lang('admin.Exit by ambulance')</a>' :
                                '') +
                            '<a href="vital-signs.html" class="dropdown-item" type="button"><i class="fa-solid fa-pen-to-square m-r-5"></i> @lang('admin.edit')</a>' +
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
                url: '{{ route('emergency.filter') }}',
                type: 'get',
                data: {
                    fromDate: fromDate,
                    toDate: toDate
                },
                success: function(response) {
                    var tableBody = $('#emergencyTable tbody');
                    tableBody.empty();
                    $.each(response.emergency, function(index, item) {

                        var newRow = '<tr>' +
                            '<td><a href="vital-signs.html">' + item.user.name + '</a></td>' +
                            '<td>' + item.user.ID_Number + '</td>' +
                            '<td>' + item.user.file_number + '</td>' +
                            '<td>' + (item.user.company_id !== null ? ' @lang('admin.insurance')' :
                                ' @lang('admin.cash')') +
                            '</td>' +
                            '<td>' + item.enter_date + '</td>' +
                            '<td>' + (item.exit_date !== '0000-00-00' ? item.exit_date : '') + '</td>' +

                            '<td>' +
                            (item.type === 1 ? '<i class="fa-solid fa-door-open m-r-5"></i>' :
                                (item.type === 2 ? '<i class="fa-solid fa-truck-medical m-r-5"></i>' :
                                    '--')) +
                            '</td>' +

                            '<td>' + item.doctors.map(doctor => doctor.name).join(', ') +
                            '</td>' +
                            '<td class="text-end">' +
                            '<div class="dropdown dropdown-action">' +
                            '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>' +
                            '<div class="dropdown-menu dropdown-menu-end">' +
                            '<a href="edit-patient.html" class="dropdown-item" type="button"><i class="fa-solid fa-file m-r-5"></i> @lang('admin.patient_file_clinic')</a>' +
                            '<a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#note_modal" data-bs-note="' +
                            item.notes +
                            '" type="button"><i class="fa-solid fa-notes-medical m-r-5"></i>@lang('admin.note')</a>' +
                            (item.type === null ?
                                '<a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#confirm_exit" data-bs-id="' +
                                item.id +
                                '" type="button"><i class="fa-solid fa-door-open m-r-5"></i>@lang('admin.exit')</a>' +
                                '<a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exit_ambulance_modal" data-bs-id="' +
                                item.id +
                                '" type="button"><i class="fa-solid fa-truck-medical m-r-5"></i>@lang('admin.Exit by ambulance')</a>' :
                                '') +
                            '<a href="vital-signs.html" class="dropdown-item" type="button"><i class="fa-solid fa-pen-to-square m-r-5"></i> @lang('admin.edit')</a>' +
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
