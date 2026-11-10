@extends('includes_admin.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header d-flex justify-content-between">
            <ul class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('manage-deals.index') }}">@lang('deals.manage_deals')</a></li>
                <li class="breadcrumb-item active">@lang('deals.deal_details')</li>
            </ul>
            <div>
                @if(!$patientDeal->used)
                    <form action="{{ route('manage-deals.confirm', $patientDeal->id) }}" method="post" class="d-inline" onsubmit="return confirm('@lang('deals.confirm_use')')">
                        @csrf
                        <button type="submit" class="btn btn-success">@lang('deals.confirm')</button>
                    </form>
                @endif
                <a href="{{ route('manage-deals.index') }}" class="btn btn-light">@lang('deals.cancel')</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header"><strong>@lang('deals.patient')</strong></div>
                    <div class="card-body">
                        <p><strong>@lang('deals.patient'):</strong> {{ $patientDeal->patient->name ?? '-' }}</p>
                        <p><strong>@lang('deals.phone'):</strong> {{ $patientDeal->patient->phone ?? ($patientDeal->patient->mobile_number ?? '-') }}</p>
                        <p><strong>@lang('deals.deal_code'):</strong> <code>{{ $patientDeal->generated_code }}</code></p>
                        <p><strong>@lang('deals.claimed_at'):</strong> {{ optional($patientDeal->created_at)->format('Y-m-d H:i') }}</p>
                        <p><strong>@lang('deals.used'):</strong> {{ $patientDeal->used ? __('deals.used') : __('deals.unused') }}</p>
                        <p><strong>@lang('deals.used_date_time'):</strong> {{ optional($patientDeal->used_date_time)->format('Y-m-d H:i') ?? '-' }}</p>
                        <p><strong>@lang('deals.used_by'):</strong> {{ $patientDeal->usedBy->name ?? '-' }}</p>
                        <p><strong>@lang('deals.settlement_status'):</strong> {{ $patientDeal->settlement_status === 'transfer_completed' ? __('deals.transfer_completed') : __('deals.pending') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header"><strong>@lang('deals.deal_details')</strong></div>
                    <div class="card-body">
                        @if($patientDeal->deal?->image)
                            <img src="{{ $patientDeal->deal->image }}" style="max-height:120px" class="mb-3" alt="">
                        @endif
                        <h4>{{ $patientDeal->deal->localizedTitle() ?? '-' }}</h4>
                        <p>{{ $patientDeal->deal->localizedDescription() }}</p>
                        <p><strong>@lang('deals.conditions_ar'):</strong> {{ $patientDeal->deal->localizedConditions() }}</p>
                        <p><strong>@lang('deals.price_after'):</strong> {{ number_format($patientDeal->price_after ?? $patientDeal->deal->price_after, 2) }}</p>
                        <p><strong>@lang('deals_financial.platform_entitlements'):</strong> {{ number_format($patientDeal->platform_entitlement ?? 0, 2) }}</p>
                        <p><strong>@lang('deals_financial.net_due_clinic'):</strong> {{ number_format($patientDeal->clinic_net ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
