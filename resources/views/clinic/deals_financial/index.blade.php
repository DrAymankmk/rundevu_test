@extends('includes_admin.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                <li class="breadcrumb-item active">@lang('deals_financial.clinic_title')</li>
            </ul>
        </div>

        <form method="get" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">@lang('deals_financial.from')</label>
                    <input type="date" name="from" value="{{ $from }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">@lang('deals_financial.to')</label>
                    <input type="date" name="to" value="{{ $to }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">@lang('deals.settlement_status')</label>
                    <select name="settlement_status" class="form-control">
                        <option value="">@lang('deals.all')</option>
                        <option value="pending" {{ $settlementStatus === 'pending' ? 'selected' : '' }}>@lang('deals.pending')</option>
                        <option value="transfer_completed" {{ $settlementStatus === 'transfer_completed' ? 'selected' : '' }}>@lang('deals.transfer_completed')</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary w-100" type="submit">@lang('deals_financial.show_report')</button>
                </div>
            </div>
        </form>

        <div class="row mb-3">
            <div class="col-md-3"><div class="card"><div class="card-body"><p class="text-muted mb-1">@lang('deals_financial.total_sales')</p><h4>{{ number_format($totals['total_sales'], 2) }}</h4></div></div></div>
            <div class="col-md-3"><div class="card"><div class="card-body"><p class="text-muted mb-1">@lang('deals_financial.platform_entitlements')</p><h4>{{ number_format($totals['platform_entitlements'], 2) }}</h4></div></div></div>
            <div class="col-md-3"><div class="card"><div class="card-body"><p class="text-muted mb-1">@lang('deals_financial.clinic_dues')</p><h4>{{ number_format($totals['clinic_dues'], 2) }}</h4></div></div></div>
            <div class="col-md-3"><div class="card"><div class="card-body"><p class="text-muted mb-1">@lang('deals_financial.pending_clinic_dues')</p><h4>{{ number_format($totals['pending_clinic_dues'], 2) }}</h4></div></div></div>
        </div>

        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>@lang('deals_financial.deal_title')</th>
                        <th>@lang('deals_financial.price')</th>
                        <th>@lang('deals_financial.platform_commission')</th>
                        <th>@lang('deals_financial.net_due_clinic')</th>
                        <th>@lang('deals_financial.current_transfer_status')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($dealRows as $row)
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
                            </td>
                            <td>{{ number_format($row['clinic_net'], 2) }}</td>
                            <td>@include('clinic.deals_financial.partials.settlement_status', ['row' => $row])</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">@lang('deals.no_deals')</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
