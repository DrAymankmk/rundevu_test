@extends('includes_admin.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header d-flex justify-content-between align-items-center">
            <ul class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                <li class="breadcrumb-item active">@lang('deals.manage_deals')</li>
            </ul>
            <a href="{{ route('manage-deals.assign') }}" class="btn btn-primary">@lang('deals.assign_deal')</a>
        </div>

        <form method="get" class="card mb-3">
            <div class="card-body row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">@lang('deals.search')</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="@lang('deals.patient') / @lang('deals.phone') / @lang('deals.deal_code')">
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('deals.status')</label>
                    <select name="used" class="form-control">
                        <option value="">@lang('deals.all')</option>
                        <option value="0" {{ request('used') === '0' ? 'selected' : '' }}>@lang('deals.unused')</option>
                        <option value="1" {{ request('used') === '1' ? 'selected' : '' }}>@lang('deals.used')</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('deals.select_deal')</label>
                    <select name="deal_id" class="form-control">
                        <option value="">@lang('deals.all')</option>
                        @foreach($deals as $deal)
                            <option value="{{ $deal->id }}" {{ (string) request('deal_id') === (string) $deal->id ? 'selected' : '' }}>{{ $deal->localizedTitle() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('deals.settlement_status')</label>
                    <select name="settlement_status" class="form-control">
                        <option value="">@lang('deals.all')</option>
                        <option value="pending" {{ request('settlement_status') === 'pending' ? 'selected' : '' }}>@lang('deals.pending')</option>
                        <option value="transfer_completed" {{ request('settlement_status') === 'transfer_completed' ? 'selected' : '' }}>@lang('deals.transfer_completed')</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label">@lang('deals_financial.from')</label>
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                </div>
                <div class="col-md-1">
                    <label class="form-label">@lang('deals_financial.to')</label>
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary w-100" type="submit">@lang('deals.search')</button>
                </div>
            </div>
        </form>

        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('deals.patient')</th>
                        <th>@lang('deals.phone')</th>
                        <th>@lang('deals.title')</th>
                        <th>@lang('deals.deal_code')</th>
                        <th>@lang('deals.claimed_at')</th>
                        <th>@lang('deals.used')</th>
                        <th>@lang('deals.used_date_time')</th>
                        <th>@lang('deals.settlement_status')</th>
                        <th>@lang('deals.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($patientDeals as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->patient->name ?? '-' }}</td>
                            <td>{{ $item->patient->phone ?? ($item->patient->mobile_number ?? '-') }}</td>
                            <td>{{ $item->deal->localizedTitle() ?? '-' }}</td>
                            <td><code>{{ $item->generated_code }}</code></td>
                            <td>{{ optional($item->created_at)->format('Y-m-d H:i') }}</td>
                            <td>{{ $item->used ? __('deals.used') : __('deals.unused') }}</td>
                            <td>{{ optional($item->used_date_time)->format('Y-m-d H:i') ?? '-' }}</td>
                            <td>{{ $item->settlement_status === 'transfer_completed' ? __('deals.transfer_completed') : __('deals.pending') }}</td>
                            <td class="text-nowrap">
                                <a href="{{ route('manage-deals.show', $item->id) }}" class="btn btn-sm btn-outline-primary">@lang('deals.details')</a>
                                @if(!$item->used)
                                    <form action="{{ route('manage-deals.confirm', $item->id) }}" method="post" class="d-inline" onsubmit="return confirm('@lang('deals.confirm_use')')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">@lang('deals.confirm')</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="text-center">@lang('deals.no_patient_deals')</td></tr>
                    @endforelse
                    </tbody>
                </table>
                {{ $patientDeals->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
