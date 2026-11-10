@extends('includes_admin.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header d-flex justify-content-between align-items-center">
            <ul class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('clinic-deals.index') }}">@lang('deals.clinic_deals')</a></li>
                <li class="breadcrumb-item active">@lang('deals.deal_details')</li>
            </ul>
            <a href="{{ route('clinic-deals.index') }}" class="btn btn-light">@lang('deals.cancel')</a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        @if($deal->image)
                            <img src="{{ $deal->image }}" class="img-fluid rounded" alt="">
                        @endif
                    </div>
                    <div class="col-md-8">
                        <h3>{{ $deal->localizedTitle() }}</h3>
                        <p class="text-muted">{{ $deal->localizedDescription() }}</p>
                        <p><strong>@lang('deals.conditions_ar') / EN:</strong><br>{{ $deal->localizedConditions() }}</p>
                        <div class="row">
                            <div class="col-md-4"><strong>@lang('deals.price_before'):</strong> {{ number_format($deal->price_before, 2) }}</div>
                            <div class="col-md-4"><strong>@lang('deals.price_after'):</strong> {{ number_format($deal->price_after, 2) }}</div>
                            <div class="col-md-4"><strong>@lang('deals.status'):</strong> {{ $deal->status === 'active' ? __('deals.active') : __('deals.not_active') }}</div>
                            <div class="col-md-4 mt-2"><strong>@lang('deals.specialty'):</strong> {{ $deal->specialty->name_ar ?? '-' }}</div>
                            <div class="col-md-4 mt-2"><strong>@lang('deals.uses'):</strong> {{ $deal->number_of_uses }} / {{ $deal->maximum_number_of_usage ?? __('deals.unlimited') }}</div>
                            <div class="col-md-4 mt-2"><strong>@lang('deals.expiration_date'):</strong> {{ optional($deal->expiration_date)->format('Y-m-d') ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
