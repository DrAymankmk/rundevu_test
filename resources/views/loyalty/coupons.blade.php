@extends('includes_admin.mainlayout')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                            <li class="breadcrumb-item active">@lang('main.loyalty_coupons')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            @include('loyalty.partials.nav')

            <div class="card">
                <div class="card-body">
                    <p class="text-muted mb-3">@lang('main.loyalty_coupons_clinic_readonly_hint')</p>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('main.loyalty_service_name')</th>
                                <th>@lang('main.loyalty_coupon_branches')</th>
                                <th>@lang('main.loyalty_show_on_mobile_home')</th>
                                <th>@lang('main.loyalty_price_after_discount')</th>
                                <th>@lang('main.loyalty_expires_at')</th>
                                <th>@lang('admin.status')</th>
                                <th>@lang('admin.Actions')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($data['coupons'] as $coupon)
                                @php $locale = app()->getLocale(); @endphp
                                <tr>
                                    <td>{{ $coupon->id }}</td>
                                    <td>{{ $locale === 'ar' ? $coupon->service_name_ar : ($coupon->service_name_en ?: $coupon->service_name_ar) }}</td>
                                    <td>
                                        @if($coupon->appliesToAllBranches())
                                            <span class="text-muted">@lang('main.loyalty_coupon_branches_all')</span>
                                        @else
                                            @foreach($coupon->applicableBranches() as $branch)
                                                @php
                                                    $cityName = $locale === 'ar'
                                                        ? (optional($branch->city)->name_ar ?: optional($branch->city)->name_en)
                                                        : (optional($branch->city)->name_en ?: optional($branch->city)->name_ar);
                                                @endphp
                                                <div class="small">
                                                    {{ $branch->name }}
                                                    @if($cityName)
                                                        <span class="text-muted">({{ $cityName }})</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if($coupon->show_on_mobile_home)
                                            <span class="badge badge-success">@lang('main.loyalty_show_on_mobile_home_yes')</span>
                                        @else
                                            <span class="badge badge-secondary">@lang('main.loyalty_show_on_mobile_home_no')</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format((float) $coupon->price_after_discount, 2) }} @lang('main.loyalty_currency_sar')</td>
                                    <td>{{ $coupon->expires_at?->format('Y-m-d') }}</td>
                                    <td>{{ $coupon->status ? __('main.enabled') : __('main.disabled') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-secondary" data-toggle="modal"
                                                data-target="#showCoupon{{ $coupon->id }}">@lang('admin.show')</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">@lang('main.no_data')</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $data['coupons']->links() }}
                </div>
            </div>
        </div>
    </div>

    @foreach($data['coupons'] as $coupon)
        <div class="modal fade" id="showCoupon{{ $coupon->id }}" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('admin.show')</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        @include('loyalty.partials.coupon-details', ['coupon' => $coupon, 'exchange' => $data['exchange'] ?? null])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('admin.Close')</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
