@extends(in_array((int) auth()->user()->app_type, [1, 7, 11], true) ? 'includes_admin.mainlayout' : 'layout_new.mainlayout')

@php
    $paymentLabel = function ($method) {
        return $method === 'online'
            ? __('financial_reports.online')
            : __('financial_reports.cash');
    };

    $settlementDirectionLabel = function ($direction) {
        if ($direction === 'platform_pays_clinic') {
            return __('financial_reports.platform_settles_to_clinic');
        }

        if ($direction === 'clinic_pays_platform') {
            return __('financial_reports.clinic_settles_to_platform');
        }

        return __('financial_reports.annual_subscription_only');
    };
@endphp

@section('content')
    @if(in_array((int) auth()->user()->app_type, [1, 7, 11], true))
        <div class="page-body">
            <div class="container-fluid">
    @else
        <div class="page-wrapper">
            <div class="content">
    @endif
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a>
                            </li>
                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                            <li class="breadcrumb-item active">@lang('financial_reports.title')</li>
                        </ul>
                    </div>
                </div>
            </div>

            <form method="get" action="{{ route('financial-reports.index') }}" class="mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">@lang('financial_reports.month')</label>
                        <input type="month" name="month" value="{{ $month }}" class="form-control">
                    </div>
                    @if($isMainAdmin)
                        <div class="col-md-4">
                            <label class="form-label">@lang('financial_reports.clinic_or_center')</label>
                            <select name="clinic_id" class="form-control">
                                <option value="">@lang('financial_reports.all_clinics')</option>
                                @foreach($clinics as $clinic)
                                    <option value="{{ $clinic->id }}" {{ (string) $clinicId === (string) $clinic->id ? 'selected' : '' }}>
                                        {{ $clinic->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="col-md-3">
                        <label class="form-label">@lang('financial_reports.report_type')</label>
                        <select name="report_type" class="form-control">
                            @foreach($reportTypes as $value => $label)
                                <option value="{{ $value }}" {{ (string) $reportType === (string) $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">@lang('financial_reports.show_report')</button>
                    </div>
                </div>
            </form>

            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">@lang('financial_reports.total_collected')</p>
                            <h4>{{ number_format($totals['total_collected'], 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">@lang('financial_reports.platform_commission')</p>
                            <h4>{{ number_format($totals['platform_commission'], 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">@lang('financial_reports.platform_due_to_clinic')</p>
                            <h4>{{ number_format($totals['platform_due_to_clinic'], 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">@lang('financial_reports.clinic_due_to_platform')</p>
                            <h4>{{ number_format($totals['clinic_due_to_platform'], 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            @if($totals['pending_online_amount'] > 0)
                <div class="alert alert-warning">
                    {{ __('financial_reports.pending_online_warning', ['amount' => number_format($totals['pending_online_amount'], 2)]) }}
                </div>
            @endif

            @if($clinicDetailedReport)
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row align-items-center mb-3">
                            <div class="col">
                                <h3 class="mb-1">@lang('financial_reports.clinic_detailed_report')</h3>
                                <p class="text-muted mb-0">
                                    {{ optional($clinicDetailedReport['clinic'])->name }}
                                    @if($reportType)
                                        - {{ $reportTypes[$reportType] ?? '' }}
                                    @endif
                                </p>
                            </div>
                            <div class="col-auto text-end">
                                <span class="badge bg-primary">
                                    {{ __($clinicDetailedReport['settlement_label_key']) }}
                                </span>
                                <h4 class="mt-2 mb-0">{{ number_format($clinicDetailedReport['settlement_amount'], 2) }}</h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="border rounded p-3 mb-3">
                                    <p class="text-muted mb-1">@lang('financial_reports.current_contract_model')</p>
                                    <h5 class="mb-0">
                                        {{ $clinicDetailedReport['contract'] ? ($reportTypes[$clinicDetailedReport['contract']->contract_model] ?? $clinicDetailedReport['contract']->contract_model) : __('financial_reports.not_defined') }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border rounded p-3 mb-3">
                                    <p class="text-muted mb-1">@lang('financial_reports.current_commission_rate')</p>
                                    <h5 class="mb-0">{{ $clinicDetailedReport['contract'] ? number_format((float) $clinicDetailedReport['contract']->commission_rate, 2) : '0.00' }}%</h5>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border rounded p-3 mb-3">
                                    <p class="text-muted mb-1">@lang('financial_reports.completed_bookings')</p>
                                    <h5 class="mb-0">{{ $clinicDetailedReport['totals']['completed_bookings_count'] }}</h5>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border rounded p-3 mb-3">
                                    <p class="text-muted mb-1">@lang('financial_reports.clinic_net_amount')</p>
                                    <h5 class="mb-0">{{ number_format($clinicDetailedReport['totals']['clinic_net_amount'], 2) }}</h5>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table border-0 custom-table comman-table mb-0">
                                <thead>
                                <tr>
                                    <th>@lang('financial_reports.payment_method')</th>
                                    <th>@lang('financial_reports.settlement_direction')</th>
                                    <th>@lang('financial_reports.completed_bookings')</th>
                                    <th>@lang('financial_reports.total_collection')</th>
                                    <th>@lang('financial_reports.platform_commission')</th>
                                    <th>@lang('financial_reports.clinic_net')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($clinicDetailedReport['breakdown'] as $row)
                                    <tr>
                                        <td>{{ $paymentLabel($row['payment_method']) }}</td>
                                        <td>{{ $settlementDirectionLabel($row['settlement_direction']) }}</td>
                                        <td>{{ $row['bookings_count'] }}</td>
                                        <td>{{ number_format($row['total_collected'], 2) }}</td>
                                        <td>{{ number_format($row['platform_commission'], 2) }}</td>
                                        <td>{{ number_format($row['clinic_net_amount'], 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">@lang('financial_reports.no_detailed_data')</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card card-table show-entire">
                <div class="card-body">
                    <div class="page-table-header mb-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="doctor-table-blk">
                                    <h3>@lang('financial_reports.settlement_summary')</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table border-0 custom-table comman-table mb-0">
                            <thead>
                            <tr>
                                <th>@lang('financial_reports.clinic_name')</th>
                                <th>@lang('financial_reports.completed_bookings')</th>
                                <th>@lang('financial_reports.total_collection')</th>
                                <th>@lang('financial_reports.platform_commission')</th>
                                <th>@lang('financial_reports.clinic_net')</th>
                                <th>@lang('financial_reports.clinic_pays_platform')</th>
                                <th>@lang('financial_reports.platform_pays_clinic')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($clinicSummaries as $summary)
                                <tr>
                                    <td>{{ $summary['clinic_name'] }}</td>
                                    <td>{{ $summary['completed_bookings_count'] }}</td>
                                    <td>{{ number_format($summary['total_collected'], 2) }}</td>
                                    <td>{{ number_format($summary['platform_commission'], 2) }}</td>
                                    <td>{{ number_format($summary['clinic_net_amount'], 2) }}</td>
                                    <td>{{ number_format($summary['clinic_due_to_platform'], 2) }}</td>
                                    <td>{{ number_format($summary['platform_due_to_clinic'], 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">@lang('financial_reports.no_month_data')</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card card-table show-entire mt-4">
                <div class="card-body">
                    <div class="page-table-header mb-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="doctor-table-blk">
                                    <h3>@lang('financial_reports.completed_booking_details')</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table border-0 custom-table comman-table mb-0">
                            <thead>
                            <tr>
                                <th>@lang('financial_reports.booking_number')</th>
                                <th>@lang('financial_reports.clinic')</th>
                                <th>@lang('financial_reports.doctor')</th>
                                <th>@lang('financial_reports.patient')</th>
                                <th>@lang('financial_reports.payment_method')</th>
                                <th>@lang('financial_reports.visit_price')</th>
                                <th>@lang('financial_reports.platform_rate')</th>
                                <th>@lang('financial_reports.platform_commission')</th>
                                <th>@lang('financial_reports.clinic_net')</th>
                                <th>@lang('financial_reports.settlement_direction')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($reservations as $reservation)
                                <tr>
                                    <td>{{ $reservation->booking_number }}</td>
                                    <td>{{ optional($reservation->clinic)->name }}</td>
                                    <td>{{ optional($reservation->doctor)->name }}</td>
                                    <td>{{ optional($reservation->user)->name }}</td>
                                    <td>{{ $paymentLabel($reservation->payment_method) }}</td>
                                    <td>{{ number_format((float) $reservation->price, 2) }}</td>
                                    <td>{{ number_format((float) $reservation->platform_commission_rate, 2) }}%</td>
                                    <td>{{ number_format((float) $reservation->platform_commission_amount, 2) }}</td>
                                    <td>{{ number_format((float) $reservation->clinic_net_amount, 2) }}</td>
                                    <td>{{ $settlementDirectionLabel($reservation->settlement_direction) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">@lang('financial_reports.no_completed_bookings')</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $reservations->links() }}
                    </div>
                </div>
            </div>

    @if(in_array((int) auth()->user()->app_type, [1, 7, 11], true))
            </div>
        </div>
    @else
            </div>
        </div>
    @endif
@endsection
