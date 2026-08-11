<?php $page = 'appointments-list'; ?>
@extends('layout_new.mainlayout')
@section('content')

    <!-- ========================
        Start Page Content
    ========================= -->

    <div class="page-wrapper">

        <!-- Start Content -->
        <div class="content">

            <!-- Start Page Header -->
            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 pb-3 mb-3 border-1 border-bottom">
                <div class="flex-grow-1">
                    <h4 class="fw-semibold mb-0"> @lang('admin.reception.appointments_list') </h4>
                </div>
                <div class="text-end d-flex">
                    <!-- dropdown-->
{{--                    <div class="dropdown me-1">--}}
{{--                        <a href="javascript:void(0);" class="btn btn-md fs-14 fw-normal border bg-white rounded text-dark d-inline-flex align-items-center"  data-bs-toggle="dropdown">--}}
{{--                            Export<i class="ti ti-chevron-down ms-2"></i>--}}
{{--                        </a>--}}
{{--                        <ul class="dropdown-menu p-2">--}}
{{--                            <li>--}}
{{--                                <a class="dropdown-item" href="#">Download as PDF</a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a class="dropdown-item" href="#">Download as Excel</a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
                    <a href="{{ route('appointments') }}" class="btn btn-primary btn-md me-2">
                        @lang('admin.reception.appointments_list')
                    </a>
                    <div class="bg-white border shadow-sm rounded px-1 pb-0 text-center d-flex align-items-center justify-content-center">
                        <a href="{{route('appointments-list')}}" class="bg-light rounded p-1 d-flex align-items-center justify-content-center"> <i class="ti ti-list fs-14 text-dark"></i></a>
                    </div>

                </div>
            </div>
            <!-- End Page Header -->

            <!--  Start Filter -->
            <div class=" d-flex align-items-center justify-content-between flex-wrap">
                <div class="d-flex align-items-center gap-2">
                    <div class="search-set mb-3">
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <div class="table-search d-flex align-items-center mb-0">
                                <div class="search-input">
                                    <a href="javascript:void(0);" class="btn-searchset"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex table-dropdown mb-3 right-content align-items-center flex-wrap row-gap-3">
                    <div class="dropdown me-2">
                        <a href="javascript:void(0);" class="bg-white border rounded btn btn-md text-dark fs-14 py-1 align-items-center d-flex fw-normal" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                            <i class="ti ti-filter text-gray-5 me-1"></i>@lang('admin.Filters')
                        </a>
                        <div class="dropdown-menu dropdown-lg dropdown-menu-end filter-dropdown p-0" id="filter-dropdown">
                            <div class="d-flex align-items-center justify-content-between border-bottom filter-header">
                                <h4 class="mb-0 fw-bold">@lang('admin.Filter')</h4>
                                <div class="d-flex align-items-center">
                                    <a href="javascript:void(0);" class="link-danger text-decoration-underline">@lang('admin.Clear All')</a>
                                </div>
                            </div>
                            <form method="GET" action="{{ route('appointments-list') }}">
                                <div class="filter-body pb-0">
                                    {{-- Doctor Filter --}}
                                    <div class="mb-3">
                                        <label class="form-label">@lang('admin.Doctor')</label>
                                        <select name="doctor_id" class="form-select">
                                            <option value="">All</option>
                                            @foreach($doctors as $doctor)
                                                <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                                    {{ $doctor->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Date Filter --}}
                                    <div class="mb-3">
                                        <label class="form-label">@lang('admin.Date')</label>
                                        <input type="date" class="form-control" name="date" value="{{ request('date') }}">
                                    </div>

                                    {{-- Status Filter --}}
                                    <div class="mb-3">
                                        <label class="form-label">@lang('admin.reservation_status')</label>
                                        <select name="status_id" class="form-select">
                                            <option value="">All</option>
                                            @foreach($statuses as $status)
                                                <option value="{{ $status->id }}" {{ request('status_id') == $status->id ? 'selected' : '' }}>
                                                    {{ app()->getLocale() == 'en' ? $status->name_en : $status->name_ar }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="filter-footer d-flex align-items-center justify-content-end border-top">
                                    <a href="{{ route('appointments-list') }}" class="btn btn-light btn-md me-2 fw-medium">@lang('admin.Clear')</a>
                                    <button type="submit" class="btn btn-primary btn-md fw-medium">@lang('admin.Filter')</button>
                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="dropdown">
                        <a href="javascript:void(0);"
                           class="dropdown-toggle btn bg-white btn-md d-inline-flex align-items-center fw-normal rounded border text-dark px-2 py-1 fs-14"
                           data-bs-toggle="dropdown">
                            <span class="me-1">@lang('admin.Sort By') :</span>
                            {{ request('sort') === 'oldest' ? __('admin.Oldest') : __('admin.Recent') }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-2">
                            <li>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'recent']) }}"
                                   class="dropdown-item rounded-1">
                                    @lang('admin.Recent')
                                </a>
                            </li>
                            <li>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'oldest']) }}"
                                   class="dropdown-item rounded-1">
                                    @lang('admin.Oldest')
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
            <!--  End Filter -->

            <!--  Start Table -->
            <div class="table-responsive">
                <table class="table datatable table-nowrap">
                    <thead class="">
                    <tr>
                        <td>#</td>
                        <th>@lang('admin.reservation_number')</th>
                        <th>@lang('admin.date')</th>
                        <th>@lang('admin.specialize')</th>
                        <th>@lang('admin.doctor_name')</th>
                        <th>@lang('admin.patient_name')</th>
                        <th>@lang('admin.file_type')</th>
                        <th>@lang('admin.file_number')</th>
                        <th>@lang('admin.reservation_status')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data['reservations'] as $index=>$reservation)
                        <tr class="appointment-row" data-href="{{ route('booking.chat', $reservation->id) }}">
                            <td>{{ $index  + 1 }}</td>
                            <td><a href="{{ route('booking.chat', $reservation->id) }}">{{ $reservation->booking_number }}</a></td>
                            <td>{{ $reservation->date }} {{ $reservation->appointment }}</td>
                            <td>
                                {{ $reservation->doctor && $reservation->doctor->specialties
                                    ? $reservation->doctor->specialties->map(function ($special) {
                                        return app()->getLocale() == 'en'
                                            ? ($special->specialties->name_en ?? null)
                                            : ($special->specialties->name_ar ?? null);
                                    })->filter()->join(', ')
                                    : '' }}
                            </td>
                            <td>{{ $reservation->doctor->name }}</td>
                            <td class="profile-image"><a
                                    href="{{ route('appointments',$reservation->user_id) }}"><img
                                        width="28" height="28"
                                        src="{{ $reservation->user->image ?? null }}"
                                        class="rounded-circle m-r-5"
                                        alt="">{{ collect(explode('-', $reservation->user->name ?? ''))->take(2)->join(' ') }}</a></td>
                            <td>@if($reservation->user->company_id != null)
                                    @lang('admin.insurance')
                                @else
                                    @lang('admin.cash')
                                @endif</td>
                            <td><a>{{ $reservation->user->file_number }}</a></td>
                            <td>{{ app()->getLocale() == 'en' ? $reservation->reservation_status->name_en ?? null : $reservation->reservation_status->name_ar ?? null }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $data['reservations']->links() }}
            </div>

            <!--  End Table -->

        </div>
        <!-- End Content -->

        @component('components.footer')
        @endcomponent

    </div>

    <!-- ========================
        End Page Content
    ========================= -->

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.appointment-row').forEach(function (row) {
                row.style.cursor = 'pointer';
                row.addEventListener('click', function (event) {
                    if (event.target.closest('a, button, input, select, textarea')) {
                        return;
                    }
                    window.location.href = row.dataset.href;
                });
            });
        });
    </script>
@endsection
