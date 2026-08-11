@extends('includes_admin.mainlayout')

@section('content')
    <style>
        .employee-shifts-page .card-body {
            padding: 24px;
        }

        .employee-shifts-page .table-responsive {
            overflow-x: auto;
            overflow-y: visible;
        }

        .employee-shifts-page table th,
        .employee-shifts-page table td {
            white-space: normal;
            vertical-align: middle;
        }

        .employee-shifts-page .shift-day-time {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
        }

        .employee-shifts-page .shift-time-range {
            display: inline-flex;
            align-items: center;
            min-height: 24px;
            padding: 4px 10px;
            border: 1px solid #d9e5ff;
            border-radius: 4px;
            background: #f3f7ff;
            color: #24477f;
            font-size: 12px;
            font-weight: 600;
            line-height: 1.2;
        }

        .employee-shifts-page #calendar {
            width: 100%;
            min-height: 620px;
        }

        .employee-shifts-page .fc,
        .employee-shifts-page .fc-view-harness,
        .employee-shifts-page .fc-view-harness-active {
            width: 100%;
            min-height: 620px;
        }

        .employee-shifts-page .fc-scroller,
        .employee-shifts-page .fc-daygrid-body,
        .employee-shifts-page .fc-daygrid-body table {
            overflow: visible !important;
            height: auto !important;
            width: 100% !important;
        }

        .employee-shifts-page .fc-daygrid-event {
            white-space: normal;
            line-height: 1.35;
            padding: 3px 5px;
            border-radius: 4px;
        }

        .employee-shifts-page .fc-toolbar {
            gap: 12px;
            flex-wrap: wrap;
        }

        .employee-shifts-page .schedule-overview {
            border: 1px solid #e7edf5;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
        }

        .employee-shifts-page .schedule-overview-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            padding: 18px 20px;
            border-bottom: 1px solid #edf2f7;
        }

        .employee-shifts-page .schedule-overview-header h5 {
            margin: 0 0 4px;
            font-size: 18px;
            font-weight: 700;
        }

        .employee-shifts-page .schedule-overview-header p {
            margin: 0;
            color: #64748b;
            font-size: 13px;
        }

        .employee-shifts-page .schedule-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .employee-shifts-page .schedule-stat {
            min-width: 94px;
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            background: #f8fafc;
            text-align: center;
        }

        .employee-shifts-page .schedule-stat strong {
            display: block;
            color: #0f172a;
            font-size: 18px;
            line-height: 1;
        }

        .employee-shifts-page .schedule-stat span {
            color: #64748b;
            font-size: 12px;
        }

        .employee-shifts-page .schedule-overview-body {
            padding: 20px;
        }

        .employee-shifts-page .schedule-card {
            height: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #fbfdff;
            padding: 16px;
        }

        .employee-shifts-page .schedule-card-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
        }

        .employee-shifts-page .schedule-card-title h6 {
            margin: 0;
            color: #0f172a;
            font-size: 15px;
            font-weight: 700;
        }

        .employee-shifts-page .schedule-time {
            display: inline-flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 4px;
            background: #eaf4ff;
            color: #075985;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .employee-shifts-page .schedule-days {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .employee-shifts-page .schedule-day {
            display: inline-flex;
            align-items: center;
            min-height: 28px;
            padding: 5px 10px;
            border-radius: 4px;
            background: #eefcf3;
            color: #166534;
            font-size: 12px;
            font-weight: 700;
        }

        .employee-shifts-page .holiday-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .employee-shifts-page .holiday-pill {
            display: inline-flex;
            align-items: center;
            min-height: 30px;
            padding: 6px 10px;
            border: 1px solid #fde68a;
            border-radius: 4px;
            background: #fffbeb;
            color: #92400e;
            font-size: 12px;
            font-weight: 700;
        }

        .employee-shifts-page .schedule-empty {
            margin: 0;
            padding: 14px;
            border: 1px dashed #cbd5e1;
            border-radius: 6px;
            color: #64748b;
            text-align: center;
        }

        @media (max-width: 767.98px) {
            .employee-shifts-page .card-body {
                padding: 16px;
            }

            .employee-shifts-page .schedule-overview-header {
                display: block;
            }

            .employee-shifts-page .schedule-stats {
                margin-top: 14px;
            }
        }
    </style>
    <div class="page-body employee-shifts-page">
        <div class="container-fluid">

            {{-- Page Header --}}
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3>@lang('admin.shifts_item') - {{ $employee->name }}</h3>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @php
                $isArabic = app()->getLocale() == 'ar';
                $txt = function ($ar, $en) use ($isArabic) {
                    return $isArabic ? html_entity_decode($ar, ENT_QUOTES, 'UTF-8') : $en;
                };
                $workSummary = $employeeShifts
                    ->where('status', 1)
                    ->filter(function ($item) {
                        return $item->day_id && $item->shift;
                    })
                    ->groupBy(function ($item) {
                        return implode('_', [
                            $item->shift_id,
                            optional($item->shift)->time_from,
                            optional($item->shift)->time_to,
                        ]);
                    })
                    ->map(function ($items) use ($days, $isArabic, $txt) {
                        $first = $items->first();
                        $dayNames = $items->pluck('day_id')
                            ->unique()
                            ->map(function ($dayId) use ($days, $isArabic) {
                                $day = $days->firstWhere('id', $dayId);
                                return $day ? ($isArabic ? $day->name_ar : $day->name_en) : null;
                            })
                            ->filter()
                            ->values();

                        return [
                            'name' => optional($first->shift)->name ?: $txt('&#1594;&#1610;&#1585; &#1605;&#1581;&#1583;&#1583;', 'Not set'),
                            'time_from' => optional($first->shift)->time_from
                                ? \Carbon\Carbon::parse($first->shift->time_from)->format('H:i')
                                : null,
                            'time_to' => optional($first->shift)->time_to
                                ? \Carbon\Carbon::parse($first->shift->time_to)->format('H:i')
                                : null,
                            'days' => $dayNames,
                        ];
                    })
                    ->values();

                $holidaySummary = $employeeShifts
                    ->where('status', '!=', 1)
                    ->filter(function ($item) {
                        return !empty($item->dateA);
                    })
                    ->unique('dateA')
                    ->values();

                $workDaysCount = $workSummary->sum(function ($item) {
                    return $item['days']->count();
                });
            @endphp

            <div class="schedule-overview mb-4">
                <div class="schedule-overview-header">
                    <div>
                        <h5>{{ $txt('&#1583;&#1608;&#1585;&#1577; &#1593;&#1605;&#1604; &#1575;&#1604;&#1583;&#1603;&#1578;&#1608;&#1585;', 'Doctor Work Cycle') }}</h5>
                        <p>{{ $txt('&#1605;&#1604;&#1582;&#1589; &#1587;&#1585;&#1610;&#1593; &#1604;&#1571;&#1610;&#1575;&#1605; &#1575;&#1604;&#1593;&#1605;&#1604; &#1608;&#1605;&#1608;&#1575;&#1593;&#1610;&#1583; &#1575;&#1604;&#1588;&#1610;&#1601;&#1578;&#1575;&#1578; &#1608;&#1575;&#1604;&#1573;&#1580;&#1575;&#1586;&#1575;&#1578; &#1575;&#1604;&#1605;&#1587;&#1580;&#1604;&#1577;.', 'A quick summary of work days, shift times, and registered holidays.') }}</p>
                    </div>
                    <div class="schedule-stats">
                        <div class="schedule-stat">
                            <strong>{{ $workSummary->count() }}</strong>
                            <span>{{ $txt('&#1588;&#1610;&#1601;&#1578;&#1575;&#1578;', 'Shifts') }}</span>
                        </div>
                        <div class="schedule-stat">
                            <strong>{{ $workDaysCount }}</strong>
                            <span>{{ $txt('&#1571;&#1610;&#1575;&#1605; &#1593;&#1605;&#1604;', 'Work days') }}</span>
                        </div>
                        <div class="schedule-stat">
                            <strong>{{ $holidaySummary->count() }}</strong>
                            <span>{{ $txt('&#1573;&#1580;&#1575;&#1586;&#1575;&#1578;', 'Holidays') }}</span>
                        </div>
                    </div>
                </div>

                <div class="schedule-overview-body">
                    <div class="row">
                        <div class="col-lg-8 mb-3 mb-lg-0">
                            <h6 class="mb-3">{{ $txt('&#1571;&#1610;&#1575;&#1605; &#1575;&#1604;&#1593;&#1605;&#1604;', 'Working Days') }}</h6>
                            <div class="row">
                                @forelse($workSummary as $summary)
                                    <div class="col-md-6 mb-3">
                                        <div class="schedule-card">
                                            <div class="schedule-card-title">
                                                <h6>{{ $summary['name'] }}</h6>
                                                @if($summary['time_from'] && $summary['time_to'])
                                                    <span class="schedule-time">
                                                        {{ $txt('&#1605;&#1606;', 'From') }} {{ $summary['time_from'] }}
                                                        {{ $txt('&#1573;&#1604;&#1609;', 'To') }} {{ $summary['time_to'] }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="schedule-days">
                                                @foreach($summary['days'] as $dayName)
                                                    <span class="schedule-day">{{ $dayName }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <p class="schedule-empty">{{ $txt('&#1604;&#1575; &#1578;&#1608;&#1580;&#1583; &#1571;&#1610;&#1575;&#1605; &#1593;&#1605;&#1604; &#1605;&#1587;&#1580;&#1604;&#1577; &#1604;&#1607;&#1584;&#1575; &#1575;&#1604;&#1583;&#1603;&#1578;&#1608;&#1585;.', 'No working days are registered for this doctor.') }}</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <h6 class="mb-3">{{ $txt('&#1575;&#1604;&#1573;&#1580;&#1575;&#1586;&#1575;&#1578;', 'Holidays') }}</h6>
                            @if($holidaySummary->count())
                                <div class="holiday-list">
                                    @foreach($holidaySummary->take(12) as $holiday)
                                        <span class="holiday-pill">{{ $holiday->dateA }}</span>
                                    @endforeach
                                </div>
                            @else
                                <p class="schedule-empty">{{ $txt('&#1604;&#1575; &#1578;&#1608;&#1580;&#1583; &#1573;&#1580;&#1575;&#1586;&#1575;&#1578; &#1605;&#1587;&#1580;&#1604;&#1577;.', 'No holidays are registered.') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Add Shift / Holiday --}}
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('add-employee-shift', $employee->id) }}"
                          method="POST"
                          id="shiftForm">
                        @csrf

                        <div class="row">

                            {{-- Type --}}
                            <div class="col-md-4 mb-3">
<label>{{ $txt('&#1575;&#1604;&#1606;&#1608;&#1593;', 'Type') }}</label>
                                <select class="form-control"
                                        name="status"
                                        id="shiftType"
                                        onchange="toggleFormFields()"
                                        required>
<option value="1">{{ $txt('&#1588;&#1610;&#1601;&#1578;', 'Shift') }}</option>
<option value="0">{{ $txt('&#1573;&#1580;&#1575;&#1586;&#1577;', 'Holiday') }}</option>
                                </select>
                            </div>

                            {{-- Shift --}}
                            <div class="col-md-4 mb-3" id="shift-box">
<label>{{ $txt('&#1575;&#1604;&#1588;&#1610;&#1601;&#1578;', 'Shift') }}</label>
                                <select class="form-control"
                                        name="shift_id"
                                        id="firstShift">
<option value="">{{ $txt('&#1575;&#1582;&#1578;&#1585; &#1588;&#1610;&#1601;&#1578;', 'Select shift') }}</option>
                                    @foreach($data['shifts'] as $shift)
                                        <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Days --}}
                            <div class="col-md-4 mb-3" id="days-box">
<label>{{ $txt('&#1571;&#1610;&#1575;&#1605; &#1575;&#1604;&#1571;&#1587;&#1576;&#1608;&#1593;', 'Week days') }}</label>
                                <div class="border p-2 rounded">
                                    @foreach($days as $day)
                                        <label class="mr-2 d-block d-md-inline-block mb-2">
                                            <input type="checkbox"
                                                   class="day-checkbox"
                                                   name="days[]"
                                                   value="{{ $day->id }}">
                                            {{ app()->getLocale() == 'en' ? $day->name_en : $day->name_ar }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Holiday Range --}}
                            <div class="col-md-6 mb-3" id="holiday-box" style="display:none">
<label>{{ $txt('&#1601;&#1578;&#1585;&#1577; &#1575;&#1604;&#1573;&#1580;&#1575;&#1586;&#1577;', 'Holiday period') }}</label>

                                <button type="button"
                                        class="btn btn-sm btn-outline-warning mb-2"
                                        id="oneDayHolidayBtn">
{{ $txt('&#1573;&#1580;&#1575;&#1586;&#1577; &#1610;&#1608;&#1605; &#1608;&#1575;&#1581;&#1583;', 'One day holiday') }}
                                </button>

                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="date"
                                               class="form-control"
                                               name="holiday_from"
                                               id="holiday_from">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="date"
                                               class="form-control"
                                               name="holiday_to"
                                               id="holiday_to">
                                    </div>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="col-md-12 text-right">
                                <button class="btn btn-primary">
{{ $txt('&#1581;&#1601;&#1592;', 'Save') }}
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
<h5>{{ $txt('&#1575;&#1604;&#1588;&#1610;&#1601;&#1578;&#1575;&#1578; &#1608;&#1575;&#1604;&#1573;&#1580;&#1575;&#1586;&#1575;&#1578; &#1575;&#1604;&#1581;&#1575;&#1604;&#1610;&#1577;', 'Current shifts and holidays') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
<th>{{ $txt('&#1575;&#1604;&#1606;&#1608;&#1593;', 'Type') }}</th>
<th>{{ $txt('&#1575;&#1604;&#1578;&#1601;&#1575;&#1589;&#1610;&#1604;', 'Details') }}</th>
<th>{{ $txt('&#1575;&#1604;&#1571;&#1610;&#1575;&#1605;/&#1575;&#1604;&#1578;&#1575;&#1585;&#1610;&#1582;', 'Days/Date') }}</th>
<th>{{ $txt('&#1575;&#1604;&#1573;&#1580;&#1585;&#1575;&#1569;&#1575;&#1578;', 'Actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($employeeShifts as $index => $shift)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($shift->status == 1)
<span class="badge badge-primary">{{ $txt('&#1588;&#1610;&#1601;&#1578;', 'Shift') }}</span>
                                        @else
<span class="badge badge-warning">{{ $txt('&#1573;&#1580;&#1575;&#1586;&#1577;', 'Holiday') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($shift->status == 1)
{{ $shift->shift->name ?? $txt('&#1594;&#1610;&#1585; &#1605;&#1581;&#1583;&#1583;', 'Not set') }}
                                        @else
{{ $txt('&#1573;&#1580;&#1575;&#1586;&#1577;', 'Holiday') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($shift->status == 1)
                                            @if($shift->day_id)
                                                @php
                                                    $day = $days->firstWhere('id', $shift->day_id);
                                                    $timeFrom = optional($shift->shift)->time_from
                                                        ? \Carbon\Carbon::parse($shift->shift->time_from)->format('H:i')
                                                        : null;
                                                    $timeTo = optional($shift->shift)->time_to
                                                        ? \Carbon\Carbon::parse($shift->shift->time_to)->format('H:i')
                                                        : null;
                                                @endphp
                                                <div class="shift-day-time">
                                                    <span class="badge badge-info">
                                                        {{ $day ? (app()->getLocale() == 'en' ? $day->name_en : $day->name_ar) : '-' }}
                                                    </span>
                                                    @if($timeFrom && $timeTo)
                                                        <span class="shift-time-range">
                                                            {{ $txt('&#1605;&#1606;', 'From') }}
                                                            {{ $timeFrom }}
                                                            {{ $txt('&#1573;&#1604;&#1609;', 'To') }}
                                                            {{ $timeTo }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @elseif($shift->dateA)
                                                @php
                                                    $timeFrom = optional($shift->shift)->time_from
                                                        ? \Carbon\Carbon::parse($shift->shift->time_from)->format('H:i')
                                                        : null;
                                                    $timeTo = optional($shift->shift)->time_to
                                                        ? \Carbon\Carbon::parse($shift->shift->time_to)->format('H:i')
                                                        : null;
                                                @endphp
                                                <div class="shift-day-time">
                                                    <span class="badge badge-info">{{ \Carbon\Carbon::parse($shift->dateA)->format('Y-m-d') }}</span>
                                                    @if($timeFrom && $timeTo)
                                                        <span class="shift-time-range">
                                                            {{ $txt('&#1605;&#1606;', 'From') }}
                                                            {{ $timeFrom }}
                                                            {{ $txt('&#1573;&#1604;&#1609;', 'To') }}
                                                            {{ $timeTo }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        @else
                                            @if(strpos($shift->dateA, ' - ') !== false)
                                                @php
                                                    $dates = explode(' - ', $shift->dateA);
                                                    $startDate = \Carbon\Carbon::parse($dates[0])->format('Y-m-d');
                                                    $endDate = \Carbon\Carbon::parse($dates[1])->format('Y-m-d');
                                                @endphp
                                                <span class="badge badge-warning">
{{ $txt('&#1605;&#1606;', 'From') }} {{ $startDate }} {{ $txt('&#1573;&#1604;&#1609;', 'To') }} {{ $endDate }}
                                    </span>
                                            @else
                                                <span class="badge badge-warning">
                                        {{ $shift->dateA }}
                                    </span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button"
                                                class="btn btn-sm btn-danger delete-shift"
                                                data-id="{{ $shift->id }}"
                                                data-toggle="modal"
                                                data-target="#deleteModal">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
<td colspan="5" class="text-center">{{ $txt('&#1604;&#1575; &#1578;&#1608;&#1580;&#1583; &#1588;&#1610;&#1601;&#1578;&#1575;&#1578; &#1571;&#1608; &#1573;&#1580;&#1575;&#1586;&#1575;&#1578; &#1605;&#1590;&#1575;&#1601;&#1577;', 'No shifts or holidays added') }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Calendar --}}
            <div class="card">
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
<h5 class="modal-title">{{ $txt('&#1578;&#1571;&#1603;&#1610;&#1583; &#1575;&#1604;&#1581;&#1584;&#1601;', 'Delete confirmation') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
<p>{{ $txt('&#1607;&#1604; &#1571;&#1606;&#1578; &#1605;&#1578;&#1571;&#1603;&#1583; &#1605;&#1606; &#1581;&#1584;&#1601; &#1607;&#1584;&#1575; &#1575;&#1604;&#1593;&#1606;&#1589;&#1585;&#1567;', 'Are you sure you want to delete this item?') }}</p>
                </div>
                <div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ $txt('&#1573;&#1604;&#1594;&#1575;&#1569;', 'Cancel') }}</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
<button type="submit" class="btn btn-danger">{{ $txt('&#1581;&#1584;&#1601;', 'Delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

{{-- Scripts --}}
<script src="/assets/js/jquery-3.6.1.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<script>
    /* ================= TOGGLE ================= */
    function toggleFormFields() {
        const type = $('#shiftType').val();

        if (type === '1') {
            $('#shift-box, #days-box').show();
            $('#holiday-box').hide();

            $('#holiday_from, #holiday_to').val('');
        } else {
            $('#shift-box, #days-box').hide();
            $('#holiday-box').show();

            $('#firstShift').val('');
            $('.day-checkbox').prop('checked', false);
        }
    }

    /* ================= VALIDATION ================= */
    function validateForm() {

        const type = $('#shiftType').val();

        if (type === '1') {
            if (!$('#firstShift').val()) {
alert(@json($txt('&#1575;&#1582;&#1578;&#1585; &#1588;&#1610;&#1601;&#1578;', 'Select shift')));
                return false;
            }

            if ($('.day-checkbox:checked').length === 0) {
alert(@json($txt('&#1575;&#1582;&#1578;&#1585; &#1571;&#1610;&#1575;&#1605; &#1575;&#1604;&#1571;&#1587;&#1576;&#1608;&#1593;', 'Select week days')));
                return false;
            }
        }

        if (type === '0') {
            const from = $('#holiday_from').val();
            const to   = $('#holiday_to').val();

            if (!from || !to) {
alert(@json($txt('&#1575;&#1582;&#1578;&#1585; &#1601;&#1578;&#1585;&#1577; &#1575;&#1604;&#1573;&#1580;&#1575;&#1586;&#1577;', 'Select holiday period')));
                return false;
            }

            if (from > to) {
alert(@json($txt('&#1578;&#1575;&#1585;&#1610;&#1582; &#1575;&#1604;&#1576;&#1583;&#1575;&#1610;&#1577; &#1576;&#1593;&#1583; &#1575;&#1604;&#1606;&#1607;&#1575;&#1610;&#1577;', 'Start date is after end date')));
                return false;
            }
        }

        return true;
    }

    /* ================= DELETE ================= */
    $(document).ready(function () {
        $('.delete-shift').on('click', function() {
            const id = $(this).data('id');
            $('#deleteForm').attr('action', '/admin/destroy-employee-shift/' + id);
        });
    });

    /* ================= INIT ================= */
    $(document).ready(function () {

        toggleFormFields();

        $('#shiftForm').on('submit', function (e) {
            if (!validateForm()) {
                e.preventDefault();
            }
        });

        $('#oneDayHolidayBtn').on('click', function () {
            const today = new Date().toISOString().split('T')[0];
            $('#holiday_from').val(today);
            $('#holiday_to').val(today);
        });

    });

    /* ================= CALENDAR ================= */
    document.addEventListener('DOMContentLoaded', function () {

        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            editable: true,
            selectable: true,
            locale: '{{ app()->getLocale() == 'ar' ? 'ar' : 'en' }}',
            direction: '{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}',
            displayEventTime: false,

            events: {!! json_encode($calendarEvents) !!},

            eventContent: function(arg) {
                const title = document.createElement('div');
                title.className = 'fc-shift-title';
                title.textContent = arg.event.title;

                return { domNodes: [title] };
            },

            eventDidMount: function(info) {
                if (info.event.extendedProps.type === 'holiday') {
                    info.el.style.backgroundColor = '#f39c12';
                    info.el.style.borderColor = '#e67e22';
                } else {
                    info.el.style.backgroundColor = '#3788d8';
                    info.el.style.borderColor = '#2c6aa0';
                }
            },

            eventClick: function(info) {
                if (info.event.extendedProps.type === 'holiday') {
alert(@json($txt('&#1573;&#1580;&#1575;&#1586;&#1577; &#1605;&#1606;', 'Holiday from')) + ' ' + info.event.start.toLocaleDateString() + ' ' + @json($txt('&#1573;&#1604;&#1609;', 'to')) + ' ' + info.event.end.toLocaleDateString());
                } else {
alert(@json($txt('&#1588;&#1610;&#1601;&#1578;:', 'Shift:')) + ' ' + info.event.title);
                }
            },

            select: function(info) {
                $('#shiftType').val('0').change();
                $('#holiday_from').val(info.startStr);
                $('#holiday_to').val(info.endStr);

                $('html, body').animate({
                    scrollTop: $('#shiftForm').offset().top
                }, 500);
            },

            eventDrop: function(info) {
                if(confirm(@json($txt('&#1607;&#1604; &#1578;&#1585;&#1610;&#1583; &#1578;&#1581;&#1583;&#1610;&#1579; &#1578;&#1575;&#1585;&#1610;&#1582; &#1575;&#1604;&#1573;&#1580;&#1575;&#1586;&#1577;&#1567;', 'Do you want to update the holiday date?')))) {
                    alert(@json($txt('&#1578;&#1581;&#1578;&#1575;&#1580; &#1573;&#1604;&#1609; &#1573;&#1590;&#1575;&#1601;&#1577; API &#1604;&#1604;&#1578;&#1581;&#1583;&#1610;&#1579;', 'You need to add an API for update')));
                } else {
                    info.revert();
                }
            }
        });

        calendar.render();
    });
</script>

<style>
    #calendar {
        max-width: 100%;
        min-height: 500px;
    }

    .fc-event {
        cursor: pointer;
    }

    .fc-shift-title {
        width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 12px;
        line-height: 1.35;
        font-weight: 600;
    }

    .badge {
        font-size: 12px;
        padding: 5px 8px;
    }

    .day-checkbox {
        margin-left: 5px;
    }

    .table td {
        vertical-align: middle;
    }
</style>
