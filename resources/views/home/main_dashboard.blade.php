@php
    use App\Models\AppType;
    use Carbon\Carbon;
@endphp

@extends('layout_new.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content pb-0">

            <!-- Page Header -->
            <div class="d-flex align-items-sm-center justify-content-between flex-wrap gap-2 mb-4">
                <h4 class="fw-bold mb-0">{{ trans('admin.Admin Dashboard') }}</h4>
            </div>

            <!-- =======================
                TOP STAT CARDS
            ======================== -->
            <div class="row">
                @php
                    $bgColors = ['bg-primary','bg-success','bg-danger','bg-warning','bg-info','bg-secondary'];
                @endphp

                @foreach($data['app_types'] as $app_type)
                    @php $bgColor = $bgColors[$loop->index % count($bgColors)]; @endphp
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="position-relative border card rounded-2 shadow-sm">
                            <img src="{{ asset('build/img/bg/bg-01.svg') }}" class="position-absolute start-0 top-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                <span class="avatar {{ $bgColor }} rounded-circle">
                                    <i class="ti ti-calendar-heart fs-24"></i>
                                </span>
                                    <div class="text-end">
                                    <span class="badge {{ $app_type->growth_percent >= 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $app_type->growth_percent >= 0 ? '+' : '' }}{{ $app_type->growth_percent }}%
                                    </span>
                                        <p class="fs-13 mb-0">@lang('main.in last 7 Days')</p>
                                    </div>
                                </div>
                                <p class="mb-1">
                                    {{ app()->getLocale() == 'en' ? $app_type->name_en : $app_type->name_ar }}
                                </p>
                                <h3 class="fw-bold mb-0">{{ $app_type->accounts_count }}</h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- =======================
                STATISTICS + POPULAR DOCTORS
            ======================== -->
            <div class="row">
                <div class="col-xl-8">

                    <!-- Appointment Statistics -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">
                            <h5 class="fw-bold mb-0">@lang('main.Appointment Statistics')</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-2 mb-3">
                                @foreach($data['statuses'] as $status)
                                    <div class="col-md-3 col-sm-6">
                                        <div class="bg-light border p-2 text-center rounded-2">
                                            <p class="mb-1">
                                                <i class="ti ti-point-filled {{ $status->color }}"></i>
                                                {{ app()->getLocale() == 'en' ? $status->name_en : $status->name_ar }}
                                            </p>
                                            <h5 class="fw-bold mb-0">{{ $status->reservations_count }}</h5>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div id="s-col-19"></div>
                        </div>
                    </div>

                    <!-- Popular Doctors -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="fw-bold mb-0">@lang('main.Popular Doctors')</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach($data['top_doctors'] as $doctor)
                                    <div class="col-md-4">
                                        <div class="border rounded-2 p-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <img src="{{ $doctor->image }}" class="rounded-circle me-2" width="45">
                                                <div>
                                                    <strong>Dr. {{ $doctor->name }}</strong>
                                                    <div class="fs-13 text-muted">
                                                        {{ $doctor->reservations_count }} @lang('main.Bookings')
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- =======================
                        EXPIRING CLINICS
                    ======================== -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="fw-bold mb-0">@lang('main.Clinics Expiring Soon')</h5>

                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-white" data-bs-toggle="dropdown">
                                    <span id="expiryLabel">@lang('main.Weekly')</span>
                                    <i class="ti ti-chevron-down ms-1"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item expiry-btn" data-target="week" href="#">@lang('main.Weekly')</a></li>
                                    <li><a class="dropdown-item expiry-btn" data-target="month" href="#">@lang('main.Monthly')</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="card-body">

                            <!-- WEEK -->
                            <div id="expiry-week">
                                <div class="row g-3">
                                    @forelse($data['expiring_clinics_week'] as $clinic)
                                        <div class="col-md-4">
                                            <div class="border rounded-2 p-3 h-100">
                                                <img src="{{ $clinic->image }}" class="rounded-circle me-2" width="45">
                                                <h6 class="fw-semibold">{{ $clinic->name }}</h6>
                                                <span class="badge badge-soft-danger">
                                                @lang('main.Expiry Date'):
                                                {{ Carbon::parse($clinic->package_end_date)->translatedFormat('d M Y') }}
                                            </span>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted">@lang('main.No clinics expiring this week')</p>
                                    @endforelse
                                </div>
                            </div>

                            <!-- MONTH -->
                            <div id="expiry-month" class="d-none">
                                <div class="row g-3">
                                    @forelse($data['expiring_clinics_month'] as $clinic)
                                        <div class="col-md-4">
                                            <div class="border rounded-2 p-3 h-100">
                                                <h6 class="fw-semibold">{{ $clinic->name }}</h6>
                                                <span class="badge badge-soft-warning">
                                                @lang('main.Expiry Date'):
                                                {{ Carbon::parse($clinic->package_end_date)->translatedFormat('d M Y') }}
                                            </span>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted">@lang('main.No clinics expiring this month')</p>
                                    @endforelse
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- Right Column Appointments -->
                <div class="col-xl-4">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="fw-bold mb-0">@lang('main.Appointments')</h5>
                        </div>
                        <div class="card-body">
                            @foreach($data['appointments']->take(3) as $appointment)
                                <div class="bg-light p-3 rounded-2 mb-2">
                                    <strong>{{ $appointment->doctor->name }}</strong>
                                    <div class="fs-13 text-muted">
@php
    [$start, $end] = explode(' - ', $appointment->appointment);

    $startDate = \Carbon\Carbon::parse($appointment->date . ' ' . $start);
    $endDate   = \Carbon\Carbon::parse($appointment->date . ' ' . $end);
@endphp

{{ $startDate->translatedFormat('D, d M Y, h:i A') }} 
- 
{{ $endDate->translatedFormat('h:i A') }}
                                    </div>
                                </div>
                            @endforeach
                            <a href="{{ route('appointments-list') }}" class="btn btn-light w-100">
                                @lang('main.View All Appointments')
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        @component('components.footer') @endcomponent
    </div>

    <!-- Toggle Script -->
    <script>
        document.querySelectorAll('.expiry-btn').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                document.getElementById('expiry-week').classList.add('d-none');
                document.getElementById('expiry-month').classList.add('d-none');
                document.getElementById('expiry-' + btn.dataset.target).classList.remove('d-none');
                document.getElementById('expiryLabel').innerText = btn.innerText;
            });
        });
    </script>

@endsection
