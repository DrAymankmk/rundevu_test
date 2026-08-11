@extends('includes_admin.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('nursing-requests.index')}}">@lang('admin.Nursing') </a></li>
                            <li class="breadcrumb-item px-2"><i id="breadcrumbArrow"></i></li>
                            <li class="breadcrumb-item active">@lang('admin.services') </li>
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
                                            <h3>@lang('admin.List of services')</h3>
                                            <div class="doctor-search-blk">
                                                {{-- <div class="top-nav-search table-search-blk">
                                                    <input onkeyup="search(event)" type="text" class="form-control"
                                                        placeholder="@lang('admin.search_here')">
                                                    <a class="btn"><img src="/assets/img/icons/search-normal.svg"
                                                            alt=""></a>
                                                </div> --}}
                                                <div class="add-group">
                                                    <a href="javascript:;" class="btn btn-primary doctor-refresh "
                                                        onclick="refreshPage()"><img src="/assets/img/icons/re-fresh.svg"
                                                            alt=""></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end py-2 ms-auto download-grp add-group sm:flex-row flex-col">
                                        <a class="btn btn-primary text-nowrap w-100 border">
                                            <span>@lang('admin.Number services') ({{ $nurseServises->count() }}) </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- /Table Header -->

                            <!-- Filter -->
                            <div class="staff-search-table">
                                <form id="filterForm">
                                    <div class="row">

                                        <div class="col-12 col-md-6 col-lg-4">
                                            <div class="form-group local-forms cal-icon">
                                                <label>@lang('admin.from') </label>
                                                <input class="form-control datetimepicker" type="text" id="fromDate">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-4">
                                            <div class="form-group local-forms cal-icon">
                                                <label>@lang('admin.To') </label>
                                                <input class="form-control datetimepicker" type="text" id="toDate">
                                            </div>
                                        </div>
                                        <input type="hidden" id="hiddenId" value="{{ $nurse_id }}">
                                        <!-- Add a hidden input field -->
                                        <div class="col-12 col-md-6 col-lg-4">
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
                                <div class="table-loader"  id="loader">
                                    <div class="spinner"></div>
                                </div>
                                <div class="table-responsive">
                                    <table id="nurseServicesTableBody"
                                        class="table border-0 custom-table comman-table datatable mb-0">
                                        <thead>
                                            <tr>
                                                <th>@lang('admin.service_code')</th>
                                                <th>@lang('admin.service_name')</th>
                                                <th>@lang('admin.how_often')</th>
                                                <th>@lang('admin.Date')</th>
                                                <th>@lang('admin.price')</th>
                                                <th>@lang('admin.category_type')</th>
                                                <th>@lang('admin.doctor_name')</th>
                                                <th>@lang('admin.file_type')</th>
                                                <th>@lang('admin.file_number')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($nurseServises as $nurseServise)
                                                <tr>
                                                    <td>{{ $nurseServise->services->code }}</td>
                                                    <td>{{ app()->getLocale() == 'en' ? $nurseServise->services->name_en : $nurseServise->services->name_ar }}
                                                    </td>
                                                    <td>1</td>
                                                    <td>{{ $nurseServise->created_at->format('Y-m-d') }}</td>
                                                    <td>{{ $nurseServise->price }}</td>
                                                    <td>
                                                        @if ($nurseServise->type == 1)
                                                            @lang('admin.analysis')
                                                        @elseif($nurseServise->type == 2)
                                                            @lang('admin.rays')
                                                        @elseif($nurseServise->type == 3)
                                                            @lang('admin.service')
                                                        @endif
                                                    </td>
                                                    <td>{{ $nurseServise->doctor->name }}</td>
                                                    <td>
                                                        @if ($nurseServise->user->company_id != null)
                                                            @lang('admin.insurance')
                                                        @else
                                                            @lang('admin.cash')
                                                        @endif
                                                    </td>
                                                    <td>{{ $nurseServise->user->file_number }}</td>
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
    <script>
        function filter() {
            var fromDate = $('#fromDate').val();
            var toDate = $('#toDate').val();
            var nursingServiceId = $('#hiddenId').val();
            var lang = '{{ app()->getLocale() == 'en' }}';
            $.ajax({
                url: '{{ route('nursing-services.nursingServicesfilter', ['id' => ':id']) }}'.replace(':id',
                    nursingServiceId),
                type: 'get',
                data: {
                    fromDate: fromDate,
                    toDate: toDate
                },
                success: function(response) {
                    var tableBody = $('#nurseServicesTableBody tbody');
                    tableBody.empty();

                    $.each(response.nurseServises, function(index, nurseService) {
                        var originalDate = new Date(nurseService.created_at);

                        // Format the date as 'YYYY-MM-DD' (e.g., 2023-12-04)
                        var formattedDate = originalDate.getFullYear() + '-' + ('0' + (originalDate
                                .getMonth() + 1)).slice(-2) + '-' + ('0' + originalDate.getDate())
                            .slice(-2);

                        var newRow = '<tr>' +
                            '<td>' + nurseService.services.code + '</td>' +
                            '<td>' + (lang == 1 ? nurseService.services.name_en : nurseService
                                .services.name_ar) + '</td>' +
                            '<td>1</td>' +
                            '<td>' + formattedDate + '</td>' +
                            '<td>' + nurseService.price + '</td>' +
                            '<td>' + getServiceTypeLabel(nurseService.type) + '</td>' +
                            '<td>' + nurseService.doctor.name + '</td>' +
                            '<td>' + (nurseService.user.company_id != null ? '@lang('admin.insurance')' :
                                '@lang('admin.cash')') + '</td>' +
                            '<td>' + nurseService.user.file_number + '</td>' +
                            '</tr>';
                        tableBody.append(newRow);
                    });
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        }

        function getServiceTypeLabel(type) {
            switch (type) {
                case 1:
                    return '@lang('admin.analysis')';
                case 2:
                    return '@lang('admin.rays')';
                case 3:
                    return '@lang('admin.service')';
                default:
                    return '';
            }
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
