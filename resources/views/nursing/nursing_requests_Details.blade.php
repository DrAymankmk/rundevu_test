@extends('includes_admin.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('nursing-requests.index')}}">@lang('admin.Nursing') </a></li>
                            <li class="breadcrumb-item px-2"><i id="breadcrumbArrow"></i></li>
                            <li class="breadcrumb-item active">@lang('admin.requests') </li>
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
                                            <h3>{{ $nurseServises[0]->user->name}}</h3>
                                            {{-- <div class="doctor-search-blk">
                                                <div class="top-nav-search table-search-blk">
                                                    <input  type="text" id="searchInput"
                                                        class="form-control" placeholder="@lang('admin.search_here')">
                                                        <button class="btn" onclick="search()"><img src="/assets/img/icons/search-normal.svg" alt=""></button>
                                                </div>
                                                <div class="add-group">
                                                    <!-- <a href="vital-signs.html" class="btn btn-primary add-pluss"><img src="assets/img/icons/plus.svg" alt=""></a> -->
                                                    <a href="javascript:;" class="btn btn-primary doctor-refresh" onclick="refreshPage()"><img
                                                            src="/assets/img/icons/re-fresh.svg" alt=""></a>
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-5 col-12 text-end">
                                        <a href="{{route('create-emergency.create')}}" class="btn btn-primary btn-rounded"><i
                                                class="fa fa-plus m-r-5"></i> @lang('admin.Emergency patient')</i></a>
                                    </div> --}}
                                </div>
                            </div>
                            <!-- /Table Header -->

                            <!-- Filter -->
                            {{-- <div class="staff-search-table">
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
                            </div> --}}
                            <!-- Filter End -->

                            <div class="position-relative">
                                <div class="table-loader" id="loader">
                                    <div class="spinner"></div>
                                </div>
                                <div class="table-responsive">
                                    <table id="servicesTable"
                                           class="table border-0 custom-table comman-table datatable mb-0">
                                        <thead>
                                        <tr>

                                            <th>@lang('admin.ID_Number')</th>
                                            <th>@lang('admin.patient_file')</th>
                                            <th>@lang('admin.file_type')</th>
                                            <th>@lang('admin.Date of entry')</th>
                                            <th>@lang('admin.service_type')</th>
                                            <th>@lang('admin.confirm')</th>
                                            <th>@lang('admin.Doctor')</th>
                                            <th>@lang('admin.reception.options')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($nurseServises as $nurseServise)
                                            <tr>
                                                <td>{{ $nurseServise->user->ID_Number }}</td>
                                                <td>{{ $nurseServise->services->code }}</td>
                                                <td>
                                                    @if ($nurseServise->user->company_id != null)
                                                        @lang('admin.insurance')
                                                    @else
                                                        @lang('admin.cash')
                                                    @endif
                                                </td>
                                                <td>{{ $nurseServise->created_at->format('Y-m-d') }}</td>
                                                <td>
                                                    @if ($nurseServise->type == 3)
                                                        @lang('admin.service')
                                                    @elseif($nurseServise->type == 1)
                                                        @lang('admin.analysis')
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($nurseServise->nurse)
                                                        <p class="mb-0 d-flex align-items-center gap-2">
                                                            <i class="fa fa-circle-check text-info font-18"></i>
                                                            <span>{{ $nurseServise->nurse->name }}</span>
                                                        </p>
                                                    @else
                                                        <p class="mb-0 d-flex align-items-center gap-2">

                                                        </p>
                                                    @endif
                                                </td>
                                                <td>{{ $nurseServise->doctor->name }}</td>
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
                                                               data-bs-note="{{ $nurseServise->notes }}"
                                                               type="button"><i
                                                                    class="fa-solid fa-notes-medical m-r-5"></i>
                                                                @lang('admin.note')</a>
                                                            @if (!$nurseServise->nurse)
                                                                <a class="dropdown-item" data-bs-toggle="modal"
                                                                   data-bs-target="#confirm_service"
                                                                   data-bs-patient="{{ $nurseServise->id }}"
                                                                   type="button"><i
                                                                        class="fa-solid fa-check-circle m-r-5"></i>
                                                                    @lang('admin.confirm')</a>
                                                            @endif
                                                            {{-- <a href="{{route('nursing-requests.vitalSigns',$nurseServise->reservation_id )}}" class="dropdown-item"
                                                                type="button"><i
                                                                    class="fa-solid fa-heart-pulse m-r-5"></i> العلامات
                                                                الحيوية</a> --}}
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
    <div class="modal custom-modal modal-bg fade" id="confirm_service" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>@lang('admin.confirm')</h3>
                        <p>@lang('admin.Are you sure you want to confirm service execution?')</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                                <a href="javascript:void(0);" data-bs-dismiss="modal"
                                   class="btn btn-primary paid-continue-btn" id="confirmButton">@lang('admin.confirm')</a>
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
    <div class="modal custom-modal modal-bg fade" id="note_modal" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header mb-0">
                        <h3>@lang('admin.note')</h3>
                        <p id="note_content"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Note Modal End -->
    </div>
    <div class="sidebar-overlay" data-reff=""></div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        $(document).ready(function() {
            $('#note_modal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var noteContent = button.data('bs-note'); // Extract info from data-bs-note attribute
                // Update modal content with the note content
                $('#note_content').text(noteContent);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#confirm_service').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var patientServiceIdContent = button.data('bs-patient');
                $("#confirmButton").on("click", function() {
                    $.ajax({
                        url: '{{ route('nursing-requests.confirmedService') }}', // replace with your actual endpoint
                        method: "GET", // or "GET" depending on your server setup
                        data: {
                            patientServiceId: patientServiceIdContent
                        },
                        success: function(response) {

                            // Use SweetAlert to display a success message
                            Swal.fire({
                                icon: 'success',
                                title: '{{trans('admin.done')}}',
                                text: response.message,
                                onClose: () => {
                                    location
                                        .reload(); // Reload the page
                                }
                            });

                        },
                        error: function(error) {
                            // Handle the error response
                            console.error("Ajax request error:", error);
                        }
                    });
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
                url: '{{ route('nursing-requests.search') }}',
                data: {
                    query: searchInput
                },
                success: function(data) {

                    var tableBody = $('#servicesTable tbody');
                    tableBody.empty();

                    $.each(data.nurseServices, function(index, nurseService) {
                        var originalDate = new Date(nurseService.created_at);

                        // Format the date as 'YYYY-MM-DD' (e.g., 2023-12-04)
                        var formattedDate = originalDate.getFullYear() + '-' + ('0' + (originalDate
                            .getMonth() + 1)).slice(-2) + '-' + ('0' + originalDate.getDate())
                            .slice(-2);
                        var newRow = '<tr>' +
                            '<td><a href="vital-signs.html">' + nurseService.user.name + '</a></td>' +
                            '<td>' + nurseService.user.ID_Number + '</td>' +
                            '<td>' + nurseService.services.code + '</td>' +
                            '<td>' + (nurseService.user.company_id !== null ? ' @lang('admin.insurance')' : ' @lang('admin.cash')') +
                            '</td>' +
                            '<td>' + formattedDate + '</td>' +
                            '<td>' + (nurseService.type === 3 ? '@lang('admin.service')' : '') + '</td>' +
                            '<td>' + (nurseService.nurse ?
                                '<p class="mb-0 d-flex align-items-center gap-2"><i class="fa fa-circle-check text-info font-18"></i><span>' +
                                nurseService.nurse.name + '</span></p>' :
                                '<p class="mb-0 d-flex align-items-center gap-2"></p>') + '</td>' +
                            '<td>' + nurseService.doctor.name + '</td>' +
                            '<td class="text-end">' +
                            '<div class="dropdown dropdown-action">' +
                            '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>' +
                            '<div class="dropdown-menu dropdown-menu-end">' +
                            '<a href="edit-patient.html" class="dropdown-item" type="button"><i class="fa-solid fa-file m-r-5"></i> @lang('admin.patient_file_clinic')</a>' +
                            '<a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#note_modal" data-bs-note="' +
                            nurseService.notes +
                            '" type="button"><i class="fa-solid fa-notes-medical m-r-5"></i>@lang('admin.note')</a>' +
                            (nurseService.nurse === null ?
                                '<a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#confirm_service" data-bs-patient="' +
                                nurseService.id +
                                '" type="button"><i class="fa-solid fa-check-circle m-r-5"></i>@lang('admin.confirm')</a>' :
                                '') +
                            '<a href="vital-signs.html" class="dropdown-item" type="button"><i class="fa-solid fa-heart-pulse m-r-5"></i>العلامات الحيوية</a>' +
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
                url: '{{ route('nursing-requests.filter') }}',
                type: 'get',
                data: {
                    fromDate: fromDate,
                    toDate: toDate
                },
                success: function(response) {
                    var tableBody = $('#servicesTable tbody');
                    tableBody.empty();
                    $.each(response.nurseServices, function(index, nurseService) {
                        var originalDate = new Date(nurseService.created_at);

                        // Format the date as 'YYYY-MM-DD' (e.g., 2023-12-04)
                        var formattedDate = originalDate.getFullYear() + '-' + ('0' + (originalDate
                            .getMonth() + 1)).slice(-2) + '-' + ('0' + originalDate.getDate())
                            .slice(-2);
                        var newRow = '<tr>' +
                            '<td><a href="vital-signs.html">' + nurseService.user.name + '</a></td>' +
                            '<td>' + nurseService.user.ID_Number + '</td>' +
                            '<td>' + nurseService.services.code + '</td>' +
                            '<td>' + (nurseService.user.company_id !== null ? ' @lang('admin.insurance')' : ' @lang('admin.cash')') +
                            '</td>' +
                            '<td>' + formattedDate + '</td>' +
                            '<td>' + (nurseService.type === 3 ? '@lang('admin.service')' : '') + '</td>' +
                            '<td>' + (nurseService.nurse ?
                                '<p class="mb-0 d-flex align-items-center gap-2"><i class="fa fa-circle-check text-info font-18"></i><span>' +
                                nurseService.nurse.name + '</span></p>' :
                                '<p class="mb-0 d-flex align-items-center gap-2"></p>') + '</td>' +
                            '<td>' + nurseService.doctor.name + '</td>' +
                            '<td class="text-end">' +
                            '<div class="dropdown dropdown-action">' +
                            '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>' +
                            '<div class="dropdown-menu dropdown-menu-end">' +
                            '<a href="edit-patient.html" class="dropdown-item" type="button"><i class="fa-solid fa-file m-r-5"></i> @lang('admin.patient_file_clinic')</a>' +
                            '<a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#note_modal" data-bs-note="' +
                            nurseService.notes +
                            '" type="button"><i class="fa-solid fa-notes-medical m-r-5"></i>@lang('admin.note')</a>' +
                            (nurseService.nurse === null ?
                                '<a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#confirm_service" data-bs-patient="' +
                                nurseService.id +
                                '" type="button"><i class="fa-solid fa-check-circle m-r-5"></i>@lang('admin.confirm')</a>' :
                                '') +
                            '<a href="vital-signs.html" class="dropdown-item" type="button"><i class="fa-solid fa-heart-pulse m-r-5"></i>العلامات الحيوية</a>' +
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

        // function search(e) {
        //     if (e.key === 'Enter' || e.keyCode === 13) {
        //         document.getElementsByClassName('table-loader')[0].style.display = 'flex';
        //         setTimeout(function() {
        //             document.getElementsByClassName('table-loader')[0].style.display = 'none';
        //         }, 3000)
        //     }
        // }

        // function filter() {
        //     document.getElementsByClassName('table-loader')[0].style.display = 'flex';
        //     setTimeout(function() {
        //         document.getElementsByClassName('table-loader')[0].style.display = 'none';
        //     }, 3000)
        // }
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
