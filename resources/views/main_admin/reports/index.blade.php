@extends('layout_new.mainlayout')
@section('content')
    <style>
        .modal-select label {
            z-index: 99999 !important;
        }

        .select2-container--default.select2-container--open {
            z-index: 9999 !important;
        }

        .add-table-invoice {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }

        .success {
            background-color: #28C76F20;
        }

        .success i {
            color: #28C76F !important;
        }

        .danger {
            background-color: #ea545520;
        }

        .danger i {
            color: #ea5455 !important;
        }


    </style>

    <div class="page-wrapper">
        <div class="content">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a>
                            </li>
                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                            <li class="breadcrumb-item active">@lang('main.reports')</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">

                    <div class="card card-table show-entire">
                        <div class="card-body">

                            <!-- Table Header -->
                            <div class="page-table-header mb-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="doctor-table-blk">
                                            <h3>@lang('main.reports') ({{ count($reports) }})</h3>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="javascript:;" class=" me-2"><img src="/assets/img/icons/pdf-icon-01.svg" alt=""></a>
                                        <a href="javascript:;" class=" me-2"><img src="/assets/img/icons/pdf-icon-02.svg" alt=""></a>
                                        <a href="javascript:;" class=" me-2"><img src="/assets/img/icons/pdf-icon-03.svg" alt=""></a>
                                        <a href="javascript:;"><img src="/assets/img/icons/pdf-icon-04.svg" alt=""></a>
                                    </div>
                                </div>
                            </div>
                            <!-- /Table Header -->

                            <div class="staff-search-table">
                                <form action="{{ route('reports.index') }}" method="get">
                                    <div class="row">
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="form-group local-forms">
                                                <label>@lang('main.report_selection')</label>
                                                <select class="form-control" name="app_type">
                                                    @foreach($data['app_types'] as $type)
                                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                    @endforeach
                                                    <option value="13">@lang('main.users')</option>
                                                    <option value="0">@lang('main.packages')</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="form-group local-forms">
                                                <label>@lang('main.report_type')</label>
                                                <select class="form-control" name="report_type">
                                                    <option value="1">@lang('main.clinics_report')</option>
                                                    <option value="2">@lang('main.monthly_comparison')</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-4 col-xl-4">
                                            <div class="form-group local-forms cal-icon">
                                                <label>@lang('admin.From')</label>
                                                <input class="form-control datetimepicker" type="text" name="date_from">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4 col-xl-4">
                                            <div class="form-group local-forms cal-icon">
                                                <label>@lang('admin.To')</label>
                                                <input class="form-control datetimepicker" type="text" name="date_to">
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="form-group local-forms">
                                                <label>@lang('main.cities')</label>
                                                <select class="form-control" name="city_id">
                                                    @foreach($data['cities'] as $city)
                                                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6 col-xl-4 ms-auto">
                                            <div class="doctor-submit">
                                                <button type="submit" class="btn btn-primary submit-list-form me-2">@lang('admin.Search')</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            {{-- ================= Clinics ================= --}}
                            @if($data['app_type'] == 1)
                                <div class="table-responsive">
                                    <table class="table border-0 custom-table comman-table datatable mb-0">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('admin.image')</th>
                                            <th>@lang('admin.name')</th>
                                            <th>@lang('main.subscription_date')</th>
                                            <th>@lang('main.phone')</th>
                                            <th>@lang('main.city')</th>
                                            <th>@lang('main.number_of_doctors')</th>
                                            <th>@lang('main.doctors_total_points')</th>
                                            <th>@lang('main.total_points')</th>
                                            <th>@lang('main.total_bookings_for_new_users')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($reports as $index=>$clinic)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td class="profile-image">
                                                    <a href="{{ route('clinic-details', $clinic->id) }}">
                                                        <img width="50" height="50" src="{{ $clinic->image ?? null }}" class="rounded-circle m-r-5" alt="">
                                                        {{ $clinic->name ?? null }}
                                                    </a>
                                                </td>
                                                <td>{{ $clinic->name }}</td>
                                                <td>{{ $clinic->date_created ?? "" }}</td>
                                                <td>{{ $clinic->phone ?? "" }}</td>
                                                <td>{{ $clinic->city->name_ar ?? "" }}</td>
                                                <td>{{ $clinic->doctors_count ?? 0 }}</td>
                                                <td>{{ $clinic->doctors_total_points ?? 0 }}</td>
                                                <td>{{ $clinic->clinic_points_count ?? 0 }}</td>
                                                <td>{{ $clinic->reservations_clinics_count ?? 0 }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Chart --}}
                                <div class="card mt-4">
                                    <div class="card-body">
                                        <h5>@lang('main.statistics')</h5>
                                        <canvas id="clinicsChart" height="120"></canvas>
                                    </div>
                                </div>
                            @endif

                            {{-- ================= Doctors ================= --}}
                            @if($data['app_type'] == 3)
                                <div class="table-responsive">
                                    <table class="table border-0 custom-table comman-table datatable mb-0">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('admin.image')</th>
                                            <th>@lang('admin.doctor_name')</th>
                                            <th>@lang('main.subscription_date')</th>
                                            <th>@lang('main.phone')</th>
                                            <th>@lang('main.city')</th>
                                            <th>@lang('main.number_of_receipts')</th>
                                            <th>@lang('main.total_points')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($reports as $index=>$doctor)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td class="profile-image">
                                                    <a href="{{ route('doctor-details', $doctor->id) }}">
                                                        <img width="50" height="50" src="{{ $doctor->image ?? null }}" class="rounded-circle m-r-5" alt="">
                                                        {{ $doctor->name ?? null }}
                                                    </a>
                                                </td>
                                                <td>{{ $doctor->name }}</td>
                                                <td>{{ $doctor->date_created ?? "" }}</td>
                                                <td>{{ $doctor->phone ?? "" }}</td>
                                                <td>{{ $doctor->city->name_ar ?? "" }}</td>
                                                <td>{{ $doctor->reservations_count ?? 0 }}</td>
                                                <td>{{ $doctor->clinic_points_count ?? 0 }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Chart --}}
                                <div class="card mt-4">
                                    <div class="card-body">
                                        <h5>@lang('main.statistics')</h5>
                                        <canvas id="doctorsChart" height="120"></canvas>
                                    </div>
                                </div>
                            @endif

                            {{-- ================= Users ================= --}}
                            @if($data['app_type'] == 13)
                                <div class="table-responsive">
                                    <table class="table border-0 custom-table comman-table datatable mb-0">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('admin.image')</th>
                                            <th>@lang('admin.name')</th>
                                            <th>@lang('main.city')</th>
                                            <th>{{ trans('admin.age') }}</th>
                                            <th>@lang('main.expired_date')</th>
                                            <th>@lang('main.package_type')</th>
                                            <th>@lang('main.reservation_complete')</th>
                                            <th>@lang('main.reservation_not_complete')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($reports as $index=>$user)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td class="profile-image">
                                                    <a href="{{ route('clinic-details', $user->id) }}">
                                                        <img width="50" height="50" src="{{ $user->image ?? null }}" class="rounded-circle m-r-5" alt="">
                                                        {{ $user->name ?? null }}
                                                    </a>
                                                </td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->city->name_ar ?? "" }}</td>
                                                <td>{{ \Carbon\Carbon::parse($user->dob)->diff(\Carbon\Carbon::now())->y }}</td>
                                                <td>{{ $user->expired_date ?? 0 }}</td>
                                                <td>{{ app()->getLocale() == 'en' ? $user->package->name_en ?? null : $user->package->name_ar ?? null }}</td>
                                                <td>{{ $user->reservation_completed_count }}</td>
                                                <td>{{ $user->reservation_not_completed_count }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Chart --}}
                                <div class="card mt-4">
                                    <div class="card-body">
                                        <h5>@lang('main.statistics')</h5>
                                        <canvas id="usersChart" height="120"></canvas>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Load Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>


        @if($data['app_type'] == 1)
        new Chart(document.getElementById('clinicsChart'), {
            type: 'bar',
            data: {
                labels: @json($reports->pluck('name')),
                datasets: [
                    {
                        label: "@lang('main.total_points')",
                        data: @json($reports->pluck('clinic_points_count')),
                        backgroundColor: 'rgba(40, 199, 111, 0.6)'
                    },
                    {
                        label: "@lang('main.total_bookings_for_new_users')",
                        data: @json($reports->pluck('reservations_clinics_count')),
                        backgroundColor: 'rgba(234, 84, 85, 0.6)'
                    }
                ]
            }
        });
        @endif

        @if($data['app_type'] == 3)
        new Chart(document.getElementById('doctorsChart'), {
            type: 'line',
            data: {
                labels: @json($reports->pluck('name')),
                datasets: [{
                    label: "@lang('main.total_points')",
                    data: @json($reports->pluck('clinic_points_count')),
                    borderColor: 'rgba(40, 199, 111, 0.9)',
                    fill: false
                }]
            }
        });
        @endif

        @if($data['app_type'] == 13)
        new Chart(document.getElementById('usersChart'), {
            type: 'pie',
            data: {
            labels: ["@lang('main.reservation_complete')", "@lang('main.reservation_not_complete')"],
            datasets: [{
            data: [
            {{ $reports->sum('reservation_completed_count') }},
            {{ $reports->sum('reservation_not_completed_count') }}
            ],
            backgroundColor: ['rgba(40, 199, 111, 0.6)', 'rgba(234, 84, 85, 0.6)']
        }]
        },
            options: {
            responsive: false, // ✅ عشان يحترم ال width/height اللي فوق
            plugins: {
            legend: {
            position: 'bottom',
            labels: {
            boxWidth: 15, // ✅ يصغر المربعات بتاعت الليجند
            font: { size: 12 } // ✅ يصغر الخط
        }
        }
        }
        }
        });
    @endif
    </script>
@endsection
