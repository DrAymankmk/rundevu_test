@extends('includes_admin.mainlayout')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                            <li class="breadcrumb-item active">@lang('clinic_reports.doctors')</li>
                        </ul>
                        <h3 class="mt-2 mb-0">@lang('clinic_reports.doctors')</h3>
                    </div>
                </div>
            </div>

            <!-- @include('clinic_reports.partials.nav') -->
            @include('clinic_reports.partials.filters', [
                'showDoctor' => true,
                'showSpecialty' => true,
                'searchPlaceholder' => __('clinic_reports.doctor_name'),
            ])

            @include('clinic_reports.partials.export_buttons', ['exportSection' => 'doctors'])

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">@lang('clinic_reports.top_booked_doctors')</h5></div>
                <div class="card-body table-responsive">
                    <table class="table table-sm">
                        <thead>
                        <tr>
                            <th>@lang('clinic_reports.doctor_name')</th>
                            <th>@lang('clinic_reports.specialty')</th>
                            <th>@lang('clinic_reports.total_reservations')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($topBooked as $row)
                            <tr>
                                <td>{{ $row['doctor_name'] }}</td>
                                <td>{{ $row['specialty'] }}</td>
                                <td>{{ $row['total_reservations'] }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center">@lang('clinic_reports.no_data')</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>@lang('clinic_reports.id')</th>
                            <th>@lang('clinic_reports.doctor_name')</th>
                            <th>@lang('clinic_reports.specialty')</th>
                            <th>@lang('clinic_reports.total_reservations')</th>
                            <th>@lang('clinic_reports.completed_reservations')</th>
                            <th>@lang('clinic_reports.cancelled_reservations')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($doctors as $doctor)
                            <tr>
                                <td>{{ $doctor->id }}</td>
                                <td>{{ $doctor->name }}</td>
                                <td>{{ $doctor->specialty_name }}</td>
                                <td>{{ $doctor->total_reservations }}</td>
                                <td>{{ $doctor->completed_visits }}</td>
                                <td>{{ $doctor->cancelled_visits }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">@lang('clinic_reports.no_data')</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                    {{ $doctors->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
