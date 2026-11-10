@extends('layout_new.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                <li class="breadcrumb-item active">@lang('deals_financial.title')</li>
            </ul>
        </div>

        <form method="get" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">@lang('deals_financial.clinic')</label>
                    <select name="clinic_id" class="form-control">
                        <option value="">@lang('deals_financial.all_clinics')</option>
                        @foreach($clinics as $clinic)
                            <option value="{{ $clinic->id }}" {{ (string) $clinicId === (string) $clinic->id ? 'selected' : '' }}>{{ $clinic->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">@lang('deals_financial.from')</label>
                    <input type="date" name="from" value="{{ $from }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">@lang('deals_financial.to')</label>
                    <input type="date" name="to" value="{{ $to }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary w-100" type="submit">@lang('deals_financial.show_report')</button>
                </div>
            </div>
        </form>

        <div class="row">
            <div class="col-md-4 mb-3"><div class="card"><div class="card-body"><p class="text-muted mb-1">@lang('deals_financial.total_sales')</p><h4>{{ number_format($totals['total_sales'], 2) }}</h4></div></div></div>
            <div class="col-md-4 mb-3"><div class="card"><div class="card-body"><p class="text-muted mb-1">@lang('deals_financial.deals_sold') / @lang('deals_financial.deals_redeemed')</p><h4>{{ $totals['deals_sold'] }} / {{ $totals['deals_redeemed'] }}</h4></div></div></div>
            <div class="col-md-4 mb-3"><div class="card"><div class="card-body"><p class="text-muted mb-1">@lang('deals_financial.platform_entitlements')</p><h4>{{ number_format($totals['platform_entitlements'], 2) }}</h4></div></div></div>
            <div class="col-md-4 mb-3"><div class="card"><div class="card-body"><p class="text-muted mb-1">@lang('deals_financial.pending_clinic_dues')</p><h4>{{ number_format($totals['pending_clinic_dues'], 2) }}</h4></div></div></div>
            <div class="col-md-4 mb-3"><div class="card"><div class="card-body"><p class="text-muted mb-1">@lang('deals_financial.completed_clinic_dues')</p><h4>{{ number_format($totals['completed_clinic_dues'], 2) }}</h4></div></div></div>
            <div class="col-md-4 mb-3"><div class="card"><div class="card-body"><p class="text-muted mb-1">@lang('deals_financial.settlement')</p><h4>{{ $totals['pending_count'] }} @lang('deals_financial.pending') / {{ $totals['completed_count'] }} @lang('deals_financial.transfer_completed')</h4></div></div></div>
        </div>

        <div class="card mb-4">
            <div class="card-body table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>@lang('deals_financial.clinic')</th>
                        <th>@lang('deals_financial.total_sales')</th>
                        <th>@lang('deals_financial.deals_sold')</th>
                        <th>@lang('deals_financial.deals_redeemed')</th>
                        <th>@lang('deals_financial.platform_entitlements')</th>
                        <th>@lang('deals_financial.pending_clinic_dues')</th>
                        <th>@lang('deals.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($clinicSummaries as $row)
                        <tr>
                            <td>{{ $row['clinic_name'] }}</td>
                            <td>{{ number_format($row['totals']['total_sales'], 2) }}</td>
                            <td>{{ $row['totals']['deals_sold'] }}</td>
                            <td>{{ $row['totals']['deals_redeemed'] }}</td>
                            <td>{{ number_format($row['totals']['platform_entitlements'], 2) }}</td>
                            <td>{{ number_format($row['totals']['pending_clinic_dues'], 2) }}</td>
                            <td>
                                @if($row['totals']['pending_count'] > 0)
                                    <form method="post" action="{{ route('deals-financial.mark-settled') }}" onsubmit="return confirm('@lang('deals_financial.mark_clinic_settled')')">
                                        @csrf
                                        <input type="hidden" name="clinic_id" value="{{ $row['clinic_id'] }}">
                                        <input type="hidden" name="from" value="{{ $from }}">
                                        <input type="hidden" name="to" value="{{ $to }}">
                                        <button class="btn btn-sm btn-success" type="submit">@lang('deals_financial.mark_settled')</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">@lang('deals.no_deals')</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($clinicId)
            <div class="card mb-4">
                <div class="card-header"><strong>@lang('deals_financial.clinic_title')</strong></div>
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>@lang('deals_financial.deal_title')</th>
                            <th>@lang('deals_financial.price')</th>
                            <th>@lang('deals_financial.platform_commission')</th>
                            <th>@lang('deals_financial.net_due_clinic')</th>
                            <th>@lang('deals_financial.redeemed_count')</th>
                            <th>@lang('deals_financial.current_transfer_status')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($dealRows as $row)
                            <tr>
                                <td>{{ $row['deal']->localizedTitle() }}</td>
                                <td>{{ number_format($row['price'], 2) }}</td>
                                <td>
                                    @if($row['platform_type'] === 'percentage')
                                        {{ number_format($row['platform_amount'], 2) }}%
                                    @elseif($row['platform_type'] === 'fixed')
                                        {{ number_format($row['platform_amount'], 2) }}
                                    @else
                                        -
                                    @endif
                                    <div class="small text-muted">{{ number_format($row['platform_entitlements'], 2) }}</div>
                                </td>
                                <td>{{ number_format($row['clinic_net'], 2) }}</td>
                                <td>{{ $row['redeemed_count'] }}</td>
                                <td>@include('clinic.deals_financial.partials.settlement_status', ['row' => $row])</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>@lang('deals_financial.pending_redemptions')</strong>
            </div>
            <div class="card-body table-responsive">
                <form method="post" action="{{ route('deals-financial.mark-settled') }}">
                    @csrf
                    <table class="table">
                        <thead>
                        <tr>
                            <th></th>
                            <th>@lang('deals.deal_code')</th>
                            <th>@lang('deals.patient')</th>
                            <th>@lang('deals.clinic')</th>
                            <th>@lang('deals_financial.net_due_clinic')</th>
                            <th>@lang('deals.used_date_time')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($pendingPatientDeals as $item)
                            <tr>
                                <td><input type="checkbox" name="patient_deal_ids[]" value="{{ $item->id }}"></td>
                                <td>{{ $item->generated_code }}</td>
                                <td>{{ $item->patient->name ?? '-' }}</td>
                                <td>{{ $item->deal->clinic->name ?? '-' }}</td>
                                <td>{{ number_format($item->clinic_net, 2) }}</td>
                                <td>{{ optional($item->used_date_time)->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">@lang('deals.no_patient_deals')</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                    @if($pendingPatientDeals->count())
                        <button type="submit" class="btn btn-success">@lang('deals_financial.mark_settled')</button>
                    @endif
                </form>
                {{ $pendingPatientDeals->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
